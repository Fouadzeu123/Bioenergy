<x-layouts :title="'Sécurité Retrait'">
@php $user = Auth::user(); @endphp
<div class="max-w-xl mx-auto pt-6 px-4 space-y-10 pb-20">

    <!-- Header Config Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl text-center">
        <h1 class="text-2xl font-bold">Sécurisation du Compte</h1>
        <p class="text-[11px] font-semibold text-gray-400 mt-2">Paramètres de retrait confidentiels</p>
        <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Formulaire Sleeker -->
    <form action="{{ route('withdrawal.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Step 1: Verification -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-50 space-y-6">
            <h3 class="text-[10px] font-bold text-gray-400 text-center">Étape 1 • Authentification</h3>
            
            <div class="space-y-4">
                <label class="block text-[11px] font-bold text-gray-400 px-2">Mot de passe de connexion</label>
                <input type="password" name="current_password" required 
                       class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none"
                       placeholder="••••••••">
            </div>
        </div>

        <!-- Step 2: Destination -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-50 space-y-8">
            <h3 class="text-[10px] font-bold text-gray-400 text-center">Étape 2 • Destination des fonds</h3>

            <div class="grid grid-cols-2 gap-4">
                <label class="relative cursor-pointer">
                    <input type="radio" name="withdrawal_country" value="CM" {{ old('withdrawal_country', $user->withdrawal_country ?? 'CM') === 'CM' ? 'checked' : '' }} class="hidden peer">
                    <div class="p-4 rounded-2xl border-2 border-gray-50 bg-gray-50 text-center peer-checked:border-emerald-500 peer-checked:bg-white transition">
                        <span class="text-2xl">🇨🇲</span>
                        <p class="text-[11px] font-bold text-gray-800 mt-2">Cameroun</p>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="withdrawal_country" value="CI" {{ old('withdrawal_country', $user->withdrawal_country ?? 'CM') === 'CI' ? 'checked' : '' }} class="hidden peer">
                    <div class="p-4 rounded-2xl border-2 border-gray-50 bg-gray-50 text-center peer-checked:border-emerald-500 peer-checked:bg-white transition">
                        <span class="text-2xl">🇨🇮</span>
                        <p class="text-[11px] font-bold text-gray-800 mt-2">Côte d'Ivoire</p>
                    </div>
                </label>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 px-2">Opérateur</label>
                    <select name="withdrawal_method" id="withdrawal_method" required 
                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-[12px] font-bold focus:bg-white focus:border-emerald-500 transition outline-none appearance-none">
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 px-2">Numéro de téléphone</label>
                    <input type="text" name="withdrawal_account" value="{{ old('withdrawal_account', $user->withdrawal_account) }}" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-lg font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none font-mono"
                           placeholder="600000000">
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 px-2">Nom complet du compte</label>
                    <input type="text" name="withdrawal_name" value="{{ old('withdrawal_name', $user->withdrawal_name) }}" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none"
                           placeholder="JEAN DUPONT">
                </div>
            </div>
        </div>

        <!-- Step 3: Security Pwd -->
        <div class="bg-slate-900 rounded-[32px] p-8 shadow-xl space-y-6">
            <h3 class="text-[10px] font-bold text-emerald-400 text-center">Étape 3 • Mot de passe de retrait</h3>
            
            <div class="space-y-4">
                <input type="password" name="withdrawal_password" id="withdrawPwd" required 
                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-center text-white focus:bg-white/10 focus:border-emerald-500 transition outline-none"
                       placeholder="Nouveau code de retrait">
                
                <input type="password" name="withdrawal_password_confirmation" id="withdrawPwdConfirm" required
                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-center text-white focus:bg-white/10 focus:border-emerald-500 transition outline-none"
                       placeholder="Confirmer le code">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-emerald-600 text-white text-[11px] font-bold rounded-2xl shadow-xl shadow-emerald-900/20 active:scale-95 transition">
                    Valider la Configuration
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const operators = {
        CM: [
            { value: 'MTN',    label: 'MTN Mobile Money' },
            { value: 'ORANGE', label: 'Orange Money' },
        ],
        CI: [
            { value: 'MTN',    label: 'MTN Mobile Money CI' },
            { value: 'ORANGE', label: 'Orange Money CI' },
            { value: 'MOOV',   label: 'Moov Money' },
        ],
    };

    const savedMethod = @json(old('withdrawal_method', $user->withdrawal_method ?? ''));
    const methodSelect = document.getElementById('withdrawal_method');

    function updateOperators(country) {
        const list = operators[country] || operators['CM'];
        methodSelect.innerHTML = '';
        list.forEach(op => {
            const option = document.createElement('option');
            option.value = op.value;
            option.textContent = op.label;
            if (op.value === savedMethod) option.selected = true;
            methodSelect.appendChild(option);
        });
    }

    // Init
    const checkedCountry = document.querySelector('input[name="withdrawal_country"]:checked');
    updateOperators(checkedCountry ? checkedCountry.value : 'CM');

    document.querySelectorAll('input[name="withdrawal_country"]').forEach(radio => {
        radio.addEventListener('change', () => updateOperators(radio.value));
    });
</script>
</x-layouts>