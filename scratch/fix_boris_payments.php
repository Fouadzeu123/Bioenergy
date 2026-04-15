<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Transaction;
use App\Services\NotchPayService;

$boris = User::where('username', 'Boris')->first();
if (!$boris) {
    die("User Boris not found.\n");
}

$txs = Transaction::where('user_id', $boris->id)
    ->where('status', 'pending')
    ->where('type', 'depot')
    ->get();

$service = new NotchPayService();

foreach ($txs as $tx) {
    echo "Verifying Tx {$tx->reference}... ";
    if (!$tx->gateway_reference) {
        echo "No gateway reference. Skipping.\n";
        continue;
    }

    try {
        $check = $service->verifyPayment($tx->gateway_reference);
        if ($check['success'] && ($check['status'] ?? '') === 'complete') {
            $tx->update(['status' => 'completed']);
            $boris->increment('account_balance', $tx->montant);
            echo "VALIDATED and CREDITED!\n";
        } else {
            echo "Still pending or failed on NotchPay (Status: " . ($check['status'] ?? 'unknown') . ").\n";
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
