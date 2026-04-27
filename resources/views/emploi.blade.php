<x-layouts :title="'Opportunités d\'Emploi'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $currency = $user->currency;
@endphp

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    * { font-family: 'Inter', sans-serif; }

    .card-job {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-job:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 48px rgba(0,0,0,0.12);
    }

    .progress-bar-inner {
        transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.4); }
        50%       { box-shadow: 0 0 0 10px rgba(52, 211, 153, 0); }
    }
    .glow-eligible { animation: pulse-glow 2s infinite; }

    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    .shimmer-text {
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #fcd34d, #f59e0b);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 2.5s linear infinite;
    }

    .stat-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 pb-24">

    {{-- ===== HERO ===== --}}
    <div class="relative overflow-hidden px-4 pt-10 pb-14 text-center">
        {{-- Glow backdrop --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>
        </div>

        <span class="inline-flex items-center gap-2 bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
            <i class="fas fa-briefcase"></i> Programme Emploi BioEnergy
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
            Devenez un<br>
            <span class="shimmer-text">Employé BioEnergy</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-xl mx-auto leading-relaxed">
            Atteignez les conditions de chaque poste et percevez un <strong class="text-white">revenu mensuel garanti</strong>, en plus de vos revenus d'investissement.
        </p>
    </div>

    {{-- ===== MES STATISTIQUES ===== --}}
    <div class="max-w-4xl mx-auto px-4 mb-10">
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-3xl p-6">
            <h2 class="text-white font-bold text-lg mb-5 flex items-center gap-2">
                <i class="fas fa-chart-bar text-emerald-400"></i> Mes statistiques actuelles
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Filleuls directs --}}
                <div class="bg-white/5 rounded-2xl p-5 border border-white/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-plus text-emerald-400"></i>
                        </div>
                        <p class="text-slate-400 text-sm font-medium">Filleuls directs</p>
                    </div>
                    <p class="text-3xl font-extrabold text-white">{{ $filleulsDirects }}</p>
                </div>

                {{-- Dépôts équipe --}}
                <div class="bg-white/5 rounded-2xl p-5 border border-white/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-blue-400"></i>
                        </div>
                        <p class="text-slate-400 text-sm font-medium">Dépôts équipe</p>
                    </div>
                    <p class="text-2xl font-extrabold text-white">{{ number_format($depotEquipe, 0, '.', ' ') }} <span class="text-sm font-normal">{{ $currency }}</span></p>
                </div>

                {{-- Mes dépôts --}}
                <div class="bg-white/5 rounded-2xl p-5 border border-white/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-amber-400"></i>
                        </div>
                        <p class="text-slate-400 text-sm font-medium">Mes dépôts</p>
                    </div>
                    <p class="text-2xl font-extrabold text-white">{{ number_format($depotPropre, 0, '.', ' ') }} <span class="text-sm font-normal">{{ $currency }}</span></p>
                </div>
            </div>

            {{-- Badge poste actuel --}}
            @if($posteActuel)
                <div class="mt-5 bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-14 h-14 flex-shrink-0 bg-gradient-to-br {{ $posteActuel['gradient'] }} rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="{{ $posteActuel['icon'] }} text-white text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest">Poste actuel atteint</p>
                        <p class="text-white font-extrabold text-xl">{{ $posteActuel['titre'] }}</p>
                        <p class="text-emerald-400 font-bold">+ {{ number_format($posteActuel['revenu'], 0, '.', ' ') }} {{ $currency }}/mois</p>
                    </div>
                    <div class="ml-auto">
                        <span class="glow-eligible inline-block w-4 h-4 bg-emerald-400 rounded-full"></span>
                    </div>
                </div>
            @else
                <div class="mt-5 bg-white/5 border border-white/10 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-14 h-14 flex-shrink-0 bg-white/10 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-rocket text-slate-300 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Aucun poste atteint</p>
                        <p class="text-white font-bold text-base">Commencez à parrainer pour débloquer votre 1er poste !</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ===== CARTES POSTES ===== --}}
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-white font-bold text-xl mb-6 flex items-center gap-2">
            <i class="fas fa-briefcase text-amber-400"></i> Les postes disponibles
        </h2>

        <div class="space-y-6">
            @foreach($postes as $poste)
            @php
                $c = $poste['conditions'];
                $p = $poste['progress'];
                $eli = $poste['eligible'];
            @endphp

            <div class="card-job relative bg-white/5 backdrop-blur border {{ $eli ? 'border-emerald-500/60' : 'border-white/10' }} rounded-3xl overflow-hidden">

                {{-- Top gradient bar --}}
                <div class="h-1.5 w-full bg-gradient-to-r {{ $poste['gradient'] }}"></div>

                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-start gap-6">

                        {{-- Left: icon + titre --}}
                        <div class="flex-shrink-0 flex flex-col items-center md:items-start gap-2 text-center md:text-left w-full md:w-48">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $poste['gradient'] }} flex items-center justify-center shadow-lg">
                                <i class="{{ $poste['icon'] }} text-white text-2xl"></i>
                            </div>
                            <h3 class="text-white font-extrabold text-lg leading-snug">{{ $poste['titre'] }}</h3>

                            {{-- Revenu mensuel --}}
                            <div class="bg-gradient-to-r {{ $poste['gradient'] }} rounded-xl px-4 py-2 text-white text-center shadow">
                                <p class="text-xs font-semibold opacity-80">Revenu mensuel</p>
                                <p class="text-xl font-extrabold">{{ number_format($poste['revenu'], 0, '.', ' ') }} {{ $currency }}</p>
                            </div>

                            @if($eli)
                                <span class="inline-flex items-center gap-1.5 bg-emerald-500/20 text-emerald-300 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/40">
                                    <i class="fas fa-check-circle"></i> Éligible !
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-white/5 text-slate-400 text-xs font-medium px-3 py-1 rounded-full border border-white/10">
                                    <i class="fas fa-lock"></i> Conditions requises
                                </span>
                            @endif
                        </div>

                        {{-- Right: description + conditions --}}
                        <div class="flex-1">
                            <p class="text-slate-300 text-sm leading-relaxed mb-6">{{ $poste['description'] }}</p>

                            <div class="space-y-4">

                                {{-- Filleuls directs --}}
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-slate-400 text-xs font-semibold flex items-center gap-1.5">
                                            <i class="fas fa-user-plus text-emerald-400"></i> Filleuls directs
                                        </span>
                                        <span class="text-white text-xs font-bold">
                                            {{ $filleulsDirects }} / {{ $c['filleuls_directs'] }}
                                        </span>
                                    </div>
                                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r {{ $poste['gradient'] }} rounded-full progress-bar-inner"
                                             data-width="{{ $p['filleuls'] }}"
                                             style="width: 0%"></div>
                                    </div>
                                </div>

                                {{-- Dépôts équipe --}}
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-slate-400 text-xs font-semibold flex items-center gap-1.5">
                                            <i class="fas fa-users text-blue-400"></i> Dépôts équipe
                                        </span>
                                        <span class="text-white text-xs font-bold">
                                            {{ number_format($depotEquipe, 0, '.', ' ') }} / {{ number_format($c['depot_equipe'], 0, '.', ' ') }} {{ $currency }}
                                        </span>
                                    </div>
                                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r {{ $poste['gradient'] }} rounded-full progress-bar-inner"
                                             data-width="{{ $p['equipe'] }}"
                                             style="width: 0%"></div>
                                    </div>
                                </div>

                                {{-- Mes dépôts --}}
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-slate-400 text-xs font-semibold flex items-center gap-1.5">
                                            <i class="fas fa-wallet text-amber-400"></i> Mes propres dépôts
                                        </span>
                                        <span class="text-white text-xs font-bold">
                                            {{ number_format($depotPropre, 0, '.', ' ') }} / {{ number_format($c['depot_propre'], 0, '.', ' ') }} {{ $currency }}
                                        </span>
                                    </div>
                                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r {{ $poste['gradient'] }} rounded-full progress-bar-inner"
                                             data-width="{{ $p['propre'] }}"
                                             style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Overlay badge "DÉBLOQUÉ" --}}
                @if($eli)
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center gap-1.5 bg-emerald-500 text-white text-[10px] font-extrabold uppercase tracking-widest px-3 py-1.5 rounded-full shadow-lg">
                            <i class="fas fa-check"></i> Débloqué
                        </span>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- CTA bottom --}}
        <div class="mt-10 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 text-center shadow-2xl">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bullseye text-white text-3xl"></i>
            </div>
            <h3 class="text-white font-extrabold text-2xl mb-2">Prêt à gravir les échelons ?</h3>
            <p class="text-emerald-100 mb-6 text-sm max-w-md mx-auto">
                Invitez de nouveaux membres, encouragez les dépôts et atteignez votre prochain poste pour augmenter votre revenu mensuel.
            </p>
            <a href="{{ route('share') }}"
               class="inline-block bg-white text-emerald-700 font-extrabold px-8 py-3.5 rounded-2xl shadow-lg hover:bg-emerald-50 transition hover:scale-105">
                <i class="fas fa-link mr-2"></i> Partager mon lien de parrainage
            </a>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6 pb-4">
            Les revenus mensuels sont crédités chaque 1er du mois selon validation de l'administration.
        </p>
    </div>
</div>

<script>
    // Anime les barres de progression après le chargement
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('.progress-bar-inner').forEach(bar => {
                bar.style.width = bar.dataset.width + '%';
            });
        }, 300);
    });
</script>

</x-layouts>
