<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('fmtCurrency')) {
    function fmtCurrency($value, $currency = null)
    {
        if (!$currency) {
            $currency = Auth::check() ? Auth::user()->currency : 'FCFA';
        }
        return number_format($value, 0, '.', ' ') . ' ' . $currency;
    }
}

if (!function_exists('fmtUsd')) {
    function fmtUsd($value)
    {
        return fmtCurrency($value);
    }
}

if (!function_exists('fmtXaf')) {
    function fmtXaf($value)
    {
        return fmtCurrency($value, 'XAF');
    }
}

if (!function_exists('fmtFcfa')) {
    function fmtFcfa($value)
    {
        return fmtCurrency($value);
    }
}