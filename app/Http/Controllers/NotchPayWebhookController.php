<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\NotchPayPaymentProvider;

/**
 * NotchPayWebhookController
 *
 * Reçoit les notifications asynchrones (webhooks) de Notch Pay.
 *
 * Flux :
 *  1. Notch Pay envoie un POST JSON sur /notchpay/webhook
 *  2. On vérifie la signature HMAC-SHA256 (header X-Notch-Signature)
 *  3. On identifie l'événement (payment.complete / payment.failed / ...)
 *  4. On retrouve la transaction interne via notre référence (stockée en metadata ou dans gateway_reference)
 *  5. On effectue une vérification server-to-server pour confirmer le statut
 *  6. On met à jour la base de données (balance utilisateur, statut transaction)
 *  7. On répond 200 OK rapidement (Notch Pay n'attend pas plus de 30s)
 */
class NotchPayWebhookController extends Controller
{
    protected NotchPayPaymentProvider $notchPay;

    public function __construct(NotchPayPaymentProvider $notchPay)
    {
        $this->notchPay = $notchPay;
    }

    // =========================================================================
    // POINT D'ENTRÉE PRINCIPAL DU WEBHOOK
    // =========================================================================

    public function handle(Request $request): \Illuminate\Http\Response
    {
        // ── 1. Récupérer le payload brut (avant toute transformation Laravel) ──
        $payload   = $request->getContent();
        $signature = $request->header('X-Notch-Signature', '');

        Log::info('NotchPay Webhook reçu', [
            'signature' => $signature,
            'ip'        => $request->ip(),
        ]);

        // ── 2. Vérification de la signature ──────────────────────────────────
        if (!$this->notchPay->validateWebhookSignature($payload, $signature)) {
            Log::warning('NotchPay Webhook: signature invalide', [
                'ip'        => $request->ip(),
                'signature' => $signature,
            ]);
            return response('Signature invalide', 403);
        }

        // ── 3. Décoder le payload JSON ────────────────────────────────────────
        $event = $request->json()->all();
        $type  = $event['event'] ?? $event['type'] ?? null;

        Log::info('NotchPay Webhook: événement reçu', [
            'type' => $type,
            'notch_reference' => $event['data']['reference'] ?? 'N/A',
            'full_payload' => $event
        ]);

        // ── 4. Router selon le type d'événement ──────────────────────────────
        switch ($type) {
            case 'payment.complete':
                $this->handlePaymentComplete($event);
                break;

            case 'payment.failed':
            case 'payment.canceled':
            case 'payment.expired':
                $this->handlePaymentFailed($event);
                break;

            case 'transfer.complete':
                $this->handleTransferComplete($event);
                break;

            case 'transfer.failed':
            case 'transfer.canceled':
            case 'transfer.rejected':
                $this->handleTransferFailed($event);
                break;

            default:
                Log::info('NotchPay Webhook: événement ignoré', ['type' => $type]);
        }

        // ── 5. Toujours répondre 200 rapidement ──────────────────────────────
        return response('OK', 200);
    }

    // =========================================================================
    // PAIEMENT COMPLÉTÉ
    // =========================================================================

    protected function handlePaymentComplete(array $event): void
    {
        $transactionData  = $event['data'] ?? $event['transaction'] ?? [];
        $notchReference   = $transactionData['reference'] ?? null;
        $amountXAF        = (int) ($transactionData['amount'] ?? 0);

        if (!$notchReference) {
            Log::warning('NotchPay Webhook: référence manquante', ['event' => $event]);
            return;
        }

        // ── Vérification server-to-server (anti-spoofing) ─────────────────────
        $verification = $this->notchPay->verify($notchReference);

        if (!$verification->success) {
            Log::warning('NotchPay Webhook: vérification échouée ou statut non-complete', [
                'notch_reference' => $notchReference,
                'verification'    => $verification,
            ]);
            return;
        }

        // ── Recherche de la transaction de façon robuste ───────────────────────
        $transaction = Transaction::where('status', 'pending')
            ->where(function ($query) use ($transactionData, $notchReference) {
                // On essaie toutes les clés possibles envoyées par NotchPay
                $refs = array_filter([
                    $notchReference,
                    $transactionData['id'] ?? null,
                    $transactionData['merchant_reference'] ?? null,
                    $transactionData['metadata']['internal_reference'] ?? null
                ]);

                foreach ($refs as $ref) {
                    $query->orWhere('gateway_reference', $ref)
                          ->orWhere('reference', $ref);
                }
            })
            ->first();

        if (!$transaction) {
            Log::warning('NotchPay Webhook: transaction introuvable dans la DB', [
                'notch_reference'  => $notchReference,
                'merchant_ref'     => $transactionData['merchant_reference'] ?? 'void',
                'internal_ref_met' => $transactionData['metadata']['internal_reference'] ?? 'void'
            ]);
            return;
        }

        Log::info('NotchPay Webhook: transaction trouvée, mise à jour...', ['id' => $transaction->id, 'ref' => $transaction->reference]);

        // ── Idempotence : éviter le double-crédit ──────────────────────────────
        if ($transaction->status !== 'pending') {
            Log::info('NotchPay Webhook: transaction déjà traitée', [
                'ref'    => $transaction->reference,
                'status' => $transaction->status,
            ]);
            return;
        }

        // ── Idempotence : on n'augmente le solde que si la transaction était encore en 'pending'
        $updated = Transaction::where('id', $transaction->id)
            ->where('status', 'pending')
            ->update([
                'status'            => 'completed',
                'gateway_reference' => $notchReference,
            ]);

        if ($updated) {
            // ── Créditer l'utilisateur (dépôts seulement) ─────────────────────────
            if ($transaction->type === 'depot') {
                $user = User::find($transaction->user_id);
                if ($user) {
                    // NotchPay envoie le montant en unité locale (XAF/XOF). On utilise directement ce montant.
                    $finalAmount = ($amountXAF > 0) ? $amountXAF : $transaction->montant;
                    $user->increment('account_balance', $finalAmount);
                }
                Log::info('NotchPay Webhook: dépôt crédité avec succès', ['ref' => $transaction->reference]);
            }
        } else {
            Log::info('NotchPay Webhook: transaction déjà complétée par une autre source (polling/direct)', ['ref' => $transaction->reference]);
        }

        Log::info('NotchPay Webhook: dépôt crédité', [
            'user_id'         => $transaction->user_id,
            'reference'       => $transaction->reference,
            'notch_reference' => $notchReference,
            'montant_xaf'     => $amountXAF,
        ]);
    }

    // =========================================================================
    // PAIEMENT ÉCHOUÉ / ANNULÉ
    // =========================================================================

    protected function handlePaymentFailed(array $event): void
    {
        $transactionData = $event['data'] ?? $event['transaction'] ?? [];
        $notchReference  = $transactionData['reference'] ?? null;

        if (!$notchReference) {
            return;
        }

        $transaction = Transaction::where('status', 'pending')
            ->where(function ($query) use ($transactionData, $notchReference) {
                $refs = array_filter([
                    $notchReference,
                    $transactionData['id'] ?? null,
                    $transactionData['merchant_reference'] ?? null,
                    $transactionData['metadata']['internal_reference'] ?? null
                ]);

                foreach ($refs as $ref) {
                    $query->orWhere('gateway_reference', $ref)
                          ->orWhere('reference', $ref);
                }
            })
            ->first();

        if (!$transaction) {
            Log::warning('NotchPay Webhook Failed: transaction introuvable', ['event' => $event]);
            return;
        }

        $transaction->update(['status' => 'failed']);

        Log::info('NotchPay Webhook: paiement échoué/annulé', [
            'reference'       => $transaction->reference,
            'notch_reference' => $notchReference,
        ]);
    }

    // =========================================================================
    // TRANSFERT (RETRAIT) COMPLÉTÉ
    // =========================================================================
    protected function handleTransferComplete(array $event): void
    {
        $transferData   = $event['data'] ?? $event['transfer'] ?? [];
        $notchReference = $transferData['reference'] ?? null;

        if (!$notchReference) return;

        $transaction = Transaction::where('type', 'retrait')
            ->where('status', 'pending')
            ->where(function ($query) use ($transferData, $notchReference) {
                $refs = array_filter([
                    $notchReference,
                    $transferData['id'] ?? null,
                    $transferData['merchant_reference'] ?? null,
                    $transferData['metadata']['internal_reference'] ?? null
                ]);

                foreach ($refs as $ref) {
                    $query->orWhere('gateway_reference', $ref)
                          ->orWhere('reference', $ref);
                }
            })
            ->first();

        if (!$transaction) {
            Log::warning('NotchPay Transfer Webhook: transaction introuvable', ['event' => $event]);
            return;
        }

        $transaction->update(['status' => 'completed']);
        Log::info('NotchPay Webhook: retrait complété', ['ref' => $transaction->reference]);
    }

    // =========================================================================
    // TRANSFERT (RETRAIT) ÉCHOUÉ
    // =========================================================================
    protected function handleTransferFailed(array $event): void
    {
        $transferData   = $event['data'] ?? $event['transfer'] ?? [];
        $notchReference = $transferData['reference'] ?? null;

        if (!$notchReference) return;

        $transaction = Transaction::where('type', 'retrait')
            ->where('status', 'pending')
            ->where(function ($query) use ($transferData, $notchReference) {
                $refs = array_filter([
                    $notchReference,
                    $transferData['id'] ?? null,
                    $transferData['merchant_reference'] ?? null,
                    $transferData['metadata']['internal_reference'] ?? null
                ]);

                foreach ($refs as $ref) {
                    $query->orWhere('gateway_reference', $ref)
                          ->orWhere('reference', $ref);
                }
            })
            ->first();

        if (!$transaction) {
            Log::warning('NotchPay Transfer Webhook Failed: transaction introuvable', ['event' => $event]);
            return;
        }

        $transaction->update(['status' => 'failed']);

        // Le transfert a échoué, on recrédite le solde de l'utilisateur
        $user = User::find($transaction->user_id);
        if ($user) {
            $user->increment('account_balance', $transaction->montant);
        }

        Log::info('NotchPay Webhook: retrait échoué (remboursement effetué)', [
            'ref' => $transaction->reference,
            'montant' => $transaction->montant
        ]);
    }
}
