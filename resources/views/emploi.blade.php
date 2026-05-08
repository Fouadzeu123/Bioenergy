<x-layouts :title="'Carrière'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Hero Emploi -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #1e3a8a 100%); box-shadow: 0 0 50px rgba(99,102,241,0.3);">
        <div class="relative z-10 space-y-5">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Carrière</h1>
                <p class="text-[12px] font-medium mt-1" style="color: rgba(199,210,254,0.8);">Bâtissez votre réseau, obtenez un salaire garanti.</p>
            </div>

            <div class="rounded-2xl p-4 flex items-center gap-4" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner" style="background: rgba(139,92,246,0.25); border: 1px solid rgba(139,92,246,0.4);">
                    <i class="fas fa-award text-violet-300 text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-wider uppercase" style="color: rgba(199,210,254,0.6);">Grade Actuel</p>
                    <p class="text-lg font-bold text-white leading-tight">{{ $posteActuel ? $posteActuel['titre'] : 'Membre Partenaire' }}</p>
                    @if($posteActuel)
                        <p class="text-[11px] font-bold text-cyan-300 mt-1 bg-cyan-900/40 inline-block px-2 py-0.5 rounded-md border border-cyan-400/30">
                            Salaire : +{{ fmtCurrency($posteActuel['revenu']) }} / mois
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
        <div class="absolute -left-10 bottom-0 w-32 h-32 rounded-full" style="background: rgba(59,130,246,0.1); filter: blur(25px);"></div>
    </div>

    <!-- Bannière de motivation -->
    <div class="rounded-2xl p-4 flex gap-3 animate__animated animate__fadeIn" style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);">
        <div class="flex-shrink-0 mt-0.5">
            <i class="fas fa-fire-flame-curved text-amber-500 text-lg"></i>
        </div>
        <div>
            <p class="text-[12px] font-bold text-amber-500">Récompenses Exclusives</p>
            <p class="text-[11px] font-medium leading-relaxed mt-1" style="color: rgba(253,230,138,0.8);">
                Débloquez des paliers pour percevoir jusqu'à <strong>1 200 000 {{ Auth::user()->currency }}</strong> par mois ! Atteignez les objectifs et réclamez votre contrat.
            </p>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-2xl p-4 text-center shadow-lg" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center mb-2" style="background: rgba(59,130,246,0.1);">
                <i class="fas fa-user-plus text-blue-400 text-[10px]"></i>
            </div>
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Directs</p>
            <p class="text-lg font-bold text-white">{{ $filleulsDirects }}</p>
        </div>
        <div class="rounded-2xl p-4 text-center shadow-lg" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center mb-2" style="background: rgba(6,182,212,0.1);">
                <i class="fas fa-users text-cyan-400 text-[10px]"></i>
            </div>
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Équipe</p>
            <p class="text-lg font-bold text-cyan-400">{{ number_format($depotEquipe/1000, 1) }}k</p>
        </div>
        <div class="rounded-2xl p-4 text-center shadow-lg" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center mb-2" style="background: rgba(139,92,246,0.1);">
                <i class="fas fa-wallet text-violet-400 text-[10px]"></i>
            </div>
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Perso</p>
            <p class="text-lg font-bold text-violet-400">{{ number_format($depotPropre/1000, 1) }}k</p>
        </div>
    </div>

    <!-- Timeline of Roles -->
    <div class="space-y-5">
        @foreach($postes as $poste)
            @php $eli = $poste['eligible']; $p = $poste['progress']; @endphp
            <div class="rounded-2xl p-5 space-y-5 transition-all shadow-xl hover:shadow-2xl hover:border-blue-500/30" style="background: #0d1117; border: 1px solid {{ $eli ? 'rgba(16,185,129,0.3)' : 'rgba(255,255,255,0.06)' }}; position: relative; overflow: hidden;">

                @if($eli)
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full" style="background: rgba(16,185,129,0.1); filter: blur(20px);"></div>
                @endif

                <div class="flex items-start justify-between relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white text-lg shadow-lg bg-gradient-to-br {{ $poste['gradient'] }}">
                            <i class="{{ $poste['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 class="text-[14px] font-bold text-white">{{ $poste['titre'] }}</h3>
                            <p class="text-[12px] font-bold mt-0.5 text-transparent bg-clip-text bg-gradient-to-r {{ $poste['gradient'] }}">
                                Salaire : +{{ fmtCurrency($poste['revenu']) }} / mois
                            </p>
                        </div>
                    </div>
                    @if($eli)
                        <div class="w-8 h-8 flex-shrink-0 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    @else
                        <div class="w-8 h-8 flex-shrink-0 rounded-full flex items-center justify-center" style="background: rgba(255,255,255,0.05);">
                            <i class="fas fa-lock text-gray-500 text-xs"></i>
                        </div>
                    @endif
                </div>

                <p class="text-[11px] font-medium leading-relaxed relative z-10" style="color: #9ca3af;">
                    {{ $poste['description'] }}
                </p>

                <div class="space-y-4 pt-2 relative z-10">
                    <!-- Filleuls -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] font-semibold uppercase tracking-wider" style="color: #4b5563;">Filleuls Directs Actifs</p>
                            <p class="text-[10px] font-bold text-gray-300">{{ $filleulsDirects }} / {{ $poste['conditions']['filleuls_directs'] }}</p>
                        </div>
                        <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(59,130,246,0.5)]" style="width: {{ $p['filleuls'] }}%; background: linear-gradient(90deg, #2563eb, #06b6d4);"></div>
                        </div>
                    </div>

                    <!-- CA -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] font-semibold uppercase tracking-wider" style="color: #4b5563;">Dépôts de l'équipe</p>
                            <p class="text-[10px] font-bold text-gray-300">{{ number_format($depotEquipe, 0, '.', ' ') }} / {{ number_format($poste['conditions']['depot_equipe'], 0, '.', ' ') }}</p>
                        </div>
                        <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(167,139,250,0.5)]" style="width: {{ $p['equipe'] }}%; background: linear-gradient(90deg, #7c3aed, #a78bfa);"></div>
                        </div>
                    </div>

                    <!-- Dépôt propre -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] font-semibold uppercase tracking-wider" style="color: #4b5563;">Investissement Personnel</p>
                            <p class="text-[10px] font-bold text-gray-300">{{ number_format($depotPropre, 0, '.', ' ') }} / {{ number_format($poste['conditions']['depot_propre'], 0, '.', ' ') }}</p>
                        </div>
                        <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(52,211,153,0.5)]" style="width: {{ $p['propre'] }}%; background: linear-gradient(90deg, #10b981, #34d399);"></div>
                        </div>
                    </div>
                </div>

                <!-- Action: Mise à niveau -->
                @if($eli)
                    @if(Auth::user()->poste_id === $poste['id'])
                        <div class="pt-4 mt-2 relative z-10" style="border-top: 1px dashed rgba(16,185,129,0.2);">
                            <div class="rounded-xl p-4 flex items-center justify-center gap-3 text-center shadow-inner" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                                <i class="fas fa-certificate text-emerald-400 text-2xl drop-shadow-md"></i>
                                <div class="text-left">
                                    <p class="text-[12px] font-bold text-emerald-400">Poste Officiellement Attribué</p>
                                    <p class="text-[10px] font-medium text-emerald-500/80">Vous percevez ce salaire mensuellement.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="pt-4 mt-2 relative z-10" style="border-top: 1px dashed rgba(16,185,129,0.2);">
                            <div class="rounded-xl p-4 flex flex-col items-center text-center space-y-3" style="background: rgba(16,185,129,0.05);">
                                <p class="text-[12px] font-bold text-emerald-400">Félicitations, quota atteint ! <i class="fas fa-party-horn ml-1"></i></p>
                                <p class="text-[10px] font-medium text-gray-400 px-2 leading-relaxed">
                                    Vous êtes éligible pour le poste de <strong>{{ $poste['titre'] }}</strong>. L'administration validera votre contrat.
                                </p>
                                <a href="{{ route('contact') }}" class="w-full text-[11px] font-bold text-white px-4 py-3 rounded-xl transition-all shadow-lg active:scale-95" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 15px rgba(16,185,129,0.25);">
                                    Contacter le Service RH
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    <div class="text-center pb-4 pt-4">
        <div class="w-10 h-1 rounded-full mx-auto mb-3" style="background: rgba(255,255,255,0.1);"></div>
        <p class="text-[10px] font-semibold uppercase tracking-wider" style="color: #4b5563;">Processus d'activation</p>
        <p class="text-[10px] font-medium mt-1" style="color: #6b7280;">Le paiement des salaires s'effectue mensuellement après validation RH.</p>
    </div>
</div>
</x-layouts>
