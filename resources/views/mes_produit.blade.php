<x-layouts :title="'Mes Actifs'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-20">

    <!-- Header & Timer Sleeker -->
    <div class="bg-slate-900 rounded-[40px] p-10 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10 text-center space-y-6">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Prochain Revenu Passif</p>
            <div id="countdown" class="flex justify-center gap-6">
                <!-- JS Inject here -->
            </div>
        </div>
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Quick Stats Sleeker -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 space-y-1">
            <p class="text-[10px] font-bold text-gray-400">Gains Journaliers</p>
            <p class="text-xl font-bold text-emerald-600">{{ fmtCurrency($revenusJournee) }}</p>
        </div>
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 text-right space-y-1">
            <p class="text-[10px] font-bold text-gray-400">Actifs Détenus</p>
            <p class="text-xl font-bold text-slate-900">{{ $orders->count() }} <span class="text-[10px] font-medium text-gray-300">unités</span></p>
        </div>
    </div>

    <!-- Active Investments List -->
    <div class="space-y-6">
        <h3 class="text-[11px] font-bold text-gray-400 px-4">Portefeuille Actif</h3>
        
        @forelse($orders as $order)
            @php
                $p = $order->produit;
                $start = \Carbon\Carbon::parse($order->start_date);
                $end = \Carbon\Carbon::parse($order->end_date);
                $now = \Carbon\Carbon::now();
                $totalDays = $start->diffInDays($end);
                $daysPassed = $start->diffInDays($now);
                $progress = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100)) : 100;
                $earned = \App\Models\Transaction::where('user_id', Auth::id())->where('type', 'gain_journalier')->where('order_id', $order->id)->sum('montant');
            @endphp

            <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-50 space-y-8 active:scale-[0.98] transition-transform group" onclick="openDetails({{ $order->id }})">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center border border-gray-100 group-hover:bg-emerald-50 transition">
                            <i class="fas fa-microchip text-emerald-600 text-xs"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-gray-800 tracking-tight">{{ $p->name }}</h4>
                            <p class="text-[10px] font-bold text-emerald-600">+{{ fmtCurrency($order->day_income) }} <span class="text-gray-300 font-medium">/ jour</span></p>
                        </div>
                    </div>
                    <div class="text-right space-y-1">
                        <span class="text-[10px] font-bold text-slate-900">{{ $progress }}%</span>
                        <div class="w-12 h-1.5 bg-gray-50 rounded-full overflow-hidden border border-gray-100">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-4 space-y-1 border border-gray-100/50">
                        <p class="text-[9px] font-bold text-gray-400">Profit Cumulé</p>
                        <p class="text-xs font-bold text-emerald-600">{{ fmtCurrency($earned) }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 text-right space-y-1 border border-gray-100/50">
                        <p class="text-[9px] font-bold text-gray-400">Échéance</p>
                        <p class="text-xs font-bold text-slate-900">{{ $end->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-gray-50/50 rounded-[48px] border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i class="fas fa-leaf text-gray-200"></i>
                </div>
                <p class="text-[11px] font-bold text-gray-300">Aucun investissement actif</p>
                <a href="{{ route('products') }}" class="inline-block mt-6 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-6 py-2.5 rounded-full transition active:scale-95">Explorer</a>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Détails Sleeker -->
<div id="detailsModal" class="fixed inset-0 z-[120] hidden flex items-end sm:items-center justify-center bg-slate-900/80 backdrop-blur-sm p-0 sm:p-4">
    <div class="bg-white rounded-t-[48px] sm:rounded-[48px] shadow-2xl max-w-lg w-full p-10 space-y-10 animate__animated animate__slideInUp">
        <div class="flex justify-between items-center">
            <div class="space-y-1">
                <h4 id="modalTitle" class="text-xl font-bold text-gray-800">Détails de l'actif</h4>
                <p class="text-[10px] font-bold text-gray-400">Informations techniques</p>
            </div>
            <button onclick="closeDetails()" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-gray-800 transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <div id="modalContent" class="space-y-8">
            <!-- Dynamic Content -->
        </div>
        
        <button onclick="closeDetails()" class="w-full py-5 bg-slate-900 text-white text-[11px] font-bold rounded-2xl shadow-xl active:scale-95 transition">
            Fermer les détails
        </button>
    </div>
</div>

<script>
    const countdownElement = document.getElementById('countdown');
    const ordersData = @json($ordersForJs);
    const CURRENCY = "{{ Auth::user()->currency }}";

    function updateCountdown() {
        const now = new Date();
        const midnight = new Date(now);
        midnight.setHours(24, 0, 0, 0);
        const diff = midnight - now;

        const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
        const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
        const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');

        countdownElement.innerHTML = `
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold tracking-tight">${h}</span>
                <span class="text-[9px] font-bold text-gray-500 mt-1">HRS</span>
            </div>
            <div class="text-2xl font-bold opacity-10 pt-1">:</div>
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold tracking-tight">${m}</span>
                <span class="text-[9px] font-bold text-gray-500 mt-1">MIN</span>
            </div>
            <div class="text-2xl font-bold opacity-10 pt-1">:</div>
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold tracking-tight">${s}</span>
                <span class="text-[9px] font-bold text-gray-500 mt-1">SEC</span>
            </div>
        `;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    function openDetails(orderId) {
        const data = ordersData.find(o => o.id === orderId);
        if (!data) return;

        const cleanDescription = data.description.replace(/\$/g, '');

        document.getElementById('modalTitle').textContent = data.produitName;
        document.getElementById('modalContent').innerHTML = `
            <div class="bg-emerald-50/30 rounded-3xl p-6 border border-emerald-100/50">
                <p class="text-[10px] font-bold text-emerald-700/50 mb-3">Vision du Projet</p>
                <p class="text-xs font-medium text-gray-600 leading-relaxed italic">"${cleanDescription}"</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-2xl p-4 space-y-1">
                    <p class="text-[9px] font-bold text-gray-400">Montant Engagé</p>
                    <p class="text-sm font-bold text-slate-900">${Number(data.invested).toLocaleString('fr-FR')} ${CURRENCY}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 text-right space-y-1">
                    <p class="text-[9px] font-bold text-gray-400">Rendement / j</p>
                    <p class="text-sm font-bold text-emerald-600">${Number(data.dayIncome).toLocaleString('fr-FR')} ${CURRENCY}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-end px-2">
                    <p class="text-[10px] font-bold text-gray-400">Cycle Opérationnel</p>
                    <p class="text-[10px] font-bold text-slate-900">${data.start} → ${data.end}</p>
                </div>
                <div class="w-full bg-gray-50 h-1.5 rounded-full overflow-hidden border border-gray-100">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: ${data.progress}%"></div>
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
</script>
</x-layouts>