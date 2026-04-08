<x-layouts :title="'Dépôt'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $balance = $user->account_balance ?? 0;
    $countryCode = $user->country_code ?? 237;
    $phone = $user->phone ?? '';
    $isCameroon = $countryCode == 237;

    // Mapping des devises par code pays (taux approximatifs USD à devise locale)
    $currencyMap = [
        237 => ['code' => 'XAF', 'symbol' => 'CFA', 'rate' => 600], // Cameroun
        225 => ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 600], // Côte d'Ivoire
        221 => ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 600], // Sénégal
        223 => ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 600], // Mali
        226 => ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 600], // Burkina Faso
        227 => ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 600], // Niger
        224 => ['code' => 'GNF', 'symbol' => 'GNF', 'rate' => 8700], // Guinée
        229 => ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 600], // Bénin
        228 => ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 600], // Togo
        261 => ['code' => 'MGA', 'symbol' => 'MGA', 'rate' => 4500], // Madagascar
        241 => ['code' => 'XAF', 'symbol' => 'CFA', 'rate' => 600], // Gabon
        235 => ['code' => 'XAF', 'symbol' => 'CFA', 'rate' => 600], // Tchad
        242 => ['code' => 'XAF', 'symbol' => 'CFA', 'rate' => 600], // Congo Brazzaville
        243 => ['code' => 'CDF', 'symbol' => 'CDF', 'rate' => 2300], // RDC
        236 => ['code' => 'XAF', 'symbol' => 'CFA', 'rate' => 600], // Centrafrique
    ];

    // Récupérer la devise locale ou défaut XAF
    $localCurrency = $currencyMap[$countryCode] ?? ['code' => 'XAF', 'symbol' => 'CFA', 'rate' => 558];
    $localRate = $localCurrency['rate'];
    $localSymbol = $localCurrency['symbol'];

    // Détection opérateur UNIQUEMENT pour le Cameroun
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

<div class="min-h-screen bg-gradient-to-b from-emerald-50 via-teal-50 to-gray-100">

    <!-- Modal succès dépôt -->
    @if($lastPayment)
        <div class="fixed inset-0 z-50 flex items-end md:items-center justify-center bg-black/70 px-4 pb-4 pt-20 md:pt-0">
            <div class="w-full max-w-md bg-white rounded-t-3xl md:rounded-3xl shadow-2xl overflow-hidden animate__animated animate__slideInUp md:animate__zoomIn">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-10 text-center">
                    @php
                        $canal = $lastPayment['canal'] ?? ($lastPayment['operator'] ?? 'Mobile Money');
                        $isMesomb = in_array($canal, ['MeSomb', 'MTN', 'ORANGE']);
                    @endphp

                    @if($isMesomb)
                        @if($lastPayment['operator'] === 'MTN')
                            <img src="{{ asset('images/mtn-logo.png') }}" alt="MTN" class="h-20 w-20 mx-auto drop-shadow-xl">
                        @elseif($lastPayment['operator'] === 'ORANGE')
                            <img src="{{ asset('images/orange-logo.png') }}" alt="Orange" class="h-20 w-20 mx-auto drop-shadow-xl">
                        @endif
                        <h3 class="text-3xl font-extrabold text-white mt-6">{{ $lastPayment['operator'] ?? 'Mobile Money' }}</h3>
                    @else
                        <i class="fas fa-qrcode text-8xl text-white mb-6"></i>
                        <h3 class="text-3xl font-extrabold text-white">NotchPay</h3>
                    @endif
                </div>

                <div class="p-10 text-center space-y-8 bg-white">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Dépôt initié !</h2>

                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-3xl p-8">
                        <p class="text-gray-600 text-lg">Montant</p>
                        <p class="text-5xl md:text-6xl font-extrabold text-emerald-600 mt-2">{{ number_format($lastPayment['amount'], 2) }} $</p>
                        <p class="text-2xl md:text-3xl text-gray-600 mt-3">≈ {{ number_format(round($lastPayment['amount'] * $localRate)) }} {{ $localSymbol }}</p>
                    </div>

                    @if($isMesomb)
                        <p class="text-gray-700 text-lg leading-relaxed">
                            Validez sur votre téléphone<br>
                            <strong>Crédit automatique</strong>
                        </p>
                    @else
                        <p class="text-gray-700 text-lg leading-relaxed">
                            Scannez le QR code ou cliquez sur le lien<br>
                            <strong>Crédit automatique après paiement</strong>
                        </p>

                        @if($lastPayment['qr_url'] ?? false)
                            <div class="bg-white p-6 rounded-3xl shadow-2xl">
                                <img src="{{ $lastPayment['qr_url'] }}" alt="QR NotchPay" class="w-64 h-64 md:w-80 md:h-80 mx-auto">
                            </div>
                        @endif

                        @if($lastPayment['payment_url'] ?? false)
                            <a href="{{ $lastPayment['payment_url'] }}" target="_blank"
                               class="block bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-extrabold text-xl md:text-2xl py-6 rounded-2xl shadow-xl transition">
                                Payer avec NotchPay
                            </a>
                        @endif
                    @endif

                    <button onclick="document.getElementById('paymentSuccessModal').remove()"
                            class="w-full bg-gray-800 hover:bg-black text-white font-bold text-xl md:text-2xl py-6 rounded-2xl shadow-xl transition">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Contenu principal -->
    <div class="pt-8 pb-24 px-4 md:px-8 max-w-5xl mx-auto">

        <!-- Solde actuel -->
        <div class="bg-white rounded-3xl shadow-2xl p-10 text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-700 mb-6">Votre solde actuel</h2>
            <p class="text-5xl md:text-7xl font-extrabold text-emerald-600">{{ number_format($balance, 2) }} $</p>
            <p class="text-3xl md:text-4xl text-gray-600 mt-4">≈ {{ number_format(round($balance * $localRate)) }} {{ $localSymbol }}</p>
        </div>

        <!-- Messages flash -->
        @if(session('success') && !$lastPayment)
            <div class="bg-emerald-100 border-2 border-emerald-400 text-emerald-800 px-8 py-6 rounded-2xl text-center font-bold text-xl md:text-2xl shadow-lg mb-10">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-2 border-red-400 text-red-800 px-8 py-6 rounded-2xl text-center font-bold text-xl md:text-2xl shadow-lg mb-10">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulaire dépôt -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-10 text-center">
                <h3 class="text-3xl md:text-4xl font-extrabold text-white">Choisissez votre canal de dépôt</h3>
            </div>

            <form id="depositForm" action="{{ route('depot.store') }}" method="POST" class="p-8 md:p-12 space-y-12">
                @csrf

                <!-- Choix du canal selon le pays -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                    <!-- Canal 1 : uniquement pour le Cameroun -->
                    <label class="cursor-pointer {{ !$isCameroon ? 'opacity-50 pointer-events-none' : '' }}">
                        <input type="radio" name="canal" value="mesomb" {{ $isCameroon ? 'checked' : 'disabled' }} class="hidden peer">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-10 md:p-12 text-white shadow-2xl {{ $isCameroon ? 'peer-checked:ring-8 peer-checked:ring-emerald-400' : '' }} transition-all text-center">
                            <i class="fas fa-mobile-alt text-7xl md:text-9xl mb-8"></i>
                            <h4 class="text-2xl md:text-3xl font-extrabold mb-4">Canal Paiement 1</h4>
                            <p class="text-lg md:text-xl opacity-90 leading-relaxed">MTN • Orange Money<br>Crédit instantané</p>
                            @if(!$isCameroon)
                                <p class="text-sm mt-4 bg-black/30 px-4 py-2 rounded-full">Non disponible dans votre pays</p>
                            @endif
                        </div>
                    </label>

                    <!-- Canal 2 : pour tous sauf Cameroun -->
                    <label class="cursor-pointer {{ $isCameroon ? 'opacity-50 pointer-events-none' : '' }}">
                        <input type="radio" name="canal" value="notchpay" {{ !$isCameroon ? 'checked' : 'disabled' }} class="hidden peer">
                        <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-3xl p-10 md:p-12 text-white shadow-2xl {{ !$isCameroon ? 'peer-checked:ring-8 peer-checked:ring-purple-400' : '' }} transition-all text-center">
                            <i class="fas fa-qrcode text-7xl md:text-9xl mb-8"></i>
                            <h4 class="text-2xl md:text-3xl font-extrabold mb-4">Canal Paiement 2</h4>
                            <p class="text-lg md:text-xl opacity-90 leading-relaxed">Carte • Crypto • Mobile Money<br>Toutes devises</p>
                            @if($isCameroon)
                                <p class="text-sm mt-4 bg-black/30 px-4 py-2 rounded-full">Indisponible au cameroun</p>
                            @endif
                        </div>
                    </label>
                </div>

                <!-- Montant -->
                <div class="space-y-8">
                    <div>
                        <label class="block text-2xl md:text-3xl font-bold text-center text-gray-700 mb-6">Montant en USD</label>
                        <input type="number" name="amount" id="amountUsd" step="0.01" min="10" required
                               class="w-full text-center text-5xl md:text-6xl font-extrabold py-8 border-4 border-emerald-300 rounded-3xl focus:border-emerald-500 focus:outline-none transition"
                               placeholder="25.00" value="{{ old('amount') }}">

                        <div class="text-center mt-8">
                            <p class="text-xl md:text-2xl text-gray-600">Équivalent en {{ $localSymbol }}</p>
                            <p id="amountFcfa" class="text-5xl md:text-6xl font-extrabold text-emerald-600 mt-4">0 {{ $localSymbol }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6">
                        <button type="button" data-amount="10" class="quick-amount bg-gradient-to-r from-emerald-100 to-teal-100 hover:from-emerald-200 hover:to-teal-200 text-emerald-800 font-extrabold text-2xl md:text-3xl py-10 rounded-3xl shadow-xl transition transform hover:scale-105">
                            10 $
                        </button>
                        <button type="button" data-amount="50" class="quick-amount bg-gradient-to-r from-emerald-100 to-teal-100 hover:from-emerald-200 hover:to-teal-200 text-emerald-800 font-extrabold text-2xl md:text-3xl py-10 rounded-3xl shadow-xl transition transform hover:scale-105">
                            50 $
                        </button>
                        <button type="button" data-amount="100" class="quick-amount bg-gradient-to-r from-emerald-100 to-teal-100 hover:from-emerald-200 hover:to-teal-200 text-emerald-800 font-extrabold text-2xl md:text-3xl py-10 rounded-3xl shadow-xl transition transform hover:scale-105">
                            100 $
                        </button>
                    </div>
                </div>

                <!-- Info Canal 1 (seulement si Cameroun) -->
                @if($isCameroon)
                    <div id="mesombInfo" class="space-y-6">
                        @if($detectedOperator !== 'UNKNOWN')
                            <div class="bg-gradient-to-r from-emerald-100 to-teal-100 border-4 border-emerald-400 rounded-3xl p-10 text-center">
                                <p class="text-emerald-800 font-bold text-2xl md:text-3xl mb-4">Opérateur détecté</p>
                                <p class="text-5xl md:text-6xl font-extrabold text-emerald-700">{{ $detectedOperator }}</p>
                                <p class="text-xl md:text-2xl text-gray-700 mt-6">+237 {{ chunk_split($phone, 3, ' ') }}</p>
                                <input type="hidden" name="payment_method" value="{{ $detectedOperator }}">
                            </div>
                        @else
                            <div class="bg-gradient-to-r from-amber-100 to-orange-100 border-4 border-amber-400 rounded-3xl p-10 text-center">
                                <p class="text-amber-800 font-bold text-2xl md:text-3xl">Numéro non détecté</p>
                                <p class="text-lg md:text-xl text-gray-700 mt-6">Vérifiez votre numéro dans le profil</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Info Canal 2 (seulement si pas Cameroun) -->
                @if(!$isCameroon)
                    <div id="notchpayInfo" class="text-center space-y-10">
                        <div class="bg-gradient-to-br from-purple-100 to-pink-100 border-4 border-purple-400 rounded-3xl p-12">
                            <p class="text-purple-800 font-extrabold text-2xl md:text-3xl mb-10">Après confirmation :</p>
                            <div class="bg-white p-10 rounded-3xl shadow-2xl">
                                <div class="w-64 h-64 md:w-80 md:h-80 bg-gray-200 rounded-2xl border-4 border-dashed border-purple-300 flex items-center justify-center mx-auto">
                                    <i class="fas fa-qrcode text-7xl md:text-9xl text-purple-400"></i>
                                </div>
                            </div>
                            <p class="text-purple-700 text-xl md:text-2xl mt-10">Scannez le QR code ou payez par carte/crypto</p>
                        </div>
                    </div>
                @endif

                <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white font-extrabold text-3xl md:text-4xl py-8 rounded-3xl shadow-3xl transition transform hover:scale-105">
                    CONFIRMER LE DÉPÔT
                </button>
            </form>
        </div>

        <!-- Historique -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mt-12">
            <h3 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-8">Historique des dépôts</h3>
            @if($depots->count() > 0)
                <div class="space-y-6">
                    @foreach($depots->take(6) as $depot)
                        <div class="flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-gray-50 to-emerald-50 rounded-2xl p-6 shadow-lg">
                            <div class="text-center md:text-left mb-4 md:mb-0">
                                <p class="text-2xl md:text-3xl font-extrabold text-emerald-600">
                                    {{ number_format($depot->montant ?? $depot->amount, 2) }} $
                                </p>
                                <p class="text-lg md:text-xl text-gray-600">
                                    {{ number_format(round(($depot->montant ?? $depot->amount) * $localRate)) }} {{ $localSymbol }}
                                </p>
                                <p class="text-sm md:text-base text-gray-500 mt-3">
                                    {{ $depot->gateway === 'notchpay' ? 'NotchPay' : ($depot->operator ?? 'Mobile Money') }}
                                    • {{ $depot->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <span class="px-6 py-3 rounded-full text-lg md:text-xl font-bold
                                {{ $depot->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $depot->status === 'completed' ? 'Crédité' : 'En cours' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 text-xl md:text-2xl py-16">Aucun dépôt effectué</p>
            @endif
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    const rate = {{ $localRate }};
    const usdInput = document.getElementById('amountUsd');
    const fcfaOutput = document.getElementById('amountFcfa');

    function updateFcfa() {
        const usd = parseFloat(usdInput.value) || 0;
        const local = Math.round(usd * rate);
        fcfaOutput.textContent = local.toLocaleString('fr-FR') + ' {{ $localSymbol }}';
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-layouts>