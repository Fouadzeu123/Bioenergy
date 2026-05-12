<x-layouts :title="'Sécurité Retrait'">
@php $user = Auth::user(); @endphp
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white text-center" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
                <i class="fas fa-shield-halved text-white text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold">Sécurisation du Compte</h1>
            <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Paramètres de retrait confidentiels</p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <form action="{{ route('withdrawal.update') }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Étape 1 : Authentification -->
        <div class="rounded-2xl p-6 space-y-4" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-[11px] font-bold text-blue-400" style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3);">1</div>
                <h3 class="text-[12px] font-semibold text-gray-300">Authentification</h3>
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-semibold px-1" style="color: #4b5563;">Mot de passe de connexion</label>
                <input type="password" name="current_password" required
                       class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-center text-white outline-none transition"
                       style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                       placeholder="••••••••">
            </div>
        </div>

        <!-- Étape 2 : Destination -->
        <div class="rounded-2xl p-6 space-y-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-[11px] font-bold text-cyan-400" style="background: rgba(6,182,212,0.15); border: 1px solid rgba(6,182,212,0.3);">2</div>
                <h3 class="text-[12px] font-semibold text-gray-300">Destination des fonds</h3>
            </div>

            <!-- Choix pays -->
            <div class="space-y-2">
                <label class="text-[11px] font-semibold px-1" style="color: #4b5563;">Pays de retrait</label>
                <div class="relative">
                    <select name="withdrawal_country" id="withdrawal_country" required
                            class="w-full rounded-2xl px-5 py-4 text-[12px] font-semibold text-white outline-none transition appearance-none cursor-pointer"
                            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        @php $allCountries = config('notchpay.country_phone_codes'); @endphp
                        @foreach($allCountries as $iso => $code)
                            <option value="{{ $iso }}" {{ old('withdrawal_country', $user->withdrawal_country ?? 'CM') === $iso ? 'selected' : '' }}>
                                {{ config('notchpay.country_flags.' . $iso) }}
                                {{ config('notchpay.country_names.' . $iso) }}
                                (+{{ $code }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-500 text-[10px]"></i>
                    </div>
                </div>
            </div>

            <!-- Opérateur -->
            <div class="space-y-2">
                <label class="text-[11px] font-semibold px-1" style="color: #4b5563;">Opérateur</label>
                <select name="withdrawal_method" id="withdrawal_method" required
                        class="w-full rounded-2xl px-5 py-4 text-[12px] font-semibold text-white outline-none transition appearance-none cursor-pointer"
                        style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                </select>
            </div>

            <!-- Numéro -->
            <div class="space-y-2">
                <label class="text-[11px] font-semibold px-1" style="color: #4b5563;">Numéro de téléphone</label>
                <input type="text" name="withdrawal_account" value="{{ old('withdrawal_account', $user->withdrawal_account) }}" required
                       class="w-full rounded-2xl px-5 py-4 text-lg font-bold text-center text-white outline-none transition font-mono"
                       style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                       placeholder="600000000">
            </div>

            <!-- Nom -->
            <div class="space-y-2">
                <label class="text-[11px] font-semibold px-1" style="color: #4b5563;">Nom complet du compte</label>
                <input type="text" name="withdrawal_name" value="{{ old('withdrawal_name', $user->withdrawal_name) }}" required
                       class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-center text-white outline-none transition"
                       style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                       placeholder="Jean Dupont">
            </div>
        </div>

        <!-- Étape 3 : Code de retrait -->
        <div class="rounded-2xl p-6 space-y-4" style="background: rgba(59,130,246,0.05); border: 1px solid rgba(59,130,246,0.2);">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-[11px] font-bold text-white" style="background: linear-gradient(135deg, #2563eb, #0891b2);">3</div>
                <h3 class="text-[12px] font-semibold text-gray-300">Mot de passe de retrait</h3>
            </div>

            <input type="password" name="withdrawal_password" id="withdrawPwd" required
                   class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-center text-white outline-none transition"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                   placeholder="Nouveau code de retrait">

            <input type="password" name="withdrawal_password_confirmation" id="withdrawPwdConfirm" required
                   class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-center text-white outline-none transition"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                   placeholder="Confirmer le code">

            <button type="submit" class="w-full py-4 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 24px rgba(59,130,246,0.3);">
                Valider la Configuration
            </button>
        </div>
    </form>
</div>

<script>
    const operators = @json(array_map('array_keys', config('notchpay.channels')));

    const savedMethod = @json(old('withdrawal_method', $user->withdrawal_method ?? ''));
    const methodSelect = document.getElementById('withdrawal_method');
    const countrySelect = document.getElementById('withdrawal_country');

    function updateOperators(country) {
        const list = operators[country] || [];
        methodSelect.innerHTML = '';
        list.forEach(op => {
            const option = document.createElement('option');
            option.value = op;
            option.textContent = op;
            if (op === savedMethod) option.selected = true;
            methodSelect.appendChild(option);
        });
    }

    updateOperators(countrySelect.value);
    countrySelect.addEventListener('change', () => updateOperators(countrySelect.value));
</script>
</x-layouts>