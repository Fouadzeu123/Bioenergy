<x-layouts :title="'Retrait'" :level="Auth::user()->level">

@php
    $USD_TO_XAF = config('notchpay.usd_to_xaf', 600);
    $MIN_WITHDRAWAL_USD = 10;
    $FEE_PERCENT = 10;

    $user = Auth::user();
    $balance = $user->account_balance ?? 0;
    $totalRetraits = $retraits->sum('montant') ?? 0;
@endphp

<div class="max-w-md mx-auto  py-8">

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-5 rounded-2xl text-center font-medium text-lg">
            {{ session('success') }}
        </div>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });</script>
    @endif

    @if(session('error'))
        <div class="mb-8 bg-red-50 border border-red-200 text-red-800 px-6 py-5 rounded-2xl text-center font-medium text-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Solde principal -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-10 text-center shadow-2xl">
        <p class="text-slate-300 text-sm tracking-wider uppercase">Solde disponible</p>
        <p class="text-5xl font-bold mt-3 tracking-tight">{{ fmtUsd($balance) }}</p>
        <p class="text-slate-400 mt-2 text-sm">≈ {{ fmtXaf($balance * $USD_TO_XAF) }}</p>
    </div>

    <!-- INFORMATIONS IMPORTANTES (nouveau bloc) -->
    <div class="mt-8 bg-amber-50 border border-amber-200 rounded-2xl p-5 shadow-md">
        <div class="space-y-3 text-sm text-amber-900 font-medium">
            <div class="flex items-center gap-3">
                <i class="fas fa-info-circle text-amber-600"></i>
                <span>Le retrait minimum est de <strong>10 $</strong></span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fas fa-hourglass-half text-amber-600"></i>
                <span>Crédit généralement en <strong>0 à 3 heures</strong> après validation</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fas fa-hourglass-half text-amber-600"></i>
                <span>Le retrait est disponible uniquement le <strong>jeudi de 08:00 a 18:00 GMT+1</strong></span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fas fa-hourglass-half text-amber-600"></i>
                <span>vous ne pouvez effectuer <strong>qu'un seul retrait</strong> par jour</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                <span>Vérifiez bien que vos informations de retrait sont <strong>correctes</strong></span>
            </div>
        </div>
    </div>

    <!-- Méthode de retrait -->
    @if($user->withdrawal_method && $user->withdrawal_account)
        <div class="mt-8 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-3xl p-8 text-center shadow-lg">
            <p class="text-emerald-700 font-semibold text-sm uppercase tracking-wider">Retrait vers</p>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-emerald-800">{{ strtoupper($user->withdrawal_method) }}</p>
                <p class="text-2xl font-mono text-slate-800 mt-3 tracking-wider">{{ $user->withdrawal_account }}</p>
                <p class="text-lg text-slate-700 mt-2 font-medium">{{ $user->withdrawal_name }}</p>
            </div>
            <a href="{{ route('withdraw_info') }}" class="mt-6 inline-block text-emerald-700 text-sm underline hover:text-emerald-900">
                Modifier les coordonnées
            </a>
        </div>
    @else
        <div class="mt-8 bg-red-50 border-2 border-red-300 rounded-3xl p-8 text-center shadow-lg">
            <p class="text-red-700 font-bold text-xl">Informations de retrait manquantes</p>
            <p class="text-slate-600 mt-3">Vous devez configurer vos coordonnées avant de retirer</p>
            <a href="{{ route('withdraw_info') }}" class="mt-6 inline-block bg-red-600 text-white font-bold px-8 py-4 rounded-2xl hover:bg-red-700 transition">
                Configurer maintenant
            </a>
        </div>
    @endif

    <!-- Formulaire -->
    <form id="withdrawForm" action="{{ route('retrait.preview') }}" method="POST" class="mt-10 bg-white rounded-3xl shadow-xl p-8 space-y-10">
        @csrf

        <!-- Montant -->
        <div>
            <label class="block text-slate-700 font-semibold text-lg mb-4">Montant à retirer</label>
            <input type="number" name="amount" id="amountInput" step="0.01" min="{{ $MIN_WITHDRAWAL_USD }}" max="{{ $balance }}"
                   required placeholder="50.00"
                   class="w-full text-4xl font-medium text-center bg-gray-50 border-0 rounded-2xl py-6 focus:ring-4 focus:ring-emerald-500 focus:outline-none transition">
            <p class="text-center text-slate-500 mt-4 text-sm">
                Minimum {{ $MIN_WITHDRAWAL_USD }} $ • Frais {{ $FEE_PERCENT }}%
            </p>
        </div>

        <!-- Boutons rapides -->
        <div class="grid grid-cols-3 gap-4">
            <button type="button" data-amount="20"  class="quick-btn bg-gray-100 hover:bg-emerald-50 text-slate-700 font-semibold py-5 rounded-2xl transition">20 $</button>
            <button type="button" data-amount="50"  class="quick-btn bg-gray-100 hover:bg-emerald-50 text-slate-700 font-semibold py-5 rounded-2xl transition">50 $</button>
            <button type="button" data-amount="100" class="quick-btn bg-gray-100 hover:bg-emerald-50 text-slate-700 font-semibold py-5 rounded-2xl transition">100 $</button>
        </div>

        <!-- Aperçu net -->
        <div id="feePreview" class="hidden bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-6 text-center shadow-md">
            <p class="text-slate-600 font-medium">Vous recevrez après frais ({{ $FEE_PERCENT }}%)</p>
            <p class="text-5xl font-bold text-emerald-600 mt-3" id="netAmount">—</p>
            <p class="text-lg text-slate-600 mt-2">≈ <span id="netXaf">—</span></p>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xl py-6 rounded-2xl shadow-xl transition transform hover:scale-105">
            Continuer
        </button>
    </form>

    <!-- MODAL CONFIRMATION -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 px-2 pb-10">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-10 animate__animated animate__fadeInUp">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-arrow-down text-4xl text-emerald-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">Confirmer le retrait</h2>
            </div>

            <div class="bg-gray-50 rounded-2xl p-8 space-y-5 text-lg font-medium">
                <div class="flex justify-between"><span class="text-slate-600">Montant</span>         <strong id="finalAmount">—</strong></div>
                <div class="flex justify-between"><span class="text-slate-600">Frais ({{ $FEE_PERCENT }}%)</span>      <strong class="text-red-600" id="finalFee">—</strong></div>
                <div class="flex justify-between text-2xl font-bold"><span class="text-slate-700">Net à recevoir</span> <strong class="text-emerald-600" id="finalNet">—</strong></div>
                <div class="border-t pt-5 text-center">
                    <p class="text-emerald-700 font-semibold text-xl">
                        {{ strtoupper($user->withdrawal_method ?? 'Mobile Money') }}
                    </p>
                    <p class="text-2xl font-mono mt-2">{{ $user->withdrawal_account }}</p>
                </div>
            </div>

            <form action="{{ route('retrait.store') }}" method="POST" class="mt-10">
                @csrf
                <input type="hidden" name="amount" id="confirmedAmount">

                <label class="block text-center text-slate-700 font-semibold text-lg mb-4">
                    Mot de passe de retrait
                </label>
                <input type="password" name="withdrawal_password" required autocomplete="off"
                       class="w-full text-center text-2xl tracking-widest bg-gray-50 border-0 rounded-2xl py-6 focus:ring-4 focus:ring-emerald-500 focus:outline-none"
                       placeholder="••••••">

                <div class="mt-10 grid grid-cols-2 gap-4">
                    <button type="button" onclick="closeConfirmModal()" class="py-5 border-2 border-slate-300 rounded-2xl font-semibold hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="py-5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-2xl shadow-xl transition">
                        Confirmer le retrait
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Total retiré -->
    <div class="mt-12 bg-white rounded-3xl shadow-xl p-8 text-center">
        <p class="text-slate-600 font-medium">Total retiré à ce jour</p>
        <p class="text-4xl font-bold text-slate-800 mt-3">{{ fmtUsd($totalRetraits) }}</p>
    </div>
</div>

<!-- Le script reste 100% identique -->
<script>
    const FEE = {{ $FEE_PERCENT / 100 }};
    const XAF = {{ $USD_TO_XAF }};

    document.querySelectorAll('.quick-btn').forEach(b => b.addEventListener('click', () => {
        document.getElementById('amountInput').value = b.dataset.amount;
        updatePreview();
    }));

    document.getElementById('amountInput').addEventListener('input', updatePreview);
    function updatePreview() {
        const val = parseFloat(document.getElementById('amountInput').value) || 0;
        const net = val * (1 - FEE);
        if (val >= {{ $MIN_WITHDRAWAL_USD }}) {
            document.getElementById('netAmount').textContent = net.toFixed(2) + ' $';
            document.getElementById('netXaf').textContent = Math.round(net * XAF).toLocaleString() + ' F';
            document.getElementById('feePreview').classList.remove('hidden');
        } else {
            document.getElementById('feePreview').classList.add('hidden');
        }
    }
    updatePreview();

    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const amount = parseFloat(document.getElementById('amountInput').value);
        if (amount < {{ $MIN_WITHDRAWAL_USD }}) return alert('Minimum {{ $MIN_WITHDRAWAL_USD }} $');

        const net = amount * (1 - FEE);
        document.getElementById('finalAmount').textContent = amount.toFixed(2) + ' $';
        document.getElementById('finalFee').textContent = (amount * FEE).toFixed(2) + ' $';
        document.getElementById('finalNet').textContent = net.toFixed(2) + ' $';
        document.getElementById('confirmedAmount').value = amount;

        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmModal').classList.add('flex');
    });

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.getElementById('confirmModal').classList.remove('flex');
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-layouts>