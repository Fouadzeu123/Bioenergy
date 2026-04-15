<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Transaction;
use App\Services\NotchPayService;

$boris = User::where('username', 'Boris')->first();
$service = new NotchPayService();

$mappings = [
    'DEP-3-1776155739-S1kR' => 'trx.jsk00NTTgVbKKd2ubVumJfBW',
    'DEP-3-1776156390-v0zs' => 'trx.gLf4bKgltZau2PPKhE7GzH7n',
    'DEP-3-1776157031-1aRf' => 'trx.iZasaynRssIbUwsMkweqNMwR',
];

foreach ($mappings as $ref => $notchRef) {
    $tx = Transaction::where('reference', $ref)->first();
    if ($tx && !$tx->gateway_reference) {
        $tx->update(['gateway_reference' => $notchRef]);
        echo "Updated reference for $ref\n";
    }
}

$txs = Transaction::where('user_id', $boris->id)
    ->where('status', 'pending')
    ->where('type', 'depot')
    ->get();

foreach ($txs as $tx) {
    echo "Verifying Tx {$tx->reference}... ";
    if ($tx->gateway_reference) {
        $check = $service->verifyPayment($tx->gateway_reference);
        if ($check['success'] && ($check['status'] ?? '') === 'complete') {
            $tx->update(['status' => 'completed']);
            $boris->increment('account_balance', $tx->montant);
            echo "VALIDATED and CREDITED!\n";
        } else {
            echo "Status: " . ($check['status'] ?? 'unknown') . "\n";
        }
    } else {
        echo "No reference.\n";
    }
}
