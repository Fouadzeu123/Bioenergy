<x-layouts :title="'Réclamer un Bonus'" :level="Auth::user()->level">

<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-8 sm:py-12">
    <div class="max-w-lg mx-auto">

        <!-- Carte principale -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-green-100">

            <!-- Header coloré -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-10 sm:py-12 text-center relative">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <div class="text-5xl sm:text-6xl mb-3 animate-bounce inline-block">Gift</div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                        Réclamez<br class="sm:hidden"> Votre Bonus !
                    </h1>
                    <p class="text-green-100 text-sm sm:text-base mt-3 font-medium px-4">
                        Code secret = récompense instantanée
                    </p>
                </div>
            </div>

            <div class="p-6 sm:p-10">

                <!-- Message succès avec confettis -->
                @if(session('success'))
                    <div class="mb-8 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-5 rounded-2xl shadow-xl text-center font-bold text-lg sm:text-xl flex items-center justify-center gap-3 animate-pulse">
                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Message erreur -->
                @if(session('error'))
                    <div class="mb-8 bg-gradient-to-r from-red-500 to-pink-600 text-white px-5 py-5 rounded-2xl shadow-xl text-center font-bold text-lg flex items-center justify-center gap-3">
                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Formulaire -->
                <form action="{{ route('bonus.reclamer') }}" method="POST" class="space-y-8">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-bold text-lg text-center mb-5">
                            Votre code bonus
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>

                            <input type="text"
                                   name="code"
                                   required
                                   autocomplete="off"
                                   class="w-full pl-14 pr-6 py-5 sm:py-6 text-2xl sm:text-3xl font-mono tracking-widest text-center uppercase placeholder-gray-400
                                          border-4 border-green-200 rounded-2xl focus:outline-none focus:border-green-600 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-gray-50 shadow-inner"
                                   placeholder="BONUS2025"
                                   maxlength="20">
                        </div>

                        <p class="text-center text-xs sm:text-sm text-gray-500 mt-4">
                            Sensible à la casse • Ex: <code class="bg-gray-200 px-2 py-1 rounded font-mono text-xs">WELCOME50</code>
                        </p>
                    </div>

                    <!-- Bouton principal -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 
                                   text-white font-extrabold text-xl sm:text-2xl py-5 sm:py-6 rounded-2xl 
                                   shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 
                                   flex items-center justify-center gap-3">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m5-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        RÉCLAMER LE BONUS
                    </button>
                </form>

                <!-- Mini-badges -->
                <div class="mt-10 grid grid-cols-3 gap-3 text-center">
                    <div class="bg-yellow-100 rounded-xl py-3 px-2 border-2 border-yellow-300">
                        <div class="text-2xl sm:text-3xl">Fast</div>
                        <p class="text-[10px] sm:text-xs font-bold text-yellow-800">Instantané</p>
                    </div>
                    <div class="bg-purple-100 rounded-xl py-3 px-2 border-2 border-purple-300">
                        <div class="text-2xl sm:text-3xl">Secure</div>
                        <p class="text-[10px] sm:text-xs font-bold text-purple-800">Sécurisé</p>
                    </div>
                    <div class="bg-pink-100 rounded-xl py-3 px-2 border-2 border-pink-300">
                        <div class="text-2xl sm:text-3xl">Gift</div>
                        <p class="text-[10px] sm:text-xs font-bold text-pink-800">Exclusif</p>
                    </div>
                </div>

                <p class="text-center text-gray-400 text-xs mt-8 leading-relaxed">
                    Une question ? WhatsApp au +1(203)01289123<br>
                    Bonus soumis aux conditions générales
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Confettis uniquement sur mobile quand succès -->
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        setTimeout(() => {
            confetti({
                particleCount: 120,
                spread: 80,
                origin: { y: 0.65 },
                colors: ['#10b981', '#059669', '#34d399', '#6ee7b7']
            });
        }, 300);
    </script>
@endif

</x-layouts>