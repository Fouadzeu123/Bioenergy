<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioEnergy • {{ $title ?? 'Portfolio' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --bg-base:    #07090f;
            --bg-card:    #0d1117;
            --bg-card2:   #111827;
            --border:     rgba(255,255,255,0.06);
            --accent:     #3b82f6;
            --accent2:    #06b6d4;
            --text-muted: #6b7280;
            --text-dim:   #9ca3af;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: #e2e8f0;
            -webkit-tap-highlight-color: transparent;
            min-height: 100vh;
        }
        /* Fond étoilé subtil */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% -20%, rgba(59,130,246,0.12) 0%, transparent 70%),
                        radial-gradient(ellipse 50% 40% at 80% 80%, rgba(6,182,212,0.06) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }
        main { position: relative; z-index: 1; }
        .nav-blur { backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); }
        .premium-shadow { box-shadow: 0 0 40px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.04); }

        /* Marquee */
        .marquee {
            overflow: hidden;
            white-space: nowrap;
            background: rgba(13,17,23,0.9);
            border-bottom: 1px solid var(--border);
            position: relative;
            z-index: 1;
        }
        .marquee-track {
            display: inline-block;
            animation: marquee 40s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .custom-scrollbar::-webkit-scrollbar { display: none; }

        /* Cards glass style */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
        }
        .card-lighter {
            background: var(--bg-card2);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
        }

        /* Pill badge */
        .badge-blue {
            background: rgba(59,130,246,0.15);
            color: #60a5fa;
            border: 1px solid rgba(59,130,246,0.25);
        }

        /* Glow button */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #0891b2);
            box-shadow: 0 0 24px rgba(59,130,246,0.3);
            color: white;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.25s ease;
        }
        .btn-primary:hover {
            box-shadow: 0 0 36px rgba(59,130,246,0.5);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: scale(0.97); }

        /* Nav item active */
        .nav-active {
            background: rgba(59,130,246,0.2);
            color: #60a5fa !important;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    @php
        $user = Auth::user();
        $currentRoute = request()->route()->getName();
        $unreadCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
    @endphp

    <!-- Top Bar -->
    <header class="sticky top-0 z-[100] nav-blur border-b" style="background: rgba(7,9,15,0.85); border-color: rgba(255,255,255,0.05);">
        <div class="max-w-xl mx-auto px-5 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 16px rgba(59,130,246,0.4);">
                    <img src="{{ asset('images/logo.png') }}" class="w-5 h-5 object-contain">
                </div>
                <span class="text-sm font-bold tracking-tight text-white">BioEnergy</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('messages') }}" class="relative w-9 h-9 flex items-center justify-center rounded-xl transition" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                    <i class="fas fa-bell text-xs text-gray-400"></i>
                    @if($unreadCount > 0)
                        <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border-2 border-[#07090f] animate-pulse"></span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <!-- Info Marquee -->
    <div class="marquee py-2">
        <div class="marquee-track flex items-center gap-12">
            @foreach(range(1, 2) as $i)
                <span class="text-[10px] font-medium flex items-center gap-3" style="color: #4b5563;">
                    <i class="fas fa-circle text-[4px] text-blue-500"></i>
                    Nouveau retrait de 45 000 XAF par l'utilisateur #B782
                </span>
                <span class="text-[10px] font-medium flex items-center gap-3" style="color: #4b5563;">
                    <i class="fas fa-circle text-[4px] text-cyan-500"></i>
                    Le Pack Biomasse atteint 500% de rendement annuel
                </span>
                <span class="text-[10px] font-medium flex items-center gap-3" style="color: #4b5563;">
                    <i class="fas fa-circle text-[4px] text-blue-400"></i>
                    Bonus de parrainage VIP activé pour tous les membres
                </span>
            @endforeach
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 pb-32">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation — pleine largeur -->
    <nav class="fixed bottom-0 left-0 right-0 z-[100]" style="background: rgba(10,12,18,0.97); border-top: 1px solid rgba(255,255,255,0.07); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">
        <div class="flex items-center justify-around px-2 pt-2 pb-3" style="padding-bottom: max(12px, env(safe-area-inset-bottom));">
            <a href="{{ route('dashboard') }}" class="flex-1 flex flex-col items-center py-1.5 rounded-xl transition-all {{ $currentRoute === 'dashboard' ? '' : '' }}"
               style="{{ $currentRoute === 'dashboard' ? 'color: #60a5fa;' : 'color: #4b5563;' }}">
                <i class="fas fa-house text-[18px] mb-1"></i>
                <span style="font-size: 9px; font-weight: 600; letter-spacing: 0.02em;">Accueil</span>
            </a>
            <a href="{{ route('products') }}" class="flex-1 flex flex-col items-center py-1.5 rounded-xl transition-all"
               style="{{ $currentRoute === 'products' ? 'color: #60a5fa;' : 'color: #4b5563;' }}">
                <i class="fas fa-microchip text-[18px] mb-1"></i>
                <span style="font-size: 9px; font-weight: 600; letter-spacing: 0.02em;">Produits</span>
            </a>
            <a href="{{ route('luckywheel') }}" class="flex-1 flex flex-col items-center py-1.5 rounded-xl transition-all"
               style="{{ $currentRoute === 'luckywheel' ? 'color: #60a5fa;' : 'color: #4b5563;' }}">
                <i class="fas fa-dharmachakra text-[18px] mb-1"></i>
                <span style="font-size: 9px; font-weight: 600; letter-spacing: 0.02em;">Lucky</span>
            </a>
            <a href="{{ route('share') }}" class="flex-1 flex flex-col items-center py-1.5 rounded-xl transition-all"
               style="{{ $currentRoute === 'share' ? 'color: #60a5fa;' : 'color: #4b5563;' }}">
                <i class="fas fa-users text-[18px] mb-1"></i>
                <span style="font-size: 9px; font-weight: 600; letter-spacing: 0.02em;">Équipe</span>
            </a>
            <a href="{{ route('profile') }}" class="flex-1 flex flex-col items-center py-1.5 rounded-xl transition-all"
               style="{{ $currentRoute === 'profile' ? 'color: #60a5fa;' : 'color: #4b5563;' }}">
                <i class="fas fa-user text-[18px] mb-1"></i>
                <span style="font-size: 9px; font-weight: 600; letter-spacing: 0.02em;">Profil</span>
            </a>
        </div>
    </nav>

    <!-- Toast Success -->
    @if(session('success'))
    <div id="toast-success" class="fixed top-20 left-1/2 -translate-x-1/2 z-[200] w-full max-w-xs animate__animated animate__fadeInDown">
        <div class="text-white px-5 py-4 rounded-2xl flex items-center gap-3 shadow-2xl" style="background: linear-gradient(135deg, #1d4ed8, #0e7490); border: 1px solid rgba(59,130,246,0.3);">
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-xs"></i>
            </div>
            <span class="text-[11px] font-semibold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="toast-error" class="fixed top-20 left-1/2 -translate-x-1/2 z-[200] w-full max-w-xs animate__animated animate__shakeX">
        <div class="px-5 py-4 rounded-2xl flex items-center gap-3 shadow-2xl" style="background: rgba(13,17,23,0.95); border: 1px solid rgba(239,68,68,0.3);">
            <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(239,68,68,0.15);">
                <i class="fas fa-exclamation-triangle text-xs text-red-400"></i>
            </div>
            <span class="text-[11px] font-semibold text-red-400">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successToast = document.getElementById('toast-success');
            const errorToast = document.getElementById('toast-error');

            function hideToast(element) {
                if (element) {
                    setTimeout(() => {
                        element.classList.remove('animate__fadeInDown', 'animate__shakeX');
                        element.classList.add('animate__fadeOutUp');
                        setTimeout(() => element.remove(), 1000); // attendre la fin de l'animation
                    }, 3000); // Durée d'affichage (3 secondes)
                }
            }

            hideToast(successToast);
            hideToast(errorToast);
        });
    </script>
</body>
</html>