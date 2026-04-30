<x-layouts :title="'Validation du Dépôt'" :level="Auth::user()->level">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden" style="background-color: #0f172a;">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="w-full max-w-sm relative z-10">
            <div class="rounded-[2.5rem] p-8 text-center animate__animated animate__fadeIn" style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                
                <!-- Loading Animation -->
                <div class="mb-10 relative">
                    <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center relative" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                        <div class="absolute inset-0 rounded-full border-[3px] border-emerald-500/20"></div>
                        <div class="absolute inset-0 rounded-full border-[3px] border-emerald-500 border-t-transparent animate-spin"></div>
                        <i class="fas fa-mobile-screen-button text-3xl text-emerald-400 animate-pulse"></i>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-white mb-2">Validation Requise</h2>
                <p class="text-[12px] font-medium mb-8 leading-relaxed text-gray-400">
                    Consultez votre téléphone pour valider l'opération.<br>
                    <span class="text-emerald-400 font-bold">Saisissez votre code PIN secret.</span>
                </p>

                <!-- Info Card -->
                <div class="rounded-3xl p-5 mb-8 text-left space-y-4" style="background: #0d1117; border: 1px solid rgba(255, 255, 255, 0.06);">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Montant à valider</p>
                            <p class="text-xl font-bold text-white">{{ number_format($transaction->montant, 0, '.', ' ') }} <span class="text-sm font-bold text-emerald-400">{{ Auth::user()->currency }}</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Opérateur</p>
                            <p class="text-sm font-bold text-blue-400">{{ $transaction->operator ?: 'Mobile Money' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Tracker -->
                <div class="space-y-3">
                    <div class="relative w-full h-1.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.06);">
                        <div class="absolute top-0 left-0 h-full bg-emerald-500 rounded-full transition-all duration-300 shadow-[0_0_10px_rgba(16,185,129,0.5)]" id="progressBar" style="width: 5%"></div>
                    </div>
                    <div class="flex justify-between items-center px-1">
                        <span class="text-[10px] font-bold text-gray-500">Initialisation</span>
                        <span class="text-[10px] font-bold text-emerald-400 animate-pulse" id="statusText">En attente de validation...</span>
                    </div>
                </div>

                <!-- Cancel Option -->
                <div class="mt-8 pt-6" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('deposit') }}" class="text-[11px] font-bold text-gray-400 hover:text-rose-400 transition-colors">
                        Annuler et réessayer
                    </a>
                </div>
            </div>

            <!-- Footer Info -->
            <p class="mt-8 text-center text-[10px] font-medium leading-relaxed" style="color: #6b7280;">
                Ne fermez pas cette page. <br>
                Elle s'actualisera automatiquement dès la confirmation.
            </p>
        </div>
    </div>

    <script>
        const ref = "{{ $transaction->reference }}";
        const successRoute = "{{ route('depot.success', $transaction->reference) }}";
        const failedRoute = "{{ route('depot.failed', $transaction->reference) }}";
        const progressBar = document.getElementById('progressBar');
        const statusText = document.getElementById('statusText');

        let attempts = 0;
        const maxAttempts = 60; // 3 minutes maximum

        function updateProgress() {
            const progress = 5 + (attempts / maxAttempts) * 95;
            progressBar.style.width = `${progress}%`;
        }

        function checkStatus() {
            fetch(`/depot/status/${ref}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'completed' || data.status === 'complete') {
                        statusText.innerText = "Paiement Confirmé ! Redirection...";
                        progressBar.style.backgroundColor = "#10b981";
                        progressBar.style.width = "100%";
                        setTimeout(() => window.location.href = successRoute, 1000);
                    } else if (data.status === 'failed' || data.status === 'rejected' || data.status === 'canceled' || data.status === 'expired') {
                        window.location.href = failedRoute;
                    } else {
                        attempts++;
                        updateProgress();
                        if (attempts >= maxAttempts) {
                            window.location.href = failedRoute; 
                        }
                    }
                })
                .catch(err => {
                    console.error("Erreur polling", err);
                });
        }

        // Démarrage du polling
        const pollInterval = setInterval(checkStatus, 3000);
    </script>
</x-layouts>
