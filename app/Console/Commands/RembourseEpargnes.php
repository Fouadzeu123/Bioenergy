<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Epargne;
use App\Models\Transaction;
use Carbon\Carbon;

class RembourseEpargnes extends Command
{
    protected $signature = 'preservation:rembourser';
    protected $description = 'Rembourse automatiquement les épargnes arrivées à échéance';

    public function handle()
    {
        $epargnes = Epargne::where('end_date', '<=', Carbon::today())
            ->where('is_closed', false)
            ->with('user', 'preservation')
            ->get();

        if ($epargnes->isEmpty()) {
            $this->info('Aucune épargne à rembourser aujourd’hui.');
            return;
        }

        foreach ($epargnes as $epargne) {
            $user = $epargne->user;
            $total = $epargne->amount + $epargne->revenu_attendu;

            // Créditer le solde utilisateur
            $user->increment('account_balance', $total);

            // Enregistrer la transaction
            Transaction::create([
                'user_id'     => $user->id,
                'type'        => 'remboursement_preservation',
                'montant'     => $total,
                'status'      => 'completed',
                'reference'   => uniqid('REM-'),
                'description' => "Remboursement du capital + revenu pour {$epargne->preservation->name}",
            ]);

            // Marquer comme terminé
            $epargne->is_closed = true;
            $epargne->save();

            $this->info("✅ Remboursé : {$user->name} - {$total} $");
        }

        $this->info('✅ Tous les remboursements ont été effectués.');
    }
}
