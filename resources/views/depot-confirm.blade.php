<x-layouts :title="'Confirmation du Dépôt'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $phone = $user->phone ?? '';
    $userCountry = ($user->country_code === '225') ? 'CI' : 'CM';
    $phonePrefix = ($userCountry === 'CI') ? '225' : '237';
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
@endphp

<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">
    
    <!-- Résumé du montant -->
    <div class="rounded-3xl p-6 text-center shadow-lg" style="background: linear-gradient(135deg, #1e3a8a 0%, #0e7490 100%);">
        <p class="text-[11px] font-semibold text-blue-200 mb-1 uppercase tracking-wider">Montant sélectionné</p>
        <p class="text-3xl font-bold text-white">{{ number_format($amount, 0, '.', ' ') }} <span class="text-lg">{{ $currency }}</span></p>
    </div>

    <!-- Instructions -->
    <div class="rounded-2xl p-5" style="background: rgba(59,130,246,0.05); border: 1px solid rgba(59,130,246,0.15);">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-blue-500/20 text-blue-400">
                <i class="fas fa-info-circle"></i>
            </div>
            <h3 class="text-[12px] font-bold text-white">Instructions de dépôt</h3>
        </div>
        <ul class="space-y-2 text-[11px] font-medium text-gray-400">
            <li class="flex items-start gap-2">
                <i class="fas fa-check text-blue-400 mt-0.5"></i>
                Veuillez saisir le numéro de téléphone avec lequel vous souhaitez effectuer le paiement.
            </li>
            <li class="flex items-start gap-2">
                <i class="fas fa-check text-blue-400 mt-0.5"></i>
                Sélectionnez le bon opérateur (MTN, Orange, Moov).
            </li>
            <li class="flex items-start gap-2">
                <i class="fas fa-check text-blue-400 mt-0.5"></i>
                Gardez votre téléphone à proximité pour valider le retrait depuis votre compte Mobile Money.
            </li>
        </ul>
    </div>

    <!-- Formulaire de confirmation -->
    <form action="{{ route('depot.store') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="amount" value="{{ $amount }}">

        <div class="rounded-2xl p-6 space-y-6" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            
            <div class="space-y-4">
                <!-- Numéro de paiement -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-semibold px-1" style="color: #4b5563;">Numéro de Paiement</label>
                    <div class="flex items-center rounded-2xl px-5 py-4 transition-all" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
                        <span class="text-blue-400 font-bold text-sm pr-4 border-r" style="border-color: rgba(255,255,255,0.1);">+{{ $phonePrefix }}</span>
                        <input type="tel" name="payment_phone" value="{{ old('payment_phone', ltrim(preg_replace('/\D/','',$phone), '0237225')) }}" required
                               class="flex-1 bg-transparent text-white text-sm font-semibold pl-4 focus:outline-none"
                               placeholder="Ex: 6XXXXXXXX">
                    </div>
                </div>

                <!-- Opérateur -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-semibold px-1" style="color: #4b5563;">Opérateur Mobile</label>
                    <select name="payment_method" required class="w-full rounded-2xl px-5 py-4 text-white text-sm font-semibold focus:outline-none transition-all appearance-none" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
                        <option value="" disabled selected style="background: #0d1117;">Sélectionnez l'opérateur</option>
                        @if($userCountry === 'CI')
                            <option value="MTN" {{ $detectedOperator === 'MTN' ? 'selected' : '' }} style="background: #0d1117;">MTN Côte d'Ivoire</option>
                            <option value="ORANGE" {{ $detectedOperator === 'ORANGE' ? 'selected' : '' }} style="background: #0d1117;">Orange Côte d'Ivoire</option>
                            <option value="MOOV" {{ $detectedOperator === 'MOOV' ? 'selected' : '' }} style="background: #0d1117;">Moov Côte d'Ivoire</option>
                        @else
                            <option value="MTN" {{ $detectedOperator === 'MTN' ? 'selected' : '' }} style="background: #0d1117;">MTN Cameroun</option>
                            <option value="ORANGE" {{ $detectedOperator === 'ORANGE' ? 'selected' : '' }} style="background: #0d1117;">Orange Cameroun</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <a href="{{ route('deposit') }}" class="flex items-center justify-center py-4 rounded-2xl text-[12px] font-semibold transition active:scale-95" style="background: rgba(255,255,255,0.05); color: #9ca3af;">
                    Annuler
                </a>
                <button type="submit" class="py-4 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 15px rgba(16,185,129,0.25);">
                    Payer maintenant
                </button>
            </div>
        </div>
    </form>
</div>

</x-layouts>
