<x-admin-layout :title="'Gestion des Codes Bonus'" :level="'admin'">

<div class="space-y-6">

    <!-- Header Premium -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Codes Bonus</h1>
            <p style="font-size: 13px; color: #4b5563; margin-top: 2px;">
                Créez et gérez les codes promotionnels pour booster vos utilisateurs
            </p>
        </div>
        <a href="{{ route('admin.bonus.create') }}" class="btn-primary-admin flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Nouveau code
        </a>
    </div>

    <!-- Stats rapides -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card rounded-2xl p-5 border-l-4 border-emerald-500">
            <p class="text-[11px] font-semibold text-gray-400">Codes actifs</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $codes->where('is_active', true)->count() }}</p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-blue-500">
            <p class="text-[11px] font-semibold text-gray-400">Total distribué</p>
            <p class="text-xl font-bold text-white mt-1">
                {{ number_format($codes->sum(fn($c) => $c->montant * $c->users_count), 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-purple-500">
            <p class="text-[11px] font-semibold text-gray-400">Utilisations totales</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $codes->sum('users_count') }}</p>
        </div>
        <div class="stat-card rounded-2xl p-5 border-l-4 border-amber-500">
            <p class="text-[11px] font-semibold text-gray-400">Codes créés</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $codes->count() }}</p>
        </div>
    </div>

    <!-- Tableau moderne -->
    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Code</th>
                        <th class="text-center">Montant</th>
                        <th class="text-center">Utilisations</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                        <tr>
                            <td>
                                <code class="font-mono text-sm font-bold px-3 py-1.5 rounded-lg" style="background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.25);">
                                    {{ $code->code }}
                                </code>
                            </td>
                            <td class="text-center">
                                <p class="text-sm font-bold text-white">
                                    {{ number_format($code->montant, 0, ',', ' ') }} FCFA
                                </p>
                            </td>
                            <td class="text-center">
                                <p class="text-sm font-bold text-gray-300">
                                    {{ $code->users_count }}
                                    <span class="text-[10px] text-gray-500">/{{ $code->max_usage ?? '∞' }}</span>
                                </p>
                            </td>
                            <td class="text-center">
                                @if($code->is_active)
                                    <span class="badge-status badge-success">
                                        Actif
                                    </span>
                                @else
                                    <span class="badge-status badge-danger">
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-[12px] text-gray-400">
                                {{ $code->created_at->format('d/m/Y') }}
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.bonus.toggle', $code->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="{{ $code->is_active ? 'btn-danger-admin' : 'btn-primary-admin' }} py-1.5 px-3 text-xs">
                                        {{ $code->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                <i class="fas fa-gift text-3xl mb-3 block" style="color: #1f2937;"></i>
                                <p class="text-sm">Aucun code bonus créé pour le moment</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-admin-layout>