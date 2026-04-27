<x-layouts :title="'Fond de Préservation'" :level="Auth::user()->level">

    @php
        $currency = Auth::user()->currency;
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
                    <a href="{{ route('mes.epargnes') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition font-bold">
                        📄 Mes Épargnes
                    </a>
                @else
                    <a href="{{ route('fond.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition font-bold">
                        🔙 Retour aux fonds
                    </a>
                @endif
            </div>
        </div>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded shadow text-center font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded shadow text-center font-bold">
                {{ session('error') }}
            </div>
        @endif

        <!-- Liste des fonds -->
        @if(!isset($afficherEpargnesSeulement) || !$afficherEpargnesSeulement)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($fonds as $fond)
                    <div class="bg-white rounded-xl shadow-md p-5 flex flex-col border border-gray-100">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-blue-700">{{ $fond->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($fond->description, 120) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $fond->rate }} %
                                </span>
                                <div class="text-xs text-gray-400 mt-1">Durée: {{ $fond->period_days }} j.</div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-700">
                            <div>
                                <div class="text-xs text-gray-500">Montant min</div>
                                <div class="font-bold text-gray-800">{{ fmtCurrency($fond->min_amount) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Limite d’ordre</div>
                                <div class="font-bold text-gray-800">{{ $fond->limit_order ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <button onclick="openInvestModal({{ $fond->id }})"
                                    data-min="{{ $fond->min_amount }}"
                                    data-name="{{ htmlspecialchars($fond->name, ENT_QUOTES) }}"
                                    data-rate="{{ $fond->rate }}"
                                    data-period="{{ $fond->period_days }}"
                                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700 transition">
                                Investir
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Info block -->
            <div class="max-w-3xl mx-auto mt-6 bg-yellow-50 border border-yellow-200 rounded-lg shadow p-4 text-sm text-gray-700">
                <h4 class="font-bold text-yellow-700 mb-2">ℹ️ Informations importantes</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Les fonds sont bloqués pendant la période choisie. Le capital et le rendement sont restitués à la fin.</li>
                    <li>Le rendement indiqué est appliqué sur le capital investi et versé à la clôture.</li>
                    <li>Respectez le montant minimum du produit pour valider l’investissement.</li>
                </ul>
            </div>
        @endif

        <!-- Mes Épargnes -->
        <div id="mes-epargnes" class="mt-8 bg-white rounded-xl shadow p-5 border border-gray-100">
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

                <div class="border border-gray-100 rounded-lg p-4 mb-4 bg-gray-50/30">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-blue-700">{{ $pres->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($pres->description, 140) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-400 uppercase font-bold">Statut</div>
                            @if($epargne->is_closed)
                                <div class="text-sm font-bold text-green-600">Terminé ✅</div>
                            @else
                                <div class="text-sm font-bold text-yellow-600">En cours ⏳</div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm text-gray-700">
                        <div>
                            <div class="text-xs text-gray-400 uppercase font-bold">Investi</div>
                            <div class="font-bold text-gray-800">{{ fmtCurrency($amount) }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 uppercase font-bold">Revenu attendu</div>
                            <div class="font-bold text-emerald-600">{{ fmtCurrency($revenu) }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 uppercase font-bold">Période</div>
                            <div class="font-bold text-gray-800">{{ $start->format('d/m/Y') }} → {{ $end->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="h-2 bg-green-500" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">Progression : {{ $progress }}% • Jours passés : {{ $daysPassed }} / {{ $totalDays }}</div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-10">Vous n’avez encore effectué aucune épargne.</p>
            @endforelse
        </div>
    </div>

    <!-- Modal Investissement -->
    <div id="investModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 sm:p-8 animate__animated animate__zoomIn">
            <div class="flex items-center justify-between mb-6">
                <h4 id="modalTitle" class="text-xl font-bold text-gray-800">Investir</h4>
                <button onclick="closeInvestModal()" class="text-gray-400 hover:text-gray-800 text-2xl">✕</button>
            </div>

            <form id="investForm" method="POST" action="#" class="space-y-6">
                @csrf
                <input type="hidden" name="fond_id" id="modalFondId" value="">
                
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="text-xs text-gray-400 uppercase font-bold">Produit sélectionné</div>
                    <div id="modalFondName" class="font-bold text-blue-700 text-lg mt-1"></div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Montant à investir ({{ $currency }})</label>
                    <div class="relative">
                        <input id="modalAmount" name="amount" type="number" step="1" min="0" required
                               class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 transition font-bold text-lg"
                               placeholder="Ex: 50 000">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">{{ $currency }}</span>
                    </div>
                    <p id="modalAmountHelp" class="text-xs text-gray-500 mt-2 font-medium"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-100 text-center">
                        <div class="text-[10px] text-emerald-600 uppercase font-bold">Taux</div>
                        <div id="modalRate" class="font-bold text-emerald-700 text-lg"></div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 text-center">
                        <div class="text-[10px] text-blue-600 uppercase font-bold">Durée</div>
                        <div id="modalPeriod" class="font-bold text-blue-700 text-lg"></div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-100">
                        Confirmer l'investissement
                    </button>
                    <button type="button" onclick="closeInvestModal()" class="w-full py-3 text-gray-500 font-bold hover:bg-gray-50 rounded-xl transition">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const CURRENCY = "{{ $currency }}";

        function openInvestModal(id) {
            const btn = document.querySelector(`[onclick="openInvestModal(${id})"]`);
            const min = parseFloat(btn.getAttribute('data-min') || 0);
            const name = btn.getAttribute('data-name') || '';
            const rate = btn.getAttribute('data-rate') || '';
            const period = btn.getAttribute('data-period') || '';

            document.getElementById('modalFondId').value = id;
            document.getElementById('modalFondName').textContent = name;
            document.getElementById('modalRate').textContent = rate + ' %';
            document.getElementById('modalPeriod').textContent = period + ' jours';
            
            const amountInput = document.getElementById('modalAmount');
            amountInput.value = min > 0 ? min : '';
            amountInput.min = min;
            document.getElementById('modalAmountHelp').textContent = min > 0 ? 'Montant minimum : ' + Number(min).toLocaleString('fr-FR') + ' ' + CURRENCY : '';

            const form = document.getElementById('investForm');
            form.action = "{{ url('/fond-preservation') }}/" + id + "/epagner";
            
            const modal = document.getElementById('investModal');
            modal.classList.remove('hidden'); 
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeInvestModal() {
            const modal = document.getElementById('investModal');
            modal.classList.add('hidden'); 
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeInvestModal();
        });
    </script>

</x-layouts>
