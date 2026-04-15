<x-layouts :title="'Validation du Dépôt - BioEnergy'" :level="Auth::user()->level">
    <div class="min-h-screen bg-[#fcfdfe] flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-50 rounded-full blur-[100px] opacity-60"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[45%] h-[45%] bg-emerald-50 rounded-full blur-[100px] opacity-70"></div>

        <div class="w-full max-w-md relative z-10">
            <div class="bg-white/80 backdrop-blur-xl border border-white/40 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-8 text-center animate__animated animate__fadeIn">
                
                <!-- Loading Animation -->
                <div class="mb-10 relative">
                    <div class="w-24 h-24 bg-white rounded-full mx-auto flex items-center justify-center shadow-xl shadow-slate-200/50">
                        <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin"></div>
                        <svg class="w-10 h-10 text-emerald-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-2xl font-black text-slate-800 mb-2">Validation Requise</h2>
                <p class="text-slate-500 font-medium mb-8 leading-relaxed">
                    Un message de confirmation a été envoyé sur votre téléphone.<br>
                    <span class="text-emerald-600 font-bold">Veuillez valider l'opération via votre PIN.</span>
                </p>

                <!-- Info Card -->
                <div class="bg-slate-50/50 backdrop-blur-sm rounded-3xl p-6 mb-8 border border-slate-100 text-left space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 mt-1">Montant à valider</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-black text-slate-900">{{ number_format($transaction->montant, 2) }}</span>
                                <span class="text-sm font-bold text-emerald-600">$</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 mt-1">Opérateur</p>
                            <p class="text-xs font-black text-slate-700 uppercase">{{ $transaction->operator ?: 'Mobile Money' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Tracker -->
                <div class="space-y-4">
                    <div class="relative w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full bg-emerald-500 rounded-full transition-all duration-300" id="progressBar" style="width: 5%"></div>
                    </div>
                    <div class="flex justify-between items-center px-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Initialisation</span>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest animate-pulse" id="statusText">En attente de validation...</span>
                    </div>
                </div>

                <!-- Cancel Option -->
                <div class="mt-10 border-t border-slate-100 pt-6">
                    <p class="text-[11px] text-slate-400 font-medium mb-2">Le message n'apparaît pas ?</p>
                    <a href="{{ route('deposit') }}" class="text-xs font-bold text-slate-700 hover:text-rose-500 transition-colors">
                        Annuler et réessayer
                    </a>
                </div>
            </div>

            <!-- Footer Info -->
            <p class="mt-8 text-center text-slate-400 text-[11px] font-medium leading-relaxed opacity-60">
                Ne fermez pas cette page. <br>
                Elle s'actualisera automatiquement dès confirmation.
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-layouts>
