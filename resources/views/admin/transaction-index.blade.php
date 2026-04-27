<x-admin-layout :title="'Historique des Transactions'" :level="'admin'">

<div class="max-w-7xl mx-auto py-8 space-y-8">

    <!-- Header + Stats rapides -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div>
                <h1 class="text-4xl font-extrabold mb-2">Historique des Transactions</h1>
                <p class="text-emerald-100 text-lg">Toutes les opérations traitées via l'API</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white/20 backdrop-blur rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
                    <p class="text-sm opacity-90">Total</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-300">{{ $stats['pending_retraits'] }}</p>
                    <p class="text-sm opacity-90">Retraits En cours</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-green-300">{{ $stats['today_completed'] }}</p>
                    <p class="text-sm opacity-90">Validées aujourd'hui</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-2xl p-5 text-center">
                    <p class="text-xl font-bold">{{ number_format($stats['total_amount'], 0, ',', ' ') }}</p>
                    <p class="text-sm opacity-90">Volume total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres & Recherche -->
    <form method="GET" class="bg-white rounded-2xl shadow-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche (ref, nom, téléphone...)"
                       class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <select name="type" class="border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                <option value="">Tous les types</option>
                <option value="depot" {{ request('type') === 'depot' ? 'selected' : '' }}>Dépôt</option>
                <option value="retrait" {{ request('type') === 'retrait' ? 'selected' : '' }}>Retrait</option>
                <option value="bonus_all" {{ request('type') === 'bonus_all' ? 'selected' : '' }}>Bonus (tous)</option>
            </select>
            <select name="status" class="border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En cours</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Validé</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échoué</option>
            </select>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-emerald-600 text-white rounded-xl px-6 py-3 hover:bg-emerald-700 transition flex items-center gap-2">
                    Filtrer
                </button>
                <a href="{{ route('admin.transactions') }}"
                   class="bg-gray-200 text-gray-700 rounded-xl px-6 py-3 hover:bg-gray-300 transition">
                    Réinitialiser
                </a>
            </div>
        </div>
    </form>

    <!-- Tableau des transactions -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Utilisateur</th>
                        <th class="px-6 py-4 text-left">Type</th>
                        <th class="px-6 py-4 text-right">Montant</th>
                        <th class="px-6 py-4 text-center">Statut</th>
                        <th class="px-6 py-4 text-left">Référence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                        @php
                            $curr = $tx->user?->currency ?? 'FCFA';
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-600">
                                {{ $tx->created_at->format('d/m/Y') }}
                                <span class="block text-xs text-gray-500">{{ $tx->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                                        {{ strtoupper(substr($tx->user?->username ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $tx->user?->username ?? 'Système' }}</p>
                                        <p class="text-xs text-gray-500">{{ $tx->user?->phone ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-4 py-2 rounded-full text-xs font-bold
                                    {{ $tx->type === 'depot' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $tx->type === 'retrait' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ str_starts_with($tx->type, 'bonus') ? 'bg-amber-100 text-amber-700' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $tx->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                {{ $tx->type === 'retrait' ? '-' : '' }}{{ number_format($tx->montant, 0, '.', ' ') }} {{ $curr }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($tx->status === 'completed')
                                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-bold">Validé</span>
                                @elseif($tx->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-xs font-bold">En cours</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-xs font-bold">Échoué</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs font-mono bg-gray-100 px-3 py-1 rounded">{{ $tx->reference }}</code>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-20 text-gray-500 text-lg">
                                <i class="fas fa-inbox text-6xl mb-4 opacity-20"></i>
                                <p>Aucune transaction trouvée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t bg-gray-50">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

</x-admin-layout>