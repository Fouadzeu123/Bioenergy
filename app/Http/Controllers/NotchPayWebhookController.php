<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\NotchPayService;

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
    protected NotchPayService $notchPay;

    public function __construct(NotchPayService $notchPay)
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

        Log::info('NotchPay Webhook: événement', ['type' => $type, 'data' => $event]);

        // ── 4. Router selon le type d'événement ──────────────────────────────
        switch ($type) {
            case 'payment.complete':
                $this->handlePaymentComplete($event);
                break;

            case 'payment.failed':
            case 'payment.canceled':
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
        $verification = $this->notchPay->verifyPayment($notchReference);

        if (!$verification['success'] || $verification['status'] !== 'complete') {
            Log::warning('NotchPay Webhook: vérification échouée ou statut non-complete', [
                'notch_reference' => $notchReference,
                'verification'    => $verification,
            ]);
            return;
        }

        // ── Trouver la transaction via gateway_reference (référence Notch Pay) ─
        $transaction = Transaction::where('gateway_reference', $notchReference)
                                  ->where('status', 'pending')
                                  ->first();

        // Fallback : chercher via notre référence interne stockée dans les metadata
        if (!$transaction) {
            $internalRef = $transactionData['merchant_reference']
                        ?? $transactionData['metadata']['internal_reference']
                        ?? null;

            if ($internalRef) {
                $transaction = Transaction::where('reference', $internalRef)
                                          ->where('status', 'pending')
                                          ->first();
            }
        }

        if (!$transaction) {
            Log::warning('NotchPay Webhook: transaction introuvable ou déjà traitée', [
                'notch_reference' => $notchReference,
            ]);
            return;
        }

        // ── Idempotence : éviter le double-crédit ──────────────────────────────
        if ($transaction->status !== 'pending') {
            Log::info('NotchPay Webhook: transaction déjà traitée', [
                'ref'    => $transaction->reference,
                'status' => $transaction->status,
            ]);
            return;
        }

        // ── Créditer l'utilisateur (dépôts seulement) ─────────────────────────
        if ($transaction->type === 'depot') {
            $montantUSD = $this->notchPay->xafToUsd($amountXAF > 0 ? $amountXAF : $this->notchPay->usdToXaf($transaction->montant));

            $user = User::find($transaction->user_id);
            if ($user) {
                $user->increment('account_balance', $montantUSD > 0 ? $montantUSD : $transaction->montant);
            }
        }

        // ── Mettre à jour la transaction ──────────────────────────────────────
        $transaction->update([
            'status'            => 'completed',
            'gateway_reference' => $notchReference,
        ]);

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

        $transaction = Transaction::where('gateway_reference', $notchReference)
                                  ->where('status', 'pending')
                                  ->first();

        if (!$transaction) {
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

        $transaction = Transaction::where('gateway_reference', $notchReference)
                                  ->where('type', 'retrait')
                                  ->where('status', 'pending')
                                  ->first();

        if (!$transaction) return;

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

        $transaction = Transaction::where('gateway_reference', $notchReference)
                                  ->where('type', 'retrait')
                                  ->where('status', 'pending')
                                  ->first();

        if (!$transaction) return;

        $transaction->update(['status' => 'failed']);

        // Le transfert a échoué, on recrédite le solde de l'utilisateur
        $user = User::find($transaction->user_id);
        if ($user) {
            $user->increment('account_balance', $transaction->montant);
        }

        Log::info('NotchPay Webhook: retrait échoué (remboursement effetué)', [
            'ref' => $transaction->reference,
            'montant_usd' => $transaction->montant
        ]);
    }
}
