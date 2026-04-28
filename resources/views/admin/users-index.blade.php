<x-admin-layout :title="'Gestion des Utilisateurs'" :level="'admin'">

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Gestion des Utilisateurs</h1>
            <p style="font-size: 13px; color: #4b5563; margin-top: 2px;">{{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }} au total</p>
        </div>
    </div>

    <!-- Barre de recherche + Filtres rapides -->
    <div class="card-admin p-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input-dark"
                       placeholder="Rechercher par nom, téléphone, email ou code invitation">
            </div>

            <select name="role" class="input-dark">
                <option value="" style="background: var(--admin-card);">Tous les rôles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }} style="background: var(--admin-card);">Utilisateur</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }} style="background: var(--admin-card);">Administrateur</option>
            </select>

            <select name="level" class="input-dark">
                <option value="" style="background: var(--admin-card);">Tous les niveaux VIP</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }} style="background: var(--admin-card);">VIP {{ $i }}</option>
                @endfor
            </select>

            <div class="flex gap-3 md:col-span-4 mt-2 justify-end">
                <a href="{{ route('admin.users.index') }}" class="btn-danger-admin flex items-center justify-center gap-2">
                    Réinitialiser
                </a>
                <button type="submit" class="btn-primary-admin flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau moderne -->
    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Utilisateur</th>
                        <th class="text-left">Contact</th>
                        <th class="text-center">Niveau VIP</th>
                        <th class="text-right">Solde</th>
                        <th class="text-right">Dépôts</th>
                        <th class="text-right">Retraits</th>
                        <th class="text-center">Parrain</th>
                        <th class="text-center">Inscrit le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $totalDepots = $user->total_deposits ?? 0;
                            $totalRetraits = $user->total_withdrawals ?? 0;
                            $balance = (float) ($user->account_balance ?? 0);
                            $curr = $user->currency;
                        @endphp
                        <tr>
                            <!-- Avatar + Nom -->
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full text-white font-bold flex items-center justify-center text-sm shadow-lg flex-shrink-0" style="background: linear-gradient(135deg, #0891b2, #2563eb);">
                                        {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white text-sm">{{ $user->username }}</p>
                                        <p class="text-[10px] text-gray-500">ID: {{ $user->id }} • {{ $user->withdrawal_country }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td>
                                <div>
                                    <p class="text-[12px] text-gray-300">{{ $user->phone ?? '—' }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $user->email ?? '—' }}</p>
                                </div>
                            </td>

                            <!-- VIP Level -->
                            <td class="text-center">
                                <span class="badge-status {{ $user->level >= 3 ? 'badge-success' : 'badge-gray' }}">
                                    VIP {{ $user->level ?? 1 }}
                                </span>
                            </td>

                            <!-- Solde -->
                            <td class="text-right font-bold text-cyan-400 text-sm">
                                {{ number_format($balance, 0, '.', ' ') }} {{ $curr }}
                            </td>

                            <!-- Dépôts -->
                            <td class="text-right font-medium text-blue-400 text-[12px]">
                                {{ number_format($totalDepots, 0, '.', ' ') }} {{ $curr }}
                            </td>

                            <!-- Retraits -->
                            <td class="text-right font-medium text-red-400 text-[12px]">
                                {{ number_format($totalRetraits, 0, '.', ' ') }} {{ $curr }}
                            </td>

                            <!-- Parrain -->
                            <td class="text-center">
                                @if($user->parrain)
                                    <span class="badge-status" style="background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.25);">
                                        {{ $user->parrain->username }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-[11px] font-medium">Aucun</span>
                                @endif
                            </td>

                            <!-- Date inscription -->
                            <td class="text-center text-[11px] text-gray-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-primary-admin py-1.5 px-3 text-xs" style="background: rgba(16,185,129,0.1); color: #34d399; box-shadow: none;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary-admin py-1.5 px-3 text-xs" style="background: rgba(59,130,246,0.1); color: #60a5fa; box-shadow: none;">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    @if($user->role !== 'admin')
                                        <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" onclick="return confirm('Bannir cet utilisateur ?')" class="btn-danger-admin py-1.5 px-3 text-xs" title="Bannir">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-16 text-gray-500 text-sm">
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-5 border-t" style="border-color: var(--admin-border); background: rgba(255,255,255,0.01);">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

</x-admin-layout>