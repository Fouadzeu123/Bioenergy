<x-layouts :title="'Dépôt Échoué - BioEnergy'" :level="Auth::user()->level">
    <div class="min-h-screen bg-[#fffafa] flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-[-10%] right-[-10%] w-[45%] h-[45%] bg-rose-50 rounded-full blur-[100px] opacity-70"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-slate-100 rounded-full blur-[100px] opacity-50"></div>

        <div class="w-full max-w-md relative z-10">
            <div class="bg-white/80 backdrop-blur-xl border border-white/40 rounded-[2.5rem] shadow-2xl shadow-rose-200/50 p-8 text-center animate__animated animate__fadeIn">
                
                <!-- Error Animated Icon -->
                <div class="mb-8 relative">
                    <div class="w-24 h-24 bg-gradient-to-tr from-rose-500 to-pink-400 rounded-full mx-auto flex items-center justify-center shadow-lg shadow-rose-200 animate__animated animate__shakeX">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-3xl font-black text-slate-800 mb-2">Paiement Échoué</h2>
                <p class="text-slate-500 font-medium mb-8 leading-relaxed">
                    Nous n'avons pas pu finaliser votre transaction. Ne vous inquiétez pas, aucun montant n'a été débité de votre compte.
                </p>

                <!-- Status Card -->
                <div class="bg-rose-50/50 backdrop-blur-sm rounded-3xl p-6 mb-8 border border-rose-100/50 text-left space-y-4">
                    <div class="flex justify-between items-start border-b border-rose-100 pb-4">
                        <div>
                            <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1 mt-1">Status de la Tentative</p>
                            <p class="text-sm font-black text-rose-600 uppercase">{{ $transaction->status }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 mt-1">Montant Visé</p>
                            <p class="text-sm font-black text-slate-700">{{ number_format($transaction->montant, 2) }} $</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ID Transaction</p>
                        <p class="text-[11px] font-mono font-bold text-slate-500 break-all bg-white/50 p-2 rounded-xl border border-slate-100 leading-none">
                            {{ $transaction->reference }}
                        </p>
                    </div>
                </div>

                <!-- Guidance -->
                <div class="text-slate-500 text-xs font-medium mb-8 flex items-center gap-3 justify-center">
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                    <span>Vérifiez votre solde Mobile Money</span>
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                    <span>Réessayez</span>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('deposit') }}" class="group relative block w-full overflow-hidden rounded-2xl bg-slate-900 p-4 font-bold text-white transition-all hover:bg-slate-800 active:scale-[0.98]">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Réessayer le Dépôt
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </span>
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="block w-full py-3 text-slate-500 hover:text-slate-800 font-bold text-sm transition-colors">
                        Retour au menu principal
                    </a>
                </div>
            </div>

            <!-- Support -->
            <p class="mt-8 text-center text-slate-400 text-[11px] font-medium leading-relaxed">
                Besoin d'aide ? Contactez notre support via <br>
                <a href="#" class="text-rose-400 font-bold hover:underline">Telegram</a> ou <a href="#" class="text-rose-400 font-bold hover:underline">WhatsApp</a>
            </p>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-layouts>
