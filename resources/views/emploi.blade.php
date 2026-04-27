<x-layouts :title="'Carrière'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-20">

    <!-- Hero Emploi Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl">
        <div class="relative z-10 space-y-6">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold tracking-tight">Carrière</h1>
                <p class="text-[11px] font-semibold text-gray-400">Revenu Mensuel Garanti</p>
            </div>
            
            <div class="bg-white/5 border border-white/5 rounded-3xl p-6 flex items-center gap-6 backdrop-blur-md">
                <div class="w-14 h-14 bg-emerald-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/20">
                    <i class="fas fa-award text-emerald-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400">Grade Actuel</p>
                    <p class="text-lg font-bold text-white leading-tight">{{ $posteActuel ? $posteActuel['titre'] : 'Membre Partenaire' }}</p>
                    @if($posteActuel)
                        <p class="text-[11px] font-bold text-emerald-400 mt-1">+{{ fmtCurrency($posteActuel['revenu']) }} / mois</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="absolute -right-24 -bottom-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-50 text-center space-y-1">
            <p class="text-[9px] font-bold text-gray-400">Directs</p>
            <p class="text-base font-bold text-slate-900">{{ $filleulsDirects }}</p>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-50 text-center space-y-1">
            <p class="text-[9px] font-bold text-gray-400">Équipe</p>
            <p class="text-base font-bold text-blue-600">{{ number_format($depotEquipe/1000, 1) }}k</p>
        </div>
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-50 text-center space-y-1">
            <p class="text-[9px] font-bold text-gray-400">Perso</p>
            <p class="text-base font-bold text-emerald-600">{{ number_format($depotPropre/1000, 1) }}k</p>
        </div>
    </div>

    <!-- Timeline of Roles -->
    <div class="space-y-6">
        @foreach($postes as $poste)
            @php
                $eli = $poste['eligible'];
                $p = $poste['progress'];
            @endphp
            <div class="bg-white rounded-[40px] p-8 shadow-sm border {{ $eli ? 'border-emerald-500/30 bg-emerald-50/10' : 'border-gray-50' }} space-y-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $poste['gradient'] }} flex items-center justify-center text-white text-lg shadow-lg">
                            <i class="{{ $poste['icon'] }}"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-[12px] font-bold text-gray-800">{{ $poste['titre'] }}</h3>
                            <p class="text-sm font-bold text-emerald-600">+{{ fmtCurrency($poste['revenu']) }} / mois</p>
                        </div>
                    </div>
                    @if($eli)
                        <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white text-[10px] shadow-lg shadow-emerald-200">
                            <i class="fas fa-check"></i>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Progress Card -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <p class="text-[10px] font-bold text-gray-400">Filleuls Directs</p>
                            <p class="text-[11px] font-bold text-slate-900">{{ $filleulsDirects }} / {{ $poste['conditions']['filleuls_directs'] }}</p>
                        </div>
                        <div class="h-2 bg-gray-50 rounded-full overflow-hidden border border-gray-100">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $p['filleuls'] }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <p class="text-[10px] font-bold text-gray-400">Chiffre d'Affaire</p>
                            <p class="text-[11px] font-bold text-slate-900">{{ $p['equipe'] }}%</p>
                        </div>
                        <div class="h-2 bg-gray-50 rounded-full overflow-hidden border border-gray-100">
                            <div class="h-full bg-slate-900 rounded-full transition-all duration-1000" style="width: {{ $p['equipe'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center pt-4">
        <p class="text-[10px] font-medium text-gray-300">Mise à jour mensuelle automatique</p>
    </div>
</div>
</x-layouts>
