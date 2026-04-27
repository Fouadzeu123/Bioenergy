<x-admin-layout :title="'Tableau de bord Admin'" :level="'admin'">

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    }
    .chart-container {
        position: relative;
        height: 400px;
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
    }
</style>
@endpush

<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

    <!-- Header Premium -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-2xl">
        <h1 class="text-4xl font-extrabold mb-2">Tableau de bord Administrateur</h1>
        <p class="text-emerald-100 text-lg">Vue d'ensemble complète • {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <!-- 8 Cartes Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="stat-card rounded-2xl p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Utilisateurs totaux</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $totalUsers }}</p>
                    <p class="text-xs text-emerald-600 mt-1">+{{ $activeToday }} actifs aujourd'hui</p>
                </div>
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-emerald-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Dépôts totaux</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ fmtCurrency($totalDepots) }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-arrow-trend-up text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Retraits totaux</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ fmtCurrency($totalRetraits) }}</p>
                    <p class="text-xs text-red-600">{{ $pendingWithdrawals }} en attente</p>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-arrow-trend-down text-2xl text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Bonus distribués</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ fmtCurrency($totalBonus) }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-gift text-2xl text-amber-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus nets</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ fmtCurrency($netRevenue) }}</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-sack-dollar text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pays actifs</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-2">CM / CI</p>
                    <p class="text-xs text-green-600">XAF / XOF</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-globe-africa text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6 border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">VIP Actifs</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">
                        {{ collect($vipChartData ?? [])->slice(2)->sum() }}
                    </p>
                    <p class="text-xs text-pink-600">Niveaux 3+</p>
                </div>
                <div class="w-14 h-14 bg-pink-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-crown text-2xl text-pink-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Croissance</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">
                        {{ $chartDepots && count($chartDepots) > 1 ? round((end($chartDepots) - $chartDepots[0]) / max(1, $chartDepots[0]) * 100, 1) : 0 }}%
                    </p>
                    <p class="text-xs text-indigo-600">vs 30 jours</p>
                </div>
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-rocket text-2xl text-indigo-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques dynamiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="chart-container">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Flux financiers (30 jours)</h3>
            <canvas id="flowChart"></canvas>
        </div>

        <div class="chart-container">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Répartition VIP</h3>
            <canvas id="vipChart"></canvas>
        </div>
    </div>

    <!-- Dernières activités -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Nouveaux inscrits -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-emerald-700">Nouveaux inscrits</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-emerald-600 hover:underline">Voir tous</a>
            </div>
            <div class="space-y-4">
                @foreach($recentUsers->take(6) as $user)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-white font-bold flex items-center justify-center text-lg">
                                {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $user->username }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-medium">
                            VIP {{ $user->level ?? 1 }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Dernières transactions -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-blue-700">Dernières transactions</h3>
                <a href="{{ route('admin.transactions') }}" class="text-sm text-blue-600 hover:underline">Voir toutes</a>
            </div>
            <div class="space-y-4">
                @foreach($recentTransactions->take(6) as $tx)
                    <div class="flex items-center justify-between p-4 {{ $tx->type === 'depot' ? 'bg-emerald-50' : 'bg-red-50' }} rounded-xl">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full {{ $tx->type === 'depot' ? 'bg-emerald-200' : 'bg-red-200' }} flex items-center justify-center">
                                <i class="fas {{ $tx->type === 'depot' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xl {{ $tx->type === 'depot' ? 'text-emerald-700' : 'text-red-700' }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $tx->user->username ?? 'Système' }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $tx->type)) }} • {{ $tx->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ $tx->type === 'depot' ? 'text-emerald-700' : 'text-red-700' }}">
                                {{ $tx->type === 'depot' ? '+' : '-' }}{{ fmtCurrency($tx->montant) }}
                            </p>
                            @if($tx->status === 'pending')
                                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded mt-1 inline-block">En cours</span>
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
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('vipChart'), {
        type: 'doughnut',
        data: {
            labels: @json($vipChartLabels),
            datasets: [{
                data: @json($vipChartData),
                backgroundColor: ['#e5e7eb','#d1fae5','#86efac','#34d399','#10b981','#059669','#047857'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
</script>
@endpush

</x-admin-layout>