<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion • BioEnergy</title>
    @vite(["resources/css/app.css","resources/js/app.js"])

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
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        .input-glow:focus {
            box-shadow: 0 0 20px rgba(16, 194, 85, 0.6);
            border-color: #16c255;
        }
        .btn-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(16, 194, 85, 0.5);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">

        <!-- Logo + Titre -->
        <div class="text-center mb-10">
            <img src="{{ asset('images/logo.png') }}" alt="BioEnergy" class="w-48 mx-auto mb-6 drop-shadow-2xl">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-2">Connexion</h1>
            <p class="text-green-300 text-lg">Bienvenue à nouveau investisseur</p>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 glass text-green-300 p-5 rounded-2xl text-center font-medium border border-green-500/30 shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="mb-6 glass text-red-300 p-5 rounded-2xl border border-red-500/30 shadow-lg">
                <p class="font-bold mb-2">Connexion impossible</p>
                <p class="text-sm">
                    {{ session('error') ?? 'Vérifiez vos identifiants et réessayez.' }}
                </p>
                @if($errors->any())
                    <ul class="text-xs mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <!-- Formulaire Glassmorphism -->
        <div class="glass rounded-3xl p-8 shadow-2xl border border-white/20">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Nom d'utilisateur / Téléphone -->
                <div>
                    <input type="text" name="login" value="{{ old('login') }}" required autofocus
                           class="w-full px-6 py-5 rounded-2xl bg-white/10 border border-white/30 text-white placeholder-gray-300 text-lg focus:outline-none input-glow transition"
                           placeholder="Nom d'utilisateur ou téléphone">
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <input type="password" name="password" required
                           class="w-full px-6 py-5 rounded-2xl bg-white/10 border border-white/30 text-white placeholder-gray-300 text-lg focus:outline-none input-glow transition pr-14"
                           placeholder="Mot de passe">
                    <button type="button" onclick="togglePassword()" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white">
                        <i id="eyeIcon" class="fas fa-eye-slash"></i>
                    </button>
                </div>

                <!-- Se souvenir + Mot de passe oublié -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-300">
                        <input type="checkbox" name="remember" class="w-5 h-5 text-emerald-500 rounded" value="{{ true }}">
                        <span>Rester connecté</span>
                    </label>
                </div>

                <!-- Bouton Connexion -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-extrabold text-xl py-6 rounded-2xl shadow-2xl btn-hover transition transform">
                    Se connecter
                </button>

                <!-- Lien Inscription -->
                <div class="text-center pt-6 border-t border-white/20">
                    <p class="text-gray-300 text-sm">
                        Pas encore de compte ?
                        <a href="{{ route('index') }}" class="text-green-400 font-bold hover:text-green-300 transition">
                            S'inscrire gratuitement
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Texte final -->
        <div class="text-center mt-10 text-green-200 text-sm opacity-80">
            <p>Connectez-vous et commencez à gagner tous les jours avec BioEnergy</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.querySelector('input[name="password"]');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }
    </script>

</body>
</html>