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
        // ===================================================================
        // 1. STATISTIQUES GLOBALES (en unité locale brute)
        // ===================================================================
        $totalUsers       = User::count();
        $totalAdmins      = User::where('role', 'admin')->count();
        $activeToday      = User::whereDate('updated_at', today())->count();
        $pendingWithdrawals = Transaction::where('type', 'retrait')
                                         ->where('status', 'pending')
                                         ->count();

        // Sommes (seulement les transactions validées)
        $totalDepots = (float) Transaction::where('type', 'depot')
                                             ->where('status', 'completed')
                                             ->sum('montant');

        $totalRetraits = (float) Transaction::where('type', 'retrait')
                                               ->where('status', 'completed')
                                               ->sum('montant');

        $totalBonus = (float) Transaction::whereIn('type', ['bonus', 'bonus_vip', 'bonus_parrainage', 'bonus_journalier', 'parrainage'])
                                            ->where('status', 'completed')
                                            ->sum('montant');

        // Revenu net de la plateforme
        $netRevenue = $totalDepots - $totalRetraits - $totalBonus;

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

        for ($i = 0; $i <= 10; $i++) {
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
            ->get();

        $recentTransactions = Transaction::with(['user:id,username'])
            ->select('id', 'user_id', 'type', 'montant', 'status', 'created_at')
            ->latest()
            ->take(10)
            ->get();

        // ===================================================================
        // 5. RETOUR VUE
        // ===================================================================
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'activeToday',
            'pendingWithdrawals',
            'totalDepots',
            'totalRetraits',
            'totalBonus',
            'netRevenue',
            'chartLabels',
            'chartDepots',
            'chartRetraits',
            'chartBonus',
            'vipChartLabels',
            'vipChartData',
            'recentUsers',
            'recentTransactions'
        ));
    }
}