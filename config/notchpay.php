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
    | Canaux Mobile Money par pays et opérateur
    |--------------------------------------------------------------------------
    | Cameroun (CM) : MTN, ORANGE
    | Côte d'Ivoire (CI) : MTN, ORANGE, MOOV
    */
    'channels' => [
        'CM' => [
            'MTN'    => 'cm.mtn',
            'ORANGE' => 'cm.orange',
        ],
        'CI' => [
            'MTN'    => 'ci.mtn',
            'ORANGE' => 'ci.orange',
            'MOOV'   => 'ci.moov',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Canal générique pour les bénéficiaires (Payouts/Retraits)
    |--------------------------------------------------------------------------
    | NotchPay préfère un canal générique "mobile" pour la création de bénéficiaires.
    */
    'beneficiary_channels' => [
        'CM' => 'cm.mobile',
        'CI' => 'ci.mobile',
    ],

    /*
    |--------------------------------------------------------------------------
    | Indicatifs téléphoniques par pays
    |--------------------------------------------------------------------------
    */
    'country_phone_codes' => [
        'CM' => '237',
        'CI' => '225',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nom des pays (pour l'affichage)
    |--------------------------------------------------------------------------
    */
    'country_names' => [
        'CM' => 'Cameroun',
        'CI' => "Côte d'Ivoire",
    ],

    /*
    |--------------------------------------------------------------------------
    | Timeout des requêtes HTTP (secondes)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('NOTCHPAY_TIMEOUT', 60),

];