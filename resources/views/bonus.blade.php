<x-layouts :title="'Bonus et Récompenses'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Hero Bonus -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <h1 class="text-xl font-bold">Centre de Récompenses</h1>
            <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Échangez vos codes cadeaux</p>

            <form action="{{ route('bonus.reclamer') }}" method="POST" class="mt-6 relative">
                @csrf
                <input type="text" name="code" required
                       class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-white outline-none transition"
                       style="background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.15);"
                       placeholder="Saisir votre code ici...">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2.5 rounded-xl text-[11px] font-bold transition active:scale-95" style="background: rgba(59,130,246,0.9);">
                    Valider
                </button>
            </form>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl p-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Total Reçu</p>
            <p class="text-sm font-bold text-white">{{ fmtCurrency($historique->sum('montant')) }}</p>
        </div>
        <div class="rounded-2xl p-5 text-right" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Bonus Réclamés</p>
            <p class="text-sm font-bold text-blue-400">{{ $historique->count() }} <span class="text-[10px] font-medium opacity-40">Codes</span></p>
        </div>
    </div>

    <!-- Historique -->
    <div class="space-y-3">
        <h3 class="text-[11px] font-semibold px-1" style="color: #4b5563;">Dernières Récompenses</h3>

        @forelse($historique as $tx)
            <div class="rounded-2xl p-4 flex items-center justify-between" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                        <i class="fas fa-gift text-blue-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-white">Code Cadeau</p>
                        <p class="text-[10px] font-medium" style="color: #4b5563;">{{ $tx->created_at->format('d/m/y • H:i') }}</p>
                    </div>
                </div>
                <p class="text-xs font-bold text-cyan-400">+{{ fmtCurrency($tx->montant) }}</p>
            </div>
        @empty
            <div class="text-center py-12 rounded-2xl border border-dashed" style="border-color: rgba(255,255,255,0.08);">
                <p class="text-[11px] font-semibold" style="color: #374151;">Aucun bonus reçu</p>
            </div>
        @endforelse
    </div>

    <!-- CTA Support -->
    <div class="rounded-2xl p-6 text-center" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
        <p class="text-[11px] font-semibold text-blue-400 mb-2">Besoin d'un code ?</p>
        <p class="text-[11px] font-medium leading-relaxed mb-4" style="color: #4b5563;">Suivez notre canal officiel ou contactez votre parrain pour obtenir des codes cadeaux exclusifs.</p>
        <a href="{{ route('contact') }}" class="inline-block text-[11px] font-bold text-blue-400 border-b border-blue-400/30 pb-0.5 hover:text-blue-300 transition">
            Contacter le support
        </a>
    </div>
</div>

@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        setTimeout(() => {
            confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 }, colors: ['#3b82f6', '#06b6d4', '#60a5fa'] });
        }, 200);
    </script>
@endif
</x-layouts>