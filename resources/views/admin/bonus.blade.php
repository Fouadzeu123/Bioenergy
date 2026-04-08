<x-admin-layout :title="'Gestion des Codes Bonus'" :level="'admin'">

<div class="max-w-7xl mx-auto px-4 py-8 space-y-10">

    <!-- Header Premium -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-10 text-white shadow-2xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold mb-3">Codes Bonus</h1>
                <p class="text-emerald-100 text-lg opacity-90">
                    Créez et gérez les codes promotionnels pour booster vos utilisateurs
                </p>
            </div>
            <a href="{{ route('admin.bonus.create') }}"
               class="inline-flex items-center gap-3 bg-white text-emerald-700 px-8 py-5 rounded-2xl font-bold text-xl shadow-xl hover:shadow-2xl hover:scale-105 transition">
                <i class="fas fa-plus-circle text-2xl"></i>
                Nouveau code bonus
            </a>
        </div>
    </div>

    <!-- Stats rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Codes actifs</p>
                    <p class="text-4xl font-extrabold text-emerald-600 mt-2">{{ $codes->where('is_active', true)->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-5xl text-emerald-100"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total distribué</p>
                    <p class="text-4xl font-extrabold text-blue-600 mt-2">
                        {{ number_format($codes->sum(fn($c) => $c->montant * $c->users_count), 0, ',', ' ') }} $
                    </p>
                </div>
                <i class="fas fa-gift text-5xl text-blue-100"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Utilisations totales</p>
                    <p class="text-4xl font-extrabold text-purple-600 mt-2">{{ $codes->sum('users_count') }}</p>
                </div>
                <i class="fas fa-users text-5xl text-purple-100"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-6 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Codes créés</p>
                    <p class="text-4xl font-extrabold text-amber-600 mt-2">{{ $codes->count() }}</p>
                </div>
                <i class="fas fa-tags text-5xl text-amber-100"></i>
            </div>
        </div>
    </div>

    <!-- Tableau moderne -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800">Tous les codes bonus</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-white">
                    <tr>
                        <th class="px-6 py-5 text-left font-semibold">Code</th>
                        <th class="px-6 py-5 text-center font-semibold">Montant</th>
                        <th class="px-6 py-5 text-center font-semibold">Utilisations</th>
                        <th class="px-6 py-5 text-center font-semibold">Statut</th>
                        <th class="px-6 py-5 text-center font-semibold">Créé le</th>
                        <th class="px-6 py-5 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($codes as $code)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-5">
                                <code class="font-mono text-lg font-bold text-emerald-700 bg-emerald-50 px-4 py-2 rounded-xl">
                                    {{ $code->code }}
                                </code>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <p class="text-2xl font-extrabold text-gray-800">
                                    {{ number_format($code->montant, 0, ',', ' ') }} $
                                </p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <p class="text-xl font-bold text-gray-700">
                                    {{ $code->users_count }}
                                    <span class="text-sm text-gray-500">/{{ $code->max_usage ?? '∞' }}</span>
                                </p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($code->is_active)
                                    <span class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">
                                        <i class="fas fa-check-circle"></i> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-5 py-2 bg-red-100 text-red-700 rounded-full font-bold text-sm">
                                        <i class="fas fa-ban"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center text-gray-600">
                                {{ $code->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <form action="{{ route('admin.bonus.toggle', $code->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-5 py-3 rounded-xl font-bold text-white transition {{ $code->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                        {{ $code->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-20 text-gray-500">
                                <i class="fas fa-gift text-6xl mb-4 opacity-20"></i>
                                <p class="text-xl">Aucun code bonus créé pour le moment</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-admin-layout>