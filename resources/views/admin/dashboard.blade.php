<x-admin-layout :title="'Tableau de bord Admin'" :level="'admin'">

@push('styles')
<style>
    .stat-card {
        background: var(--admin-card);
        border: 1px solid var(--admin-border);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5);
    }
    .chart-container {
        position: relative;
        height: 400px;
        background: var(--admin-card);
        border: 1px solid var(--admin-border);
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
    }
</style>
@endpush

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Tableau de bord</h1>
            <p style="font-size: 13px; color: #4b5563; margin-top: 2px;">Vue d'ensemble complète • {{ now()->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    <!-- 8 Cartes Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="stat-card rounded-2xl p-5 border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Utilisateurs totaux</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $totalUsers }}</p>
                    <p class="text-[10px] text-cyan-400 mt-1">+{{ $activeToday }} actifs aujourd'hui</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(6,182,212,0.15); border: 1px solid rgba(6,182,212,0.25);">
                    <i class="fas fa-users text-cyan-400"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Dépôts totaux</p>
                    <p class="text-xl font-bold text-white mt-1">{{ fmtCurrency($totalDepots) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.25);">
                    <i class="fas fa-arrow-trend-up text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-5 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Retraits totaux</p>
                    <p class="text-xl font-bold text-white mt-1">{{ fmtCurrency($totalRetraits) }}</p>
                    <p class="text-[10px] text-red-400 mt-1">{{ $pendingWithdrawals }} en attente</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.25);">
                    <i class="fas fa-arrow-trend-down text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Bonus distribués</p>
                    <p class="text-xl font-bold text-white mt-1">{{ fmtCurrency($totalBonus) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.25);">
                    <i class="fas fa-gift text-amber-400"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-5 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Revenus nets</p>
                    <p class="text-xl font-bold text-white mt-1">{{ fmtCurrency($netRevenue) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(168,85,247,0.15); border: 1px solid rgba(168,85,247,0.25);">
                    <i class="fas fa-sack-dollar text-purple-400"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Pays actifs</p>
                    <p class="text-xl font-bold text-white mt-1">CM / CI</p>
                    <p class="text-[10px] text-emerald-400 mt-1">XAF / XOF</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25);">
                    <i class="fas fa-globe-africa text-emerald-400"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-5 border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">VIP Actifs</p>
                    <p class="text-2xl font-bold text-white mt-1">
                        {{ collect($vipChartData ?? [])->slice(2)->sum() }}
                    </p>
                    <p class="text-[10px] text-pink-400 mt-1">Niveaux 3+</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(236,72,153,0.15); border: 1px solid rgba(236,72,153,0.25);">
                    <i class="fas fa-crown text-pink-400"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-5 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Croissance</p>
                    <p class="text-2xl font-bold text-white mt-1">
                        {{ $chartDepots && count($chartDepots) > 1 ? round((end($chartDepots) - $chartDepots[0]) / max(1, $chartDepots[0]) * 100, 1) : 0 }}%
                    </p>
                    <p class="text-[10px] text-indigo-400 mt-1">vs 30 jours</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.25);">
                    <i class="fas fa-rocket text-indigo-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques dynamiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="chart-container">
            <h3 class="text-sm font-bold text-gray-300 mb-4">Flux financiers (30 jours)</h3>
            <canvas id="flowChart"></canvas>
        </div>

        <div class="chart-container">
            <h3 class="text-sm font-bold text-gray-300 mb-4">Répartition VIP</h3>
            <canvas id="vipChart"></canvas>
        </div>
    </div>

    <!-- Dernières activités -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Nouveaux inscrits -->
        <div class="card-admin p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-cyan-400">Nouveaux inscrits</h3>
                <a href="{{ route('admin.users.index') }}" class="text-[11px] text-blue-400 hover:underline">Voir tous</a>
            </div>
            <div class="space-y-3">
                @foreach($recentUsers->take(6) as $user)
                    <div class="flex items-center justify-between p-3 rounded-xl transition" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full text-white font-bold flex items-center justify-center text-sm shadow-lg" style="background: linear-gradient(135deg, #06b6d4, #3b82f6);">
                                {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-200 text-sm">{{ $user->username }}</p>
                                <p class="text-[10px] text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] px-3 py-1 rounded-full font-bold" style="background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3);">
                            VIP {{ $user->level ?? 1 }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Dernières transactions -->
        <div class="card-admin p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-blue-400">Dernières transactions</h3>
                <a href="{{ route('admin.transactions') }}" class="text-[11px] text-blue-400 hover:underline">Voir toutes</a>
            </div>
            <div class="space-y-3">
                @foreach($recentTransactions->take(6) as $tx)
                    <div class="flex items-center justify-between p-3 rounded-xl" style="background: {{ $tx->type === 'depot' ? 'rgba(16,185,129,0.05)' : 'rgba(239,68,68,0.05)' }}; border: 1px solid {{ $tx->type === 'depot' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }};">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: {{ $tx->type === 'depot' ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }};">
                                <i class="fas {{ $tx->type === 'depot' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-sm {{ $tx->type === 'depot' ? 'text-emerald-400' : 'text-red-400' }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-200 text-sm">{{ $tx->user->username ?? 'Système' }}</p>
                                <p class="text-[10px] text-gray-500">{{ ucfirst(str_replace('_', ' ', $tx->type)) }} • {{ $tx->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-sm {{ $tx->type === 'depot' ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $tx->type === 'depot' ? '+' : '-' }}{{ fmtCurrency($tx->montant) }}
                            </p>
                            @if($tx->status === 'pending')
                                <span class="text-[10px] px-2 py-0.5 rounded mt-1 inline-block font-medium" style="background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3);">En cours</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = '#9ca3af';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';

    new Chart(document.getElementById('flowChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                { label: 'Dépôts', data: @json($chartDepots), borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', tension: 0.4, fill: true },
                { label: 'Retraits', data: @json($chartRetraits), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', tension: 0.4, fill: true },
                { label: 'Bonus', data: @json($chartBonus), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', tension: 0.4, fill: true }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { color: '#d1d5db' } } },
            scales: { 
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } },
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } }
            }
        }
    });

    new Chart(document.getElementById('vipChart'), {
        type: 'doughnut',
        data: {
            labels: @json($vipChartLabels),
            datasets: [{
                data: @json($vipChartData),
                backgroundColor: ['#4b5563','#3b82f6','#8b5cf6','#ec4899','#f43f5e','#f97316','#eab308'],
                borderWidth: 2,
                borderColor: '#0d1117'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { color: '#d1d5db' } }
            }
        }
    });
</script>
@endpush

</x-admin-layout>