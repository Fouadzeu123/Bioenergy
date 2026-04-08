<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MesombWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Récupère la transaction MeSomb
        $status       = $request->input('status');           // SUCCESS ou FAILED
        $reference    = $request->input('reference');        // DEP-1-1738...
        $transaction  = $request->input('transaction');
        $amount       = $transaction['amount'] ?? 0;         // en centimes
        $service      = $transaction['service'] ?? null;

        // Sécurité : on ignore tout ce qui n'est pas SUCCESS
        if ($status !== 'SUCCESS') {
            Log::info('MeSomb webhook ignoré', $request->all());
            return response('OK', 200);
        }

        // 2. Trouve la transaction dans ta base via la référence
        $depot = Transaction::where('reference', $reference)
                            ->where('type', 'depot')
                            ->where('status', 'pending')
                            ->first();

        if (!$depot) {
            Log::warning('Dépôt non trouvé ou déjà crédité', ['ref' => $reference]);
            return response('OK', 200);
        }

        // 3. Crédite le solde (on convertit XAF → USD)
        $montantUSD = (int) round($amount / config('mesomb.usd_to_xaf', 600));

        $user = User::find($depot->user_id);
        $user->increment('account_balance', $montantUSD);

        // 4. Marque la transaction comme complétée
        $depot->update([
            'status'      => 'completed',
            'operator'    => $service,
            'montant'     => $montantUSD, // on met à jour avec le montant réel reçu
        ]);

        Log::info('Dépôt crédité via webhook MeSomb', [
            'user_id' => $user->id,
            'reference' => $reference,
            'montant_usd' => $montantUSD,
        ]);

        return response('OK', 200);
    }
}