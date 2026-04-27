<x-admin-layout :title="'Gestion Emploi'" :level="'admin'">

<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">
    
    <div class="flex justify-between items-center bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Programme Emploi</h1>
            <p class="text-gray-500 mt-1">Gérez les salaires mensuels des agents éligibles.</p>
        </div>
        <form method="POST" action="{{ route('admin.emploi.pay') }}" onsubmit="return confirm('Voulez-vous vraiment verser les salaires à tous les utilisateurs éligibles ?')">
            @csrf
            <button type="submit" class="bg-emerald-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-100 flex items-center gap-3">
                <i class="fas fa-money-bill-wave"></i>
                Payer tous les salaires
            </button>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-6 py-4 text-left">Utilisateur</th>
                        <th class="px-6 py-4 text-left">Poste Atteint</th>
                        <th class="px-6 py-4 text-center">Filleuls Directs</th>
                        <th class="px-6 py-4 text-right">CA Équipe</th>
                        <th class="px-6 py-4 text-right">Salaire</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($eligibleUsers as $item)
                        @php $u = $item['user']; $p = $item['poste']; @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-500">
                                        {{ substr($u->username, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $u->username }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $u->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $p['badge_color'] }}">
                                    {{ $p['titre'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-gray-600">
                                {{ $item['stats']['filleuls'] }} / {{ $p['conditions']['filleuls_directs'] }}
                            </td>
                            <td class="px-6 py-5 text-right font-bold text-blue-600">
                                {{ number_format($item['stats']['equipe'], 0, '.', ' ') }}
                            </td>
                            <td class="px-6 py-5 text-right font-bold text-emerald-600">
                                {{ number_format($p['revenu'], 0, '.', ' ') }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="text-slate-400 hover:text-slate-900 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-gray-400 uppercase text-[10px] font-black tracking-widest">
                                Aucun utilisateur éligible pour le moment
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-admin-layout>
