<x-layouts :title="'Dépôt Réussi'" :level="Auth::user()->level">
    <div class="min-h-screen bg-emerald-50 flex flex-col items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-8 text-center animate__animated animate__tada">
            
            <div class="mb-6">
                <div class="w-24 h-24 bg-green-100 rounded-full mx-auto flex items-center justify-center shadow-inner">
                    <i class="fas fa-check-circle text-6xl text-green-500"></i>
                </div>
            </div>

            <h2 class="text-3xl font-black text-gray-800 mb-2">Paiement Réussi !</h2>
            <p class="text-gray-500 text-sm mb-6">
                Votre dépôt a été traité et votre solde a été mis à jour avec succès.
            </p>

            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100 flex justify-between items-center text-left">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Net Crédité</p>
                    <p class="text-2xl font-extrabold text-gray-800">{{ number_format($transaction->montant, 2) }} $</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Opérateur</p>
                    <p class="text-sm font-bold text-green-600 uppercase">{{ $transaction->operator ?? 'Mobile Money' }}</p>
                </div>
            </div>

            <a href="{{ route('deposit') }}" class="block w-full py-4 text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg transition">
                Retour aux dépôts
            </a>
            
            <a href="{{ route('dashboard') }}" class="block w-full py-3 mt-3 text-center text-gray-500 hover:text-gray-700 font-bold text-sm transition">
                Voir mon tableau de bord
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        confetti({ particleCount: 150, spread: 80, origin: { y: 0.6 } });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</x-layouts>
