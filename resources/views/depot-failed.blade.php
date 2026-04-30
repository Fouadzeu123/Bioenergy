<x-layouts :title="'Dépôt Échoué'" :level="Auth::user()->level">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden" style="background-color: #0f172a;">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-rose-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-slate-500/5 rounded-full blur-[120px]"></div>
        </div>

        <div class="w-full max-w-sm relative z-10">
            <div class="rounded-[2.5rem] p-8 text-center animate__animated animate__fadeIn" style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                
                <!-- Error Animated Icon -->
                <div class="mb-8 relative">
                    <div class="w-20 h-20 bg-gradient-to-tr from-rose-500 to-pink-500 rounded-full mx-auto flex items-center justify-center shadow-lg shadow-rose-500/20 animate__animated animate__shakeX">
                        <i class="fas fa-xmark text-3xl text-white"></i>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-white mb-2">Paiement Échoué</h2>
                <p class="text-[12px] font-medium text-gray-400 mb-8 leading-relaxed">
                    Nous n'avons pas pu finaliser votre transaction. Aucun montant n'a été débité de votre compte.
                </p>

                <!-- Status Card -->
                <div class="rounded-3xl p-5 mb-8 text-left space-y-4" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                        <div>
                            <p class="text-[10px] font-bold text-rose-400/80 uppercase tracking-wider mb-1">Status</p>
                            <p class="text-sm font-bold text-rose-500 uppercase">{{ $transaction->status }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Montant</p>
                            <p class="text-sm font-bold text-white">{{ number_format($transaction->montant, 0, '.', ' ') }} {{ Auth::user()->currency }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Référence</p>
                        <p class="text-[11px] font-mono font-medium text-gray-400 break-all leading-tight">
                            {{ $transaction->reference }}
                        </p>
                    </div>
                </div>

                <!-- Guidance -->
                <div class="flex items-center justify-center gap-4 mb-8">
                    <span class="text-[10px] font-bold text-gray-500 flex items-center gap-1">
                        <i class="fas fa-circle-info text-[8px] text-blue-400"></i>
                        Vérifiez votre solde
                    </span>
                    <span class="text-[10px] font-bold text-gray-500 flex items-center gap-1">
                        <i class="fas fa-rotate text-[8px] text-emerald-400"></i>
                        Réessayez
                    </span>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('deposit') }}" class="block w-full py-4 rounded-2xl bg-white text-slate-900 font-bold text-[12px] transition-all hover:bg-gray-100 active:scale-95 shadow-lg shadow-white/5">
                        <i class="fas fa-rotate-left mr-2"></i> Réessayer le Dépôt
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="block w-full py-3 text-gray-500 hover:text-white font-bold text-[11px] transition-colors">
                        Retour au Tableau de bord
                    </a>
                </div>
            </div>

            <!-- Support -->
            <p class="mt-8 text-center text-[11px] font-medium leading-relaxed" style="color: #6b7280;">
                Besoin d'aide ? Contactez notre <br>
                <a href="{{ route('contact') }}" class="text-rose-400 font-bold hover:underline">Support Technique</a>
            </p>
        </div>
    </div>
</x-layouts>
