<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioEnergy - {{ $title ?? 'Dashboard' }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --accent: #facc15;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            min-height: 100dvh;
        }

        /* MARQUEE PARFAITE – TOUS LES MESSAGES DÉFILANT */
        .marquee {
            background: linear-gradient(90deg, #15803d, #16a34a, #22c55e);
            color: white;
            font-weight: 600;
            font-size: 0.925rem;
            padding: 12px 0;
            overflow: hidden;
            position: relative;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.25);
        }

        .marquee-track {
            display: inline-block;
            animation: marquee 120s linear infinite;
        }

        .marquee-item {
            display: inline-flex;
            align-items: center;
            gap: 3.5rem;
            padding-right: 3.5rem;
        }

        .marquee-item i {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        @keyframes marquee {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        .marquee:hover .marquee-track {
            animation-play-state: paused;
        }

        .badge-vip {
            background: linear-gradient(135deg, #fbbf24, #f59e0b, #dc2626);
            color: white;
            font-weight: 800;
            font-size: 0.875rem;
            padding: 8px 16px;
            border-radius: 9999px;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .nav-active { color: var(--primary); }
        .nav-active i { transform: scale(1.2); }
    </style>
</head>

@php
    $user = Auth::user();
    $notifications = \App\Models\Notification::where('user_id', $user->id)
        ->where('is_read', false)
        ->latest()
        ->take(6)
        ->get();

    $messageCount = $notifications->where('type', 'message')->count();
    $currentRoute = request()->route()->getName();
@endphp

<body class="flex flex-col min-h-dvh">

    <!-- HEADER -->
    <header class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-40 shadow-sm">
        <div class="px-5 py-4 flex items-center justify-between">
            <img src="{{ asset('images/logo.png') }}" alt="BioEnergy" class="h-12 w-auto">
            <div class="flex items-center gap-4">
                <div class="badge-vip flex items-center gap-2">
                    <i class="fas fa-crown text-lg"></i>
                    <span>VIP {{ $user->level }}</span>
                </div>
                <a href="{{ route('messages') }}" class="relative p-3 rounded-full hover:bg-green-50 transition">
                    <i class="fas fa-envelope text-2xl text-gray-700"></i>
                    @if($messageCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center shadow-lg">
                            {{ $messageCount > 99 ? '99+' : $messageCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <!-- MARQUEE – TOUS LES MESSAGES DÉFILANT (corrigé à 100%) -->
    <div class="marquee">
        <div class="marquee-track">
            <span class="marquee-item"><i class="fas fa-seedling"></i> BioEnergy – Investissez dans l’énergie verte et gagnez tous les jours</span>
            <span class="marquee-item"><i class="fas fa-chart-line"></i> Marché bioénergie 2025 : +18% de croissance mondiale (IEA)</span>
            <span class="marquee-item"><i class="fas fa-solar-panel"></i> Solaire : coût de production ÷5 en 10 ans – plus rentable que le pétrole</span>
            <span class="marquee-item"><i class="fas fa-wind"></i> Éolien : +30% de capacité en Afrique subsaharienne d'ici 2030</span>
            <span class="marquee-item"><i class="fas fa-leaf"></i> Biomasse : 1 tonne de déchets agricoles = 400 kWh d’électricité propre</span>
            <span class="marquee-item"><i class="fas fa-globe-africa"></i> Cameroun : objectif 75% d’énergie renouvelable d’ici 2035</span>
            <span class="marquee-item"><i class="fas fa-bolt"></i> BioEnergy : déjà plus de 12 000 investisseurs actifs</span>
            <span class="marquee-item"><i class="fas fa-trophy"></i> Top 3 des plateformes d’investissement vert en Afrique 2025</span>

            <!-- Duplication pour boucle infinie parfaite -->
            <span class="marquee-item"><i class="fas fa-seedling"></i> BioEnergy – Investissez dans l’énergie verte et gagnez tous les jours</span>
            <span class="marquee-item"><i class="fas fa-chart-line"></i> Marché bioénergie 2025 : +18% de croissance mondiale (IEA)</span>
            <span class="marquee-item"><i class="fas fa-solar-panel"></i> Solaire : coût de production ÷5 en 10 ans – plus rentable que le pétrole</span>
            <span class="marquee-item"><i class="fas fa-wind"></i> Éolien : +30% de capacité en Afrique subsaharienne d'ici 2030</span>
            <span class="marquee-item"><i class="fas fa-leaf"></i> Biomasse : 1 tonne de déchets agricoles = 400 kWh d’électricité propre</span>
            <span class="marquee-item"><i class="fas fa-globe-africa"></i> Cameroun : objectif 75% d’énergie renouvelable d’ici 2035</span>
            <span class="marquee-item"><i class="fas fa-bolt"></i> BioEnergy : déjà plus de 12 000 investisseurs actifs</span>
            <span class="marquee-item"><i class="fas fa-trophy"></i> Top 3 des plateformes d’investissement vert en Afrique 2025</span>
        </div>
    </div>

    <!-- TOAST NOTIFICATIONS -->
    @if($notifications->count() > 0)
        <div class="fixed top-24 right-4 z-50 space-y-3">
            @foreach($notifications as $notif)
                <div class="bg-white border-l-5 border-green-600 shadow-lg rounded-xl p-4 max-w-xs">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-bell text-green-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium">{{ $notif->title ?? 'Nouvelle notification' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- CONTENU PRINCIPAL -->
    <main class="flex-1 pb-20">
        {{ $slot }}
    </main>

    <!-- NAVIGATION BAS -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-gray-200 px-4 py-3 z-50">
        <div class="max-w-2xl mx-auto flex justify-around items-center text-gray-600">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 py-2 px-4 rounded-xl {{ $currentRoute === 'dashboard' ? 'nav-active font-bold' : '' }}">
                <i class="fas fa-home text-2xl"></i>
                <span class="text-xs">Accueil</span>
            </a>
            <a href="{{ route('products') }}" class="flex flex-col items-center gap-1 py-2 px-4 rounded-xl {{ $currentRoute === 'products' ? 'nav-active font-bold' : '' }}">
                <i class="fas fa-solar-panel text-2xl"></i>
                <span class="text-xs">Produits</span>
            </a>
            <a href="{{ route('share') }}" class="flex flex-col items-center gap-1 py-2 px-4 rounded-xl {{ $currentRoute === 'share' ? 'nav-active font-bold' : '' }}">
                <i class="fas fa-share-alt text-2xl"></i>
                <span class="text-xs">Partager</span>
            </a>
            <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 py-2 px-4 rounded-xl {{ $currentRoute === 'profile' ? 'nav-active font-bold' : '' }}">
                <i class="fas fa-user-circle text-2xl"></i>
                <span class="text-xs">Compte</span>
            </a>
        </div>
    </nav>
</body>
</html>