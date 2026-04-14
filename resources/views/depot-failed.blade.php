<x-layouts :title="'Dépôt Échoué'" :level="Auth::user()->level">
    <div class="min-h-screen bg-red-50 flex flex-col items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-8 text-center animate__animated animate__shakeX">
            
            <div class="mb-6">
                <div class="w-24 h-24 bg-red-100 rounded-full mx-auto flex items-center justify-center shadow-inner">
                    <i class="fas fa-times-circle text-6xl text-red-500"></i>
                </div>
            </div>

            <h2 class="text-3xl font-black text-gray-800 mb-2">Paiement Échoué</h2>
            <p class="text-gray-500 text-sm mb-6">
                Le paiement a été annulé, expiré ou n'a pas pu être traité.<br>Votre solde n'a pas été débité.
            </p>

            <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100 text-left">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 mt-1">Référence Tentative</p>
                <p class="text-sm font-mono font-bold text-gray-800">{{ $transaction->reference }}</p>
                
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4">Statut Système</p>
                <p class="text-xs font-bold text-red-600 uppercase">{{ $transaction->status }}</p>
            </div>

            <a href="{{ route('deposit') }}" class="block w-full py-4 text-center bg-gray-800 hover:bg-black text-white font-bold rounded-xl shadow-lg transition">
                Réessayer le dépôt
            </a>
            
            <a href="{{ route('dashboard') }}" class="block w-full py-3 mt-3 text-center text-gray-500 hover:text-gray-700 font-bold text-sm transition">
                Retour au menu
            </a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</x-layouts>
