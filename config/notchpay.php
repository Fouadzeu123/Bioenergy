<?php

return [

    'public_key'     => env('NOTCHPAY_PUBLIC_KEY'),
    'private_key'    => env('NOTCHPAY_PRIVATE_KEY'),
    'webhook_secret' => env('NOTCHPAY_WEBHOOK_SECRET'),
    'currency'       => env('NOTCHPAY_CURRENCY', 'XAF'),
    'api_url'        => env('NOTCHPAY_API_URL', 'https://api.notchpay.co'),
    'sandbox'        => env('NOTCHPAY_SANDBOX', false),
    'usd_to_xaf'     => env('USD_TO_XAF', 600),
    'timeout'        => env('NOTCHPAY_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | Canaux Mobile Money par pays et opérateur
    |--------------------------------------------------------------------------
    */
    'channels' => [
        'CM' => ['MTN'      => 'cm.mtn',       'ORANGE'    => 'cm.orange'],
        'CI' => ['MTN'      => 'ci.mtn',       'ORANGE'    => 'ci.orange',  'MOOV' => 'ci.moov', 'WAVE' => 'ci.wave'],
        'SN' => ['ORANGE'   => 'sn.orange',    'WAVE'      => 'sn.wave',    'FREE' => 'sn.free'],
        'BF' => ['ORANGE'   => 'bf.orange',    'MOOV'      => 'bf.moov'],
        'BJ' => ['MTN'      => 'bj.mtn',       'MOOV'      => 'bj.moov'],
        'TG' => ['TOGOCEL'  => 'tg.togocel',   'MOOV'      => 'tg.moov'],
        'ML' => ['ORANGE'   => 'ml.orange',    'MOOV'      => 'ml.moov'],
        'GH' => ['MTN'      => 'gh.mtn',       'VODAFONE'  => 'gh.vodafone', 'AIRTELTIGO' => 'gh.airteltigo'],
        'GN' => ['ORANGE'   => 'gn.orange',    'MTN'       => 'gn.mtn'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Canal générique pour les bénéficiaires (Payouts/Retraits)
    |--------------------------------------------------------------------------
    */
    'beneficiary_channels' => [
        'CM' => 'cm.mobile',
        'CI' => 'ci.mobile',
        'SN' => 'sn.mobile',
        'BF' => 'bf.mobile',
        'BJ' => 'bj.mobile',
        'TG' => 'tg.mobile',
        'ML' => 'ml.mobile',
        'GH' => 'gh.mobile',
        'GN' => 'gn.mobile',
    ],

    /*
    |--------------------------------------------------------------------------
    | Indicatifs téléphoniques par pays
    |--------------------------------------------------------------------------
    */
    'country_phone_codes' => [
        'CM' => '237',
        'CI' => '225',
        'SN' => '221',
        'BF' => '226',
        'BJ' => '229',
        'TG' => '228',
        'ML' => '223',
        'GH' => '233',
        'GN' => '224',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mapping indicatif → code pays (inverse de country_phone_codes)
    |--------------------------------------------------------------------------
    */
    'phone_to_country' => [
        '237' => 'CM',
        '225' => 'CI',
        '221' => 'SN',
        '226' => 'BF',
        '229' => 'BJ',
        '228' => 'TG',
        '223' => 'ML',
        '233' => 'GH',
        '224' => 'GN',
    ],

    /*
    |--------------------------------------------------------------------------
    | Devise par pays
    |--------------------------------------------------------------------------
    */
    'currencies' => [
        'CM' => 'XAF',
        'CI' => 'XOF',
        'SN' => 'XOF',
        'BF' => 'XOF',
        'BJ' => 'XOF',
        'TG' => 'XOF',
        'ML' => 'XOF',
        'GH' => 'GHS',
        'GN' => 'GNF',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nom des pays (pour l'affichage)
    |--------------------------------------------------------------------------
    */
    'country_names' => [
        'CM' => 'Cameroun',
        'CI' => "Côte d'Ivoire",
        'SN' => 'Sénégal',
        'BF' => 'Burkina Faso',
        'BJ' => 'Bénin',
        'TG' => 'Togo',
        'ML' => 'Mali',
        'GH' => 'Ghana',
        'GN' => 'Guinée',
    ],

    /*
    |--------------------------------------------------------------------------
    | Drapeaux emoji par pays
    |--------------------------------------------------------------------------
    */
    'country_flags' => [
        'CM' => '🇨🇲',
        'CI' => '🇨🇮',
        'SN' => '🇸🇳',
        'BF' => '🇧🇫',
        'BJ' => '🇧🇯',
        'TG' => '🇹🇬',
        'ML' => '🇲🇱',
        'GH' => '🇬🇭',
        'GN' => '🇬🇳',
    ],

];