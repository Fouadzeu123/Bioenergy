<x-layouts :title="'Mes Produits'" :level="'Vip1'">

@php
    $USD_TO_FCFA = 600;
@endphp

<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-8 ">
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Header -->
        <div class="text-center">
            <h1 class="text-4xl font-bold text-green-800 mb-2">Mes Produits BioEnergy</h1>
            <p class="text-gray-600">Suivez vos investissements et vos gains journaliers</p>
        </div>

        <!-- Prochain gain + Revenus du jour -->
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Compte à rebours -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-3xl shadow-2xl p-8 text-center">
                <h3 class="text-xl font-semibold mb-4 opacity-90">Prochain gain dans</h3>
                <div id="countdown" class="text-4xl font-bold tracking-wider">
                    Calcul en cours...
                </div>
                <p class="text-sm opacity-80 mt-4">Crédité automatiquement à minuit</p>
            </div>

            <!-- Revenus du jour -->
            <div class="bg-white/90 backdrop-blur rounded-3xl shadow-2xl p-8 text-center border border-green-100">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Revenus crédités aujourd'hui</h3>
                <div class="text-5xl font-bold text-green-600 mb-2">{{ fmtUSD($revenusJournee) }}</div>
                <div class="text-2xl text-gray-600">{{ fmtFCFA($revenusJournee * $USD_TO_FCFA) }}</div>
                <p class="text-sm text-gray-500 mt-4">Du lundi au samedi</p>
            </div>
        </div>

        <!-- Liste des investissements -->
        <div class="space-y-8">
            @forelse($orders as $order)
   @php
    $p = $order->produit;
    $invested = $order->amount_invested ?? 0;
    $dayIncome = $order->day_income;
    $start = \Carbon\Carbon::parse($order->start_date);
    $end = \Carbon\Carbon::parse($order->end_date);
    $now = \Carbon\Carbon::now();

    $daysPassed = $start->diffInDays($now);
    $daysRemaining = max(180, $end->diffInDays($now));
    $totalDays = $start->diffInDays($end);

    // Gains cumulés UNIQUEMENT pour CETTE commande
    $earnedSoFar = \App\Models\Transaction::where('user_id', Auth::id())
        ->where('type', 'gain_journalier')
        ->where('order_id', $order->id) // ← Filtre par produit
        ->sum('montant');

    $progress = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100, 1)) : 100;
@endphp

                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-300 border border-green-100">
                    <div class="flex flex-col lg:flex-row">
                        <div class="lg:w-80">
                            <img src="{{ asset('images/produits/produit' . $p->id . '.jpg') }}"
                                 alt="{{ $p->name }}"
                                 class="w-full h-64 lg:h-full object-cover">
                        </div>

                        <div class="flex-1 p-8 space-y-6">
                            <div>
                                <h3 class="text-2xl font-bold text-emerald-700">{{ $p->name }}</h3>
                                <p class="text-gray-600 mt-2">{{ $p->description }}</p>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                                <div class="bg-green-50 rounded-2xl p-4">
                                    <p class="text-sm text-gray-600">Investi</p>
                                    <p class="text-2xl font-bold text-green-700">{{ fmtUSD($invested) }}</p>
                                </div>
                                <div class="bg-yellow-50 rounded-2xl p-4">
                                    <p class="text-sm text-gray-600">Gain / jour</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ fmtUSD($dayIncome) }}</p>
                                </div>
                                <div class="bg-blue-50 rounded-2xl p-4">
                                    <p class="text-sm text-gray-600">Gains cumulés</p>
                                    <p class="text-2xl font-bold text-blue-700">{{ fmtUSD($earnedSoFar) }}</p>
                                </div>
                                <div class="bg-red-50 rounded-2xl p-4">
                                    <p class="text-sm text-gray-600">Fin le</p>
                                    <p class="text-xl font-bold text-red-600">{{ $end->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Progression</span>
                                    <span class="font-bold text-emerald-600">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-5 rounded-full transition-all duration-1000"
                                         style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ $daysPassed }} jours écoulés</span>
                                    <span>{{ $daysRemaining }} jours totaux</span>
                                </div>
                            </div>

                            <button onclick="openDetails({{ $order->id }})"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-lg py-4 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition transform hover:scale-105 shadow-lg">
                                Voir les détails complets
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-3xl shadow-2xl">
                    <div class="text-4xl mb-6">Aucun produit actif</div>
                    <p class="text-xl text-gray-600 mb-8">Commencez votre aventure BioEnergy !</p>
                    <a href="{{ route('products') }}"
                       class="inline-block bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold text-xl px-12 py-6 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition">
                        Découvrir les produits
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Détails -->
<div id="detailsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 p-4">
    <div class="bg-white rounded-3xl shadow-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 flex justify-between items-center rounded-t-3xl">
            <h3 id="modalTitle" class="text-3xl font-bold"></h3>
            <button onclick="closeDetails()" class="text-white hover:text-gray-200 text-4xl">×</button>
        </div>
        <div class="p-8 space-y-8" id="modalContent"></div>
    </div>
</div>

<script>
    const rate = {{ $USD_TO_FCFA }};
    const usdInput = document.getElementById('amountUsd');
    const fcfaOutput = document.getElementById('amountFcfa');
    const countdownElement = document.getElementById('countdown');
    // Données pour le modal
    const ordersData = @json($ordersForJs);

    // Compte à rebours jusqu'à minuit
    // Compte à rebours avec figé le dimanche
    function updateCountdown() {
        const now = new Date();
        const dayOfWeek = now.getDay(); // 0 = dimanche, 1 = lundi, ..., 6 = samedi

        if (dayOfWeek === 0) { // Dimanche
            countdownElement.innerHTML = `
                <span class="text-4xl md:text-6xl font-bold text-yellow-300">
                    Pas de crédit aujourd'hui
                </span>
                <p class="text-xl mt-6 opacity-90">Reprise demain lundi à minuit</p>
            `;
            return;
        }

        // Sinon : compte à rebours normal jusqu'à minuit
        const midnight = new Date(now);
        midnight.setHours(24, 0, 0, 0);
        const diff = midnight - now;

        const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
        const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
        const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');

        countdownElement.innerHTML = `
            <span class="inline-block bg-white/20 rounded-2xl px-8 py-5 text-6xl md:text-7xl">${h}</span>h
            <span class="inline-block bg-white/20 rounded-2xl px-8 py-5 text-6xl md:text-7xl">${m}</span>m
            <span class="inline-block bg-white/20 rounded-2xl px-8 py-5 text-6xl md:text-7xl">${s}</span>s
        `;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Modal détails
    function openDetails(orderId) {
        const data = ordersData.find(o => o.id === orderId);
        if (!data) return;

        document.getElementById('modalTitle').textContent = data.produitName;

        document.getElementById('modalContent').innerHTML = `
            <div class="space-y-8 text-center">
                <p class="text-xl italic text-gray-700">"${data.description}"</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                    <div class="bg-green-50 rounded-3xl p-10">
                        <p class="text-gray-600 mb-3">Montant investi</p>
                        <p class="text-5xl font-bold text-green-700">$${parseFloat(data.invested).toFixed(2)}</p>
                        <p class="text-2xl text-gray-600 mt-3">≈ ${(data.invested * 600).toLocaleString()} FCFA</p>
                    </div>
                    <div class="bg-yellow-50 rounded-3xl p-10">
                        <p class="text-gray-600 mb-3">Gain journalier</p>
                        <p class="text-5xl font-bold text-yellow-600">$${parseFloat(data.dayIncome).toFixed(4)}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-blue-50 rounded-2xl p-6"><p class="text-sm text-gray-600">Gains cumulés</p><p class="text-2xl font-bold text-blue-700">$${parseFloat(data.earnedSoFar).toFixed(2)}</p></div>
                    <div class="bg-purple-50 rounded-2xl p-6"><p class="text-sm text-gray-600">Début</p><p class="text-xl font-bold">${data.start}</p></div>
                    <div class="bg-red-50 rounded-2xl p-6"><p class="text-sm text-gray-600">Fin</p><p class="text-xl font-bold text-red-700">${data.end}</p></div>
                    <div class="bg-emerald-50 rounded-2xl p-6"><p class="text-sm text-gray-600">Progression</p><p class="text-3xl font-bold text-emerald-700">${data.progress}%</p></div>
                </div>

                <div class="bg-gray-50 rounded-3xl p-10">
                    <h4 class="text-2xl font-bold text-gray-800 mb-6">Informations détaillées</h4>
                    <p class="text-lg text-gray-700 leading-relaxed whitespace-pre-line">
                        ${data.information || 'Aucune information supplémentaire disponible.'}
                    </p>
                </div>
            </div>
        `;

        document.getElementById('detailsModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetails() {
        document.getElementById('detailsModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => e.key === 'Escape' && closeDetails());
</script>

</x-layouts>