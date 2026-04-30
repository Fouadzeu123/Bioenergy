<x-layouts :title="'Dépôt Réussi'" :level="Auth::user()->level">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden" style="background-color: #0f172a;">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="w-full max-w-sm relative z-10">
            <div class="rounded-[2.5rem] p-8 text-center animate__animated animate__zoomIn" style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                
                <!-- Success Animated Icon -->
                <div class="mb-8 relative">
                    <div class="w-20 h-20 bg-gradient-to-tr from-emerald-500 to-lime-400 rounded-full mx-auto flex items-center justify-center shadow-lg shadow-emerald-500/20 animate__animated animate__bounceIn">
                        <i class="fas fa-check text-3xl text-white"></i>
                    </div>
                    <div class="absolute inset-0 animate-ping opacity-20 rounded-full bg-emerald-400"></div>
                </div>

                <h2 class="text-2xl font-bold text-white mb-2 leading-tight">Félicitations !</h2>
                <p class="text-[12px] font-medium text-gray-400 mb-8 leading-relaxed">
                    Votre paiement a été confirmé. Votre compte a été crédité avec succès.
                </p>

                <!-- Transaction Details Card -->
                <div class="rounded-3xl p-5 mb-8 text-left space-y-4" style="background: #0d1117; border: 1px solid rgba(255, 255, 255, 0.06);">
                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                        <div>
                            <p class="text-[10px] font-bold text-emerald-400/80 uppercase tracking-wider mb-1">Montant Crédité</p>
                            <p class="text-xl font-bold text-white">{{ number_format($transaction->montant, 0, '.', ' ') }} <span class="text-xs text-emerald-400">{{ Auth::user()->currency }}</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Méthode</p>
                            <p class="text-sm font-bold text-blue-400">{{ $transaction->operator ?: 'NotchPay' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Référence</p>
                        <p class="text-[10px] font-mono font-medium text-gray-400 break-all leading-tight">
                            {{ $transaction->reference }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('dashboard') }}" class="block w-full py-4 rounded-2xl bg-white text-slate-900 font-bold text-[12px] transition-all hover:bg-gray-100 active:scale-95 shadow-lg shadow-white/5">
                        Tableau de Bord <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    
                    <a href="{{ route('transaction') }}" class="block w-full py-3 text-gray-500 hover:text-white font-bold text-[11px] transition-colors">
                        Consulter mon historique
                    </a>
                </div>
            </div>

            <!-- Trust Badge -->
            <div class="mt-8 flex items-center justify-center gap-2 text-gray-600">
                <i class="fas fa-shield-halved text-xs"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Paiement Sécurisé</span>
            </div>
        </div>
    </div>

    <!-- Confetti & Animations -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
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
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);
        });
    </script>
</x-layouts>
