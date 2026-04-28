<x-admin-layout title="Gestion Emploi">
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Programme Emploi</h1>
            <p style="font-size: 13px; color: #4b5563; margin-top: 2px;">Gérez les salaires mensuels des agents éligibles.</p>
        </div>
        <form method="POST" action="{{ route('admin.emploi.pay') }}" onsubmit="return confirm('Voulez-vous vraiment verser les salaires à tous les utilisateurs éligibles ?')">
            @csrf
            <button type="submit" class="btn-primary-admin flex items-center gap-2">
                <i class="fas fa-money-bill-wave"></i>
                Payer tous les salaires
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Utilisateur</th>
                        <th class="text-left">Poste Atteint</th>
                        <th class="text-center">Filleuls Directs</th>
                        <th class="text-right">CA Équipe</th>
                        <th class="text-right">Salaire</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eligibleUsers as $item)
                        @php $u = $item['user']; $p = $item['poste']; @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold" style="background: rgba(59,130,246,0.15); color: #60a5fa; font-size: 11px;">
                                        U
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white" style="font-size: 13px;">+{{ $u->country_code }} {{ $u->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status badge-success">{{ $p['titre'] }}</span>
                            </td>
                            <td class="text-center font-bold" style="color: #d1d5db; font-size: 13px;">
                                {{ $item['stats']['filleuls'] }} <span style="color: #4b5563; font-weight: normal; font-size: 11px;">/ {{ $p['conditions']['filleuls_directs'] }}</span>
                            </td>
                            <td class="text-right font-bold text-blue-400" style="font-size: 13px;">
                                {{ number_format($item['stats']['equipe'], 0, '.', ' ') }}
                            </td>
                            <td class="text-right font-bold text-emerald-400" style="font-size: 13px;">
                                {{ number_format($p['revenu'], 0, '.', ' ') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="btn-primary-admin py-1.5 px-3 text-xs inline-block" style="background: rgba(255,255,255,0.1); box-shadow: none;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10" style="color: #374151;">
                                <i class="fas fa-briefcase text-3xl mb-3 block" style="color: #1f2937;"></i>
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
