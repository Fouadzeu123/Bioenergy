<x-admin-layout :title="'Profil de ' . $user->username" :level="'admin'">

@php
    $curr = $user->currency;
@endphp

<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

    <!-- Header avec avatar + infos principales -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-2xl">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
            <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-6xl font-bold shadow-2xl">
                {{ strtoupper(substr($user->username, 0, 1)) }}
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl font-extrabold mb-2">{{ $user->username }}</h1>
                <div class="flex flex-wrap gap-4 justify-center md:justify-start text-sm opacity-90">
                    <span><i class="fas fa-phone"></i> {{ $user->phone ?? 'Non renseigné' }}</span>
                    <span><i class="fas fa-envelope"></i> {{ $user->email }}</span>
                    <span><i class="fas fa-globe"></i> Pays: {{ $user->withdrawal_country }}</span>
                    <span><i class="fas fa-calendar"></i> Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex flex-wrap gap-3 mt-6 justify-center md:justify-start">
                    <span class="px-5 py-2 bg-white/20 rounded-full font-bold text-lg">
                        VIP {{ $user->level ?? 0 }}
                    </span>
                    <span class="px-5 py-2 {{ $user->role === 'admin' ? 'bg-yellow-500' : 'bg-white/20' }} rounded-full font-bold">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->is_banned ?? false)
                        <span class="px-5 py-2 bg-red-600 rounded-full font-bold">Compte banni</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-xl p-6 text-center border-l-4 border-emerald-500">
            <p class="text-gray-600 text-sm font-bold uppercase">Solde actuel</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-2">
                {{ number_format($user->account_balance ?? 0, 0, '.', ' ') }} {{ $curr }}
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-6 text-center border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-bold uppercase">Total dépôts</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-2">
                {{ number_format($user->total_deposits ?? 0, 0, '.', ' ') }} {{ $curr }}
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-6 text-center border-l-4 border-red-500">
            <p class="text-gray-600 text-sm font-bold uppercase">Total retraits</p>
            <p class="text-2xl font-extrabold text-red-600 mt-2">
                {{ number_format($user->total_withdrawals ?? 0, 0, '.', ' ') }} {{ $curr }}
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-6 text-center border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-bold uppercase">Bénéfice net</p>
            <p class="text-2xl font-extrabold text-purple-600 mt-2">
                {{ number_format(($user->total_deposits ?? 0) - ($user->total_withdrawals ?? 0), 0, '.', ' ') }} {{ $curr }}
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-6 text-center border-l-4 border-amber-500">
            <p class="text-gray-600 text-sm font-bold uppercase">Tours Lucky Wheel</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-2">
                {{ $user->lucky_spins ?? 0 }}
            </p>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Actions rapides</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.edit', $user->id) }}"
               class="bg-blue-600 text-white text-center py-4 rounded-xl font-bold hover:bg-blue-700 transition">
                Modifier le profil
            </a>

            <button type="button" onclick="openBonusModal()" 
                    class="w-full bg-emerald-600 text-white py-4 rounded-xl font-bold hover:bg-emerald-700 transition">
                Ajouter un bonus
            </button>

            <button type="button" onclick="openSpinsModal()" 
                    class="w-full bg-amber-600 text-white py-4 rounded-xl font-bold hover:bg-amber-700 transition">
                Accorder des tours
            </button>

            <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" class="inline-block w-full">
                @csrf
                <button type="submit"
                        class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold hover:bg-orange-700 transition">
                    Réinitialiser mot de passe
                </button>
            </form>

            @if(($user->is_banned ?? false))
                <form method="POST" action="{{ route('admin.users.unban', $user->id) }}" class="inline-block w-full">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full bg-green-600 text-white py-4 rounded-xl font-bold hover:bg-green-700 transition">
                        Réactiver le compte
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" class="inline-block w-full"
                      onsubmit="return confirm('Bannir cet utilisateur ?')">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full bg-red-600 text-white py-4 rounded-xl font-bold hover:bg-red-700 transition">
                        Bannir le compte
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Parrain & Code invitation -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Parrain</h3>
            @if($user->parrain)
                <div class="flex items-center gap-4 p-4 bg-emerald-50 rounded-xl">
                    <div class="w-12 h-12 rounded-full bg-emerald-200 flex items-center justify-center font-bold text-emerald-700">
                        {{ strtoupper(substr($user->parrain->username, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold">{{ $user->parrain->username }}</p>
                        <p class="text-sm text-gray-600">VIP {{ $user->parrain->level ?? 1 }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucun parrain</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Code d'invitation</h3>
            <div class="text-center py-8">
                <code class="text-3xl font-mono bg-gray-100 px-6 py-4 rounded-xl">
                    {{ $user->invitation_code }}
                </code>
            </div>
        </div>
    </div>

    <!-- Dernières transactions -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Dernières transactions</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-right">Montant</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-left">Référence</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($user->transactions as $tx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $tx->type === 'depot' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $tx->type === 'retrait' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ str_starts_with($tx->type, 'bonus') ? 'bg-amber-100 text-amber-700' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $tx->type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-gray-800">
                                {{ $tx->type === 'retrait' ? '-' : '+' }}{{ number_format($tx->montant, 0, '.', ' ') }} {{ $curr }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $tx->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $tx->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $tx->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $tx->reference }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-12 text-gray-500">Aucune transaction</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajouter Bonus -->
<div id="bonusModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 animate__animated animate__zoomIn">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Ajouter un bonus</h3>
        <form method="POST" action="{{ route('admin.users.bonus', $user->id) }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Montant ({{ $curr }})</label>
                    <input type="number" name="montant" step="1" min="1" required
                           class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 transition font-bold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description (optionnel)</label>
                    <textarea name="description" rows="3"
                              class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 transition"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-emerald-600 text-white py-4 rounded-xl font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-100">
                        Ajouter
                    </button>
                    <button type="button" onclick="closeBonusModal()"
                            class="flex-1 bg-gray-100 py-4 rounded-xl font-bold hover:bg-gray-200 text-gray-600">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Accorder Tours -->
<div id="spinsModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 animate__animated animate__zoomIn">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Accorder des tours</h3>
        <form method="POST" action="{{ route('admin.users.lucky_spins', $user->id) }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre de tours</label>
                    <input type="number" name="spins" step="1" min="1" required
                           class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 transition font-bold">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-amber-600 text-white py-4 rounded-xl font-bold hover:bg-amber-700 shadow-lg shadow-amber-100">
                        Ajouter
                    </button>
                    <button type="button" onclick="closeSpinsModal()"
                            class="flex-1 bg-gray-100 py-4 rounded-xl font-bold hover:bg-gray-200 text-gray-600">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openBonusModal() {
    document.getElementById('bonusModal').classList.remove('hidden');
}
function closeBonusModal() {
    document.getElementById('bonusModal').classList.add('hidden');
}
function openSpinsModal() {
    document.getElementById('spinsModal').classList.remove('hidden');
}
function closeSpinsModal() {
    document.getElementById('spinsModal').classList.add('hidden');
}
</script>

</x-admin-layout>