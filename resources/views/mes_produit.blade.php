<x-layouts :title="'Mes Produits'" :level="'Vip1'">

<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-8 ">
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Header -->
        <div class="text-center px-4">
            <h1 class="text-2xl sm:text-4xl font-bold text-green-800 mb-2">Mes Produits BioEnergy</h1>
            <p class="text-sm sm:text-gray-600">Suivez vos investissements et vos gains journaliers</p>
        </div>

        <!-- Prochain gain + Revenus du jour -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8 px-4">
            <!-- Compte à rebours -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-3xl shadow-2xl p-6 sm:p-8 text-center">
                <h3 class="text-base sm:text-xl font-semibold mb-4 opacity-90">Prochain gain dans</h3>
                <div id="countdown" class="text-2xl sm:text-4xl font-bold tracking-wider">
                    Calcul en cours...
                </div>
                <p class="text-xs opacity-70 mt-4">Crédité automatiquement à minuit</p>
            </div>

            <!-- Revenus du jour -->
            <div class="bg-white/90 backdrop-blur rounded-3xl shadow-2xl p-6 sm:p-8 text-center border border-green-100">
                <h3 class="text-base sm:text-xl font-semibold text-gray-800 mb-3">Revenus crédités aujourd'hui</h3>
                <div class="text-3xl sm:text-5xl font-bold text-green-600 mb-1">{{ fmtCurrency($revenusJournee) }}</div>
                <p class="text-xs text-gray-400 mt-4">Du lundi au samedi</p>
            </div>
        </div>

        <!-- Liste des investissements -->
        <div class="space-y-6 sm:space-y-8 px-4">
            @forelse($orders as $order)
                @php
                    $p = $order->produit;
                    $invested = $order->amount_invested ?? 0;
                    $dayIncome = $order->day_income;
                    $start = \Carbon\Carbon::parse($order->start_date);
                    $end = \Carbon\Carbon::parse($order->end_date);
                    $now = \Carbon\Carbon::now();

                    $daysPassed = $start->diffInDays($now);
                    $totalDays = $start->diffInDays($end);

                    $earnedSoFar = \App\Models\Transaction::where('user_id', Auth::id())
                        ->where('type', 'gain_journalier')
                        ->where('order_id', $order->id)
                        ->sum('montant');

                    $progress = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100, 1)) : 100;
                @endphp

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-green-100">
                    <div class="flex flex-col lg:flex-row">
                        <div class="lg:w-80 h-48 lg:h-auto overflow-hidden">
                            <img src="{{ asset('images/produits/produit' . $p->id . '.jpg') }}"
                                 alt="{{ $p->name }}"
                                 class="w-full h-full object-cover">
                        </div>

                        <div class="flex-1 p-6 sm:p-8 space-y-5 sm:space-y-6">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold text-emerald-700">{{ $p->name }}</h3>
                                <p class="text-sm sm:text-base text-gray-500 mt-1 line-clamp-2">{{ $p->description }}</p>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 text-center">
                                <div class="bg-green-50 rounded-2xl p-3 sm:p-4">
                                    <p class="text-[10px] sm:text-sm text-gray-500 uppercase tracking-wider">Investi</p>
                                    <p class="text-base sm:text-2xl font-bold text-green-700">{{ fmtCurrency($invested) }}</p>
                                </div>
                                <div class="bg-yellow-50 rounded-2xl p-3 sm:p-4">
                                    <p class="text-[10px] sm:text-sm text-gray-500 uppercase tracking-wider">Gain/jour</p>
                                    <p class="text-base sm:text-2xl font-bold text-yellow-600">{{ fmtCurrency($dayIncome) }}</p>
                                </div>
                                <div class="bg-blue-50 rounded-2xl p-3 sm:p-4">
                                    <p class="text-[10px] sm:text-sm text-gray-500 uppercase tracking-wider">Cumul</p>
                                    <p class="text-base sm:text-2xl font-bold text-blue-700">{{ fmtCurrency($earnedSoFar) }}</p>
                                </div>
                                <div class="bg-red-50 rounded-2xl p-3 sm:p-4">
                                    <p class="text-[10px] sm:text-sm text-gray-500 uppercase tracking-wider">Fin</p>
                                    <p class="text-base sm:text-xl font-black text-red-600">{{ $end->format('d/m/y') }}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between text-xs sm:text-sm">
                                    <span class="text-gray-500">Progression du contrat</span>
                                    <span class="font-bold text-emerald-600">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3 sm:h-5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-full rounded-full transition-all duration-1000"
                                         style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="flex justify-between text-[10px] sm:text-xs text-gray-400">
                                    <span>{{ $daysPassed }} j. écoulés</span>
                                    <span>{{ $totalDays }} j. totaux</span>
                                </div>
                            </div>

                            <button onclick="openDetails({{ $order->id }})"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 sm:py-4 rounded-xl shadow-lg hover:shadow-xl transition transform active:scale-95">
                                Détails complets
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
<div id="detailsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 p-2 sm:p-4">
    <div class="bg-white rounded-3xl shadow-3xl max-w-4xl w-full max-h-[92vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-4 sm:p-6 flex justify-between items-center z-10">
            <h3 id="modalTitle" class="text-xl sm:text-2xl font-bold text-gray-800 truncate pr-4"></h3>
            <button onclick="closeDetails()" class="text-gray-400 hover:text-gray-800 text-3xl sm:text-4xl">&times;</button>
        </div>
        <div class="p-4 sm:p-8" id="modalContent"></div>
    </div>
</div>

<script>
    const countdownElement = document.getElementById('countdown');
    const ordersData = @json($ordersForJs);
    const CURRENCY = "{{ Auth::user()->currency }}";

    function updateCountdown() {
        const now = new Date();
        const dayOfWeek = now.getDay();

        if (dayOfWeek === 0) {
            countdownElement.innerHTML = `
                <span class="text-2xl sm:text-4xl font-bold text-yellow-300">Pas de crédit aujourd'hui</span>
                <p class="text-sm mt-2 opacity-90">Reprise lundi à minuit</p>
            `;
            return;
        }

        const midnight = new Date(now);
        midnight.setHours(24, 0, 0, 0);
        const diff = midnight - now;

        const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
        const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
        const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');

        countdownElement.innerHTML = `
            <div class="flex justify-center gap-2 sm:gap-4">
                <div class="flex flex-col items-center">
                    <span class="bg-white/10 rounded-xl px-3 py-2 sm:px-6 sm:py-5 text-2xl sm:text-4xl font-mono">${h}</span>
                    <span class="text-[8px] sm:text-xs mt-1 uppercase opacity-60">heures</span>
                </div>
                <div class="text-2xl sm:text-4xl pt-2">:</div>
                <div class="flex flex-col items-center">
                    <span class="bg-white/10 rounded-xl px-3 py-2 sm:px-6 sm:py-5 text-2xl sm:text-4xl font-mono">${m}</span>
                    <span class="text-[8px] sm:text-xs mt-1 uppercase opacity-60">minutes</span>
                </div>
                <div class="text-2xl sm:text-4xl pt-2">:</div>
                <div class="flex flex-col items-center">
                    <span class="bg-white/10 rounded-xl px-3 py-2 sm:px-6 sm:py-5 text-2xl sm:text-4xl font-mono">${s}</span>
                    <span class="text-[8px] sm:text-xs mt-1 uppercase opacity-60">secondes</span>
                </div>
            </div>
        `;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    function openDetails(orderId) {
        const data = ordersData.find(o => o.id === orderId);
        if (!data) return;

        document.getElementById('modalTitle').textContent = data.produitName;

        document.getElementById('modalContent').innerHTML = `
            <div class="space-y-6 sm:space-y-8">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 sm:p-6 rounded-r-2xl">
                    <p class="text-sm sm:text-lg italic text-blue-800 leading-relaxed">"${data.description}"</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mt-6">
                    <div class="bg-green-50 rounded-2xl p-5 sm:p-8 text-center border border-green-100">
                        <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-widest mb-2">Montant investi</p>
                        <p class="text-3xl sm:text-4xl font-black text-green-700">${Number(data.invested).toLocaleString('fr-FR')} ${CURRENCY}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-2xl p-5 sm:p-8 text-center border border-yellow-100">
                        <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-widest mb-2">Gain journalier</p>
                        <p class="text-3xl sm:text-4xl font-black text-yellow-600">${Number(data.dayIncome).toLocaleString('fr-FR')} ${CURRENCY}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div class="bg-blue-50/50 rounded-xl p-3 sm:p-5 border border-blue-100">
                        <p class="text-[10px] sm:text-xs text-gray-400 uppercase mb-1">Total Cumulé</p>
                        <p class="text-base sm:text-xl font-bold text-blue-700">${Number(data.earnedSoFar).toLocaleString('fr-FR')} ${CURRENCY}</p>
                    </div>
                    <div class="bg-indigo-50/50 rounded-xl p-3 sm:p-5 border border-indigo-100">
                        <p class="text-[10px] sm:text-xs text-gray-400 uppercase mb-1">Date début</p>
                        <p class="text-base sm:text-xl font-bold text-indigo-700">${data.start}</p>
                    </div>
                    <div class="bg-red-50/50 rounded-xl p-3 sm:p-5 border border-red-100">
                        <p class="text-[10px] sm:text-xs text-gray-400 uppercase mb-1">Date fin</p>
                        <p class="text-base sm:text-xl font-bold text-red-700">${data.end}</p>
                    </div>
                    <div class="bg-emerald-50/50 rounded-xl p-3 sm:p-5 border border-emerald-100">
                        <p class="text-[10px] sm:text-xs text-gray-400 uppercase mb-1">Progression</p>
                        <p class="text-base sm:text-xl font-bold text-emerald-700">${data.progress}%</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5 sm:p-8 border border-gray-100">
                    <h4 class="text-base sm:text-xl font-bold text-gray-800 mb-3 uppercase tracking-wider">Informations détaillées</h4>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed whitespace-pre-line">
                        ${data.information || 'Aucune information supplémentaire disponible.'}
                    </p>
                </div>

                <button onclick="closeDetails()" class="w-full bg-gray-100 text-gray-600 font-bold py-4 rounded-xl hover:bg-gray-200 transition">
                    Fermer les détails
                </button>
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