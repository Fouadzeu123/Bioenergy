<x-layouts :title="'Dépôt'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $balance = $user->account_balance ?? 0;
    $phone = $user->phone ?? '';
    $isCameroon = true;

    // Fixé pour le Cameroun
    $localRate = config('notchpay.usd_to_xaf', 600);
    $localSymbol = 'CFA';

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

    <!-- Modal succès/attente dépôt -->
    @if($lastPayment)
        <div id="paymentSuccessModal" class="fixed inset-0 z-50 flex items-end md:items-center justify-center bg-black/70 px-4 pb-4 pt-20 md:pt-0">
            <div class="w-full max-w-md bg-white rounded-t-3xl md:rounded-3xl shadow-2xl overflow-hidden animate__animated animate__slideInUp md:animate__zoomIn">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-10 text-center">
                    @php
                        $canal = $lastPayment['canal'] ?? ($lastPayment['operator'] ?? 'Mobile Money');
                        $isNotchPay = in_array($canal, ['NotchPay', 'MTN', 'ORANGE']);
                    @endphp

                    @if($isNotchPay)
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

                <div class="p-10 text-center space-y-8 bg-white" id="paymentStatusContent">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Dépôt en attente !</h2>

                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-3xl p-8">
                        <p class="text-gray-600 text-lg">Montant</p>
                        <p class="text-5xl md:text-6xl font-extrabold text-emerald-600 mt-2">{{ number_format($lastPayment['amount'], 2) }} $</p>
                        <p class="text-2xl md:text-3xl text-gray-600 mt-3">≈ {{ number_format(round($lastPayment['amount'] * $localRate)) }} {{ $localSymbol }}</p>
                    </div>

                    @if($isNotchPay)
                        <div class="text-gray-700 text-lg leading-relaxed flex flex-col items-center">
                            <i class="fas fa-spinner fa-spin text-4xl text-emerald-500 mb-4" id="loadingSpinner"></i>
                            <span id="instructionText">
                                Validez la notification sur votre téléphone<br>
                                <strong>Vérification automatique en cours...</strong>
                            </span>
                        </div>
                    @else
                        <p class="text-gray-700 text-lg leading-relaxed">
                            Scannez le QR code ou cliquez sur le lien<br>
                            <strong>Crédit automatique après paiement</strong>
                        </p>
                    @endif

                    <button onclick="document.getElementById('paymentSuccessModal').remove()"
                            id="closeModalBtn"
                            class="w-full bg-gray-800 hover:bg-black text-white font-bold text-xl md:text-2xl py-6 rounded-2xl shadow-xl transition">
                        Fermer la fenêtre (l'attente continuera en arrière plan)
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Logique JS pour vérifier le statut automatiquement (polling)
            const ref = "{{ $lastPayment['reference'] ?? '' }}";
            let pollInterval;

            function verifierStatutPaiement() {
                if (!ref) return;

                fetch(`/depot/status/${ref}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'completed') {
                            clearInterval(pollInterval);
                            document.getElementById('loadingSpinner').className = "fas fa-check-circle text-5xl text-emerald-500 mb-4 animate__animated animate__bounceIn";
                            document.getElementById('instructionText').innerHTML = "<strong>Paiement reçu avec succès !</strong><br>Votre solde va être mis à jour.";
                            document.getElementById('closeModalBtn').innerText = "Rafraîchir maintenant";
                            document.getElementById('closeModalBtn').className = "w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xl md:text-2xl py-6 rounded-2xl shadow-xl transition";
                            document.getElementById('closeModalBtn').onclick = () => window.location.href = "{{ route('deposit') }}";
                            
                            // Recharge auto après 3 secondes au cas où l'utilisateur ne clique pas
                            setTimeout(() => {
                                window.location.href = "{{ route('deposit') }}";
                            }, 3000);
                        } else if (data.status === 'failed') {
                            clearInterval(pollInterval);
                            document.getElementById('loadingSpinner').className = "fas fa-times-circle text-5xl text-red-500 mb-4 animate__animated animate__shakeX";
                            document.getElementById('instructionText').innerHTML = "<strong>Le paiement a échoué ou a été annulé.</strong><br>Veuillez réessayer.";
                            document.getElementById('closeModalBtn').innerText = "Fermer";
                        }
                    })
                    .catch(err => console.error("Erreur check status", err));
            }

            if (ref) {
                // Vérifier toutes les 3 secondes
                pollInterval = setInterval(verifierStatutPaiement, 3000);
            }
        </script>
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
                <div class="grid grid-cols-1 gap-8 lg:gap-12 max-w-2xl mx-auto">
                    <!-- Canal 1 : uniquement pour le Cameroun -->
                    <label class="cursor-pointer">
                        <input type="radio" name="canal" value="notchpay" checked class="hidden peer">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-10 md:p-12 text-white shadow-2xl peer-checked:ring-8 peer-checked:ring-emerald-400 transition-all text-center">
                            <i class="fas fa-mobile-alt text-7xl md:text-9xl mb-8"></i>
                            <h4 class="text-2xl md:text-3xl font-extrabold mb-4">Paiement Mobile Money</h4>
                            <p class="text-lg md:text-xl opacity-90 leading-relaxed">MTN • Orange Money<br>Crédit instantané</p>
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

                <!-- Info Canal 1 -->
                <div id="notchPayInfo" class="space-y-6">
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