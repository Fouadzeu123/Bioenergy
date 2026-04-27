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

<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-20">

    <!-- Card Solde Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl">
        <div class="relative z-10 flex justify-between items-end">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-gray-400">Liquidités disponibles</p>
                <h2 class="text-4xl font-bold tracking-tight">{{ fmtCurrency($balance) }}</h2>
            </div>
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/5">
                <i class="fas fa-wallet text-emerald-400"></i>
            </div>
        </div>
        <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Formulaire Dépôt Sleeker -->
    <form id="depositForm" action="{{ route('depot.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-50 space-y-8">
            <div class="space-y-4 text-center">
                <h3 class="text-xs font-bold text-gray-400">Passerelle de Paiement</h3>
                <div class="p-6 rounded-[24px] border-2 border-emerald-500 bg-emerald-50/30">
                    <div class="flex items-center justify-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-mobile-screen text-emerald-600"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[11px] font-bold text-gray-800">NotchPay Gateway</p>
                            <p class="text-[9px] font-medium text-gray-400">{{ $countryFlag }} Momo, Orange & Moov</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-[11px] font-bold text-gray-400 text-center">Montant de l'approvisionnement</label>
                <div class="relative">
                    <input type="number" name="amount" id="amount" step="1" min="{{ $minDepot }}" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-6 text-3xl font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none tracking-tight"
                           placeholder="0">
                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 font-black text-sm italic">{{ $currency }}</span>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    @foreach([5000, 10000, 50000] as $amt)
                        <button type="button" onclick="setAmount({{ $amt }})" class="py-3 rounded-xl bg-gray-50 text-[10px] font-black text-gray-400 hover:bg-slate-900 hover:text-white transition uppercase tracking-wider border border-gray-100">
                            {{ number_format($amt, 0, '.', ' ') }}
                        </button>
                    @endforeach
                </div>
            </div>

            @if($detectedOperator !== 'UNKNOWN')
                <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-xs text-gray-400">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Numéro détecté</p>
                            <p class="text-[10px] font-black text-gray-800">+{{ $phonePrefix }} {{ chunk_split(ltrim(preg_replace('/\D/','',$phone), '0237225'), 2, ' ') }}</p>
                        </div>
                    </div>
                    <span class="text-[9px] font-black uppercase text-emerald-600 px-2 py-1 bg-emerald-50 rounded-md">{{ $detectedOperator }}</span>
                    <input type="hidden" name="payment_method" value="{{ $detectedOperator }}">
                </div>
            @endif

            <button type="submit" class="w-full py-6 bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl active:scale-95 transition">
                Confirmer le dépôt
            </button>
        </div>
    </form>

    <!-- Historique Mini -->
    @if($depots->count() > 0)
    <div class="space-y-4">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2 italic">Dernières opérations</h3>
        <div class="space-y-3">
            @foreach($depots->take(3) as $depot)
                <div class="bg-white rounded-[24px] p-5 border border-gray-50 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-arrow-down text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-800 italic">{{ fmtCurrency($depot->montant) }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $depot->created_at->format('d M, H:i') }}</p>
                        </div>
                    </div>
                    @php
                        $statusClass = 'bg-gray-100 text-gray-400';
                        if($depot->status === 'completed') $statusClass = 'bg-emerald-50 text-emerald-600';
                        elseif(in_array($depot->status, ['failed', 'canceled', 'rejected'])) $statusClass = 'bg-red-50 text-red-600';
                    @endphp
                    <span class="text-[8px] font-black uppercase px-3 py-1.5 rounded-full {{ $statusClass }}">
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
</script>
</x-layouts>
