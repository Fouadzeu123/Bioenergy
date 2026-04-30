<x-layouts :title="'Mes Actifs'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Messages de succès / erreur -->
    @if(session('success'))
        <div class="rounded-2xl p-4 flex items-center gap-3 animate__animated animate__fadeInDown" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.2);">
                <i class="fas fa-check-circle text-emerald-400 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-emerald-400">Succès !</p>
                <p class="text-[11px] font-medium mt-0.5" style="color: rgba(167,243,208,0.8);">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl p-4 flex items-center gap-3 animate__animated animate__shakeX" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(239,68,68,0.2);">
                <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-red-400">Oups !</p>
                <p class="text-[11px] font-medium mt-0.5" style="color: rgba(254,202,202,0.8);">{{ session('error') }}</p>
            </div>
        </div>
    @endif
    <!-- Header & Timer Countdown -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white text-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #1e3a8a 100%); box-shadow: 0 0 50px rgba(99,102,241,0.3);">
        <div class="relative z-10 space-y-3">
            <p class="text-[11px] font-semibold" style="color: rgba(199,210,254,0.8);">Prochain Revenu Passif</p>
            <div id="countdown" class="flex justify-center gap-5"></div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
        <div class="absolute -left-10 -top-10 w-32 h-32 rounded-full" style="background: rgba(59,130,246,0.1); filter: blur(24px);"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl p-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[11px] font-semibold mb-1" style="color: #4b5563;">Gains Journaliers</p>
            <p class="text-xl font-bold text-cyan-400">{{ fmtCurrency($revenusJournee) }}</p>
        </div>
        <div class="rounded-2xl p-5 text-right" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[11px] font-semibold mb-1" style="color: #4b5563;">Actifs Détenus</p>
            <p class="text-xl font-bold text-white">{{ $orders->count() }} <span class="text-[10px] font-medium" style="color: #374151;">unités</span></p>
        </div>
    </div>

    <!-- Active Investments List -->
    <div class="space-y-4">
        @if($claimableAmount > 0)
        <div class="rounded-2xl p-5 flex items-center justify-between" style="background: linear-gradient(135deg, rgba(16,185,129,0.1) 0%, rgba(6,182,212,0.1) 100%); border: 1px solid rgba(16,185,129,0.2);">
            <div>
                <p class="text-[11px] font-semibold text-gray-400 mb-1">Gains Disponibles</p>
                <p class="text-2xl font-bold text-emerald-400">{{ fmtCurrency($claimableAmount) }}</p>
            </div>
            <form method="POST" action="{{ route('produits.claim') }}">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-xl text-[12px] font-bold text-white transition-all active:scale-95" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
                    <i class="fas fa-hand-holding-dollar mr-1"></i> Réclamer
                </button>
            </form>
        </div>
        @endif

        <h3 class="text-[12px] font-semibold px-1" style="color: #4b5563;">Portefeuille Actif</h3>

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
                
                $today = \Carbon\Carbon::today()->startOfDay();
                $validGainDay = !$today->isSunday() && 
                                $today->isAfter($start->startOfDay()) &&
                                ($order->last_gain_at === null || \Carbon\Carbon::parse($order->last_gain_at)->startOfDay()->lt($today));
            @endphp

            <div class="rounded-2xl p-5 space-y-5 cursor-pointer hover:border-blue-500/20 transition-all" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);" onclick="openDetails({{ $order->id }})">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                            <i class="fas fa-microchip text-blue-400 text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">{{ $p->name }}</h4>
                            <p class="text-[11px] font-semibold text-cyan-400">+{{ fmtCurrency($order->day_income) }} <span style="color: #374151;">/ jour</span></p>
                        </div>
                    </div>
                    <div class="text-right space-y-1.5">
                        <span class="text-[11px] font-bold text-gray-400">{{ $progress }}%</span>
                        <div class="w-16 h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-1000" style="width: {{ $progress }}%; background: linear-gradient(90deg, #2563eb, #06b6d4);"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <div>
                        <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Profit Cumulé</p>
                        <p class="text-sm font-bold text-cyan-400">{{ fmtCurrency($earned) }}</p>
                    </div>
                    <div>
                        @if($validGainDay)
                            <form method="POST" action="{{ route('produits.claim') }}" onclick="event.stopPropagation();">
                                @csrf
                                <button type="submit" class="px-5 py-2 rounded-xl text-[11px] font-bold text-white transition-all active:scale-95 shadow-lg" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
                                    Réclamer
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="px-5 py-2 rounded-xl text-[11px] font-bold text-gray-500 cursor-not-allowed" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);" onclick="event.stopPropagation();">
                                <i class="fas fa-clock mr-1 text-[10px]"></i> En attente
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 rounded-2xl border border-dashed" style="border-color: rgba(255,255,255,0.08);">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: rgba(59,130,246,0.08);">
                    <i class="fas fa-leaf text-blue-500 text-2xl"></i>
                </div>
                <p class="text-[12px] font-semibold mb-4" style="color: #374151;">Aucun investissement actif</p>
                <a href="{{ route('products') }}" class="inline-block text-[11px] font-bold text-white px-6 py-2.5 rounded-xl transition" style="background: linear-gradient(135deg, #2563eb, #0891b2);">Explorer</a>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Détails -->
<div id="detailsModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center backdrop-blur-sm p-4" style="background: rgba(0,0,0,0.75);">
    <div class="rounded-3xl shadow-2xl max-w-lg w-full p-6 space-y-6 animate__animated animate__slideInUp" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
        <div class="flex justify-between items-center">
            <div>
                <h4 id="modalTitle" class="text-xl font-bold text-white">Détails de l'actif</h4>
                <p class="text-[11px] font-semibold mt-0.5" style="color: #4b5563;">Informations techniques</p>
            </div>
            <button onclick="closeDetails()" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-500 hover:text-white transition" style="background: rgba(255,255,255,0.05);">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div id="modalContent" class="space-y-5"></div>

        <button onclick="closeDetails()" class="w-full py-4 text-[12px] font-bold text-white rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2);">
            Fermer
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
                <span class="text-[10px] font-semibold mt-1" style="color: rgba(147,197,253,0.6);">h</span>
            </div>
            <div class="text-2xl font-bold pt-1" style="color: rgba(255,255,255,0.15);">:</div>
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold tracking-tight">${m}</span>
                <span class="text-[10px] font-semibold mt-1" style="color: rgba(147,197,253,0.6);">min</span>
            </div>
            <div class="text-2xl font-bold pt-1" style="color: rgba(255,255,255,0.15);">:</div>
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold tracking-tight">${s}</span>
                <span class="text-[10px] font-semibold mt-1" style="color: rgba(147,197,253,0.6);">sec</span>
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
            <div class="rounded-xl p-4" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
                <p class="text-[11px] font-semibold text-blue-400 mb-2">Vision du Projet</p>
                <p class="text-[12px] font-medium leading-relaxed" style="color: #6b7280;">"${cleanDescription}"</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Montant Engagé</p>
                    <p class="text-sm font-bold text-white">${Number(data.invested).toLocaleString('fr-FR')} ${CURRENCY}</p>
                </div>
                <div class="rounded-xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Rendement / j</p>
                    <p class="text-sm font-bold text-cyan-400">${Number(data.dayIncome).toLocaleString('fr-FR')} ${CURRENCY}</p>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center px-1">
                    <p class="text-[11px] font-semibold" style="color: #4b5563;">Cycle Opérationnel</p>
                    <p class="text-[11px] font-bold text-gray-300">${data.start} → ${data.end}</p>
                </div>
                <div class="h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                    <div class="h-full rounded-full transition-all" style="width: ${data.progress}%; background: linear-gradient(90deg, #2563eb, #06b6d4);"></div>
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