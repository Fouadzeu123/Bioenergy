<x-layouts :title="'Mes Transactions'" :level="Auth::user()->level">
@php
    $currency = Auth::user()->currency;
    $types = [
        'depot' => 'Dépôt', 'retrait' => 'Retrait', 'achat' => 'Achat',
        'gain_journalier' => 'Gain journalier', 'bonus_vip' => 'Bonus VIP',
        'bonus_journalier' => 'Bonus journalier', 'parrainage' => 'Parrainage'
    ];
    $statuses = [
        'pending'   => ['label' => 'En attente', 'color' => 'yellow'],
        'completed' => ['label' => 'Validé',     'color' => 'green'],
        'rejected'  => ['label' => 'Rejeté',     'color' => 'red']
    ];
    $depots  = $transactions->where('type', 'depot');
    $retraits = $transactions->where('type', 'retrait');
    $autres   = $transactions->whereNotIn('type', ['depot', 'retrait']);
@endphp

<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Header -->
    <div class="text-center space-y-1 pt-2">
        <h1 class="text-2xl font-bold text-white">Historique</h1>
        <p class="text-[11px] font-medium" style="color: #4b5563;">Flux financiers sécurisés</p>
    </div>

    <!-- Navigation Filtres -->
    <div class="flex gap-2 p-1.5 rounded-[20px]" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
        <button onclick="showTab('depots')" id="btn-depots" class="tab-btn flex-1 py-3 rounded-[15px] font-semibold text-[11px] transition-all text-blue-400" style="background: rgba(59,130,246,0.15);">
            Dépôts
        </button>
        <button onclick="showTab('retraits')" id="btn-retraits" class="tab-btn flex-1 py-3 rounded-[15px] font-semibold text-[11px] transition-all" style="color: #4b5563;">
            Retraits
        </button>
        <button onclick="showTab('autres')" id="btn-autres" class="tab-btn flex-1 py-3 rounded-[15px] font-semibold text-[11px] transition-all" style="color: #4b5563;">
            Revenus
        </button>
    </div>

    <!-- Conteneur listes -->
    <div class="space-y-3">
        <!-- Tab Dépôts -->
        <div id="tab-depots" class="tab-content space-y-3">
            @forelse($depots as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" />
            @empty
                <div class="text-center py-16 rounded-2xl border border-dashed" style="border-color: rgba(255,255,255,0.08);">
                    <p class="text-[11px] font-semibold" style="color: #374151;">Aucun dépôt</p>
                </div>
            @endforelse
        </div>

        <!-- Tab Retraits -->
        <div id="tab-retraits" class="tab-content hidden space-y-3">
            @forelse($retraits as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" />
            @empty
                <div class="text-center py-16 rounded-2xl border border-dashed" style="border-color: rgba(255,255,255,0.08);">
                    <p class="text-[11px] font-semibold" style="color: #374151;">Aucun retrait</p>
                </div>
            @endforelse
        </div>

        <!-- Tab Autres -->
        <div id="tab-autres" class="tab-content hidden space-y-3">
            @forelse($autres as $tx)
                <x-tx-card :tx="$tx" :types="$types" :statuses="$statuses" />
            @empty
                <div class="text-center py-16 rounded-2xl border border-dashed" style="border-color: rgba(255,255,255,0.08);">
                    <p class="text-[11px] font-semibold" style="color: #374151;">Aucun revenu</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Détails TX -->
<div id="txModal" class="fixed inset-0 z-[110] hidden flex items-end sm:items-center justify-center backdrop-blur-sm p-0 sm:p-4" style="background: rgba(0,0,0,0.7);">
    <div class="rounded-t-[2rem] sm:rounded-3xl shadow-2xl max-w-lg w-full p-7 space-y-6 animate__animated animate__slideInUp" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
        <div class="flex justify-between items-center">
            <h4 class="text-lg font-bold text-white">Détails Transaction</h4>
            <button onclick="closeTxModal()" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-500 hover:text-white transition" style="background: rgba(255,255,255,0.05);">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <div class="text-center space-y-2">
            <p id="txModalType" class="text-[11px] font-semibold" style="color: #4b5563;"></p>
            <div class="flex items-baseline justify-center gap-2">
                <span id="txModalAmount" class="text-4xl font-bold text-white tracking-tight"></span>
                <span id="txModalCurrency" class="text-lg font-semibold" style="color: #4b5563;"></span>
            </div>
            <div class="pt-1">
                <span id="txModalStatus" class="text-[10px] font-bold px-4 py-1.5 rounded-full"></span>
            </div>
        </div>

        <div class="rounded-2xl p-5 space-y-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex justify-between items-center">
                <p class="text-[10px] font-semibold" style="color: #4b5563;">Référence</p>
                <p id="txModalRef" class="text-[11px] font-bold text-gray-300 font-mono"></p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[10px] font-semibold" style="color: #4b5563;">Moyen</p>
                <p id="txModalMethod" class="text-[11px] font-bold text-gray-300"></p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[10px] font-semibold" style="color: #4b5563;">Date</p>
                <p id="txModalDate" class="text-[11px] font-bold text-gray-300"></p>
            </div>
        </div>

        <div>
            <p class="text-[10px] font-semibold mb-2" style="color: #4b5563;">Description</p>
            <p id="txModalDesc" class="text-[12px] font-medium leading-relaxed" style="color: #6b7280;"></p>
        </div>

        <button onclick="closeTxModal()" class="w-full py-4 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2);">
            Fermer
        </button>
    </div>
</div>

<script>
    const CURRENCY = "{{ $currency }}";

    function showTab(id) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.style.background = 'transparent';
            btn.style.color = '#4b5563';
        });
        document.getElementById('tab-' + id).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + id);
        activeBtn.style.background = 'rgba(59,130,246,0.15)';
        activeBtn.style.color = '#60a5fa';
    }

    function openTxModal(tx) {
        if(typeof tx === 'string') tx = JSON.parse(tx);
        document.getElementById('txModalRef').textContent    = tx.reference || '—';
        document.getElementById('txModalType').textContent   = (tx.type || '—').replace(/_/g, ' ');
        document.getElementById('txModalAmount').textContent = Number(tx.montant || 0).toLocaleString('fr-FR');
        document.getElementById('txModalCurrency').textContent = CURRENCY;
        document.getElementById('txModalMethod').textContent = tx.method || 'Momo/Om';
        const statusEl = document.getElementById('txModalStatus');
        let statusStyle = 'background: rgba(107,114,128,0.15); color: #9ca3af;';
        if(tx.status === 'completed') statusStyle = 'background: rgba(6,182,212,0.15); color: #22d3ee;';
        else if(tx.status === 'pending') statusStyle = 'background: rgba(245,158,11,0.15); color: #fbbf24;';
        else if(tx.status === 'rejected' || tx.status === 'failed') statusStyle = 'background: rgba(239,68,68,0.15); color: #f87171;';
        statusEl.textContent = tx.status || 'Inconnu';
        statusEl.setAttribute('style', `text-size: 10px; font-weight: 700; padding: 4px 12px; border-radius: 9999px; ${statusStyle}`);
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