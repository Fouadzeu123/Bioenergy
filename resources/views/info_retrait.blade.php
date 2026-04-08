<x-layouts :title="'Configuration du Retrait'" :level="Auth::user()->level">

@php
    $user = Auth::user();
@endphp

<div class="max-w-md mx-auto py-10">

    <!-- En-tête premium -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-10 text-center shadow-2xl mb-10">
        <h1 class="text-4xl font-extrabold tracking-tight">Configuration du retrait</h1>
        <p class="text-slate-300 mt-4 text-lg leading-relaxed">
            Protégez vos fonds avec un mot de passe dédié et des informations vérifiées
        </p>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-5 rounded-2xl text-center font-medium text-lg shadow-lg">
            {{ session('success') }}
        </div>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });</script>
    @endif

    @if($errors->any() || session('error'))
        <div class="mb-8 bg-red-50 border border-red-200 text-red-800 px-6 py-5 rounded-2xl text-center font-medium text-lg shadow-lg">
            {{ session('error') ?? 'Veuillez corriger les erreurs ci-dessous.' }}
        </div>
    @endif

    <!-- Formulaire principal -->
    <form action="{{ route('withdrawal.update') }}" method="POST" class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-8 space-y-10">

            <!-- 1. Vérification identité -->
            <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-6 text-center">
                <p class="text-amber-800 font-bold text-lg mb-4">Sécurité renforcée</p>
                <p class="text-slate-700 mb-6 text-sm leading-relaxed">
                    Pour modifier vos informations de retrait, veuillez confirmer votre identité avec votre mot de passe de connexion.
                </p>

                <label class="block text-slate-700 font-semibold text-lg mb-3">
                    Mot de passe de connexion
                </label>
                <input type="password" name="current_password" required autocomplete="current-password"
                       class="w-full text-center text-2xl tracking-widest bg-white border {{ $errors->has('current_password') ? 'border-red-500 ring-4 ring-red-100' : 'border-slate-300' }} rounded-2xl py-6 focus:ring-4 focus:ring-emerald-500 focus:outline-none transition">
                @error('current_password')
                    <p class="text-red-600 text-sm font-medium mt-3">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Opérateur -->
            <div>
                <label class="block text-slate-700 font-semibold text-lg mb-3">Opérateur Mobile Money</label>
                <select name="withdrawal_method" required
                        class="w-full bg-gray-50 border {{ $errors->has('withdrawal_method') ? 'border-red-500' : 'border-0' }} rounded-2xl py-6 px-6 text-lg focus:ring-4 focus:ring-emerald-500 focus:outline-none transition">
                    <option value="">Sélectionner un opérateur</option>
                    <option value="MTN" {{ old('withdrawal_method', $user->withdrawal_method) === 'MTN' ? 'selected' : '' }}>MTN Mobile Money</option>
                    <option value="ORANGE" {{ old('withdrawal_method', $user->withdrawal_method) === 'ORANGE' ? 'selected' : '' }}>Orange Money</option>
                </select>
                @error('withdrawal_method')
                    <p class="text-red-600 text-sm font-medium mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Numéro -->
            <div>
                <label class="block text-slate-700 font-semibold text-lg mb-3">Numéro Mobile Money</label>
                <input type="text" name="withdrawal_account" required
                       value="{{ old('withdrawal_account', $user->withdrawal_account) }}"
                       class="w-full text-center text-3xl font-mono tracking-wider bg-gray-50 border {{ $errors->has('withdrawal_account') ? 'border-red-500 ring-4 ring-red-100' : 'border-0' }} rounded-2xl py-6 focus:ring-4 focus:ring-emerald-500 focus:outline-none transition"
                       placeholder="690 00 00 000" maxlength="20">
                @error('withdrawal_account')
                    <p class="text-red-600 text-sm font-medium text-center mt-3">{{ $message }}</p>
                @enderror
                <p class="text-center text-slate-500 text-xs mt-3">Sans indicatif, espaces ou symboles</p>
            </div>

            <!-- 4. Nom du titulaire -->
            <div>
                <label class="block text-slate-700 font-semibold text-lg mb-3">Nom complet du titulaire</label>
                <input type="text" name="withdrawal_name" required
                       value="{{ old('withdrawal_name', $user->withdrawal_name) }}"
                       class="w-full text-center text-xl bg-gray-50 border {{ $errors->has('withdrawal_name') ? 'border-red-500' : 'border-0' }} rounded-2xl py-6 focus:ring-4 focus:ring-emerald-500 focus:outline-none transition"
                       placeholder="Ex: DUPONT Jean Marie">
                @error('withdrawal_name')
                    <p class="text-red-600 text-sm font-medium text-center mt-3">{{ $message }}</p>
                @enderror
            </div>

            <!-- 5. Mot de passe de retrait + Barre de force -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-8 border border-slate-200">
                <h3 class="text-2xl font-bold text-center text-slate-800 mb-8">
                    Mot de passe de retrait <span class="text-red-600">*</span>
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <input type="password" name="withdrawal_password" id="withdrawPwd" required minlength="6"
                               class="w-full text-center text-xl bg-white border {{ $errors->has('withdrawal_password') ? 'border-red-500 ring-4 ring-red-100' : 'border-slate-300' }} rounded-2xl py-6 focus:ring-4 focus:ring-emerald-500 focus:outline-none transition"
                               placeholder="Minimum 6 caractères" autocomplete="new-password">
                    </div>
                    <div>
                        <input type="password" name="withdrawal_password_confirmation" id="withdrawPwdConfirm" required minlength="6"
                               class="w-full text-center text-xl bg-white border {{ $errors->has('withdrawal_password') ? 'border-red-500' : 'border-slate-300' }} rounded-2xl py-6 focus:ring-4 focus:ring-emerald-500 focus:outline-none transition"
                               placeholder="Confirmez le mot de passe">
                    </div>
                </div>

                <!-- Barre de force -->
                <div class="mt-8">
                    <div class="flex justify-between text-sm mb-3">
                        <span class="font-semibold text-slate-600">Force du mot de passe</span>
                        <span id="strengthText" class="font-bold text-slate-500">Entrez un mot de passe</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                        <div id="strengthBar" class="h-full rounded-full transition-all duration-300" style="width: 0%; background: #94a3b8;"></div>
                    </div>
                </div>

                <div id="matchText" class="text-center mt-5 text-sm font-medium"></div>

                @error('withdrawal_password')
                    <p class="text-red-600 text-sm font-medium text-center mt-4">{{ $message }}</p>
                @enderror
            </div>

            <!-- Conseils sécurité -->
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-6 text-sm">
                <p class="font-bold text-emerald-800 mb-3">Ce mot de passe sera exigé à chaque retrait</p>
                <ul class="space-y-2 text-slate-700">
                    <li>• Différent de votre mot de passe de connexion</li>
                    <li>• Minimum 6 caractères (8+ recommandé)</li>
                    <li>• En cas d’oubli → contactez le support</li>
                </ul>
            </div>

            <!-- Boutons -->
            <div class="pt-8 space-y-4">
                <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xl py-7 rounded-2xl shadow-xl transition transform hover:scale-105">
                    Sauvegarder les modifications
                </button>

                <a href="{{ route('retrait') }}"
                   class="block text-center text-slate-600 font-medium py-4 hover:text-slate-800 transition">
                    ← Retour aux retraits
                </a>
            </div>
        </div>
    </form>

    <!-- Pied de page -->
    <div class="mt-12 text-center text-slate-500 text-xs">
        <p class="font-medium">Vos données sont chiffrées et protégées</p>
        <p class="mt-1">Aucune information n’est partagée avec des tiers</p>
    </div>
</div>

<script>
    const pwd = document.getElementById('withdrawPwd');
    const confirm = document.getElementById('withdrawPwdConfirm');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const matchText = document.getElementById('matchText');

    function updateStrength(password) {
        let score = 0;
        if (password.length >= 8) score += 25;
        if (password.length >= 12) score += 15;
        if (/[a-z]/.test(password)) score += 15;
        if (/[A-Z]/.test(password)) score += 20;
        if (/[0-9]/.test(password)) score += 15;
        if (/[^A-Za-z0-9]/.test(password)) score += 20;

        strengthBar.style.width = Math.min(score, 100) + '%';

        if (score < 40) {
            strengthBar.style.background = '#ef4444';
            strengthText.textContent = 'Très faible';
            strengthText.className = 'font-bold text-red-600';
        } else if (score < 60) {
            strengthBar.style.background = '#f97316';
            strengthText.textContent = 'Faible';
            strengthText.className = 'font-bold text-orange-600';
        } else if (score < 80) {
            strengthBar.style.background = '#eab308';
            strengthText.textContent = 'Moyen';
            strengthText.className = 'font-bold text-yellow-600';
        } else {
            strengthBar.style.background = '#22c55e';
            strengthText.textContent = 'Excellent !';
            strengthText.className = 'font-bold text-green-600';
        }
    }

    function checkMatch() {
        if (!pwd.value || !confirm.value) {
            matchText.textContent = '';
            return;
        }
        if (pwd.value === confirm.value) {
            matchText.textContent = 'Les mots de passe correspondent';
            matchText.className = 'text-green-600 font-bold';
        } else {
            matchText.textContent = 'Les mots de passe ne correspondent pas';
            matchText.className = 'text-red-600 font-bold';
        }
    }

    pwd.addEventListener('input', () => {
        updateStrength(pwd.value);
        checkMatch();
    });
    confirm.addEventListener('input', checkMatch);
</script>

</x-layouts>