<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::whereNotNull('withdrawal_method')->first();
if ($user) {
    echo "User ID: " . $user->id . "\n";
    echo "Withdrawal Method: " . $user->withdrawal_method . "\n";
    $channel = config('notchpay.channels.' . strtoupper($user->withdrawal_method), 'cm.mtn');
    echo "Resolved Channel: " . $channel . "\n";
} else {
    echo "No user with withdrawal method found.\n";
}
