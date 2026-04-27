<x-layouts :title="'Bonus et Récompenses'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-20">

    <!-- Message succès -->
    @if(session('success'))
        <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-lg text-center font-bold text-[10px] animate__animated animate__fadeInDown">
            {{ session('success') }}
        </div>
    @endif

    <!-- Message erreur -->
    @if(session('error'))
        <div class="bg-red-500 text-white px-6 py-4 rounded-2xl shadow-lg text-center font-bold text-[10px] animate__animated animate__shakeX">
            {{ session('error') }}
        </div>
    @endif

    <!-- Hero Bonus Sleeker -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-8 text-white shadow-xl">
        <div class="relative z-10">
            <h1 class="text-xl font-bold">Centre de Récompenses</h1>
            <p class="text-[11px] font-semibold text-gray-400 mt-1">Échangez vos codes cadeaux</p>
            
            <form action="{{ route('bonus.reclamer') }}" method="POST" class="mt-8 relative">
                @csrf
                <input type="text" name="code" required 
                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:bg-white/10 focus:border-emerald-500 transition outline-none"
                       placeholder="Saisir votre code ici...">
                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-emerald-600 text-white px-6 rounded-xl text-[11px] font-bold active:scale-95 transition">
                    Valider
                </button>
            </form>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Stats Bonus Sleeker -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50">
            <p class="text-[10px] font-bold text-gray-400 mb-1">Total Reçu</p>
            <p class="text-sm font-bold text-gray-800">{{ fmtCurrency($historique->sum('montant')) }}</p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 text-right">
            <p class="text-[10px] font-bold text-gray-400 mb-1">Bonus Réclamés</p>
            <p class="text-sm font-bold text-emerald-600">{{ $historique->count() }} <span class="text-[10px] font-medium opacity-30">Codes</span></p>
        </div>
    </div>

    <!-- Historique Sleeker -->
    <div class="space-y-4">
        <h3 class="text-[11px] font-bold text-gray-400 px-2">Dernières Récompenses</h3>
        
        @forelse($historique as $tx)
            <div class="bg-white rounded-2xl p-4 flex items-center justify-between border border-gray-50 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-gift text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-800">Code Cadeau</p>
                        <p class="text-[10px] font-medium text-gray-400">{{ $tx->created_at->format('d/m/y • H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-emerald-600">+{{ fmtCurrency($tx->montant) }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50/50 rounded-3xl border border-dashed border-gray-100">
                <p class="text-[11px] font-bold text-gray-300">Aucun bonus reçu</p>
            </div>
        @endforelse
    </div>

    <!-- CTA Support Sleeker -->
    <div class="bg-emerald-600/5 rounded-[32px] p-8 border border-emerald-100 text-center">
        <p class="text-[10px] font-bold text-emerald-700 mb-4">Besoin d'un code ?</p>
        <p class="text-[11px] font-medium text-emerald-800/60 leading-relaxed mb-6">Suivez notre canal officiel ou contactez votre parrain pour obtenir des codes cadeaux exclusifs.</p>
        <a href="{{ route('contact') }}" class="inline-block text-[11px] font-bold text-emerald-700 border-b-2 border-emerald-200 pb-1 hover:text-emerald-900 transition">
            Contacter le support
        </a>
    </div>
</div>

@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        setTimeout(() => {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#10b981', '#059669', '#34d399']
            });
        }, 200);
    </script>
@endif
</x-layouts>