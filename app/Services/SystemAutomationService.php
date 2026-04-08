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
    protected function processGains(User $user)
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
            $startDate = $order->last_gain_at ? Carbon::parse($order->last_gain_at)->addDay() : Carbon::parse($order->start_date);
            $endDate = Carbon::yesterday(); // We process full days that have passed

            // If start date is today or in the future, nothing to process yet for "passed days"
            if ($startDate->isAfter($endDate)) {
                continue;
            }

            $currentDate = $startDate->copy();
            $totalGain = 0;
            $daysProcessed = 0;

            while ($currentDate->lte($endDate)) {
                // Skip Sundays as per original logic
                if (!$currentDate->isSunday()) {
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
                                'description' => 'Gain journalier (' . $currentDate->format('d/m/Y') . ') : ' . ($order->produit->name ?? 'Produit'),
                                'created_at'  => $currentDate->copy()->setTime(12, 0, 0), // Use the day it belongs to for records
                            ]);

                            $this->attribuerBonusParrainage($user, $gain, $currentDate);
                            
                            DB::commit();
                            $totalGain += $gain;
                            $daysProcessed++;
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error("Failed to process gain for User {$user->id}, Order {$order->id} on {$currentDate->toDateString()}: " . $e->getMessage());
                        }
                    }
                }
                $currentDate->addDay();
            }

            if ($daysProcessed > 0 || $order->last_gain_at === null) {
                $order->update(['last_gain_at' => Carbon::yesterday()]);
            }
        }
    }

    /**
     * Process matured savings/preservations.
     */
    protected function processRefunds(User $user)
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
                    'description' => "Bonus journalier niv {$b['level']} (" . $date->format('d/m/Y') . ") grâce à {$filleul->username}",
                    'created_at'  => $date->copy()->setTime(12, 0, 1),
                ]);
            }
        }
    }
}
