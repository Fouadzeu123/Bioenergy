<x-layouts :title="'Dépôt'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $balance = $user->account_balance ?? 0;
    $phone = $user->phone ?? '';
    $userCountry = ($user->country_code === '225') ? 'CI' : 'CM';
    $phonePrefix = ($userCountry === 'CI') ? '225' : '237';
    $countryName  = ($userCountry === 'CI') ? "Côte d'Ivoire" : 'Cameroun';
    $countryFlag  = ($userCountry === 'CI') ? '🇨🇮' : '🇨🇲';
    $minDepot = 1000;
    $currency = $user->currency;

    $detectedOperator = 'UNKNOWN';
    if ($phone) {
        $phoneStr = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phoneStr, '237')) $phoneStr = substr($phoneStr, 3);
        if (str_starts_with($phoneStr, '225')) $phoneStr = substr($phoneStr, 3);
        if ($userCountry === 'CI') {
            $prefix2 = substr($phoneStr, 0, 2);
            if (in_array($prefix2, ['05','25','45','65','85'])) $detectedOperator = 'MTN';
            elseif (in_array($prefix2, ['07','27','47','67','87'])) $detectedOperator = 'ORANGE';
            elseif (in_array($prefix2, ['01','21','41','61','81'])) $detectedOperator = 'MOOV';
        } else {
            if (strlen($phoneStr) >= 9) {
                $prefix3 = substr($phoneStr, 0, 3);
                $mtnCM    = ['650','651','652','653','654','670','671','672','673','674','675','676','677','678','679','680','681','682','683'];
                $orangeCM = ['640','641','642','643','644','645','646','647','648','655','656','657','658','659','690','691','692','693','694','695','696','697','698','699'];
                if (in_array($prefix3, $mtnCM)) $detectedOperator = 'MTN';
                elseif (in_array($prefix3, $orangeCM)) $detectedOperator = 'ORANGE';
            }
        }
    }
    $depots = $depots ?? collect();
@endphp

<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Card Solde -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10 flex justify-between items-end">
            <div class="space-y-1">
                <p class="text-[11px] font-medium" style="color: rgba(147,197,253,0.8);">Liquidités disponibles</p>
                <h2 class="text-4xl font-bold tracking-tight">{{ fmtCurrency($balance) }}</h2>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                <i class="fas fa-wallet text-blue-200 text-lg"></i>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Formulaire Dépôt -->
    <form id="depositForm" action="{{ route('depot.preview') }}" method="POST" class="space-y-5">
        @csrf

        <div class="rounded-2xl p-6 space-y-6" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <!-- Passerelle -->
            <div class="space-y-3 text-center">
                <p class="text-[11px] font-semibold" style="color: #4b5563;">Passerelle de Paiement</p>
                <div class="grid grid-cols-2 gap-3">
                    <div id="gw1" class="gw-card p-4 rounded-2xl cursor-pointer transition-all" style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.4); box-shadow: 0 0 15px rgba(59,130,246,0.2);" onclick="selectGw(1)">
                        <div class="flex items-center justify-center gap-3">
                            <div class="gw-icon-bg w-10 h-10 rounded-xl flex items-center justify-center transition-all" style="background: rgba(59,130,246,0.2);">
                                <i class="gw-icon fas fa-mobile-screen transition-all text-blue-400"></i>
                            </div>
                            <div class="text-left">
                                <p class="gw-text text-[12px] font-bold transition-all text-white">Fast Pay 01</p>
                            </div>
                        </div>
                    </div>
                    <div id="gw2" class="gw-card p-4 rounded-2xl cursor-pointer transition-all" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); box-shadow: none;" onclick="selectGw(2)">
                        <div class="flex items-center justify-center gap-3">
                            <div class="gw-icon-bg w-10 h-10 rounded-xl flex items-center justify-center transition-all" style="background: rgba(255,255,255,0.05);">
                                <i class="gw-icon fas fa-mobile-screen transition-all text-gray-500"></i>
                            </div>
                            <div class="text-left">
                                <p class="gw-text text-[12px] font-bold transition-all text-gray-500">Fast Pay 02</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Montant -->
            <div class="space-y-3">
                <label class="block text-[11px] font-semibold text-center" style="color: #4b5563;">Montant de l'approvisionnement</label>
                <div class="relative">
                    <input type="number" name="amount" id="amount" step="1" min="{{ $minDepot }}" required
                           class="w-full rounded-2xl px-6 py-5 text-3xl font-bold text-center text-white outline-none transition"
                           style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);"
                           placeholder="0">
                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-sm font-semibold" style="color: #374151;">{{ $currency }}</span>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    @foreach([5000, 10000, 50000] as $amt)
                        <button type="button" onclick="setAmount({{ $amt }})" class="py-3 rounded-xl text-[11px] font-semibold transition active:scale-95" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: #9ca3af;">
                            {{ number_format($amt, 0, '.', ' ') }}
                        </button>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="w-full py-5 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition mt-4" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 24px rgba(59,130,246,0.3);">
                Suivant <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </form>

    <!-- Historique Mini -->
    @if($depots->count() > 0)
    <div class="space-y-3">
        <h3 class="text-[11px] font-semibold px-1" style="color: #4b5563;">Dernières opérations</h3>
        <div class="space-y-3">
            @foreach($depots->take(3) as $depot)
                <div class="rounded-2xl p-4 flex items-center justify-between" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                            <i class="fas fa-arrow-down text-blue-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">{{ fmtCurrency($depot->montant) }}</p>
                            <p class="text-[10px] font-medium" style="color: #4b5563;">{{ $depot->created_at->format('d M, H:i') }}</p>
                        </div>
                    </div>
                    @php
                        $sc = 'background: rgba(107,114,128,0.15); color: #9ca3af;';
                        if($depot->status === 'completed') $sc = 'background: rgba(6,182,212,0.15); color: #22d3ee;';
                        elseif(in_array($depot->status, ['failed','canceled','rejected'])) $sc = 'background: rgba(239,68,68,0.15); color: #f87171;';
                    @endphp
                    <span class="text-[9px] font-bold px-3 py-1 rounded-full" style="{{ $sc }}">
                        {{ $depot->status }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    function setAmount(amt) {
        document.getElementById('amount').value = amt;
    }

    function selectGw(id) {
        // Reset all
        document.querySelectorAll('.gw-card').forEach(el => {
            el.style.background = 'rgba(255,255,255,0.03)';
            el.style.borderColor = 'rgba(255,255,255,0.06)';
            el.style.boxShadow = 'none';
            
            let iconBg = el.querySelector('.gw-icon-bg');
            iconBg.style.background = 'rgba(255,255,255,0.05)';
            
            let icon = el.querySelector('.gw-icon');
            icon.classList.remove('text-blue-400');
            icon.classList.add('text-gray-500');

            let text = el.querySelector('.gw-text');
            text.classList.remove('text-white');
            text.classList.add('text-gray-500');
        });

        // Highlight selected
        const selected = document.getElementById('gw' + id);
        selected.style.background = 'rgba(59,130,246,0.15)';
        selected.style.borderColor = 'rgba(59,130,246,0.4)';
        selected.style.boxShadow = '0 0 15px rgba(59,130,246,0.2)';

        let selIconBg = selected.querySelector('.gw-icon-bg');
        selIconBg.style.background = 'rgba(59,130,246,0.2)';

        let selIcon = selected.querySelector('.gw-icon');
        selIcon.classList.remove('text-gray-500');
        selIcon.classList.add('text-blue-400');

        let selText = selected.querySelector('.gw-text');
        selText.classList.remove('text-gray-500');
        selText.classList.add('text-white');
    }
</script>
</x-layouts>
