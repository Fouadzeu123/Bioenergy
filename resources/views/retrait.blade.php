<x-layouts :title="'Retrait'" :level="Auth::user()->level">

@php
    $USD_TO_XAF = config('notchpay.usd_to_xaf', 600);
    $user = Auth::user();
    $MIN_WITHDRAWAL_USD = strtolower($user->username ?? '') === 'boris' ? 1 : 10;
    $FEE_PERCENT = 10;

    $balance = $user->account_balance ?? 0;
    $totalRetraits = $retraits->sum('montant') ?? 0;
@endphp

<div class="max-w-2xl mx-auto py-8 px-4">

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-center font-medium shadow-sm">
            {{ session('success') }}
        </div>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });</script>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-center font-medium shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Cards Supérieures -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Solde principal -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm tracking-wider uppercase font-semibold">Solde disponible</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-extrabold text-gray-800">{{ fmtUsd($balance) }}</p>
                    <p class="text-sm font-medium text-gray-400">≈ {{ fmtXaf($balance * $USD_TO_XAF) }}</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>

        <!-- Total retiré -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm tracking-wider uppercase font-semibold">Total Retiré</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ fmtUsd($totalRetraits) }}</p>
            </div>
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-xl"></i>
            </div>
        </div>
    </div>

    <!-- INFORMATIONS IMPORTANTES -->
    <div class="mt-6 bg-amber-50 border border-amber-100 rounded-2xl p-4 shadow-sm">
        <h4 class="text-amber-800 font-bold mb-2 flex items-center gap-2 text-sm"><i class="fas fa-info-circle"></i> Conditions de retrait</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-xs text-amber-900/80 font-medium">
            <div class="flex items-start gap-2"><i class="fas fa-check mt-0.5 text-amber-600"></i> Minimum de retrait : <strong>{{ $MIN_WITHDRAWAL_USD }} $</strong></div>
            <div class="flex items-start gap-2"><i class="fas fa-check mt-0.5 text-amber-600"></i> Frais appliqués : <strong>{{ $FEE_PERCENT }}%</strong></div>
            <div class="flex items-start gap-2"><i class="fas fa-clock mt-0.5 text-amber-600"></i> Horaires : <strong>Lun. au Ven. de 09:00 à 18:00</strong></div>
            <div class="flex items-start gap-2"><i class="fas fa-exclamation-triangle mt-0.5 text-amber-600"></i> Limite : <strong>1 retrait par jour</strong></div>
        </div>
    </div>

    <!-- Méthode de retrait actuelle -->
    @if($user->withdrawal_method && $user->withdrawal_account)
        <div class="mt-6 bg-white border border-gray-100 rounded-3xl p-5 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                    <i class="fas fa-university text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Compte de réception</p>
                    <p class="font-bold text-gray-800 text-sm mt-0.5">{{ strtoupper($user->withdrawal_method) }} • {{ $user->withdrawal_account }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $user->withdrawal_name }}</p>
                </div>
            </div>
            <a href="{{ route('withdraw_info') }}" class="text-blue-600 text-sm hover:underline font-medium px-3 py-1.5 bg-blue-50 rounded-lg">Modifier</a>
        </div>
    @else
        <div class="mt-6 bg-red-50 border border-red-200 rounded-3xl p-6 text-center shadow-sm flex flex-col items-center justify-center">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <p class="text-red-700 font-bold">Informations de retrait manquantes</p>
            <p class="text-sm text-red-600 mt-1 mb-4">Veuillez configurer vos coordonnées pour retirer vos fonds.</p>
            <a href="{{ route('withdraw_info') }}" class="bg-red-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-red-700 transition shadow">
                Configurer maintenant
            </a>
        </div>
    @endif

    <!-- Formulaire Retrait -->
    <form id="withdrawForm" action="{{ route('retrait.preview') }}" method="POST" class="mt-6 bg-white rounded-3xl shadow-lg border border-gray-100 p-6 sm:p-8 space-y-6">
        @csrf

        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-4">
            <i class="fas fa-arrow-up text-emerald-500"></i> Initier un retrait
        </h3>

        <!-- Montant -->
        <div>
            <label class="block font-semibold text-gray-700 mb-3 text-sm">Montant à retirer (USD)</label>
            <div class="relative">
                <span class="absolute left-4 py-4 text-gray-400 font-bold text-xl">$</span>
                <input type="number" name="amount" id="amountInput" step="0.01" min="{{ $MIN_WITHDRAWAL_USD }}" max="{{ $balance }}"
                       required placeholder="50.00"
                       class="w-full pl-10 pr-4 py-4 text-2xl font-bold bg-gray-50 border border-gray-200 rounded-2xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:bg-white transition">
            </div>
            
            <!-- Boutons rapides -->
            <div class="grid grid-cols-4 gap-2 mt-4">
                <button type="button" data-amount="20"  class="quick-btn bg-gray-50 hover:bg-emerald-50 border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-700 font-semibold py-2 rounded-xl transition text-sm">20 $</button>
                <button type="button" data-amount="50"  class="quick-btn bg-gray-50 hover:bg-emerald-50 border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-700 font-semibold py-2 rounded-xl transition text-sm">50 $</button>
                <button type="button" data-amount="100" class="quick-btn bg-gray-50 hover:bg-emerald-50 border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-700 font-semibold py-2 rounded-xl transition text-sm">100 $</button>
                <button type="button" data-amount="{{ $balance }}" class="quick-btn bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 hover:border-emerald-300 text-emerald-700 font-bold py-2 rounded-xl transition text-sm">MAX</button>
            </div>
        </div>

        <!-- Aperçu net -->
        <div id="feePreview" class="hidden bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500">Vous recevrez <span class="bg-red-100 text-red-600 px-1.5 py-0.5 rounded text-[10px]">-{{ $FEE_PERCENT }}% frais</span></p>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1" id="netAmount">—</p>
                <p class="text-xs font-semibold text-emerald-800/60" id="netXaf">—</p>
            </div>
            <i class="fas fa-hand-holding-usd text-4xl text-emerald-200"></i>
        </div>

        <button type="submit" class="w-full font-bold text-white bg-emerald-600 hover:bg-emerald-700 py-4 rounded-2xl shadow-lg shadow-emerald-200 transition">
            Continuer
        </button>
    </form>

    <!-- MODAL CONFIRMATION -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 shadow-2xl backdrop-blur-sm px-4">
        <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full overflow-hidden animate__animated animate__zoomIn">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-5 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-emerald-500"></i> Vérification
                </h3>
                <button type="button" onclick="closeConfirmModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 transition">✕</button>
            </div>

            <div class="p-6">
                <div class="space-y-3 font-medium text-sm border-b border-gray-100 pb-5 mb-5">
                    <div class="flex justify-between items-center"><span class="text-gray-500">Montant demandé</span> <strong id="finalAmount" class="text-gray-800 text-base">—</strong></div>
                    <div class="flex justify-between items-center"><span class="text-gray-500">Frais ({{ $FEE_PERCENT }}%)</span> <strong class="text-red-500" id="finalFee">—</strong></div>
                    <div class="flex justify-between items-center bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                        <span class="text-emerald-800 font-bold block">Net à recevoir</span>
                        <strong class="text-emerald-600 text-xl" id="finalNet">—</strong>
                    </div>
                </div>

                <div class="text-center mb-5 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Versation vers</p>
                    <p class="text-sm font-bold text-gray-800 mt-1">
                        {{ strtoupper($user->withdrawal_method ?? '') }} • {{ $user->withdrawal_account }}
                    </p>
                </div>

                <form action="{{ route('retrait.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" id="confirmedAmount">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 text-center">Mot de passe de retrait</label>
                        <input type="password" name="withdrawal_password" required autocomplete="off"
                               class="w-full text-center tracking-widest text-lg font-mono bg-white border border-gray-200 rounded-xl py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition shadow-inner"
                               placeholder="••••••">
                    </div>

                    <button type="submit" class="mt-6 w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 transition">
                        Valider le retrait
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Historique abrégé -->
    <div class="mt-8 text-sm">
        <div class="flex items-center justify-between mb-4 px-2">
            <h3 class="font-bold text-gray-700">Derniers retraits</h3>
            <a href="{{ route('transaction') }}" class="text-emerald-600 hover:underline">Voir tout</a>
        </div>
        
        @if(isset($retraits) && $retraits->count() > 0)
            <div class="space-y-3">
                @foreach($retraits->take(3) as $retrait)
                    <div class="bg-white rounded-2xl p-4 flex items-center justify-between border border-gray-100 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center"><i class="fas fa-arrow-up"></i></div>
                            <div>
                                <p class="font-bold text-gray-800">{{ number_format($retrait->montant, 2) }} $</p>
                                <p class="text-xs text-gray-400">{{ $retrait->created_at->format('d M, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-500 mb-1">{{ strtoupper($retrait->operator ?? 'Mobile Money') }}</p>
                            @if($retrait->status === 'completed')
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md bg-green-100 text-green-700">Validé</span>
                            @elseif(in_array($retrait->status, ['failed', 'canceled', 'rejected']))
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md bg-red-100 text-red-700">Échoué</span>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md bg-yellow-100 text-yellow-700">En cours</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-gray-500">
                Aucun retrait récent
            </div>
        @endif
    </div>
</div>

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
            document.getElementById('netXaf').textContent = '≈ ' + Math.round(net * XAF).toLocaleString('fr-FR') + ' FCFA';
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
        document.getElementById('finalFee').textContent = '-' + (amount * FEE).toFixed(2) + ' $';
        document.getElementById('finalNet').textContent = net.toFixed(2) + ' $';
        document.getElementById('confirmedAmount').value = amount;

        document.getElementById('confirmModal').classList.remove('hidden');
    });

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-layouts>