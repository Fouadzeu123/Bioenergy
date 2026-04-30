<x-layouts :title="'Fonds de Préservation'">
@php $currency = Auth::user()->currency; @endphp
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Hero -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <h1 class="text-2xl font-bold">Fonds de Préservation</h1>
            <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Sécurité & Rendement Stable</p>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('mes.epargnes') }}" class="text-white text-[11px] font-bold px-5 py-3 rounded-xl active:scale-95 transition" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
                    Mes Épargnes
                </a>
                <a href="{{ route('fond.index') }}" class="text-white text-[11px] font-bold px-5 py-3 rounded-xl active:scale-95 transition" style="background: rgba(59,130,246,0.3); border: 1px solid rgba(59,130,246,0.4);">
                    Explorer
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Liste des Fonds -->
    <div class="space-y-4">
        <h3 class="text-[12px] font-semibold px-1" style="color: #4b5563;">Produits Disponibles</h3>

        @foreach($fonds as $fond)
            <div class="rounded-2xl p-5 space-y-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <div class="flex justify-between items-start">
                    <div class="flex-1 pr-4">
                        <h4 class="text-sm font-bold text-white">{{ $fond->name }}</h4>
                        <p class="text-[11px] font-medium mt-1 leading-relaxed" style="color: #6b7280;">{{ Str::limit($fond->description, 80) }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-2xl font-bold text-cyan-400">{{ $fond->rate }}%</span>
                        <p class="text-[10px] font-semibold mt-0.5" style="color: #4b5563;">{{ $fond->period_days }} jours</p>
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-xl p-4" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.12);">
                    <div>
                        <p class="text-[10px] font-semibold mb-0.5" style="color: #4b5563;">Montant Min</p>
                        <p class="text-sm font-bold text-white">{{ fmtCurrency($fond->min_amount) }}</p>
                    </div>
                    <button onclick="openInvestModal({{ $fond->id }})"
                            data-min="{{ $fond->min_amount }}"
                            data-name="{{ htmlspecialchars($fond->name, ENT_QUOTES) }}"
                            data-rate="{{ $fond->rate }}"
                            data-period="{{ $fond->period_days }}"
                            class="text-white text-[11px] font-bold px-5 py-2.5 rounded-xl active:scale-95 transition"
                            style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 16px rgba(59,130,246,0.25);">
                        Investir
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info Section -->
    <div class="rounded-2xl p-5 space-y-4" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
        <h4 class="text-[11px] font-bold text-blue-400">À savoir</h4>
        <ul class="space-y-3">
            <li class="flex gap-3 items-start">
                <i class="fas fa-lock text-blue-400 mt-0.5 text-xs flex-shrink-0"></i>
                <p class="text-[11px] font-medium leading-relaxed" style="color: #6b7280;">Fonds bloqués pendant la durée sélectionnée</p>
            </li>
            <li class="flex gap-3 items-start">
                <i class="fas fa-chart-line text-cyan-400 mt-0.5 text-xs flex-shrink-0"></i>
                <p class="text-[11px] font-medium leading-relaxed" style="color: #6b7280;">Rendement fixe versé à la fin du cycle</p>
            </li>
        </ul>
    </div>
</div>

<!-- Modal Investissement -->
<div id="investModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center backdrop-blur-sm p-4" style="background: rgba(0,0,0,0.75);">
    <div class="rounded-3xl shadow-2xl max-w-lg w-full p-7 space-y-6 animate__animated animate__slideInUp" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
        <div class="flex justify-between items-center">
            <h4 class="text-xl font-bold text-white">Placer des fonds</h4>
            <button onclick="closeInvestModal()" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-500 hover:text-white transition" style="background: rgba(255,255,255,0.05);">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="investForm" method="POST" action="#" class="space-y-5">
            @csrf
            <input type="hidden" name="fond_id" id="modalFondId" value="">

            <div class="rounded-2xl p-5 space-y-3" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
                <p id="modalFondName" class="text-sm font-bold text-white"></p>
                <div class="flex gap-6">
                    <div>
                        <p class="text-[10px] font-semibold mb-0.5" style="color: #4b5563;">Taux</p>
                        <p id="modalRate" class="text-sm font-bold text-cyan-400"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold mb-0.5" style="color: #4b5563;">Cycle</p>
                        <p id="modalPeriod" class="text-sm font-bold text-blue-400"></p>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-semibold" style="color: #4b5563;">Montant du placement</label>
                <div class="relative">
                    <input id="modalAmount" name="amount" type="number" step="1" required
                           class="w-full rounded-2xl px-6 py-4 text-xl font-bold text-white outline-none transition text-center"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                           placeholder="0">
                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-sm font-semibold" style="color: #374151;">{{ $currency }}</span>
                </div>
                <p id="modalAmountHelp" class="text-[10px] font-semibold text-blue-400 text-center"></p>
            </div>

            <button type="submit" class="w-full py-4 text-white font-bold text-[12px] rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 24px rgba(59,130,246,0.3);">
                Valider mon épargne
            </button>
        </form>
    </div>
</div>

<script>
    function openInvestModal(id) {
        const btn = document.querySelector(`[onclick="openInvestModal(${id})"]`);
        const min = parseFloat(btn.getAttribute('data-min') || 0);
        document.getElementById('modalFondId').value = id;
        document.getElementById('modalFondName').textContent = btn.getAttribute('data-name') || '';
        document.getElementById('modalRate').textContent = btn.getAttribute('data-rate') + '%';
        document.getElementById('modalPeriod').textContent = btn.getAttribute('data-period') + ' jours';
        const amountInput = document.getElementById('modalAmount');
        amountInput.value = min;
        amountInput.min = min;
        document.getElementById('modalAmountHelp').textContent = 'Minimum requis : ' + Number(min).toLocaleString('fr-FR') + ' {{ $currency }}';
        document.getElementById('investForm').action = "{{ url('/fond-preservation') }}/" + id + "/epagner";
        document.getElementById('investModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeInvestModal() {
        document.getElementById('investModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeInvestModal(); });
</script>
</x-layouts>
