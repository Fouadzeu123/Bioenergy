<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioEnergy • Inscription</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; }
        .glass-card { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .country-radio:checked + .country-card { background: #10b981; border-color: #10b981; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-2 overflow-x-hidden relative py-12">

    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-sm space-y-10">

        <!-- Brand Header -->
        <div class="text-center space-y-4">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-800 rounded-[32px] border border-white/5 shadow-2xl mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
            </div>
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-white tracking-tight leading-none">BioEnergy</h1>
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Nouvel Investisseur</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-[48px] p-8 shadow-2xl space-y-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                @if($errors->any())
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-[11px] p-4 rounded-2xl">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-[11px] font-bold p-4 rounded-2xl text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-5">
                    <!-- Sélection du pays -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 px-1">Pays</label>
                        <div class="relative">
                            <select id="country-select" class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-5 py-4 text-white text-sm font-semibold focus:border-emerald-500 transition outline-none appearance-none">
                                @php $countries = config('notchpay.country_phone_codes'); @endphp
                                @foreach($countries as $iso => $code)
                                    <option value="{{ $iso }}" data-code="{{ $code }}"
                                        @selected(old('country_code_iso', 'CM') === $iso)>
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
                        <input type="hidden" name="country_code" id="country_code_input" value="{{ old('country_code', '237') }}">
                    </div>

                    <!-- Téléphone -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 px-2">Téléphone</label>
                        <div class="flex items-center bg-slate-900/50 border border-white/5 rounded-2xl px-6 py-4 focus-within:border-emerald-500 transition">
                            <span id="phone-prefix-display" class="text-emerald-400 font-bold text-sm pr-4 border-r border-white/5">+237</span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="flex-1 bg-transparent text-white text-sm font-semibold pl-4 focus:outline-none"
                                   placeholder="Numéro de téléphone">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 px-2">Sécurité</label>
                        <div class="space-y-3">
                            <input type="password" name="password" required
                                   class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-6 py-4 text-white text-sm font-semibold focus:border-emerald-500 transition outline-none"
                                   placeholder="Mot de passe">
                            <input type="password" name="password_confirmation" required
                                   class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-6 py-4 text-white text-sm font-semibold focus:border-emerald-500 transition outline-none"
                                   placeholder="Confirmer mot de passe">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 px-2">Parrainage</label>
                        <input type="text" name="invitation_code" value="{{ old('invitation_code', request('ref', session('referral_code'))) }}" required
                               class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-6 py-4 text-white text-sm font-semibold focus:border-emerald-500 transition outline-none"
                               placeholder="Code d'invitation">
                    </div>
                </div>

                <button type="submit" class="w-full py-6 bg-emerald-600 text-white text-[12px] font-bold rounded-2xl shadow-xl active:scale-95 transition shadow-emerald-500/20">
                    Créer mon compte
                </button>
            </form>

            <div class="text-center pt-2">
                <p class="text-[11px] font-bold text-gray-500">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 transition">Se connecter</a>
                </p>
            </div>
        </div>

        <p class="text-center text-[10px] font-medium text-gray-600 px-8">
            En continuant, vous acceptez nos conditions générales de service et d'investissement.
        </p>
    </div>

    <script>
        const countries = @json(config('notchpay.country_phone_codes'));

        document.getElementById('country-select').addEventListener('change', function () {
            const iso = this.value;
            const code = this.options[this.selectedIndex].dataset.code;
            document.getElementById('phone-prefix-display').textContent = '+' + code;
            document.getElementById('country_code_input').value = code;
        });

        // Init on load
        (function () {
            const sel = document.getElementById('country-select');
            const code = sel.options[sel.selectedIndex].dataset.code;
            document.getElementById('phone-prefix-display').textContent = '+' + code;
            document.getElementById('country_code_input').value = code;
        })();
    </script>
</body>
</html>
