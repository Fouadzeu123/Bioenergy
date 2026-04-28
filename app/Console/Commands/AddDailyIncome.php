<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Notification;
use Carbon\Carbon;

class AddDailyIncome extends Command
{
    /**
     * Nom de la commande artisan
     */
    protected $signature = 'gains:journalier';

    /**
     * Description de la commande
     */
    protected $description = 'Attribuer automatiquement les gains journaliers et les bonus de parrainage multi-niveaux (hors dimanche)';

    /**
     * Exécution de la commande
     */
    public function handle()
    {
        $this->info('Les gains sont maintenant récupérés manuellement par les utilisateurs via le bouton "Réclamer".');
        return 0;
    }

    /**
     * Attribuer les bonus de parrainage multi-niveaux
     */
    private function attribuerBonusParrainage($filleul, float $gain): void
    {
        // Relation parrain correctement définie: belongsTo(User::class, 'invited_by')
        $niveau1 = $filleul->parrain;
        $niveau2 = $niveau1 ? $niveau1->parrain : null;
        $niveau3 = $niveau2 ? $niveau2->parrain : null;

        // Niveau 1 → 5%
        if ($niveau1) {
            $bonus1 = round($gain * 0.05, 2);
            $niveau1->increment('account_balance', $bonus1);

            Transaction::create([
                'user_id'     => $niveau1->id,
                'type'        => 'bonus_journalier',
                'montant'     => $bonus1,
                'status'      => 'completed',
                'from_user_id'=> $filleul->id,
                'reference'   => uniqid('BONJ-'),
                'description' => "Bonus journalier niveau 1 (5%) grâce à {$filleul->phone}",
            ]);
        }

        // Niveau 2 → 2%
        if ($niveau2) {
            $bonus2 = round($gain * 0.02, 2);
            $niveau2->increment('account_balance', $bonus2);

            Transaction::create([
                'user_id'     => $niveau2->id,
                'type'        => 'bonus_journalier',
                'montant'     => $bonus2,
                'status'      => 'completed',
                'from_user_id'=> $filleul->id,
                'reference'   => uniqid('BONJ-'),
                'description' => "Bonus journalier niveau 2 (2%) grâce à {$filleul->phone}",
            ]);
        }

        // Niveau 3 → 1%
        if ($niveau3) {
            $bonus3 = round($gain * 0.01, 2);
            $niveau3->increment('account_balance', $bonus3);

            Transaction::create([
                'user_id'     => $niveau3->id,
                'type'        => 'bonus_journalier',
                'montant'     => $bonus3,
                'status'      => 'completed',
                'from_user_id'=> $filleul->id,
                'reference'   => uniqid('BONJ-'),
                'description' => "Bonus journalier niveau 3 (1%) grâce à {$filleul->phone}",
            ]);
        }
    }
}