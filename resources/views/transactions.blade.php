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
    $statuses = ['pending' => 'En attente', 'completed' => 'Validé', 'rejected' => 'Rejeté'];
@endphp

<div class="max-w-6xl mx-auto py-6 space-y-6">

    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-blue-700">📜 Historique des transactions</h2>
            <p class="text-sm text-gray-500 mt-1">Affichage principal en USD, équivalent FCFA indiqué en second.</p>
        </div>

    <!-- Table responsive -->
    <div class="bg-white rounded-lg shadow-md p-4 overflow-x-auto">
        <table class="min-w-full w-full table-auto border-collapse text-sm text-left text-gray-700">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 border border-gray-200">Référence</th>
                    <th class="px-3 py-2 border border-gray-200">Type</th>
                    <th class="px-3 py-2 border border-gray-200">Montant (USD)</th>
                    <th class="px-3 py-2 border border-gray-200">Équiv. FCFA</th>
                    <th class="px-3 py-2 border border-gray-200">Méthode</th>
                    <th class="px-3 py-2 border border-gray-200">Statut</th>
                    <th class="px-3 py-2 border border-gray-200">Date</th>
                    <th class="px-3 py-2 border border-gray-200">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $transaction)
                    @php
                        $montant_usd = $transaction->montant;
                        $montant_fcfa = round(($montant_usd * $rate), 2);
                        $typeLabel = $types[$transaction->type] ?? ucfirst($transaction->type);
                        $statusLabel = $statuses[$transaction->status] ?? ucfirst($transaction->status);
                        // JSON sécurisé pour data attribute
                        $txJson = json_encode([
                            'reference' => $transaction->reference,
                            'type' => $transaction->type,
                            'montant_fcfa' => $montant_fcfa,
                            'montant_usd' => $montant_usd,
                            'method' => $transaction->method,
                            'status' => $transaction->status,
                            'created_at' => $transaction->created_at ? $transaction->created_at->toDateTimeString() : null,
                            'description' => $transaction->description,
                        ], JSON_HEX_APOS | JSON_HEX_QUOT);
                    @endphp

                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-3 py-2 border border-gray-200 max-w-[140px] truncate">
                            <div class="flex items-center gap-2">
                                <span class="font-medium truncate">{{ $transaction->reference }}</span>
                                <button type="button" onclick="copyText('{{ $transaction->reference }}', this)" class="text-xs text-gray-500 hover:text-gray-700" title="Copier la référence">Copier</button>
                            </div>
                        </td>

                        <td class="px-3 py-2 border border-gray-200">
                            <span class="font-semibold text-sm">
                                @switch($transaction->type)
                                    @case('depot') <span class="text-green-600">Dépôt</span> @break
                                    @case('retrait') <span class="text-red-600">Retrait</span> @break
                                    @case('achat') <span class="text-blue-600">Achat</span> @break
                                    @case('gain_journalier') <span class="text-green-700">Gain journalier</span> @break
                                    @case('bonus_vip') <span class="text-purple-600">Bonus VIP</span> @break
                                    @case('bonus_journalier') <span class="text-indigo-600">Bonus journalier</span> @break
                                    @case('parrainage') <span class="text-yellow-600">Parrainage</span> @break
                                    @default <span class="text-gray-600">{{ $typeLabel }}</span>
                                @endswitch
                            </span>
                        </td>

                        <td class="px-3 py-2 border border-gray-200">
                            <div class="font-semibold text-gray-800">{{ number_format($montant_usd, 2, '.', ',') }} $</div>
                        </td>

                        <td class="px-3 py-2 border border-gray-200">
                            <div class="text-sm text-gray-600">{{ number_format($montant_fcfa, 0, ',', ' ') }} FCFA</div>
                        </td>

                        <td class="px-3 py-2 border border-gray-200 max-w-[120px] truncate">{{ ucfirst($transaction->method ?? '—') }}</td>

                        <td class="px-3 py-2 border border-gray-200">
                            @if($transaction->status === 'pending')
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">En attente</span>
                            @elseif($transaction->status === 'completed')
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Validé</span>
                            @else
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Rejeté</span>
                            @endif
                        </td>

                        <td class="px-3 py-2 border border-gray-200">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>

                        <td class="px-3 py-2 border border-gray-200">
                            <div class="flex items-center gap-2">
                                <button type="button" data-tx='@{!! $txJson !!}' onclick="openTxModalFromButton(this)" class="text-sm text-blue-600 hover:underline">Détails</button>
                                @if($transaction->status === 'pending' && $transaction->type === 'depot')
                                    <button type="button" onclick="alert('Si nécessaire, contactez le support pour accélérer le dépôt.')" class="text-sm text-gray-600">Aide</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-4">Aucune transaction enregistrée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination (si la collection est paginée) -->
        <div class="mt-4">
            @if(method_exists($transactions, 'links'))
                <div class="flex items-center justify-between">
                    <div class="text-xs text-gray-500">Affichage {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} sur {{ $transactions->total() ?? $transactions->count() }}</div>
                    <div>
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Modal transaction -->
<div id="txModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 id="txModalRef" class="text-lg font-semibold text-gray-800">Référence</h3>
                <p id="txModalType" class="text-sm text-gray-500 mt-1">Type</p>
            </div>
            <button type="button" onclick="closeTxModal()" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <div class="mt-4 space-y-2 text-sm text-gray-700">
            <div><strong>Montant :</strong> <span id="txModalAmount"></span></div>
            <div><strong>Méthode :</strong> <span id="txModalMethod"></span></div>
            <div><strong>Statut :</strong> <span id="txModalStatus"></span></div>
            <div><strong>Date :</strong> <span id="txModalDate"></span></div>
            <div><strong>Description :</strong>
                <div id="txModalDesc" class="mt-1 text-gray-600 whitespace-pre-wrap"></div>
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="button" onclick="closeTxModal()" class="px-4 py-2 border rounded-lg">Fermer</button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    const RATE = {{ $rate }};

    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const original = btn.innerText;
            btn.innerText = 'Copié';
            setTimeout(() => btn.innerText = original, 1500);
        }).catch(() => {
            alert('Impossible de copier la référence.');
        });
    }

    // Récupère le JSON depuis data-tx et ouvre le modal
    function openTxModalFromButton(button) {
        try {
            const raw = button.getAttribute('data-tx');
            if (!raw) return;
            const tx = JSON.parse(raw);
            openTxModal(tx);
        } catch (err) {
            console.error('openTxModalFromButton error:', err);
            alert('Impossible d\'ouvrir les détails de la transaction.');
        }
    }

    function openTxModal(tx) {
        const modal = document.getElementById('txModal');
        document.getElementById('txModalRef').textContent = tx.reference || '—';
        document.getElementById('txModalType').textContent = (tx.type || '—').replace(/_/g, ' ');
        const montant_fcfa = (tx.montant_fcfa !== undefined && tx.montant_fcfa !== null) ? tx.montant_fcfa : (tx.montant || 0);
        const montant_usd = (tx.montant_usd !== undefined && tx.montant_usd !== null) ? tx.montant_usd : Math.round((montant_fcfa / RATE) * 100) / 100;
        document.getElementById('txModalAmount').textContent = montant_usd.toFixed(2) + ' $ • ' + new Intl.NumberFormat('fr-FR').format(montant_fcfa) + ' FCFA';
        document.getElementById('txModalMethod').textContent = tx.method ? tx.method.charAt(0).toUpperCase() + tx.method.slice(1) : '—';
        document.getElementById('txModalStatus').textContent = tx.status ? tx.status.charAt(0).toUpperCase() + tx.status.slice(1) : '—';
        document.getElementById('txModalDate').textContent = tx.created_at ? new Date(tx.created_at).toLocaleString() : '—';
        document.getElementById('txModalDesc').textContent = tx.description || '—';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeTxModal() {
        const modal = document.getElementById('txModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeTxModal();
    });
</script>
</x-layouts>