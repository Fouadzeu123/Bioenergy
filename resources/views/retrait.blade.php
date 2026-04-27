<x-layouts :title="'Retrait'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $currency = $user->currency;
    $MIN_WITHDRAWAL = 1000;
    $FEE_PERCENT = 10;
    $balance = $user->account_balance ?? 0;
@endphp

<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-20">

    <!-- Card Solde Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl">
        <div class="relative z-10 flex justify-between items-end">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-gray-400">Disponible pour retrait</p>
                <h2 class="text-4xl font-bold tracking-tight">{{ fmtCurrency($balance) }}</h2>
            </div>
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/5">
                <i class="fas fa-arrow-up-from-bracket text-emerald-400"></i>
            </div>
        </div>
        <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Conditions Compactes Sleeker -->
    <div class="bg-amber-50/50 rounded-[24px] p-5 border border-amber-100 flex items-center justify-center gap-6">
        <div class="text-center">
            <p class="text-[9px] font-bold text-amber-600">Min. Retrait</p>
            <p class="text-[11px] font-bold text-amber-800">{{ number_format($MIN_WITHDRAWAL, 0, '.', ' ') }} {{ $currency }}</p>
        </div>
        <div class="w-px h-8 bg-amber-200"></div>
        <div class="text-center">
            <p class="text-[9px] font-bold text-amber-600">Frais Service</p>
            <p class="text-[11px] font-bold text-amber-800">{{ $FEE_PERCENT }}%</p>
        </div>
        <div class="w-px h-8 bg-amber-200"></div>
        <div class="text-center">
            <p class="text-[8px] font-black text-amber-600 uppercase tracking-widest">Traitement</p>
            <p class="text-[11px] font-black text-amber-800 italic">Lun-Ven</p>
        </div>
    </div>

    <!-- Formulaire Retrait Sleeker -->
    <form id="withdrawForm" method="POST" class="space-y-8">
        @csrf
        
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-50 space-y-8 text-center">
            <div class="space-y-4">
                <label class="text-[11px] font-bold text-gray-400">Montant à transférer</label>
                <div class="relative">
                    <input type="number" name="amount" id="amountInput" step="1" min="{{ $MIN_WITHDRAWAL }}" max="{{ $balance }}"
                           required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-6 text-3xl font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none tracking-tight"
                           placeholder="0">
                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 font-black text-sm italic">{{ $currency }}</span>
                </div>
            </div>

            <!-- Boutons rapides Sleeker -->
            <div class="grid grid-cols-4 gap-3">
                @foreach([5000, 10000, 50000] as $amt)
                    <button type="button" onclick="setAmount({{ $amt }})" class="py-3 rounded-xl bg-gray-50 text-[10px] font-black text-gray-400 hover:bg-slate-900 hover:text-white transition uppercase tracking-wider border border-gray-100">
                        {{ number_format($amt/1000, 0) }}K
                    </button>
                @endforeach
                <button type="button" onclick="setAmount({{ $balance }})" class="py-3 rounded-xl bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider active:scale-95 transition">
                    MAX
                </button>
            </div>

            <!-- Aperçu Net Sleeker -->
            <div id="feePreview" class="hidden animate__animated animate__fadeIn">
                <div class="bg-emerald-50/50 rounded-2xl p-6 border border-emerald-100">
                    <p class="text-[10px] font-bold text-emerald-600 mb-1">Montant Net (estimé)</p>
                    <p class="text-2xl font-bold text-emerald-700" id="netAmount">--</p>
                </div>
            </div>

            <button type="submit" class="w-full py-6 bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl active:scale-95 transition">
                Confirmer le retrait
            </button>
        </div>
    </form>

    <!-- Historique Mini Sleeker -->
    @if($retraits->count() > 0)
    <div class="space-y-4">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2 italic">Opérations récentes</h3>
        <div class="space-y-3">
            @foreach($retraits->take(3) as $retrait)
                <div class="bg-white rounded-[24px] p-5 border border-gray-50 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-gray-400">
                            <i class="fas fa-arrow-up text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-800 italic">{{ fmtCurrency($retrait->montant) }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $retrait->created_at->format('d M, H:i') }}</p>
                        </div>
                    </div>
                    @php
                        $statusClass = 'bg-gray-100 text-gray-400';
                        if($retrait->status === 'completed') $statusClass = 'bg-emerald-50 text-emerald-600';
                        elseif(in_array($retrait->status, ['failed', 'canceled', 'rejected'])) $statusClass = 'bg-red-50 text-red-600';
                    @endphp
                    <span class="text-[8px] font-black uppercase px-3 py-1.5 rounded-full {{ $statusClass }}">
                        {{ $retrait->status }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Modal Confirmation Sleeker -->
<div id="confirmModal" class="fixed inset-0 z-[110] hidden flex items-end sm:items-center justify-center bg-slate-900/80 backdrop-blur-sm p-0 sm:p-4">
    <div class="bg-white rounded-t-[40px] sm:rounded-[40px] shadow-2xl max-w-lg w-full p-8 space-y-8 animate__animated animate__slideInUp">
        <div class="text-center space-y-2">
            <h4 class="text-xl font-black text-gray-800">Finaliser le transfert</h4>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Confidentialité & Sécurité</p>
        </div>

        <div class="bg-gray-50 rounded-[24px] p-6 space-y-4">
            <div class="flex justify-between items-center">
                <p class="text-[9px] font-black text-gray-400 uppercase">Recevable net</p>
                <p id="finalNet" class="text-lg font-black text-emerald-600 italic">--</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[9px] font-black text-gray-400 uppercase">Frais déduits</p>
                <p class="text-[10px] font-black text-red-400 italic">{{ $FEE_PERCENT }}%</p>
            </div>
            <div class="pt-4 border-t border-gray-200">
                <p class="text-[9px] font-black text-gray-400 uppercase text-center mb-2">Destination</p>
                <p class="text-xs font-black text-center text-gray-800 uppercase italic">{{ $user->withdrawal_method }} • {{ $user->withdrawal_account }}</p>
            </div>
        </div>

        <form action="{{ route('retrait.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="amount" id="confirmedAmount">
            <div class="space-y-2">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Code de retrait secret</label>
                <input type="password" name="withdrawal_password" required autocomplete="off"
                       class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-center focus:bg-white focus:border-emerald-500 transition outline-none"
                       placeholder="••••••••">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeConfirmModal()" class="py-5 bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest rounded-2xl active:scale-95 transition">
                    Annuler
                </button>
                <button type="submit" class="py-5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl active:scale-95 transition">
                    Confirmer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const FEE = {{ $FEE_PERCENT / 100 }};
    const CURRENCY = "{{ $currency }}";
    const MIN_W = {{ $MIN_WITHDRAWAL }};

    function setAmount(amt) {
        document.getElementById('amountInput').value = amt;
        updatePreview();
    }

    document.getElementById('amountInput').addEventListener('input', updatePreview);

    function updatePreview() {
        const val = parseFloat(document.getElementById('amountInput').value) || 0;
        const net = Math.round(val * (1 - FEE));
        if (val >= MIN_W) {
            document.getElementById('netAmount').textContent = net.toLocaleString('fr-FR') + ' ' + CURRENCY;
            document.getElementById('feePreview').classList.remove('hidden');
        } else {
            document.getElementById('feePreview').classList.add('hidden');
        }
    }

    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const amount = parseFloat(document.getElementById('amountInput').value);
        if (amount < MIN_W) {
            alert('Le montant minimum est de ' + MIN_W + ' ' + CURRENCY);
            return;
        }

        const net = Math.round(amount * (1 - FEE));
        document.getElementById('finalNet').textContent = net.toLocaleString('fr-FR') + ' ' + CURRENCY;
        document.getElementById('confirmedAmount').value = amount;

        document.getElementById('confirmModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
</x-layouts>
