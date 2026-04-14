<x-layouts :title="'Mes Transactions'" :level="Auth::user()->level">
@php
    $rate = $rateFCFAperUSD ?? 600;
    $types = [
        'depot' => 'Dépôt',
        'retrait' => 'Retrait',
        'achat' => 'Achat',
        'gain_journalier' => 'Gain journalier',
        'bonus_vip' => 'Bonus VIP',
        'bonus_journalier' => 'Bonus journalier',
        'parrainage' => 'Parrainage'
    ];
    $statuses = [
        'pending' => ['label' => 'En attente', 'color' => 'yellow'],
        'completed' => ['label' => 'Validé', 'color' => 'green'],
        'rejected' => ['label' => 'Rejeté', 'color' => 'red']
    ];

    $depots = $transactions->where('type', 'depot');
    $retraits = $transactions->where('type', 'retrait');
    $autres = $transactions->whereNotIn('type', ['depot', 'retrait']);
@endphp

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 space-y-8">

    <!-- En-tête -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-900 rounded-3xl shadow-xl p-6 sm:p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-4xl font-extrabold mb-2">Historique des transactions</h1>
            <p class="text-blue-100 text-sm sm:text-base opacity-90 max-w-lg">Consultez tous vos mouvements financiers. Basculez entre vos dépôts, vos retraits et vos autres transactions.</p>
        </div>
        <i class="fas fa-file-invoice-dollar absolute -right-6 -bottom-6 text-8xl text-white opacity-20"></i>
    </div>

    <!-- Navigation Filtres / Tabs -->
    <div class="flex gap-2 p-1.5 bg-gray-100 rounded-2xl overflow-x-auto whitespace-nowrap shadow-inner w-full md:w-max">
        <button onclick="showTab('depots')" id="btn-depots" class="tab-btn py-3 px-6 rounded-xl font-bold text-sm transition-all bg-white shadow-sm text-blue-600 flex items-center gap-2">
            Dépôts <span class="bg-gray-100 text-gray-600 py-1 px-2.5 rounded-full text-xs">{{ $depots->count() }}</span>
        </button>
        <button onclick="showTab('retraits')" id="btn-retraits" class="tab-btn py-3 px-6 rounded-xl font-bold text-sm transition-all text-gray-500 hover:bg-white/50 flex items-center gap-2">
            Retraits <span class="bg-gray-200 text-gray-600 py-1 px-2.5 rounded-full text-xs">{{ $retraits->count() }}</span>
        </button>
        <button onclick="showTab('autres')" id="btn-autres" class="tab-btn py-3 px-6 rounded-xl font-bold text-sm transition-all text-gray-500 hover:bg-white/50 flex items-center gap-2">
            Autres <span class="bg-gray-200 text-gray-600 py-1 px-2.5 rounded-full text-xs">{{ $autres->count() }}</span>
        </button>
    </div>

    <!-- Conteneur des listes -->
    <div class="relative min-h-[400px]">
        
        <!-- Tab Dépôts -->
        <div id="tab-depots" class="tab-content block animate__animated animate__fadeIn">
            @forelse($depots as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" :rate="$rate" />
            @empty
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fas fa-arrow-down text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Aucun dépôt</h3>
                    <p class="text-gray-500 mt-2">Vous n'avez effectué aucun dépôt pour le moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Tab Retraits -->
        <div id="tab-retraits" class="tab-content hidden animate__animated animate__fadeIn">
            @forelse($retraits as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" :rate="$rate" />
            @empty
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fas fa-arrow-up text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Aucun retrait</h3>
                    <p class="text-gray-500 mt-2">Vous n'avez effectué aucun retrait pour le moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Tab Autres -->
        <div id="tab-autres" class="tab-content hidden animate__animated animate__fadeIn">
            @forelse($autres as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" :rate="$rate" />
            @empty
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fas fa-exchange-alt text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Aucune autre transaction</h3>
                    <p class="text-gray-500 mt-2">Votre historique est vide pour cette catégorie.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal transaction -->
<div id="txModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate__animated animate__zoomIn">
        
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-5 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-600"></i> Détail de la transaction
            </h3>
            <button type="button" onclick="closeTxModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 transition">✕</button>
        </div>

        <div class="p-6 space-y-5">
            <!-- Header modal -->
            <div class="text-center">
                <p id="txModalType" class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Type</p>
                <div class="flex items-end justify-center gap-2">
                    <span id="txModalAmountUsd" class="text-4xl font-black text-gray-800"></span>
                    <span class="text-xl font-bold text-gray-400 mb-1">$</span>
                </div>
                <p id="txModalAmountFcfa" class="text-sm font-semibold text-gray-500 mt-1"></p>
                <div class="mt-3">
                    <span id="txModalStatus" class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-wide"></span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 space-y-4">
                <div class="flex justify-between items-center text-sm border-b border-gray-200 pb-3">
                    <span class="text-gray-500 font-medium">Référence</span>
                    <span id="txModalRef" class="font-bold text-gray-800 font-mono"></span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-gray-200 pb-3">
                    <span class="text-gray-500 font-medium">Moyen de paiement</span>
                    <span id="txModalMethod" class="font-bold text-gray-800 uppercase bg-white px-2 py-1 rounded border border-gray-200 shadow-sm text-[10px]"></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Date du système</span>
                    <span id="txModalDate" class="font-bold text-gray-800"></span>
                </div>
            </div>

            <div class="text-sm">
                <p class="text-gray-500 font-medium mb-1">Motif / Description</p>
                <p id="txModalDesc" class="text-gray-800 font-medium bg-gray-50 p-3 rounded-xl border border-gray-100"></p>
            </div>
        </div>

        <div class="bg-gray-50 p-4 border-t border-gray-100">
            <button type="button" onclick="closeTxModal()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition">
                Fermer
            </button>
        </div>
    </div>
</div>

<!-- Add Animate.css if not already present in layouts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Scripts -->
<script>
    const RATE = {{ $rate }};

    function showTab(id) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btn.classList.add('text-gray-500', 'hover:bg-white/50');
        });

        document.getElementById('tab-' + id).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + id);
        activeBtn.classList.remove('text-gray-500', 'hover:bg-white/50');
        activeBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
    }

    function copyText(text, element) {
        navigator.clipboard.writeText(text).then(() => {
            const feedback = element.querySelector('.copy-feedback');
            if(feedback) {
                feedback.classList.remove('hidden');
                setTimeout(() => feedback.classList.add('hidden'), 1500);
            }
        }).catch(() => {
            alert('Impossible de copier la référence.');
        });
    }

    function openTxModal(tx) {
        if(typeof tx === 'string') {
            tx = JSON.parse(tx);
        }
        
        const modal = document.getElementById('txModal');
        
        document.getElementById('txModalRef').textContent = tx.reference || '—';
        document.getElementById('txModalType').textContent = (tx.type || '—').replace(/_/g, ' ');
        
        const montant_fcfa = (tx.montant_fcfa !== undefined && tx.montant_fcfa !== null) ? tx.montant_fcfa : (tx.montant || 0);
        const montant_usd = (tx.montant_usd !== undefined && tx.montant_usd !== null) ? tx.montant_usd : Math.round((montant_fcfa / RATE) * 100) / 100;
        
        document.getElementById('txModalAmountUsd').textContent = montant_usd.toFixed(2);
        document.getElementById('txModalAmountFcfa').textContent = new Intl.NumberFormat('fr-FR').format(montant_fcfa) + ' FCFA';
        
        document.getElementById('txModalMethod').textContent = tx.method ? tx.method : 'N/A';
        
        const statusEl = document.getElementById('txModalStatus');
        let statusText = '—', statusClass = 'bg-gray-200 text-gray-800';
        if(tx.status === 'completed') {
            statusText = 'Validé';
            statusClass = 'bg-green-100 text-green-700';
        } else if(tx.status === 'pending') {
            statusText = 'En attente';
            statusClass = 'bg-yellow-100 text-yellow-700';
        } else if(tx.status === 'rejected' || tx.status === 'failed') {
            statusText = 'Rejeté / Échoué';
            statusClass = 'bg-red-100 text-red-700';
        }
        statusEl.textContent = statusText;
        statusEl.className = `inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase ${statusClass}`;
        
        document.getElementById('txModalDate').textContent = tx.created_at ? new Date(tx.created_at).toLocaleString('fr-FR') : '—';
        document.getElementById('txModalDesc').textContent = tx.description || 'Acune description';

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTxModal() {
        const modal = document.getElementById('txModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeTxModal();
    });
</script>
</x-layouts>