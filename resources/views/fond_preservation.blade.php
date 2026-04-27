<x-layouts :title="'Fonds de Préservation'">
@php $currency = Auth::user()->currency; @endphp
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8">

    <!-- Hero Fonds Sleeker -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-8 text-white shadow-xl">
        <div class="relative z-10">
            <h1 class="text-xl font-bold">Fonds de Préservation</h1>
            <p class="text-[10px] font-semibold text-gray-400 mt-1">Sécurité & Rendement Stable</p>
            
            <div class="mt-8 flex gap-3">
                <a href="{{ route('mes.epargnes') }}" class="bg-emerald-600 text-white text-[10px] font-bold px-4 py-3 rounded-xl shadow-lg active:scale-95 transition">
                    Mes Épargnes
                </a>
                <a href="{{ route('fond.index') }}" class="bg-white/10 border border-white/10 text-white text-[10px] font-bold px-4 py-3 rounded-xl active:scale-95 transition">
                    Explorer
                </a>
            </div>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Liste des Fonds Sleeker -->
    <div class="space-y-6">
        <h3 class="text-[10px] font-bold text-gray-400 px-2">Produits Disponibles</h3>
        @foreach($fonds as $fond)
            <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 space-y-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">{{ $fond->name }}</h4>
                        <p class="text-[10px] text-gray-400 font-medium mt-1 leading-relaxed">{{ Str::limit($fond->description, 80) }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-bold text-emerald-600">{{ $fond->rate }}%</span>
                        <p class="text-[9px] font-bold text-gray-300">{{ $fond->period_days }} jours</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-[9px] font-bold text-gray-400 mb-1">Montant Min</p>
                        <p class="text-xs font-bold text-gray-800">{{ fmtCurrency($fond->min_amount) }}</p>
                    </div>
                    <button onclick="openInvestModal({{ $fond->id }})"
                            data-min="{{ $fond->min_amount }}"
                            data-name="{{ htmlspecialchars($fond->name, ENT_QUOTES) }}"
                            data-rate="{{ $fond->rate }}"
                            data-period="{{ $fond->period_days }}"
                            class="bg-slate-900 text-white rounded-2xl text-[10px] font-bold active:scale-95 transition">
                        Investir
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info Section Sleeker -->
    <div class="bg-emerald-50 rounded-[32px] p-8 border border-emerald-100">
        <h4 class="text-[10px] font-bold text-emerald-700 mb-4">À savoir</h4>
        <ul class="space-y-4">
            <li class="flex gap-3">
                <i class="fas fa-lock text-emerald-500 mt-0.5 text-xs"></i>
                <p class="text-[10px] font-medium text-emerald-800/70 leading-relaxed">Fonds bloqués pendant la durée sélectionnée</p>
            </li>
            <li class="flex gap-3">
                <i class="fas fa-chart-line text-emerald-500 mt-0.5 text-xs"></i>
                <p class="text-[10px] font-medium text-emerald-800/70 leading-relaxed">Rendement fixe versé à la fin du cycle</p>
            </li>
        </ul>
    </div>
</div>

<!-- Modal Invest Sleeker -->
<div id="investModal" class="fixed inset-0 z-[100] hidden flex items-end sm:items-center justify-center bg-slate-900/80 backdrop-blur-sm p-0 sm:p-4">
    <div class="bg-white rounded-t-[40px] sm:rounded-[40px] shadow-2xl max-w-lg w-full p-8 space-y-8 animate__animated animate__slideInUp">
        <div class="flex justify-between items-center">
            <h4 class="text-xl font-bold text-gray-800">Placer des fonds</h4>
            <button onclick="closeInvestModal()" class="text-gray-400 text-2xl hover:text-gray-800">×</button>
        </div>

        <form id="investForm" method="POST" action="#" class="space-y-6">
            @csrf
            <input type="hidden" name="fond_id" id="modalFondId" value="">
            
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <p id="modalFondName" class="text-xs font-bold text-gray-800"></p>
                <div class="flex gap-4 mt-4">
                    <div class="text-left">
                        <p class="text-[9px] font-bold text-gray-400">Taux</p>
                        <p id="modalRate" class="text-sm font-bold text-emerald-600"></p>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-bold text-gray-400">Cycle</p>
                        <p id="modalPeriod" class="text-sm font-bold text-gray-800"></p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 mb-3">Montant du placement</label>
                <div class="relative">
                    <input id="modalAmount" name="amount" type="number" step="1" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-xl font-bold text-gray-800 focus:bg-white focus:border-emerald-500 transition outline-none"
                           placeholder="0">
                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-300">{{ $currency }}</span>
                </div>
                <p id="modalAmountHelp" class="text-[9px] font-bold text-gray-400 mt-3"></p>
            </div>

            <button type="submit" class="w-full bg-emerald-600 text-white font-bold text-[11px] py-5 rounded-2xl shadow-lg shadow-emerald-100 active:scale-95 transition">
                Valider mon épargne
            </button>
        </form>
    </div>
</div>

<script>
    function openInvestModal(id) {
        const btn = document.querySelector(`[onclick="openInvestModal(${id})"]`);
        const min = parseFloat(btn.getAttribute('data-min') || 0);
        const name = btn.getAttribute('data-name') || '';
        const rate = btn.getAttribute('data-rate') || '';
        const period = btn.getAttribute('data-period') || '';

        document.getElementById('modalFondId').value = id;
        document.getElementById('modalFondName').textContent = name;
        document.getElementById('modalRate').textContent = rate + '%';
        document.getElementById('modalPeriod').textContent = period + ' jours';
        
        const amountInput = document.getElementById('modalAmount');
        amountInput.value = min;
        amountInput.min = min;
        document.getElementById('modalAmountHelp').textContent = 'Minimum requis: ' + Number(min).toLocaleString('fr-FR') + ' {{ $currency }}';

        const form = document.getElementById('investForm');
        form.action = "{{ url('/fond-preservation') }}/" + id + "/epagner";
        
        document.getElementById('investModal').classList.remove('hidden'); 
        document.body.style.overflow = 'hidden';
    }

    function closeInvestModal() {
        document.getElementById('investModal').classList.add('hidden'); 
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeInvestModal();
    });
</script>
</x-layouts>
