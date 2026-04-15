<x-layouts :title="'Dépôt Réussi - BioEnergy'" :level="Auth::user()->level">
    <div class="min-h-screen bg-[#f8fafc] flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-100 rounded-full blur-[100px] opacity-50"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-100 rounded-full blur-[100px] opacity-50"></div>

        <div class="w-full max-w-md relative z-10">
            <div class="bg-white/80 backdrop-blur-xl border border-white/40 rounded-[2.5rem] shadow-2xl shadow-emerald-200/50 p-8 text-center animate__animated animate__zoomIn">
                
                <!-- Success Animated Icon -->
                <div class="mb-8 relative">
                    <div class="w-24 h-24 bg-gradient-to-tr from-emerald-500 to-lime-400 rounded-full mx-auto flex items-center justify-center shadow-lg shadow-emerald-200 animate__animated animate__bounceIn animate__delay-1s">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="absolute top-0 left-0 w-full h-full animate-ping opacity-20 rounded-full bg-emerald-400"></div>
                </div>

                <h2 class="text-3xl font-black text-slate-800 mb-2 leading-tight">Félicitations !</h2>
                <p class="text-slate-500 font-medium mb-8">
                    Votre paiement a été confirmé. Votre investissement est prêt à générer des profits.
                </p>

                <!-- Transaction Details Card -->
                <div class="bg-slate-50/50 backdrop-blur-sm rounded-3xl p-6 mb-8 border border-slate-100 text-left space-y-4">
                    <div class="flex justify-between items-end border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Montant Crédité</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-slate-900">{{ number_format($transaction->montant, 2) }}</span>
                                <span class="text-lg font-bold text-emerald-600">$</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Valeur Locale</p>
                            <p class="text-sm font-black text-slate-700">~ {{ number_format($transaction->montant_fcfa ?? ($transaction->montant * 600), 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Méthode</p>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <p class="text-xs font-bold text-slate-700 uppercase">{{ $transaction->operator ?: 'NotchPay' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Référence</p>
                            <p class="text-[10px] font-mono font-bold text-slate-500 break-all">{{ substr($transaction->reference, -12) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('dashboard') }}" class="group relative block w-full overflow-hidden rounded-2xl bg-slate-900 p-4 font-bold text-white transition-all hover:bg-slate-800 active:scale-[0.98]">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Aller au Tableau de Bord
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </a>
                    
                    <a href="{{ route('transaction') }}" class="block w-full py-3 text-slate-500 hover:text-emerald-600 font-bold text-sm transition-colors">
                        Consulter mon historique
                    </a>
                </div>
            </div>

            <!-- Trust Badge -->
            <div class="mt-8 flex items-center justify-center gap-2 text-slate-400 opacity-60">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 4.908-3.367 9.132-8 10.125a12.01 12.01 0 01-8-10.125c0-.681.057-1.35.166-2.001zM10 21a11.954 11.954 0 01-10-10C0 4.477 4.477 0 10 0s10 4.477 10 10c0 5.523-4.477 10-10 10z" clip-rule="evenodd"></path></svg>
                <span class="text-[10px] font-bold tracking-widest uppercase">Paiement Sécurisé par NotchPay</span>
            </div>
        </div>
    </div>

    <!-- Confetti & Animations -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <script>
        window.addEventListener('load', () => {
            const duration = 3 * 1000;
            const animationEnd = Date.now() + duration;
            const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);
                // since particles fall down, start a bit higher than random
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);
        });
    </script>
</x-layouts>
