<x-layouts :title="'Dépôt'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $balance = $user->account_balance ?? 0;
    $phone = $user->phone ?? '';
    $isCameroon = true;

    $localRate = config('notchpay.usd_to_xaf', 600);
    $localSymbol = 'CFA';

    $detectedOperator = 'UNKNOWN';
    if ($isCameroon && $phone) {
        $phoneStr = (string) $phone;
        if (strlen($phoneStr) >= 9) {
            $prefix = substr($phoneStr, 0, 3);
            $mtn = ['650','651','652','653','654','670','671','672','673','674','675','676','677','678','679','680','681','682','683'];
            $orange = ['640','641','642','643','644','645','646','647','648','655','656','657','658','659','690','691','692','693','694','695','696','697','698','699'];

            if (in_array($prefix, $mtn)) $detectedOperator = 'MTN';
            elseif (in_array($prefix, $orange)) $detectedOperator = 'ORANGE';
        }
    }

    $lastPayment = session('last_payment');
    if ($lastPayment) session()->forget('last_payment');

    $depots = $depots ?? collect();
@endphp

<div class="min-h-screen bg-gray-50/50 py-6">

<div class="px-4 md:px-0 max-w-2xl mx-auto space-y-6">

        <!-- Flash messages -->
        @if(session('success') && !session('last_payment'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-center font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-center font-medium shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Solde -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Solde Actuel</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-extrabold text-gray-800">{{ number_format($balance, 2) }} $</p>
                    <p class="text-sm font-medium text-gray-400">≈ {{ number_format(round($balance * $localRate)) }} FCFA</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>

        <!-- Formulaire Dépôt -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-5">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-arrow-down bg-white/20 p-2 rounded-lg"></i> Faire un dépôt
                </h3>
            </div>

            <form id="depositForm" action="{{ route('depot.store') }}" method="POST" class="p-6 md:p-8 space-y-8">
                @csrf

                <!-- Choix Méthode Simplifié -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-3 text-sm">Mode de paiement</label>
                    <label class="cursor-pointer block border-2 border-emerald-500 bg-emerald-50 rounded-2xl p-4 transition text-center shadow-sm">
                        <input type="radio" name="canal" value="notchpay" checked class="hidden peer">
                        <div class="flex items-center justify-center gap-3">
                            <i class="fas fa-mobile-alt text-2xl text-emerald-600"></i>
                            <div class="text-left leading-tight">
                                <span class="block font-bold text-emerald-800">Mobile Money</span>
                                <span class="text-xs text-emerald-600">MTN & Orange Money (Cameroun)</span>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Saisie Montant -->
                <div>
                    <label class="block font-semibold text-gray-700 mb-3 text-sm flex justify-between">
                        Montant à déposer (USD)
                        <span class="text-xs text-gray-400 font-normal">Min. 10$</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 py-4 text-gray-400 font-bold text-xl">$</span>
                        <input type="number" name="amount" id="amountUsd" step="0.01" min="10" required
                               class="w-full pl-10 pr-4 py-4 text-2xl font-bold bg-gray-50 border border-gray-200 rounded-2xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:bg-white transition"
                               placeholder="25.00" value="{{ old('amount') }}">
                    </div>

                    <p class="text-right text-emerald-600 font-bold text-sm mt-2" id="amountFcfa">0 {{ $localSymbol }}</p>

                    <div class="grid grid-cols-3 gap-3 mt-4">
                        <button type="button" data-amount="10" class="quick-amount bg-gray-50 hover:bg-emerald-50 border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-700 font-semibold py-2 rounded-xl transition">10 $</button>
                        <button type="button" data-amount="50" class="quick-amount bg-gray-50 hover:bg-emerald-50 border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-700 font-semibold py-2 rounded-xl transition">50 $</button>
                        <button type="button" data-amount="100" class="quick-amount bg-gray-50 hover:bg-emerald-50 border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-700 font-semibold py-2 rounded-xl transition">100 $</button>
                    </div>
                </div>

                <!-- Info opérateur -->
                @if($detectedOperator !== 'UNKNOWN')
                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Numéro de dépôt</p>
                            <p class="font-bold text-gray-700 text-sm mt-0.5"><i class="fas fa-phone-alt text-emerald-500 mr-1"></i> +237 {{ chunk_split($phone, 3, ' ') }}</p>
                        </div>
                        <span class="px-3 py-1 bg-white border border-gray-200 text-xs font-bold rounded-lg shadow-sm text-gray-600">{{ $detectedOperator }}</span>
                        <input type="hidden" name="payment_method" value="{{ $detectedOperator }}">
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-100 text-amber-800 text-xs px-4 py-3 rounded-xl flex items-center gap-2 font-medium">
                        <i class="fas fa-exclamation-triangle"></i>
                        Veuillez ajouter un numéro valide dans votre profil.
                    </div>
                @endif

                <button type="submit" class="w-full font-bold text-white bg-emerald-600 hover:bg-emerald-700 py-4 rounded-2xl shadow-lg shadow-emerald-200 transition">
                    Déposer maintenant
                </button>
            </form>
        </div>

        <!-- Historique abrégé (Optionnel, on peut le rendre plus discret) -->
        <div class="mt-8 text-sm">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-bold text-gray-700">Derniers dépôts</h3>
                <a href="{{ route('transaction') }}" class="text-emerald-600 hover:underline">Voir tout</a>
            </div>

            @if($depots->count() > 0)
                <div class="space-y-3">
                    @foreach($depots->take(3) as $depot)
                        <div class="bg-white rounded-2xl p-4 flex items-center justify-between border border-gray-100 shadow-sm hover:shadow-md transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center"><i class="fas fa-arrow-down"></i></div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ number_format($depot->montant ?? $depot->amount, 2) }} $</p>
                                    <p class="text-xs text-gray-400">{{ $depot->created_at->format('d M, H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium text-gray-500 mb-1">{{ $depot->gateway === 'notchpay' ? 'NotchPay' : ($depot->operator ?? 'Mobile Money') }}</p>
                                @if($depot->status === 'completed')
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md bg-green-100 text-green-700">Crédité</span>
                                @elseif(in_array($depot->status, ['failed', 'canceled', 'rejected']))
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
                    Aucun dépôt récent
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    const rate = {{ $localRate }};
    const usdInput = document.getElementById('amountUsd');
    const fcfaOutput = document.getElementById('amountFcfa');

    function updateFcfa() {
        const usd = parseFloat(usdInput.value) || 0;
        const local = Math.round(usd * rate);
        fcfaOutput.textContent = '≈ ' + local.toLocaleString('fr-FR') + ' {{ $localSymbol }}';
    }

    usdInput?.addEventListener('input', updateFcfa);

    document.querySelectorAll('.quick-amount').forEach(btn => {
        btn.addEventListener('click', () => {
            usdInput.value = btn.dataset.amount;
            updateFcfa();
        });
    });

    updateFcfa();
</script>

<!-- Add Animate.css if not present globally -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-layouts>
