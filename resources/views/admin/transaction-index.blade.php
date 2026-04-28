<x-admin-layout :title="'Historique des Transactions'" :level="'admin'">

<div class="space-y-6">

    <!-- Header + Stats rapides -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Historique des Transactions</h1>
            <p style="font-size: 13px; color: #4b5563; margin-top: 2px;">Toutes les opérations traitées via l'API</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-card rounded-2xl p-5 border-l-4 border-emerald-500">
            <p class="text-[11px] font-semibold text-gray-400">Volume total</p>
            <p class="text-xl font-bold text-white mt-1">{{ number_format($stats['total_amount'], 0, ',', ' ') }}</p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-blue-500">
            <p class="text-[11px] font-semibold text-gray-400">Total Transactions</p>
            <p class="text-xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-cyan-500">
            <p class="text-[11px] font-semibold text-gray-400">Validées aujourd'hui</p>
            <p class="text-xl font-bold text-white mt-1">{{ $stats['today_completed'] }}</p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-amber-500">
            <p class="text-[11px] font-semibold text-gray-400">Retraits En cours</p>
            <p class="text-xl font-bold text-white mt-1">{{ $stats['pending_retraits'] }}</p>
        </div>
    </div>

    <!-- Filtres & Recherche -->
    <div class="card-admin p-5">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche (ref, nom, téléphone...)" class="input-dark">
            </div>
            <select name="type" class="input-dark">
                <option value="" style="background: var(--admin-card);">Tous les types</option>
                <option value="depot" {{ request('type') === 'depot' ? 'selected' : '' }} style="background: var(--admin-card);">Dépôt</option>
                <option value="retrait" {{ request('type') === 'retrait' ? 'selected' : '' }} style="background: var(--admin-card);">Retrait</option>
                <option value="bonus_all" {{ request('type') === 'bonus_all' ? 'selected' : '' }} style="background: var(--admin-card);">Bonus (tous)</option>
            </select>
            <select name="status" class="input-dark">
                <option value="" style="background: var(--admin-card);">Tous les statuts</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }} style="background: var(--admin-card);">En cours</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }} style="background: var(--admin-card);">Validé</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }} style="background: var(--admin-card);">Échoué</option>
            </select>

            <div class="flex gap-3 md:col-span-4 mt-2 justify-end">
                <a href="{{ route('admin.transactions') }}" class="btn-danger-admin flex items-center justify-center gap-2">
                    Réinitialiser
                </a>
                <button type="submit" class="btn-primary-admin flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des transactions -->
    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Date</th>
                        <th class="text-left">Utilisateur</th>
                        <th class="text-left">Type</th>
                        <th class="text-right">Montant</th>
                        <th class="text-center">Statut</th>
                        <th class="text-left">Référence</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        @php $curr = $tx->user?->currency ?? 'FCFA'; @endphp
                        <tr>
                            <td>
                                <p class="text-[12px] font-semibold text-white">{{ $tx->created_at->format('d/m/Y') }}</p>
                                <span class="text-[10px] text-gray-500">{{ $tx->created_at->format('H:i') }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold" style="background: rgba(16,185,129,0.15); color: #34d399; font-size: 11px;">
                                        {{ strtoupper(substr($tx->user?->username ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white text-[13px]">{{ $tx->user?->username ?? 'Système' }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $tx->user?->phone ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status 
                                    {{ $tx->type === 'depot' ? 'badge-success' : '' }}
                                    {{ $tx->type === 'retrait' ? 'badge-danger' : '' }}
                                    {{ str_starts_with($tx->type, 'bonus') ? 'badge-warning' : '' }}
                                    {{ !in_array($tx->type, ['depot','retrait']) && !str_starts_with($tx->type, 'bonus') ? 'badge-gray' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $tx->type)) }}
                                </span>
                            </td>
                            <td class="text-right font-bold text-white text-[13px]">
                                {{ $tx->type === 'retrait' ? '-' : '' }}{{ number_format($tx->montant, 0, '.', ' ') }} {{ $curr }}
                            </td>
                            <td class="text-center">
                                @if($tx->status === 'completed')
                                    <span class="badge-status badge-success">Validé</span>
                                @elseif($tx->status === 'pending')
                                    <span class="badge-status badge-warning">En cours</span>
                                @else
                                    <span class="badge-status badge-danger">Échoué</span>
                                @endif
                            </td>
                            <td>
                                <code class="text-[11px] font-mono px-2 py-1 rounded" style="background: rgba(255,255,255,0.05); color: #9ca3af;">{{ $tx->reference }}</code>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10" style="color: #374151;">
                                <i class="fas fa-inbox text-3xl mb-3 block" style="color: #1f2937;"></i>
                                Aucune transaction trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-5 border-t" style="border-color: var(--admin-border); background: rgba(255,255,255,0.01);">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

</x-admin-layout>