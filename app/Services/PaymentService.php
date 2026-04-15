<?php

namespace App\Services;

use App\Models\Transaction;

interface PaymentService
{
    public function charge(Transaction $transaction, array $data);
    public function verify(string $reference);
}
