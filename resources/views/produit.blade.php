<x-layouts :title="'Produits'" :level="Auth::user()->level">

<!-- Messages -->
@if(session('success'))
<div class="max-w-lg mx-auto mt-6 bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-lg text-center font-black text-[10px] uppercase tracking-widest animate__animated animate__fadeInDown">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="max-w-lg mx-auto mt-6 bg-red-500 text-white px-6 py-4 rounded-2xl shadow-lg text-center font-black text-[10px] uppercase tracking-widest animate__animated animate__shakeX">
    {{ session('error') }}
</div>
@endif

<!-- Main Container -->
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-32">

    <!-- Hero Marketplace Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl">
        <div class="relative z-10 space-y-2">
            <h1 class="text-3xl font-bold tracking-tight leading-none">Marché</h1>
            <p class="text-[10px] font-semibold text-emerald-400">Infrastructures BioÉnergétiques</p>
        </div>
        <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-50">
            <p class="text-[10px] font-bold text-gray-400 mb-1">Rendement Max</p>
            <p class="text-lg font-bold text-emerald-600">7.2% <span class="text-[10px] font-medium text-gray-300">/ jour</span></p>
        </div>
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 flex items-center justify-center">
            <a href="{{ route('Mesproduits') }}" class="text-[10px] font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                Mes Actifs <i class="fas fa-chevron-right text-[8px]"></i>
            </a>
        </div>
    </div>

    <!-- Grille produits Sleeker -->
    <div class="space-y-6">
        @foreach($produits as $produit)
            @php
                $purchases = Auth::user()->orders()->where('produit_id', $produit->id)->count();
                $canBuy = $purchases < $produit->limit_order;
            @endphp

            <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
                <div class="relative h-48">
                    <img src="{{ asset('images/produits/produit' . $produit->id . '.jpg') }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-slate-900 text-white text-[9px] font-bold px-4 py-1.5 rounded-full shadow-xl">VIP {{ $produit->level }}</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 flex justify-between items-end">
                        <div class="space-y-1">
                            <h3 class="text-lg font-black text-gray-800 italic leading-tight">{{ $produit->name }}</h3>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Contrat 180 Jours</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-emerald-600">{{ $produit->rate }}%</p>
                            <p class="text-[9px] font-bold text-gray-300">Par jour</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0 space-y-6">
                    <p class="text-[11px] text-gray-500 font-medium leading-relaxed px-2">
                        {{ $produit->description }}
                    </p>

                    <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                <i class="fas fa-wallet text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-gray-400">Invest. Min.</p>
                                <p class="text-[11px] font-bold text-gray-800">{{ fmtCurrency($produit->min_amount) }}</p>
                            </div>
                        </div>
                        <button onclick="openProductModal({{ $produit->id }})" 
                                class="bg-slate-900 text-white text-[10px] font-bold px-6 py-2.5 rounded-xl active:scale-95 transition shadow-lg shadow-slate-200">
                            {{ $canBuy ? 'Investir' : 'Complet' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Produit Sleeker -->
<div id="productModal" class="fixed inset-0 z-[110] hidden flex items-end sm:items-center justify-center bg-slate-900/80 backdrop-blur-sm p-0 sm:p-4">
    <div class="bg-white rounded-t-[40px] sm:rounded-[40px] shadow-2xl max-w-lg w-full overflow-hidden animate__animated animate__slideInUp">
        <div class="relative h-56">
            <img id="modalImage" src="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
            <button onclick="closeProductModal()" class="absolute top-6 right-6 w-10 h-10 bg-black/20 backdrop-blur-md text-white rounded-full flex items-center justify-center">
                <i class="fas fa-times text-xs"></i>
            </button>
            <div class="absolute bottom-0 left-0 right-0 p-8">
                <h3 id="modalTitle" class="text-2xl font-black text-gray-800 italic"></h3>
                <p id="modalRate" class="text-emerald-600 font-black text-[10px] uppercase tracking-[0.2em] mt-1"></p>
            </div>
        </div>

        <div class="p-8 space-y-8 max-h-[60vh] overflow-y-auto custom-scrollbar">
            <div class="bg-slate-50 rounded-3xl p-6 border border-gray-100">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Informations Projet</h4>
                <p id="modalInformation" class="text-[11px] text-gray-600 leading-relaxed font-medium italic"></p>
            </div>

            <form id="investForm" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Montant de l'engagement</label>
                    <div class="relative">
                        <input type="number" name="amount" id="investAmount" step="1" required
                               class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-2xl font-black text-center focus:bg-white focus:border-emerald-500 transition outline-none"
                               placeholder="0">
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-gray-300 italic">{{ Auth::user()->currency }}</span>
                    </div>
                    <p id="modalMin" class="text-[9px] font-black text-center text-emerald-600 uppercase tracking-widest"></p>
                </div>

                <button type="submit" class="w-full py-5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl active:scale-95 transition">
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
        document.getElementById('modalImage').src = `/images/produits/produit${p.id}.jpg`;
        document.getElementById('modalInformation').textContent = p.information || 'Innovation durable pour un avenir énergétique autonome.';
        document.getElementById('modalRate').textContent = `Rendement Journalier: ${p.rate}%`;
        document.getElementById('modalMin').textContent = `Min. requis: ${Number(p.min_amount).toLocaleString('fr-FR')} ${CURRENCY}`;

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
