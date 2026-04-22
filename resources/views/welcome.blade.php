<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription • BioEnergy</title>
    @vite(["resources/css/app.css","resources/js/app.js"])

    <!-- Tailwind + Animations -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #0f172a);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .input-glow:focus {
            box-shadow: 0 0 20px rgba(16, 194, 85, 0.5);
            border-color: #16c255;
        }
        .btn-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(16, 194, 85, 0.4);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">

        <!-- Logo + Titre -->
        <div class="text-center mb-10">
            <img src="{{ asset('images/logo.png') }}" alt="BioEnergy" class="w-48 mx-auto mb-6">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-2">Inscription</h1>
            <p class="text-green-300 text-lg">Rejoignez la révolution verte</p>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 glass text-green-300 p-5 rounded-2xl text-center font-medium border border-green-500/30 shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 glass text-red-300 p-5 rounded-2xl border border-red-500/30 shadow-lg">
                <p class="font-bold mb-2">Erreurs détectées :</p>
                <ul class="text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire Glassmorphism -->
        <div class="glass rounded-3xl p-8 shadow-2xl border border-white/20">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Nom d'utilisateur -->
                <div>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full px-5 py-4 rounded-2xl bg-white/10 border border-white/30 text-white placeholder-gray-300 focus:outline-none input-glow transition"
                           placeholder="Nom d'utilisateur">
                </div>

                <!-- Numéro de téléphone avec sélection de pays -->
                <div>
                    <!-- Sélecteur de pays -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="_country" value="CM" checked class="hidden peer" id="reg-cm">
                            <div class="peer-checked:border-green-500 peer-checked:bg-green-500/20 border-2 border-white/20 rounded-2xl p-3 text-center transition">
                                <span class="text-xl">🇨🇲</span>
                                <p class="text-white text-xs font-bold mt-1">Cameroun</p>
                                <p class="text-green-300 text-[10px]">+237</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="_country" value="CI" class="hidden peer" id="reg-ci">
                            <div class="peer-checked:border-green-500 peer-checked:bg-green-500/20 border-2 border-white/20 rounded-2xl p-3 text-center transition">
                                <span class="text-xl">🇨🇮</span>
                                <p class="text-white text-xs font-bold mt-1">Côte d'Ivoire</p>
                                <p class="text-green-300 text-[10px]">+225</p>
                            </div>
                        </label>
                    </div>
                    <!-- Champ téléphone avec indicatif dynamique -->
                    <div class="flex items-center gap-4 px-5 py-4 rounded-2xl bg-white/10 border border-white/30 focus-within:border-green-500 transition-all duration-300 group">
                        <div class="flex items-center gap-2 border-r border-white/20 pr-4" id="phone-flag-area">
                            <img id="flag-img" src="https://flagcdn.com/w20/cm.png" alt="Pays" class="w-5 shadow-sm">
                            <span id="phone-prefix-display" class="text-green-400 font-bold text-lg tracking-wider">+237</span>
                        </div>
                        <input type="hidden" name="country_code" id="country_code_input" value="{{ old('country_code', '237') }}">
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               class="flex-1 bg-transparent text-white placeholder-gray-400 focus:outline-none text-lg"
                               placeholder="Numéro de téléphone" id="phone-input">
                    </div>
                </div>


                <!-- Mots de passe -->
                <div>
                    <input type="password" name="password" required
                           class="w-full px-5 py-4 rounded-2xl bg-white/10 border border-white/30 text-white placeholder-gray-300 focus:outline-none input-glow"
                           placeholder="Mot de passe">
                </div>

                <div>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-5 py-4 rounded-2xl bg-white/10 border border-white/30 text-white placeholder-gray-300 focus:outline-none input-glow"
                           placeholder="Confirmer le mot de passe">
                </div>

                <!-- Code d'invitation -->
                <div>
                    <input type="text" name="invitation_code" 
                           value="{{ old('invitation_code', request('ref', session('referral_code'))) }}" 
                           required
                           class="w-full px-5 py-4 rounded-2xl bg-white/10 border border-white/30 text-white placeholder-gray-300 focus:outline-none input-glow"
                           placeholder="Code d'invitation (obligatoire)">
                </div>

                <!-- Connexion -->
                <div class="text-center pt-4">
                    <p class="text-gray-300 text-sm mb-6">
                        Déjà membre ? 
                        <a href="{{ route('login') }}" class="text-green-400 font-bold hover:text-green-300 transition">
                            Se connecter
                        </a>
                    </p>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-extrabold text-xl py-5 rounded-2xl shadow-xl btn-hover transition transform">
                        S'inscrire maintenant
                    </button>
                </div>
            </form>
        </div>

        <!-- Texte final -->
        <div class="text-center mt-8 text-green-200 text-sm opacity-80">
            <p>En vous inscrivant, vous acceptez nos <a href="#" class="underline hover:text-white">conditions d'utilisation</a></p>
        </div>
    </div>

<script>
    // Mise à jour du préfixe téléphone et du pays à l'inscription
    const countries = {
        CM: { code: '237', flag: 'https://flagcdn.com/w20/cm.png', prefix: '+237' },
        CI: { code: '225', flag: 'https://flagcdn.com/w20/ci.png', prefix: '+225' },
    };

    document.querySelectorAll('input[name="_country"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const c = countries[this.value];
            if (!c) return;
            document.getElementById('flag-img').src = c.flag;
            document.getElementById('phone-prefix-display').textContent = c.prefix;
            document.getElementById('country_code_input').value = c.code;
        });
    });
</script>
</body>
</html>