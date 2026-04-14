<x-layouts :title="'Validation du Dépôt'" :level="Auth::user()->level">
    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-8 text-center animate__animated animate__zoomIn">
            
            <div class="mb-6 relative">
                <div class="w-24 h-24 bg-emerald-50 rounded-full mx-auto flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin text-5xl text-emerald-500"></i>
                </div>
            </div>

            <h2 class="text-2xl font-black text-gray-800 mb-2">Paiement en cours...</h2>
            <p class="text-gray-500 text-sm mb-6">
                Veuillez valider la transaction sur votre téléphone. La validation peut prendre quelques secondes.
            </p>

            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Montant</p>
                <p class="text-3xl font-extrabold text-emerald-600">{{ number_format($transaction->montant, 2) }} $</p>
                <p class="text-xs font-bold text-gray-500 mt-1">Via {{ $transaction->operator ?? 'Mobile Money' }}</p>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                <div class="bg-emerald-500 h-full animate-[progress_15s_ease-in-out_infinite]"></div>
            </div>
            
            <p class="text-xs text-gray-400 mt-4 font-semibold">Référence: {{ $transaction->reference }}</p>
        </div>
    </div>

    <script>
        const ref = "{{ $transaction->reference }}";
        const successRoute = "{{ route('depot.success', $transaction->reference) }}";
        const failedRoute = "{{ route('depot.failed', $transaction->reference) }}";

        let attempts = 0;
        const maxAttempts = 20; // ~ 1 minute maximum de vérification (20 * 3s = 60s)

        function checkStatus() {
            fetch(`/depot/status/${ref}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'completed') {
                        window.location.href = successRoute;
                    } else if (data.status === 'failed' || data.status === 'rejected' || data.status === 'canceled') {
                        window.location.href = failedRoute;
                    } else {
                        // pending
                        attempts++;
                        if (attempts >= maxAttempts) {
                            window.location.href = failedRoute; // Timeout
                        }
                    }
                })
                .catch(err => {
                    console.error("Erreur check status", err);
                });
        }

        // Vérifier toutes les 3 secondes
        setInterval(checkStatus, 3000);

        // Au bout de 10 secondes minimum d'attente (comme demandé par l'utilisateur),
        // on accélère la transition si on détecte que c'est fini, sinon ça continue jusqu'au timeout
        setTimeout(checkStatus, 10000); 

    </script>
    <style>
        @keyframes progress {
            0% { width: 0%; left: 0; }
            50% { width: 100%; left: 0; }
            100% { width: 0%; left: 100%; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</x-layouts>
