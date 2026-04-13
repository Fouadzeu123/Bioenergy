<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notch Pay Public Key
    |--------------------------------------------------------------------------
    | Votre clé publique Notch Pay (commence par pk.)
    | Utilisée dans l'en-tête Authorization des requêtes API.
    */
    'public_key' => env('NOTCHPAY_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Notch Pay Private / Grant Key
    |--------------------------------------------------------------------------
    | Votre clé privée Notch Pay (commence par sk.)
    | Utilisée pour les opérations sensibles (ex: vérification serveur-à-serveur).
    */
    'private_key' => env('NOTCHPAY_PRIVATE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    | Utilisé pour vérifier la signature HMAC-SHA256 des webhooks entrants.
    | Configurable depuis le dashboard Notch Pay → Settings → Webhooks.
    */
    'webhook_secret' => env('NOTCHPAY_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    | Devise utilisée pour les paiements. XAF = Franc CFA (Cameroun).
    */
    'currency' => env('NOTCHPAY_CURRENCY', 'XAF'),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    */
    'api_url' => env('NOTCHPAY_API_URL', 'https://api.notchpay.co'),

    /*
    |--------------------------------------------------------------------------
    | Mode Sandbox / Production
    |--------------------------------------------------------------------------
    | true  → mode test (pas de transfert réel d'argent)
    | false → production réelle
    */
    'sandbox' => env('NOTCHPAY_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | Taux de conversion USD → XAF
    |--------------------------------------------------------------------------
    | Utilisé pour convertir les montants internes (USD) en XAF pour l'API.
    */
    'usd_to_xaf' => env('USD_TO_XAF', 600),

    /*
    |--------------------------------------------------------------------------
    | Canaux Mobile Money (opérateurs)
    |--------------------------------------------------------------------------
    | Correspondance entre le nom de l'opérateur et le canal Notch Pay.
    */
    'channels' => [
        'MTN'    => 'cm.mtn',
        'ORANGE' => 'cm.orange',
    ],

    /*
    |--------------------------------------------------------------------------
    | Timeout des requêtes HTTP (secondes)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('NOTCHPAY_TIMEOUT', 60),

];