<x-admin-layout :title="'Profil de ' . $user->phone" :level="'admin'">

@php $curr = $user->currency; @endphp

<div class="space-y-6">

    <div>
        <a href="{{ route('admin.users.index') }}" style="font-size: 12px; color: #4b5563; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left text-xs"></i> Retour aux utilisateurs
        </a>
    </div>

    <!-- Header avec avatar + infos principales -->
    <div class="flex flex-col md:flex-row gap-6 p-6 rounded-3xl" style="background: linear-gradient(135deg, rgba(37,99,235,0.1) 0%, rgba(6,182,212,0.1) 100%); border: 1px solid rgba(6,182,212,0.2);">
        <div class="w-28 h-28 rounded-full flex items-center justify-center text-5xl font-bold shadow-xl flex-shrink-0" style="background: linear-gradient(135deg, #0891b2, #2563eb); color: white;">
            U
        </div>
        <div class="flex-1 flex flex-col justify-center text-center md:text-left">
            <h1 class="text-3xl font-bold text-white mb-2">+{{ $user->country_code }} {{ $user->phone }}</h1>
            <div class="flex flex-wrap gap-4 justify-center md:justify-start text-xs text-gray-400">
                <span><i class="fas fa-phone mr-1"></i> {{ $user->phone ?? 'Non renseigné' }}</span>
                <span><i class="fas fa-envelope mr-1"></i> {{ $user->email }}</span>
                <span><i class="fas fa-globe mr-1"></i> {{ $user->withdrawal_country }}</span>
                <span><i class="fas fa-calendar mr-1"></i> {{ $user->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="flex flex-wrap gap-2 mt-4 justify-center md:justify-start">
                <span class="badge-status {{ $user->level >= 3 ? 'badge-success' : 'badge-gray' }}">VIP {{ $user->level ?? 0 }}</span>
                <span class="badge-status {{ $user->role === 'admin' ? 'badge-warning' : 'badge-gray' }}">{{ ucfirst($user->role) }}</span>
                @if($user->is_banned ?? false)
                    <span class="badge-status badge-danger">Compte banni</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques rapides -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="stat-card rounded-2xl p-5 border-l-4 border-cyan-500">
            <p class="text-[10px] font-semibold text-gray-400 uppercase">Solde actuel</p>
            <p class="text-xl font-bold text-cyan-400 mt-1">{{ number_format($user->account_balance ?? 0, 0, '.', ' ') }} <span class="text-xs">{{ $curr }}</span></p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-blue-500">
            <p class="text-[10px] font-semibold text-gray-400 uppercase">Total dépôts</p>
            <p class="text-xl font-bold text-blue-400 mt-1">{{ number_format($user->total_deposits ?? 0, 0, '.', ' ') }} <span class="text-xs">{{ $curr }}</span></p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-red-500">
            <p class="text-[10px] font-semibold text-gray-400 uppercase">Total retraits</p>
            <p class="text-xl font-bold text-red-400 mt-1">{{ number_format($user->total_withdrawals ?? 0, 0, '.', ' ') }} <span class="text-xs">{{ $curr }}</span></p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-purple-500">
            <p class="text-[10px] font-semibold text-gray-400 uppercase">Bénéfice net</p>
            <p class="text-xl font-bold text-purple-400 mt-1">{{ number_format(($user->total_deposits ?? 0) - ($user->total_withdrawals ?? 0), 0, '.', ' ') }} <span class="text-xs">{{ $curr }}</span></p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-amber-500">
            <p class="text-[10px] font-semibold text-gray-400 uppercase">Tours Lucky</p>
            <p class="text-xl font-bold text-amber-400 mt-1">{{ $user->lucky_spins ?? 0 }}</p>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="card-admin p-6">
        <h3 class="text-sm font-bold text-white mb-4">Actions rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary-admin py-3 text-center text-[12px] flex flex-col items-center justify-center gap-2" style="background: rgba(59,130,246,0.1); color: #60a5fa; box-shadow: none;">
                <i class="fas fa-pen"></i> Modifier
            </a>

            <button type="button" onclick="openBonusModal()" class="btn-primary-admin py-3 text-center text-[12px] flex flex-col items-center justify-center gap-2" style="background: rgba(16,185,129,0.1); color: #34d399; box-shadow: none;">
                <i class="fas fa-gift"></i> Ajouter bonus
            </button>

            <button type="button" onclick="openSpinsModal()" class="btn-primary-admin py-3 text-center text-[12px] flex flex-col items-center justify-center gap-2" style="background: rgba(245,158,11,0.1); color: #fbbf24; box-shadow: none;">
                <i class="fas fa-dharmachakra"></i> Accorder tours
            </button>

            <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" class="inline-block w-full" onsubmit="return confirm('Réinitialiser le mot de passe ?')">
                @csrf
                <button type="submit" class="w-full btn-primary-admin py-3 text-center text-[12px] flex flex-col items-center justify-center gap-2" style="background: rgba(249,115,22,0.1); color: #fb923c; box-shadow: none;">
                    <i class="fas fa-key"></i> Password
                </button>
            </form>

            @if(($user->is_banned ?? false))
                <form method="POST" action="{{ route('admin.users.unban', $user->id) }}" class="inline-block w-full">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full btn-primary-admin py-3 text-center text-[12px] flex flex-col items-center justify-center gap-2" style="background: rgba(16,185,129,0.1); color: #34d399; box-shadow: none;">
                        <i class="fas fa-unlock"></i> Débannir
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" class="inline-block w-full" onsubmit="return confirm('Bannir cet utilisateur ?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full btn-danger-admin py-3 text-center text-[12px] flex flex-col items-center justify-center gap-2" style="box-shadow: none;">
                        <i class="fas fa-ban"></i> Bannir
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Parrain & Code invitation -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card-admin p-6">
            <h3 class="text-sm font-bold text-white mb-4">Parrain</h3>
            @if($user->parrain)
                <div class="flex items-center gap-4 p-4 rounded-xl" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" style="background: rgba(16,185,129,0.15); color: #34d399;">
                        U
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">+{{ $user->parrain->country_code }} {{ $user->parrain->phone }}</p>
                        <p class="text-[11px] text-gray-500">VIP {{ $user->parrain->level ?? 1 }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Aucun parrain</p>
            @endif
        </div>

        <div class="card-admin p-6 text-center">
            <h3 class="text-sm font-bold text-white mb-4 text-left">Code d'invitation</h3>
            <div class="py-2">
                <code class="text-2xl font-mono px-6 py-3 rounded-xl" style="background: rgba(255,255,255,0.05); color: #60a5fa; border: 1px dashed rgba(96,165,250,0.3);">
                    {{ $user->invitation_code }}
                </code>
            </div>
        </div>
    </div>

    <!-- Dernières transactions -->
    <div class="card-admin overflow-hidden">
        <div class="p-6 border-b" style="border-color: var(--admin-border);">
            <h3 class="text-sm font-bold text-white">Dernières transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Date</th>
                        <th class="text-left">Type</th>
                        <th class="text-right">Montant</th>
                        <th class="text-center">Statut</th>
                        <th class="text-left">Référence</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->transactions as $tx)
                        <tr>
                            <td>
                                <p class="text-[12px] font-semibold text-gray-300">{{ $tx->created_at->format('d/m/Y H:i') }}</p>
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
                                {{ $tx->type === 'retrait' ? '-' : '+' }}{{ number_format($tx->montant, 0, '.', ' ') }} {{ $curr }}
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
                            <td class="font-mono text-[11px] text-gray-500">{{ $tx->reference }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-10 text-gray-500">Aucune transaction</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajouter Bonus -->
<div id="bonusModal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4" style="backdrop-filter: blur(8px);">
    <div class="card-admin max-w-sm w-full p-6 animate__animated animate__zoomIn">
        <h3 class="text-lg font-bold mb-5 text-white">Ajouter un bonus</h3>
        <form method="POST" action="{{ route('admin.users.bonus', $user->id) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-2">Montant ({{ $curr }})</label>
                    <input type="number" name="montant" step="1" min="1" required class="input-dark">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-2">Description (optionnel)</label>
                    <textarea name="description" rows="2" class="input-dark"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeBonusModal()" class="flex-1 btn-danger-admin py-3">Annuler</button>
                    <button type="submit" class="flex-1 btn-primary-admin py-3">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Accorder Tours -->
<div id="spinsModal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4" style="backdrop-filter: blur(8px);">
    <div class="card-admin max-w-sm w-full p-6 animate__animated animate__zoomIn">
        <h3 class="text-lg font-bold mb-5 text-white">Accorder des tours Lucky</h3>
        <form method="POST" action="{{ route('admin.users.lucky_spins', $user->id) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 mb-2">Nombre de tours</label>
                    <input type="number" name="spins" step="1" min="1" required class="input-dark">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeSpinsModal()" class="flex-1 btn-danger-admin py-3">Annuler</button>
                    <button type="submit" class="flex-1 btn-primary-admin py-3" style="background: linear-gradient(135deg, #d97706, #b45309);">Ajouter</button>
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