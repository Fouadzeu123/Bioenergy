<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioEnergy • Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-2 overflow-hidden relative">

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
                <h1 class="text-3xl font-bold text-white tracking-tight">BioEnergy</h1>
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Accès Investisseur</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-[48px] p-10 shadow-2xl space-y-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
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

                @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[11px] font-bold p-4 rounded-2xl text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Login Input -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 px-2">Téléphone</label>
                        <input type="text" name="login" value="{{ old('login') }}" required autofocus
                               class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-6 py-5 text-white text-sm font-semibold focus:border-emerald-500 transition outline-none"
                               placeholder="Numéro de téléphone">
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 px-2">Mot de passe</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-6 py-5 text-white text-sm font-semibold focus:border-emerald-500 transition outline-none"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePassword()" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition">
                                <i id="eyeIcon" class="fas fa-eye-slash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-3 px-2">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded-lg bg-slate-900 border-white/5 text-emerald-500">
                    <label for="remember" class="text-[11px] font-bold text-gray-400 cursor-pointer">Rester connecté</label>
                </div>

                <button type="submit" class="w-full py-6 bg-emerald-600 text-white text-[12px] font-bold rounded-2xl shadow-xl active:scale-95 transition shadow-emerald-500/20">
                    Se connecter
                </button>
            </form>

            <div class="text-center pt-4">
                <p class="text-[11px] font-bold text-gray-500">
                    Nouveau membre ?
                    <a href="{{ route('index') }}" class="text-emerald-400 hover:text-emerald-300 transition">Créer un compte</a>
                </p>
            </div>
        </div>

        <!-- Footer Info -->
        <p class="text-center text-[10px] font-medium text-gray-600">
            &copy; {{ date('Y') }} BioEnergy Corporation • Sécurisé AES-256
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
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
