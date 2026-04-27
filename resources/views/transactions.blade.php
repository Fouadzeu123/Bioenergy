<x-layouts :title="'Mes Transactions'" :level="Auth::user()->level">
@php
    $currency = Auth::user()->currency;
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

<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-20">

    <!-- Header Historique Sleeker -->
    <div class="text-center space-y-1">
        <h1 class="text-2xl font-bold text-gray-800">Historique</h1>
        <p class="text-[11px] font-semibold text-gray-400">Flux financiers sécurisés</p>
    </div>

    <!-- Navigation Filtres Sleeker -->
    <div class="flex gap-2 p-1.5 bg-gray-100 rounded-[20px] backdrop-blur-md">
        <button onclick="showTab('depots')" id="btn-depots" class="tab-btn flex-1 py-3 rounded-[15px] font-bold text-[10px] transition-all bg-white shadow-sm text-emerald-600">
            Dépôts
        </button>
        <button onclick="showTab('retraits')" id="btn-retraits" class="tab-btn flex-1 py-3 rounded-[15px] font-bold text-[10px] transition-all text-gray-400">
            Retraits
        </button>
        <button onclick="showTab('autres')" id="btn-autres" class="tab-btn flex-1 py-3 rounded-[15px] font-bold text-[10px] transition-all text-gray-400">
            Revenus
        </button>
    </div>

    <!-- Conteneur des listes Sleeker -->
    <div class="space-y-4">
        <!-- Tab Dépôts -->
        <div id="tab-depots" class="tab-content space-y-3">
            @forelse($depots as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" />
            @empty
                <div class="text-center py-20 bg-gray-50/50 rounded-[40px] border border-dashed border-gray-100">
                    <p class="text-[11px] font-bold text-gray-300">Aucun dépôt</p>
                </div>
            @endforelse
        </div>

        <!-- Tab Retraits -->
        <div id="tab-retraits" class="tab-content hidden space-y-3">
            @forelse($retraits as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" />
            @empty
                <div class="text-center py-20 bg-gray-50/50 rounded-[40px] border border-dashed border-gray-100">
                    <p class="text-[11px] font-bold text-gray-300">Aucun retrait</p>
                </div>
            @endforelse
        </div>

        <!-- Tab Autres -->
        <div id="tab-autres" class="tab-content hidden space-y-3">
            @forelse($autres as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" />
            @empty
                <div class="text-center py-20 bg-gray-50/50 rounded-[40px] border border-dashed border-gray-100">
                    <p class="text-[11px] font-bold text-gray-300">Aucun revenu</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Détails TX Sleeker -->
<div id="txModal" class="fixed inset-0 z-[110] hidden flex items-end sm:items-center justify-center bg-slate-900/80 backdrop-blur-sm p-0 sm:p-4">
    <div class="bg-white rounded-t-[40px] sm:rounded-[40px] shadow-2xl max-w-lg w-full p-8 space-y-8 animate__animated animate__slideInUp">
        <div class="flex justify-between items-center">
            <h4 class="text-xl font-bold text-gray-800">Détails Transaction</h4>
            <button onclick="closeTxModal()" class="text-gray-400 text-2xl hover:text-gray-800">×</button>
        </div>

        <div class="text-center space-y-2">
            <p id="txModalType" class="text-[11px] font-bold text-gray-400"></p>
            <div class="flex items-center justify-center gap-1">
                <span id="txModalAmount" class="text-4xl font-bold text-gray-800 tracking-tight"></span>
                <span id="txModalCurrency" class="text-lg font-bold text-gray-300"></span>
            </div>
            <div class="pt-2">
                <span id="txModalStatus" class="text-[10px] font-bold px-4 py-1.5 rounded-full"></span>
            </div>
        </div>

        <div class="bg-gray-50 rounded-2xl p-6 space-y-4">
            <div class="flex justify-between items-center px-1">
                <p class="text-[10px] font-bold text-gray-400">Référence</p>
                <p id="txModalRef" class="text-[11px] font-bold text-gray-800 font-mono"></p>
            </div>
            <div class="flex justify-between items-center px-1">
                <p class="text-[10px] font-bold text-gray-400">Moyen</p>
                <p id="txModalMethod" class="text-[11px] font-bold text-gray-800"></p>
            </div>
            <div class="flex justify-between items-center px-1">
                <p class="text-[10px] font-bold text-gray-400">Date</p>
                <p id="txModalDate" class="text-[11px] font-bold text-gray-800"></p>
            </div>
        </div>

        <div class="px-2">
            <p class="text-[10px] font-bold text-gray-400 mb-2">Description</p>
            <p id="txModalDesc" class="text-[12px] font-medium text-gray-600 leading-relaxed italic"></p>
        </div>

        <button onclick="closeTxModal()" class="w-full py-5 bg-slate-900 text-white text-[11px] font-bold rounded-2xl active:scale-95 transition">
            Fermer
        </button>
    </div>
</div>

<script>
    const CURRENCY = "{{ $currency }}";

    function showTab(id) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-emerald-600');
            btn.classList.add('text-gray-400');
        });

        document.getElementById('tab-' + id).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + id);
        activeBtn.classList.remove('text-gray-400');
        activeBtn.classList.add('bg-white', 'shadow-sm', 'text-emerald-600');
    }

    function openTxModal(tx) {
        if(typeof tx === 'string') tx = JSON.parse(tx);
        
        document.getElementById('txModalRef').textContent = tx.reference || '—';
        document.getElementById('txModalType').textContent = (tx.type || '—').replace(/_/g, ' ');
        document.getElementById('txModalAmount').textContent = Number(tx.montant || 0).toLocaleString('fr-FR');
        document.getElementById('txModalCurrency').textContent = CURRENCY;
        document.getElementById('txModalMethod').textContent = tx.method || 'Momo/Om';
        
        const statusEl = document.getElementById('txModalStatus');
        let statusClass = 'bg-gray-100 text-gray-400';
        if(tx.status === 'completed') statusClass = 'bg-emerald-50 text-emerald-600';
        else if(tx.status === 'pending') statusClass = 'bg-amber-50 text-amber-600';
        else if(tx.status === 'rejected' || tx.status === 'failed') statusClass = 'bg-red-50 text-red-600';
        
        statusEl.textContent = tx.status || 'Inconnu';
        statusEl.className = `text-[10px] font-bold px-4 py-1.5 rounded-full ${statusClass}`;
        
        document.getElementById('txModalDate').textContent = tx.created_at ? new Date(tx.created_at).toLocaleString('fr-FR') : '—';
        document.getElementById('txModalDesc').textContent = tx.description || 'Aucune description';

        document.getElementById('txModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTxModal() {
        document.getElementById('txModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
</x-layouts>