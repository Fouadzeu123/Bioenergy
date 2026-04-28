<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Epargne;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SystemAutomationService
{
    /**
     * Process all pending gains and refunds for a specific user.
     */
    public function processForUser(User $user)
    {
        $this->processGains($user);
        $this->processRefunds($user);
    }

    /**
     * Calculate and attribute daily gains missed since last check.
     */
    public function processGains(User $user)
    {
        $orders = Order::where('user_id', $user->id)
            ->whereDate('end_date', '>=', Carbon::today())
            ->where(function ($query) {
                $query->whereNull('last_gain_at')
                      ->orWhereDate('last_gain_at', '<', Carbon::today());
            })
            ->with('produit')
            ->get();

        foreach ($orders as $order) {
            $today = Carbon::today()->startOfDay();

            // Si la date de début est aujourd'hui ou dans le futur, on ne peut rien réclamer
            if (!Carbon::parse($order->start_date)->startOfDay()->lt($today)) {
                continue;
            }

            $validGainDay = !$today->isSunday() && 
                            ($order->last_gain_at === null || Carbon::parse($order->last_gain_at)->startOfDay()->lt($today));

            if ($validGainDay) {
                $gain = (float) $order->day_income;
                if ($gain > 0) {
                    DB::beginTransaction();
                    try {
                        $user->increment('account_balance', $gain);

                        Transaction::create([
                            'user_id'     => $user->id,
                            'type'        => 'gain_journalier',
                            'montant'     => $gain,
                            'order_id'    => $order->id,
                            'status'      => 'completed',
                            'reference'   => uniqid('GAIN-'),
                            'description' => 'Gain journalier (' . $today->format('d/m/Y') . ') : ' . ($order->produit->name ?? 'Produit'),
                            'created_at'  => $today->copy()->setTime(12, 0, 0),
                        ]);

                        $this->attribuerBonusParrainage($user, $gain, $today);
                        
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Failed to process gain for User {$user->id}, Order {$order->id} on {$today->toDateString()}: " . $e->getMessage());
                    }
                }
            }

            // Mettre à jour last_gain_at à "aujourd'hui" pour s'assurer que les jours passés non réclamés sont perdus
            $order->update(['last_gain_at' => $today]);
        }
    }

    /**
     * Process matured savings/preservations.
     */
    public function processRefunds(User $user)
    {
        $epargnes = Epargne::where('user_id', $user->id)
            ->where('end_date', '<=', Carbon::today())
            ->where('is_closed', false)
            ->with('preservation')
            ->get();

        foreach ($epargnes as $epargne) {
            DB::beginTransaction();
            try {
                $total = $epargne->amount + $epargne->revenu_attendu;
                $user->increment('account_balance', $total);

                Transaction::create([
                    'user_id'     => $user->id,
                    'type'        => 'remboursement_preservation',
                    'montant'     => $total,
                    'status'      => 'completed',
                    'reference'   => uniqid('REM-'),
                    'description' => "Remboursement du capital + revenu pour {$epargne->preservation->name}",
                ]);

                $epargne->update(['is_closed' => true]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to refund Epargne {$epargne->id} for User {$user->id}: " . $e->getMessage());
            }
        }
    }

    private function attribuerBonusParrainage($filleul, float $gain, Carbon $date): void
    {
        $niveau1 = $filleul->parrain;
        $niveau2 = $niveau1 ? $niveau1->parrain : null;
        $niveau3 = $niveau2 ? $niveau2->parrain : null;

        $bonuses = [
            ['user' => $niveau1, 'rate' => 0.05, 'level' => 1],
            ['user' => $niveau2, 'rate' => 0.02, 'level' => 2],
            ['user' => $niveau3, 'rate' => 0.01, 'level' => 3],
        ];

        foreach ($bonuses as $b) {
            if ($b['user']) {
                $bonusAmount = round($gain * $b['rate'], 2);
                $b['user']->increment('account_balance', $bonusAmount);

                Transaction::create([
                    'user_id'     => $b['user']->id,
                    'type'        => 'bonus_journalier',
                    'montant'     => $bonusAmount,
                    'status'      => 'completed',
                    'from_user_id'=> $filleul->id,
                    'reference'   => uniqid('BONJ-'),
                    'description' => "Bonus journalier niv {$b['level']} (" . $date->format('d/m/Y') . ") grâce à {$filleul->phone}",
                    'created_at'  => $date->copy()->setTime(12, 0, 1),
                ]);
            }
        }
    }
}
