<x-admin-layout :title="'Gestion des Utilisateurs'" :level="'admin'">

<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

    <!-- Header Premium -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-extrabold mb-2">Gestion des Utilisateurs</h1>
                <p class="text-emerald-100 text-lg">{{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }} au total</p>
            </div>
        </div>
    </div>

    <!-- Barre de recherche + Filtres rapides -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                       placeholder="Rechercher par nom, téléphone, email ou code invitation">
            </div>

            <select name="role" class="border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                <option value="">Tous les rôles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>

            <select name="level" class="border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                <option value="">Tous les niveaux VIP</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>VIP {{ $i }}</option>
                @endfor
            </select>

            <div class="flex gap-3">
                <button type="submit" class="bg-emerald-600 text-white rounded-xl px-6 py-3 hover:bg-emerald-700 transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 rounded-xl px-6 py-3 hover:bg-gray-300 transition">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau moderne -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">Utilisateur</th>
                        <th class="px-6 py-4 text-left">Contact</th>
                        <th class="px-6 py-4 text-center">Niveau VIP</th>
                        <th class="px-6 py-4 text-right">Solde</th>
                        <th class="px-6 py-4 text-right">Dépôts</th>
                        <th class="px-6 py-4 text-right">Retraits</th>
                        <th class="px-6 py-4 text-center">Parrain</th>
                        <th class="px-6 py-4 text-center">Inscrit le</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        @php
                            $totalDepots = $user->total_deposits ?? 0;
                            $totalRetraits = $user->total_withdrawals ?? 0;
                            $balance = (float) ($user->account_balance ?? 0);
                            $curr = $user->currency;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Avatar + Nom -->
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-white font-bold flex items-center justify-center text-lg shadow-lg">
                                        {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $user->username }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $user->id }} • {{ $user->withdrawal_country }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-6 py-5">
                                <div>
                                    <p class="text-gray-700">{{ $user->phone ?? '—' }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email ?? '—' }}</p>
                                </div>
                            </td>

                            <!-- VIP Level -->
                            <td class="px-6 py-5 text-center">
                                <span class="inline-block px-4 py-2 rounded-full text-xs font-bold
                                    {{ $user->level >= 5 ? 'bg-gradient-to-r from-yellow-400 to-amber-600 text-white' : '' }}
                                    {{ $user->level >= 3 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    VIP {{ $user->level ?? 1 }}
                                </span>
                            </td>

                            <!-- Solde -->
                            <td class="px-6 py-5 text-right font-bold text-emerald-600">
                                {{ number_format($balance, 0, '.', ' ') }} {{ $curr }}
                            </td>

                            <!-- Dépôts -->
                            <td class="px-6 py-5 text-right font-medium text-blue-600">
                                {{ number_format($totalDepots, 0, '.', ' ') }} {{ $curr }}
                            </td>

                            <!-- Retraits -->
                            <td class="px-6 py-5 text-right font-medium text-red-600">
                                {{ number_format($totalRetraits, 0, '.', ' ') }} {{ $curr }}
                            </td>

                            <!-- Parrain -->
                            <td class="px-6 py-5 text-center">
                                @if($user->parrain)
                                    <span class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-medium">
                                        {{ $user->parrain->username }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Aucun</span>
                                @endif
                            </td>

                            <!-- Date inscription -->
                            <td class="px-6 py-5 text-center text-xs text-gray-500">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                       class="text-emerald-600 hover:text-emerald-800 font-bold text-xs">
                                        Voir
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-bold text-xs">
                                        Modifier
                                    </a>
                                    @if($user->role !== 'admin')
                                        <span class="text-gray-300">|</span>
                                        <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" onclick="return confirm('Bannir cet utilisateur ?')"
                                                    class="text-red-600 hover:text-red-800 font-bold text-xs">
                                                Bannir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-16 text-gray-500 text-lg">
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t bg-gray-50">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

</x-admin-layout>