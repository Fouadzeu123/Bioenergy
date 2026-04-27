<x-layouts :title="'Mon Compte'" :level="Auth::user()->level">

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-emerald-900 to-slate-900">

    <!-- Carte principale premium -->
    <div class="max-w-5xl mx-auto pt-6 sm:pt-12 pb-20 px-4">

        <!-- Profil + Solde principal -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-3xl overflow-hidden">
            <div class="bg-gradient-to-br from-emerald-600/30 to-teal-700/30 p-6 sm:p-10 text-white">
                <div class="flex flex-col md:flex-row items-center gap-6 sm:gap-8">
                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white text-3xl sm:text-5xl font-extrabold shadow-2xl border-4 border-white/50">
                        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-2xl sm:text-4xl font-extrabold mb-1 sm:mb-3">{{ Auth::user()->username }}</h1>
                        <p class="text-lg sm:text-2xl opacity-90">
                            {{ Auth::user()->phone ? substr(Auth::user()->phone,0,3).'****'.substr(Auth::user()->phone,-3) : '6****0000' }}
                        </p>
                        <div class="flex items-center justify-center md:justify-start gap-4 mt-4 sm:mt-6">
                            <span class="px-4 py-2 sm:px-6 sm:py-3 bg-white/20 rounded-full font-bold text-base sm:text-xl">
                                VIP {{ Auth::user()->level }}
                            </span>
                            <a href="{{ route('profile.edit') }}" class="text-white hover:text-emerald-300">
                                <i class="fas fa-cog text-2xl sm:text-3xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portefeuille principal -->
            <div class="p-6 sm:p-10 text-white">
                <h2 class="text-xl sm:text-3xl font-extrabold mb-6 sm:mb-10 text-center">Mon Portefeuille</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-8 mb-10 sm:mb-12">
                    <div class="bg-white/10 rounded-3xl p-6 sm:p-8 text-center border border-white/20">
                        <p class="text-green-300 text-sm sm:text-lg mb-2 sm:mb-4">Solde disponible</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-emerald-400">{{ fmtCurrency($solde_total) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-3xl p-6 sm:p-8 text-center border border-white/20">
                        <p class="text-green-300 text-sm sm:text-lg mb-2 sm:mb-4">Revenu total</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-yellow-400">{{ fmtCurrency($revenu_total) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-3xl p-6 sm:p-8 text-center border border-white/20">
                        <p class="text-red-300 text-sm sm:text-lg mb-2 sm:mb-4">Total retiré</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-red-400">{{ fmtCurrency($total_retraits) }}</p>
                    </div>
                </div>

                <!-- Stats secondaires -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-center">
                    <div class="bg-white/10 rounded-2xl p-4 sm:p-6 border border-white/5">
                        <p class="text-green-200 text-[10px] sm:text-sm uppercase tracking-wide">Gain fixe / jour</p>
                        <p class="text-lg sm:text-3xl font-bold text-emerald-400">{{ fmtCurrency($capturer_benefices) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4 sm:p-6 border border-white/5">
                        <p class="text-blue-200 text-[10px] sm:text-sm uppercase tracking-wide">Taille équipe</p>
                        <p class="text-lg sm:text-3xl font-bold text-white">{{ $taille_equipe }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4 sm:p-6 border border-white/5">
                        <p class="text-yellow-200 text-[10px] sm:text-sm uppercase tracking-wide">Revenu équipe</p>
                        <p class="text-lg sm:text-3xl font-bold text-yellow-400">{{ fmtCurrency($revenu_equipe) }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4 sm:p-6 border border-white/5">
                        <p class="text-purple-200 text-[10px] sm:text-sm uppercase tracking-wide">Fonds recharge</p>
                        <p class="text-lg sm:text-3xl font-bold text-purple-400">{{ fmtCurrency($fonds_recharge) }}</p>
                    </div>
                </div>

                <!-- Boutons principaux -->
                <div class="grid grid-cols-2 gap-4 sm:gap-8 mt-10 sm:mt-12">
                    <a href="{{ route('deposit') }}"
                       class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-base sm:text-xl py-5 sm:py-8 rounded-2xl text-center shadow-2xl transform hover:scale-[1.02] transition">
                        RECHARGER
                    </a>
                    <a href="{{ route('retrait') }}"
                       class="bg-gradient-to-r from-gray-700 to-black hover:from-black hover:to-gray-900 text-white font-extrabold text-base sm:text-xl py-5 sm:py-8 rounded-2xl text-center shadow-2xl transform hover:scale-[1.02] transition">
                        RETIRER
                    </a>
                </div>
            </div>
        </div>

        <!-- Section blanche en bas -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 sm:p-10 mt-8 sm:mt-12 shadow-3xl">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 text-white">
                <div class="text-center">
                    <p class="text-green-300 text-xs sm:text-sm mb-1 sm:mb-3">Équilibre</p>
                    <p class="text-xl sm:text-3xl font-extrabold">{{ fmtCurrency($solde_total) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-yellow-300 text-xs sm:text-sm mb-1 sm:mb-3">Revenu épargne</p>
                    <p class="text-xl sm:text-3xl font-extrabold">{{ fmtCurrency($revenu_epargne) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-purple-300 text-xs sm:text-sm mb-1 sm:mb-3">Total épargne</p>
                    <p class="text-xl sm:text-3xl font-extrabold">{{ fmtCurrency($total_epargne) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-blue-300 text-xs sm:text-sm mb-1 sm:mb-3">Fonds recharge</p>
                    <p class="text-xl sm:text-3xl font-extrabold">{{ fmtCurrency($fonds_recharge) }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 sm:p-8 mt-8 sm:mt-12 shadow-3xl">
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="flex items-center justify-between py-4 sm:py-5 px-5 sm:px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-4 sm:gap-5">
                        <i class="fas fa-home text-lg sm:text-2xl text-emerald-400 group-hover:scale-110 transition"></i>
                        <span class="text-base sm:text-xl font-medium text-white">Accueil</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400 text-xs"></i>
                </a>

                <a href="{{ route('products') }}" class="flex items-center justify-between py-4 sm:py-5 px-5 sm:px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-4 sm:gap-5">
                        <i class="fas fa-solar-panel text-lg sm:text-2xl text-yellow-400 group-hover:scale-110 transition"></i>
                        <span class="text-base sm:text-xl font-medium text-white">Produits</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400 text-xs"></i>
                </a>

                <a href="{{ route('team') }}" class="flex items-center justify-between py-4 sm:py-5 px-5 sm:px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-4 sm:gap-5">
                        <i class="fas fa-users text-lg sm:text-2xl text-purple-400 group-hover:scale-110 transition"></i>
                        <span class="text-base sm:text-xl font-medium text-white">Mon Équipe</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400 text-xs"></i>
                </a>

                <a href="{{ route('Mesproduits') }}" class="flex items-center justify-between py-4 sm:py-5 px-5 sm:px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-4 sm:gap-5">
                        <i class="fas fa-gift text-lg sm:text-2xl text-pink-400 group-hover:scale-110 transition"></i>
                        <span class="text-base sm:text-xl font-medium text-white">Mes Produits</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400 text-xs"></i>
                </a>

                <a href="{{ route('transaction') }}" class="flex items-center justify-between py-4 sm:py-5 px-5 sm:px-6 bg-white/10 hover:bg-white/20 rounded-2xl transition group">
                    <div class="flex items-center gap-4 sm:gap-5">
                        <i class="fas fa-exchange-alt text-lg sm:text-2xl text-blue-400 group-hover:scale-110 transition"></i>
                        <span class="text-base sm:text-xl font-medium text-white">Transactions</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-400 text-xs"></i>
                </a>

                <a href="{{ route('withdraw_info') }}" class="flex items-center justify-between py-4 sm:py-5 px-5 sm:px-6 bg-emerald-600/30 hover:bg-emerald-600/50 rounded-2xl transition group border-l-4 border-emerald-500">
                    <div class="flex items-center gap-4 sm:gap-5">
                        <i class="fas fa-credit-card text-lg sm:text-2xl text-emerald-300 group-hover:scale-110 transition"></i>
                        <span class="text-base sm:text-xl font-bold text-emerald-300">Informations de retrait</span>
                    </div>
                    <i class="fas fa-chevron-right text-emerald-300 text-xs"></i>
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