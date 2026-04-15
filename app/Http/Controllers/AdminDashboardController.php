<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Taux de conversion configurable (1 USD → X FCFA)
        $rateFCFAperUSD = env('USD_TO_XAF', 600);

        // ===================================================================
        // 1. STATISTIQUES GLOBALES (tout en USD)
        // ===================================================================
        $totalUsers       = User::count();
        $totalAdmins      = User::where('role', 'admin')->count();
        $activeToday      = User::whereDate('updated_at', today())->count();
        $pendingWithdrawals = Transaction::where('type', 'retrait')
                                         ->where('status', 'pending')
                                         ->count();

        // Sommes en USD (seulement les transactions validées)
        $totalDepotsUsd = (float) Transaction::where('type', 'depot')
                                             ->where('status', 'completed')
                                             ->sum('montant');

        $totalRetraitsUsd = (float) Transaction::where('type', 'retrait')
                                               ->where('status', 'completed')
                                               ->sum('montant');

        $totalBonusUsd = (float) Transaction::whereIn('type', ['bonus', 'bonus_vip', 'bonus_parrainage'])
                                            ->where('status', 'completed')
                                            ->sum('montant');

        // Revenu net de la plateforme
        $netRevenueUsd = $totalDepotsUsd - $totalRetraitsUsd - $totalBonusUsd;

        // Conversions en FCFA (arrondi propre)
        $totalDepotsFcfa   = (int) round($totalDepotsUsd * $rateFCFAperUSD);
        $totalRetraitsFcfa = (int) round($totalRetraitsUsd * $rateFCFAperUSD);
        $totalBonusFcfa    = (int) round($totalBonusUsd * $rateFCFAperUSD);
        $netRevenueFcfa    = (int) round($netRevenueUsd * $rateFCFAperUSD);

        // ===================================================================
        // 2. DONNÉES POUR LE GRAPHIQUE (30 derniers jours)
        // ===================================================================
        $dailyStats = Transaction::selectRaw("
                DATE(created_at) as date,
                COALESCE(SUM(CASE WHEN type = 'depot' AND status = 'completed' THEN montant ELSE 0 END), 0) as depots,
                COALESCE(SUM(CASE WHEN type = 'retrait' AND status = 'completed' THEN montant ELSE 0 END), 0) as retraits,
                COALESCE(SUM(CASE WHEN type LIKE 'bonus%' AND status = 'completed' THEN montant ELSE 0 END), 0) as bonus
            ")
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format pour Chart.js
        $chartLabels   = $dailyStats->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray();
        $chartDepots   = $dailyStats->pluck('depots')->toArray();
        $chartRetraits = $dailyStats->pluck('retraits')->toArray();
        $chartBonus    = $dailyStats->pluck('bonus')->toArray();

        // ===================================================================
        // 3. RÉPARTITION PAR NIVEAU VIP
        // ===================================================================
        $vipDistribution = User::select('level', DB::raw('count(*) as total'))
            ->groupBy('level')
            ->pluck('total', 'level')
            ->toArray();

        $vipChartLabels = [];
        $vipChartData   = [];
        $colors = ['#e5e7eb', '#d1fae5', '#86efac', '#34d399', '#10b981', '#059669', '#047857'];

        for ($i = 1; $i <= 10; $i++) {
            $count = $vipDistribution[$i] ?? 0;
            if ($count > 0 || $i <= 5) {
                $vipChartLabels[] = "VIP $i";
                $vipChartData[]   = $count;
            }
        }

        // ===================================================================
        // 4. DERNIERS UTILISATEURS & TRANSACTIONS
        // ===================================================================
        $recentUsers = User::select('id', 'username', 'level', 'created_at', 'account_balance')
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($user) use ($rateFCFAperUSD) {
                $balanceUsd = (float) ($user->account_balance ?? 0);
                $user->balance_usd  = $balanceUsd;
                $user->balance_fcfa = (int) round($balanceUsd * $rateFCFAperUSD);
                return $user;
            });

        $recentTransactions = Transaction::with(['user:id,username'])
            ->select('id', 'user_id', 'type', 'montant', 'status', 'created_at')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($tx) use ($rateFCFAperUSD) {
                $amountUsd = round((float) $tx->montant, 2);
                $tx->montant_usd  = $amountUsd;
                $tx->montant_fcfa = (int) round($amountUsd * $rateFCFAperUSD);
                return $tx;
            });

        // ===================================================================
        // 5. RETOUR VUE
        // ===================================================================
        return view('admin.dashboard', compact(
            // Stats principales
            'totalUsers',
            'totalAdmins',
            'activeToday',
            'pendingWithdrawals',

            // Montants USD
            'totalDepotsUsd',
            'totalRetraitsUsd',
            'totalBonusUsd',
            'netRevenueUsd',

            // Montants FCFA
            'totalDepotsFcfa',
            'totalRetraitsFcfa',
            'totalBonusFcfa',
            'netRevenueFcfa',

            // Taux
            'rateFCFAperUSD',

            // Graphiques
            'chartLabels',
            'chartDepots',
            'chartRetraits',
            'chartBonus',
            'vipChartLabels',
            'vipChartData',

            // Listes
            'recentUsers',
            'recentTransactions'
        ));
    }
}