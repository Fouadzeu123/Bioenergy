<x-layouts :title="'Confirmation du Dépôt'" :level="Auth::user()->level">

@php
    $user = Auth::user();
    $phone = $user->phone ?? '';
    $userCountry = config('notchpay.phone_to_country.' . $user->country_code, 'CM');
    $phonePrefix = config('notchpay.country_phone_codes.' . $userCountry, '237');
    $currency = $user->currency;
    $countryOperators = config('notchpay.channels.' . $userCountry, []);
@endphp

<div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden" style="background-color: #0f172a;">
    <!-- Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-sm relative z-10">
        <div class="rounded-[2.5rem] p-8 animate__animated animate__fadeIn" style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
            
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Finaliser le Dépôt</h2>
                <p class="text-[12px] font-medium text-gray-400">Vérifiez vos informations avant de valider.</p>
            </div>

            <!-- Résumé du montant -->
            <div class="rounded-3xl p-6 text-center mb-6 shadow-lg" style="background: linear-gradient(135deg, #1e3a8a 0%, #0e7490 100%);">
                <p class="text-[11px] font-semibold text-blue-200 mb-1 uppercase tracking-wider">Montant à créditer</p>
                <p class="text-3xl font-bold text-white">{{ number_format($amount, 0, '.', ' ') }} <span class="text-lg text-emerald-400">{{ $currency }}</span></p>
            </div>

            <!-- Instructions Flash -->
            <div class="rounded-2xl p-4 mb-6" style="background: rgba(59,130,246,0.05); border: 1px solid rgba(59,130,246,0.1);">
                <div class="flex items-center gap-3">
                    <i class="fas fa-info-circle text-blue-400 text-sm"></i>
                    <p class="text-[10px] font-medium text-gray-300 leading-relaxed">
                        Un message USSD s'affichera sur votre téléphone après validation.
                    </p>
                </div>
            </div>

            <!-- Formulaire de confirmation -->
            <form action="{{ route('depot.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="amount" value="{{ $amount }}">

                <div class="space-y-4">
                    <!-- Numéro de paiement -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider px-1">Numéro de Paiement</label>
                        <div class="flex items-center rounded-2xl px-5 py-4 transition-all" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
                            <span class="text-blue-400 font-bold text-sm pr-4 border-r" style="border-color: rgba(255,255,255,0.1);">+{{ $phonePrefix }}</span>
                            <input type="tel" name="payment_phone" value="{{ old('payment_phone', ltrim(preg_replace('/\D/','',$phone), '0237225')) }}" required
                                   class="flex-1 bg-transparent text-white text-sm font-semibold pl-4 focus:outline-none"
                                   placeholder="Ex: 6XXXXXXXX">
                        </div>
                    </div>

                    <!-- Opérateur -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider px-1">Opérateur Mobile</label>
                        <div class="relative">
                            <select name="payment_method" required class="w-full rounded-2xl px-5 py-4 text-white text-sm font-semibold focus:outline-none transition-all appearance-none" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
                                <option value="" disabled selected>Sélectionnez l'opérateur</option>
                                @foreach($countryOperators as $operatorKey => $channel)
                                    <option value="{{ $operatorKey }}">{{ $operatorKey }} {{ config('notchpay.country_names.' . $userCountry) }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-500 text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4">
                    <a href="{{ route('deposit') }}" class="flex items-center justify-center py-4 rounded-2xl text-[12px] font-bold transition active:scale-95 text-gray-400" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.05);">
                        Annuler
                    </a>
                    <button type="submit" class="py-4 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition shadow-lg shadow-emerald-500/20" style="background: linear-gradient(135deg, #10b981, #059669);">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-layouts>
