<?php

namespace App\Services;

use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;
use NotchPay\NotchPay;
use NotchPay\Payment;

class NotchPayPaymentProvider implements PaymentService
{
    public function __construct()
    {
        // Le SDK NotchPay (setApiKey) exige une clé commençant par 'pk.' ou 'sb.'
        // On utilise donc la clé publique pour satisfaire la validation du SDK.
        $publicKey = config('notchpay.public_key');
        if ($publicKey) {
            NotchPay::setApiKey($publicKey);
        }

        // On définit également la clé privée (sk...) si elle est disponible,
        // ce qui ajoute le header X-Grant aux requêtes (nécessaire pour certaines opérations).
        $privateKey = config('notchpay.private_key');
        if ($privateKey) {
            NotchPay::$privateKey = $privateKey;
        }
    }

    /**
     * Effectue une demande de paiement (Charge)
     */
    public function charge(Transaction $transaction, array $data)
    {
        try {
            if (empty($data['phone']) || empty($data['provider'])) {
                return (object) [
                    'success' => false,
                    'reference' => null,
                    'message' => 'Le numéro de téléphone et le service (opérateur) sont requis.',
                ];
            }

            // Récupération de la référence interne
            $reference = $transaction->reference;

            // Détermination du canal (ex: cm.orange, cm.mtn)
            $country  = strtolower($data['country'] ?? 'cm');
            $provider = strtolower($data['provider'] ?? 'orange');
            $channel  = "{$country}.{$provider}";

            // ─── Étape 1 : Initialiser la transaction ───────────────────────
            // On utilise le montant en FCFA stocké dans la transaction
            $paymentInit = Payment::initialize([
                'amount'      => (int) $transaction->montant_fcfa,
                'email'       => $transaction->user->email ?? "user_{$transaction->user_id}@bioenergy.cm",
                'currency'    => 'XAF',
                'reference'   => $reference,
                'description' => $transaction->description ?: "Dépôt BioEnergy",
            ]);

            $npReference = $paymentInit->transaction->reference ?? $reference;

            // ─── Étape 2 : Envoyer la demande de paiement au téléphone ──────
            try {
                $chargeResponse = Payment::charge($npReference, [
                    'channel'        => $channel,
                    'account_number' => $data['phone'],
                ]);

                // Statut de la réponse directe
                $status = strtolower($chargeResponse->transaction->status ?? $chargeResponse->status ?? 'pending');
                Log::info('NotchPay charge réussi immédiatement', ['status' => $status, 'ref' => $npReference]);

            } catch (\NotchPay\Exceptions\ApiException $chargeException) {
                // Le SDK jette une ApiException pour les réponses "incomplètes/pending" (push USSD envoyé)
                $errors  = $chargeException->errors ?? [];
                $status  = strtolower($errors['transaction']['status'] ?? $errors['status'] ?? 'error');
                $message = $errors['message'] ?? $chargeException->getMessage();

                Log::info('NotchPay charge exception capturée', [
                    'status'  => $status,
                    'message' => $message,
                    'ref'     => $npReference,
                    'errors'  => $errors,
                ]);

                if (in_array($status, ['incomplete', 'pending', 'processing'])) {
                    return (object) [
                        'success'    => false,
                        'is_pending' => true,
                        'reference'  => $npReference,
                        'message'    => 'Paiement en cours. Veuillez valider sur votre téléphone.',
                    ];
                }

                return (object) [
                    'success'   => false,
                    'reference' => null,
                    'message'   => $message ?: 'Impossible de traiter le paiement.',
                ];
            }

            // Réponse directe sans exception : traiter selon le statut
            if (in_array($status, ['complete', 'success', 'successful', 'approved'])) {
                return (object) [
                    'success'   => true,
                    'reference' => $npReference,
                    'message'   => 'Paiement effectué avec succès.',
                ];
            }

            if (in_array($status, ['failed', 'canceled', 'expired', 'rejected'])) {
                return (object) [
                    'success'   => false,
                    'reference' => $npReference,
                    'message'   => 'La transaction a échoué ou a été annulée.',
                ];
            }

            if (in_array($status, ['incomplete', 'pending', 'processing'])) {
                return (object) [
                    'success'    => false,
                    'is_pending' => true,
                    'reference'  => $npReference,
                    'message'    => 'Paiement en cours. Veuillez valider sur votre téléphone.',
                ];
            }

            return (object) [
                'success'   => false,
                'reference' => null,
                'message'   => 'Statut de paiement inconnu : ' . $status,
            ];

        } catch (\NotchPay\Exceptions\ApiException $e) {
            Log::error('Notch Pay API Exception (initialize): ' . $e->getMessage(), ['errors' => $e->errors ?? []]);
            return (object) [
                'success'   => false,
                'reference' => null,
                'message'   => 'Erreur de paiement : ' . $e->getMessage(),
            ];
        } catch (Exception $e) {
            Log::error('Erreur globale NotchPayPaymentProvider: ' . $e->getMessage());
            return (object) [
                'success'   => false,
                'reference' => null,
                'message'   => 'Le service de paiement est temporairement indisponible.',
            ];
        }
    }

    /**
     * Vérifie le statut d'une transaction
     */
    public function verify(string $reference)
    {
        try {
            $payment = Payment::verify($reference);
            $status = strtolower($payment->transaction->status ?? $payment->status ?? 'pending');

            return (object) [
                'status'     => $status,
                'success'    => in_array($status, ['complete', 'success', 'successful', 'approved']),
                'is_pending' => in_array($status, ['incomplete', 'pending', 'processing']),
            ];
        } catch (Exception $e) {
            Log::error('NotchPay verify error: ' . $e->getMessage());
            return (object) [
                'status'     => 'error',
                'success'    => false,
                'is_pending' => false,
            ];
        }
    }

    // =========================================================================
    // UTILITAIRES DE CONVERSION
    // =========================================================================

    public function usdToXaf(float $usd): int
    {
        return (int) round($usd * config('notchpay.usd_to_xaf', 600));
    }

    public function xafToUsd(int $xaf): float
    {
        $rate = config('notchpay.usd_to_xaf', 600);
        return round($xaf / $rate, 2);
    }

    // =========================================================================
    // WEBHOOK VALIDATION
    // =========================================================================

    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        $secret = config('notchpay.webhook_secret');
        if (empty($secret)) {
            Log::warning('NotchPay: NOTCHPAY_WEBHOOK_SECRET non configuré – validation sautée.');
            return true;
        }
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
    }

    // =========================================================================
    // RETRAITS (Payouts)
    // =========================================================================

    /**
     * Crée un bénéficiaire pour les retraits
     */
    public function createBeneficiary(string $name, string $phone, string $email, string $channel, string $country = 'CM'): array
    {
        // On peut utiliser le SDK ou Http si le SDK n'a pas encore de helper dédié
        // Mais on va essayer de rester sur la logique fonctionnelle existante si elle marche.
        try {
            // Note: Le SDK notchpay-php ne semble pas avoir de classe Beneficiary directe facilement accessible dans toutes les versions.
            // On peut utiliser NotchPay::request() ou continuer avec Http pour cette partie si on veut être sûr.
            // Dans le doute, on garde la logique Http qui fonctionnait dans NotchPayService.

            $publicKey = config('notchpay.public_key');
            $privateKey = config('notchpay.private_key');
            $apiUrl = rtrim(config('notchpay.api_url', 'https://api.notchpay.co'), '/');

            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $publicKey,
                    'X-Grant'       => $privateKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->post("{$apiUrl}/beneficiaries", [
                    'name'           => $name,
                    'phone'          => $phone,
                    'account_number' => $phone,
                    'email'          => $email,
                    'channel'        => $channel,
                    'country'        => $country,
                ]);

            $data = $response->json();

            if (!$response->successful()) {
                Log::error('NotchPay: Échec création bénéficiaire', ['response' => $data]);
                return ['success' => false, 'message' => $data['message'] ?? 'Erreur création bénéficiaire'];
            }

            return [
                'success'        => true,
                'beneficiary_id' => $data['beneficiary']['id'] ?? null,
                'message'        => 'Bénéficiaire créé avec succès',
            ];
        } catch (Exception $e) {
            Log::error('NotchPay: Exception création bénéficiaire', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Erreur technique interne.'];
        }
    }

    /**
     * Effectue un transfert (Retrait)
     */
    public function transfer(int $amountXAF, string $beneficiaryId, string $description, string $reference): array
    {
        try {
            $publicKey = config('notchpay.public_key');
            $privateKey = config('notchpay.private_key');
            $apiUrl = rtrim(config('notchpay.api_url', 'https://api.notchpay.co'), '/');

            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $publicKey,
                    'X-Grant'       => $privateKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->post("{$apiUrl}/transfers", [
                    'amount'      => $amountXAF,
                    'currency'    => config('notchpay.currency', 'XAF'),
                    'beneficiary' => $beneficiaryId,
                    'description' => $description,
                    'reference'   => $reference,
                ]);

            $data = $response->json();

            if (!$response->successful()) {
                Log::error('NotchPay: Échec transfert', ['response' => $data]);
                return ['success' => false, 'message' => $data['message'] ?? 'Erreur lors du transfert.'];
            }

            return [
                'success'         => true,
                'status'          => $data['transfer']['status'] ?? 'pending',
                'notch_reference' => $data['transfer']['reference'] ?? null,
                'message'         => 'Transfert initié avec succès.',
            ];
        } catch (Exception $e) {
            Log::error('NotchPay: Exception transfert', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Erreur technique interne lors du transfert.'];
        }
    }
}
