<x-layouts :title="'Produits'" :level="Auth::user()->level">

<!-- Messages -->
@if(session('success'))
<div class="max-w-xl mx-auto mt-4 px-4">
    <div class="rounded-2xl p-4 text-center text-[11px] font-semibold text-cyan-300 animate__animated animate__fadeInDown" style="background: rgba(6,182,212,0.12); border: 1px solid rgba(6,182,212,0.25);">
        {{ session('success') }}
    </div>
</div>
@endif
@if(session('error'))
<div class="max-w-xl mx-auto mt-4 px-4">
    <div class="rounded-2xl p-4 text-center text-[11px] font-semibold text-red-400 animate__animated animate__shakeX" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
        {{ session('error') }}
    </div>
</div>
@endif

<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-32">

    <!-- Hero Marketplace -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10 space-y-1">
            <h1 class="text-3xl font-extrabold tracking-tight leading-tight">Marché</h1>
            <p class="text-[11px] font-medium" style="color: rgba(147,197,253,0.8);">Infrastructures BioÉnergétiques</p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
        <div class="absolute -left-10 -top-10 w-32 h-32 rounded-full" style="background: rgba(6,182,212,0.1); filter: blur(24px);"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl p-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Rendement Max</p>
            <p class="text-lg font-bold text-blue-400">7.2% <span class="text-[10px] font-medium" style="color: #374151;">/ jour</span></p>
        </div>
        <div class="rounded-2xl p-5 group hover:border-blue-500/30 transition-colors" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('Mesproduits') }}" class="flex items-center justify-center h-full gap-2 text-[12px] font-bold text-gray-400 group-hover:text-blue-400 transition-colors">
                Mes Actifs <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Grille produits -->
    <div class="space-y-4">
        @foreach($produits as $produit)
            @php
                $purchases = Auth::user()->orders()->where('produit_id', $produit->id)->count();
                $canBuy = $purchases < $produit->limit_order;
            @endphp

            <div class="rounded-2xl overflow-hidden transition-all hover:border-blue-500/20" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <div class="relative h-44">
                    <img src="{{ asset($produit->image ? $produit->image : 'images/produits/produit' . $produit->id . '.jpg') }}" class="w-full h-full object-cover opacity-70">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, #0d1117 0%, rgba(13,17,23,0.4) 60%, transparent 100%);"></div>
                    <div class="absolute top-3 left-3">
                        <span class="text-[10px] font-bold px-3 py-1.5 rounded-full" style="background: rgba(59,130,246,0.2); color: #93c5fd; border: 1px solid rgba(59,130,246,0.3);">VIP {{ $produit->level }}</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex justify-between items-end">
                        <div>
                            <h3 class="text-lg font-bold text-white leading-tight">{{ $produit->name }}</h3>
                            <p class="text-[10px] font-medium text-cyan-400">Contrat 180 Jours</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-blue-400">{{ $produit->rate }}%</p>
                            <p class="text-[10px] font-medium" style="color: #4b5563;">Par jour</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 space-y-4">
                    <p class="text-[11px] font-medium leading-relaxed" style="color: #6b7280;">{{ $produit->description }}</p>

                    <div class="flex items-center justify-between rounded-xl p-4" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.12);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.15);">
                                <i class="fas fa-wallet text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold" style="color: #4b5563;">Investissement min.</p>
                                <p class="text-[12px] font-bold text-white">{{ fmtCurrency($produit->min_amount) }}</p>
                            </div>
                        </div>
                        <button onclick="openProductModal({{ $produit->id }})"
                                class="text-white text-[11px] font-bold px-5 py-2.5 rounded-xl active:scale-95 transition-all"
                                style="background: {{ $canBuy ? 'linear-gradient(135deg, #2563eb, #0891b2)' : 'rgba(107,114,128,0.2)' }}; {{ $canBuy ? 'box-shadow: 0 0 16px rgba(59,130,246,0.25);' : '' }}">
                            {{ $canBuy ? 'Investir' : 'Complet' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Produit -->
<div id="productModal" class="fixed inset-0 z-[110] hidden flex items-end sm:items-center justify-center backdrop-blur-sm p-0 sm:p-4" style="background: rgba(0,0,0,0.75);">
    <div class="rounded-t-[2rem] sm:rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden animate__animated animate__slideInUp" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
        <div class="relative h-44">
            <img id="modalImage" src="" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0" style="background: linear-gradient(to top, #0d1117, rgba(13,17,23,0.3), transparent);"></div>
            <button onclick="closeProductModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition" style="background: rgba(255,255,255,0.1);">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div class="absolute bottom-0 left-0 right-0 p-5">
                <h3 id="modalTitle" class="text-2xl font-bold text-white"></h3>
                <p id="modalRate" class="text-[11px] font-semibold text-cyan-400 mt-1"></p>
            </div>
        </div>

        <div class="p-6 space-y-5 max-h-[60vh] overflow-y-auto custom-scrollbar">
            <div class="rounded-2xl p-4" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
                <h4 class="text-[11px] font-bold text-blue-400 mb-2">Informations Projet</h4>
                <p id="modalInformation" class="text-[12px] font-medium leading-relaxed" style="color: #6b7280;"></p>
            </div>

            <form id="investForm" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-3">
                    <label class="block text-[11px] font-semibold text-center" style="color: #4b5563;">Montant de l'engagement</label>
                    <div class="relative">
                        <input type="number" name="amount" id="investAmount" step="1" required
                               class="w-full rounded-2xl px-6 py-4 text-xl font-bold text-center text-white outline-none transition"
                               style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);"
                               placeholder="0">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-xs font-semibold" style="color: #374151;">{{ Auth::user()->currency }}</span>
                    </div>
                    <p id="modalMin" class="text-[10px] font-semibold text-center text-blue-400"></p>
                </div>

                <button type="submit" class="w-full py-4 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition-all" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 24px rgba(59,130,246,0.3);">
                    Confirmer l'Investissement
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const CURRENCY = "{{ Auth::user()->currency }}";
    const produits = @json($produits->mapWithKeys(fn($p) => [$p->id => $p])->toArray());

    function openProductModal(id) {
        const p = produits[id];
        if (!p) return;
        document.getElementById('modalTitle').textContent = p.name;
        document.getElementById('modalImage').src = p.image ? `/${p.image}` : `/images/produits/produit${p.id}.jpg`;
        document.getElementById('modalInformation').textContent = p.description || p.information || 'Innovation durable pour un avenir énergétique autonome.';
        document.getElementById('modalRate').textContent = `Rendement Journalier : ${p.rate}%`;
        document.getElementById('modalMin').textContent = `Min. requis : ${Number(p.min_amount).toLocaleString('fr-FR')} ${CURRENCY}`;
        document.getElementById('investForm').action = `/products/${id}`;
        const input = document.getElementById('investAmount');
        input.value = p.min_amount;
        input.min = p.min_amount;
        if (p.max_amount) input.max = p.max_amount;
        document.getElementById('productModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => e.key === 'Escape' && closeProductModal());
</script>
</x-layouts>
