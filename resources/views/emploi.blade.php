<x-layouts :title="'Carrière'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Hero Emploi -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #1e3a8a 100%); box-shadow: 0 0 50px rgba(99,102,241,0.3);">
        <div class="relative z-10 space-y-5">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Carrière</h1>
                <p class="text-[11px] font-medium mt-1" style="color: rgba(199,210,254,0.7);">Revenu Mensuel Garanti</p>
            </div>

            <div class="rounded-2xl p-4 flex items-center gap-4" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.25); border: 1px solid rgba(139,92,246,0.3);">
                    <i class="fas fa-award text-violet-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold" style="color: rgba(199,210,254,0.6);">Grade Actuel</p>
                    <p class="text-lg font-bold text-white leading-tight">{{ $posteActuel ? $posteActuel['titre'] : 'Membre Partenaire' }}</p>
                    @if($posteActuel)
                        <p class="text-[11px] font-semibold text-cyan-300 mt-0.5">+{{ fmtCurrency($posteActuel['revenu']) }} / mois</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-2xl p-4 text-center" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Directs</p>
            <p class="text-lg font-bold text-white">{{ $filleulsDirects }}</p>
        </div>
        <div class="rounded-2xl p-4 text-center" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Équipe</p>
            <p class="text-lg font-bold text-blue-400">{{ number_format($depotEquipe/1000, 1) }}k</p>
        </div>
        <div class="rounded-2xl p-4 text-center" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Perso</p>
            <p class="text-lg font-bold text-cyan-400">{{ number_format($depotPropre/1000, 1) }}k</p>
        </div>
    </div>

    <!-- Timeline of Roles -->
    <div class="space-y-4">
        @foreach($postes as $poste)
            @php $eli = $poste['eligible']; $p = $poste['progress']; @endphp
            <div class="rounded-2xl p-5 space-y-5 transition-all" style="background: #0d1117; border: 1px solid {{ $eli ? 'rgba(59,130,246,0.3)' : 'rgba(255,255,255,0.06)' }};">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white text-lg shadow-lg bg-gradient-to-br {{ $poste['gradient'] }}">
                            <i class="{{ $poste['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 class="text-[13px] font-bold text-white">{{ $poste['titre'] }}</h3>
                            <p class="text-sm font-bold text-cyan-400">+{{ fmtCurrency($poste['revenu']) }} / mois</p>
                        </div>
                    </div>
                    @if($eli)
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: rgba(6,182,212,0.2); border: 1px solid rgba(6,182,212,0.3);">
                            <i class="fas fa-check text-cyan-400 text-xs"></i>
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <!-- Filleuls -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] font-semibold" style="color: #4b5563;">Filleuls Directs</p>
                            <p class="text-[11px] font-bold text-gray-300">{{ $filleulsDirects }} / {{ $poste['conditions']['filleuls_directs'] }}</p>
                        </div>
                        <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-1000" style="width: {{ $p['filleuls'] }}%; background: linear-gradient(90deg, #2563eb, #06b6d4);"></div>
                        </div>
                    </div>

                    <!-- CA -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] font-semibold" style="color: #4b5563;">Chiffre d'Affaire</p>
                            <p class="text-[11px] font-bold text-gray-300">{{ $p['equipe'] }}%</p>
                        </div>
                        <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-1000" style="width: {{ $p['equipe'] }}%; background: linear-gradient(90deg, #7c3aed, #a78bfa);"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center pb-4">
        <p class="text-[11px] font-medium" style="color: #374151;">Mise à jour mensuelle automatique</p>
    </div>
</div>
</x-layouts>
