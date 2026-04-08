<?php

if (!function_exists('fmtUsd')) {
    function fmtUsd($value)
    {
        return number_format($value, 2, '.', ',') . ' $';
    }
}

if (!function_exists('fmtXaf')) {
    function fmtXaf($value)
    {
        return number_format(round($value), 0, ',', ' ') . ' F';
    }
}

if (!function_exists('fmtFcfa')) { // alias si tu l'utilises
    function fmtFcfa($value)
    {
        return fmtXaf($value);
    }
}