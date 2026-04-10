<x-layouts :title="'Fond de Préservation'" :level="Auth::user()->level">

    @php
        $USD_TO_F = 600;
    @endphp

    <!-- Hero -->
    <div class="w-full relative">
        <img src="{{ asset('images/preservation.jpg') }}" alt="Fond de Préservation BioEnergy"
             class="w-full h-44 sm:h-64 object-cover rounded-b-lg shadow-lg">
        <div class="absolute inset-0 flex items-center justify-center px-4">
            <div class="bg-black/35 rounded-lg px-4 py-3 text-center w-full max-w-4xl">
                <h1 class="text-lg sm:text-2xl font-extrabold text-white">🌱 Fonds de Préservation</h1>
                <p class="text-xs sm:text-sm text-white/90 mt-1">Choisissez un produit d’épargne adapté à vos objectifs et suivez vos placements.</p>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-6">

        <!-- CTA -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-sm text-gray-700">
                <strong class="text-green-700">Astuce :</strong> Investissez dès aujourd’hui pour commencer à générer des revenus à la fin de la période.
            </div>

            <div class="flex gap-3">
                @if(!isset($afficherEpargnesSeulement) || !$afficherEpargnesSeulement)
                    <a href="{{ route('mes.epargnes') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        📄 Mes Épargnes
                    </a>
                @else
                    <a href="{{ route('fond.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                        🔙 Retour aux fonds
                    </a>
                @endif
            </div>
        </div>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded shadow text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded shadow text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Liste des fonds -->
        @if(!isset($afficherEpargnesSeulement) || !$afficherEpargnesSeulement)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($fonds as $fond)
                    @php
                        $min = $fond->min_amount ?? 0;
                        // assume stored in dollars already; if stored in F convert accordingly
                        $minF = $min * $USD_TO_F;
                    @endphp

                    <div class="bg-white rounded-xl shadow-md p-5 flex flex-col">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-blue-700">{{ $fond->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($fond->description, 120) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $fond->rate }} %
                                </span>
                                <div class="text-xs text-gray-400 mt-1">Durée: {{ $fond->period_days }}j</div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-700">
                            <div>
                                <div class="text-xs text-gray-500">Montant min</div>
                                <div class="font-semibold">{{ fmtUsd($min) }} <span class="text-xs text-gray-500"> (≈ {{ fmtFcfa($minF) }})</span></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Limite d’ordre</div>
                                <div class="font-semibold">{{ $fond->limit_order ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <button onclick="openInvestModal({{ $fond->id }})"
                                    data-min="{{ $min }}"
                                    data-name="{{ htmlspecialchars($fond->name, ENT_QUOTES) }}"
                                    data-rate="{{ $fond->rate }}"
                                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                                Investir
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Info block -->
            <div class="max-w-3xl mx-auto mt-6 bg-yellow-50 border border-yellow-200 rounded-lg shadow p-4 text-sm text-gray-700">
                <h4 class="font-semibold text-yellow-700 mb-2">ℹ️ Informations importantes</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Les fonds sont bloqués pendant la période choisie. Le capital et le rendement sont restitués à la fin.</li>
                    <li>Le rendement indiqué est appliqué sur le capital investi et versé à la clôture.</li>
                    <li>Respectez le montant minimum du produit pour valider l’investissement.</li>
                </ul>
            </div>
        @endif

        <!-- Mes Épargnes -->
        <div id="mes-epargnes" class="mt-8 bg-white rounded-xl shadow p-5">
            <h2 class="text-xl font-bold text-green-700 mb-4 text-center">📄 Mes Épargnes</h2>

            @forelse($mesEpargnes as $epargne)
                @php
                    $pres = $epargne->preservation;
                    $amount = $epargne->amount ?? 0;
                    $revenu = $epargne->revenu_attendu ?? 0;
                    $start = \Carbon\Carbon::parse($epargne->start_date);
                    $end = \Carbon\Carbon::parse($epargne->end_date);
                    $now = \Carbon\Carbon::now();
                    $totalDays = max(1, $start->diffInDays($end));
                    $daysPassed = min($totalDays, $start->diffInDays($now));
                    $progress = round(($daysPassed / $totalDays) * 100);
                @endphp

                <div class="border border-gray-100 rounded-lg p-4 mb-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-700">{{ $pres->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($pres->description, 140) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Statut</div>
                            @if($epargne->is_closed)
                                <div class="text-sm font-semibold text-green-600">Terminé ✅</div>
                            @else
                                <div class="text-sm font-semibold text-yellow-600">En cours ⏳</div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm text-gray-700">
                        <div>
                            <div class="text-xs text-gray-500">Montant investi</div>
                            <div class="font-semibold">{{ fmtUsd($amount) }} <span class="text-xs text-gray-500"> (≈ {{ fmtFcfa($amount * $USD_TO_F) }})</span></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Revenu attendu</div>
                            <div class="font-semibold">{{ fmtUsd($revenu) }} <span class="text-xs text-gray-500"> (≈ {{ fmtFcfa($revenu * $USD_TO_F) }})</span></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Période</div>
                            <div class="font-semibold">{{ $start->format('d/m/Y') }} → {{ $end->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 bg-green-500" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Progression : {{ $progress }}% • Jours passés : {{ $daysPassed }} / {{ $totalDays }}</div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Vous n’avez encore effectué aucune épargne.</p>
            @endforelse
        </div>
    </div>

    <!-- Modal Investissement -->
    <div id="investModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-3">
                <h4 id="modalTitle" class="text-lg font-semibold">Investir</h4>
                <button onclick="closeInvestModal()" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            <form id="investForm" method="POST" action="#" class="space-y-4">
                @csrf
                <input type="hidden" name="fond_id" id="modalFondId" value="">
                <div>
                    <div class="text-sm text-gray-500">Produit</div>
                    <div id="modalFondName" class="font-semibold text-gray-800"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Montant à investir (en $)</label>
                    <input id="modalAmount" name="amount" type="number" step="0.01" min="0" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Ex: 50.00">
                    <p id="modalAmountHelp" class="text-xs text-gray-500 mt-1"></p>
                    <p id="modalAmountF" class="text-xs text-gray-600 mt-1 font-semibold"></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Taux</div>
                        <div id="modalRate" class="font-semibold text-gray-800">—</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Durée</div>
                        <div id="modalPeriod" class="font-semibold text-gray-800">—</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        Confirmer l'investissement
                    </button>
                    <button type="button" onclick="closeInvestModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const USD_TO_F = {{ $USD_TO_F }};

        // Ouvrir modal avec données du fond
        function openInvestModal(id) {
            const btn = document.querySelector(`[onclick="openInvestModal(${id})"]`);
            const min = parseFloat(btn.getAttribute('data-min') || 0);
            const name = btn.getAttribute('data-name') || '';
            const rate = btn.getAttribute('data-rate') || '';

            document.getElementById('modalFondId').value = id;
            document.getElementById('modalFondName').textContent = name;
            document.getElementById('modalRate').textContent = rate + ' %';
            document.getElementById('modalPeriod').textContent = 'Voir page produit';
            const amountInput = document.getElementById('modalAmount');
            amountInput.value = min > 0 ? min : '';
            amountInput.min = min;
            document.getElementById('modalAmountHelp').textContent = min > 0 ? 'Montant minimum : ' + min + ' $' : '';
            document.getElementById('modalAmountF').textContent = amountInput.value ? '≈ ' + Math.round(amountInput.value * USD_TO_F).toLocaleString() + ' F' : '';

            amountInput.oninput = function() {
                const v = parseFloat(this.value || 0);
                if (!isNaN(v) && v > 0) {
                    document.getElementById('modalAmountF').textContent = '≈ ' + Math.round(v * USD_TO_F).toLocaleString() + ' F';
                } else {
                    document.getElementById('modalAmountF').textContent = '';
                }
            };

            // set form action dynamically
            const form = document.getElementById('investForm');
            form.action = "{{ url('/fond-preservation') }}/" + id + "/epagner";
            const modal = document.getElementById('investModal');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeInvestModal() {
            const modal = document.getElementById('investModal');
            modal.classList.add('hidden'); modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close modal on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeInvestModal();
        });
    </script>

</x-layouts>
