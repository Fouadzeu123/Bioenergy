<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service d'intégration Notch Pay.
 *
 * Gère :
 *  - Initialisation d'un paiement (collect → l'utilisateur est redirigé ou push USSD)
 *  - Collect direct Mobile Money (MTN / ORANGE) via le canal cm.mtn / cm.orange
 *  - Vérification du statut d'un paiement (server-to-server)
 *  - Validation de la signature HMAC-SHA256 des webhooks
 */
class NotchPayService
{
    protected string $apiUrl;
    protected string $publicKey;
    protected string $privateKey;
    protected string $currency;
    protected int    $timeout;

    public function __construct()
    {
        $this->apiUrl     = rtrim(config('notchpay.api_url', 'https://api.notchpay.co'), '/');
        $this->publicKey  = config('notchpay.public_key');
        $this->privateKey = config('notchpay.private_key');
        $this->currency   = config('notchpay.currency', 'XAF');
        $this->timeout    = (int) config('notchpay.timeout', 60);
    }

    // =========================================================================
    // 1. INITIALISER UN PAIEMENT (Collect – utilisateur doit valider)
    // =========================================================================

    /**
     * Initialise un paiement Notch Pay et retourne la réponse brute.
     *
     * @param  float  $amountXAF   Montant en XAF
     * @param  string $email       Email du client
     * @param  string $phone       Numéro de téléphone (ex: +237690000000)
     * @param  string $reference   Référence interne unique (ex: DEP-1-1234)
     * @param  string $description Description de la transaction
     * @param  string $operator    Opérateur : MTN ou ORANGE
     * @return array               ['success' => bool, 'data' => ..., 'message' => ...]
     */
    public function initializePayment(
        float  $amountXAF,
        string $email,
        string $phone,
        string $reference,
        string $description,
        string $operator = 'MTN'
    ): array {
        try {
            // Étape 1 : Initialiser le paiement → obtenir une référence Notch Pay
            $initResponse = Http::withoutVerifying()->timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => $this->publicKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->post("{$this->apiUrl}/payments", [
                    'amount'      => (int) $amountXAF,
                    'currency'    => $this->currency,
                    'email'       => $email,
                    'phone'       => $phone,
                    'reference'   => $reference,
                    'description' => $description,
                    'locked'      => true, // Empêche de changer le montant
                ]);

            $initData = $initResponse->json();

            if (!$initResponse->successful()) {
                Log::error('NotchPay: Échec initialisation paiement', [
                    'status'    => $initResponse->status(),
                    'reference' => $reference,
                    'response'  => $initData,
                ]);
                return [
                    'success' => false,
                    'message' => $initData['message'] ?? 'Erreur lors de l\'initialisation du paiement.',
                    'data'    => $initData,
                ];
            }

            // La référence Notch Pay (différente de notre référence interne)
            $notchReference = $initData['transaction']['reference'] ?? null;

            if (!$notchReference) {
                return [
                    'success' => false,
                    'message' => 'Référence Notch Pay introuvable dans la réponse.',
                    'data'    => $initData,
                ];
            }

            // Étape 2 : Collecter directement via Mobile Money (push USSD)
            $channel = config('notchpay.channels.' . strtoupper($operator), 'cm.mtn');

            $collectResponse = Http::withoutVerifying()->timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => $this->publicKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->put("{$this->apiUrl}/payments/{$notchReference}", [
                    'channel' => $channel,
                    'data'    => [
                        'phone' => $phone,
                    ],
                ]);

            $collectData = $collectResponse->json();

            Log::info('NotchPay: Collect initié', [
                'reference'       => $reference,
                'notch_reference' => $notchReference,
                'channel'         => $channel,
                'collect_status'  => $collectData['status'] ?? null,
            ]);

            return [
                'success'         => true,
                'notch_reference' => $notchReference,
                'status'          => $collectData['transaction']['status'] ?? 'pending',
                'message'         => $collectData['message'] ?? 'Paiement initié.',
                'data'            => $collectData,
            ];

        } catch (\Exception $e) {
            Log::error('NotchPay: Exception paiement', [
                'reference' => $reference,
                'error'     => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Erreur de connexion au service de paiement.',
                'data'    => [],
            ];
        }
    }

    // =========================================================================
    // 2. VÉRIFIER LE STATUT D'UN PAIEMENT (server-to-server)
    // =========================================================================

    /**
     * Vérifie le statut d'un paiement auprès de l'API Notch Pay.
     *
     * @param  string $notchReference  La référence Notch Pay (pas la nôtre)
     * @return array  ['success', 'status', 'data', 'message']
     */
    public function verifyPayment(string $notchReference): array
    {
        try {
            $response = Http::withoutVerifying()->timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => $this->privateKey,
                    'Accept'        => 'application/json',
                ])
                ->get("{$this->apiUrl}/payments/{$notchReference}");

            $data = $response->json();

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'status'  => 'unknown',
                    'message' => $data['message'] ?? 'Impossible de vérifier le paiement.',
                    'data'    => $data,
                ];
            }

            return [
                'success' => true,
                'status'  => $data['transaction']['status'] ?? 'unknown',
                'message' => $data['message'] ?? '',
                'data'    => $data,
            ];

        } catch (\Exception $e) {
            Log::error('NotchPay: Exception vérification', [
                'notch_reference' => $notchReference,
                'error'           => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'status'  => 'error',
                'message' => 'Erreur lors de la vérification.',
                'data'    => [],
            ];
        }
    }

    // =========================================================================
    // 3. VALIDER LA SIGNATURE D'UN WEBHOOK
    // =========================================================================

    /**
     * Vérifie la signature HMAC-SHA256 d'un webhook Notch Pay.
     *
     * Notch Pay envoie le header : X-Notch-Signature
     * La signature = HMAC-SHA256(payload brut, webhook_secret)
     *
     * @param  string $payload    Corps brut de la requête (file_get_contents / $request->getContent())
     * @param  string $signature  Valeur du header X-Notch-Signature
     * @return bool
     */
    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        $secret = config('notchpay.webhook_secret');

        if (empty($secret)) {
            Log::warning('NotchPay: NOTCHPAY_WEBHOOK_SECRET non configuré – validation sautée.');
            return true; // En l'absence de secret, on accepte (à configurer en prod !)
        }

        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }

    // =========================================================================
    // 4. CONVERSION USD → XAF
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
    // 5. CRÉER UN BÉNÉFICIAIRE (Pour les retraits)
    // =========================================================================

    /**
     * Crée un bénéficiaire sur Notch Pay.
     *
     * @return array ['success', 'beneficiary_id', 'message']
     */
    public function createBeneficiary(string $name, string $phone, string $email, string $country = 'CM'): array
    {
        try {
            $response = Http::withoutVerifying()->timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => $this->publicKey,
                    'X-Grant'       => $this->privateKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->post("{$this->apiUrl}/beneficiaries", [
                    'name'           => $name,
                    'phone'          => $phone,
                    'email'          => $email,
                    'country'        => $country,
                ]);

            $data = $response->json();

            if (!$response->successful()) {
                Log::error('NotchPay: Échec création bénéficiaire', [
                    'status'   => $response->status(),
                    'response' => $data,
                ]);
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Erreur création bénéficiaire',
                ];
            }

            return [
                'success'        => true,
                'beneficiary_id' => $data['beneficiary']['id'] ?? null,
                'message'        => 'Bénéficiaire créé avec succès',
            ];
        } catch (\Exception $e) {
            Log::error('NotchPay: Exception création bénéficiaire', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Erreur technique interne.'];
        }
    }

    // =========================================================================
    // 6. EFFECTUER UN TRANSFERT (Retrait)
    // =========================================================================

    /**
     * Initie un transfert (payout) vers un bénéficiaire.
     *
     * @return array ['success', 'status', 'notch_reference', 'message']
     */
    public function transfer(int $amountXAF, string $beneficiaryId, string $description, string $reference): array
    {
        try {
            $payload = [
                'amount'      => $amountXAF,
                'currency'    => $this->currency,
                'beneficiary' => $beneficiaryId,
                'description' => $description,
                'reference'   => $reference,
            ];

            $response = Http::withoutVerifying()->timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => $this->publicKey,
                    'X-Grant'       => $this->privateKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->post("{$this->apiUrl}/transfers", $payload);

            $data = $response->json();

            if (!$response->successful()) {
                Log::error('NotchPay: Échec transfert', [
                    'status'   => $response->status(),
                    'reference'=> $reference,
                    'response' => $data,
                ]);
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Erreur lors du transfert.',
                ];
            }

            return [
                'success'         => true,
                'status'          => $data['transfer']['status'] ?? 'pending',
                'notch_reference' => $data['transfer']['reference'] ?? null,
                'message'         => 'Transfert initié avec succès.',
            ];
        } catch (\Exception $e) {
            Log::error('NotchPay: Exception transfert', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Erreur technique interne lors du transfert.'];
        }
    }
}
