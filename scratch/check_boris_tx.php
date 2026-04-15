<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\Transaction;

$user = User::where('username', 'Boris')->first();
if ($user) {
    echo "User ID: " . $user->id . "\n";
    $lastTx = Transaction::where('user_id', $user->id)->orderBy('id', 'desc')->first();
    if ($lastTx) {
        echo "Last Tx Reference: " . $lastTx->reference . "\n";
        echo "Last Tx Status: " . $lastTx->status . "\n";
        echo "Last Tx Type: " . $lastTx->type . "\n";
        echo "Last Tx Created At: " . $lastTx->created_at . "\n";
        echo "Gateway Ref: " . $lastTx->gateway_reference . "\n";
    } else {
        echo "No transactions found.\n";
    }
} else {
    echo "User Boris not found.\n";
}
