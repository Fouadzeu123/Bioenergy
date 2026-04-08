<x-layouts :title="'Produits'" :level="'Vip1'">

@php
    $USD_TO_F = 600;
@endphp

<!-- Messages -->
@if(session('success'))
<div class="max-w-lg mx-auto mt-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-md text-center">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="max-w-lg mx-auto mt-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-md text-center">
    {{ session('error') }}
</div>
@endif

<!-- Hero + CTA -->
<div class="w-full mt-6">
    <img src="{{ asset('images/biomasse.jpg') }}" alt="BioEnergy" class="w-full h-64 object-cover rounded-xl shadow-2xl">
</div>
<div class="text-center my-8">
    <a href="{{ route('Mesproduits') }}" class="inline-block bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold text-lg px-10 py-5 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-105 transition">
        Voir mes produits actifs
    </a>
</div>

<!-- Intro -->
<div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-10 text-center shadow-inner mb-12">
    <h2 class="text-4xl font-bold text-green-800 mb-4">Investissez dans l'énergie verte</h2>
    <p class="text-xl text-gray-700 leading-relaxed max-w-5xl mx-auto">
        Rendement journalier de <strong>2% à 8%</strong> selon le produit.<br>
        Revenus journaliers pendant <strong>365 jours</strong>.
    </p>
</div>

<!-- Grille produits -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto ">
@foreach($produits as $produit)
    @php
        $purchases = Auth::user()->orders()->where('produit_id', $produit->id)->count();
        $canBuy = $purchases < $produit->limit_order;
        $balanceOk = Auth::user()->account_balance >= ($produit->min_amount ?? 0);
    @endphp

    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden hover:shadow-3xl transform hover:-translate-y-3 transition-all duration-300">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6 text-center">
            <h3 class="text-2xl font-bold">{{ $produit->name }}</h3>
            <div class="text-4xl font-bold mt-2">{{ $produit->rate }}% / jour</div>
            <span class="inline-block mt-2 bg-white/20 px-4 py-1 rounded-full text-sm">Niveau {{ $produit->level }}</span>
        </div>

        <img src="{{ asset('images/produits/produit' . $produit->id . '.jpg') }}"
             alt="{{ $produit->name }}"
             class="w-full h-56 object-cover">

        <div class="p-6 space-y-5">
            <p class="text-gray-700,700 line-clamp-3">{{ $produit->description }}</p>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Minimum :</span>
                    <strong class="text-green-600">{{ fmtUsd($produit->min_amount) }}</strong>
                </div>
                @if($produit->max_amount)
                <div class="flex justify-between">
                    <span class="text-gray-600">Maximum :</span>
                    <strong class="text-blue-600">{{ fmtUsd($produit->max_amount) }}</strong>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Achats :</span>
                    <strong class="{{ $canBuy ? 'text-green-600' : 'text-red-600' }}">
                        {{ $purchases }} / {{ $produit->limit_order }}
                    </strong>
                </div>
            </div>

            <!-- Barre progression -->
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-700"
                     style="width: {{ $produit->limit_order > 0 ? ($purchases / $produit->limit_order) * 100 : 0 }}%"></div>
            </div>

            <!-- Boutons -->
            <div class="grid grid-cols-2 gap-4 pt-4">
                <button onclick="openProductModal({{ $produit->id }})"
                        class="bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
                    Détails
                </button>

                @if($canBuy && $balanceOk)
                    <button onclick="openProductModal({{ $produit->id }})"
                            class="bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition">
                        Investir
                    </button>
                @elseif(!$canBuy)
                    <button disabled class="bg-gray-500 text-white py-3 rounded-lg cursor-not-allowed">Limite atteinte</button>
                @else
                    <button disabled class="bg-orange-500 text-white py-3 rounded-lg cursor-not-allowed">Solde insuffisant</button>
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>

<!-- Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 p-4">
    <div class="bg-white rounded-2xl shadow-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-6 flex justify-between items-center">
            <h3 id="modalTitle" class="text-3xl font-bold text-gray-800"></h3>
            <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-800 text-4xl">×</button>
        </div>

        <div class="p-8 space-y-8">
            <div class="grid md:grid-cols-2 gap-8">
                <img id="modalImage" src="" alt="" class="w-full h-80 object-cover rounded-2xl shadow-xl">
                <div class="space-y-6">
                    <p id="modalDescription" class="text-lg text-gray-700 leading-relaxed"></p>
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl space-y-4">
                        <div class="flex justify-between text-xl">
                            <span class="font-bold">Taux Journalier :</span>
                            <span id="modalRate" class="text-green-600 font-bold"></span>
                        </div>
                        <div class="flex justify-between text-xl">
                            <span class="font-bold">Durée :</span>
                            <span class="font-bold">365 jours</span>
                        </div>
                        <div class="flex justify-between text-xl">
                            <span class="font-bold">Minimum :</span>
                            <span id="modalMin" class="text-green-600 font-bold"></span>
                        </div>
                        <div id="maxRow" class="flex justify-between text-xl hidden">
                            <span class="font-bold">Maximum :</span>
                            <span id="modalMax" class="text-blue-600 font-bold"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-2xl font-bold text-gray-800 mb-4">Informations détaillées</h4>
                <div id="modalInformation" class="bg-gray-50 p-8 rounded-2xl text-gray-700 whitespace-pre-line text-lg leading-relaxed"></div>
            </div>

            <!-- Formulaire -->
            <form id="investForm" method="POST" class="bg-green-50 p-8 rounded-2xl space-y-6">
                @csrf
                <div>
                    <label class="block text-xl font-bold mb-3">Montant à investir (en $)</label>
                    <input type="number" name="amount" id="investAmount" step="0.01" required
                           class="w-full px-6 py-4 text-xl border-2 border-green-300 rounded-xl focus:border-green-600 focus:outline-none"
                           placeholder="Ex: 1000.00">
                    <p id="amountInF" class="text-2xl font-bold text-green-700 mt-3"></p>
                    <p id="amountHelp" class="text-lg text-gray-600 mt-2"></p>
                </div>

                <div class="flex gap-6">
                    <button type="submit" class="flex-1 bg-green-600 text-white font-bold text-xl py-5 rounded-xl hover:bg-green-700 transition transform hover:scale-105">
                        Confirmer l'investissement
                    </button>
                    <button type="button" onclick="closeProductModal()" class="px-10 py-5 border-2 border-gray-400 rounded-xl hover:bg-gray-100 transition text-xl font-bold">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const USD_TO_F = 600;

    // On passe les produits proprement depuis Laravel
    const produits = @json($produits->mapWithKeys(fn($p) => [$p->id => $p])->toArray());

    function openProductModal(id) {
        const p = produits[id];
        if (!p) return;

        // Titre + image
        document.getElementById('modalTitle').textContent = p.name;
        document.getElementById('modalImage').src = `/images/produits/produit${p.id}.jpg`;

        // Contenu
        document.getElementById('modalDescription').textContent = p.description;
        document.getElementById('modalInformation').textContent = p.information || 'Aucune information supplémentaire.';

        document.getElementById('modalRate').textContent = p.rate + '%';
        document.getElementById('modalMin').textContent = Number(p.min_amount).toLocaleString() + ' $';

        const maxRow = document.getElementById('maxRow');
        const maxSpan = document.getElementById('modalMax');
        if (p.max_amount) {
            maxRow.classList.remove('hidden');
            maxSpan.textContent = Number(p.max_amount).toLocaleString() + ' $';
        } else {
            maxRow.classList.add('hidden');
        }

        // Formulaire
        document.getElementById('investForm').action = `/products/${id}`;

        const input = document.getElementById('investAmount');
        input.value = p.min_amount;
        input.min = p.min_amount;
        if (p.max_amount) input.max = p.max_amount;
        else input.removeAttribute('max');

        document.getElementById('amountHelp').textContent = 
            p.max_amount 
                ? `Montant accepté : ${p.min_amount}$ → ${p.max_amount}$`
                : `Minimum : ${p.min_amount}$`;

        // Conversion en temps réel
        input.oninput = function() {
            const val = parseFloat(this.value) || 0;
            document.getElementById('amountInF').textContent = 
                val > 0 ? `≈ ${val.toLocaleString()} $ = ${(val * USD_TO_F).toLocaleString()} F` : '';
        };
        input.oninput(); // trigger initial

        // Afficher modal
        document.getElementById('productModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Fermer avec Échap
    document.addEventListener('keydown', e => e.key === 'Escape' && closeProductModal());
</script>

</x-layouts>