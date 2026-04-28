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
        // 🚫 Ne pas exécuter le dimanche
        if (Carbon::now()->isSunday()) {
            $this->info('⏭️ Pas de gains journaliers le dimanche.');
            return 0;
        }

        // 🔹 Récupérer toutes les commandes actives
        $orders = Order::with([
                'user.parrain.parrain', // jusqu’au niveau 3
                'produit',
            ])
            ->whereDate('end_date', '>=', Carbon::today())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Aucune commande active.');
            return 0;
        }

        // 🔄 Parcourir les commandes
        foreach ($orders as $order) {
            $user    = $order->user;
            $produit = $order->produit;

            if (!$produit) {
                $this->warn("⚠️ Produit introuvable pour la commande #{$order->id}");
                continue;
            }

            // ⚡ Utiliser le revenu journalier du produit
            $gain = (float) $order->day_income;

            if ($gain <= 0) {
                $this->warn("⚠️ Gain invalide pour la commande #{$order->id}");
                continue;
            }

            DB::beginTransaction();
            try {
                // ➕ Créditer le filleul
                $user->increment('account_balance', $gain);

                Transaction::create([
                    'user_id'     => $user->id,
                    'type'        => 'gain_journalier',
                    'montant'     => $gain,
                    'order_id'=>$order->id,
                    'status'      => 'completed',
                    'reference'   => uniqid('GAIN-'),
                    'description' => 'Gain journalier du produit : ' . ($produit->name ?? $produit->nom ?? 'Produit'),
                ]);

                // 🎁 Bonus multi-niveaux
                $this->attribuerBonusParrainage($user, $gain);

                DB::commit();
                $this->info("✅ Gain de {$gain} $ attribué à {$user->phone} (commande #{$order->id})");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("❌ Erreur commande #{$order->id} : {$e->getMessage()}");
            }
        }

        $this->info('✅ Gains journaliers et bonus attribués.');
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