<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Hachther\MeSomb\Operation\Payment\Collect;

class ProcessMesombDeposit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $amountXAF;
    protected $operator;
    protected $country;
    protected $phone;

    public function __construct(Transaction $transaction, $amountXAF, $operator, $country, $phone)
    {
        $this->transaction = $transaction;
        $this->amountXAF   = $amountXAF;
        $this->operator    = $operator;
        $this->country     = $country;
        $this->phone       = $phone;
    }

    public function handle()
    {
        // 1. MODE LOCAL OU TESTING → SIMULATION INSTANTANÉE
        if (App::environment(['local', 'testing'])) {
            Log::info('DÉPÔT SIMULÉ (local)', [
                'transaction_id' => $this->transaction->id,
                'user_id'        => $this->transaction->user_id,
                'montant_usd'    => $this->transaction->montant,
                'montant_xaf'    => $this->amountXAF,
                'operator'       => $this->operator,
            ]);

            $fakeRef = 'SIMU_' . strtoupper(Str::random(10));

            $this->transaction->update([
                'status'            => 'successful',
                'gateway_reference' => $fakeRef,
                'completed_at'      => now(),
            ]);

            User::where('id', $this->transaction->user_id)
                ->increment('account_balance', $this->transaction->montant);

            return;
        }

        // 2. MODE PRODUCTION → VRAI PAIEMENT MESOMB
        try {
            $collect = new Collect(
                $this->phone,
                $this->amountXAF,
                $this->operator,
                $this->country
            );

            $payment = $collect->pay();

            // Succès immédiat
            if (isset($payment->status) && $payment->status === 'SUCCESS') {
                $this->transaction->update([
                    'status'            => 'successful',
                    'gateway_reference' => $payment->transaction->reference ?? null,
                    'completed_at'      => now(),
                ]);

                User::where('id', $this->transaction->user_id)
                    ->increment('account_balance', $this->transaction->montant);

                Log::info('Dépôt MeSomb réussi', ['ref' => $payment->transaction->reference]);
                return;
            }

            // Échec explicite
            if (isset($payment->status) && $payment->status === 'FAILED') {
                $reason = $payment->message ?? $payment->detail ?? 'Échec inconnu';
                $this->transaction->update([
                    'status'      => 'failed',
                    'description' => 'MeSomb : ' . $reason,
                ]);
                Log::warning('Dépôt échoué', ['reason' => $reason]);
                return;
            }

            // Sinon → pending (attente confirmation MeSomb)
            Log::info('Dépôt en attente de confirmation MeSomb', ['transaction_id' => $this->transaction->id]);

        } catch (\Exception $e) {
            Log::error('Erreur MeSomb dans ProcessMesombDeposit', [
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            $this->transaction->update([
                'status'      => 'failed',
                'description' => 'Erreur système MeSomb',
            ]);
        }
    }
}