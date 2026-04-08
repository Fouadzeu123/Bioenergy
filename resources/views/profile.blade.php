<x-layouts :title="'Mon Compte'" :level="Auth::user()->level">

@php
    $rate = $rateFCFAperUSD ?? 650;

    // Toutes tes variables (inchangées)
    $revenus = $revenus ?? ['journalier' => 0, 'mensuel' => 0, 'annuel' => 0];
    $solde_total_usd = $solde_total_usd ?? 0;
    $solde_total_fcfa = $solde_total_fcfa ?? 0;
    $total_retraits_usd = $total_retraits_usd ?? 0;
    $total_retraits_fcfa = $total_retraits_fcfa ?? 0;
    $taille_equipe = $taille_equipe ?? 0;
    $revenu_total_usd = $revenu_total_usd ?? 0;
    $revenu_total_fcfa = $revenu_total_fcfa ?? 0;
    $revenu_equipe_usd = $revenu_equipe_usd ?? 0;
    $revenu_equipe_fcfa = $revenu_equipe_fcfa ?? 0;
    $capturer_benefices_usd = $capturer_benefices_usd ?? 0;
    $capturer_benefices_fcfa = $capturer_benefices_fcfa ?? 0;
    $fonds_recharge_usd = $fonds_recharge_usd ?? 0;
    $fonds_recharge_fcfa = $fonds_recharge_fcfa ?? 0;
    $revenu_epargne_usd = $revenu_epargne_usd ?? 0;
    $revenu_epargne_fcfa = $revenu_epargne_fcfa ?? 0;
    $total_epargne_usd = $total_epargne_usd ?? 0;
    $total_epargne_fcfa = $total_epargne_fcfa ?? 0;
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-emerald-900 to-slate-900">

    <!-- Carte principale premium -->
    <div class="max-w-5xl mx-auto pt-12 pb-20">

        <!-- Profil + Solde principal -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-3xl overflow-hidden">
            <div class="bg-gradient-to-br from-emerald-600/30 to-teal-700/30 p-10 text-white">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white text-5xl font-extrabold shadow-2xl border-4 border-white/50">
                        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-4xl font-extrabold mb-3">{{ Auth::user()->username }}</h1>
                        <p class="text-2xl opacity-90">
                            {{ Auth::user()->phone ? substr(Auth::user()->phone,0,3).'****'.substr(Auth::user()->phone,-3) : '6****0000' }}
                        </p>
                        <div class="flex items-center justify-center md:justify-start gap-4 mt-6">
                            <span class="px-6 py-3 bg-white/20 rounded-full font-bold text-xl">
                                VIP {{ Auth::user()->level }}
                            </span>
                            <a href="{{ route('profile.edit') }}" class="text-white hover:text-emerald-300">
                                <i class="fas fa-cog text-3xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portefeuille principal -->
            <div class="p-10 text-white">
                <h2 class="text-3xl font-extrabold mb-10 text-center">Mon Portefeuille</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-white/10 rounded-3xl p-8 text-center border border-white/20">
                        <p class="text-green-300 text-lg mb-4">Solde disponible</p>
                        <p class="text-4xl font-extrabold text-emerald-400">{{ fmtUSD($solde_total_usd) }}</p>
                        <p class="text-2xl opacity-80 mt-3">{{ fmtFCFA($solde_total_fcfa) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-3xl p-8 text-center border border-white/20">
                        <p class="text-green-300 text-lg mb-4">Revenu total</p>
                        <p class="text-4xl font-extrabold text-yellow-400">{{ fmtUSD($revenu_total_usd) }}</p>
                        <p class="text-2xl opacity-80 mt-3">{{ fmtFCFA($revenu_total_fcfa) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-3xl p-8 text-center border border-white/20">
                        <p class="text-red-300 text-lg mb-4">Total retiré</p>
                        <p class="text-3xl font-extrabold text-red-400">{{ fmtUSD($total_retraits_usd) }}</p>
                        <p class="text-2xl opacity-80 mt-3">{{ fmtFCFA($total_retraits_fcfa) }}</p>
                    </div>
                </div>

                <!-- Stats secondaires -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="bg-white/10 rounded-2xl p-6">
                        <p class="text-green-200 text-sm">Gain fixe / jour</p>
                        <p class="text-3xl font-bold text-emerald-400">{{ fmtUSD($capturer_benefices_usd) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-6">
                        <p class="text-blue-200 text-sm">Taille équipe</p>
                        <p class="text-2xl font-bold text-white">{{ $taille_equipe }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-6">
                        <p class="text-yellow-200 text-sm">Revenu équipe</p>
                        <p class="text-2xl font-bold text-yellow-400">{{ fmtUSD($revenu_equipe_usd) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-6">
                        <p class="text-purple-200 text-sm">Fonds recharge</p>
                        <p class="text-2xl font-bold text-purple-400">{{ fmtUSD($fonds_recharge_usd) }}</p>
                    </div>
                </div>

                <!-- Boutons principaux -->
                <div class="grid grid-cols-2 gap-8 mt-12">
                    <a href="{{ route('deposit') }}"
                       class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-xl py-8 rounded-3xl text-center shadow-2xl transform hover:scale-105 transition">
                        RECHARGER
                    </a>
                    <a href="{{ route('retrait') }}"
                       class="bg-gradient-to-r from-gray-700 to-black hover:from-black hover:to-gray-900 text-white font-extrabold text-xl py-8 rounded-3xl text-center shadow-2xl transform hover:scale-105 transition">
                        RETIRER
                    </a>
                </div>
            </div>
        </div>

        <!-- Section blanche en bas -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-10 mt-12 shadow-3xl">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-white">
                <div class="text-center">
                    <p class="text-green-300 text-sm mb-3">Équilibre</p>
                    <p class="text-3xl font-extrabold">{{ fmtUSD($solde_total_usd) }}</p>
                    <p class="text-lg opacity-80">{{ fmtFCFA($solde_total_fcfa) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-yellow-300 text-sm mb-3">Revenu épargne</p>
                    <p class="text-3xl font-extrabold">{{ fmtUSD($revenu_epargne_usd) }}</p>
                    <p class="text-lg opacity-80">{{ fmtFCFA($revenu_epargne_fcfa) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-purple-300 text-sm mb-3">Total épargne</p>
                    <p class="text-3xl font-extrabold">{{ fmtUSD($total_epargne_usd) }}</p>
                    <p class="text-lg opacity-80">{{ fmtFCFA($total_epargne_fcfa) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-blue-300 text-sm mb-3">Fonds recharge</p>
                    <p class="text-3xl font-extrabold">{{ fmtUSD($fonds_recharge_usd) }}</p>
                    <p class="text-lg opacity-80">{{ fmtFCFA($fonds_recharge_fcfa) }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 mt-12 shadow-3xl">
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="flex items-center justify-between py-5 px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-5">
                        <i class="fas fa-home text-2xl text-emerald-400 group-hover:scale-110 transition"></i>
                        <span class="text-xl font-medium text-white">Accueil</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400"></i>
                </a>

                <a href="{{ route('products') }}" class="flex items-center justify-between py-5 px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-5">
                        <i class="fas fa-solar-panel text-2xl text-yellow-400 group-hover:scale-110 transition"></i>
                        <span class="text-xl font-medium text-white">Produits</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400"></i>
                </a>

                <a href="{{ route('team') }}" class="flex items-center justify-between py-5 px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-5">
                        <i class="fas fa-users text-2xl text-purple-400 group-hover:scale-110 transition"></i>
                        <span class="text-xl font-medium text-white">Mon Équipe</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400"></i>
                </a>

                <a href="{{ route('Mesproduits') }}" class="flex items-center justify-between py-5 px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-5">
                        <i class="fas fa-gift text-2xl text-pink-400 group-hover:scale-110 transition"></i>
                        <span class="text-xl font-medium text-white">Mes Produits</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400"></i>
                </a>

                <a href="{{ route('transaction') }}" class="flex items-center justify-between py-5 px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-5">
                        <i class="fas fa-exchange-alt text-2xl text-blue-400 group-hover:scale-110 transition"></i>
                        <span class="text-xl font-medium text-white">Transactions</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400"></i>
                </a>

                <a href="{{ route('withdraw_info') }}" class="flex items-center justify-between py-5 px-6 bg-emerald-600/30 hover:bg-emerald-600/50 rounded-2xl transition group border-l-4 border-emerald-500">
                    <div class="flex items-center gap-5">
                        <i class="fas fa-credit-card text-2xl text-emerald-300 group-hover:scale-110 transition"></i>
                        <span class="text-xl font-bold text-emerald-300">Informations de retrait</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-300"></i>
                </a>
            </div>

            <!-- Déconnexion -->
            <div class="mt-12 pt-8 border-t border-white/20">
                <form action="{{ route('logout') }}" method="POST" class="text-center">
                    @csrf
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xl py-6 rounded-2xl shadow-2xl transition transform hover:scale-105">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</x-layouts>