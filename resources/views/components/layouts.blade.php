<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioEnergy • {{ $title ?? 'Portfolio' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            color: #0f172a; 
            -webkit-tap-highlight-color: transparent;
        }
        .premium-shadow { box-shadow: 0 20px 50px -12px rgba(15, 23, 42, 0.1); }
        .nav-blur { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        
        .marquee {
            overflow: hidden;
            white-space: nowrap;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
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
    </style>
</head>
<body class="min-h-screen flex flex-col">

    @php
        $user = Auth::user();
        $currentRoute = request()->route()->getName();
        $unreadCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
    @endphp

    <!-- Top Bar Sleeker -->
    <header class="sticky top-0 z-[100] bg-white/80 nav-blur border-b border-gray-100">
        <div class="max-w-xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg">
                    <img src="{{ asset('images/logo.png') }}" class="w-5 h-5 object-contain">
                </div>
                <span class="text-sm font-bold tracking-tight">BioEnergy</span>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="bg-emerald-500/10 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-bold border border-emerald-500/20">
                    VIP {{ $user->level }}
                </div>
                <a href="{{ route('messages') }}" class="relative w-10 h-10 flex items-center justify-center text-gray-400 hover:text-slate-900 transition">
                    <i class="fas fa-bell text-xs"></i>
                    @if($unreadCount > 0)
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <!-- Info Marquee -->
    <div class="marquee py-2 px-4">
        <div class="marquee-track flex items-center gap-12">
            @foreach(range(1, 2) as $i)
                <span class="text-[10px] font-medium text-gray-500 flex items-center gap-3">
                    <i class="fas fa-circle text-[4px] text-emerald-500"></i>
                    Nouveau retrait de 45 000 XAF par l'utilisateur #B782
                </span>
                <span class="text-[10px] font-medium text-gray-500 flex items-center gap-3">
                    <i class="fas fa-circle text-[4px] text-emerald-500"></i>
                    Le Pack Biomasse atteint 500% de rendement annuel
                </span>
                <span class="text-[10px] font-medium text-gray-500 flex items-center gap-3">
                    <i class="fas fa-circle text-[4px] text-emerald-500"></i>
                    Bonus de parrainage VIP activé pour tous les membres
                </span>
            @endforeach
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 pb-32">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Premium Floating -->
    <nav class="fixed bottom-6 left-6 right-6 z-[100] max-w-sm mx-auto">
        <div class="bg-slate-900/95 nav-blur rounded-[32px] p-2 premium-shadow border border-white/5 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex-1 flex flex-col items-center py-3 rounded-2xl transition {{ $currentRoute === 'dashboard' ? 'bg-white/10 text-emerald-400' : 'text-gray-500' }}">
                <i class="fas fa-house text-xs mb-1"></i>
                <span class="text-[10px] font-bold">Accueil</span>
            </a>
            <a href="{{ route('products') }}" class="flex-1 flex flex-col items-center py-3 rounded-2xl transition {{ $currentRoute === 'products' ? 'bg-white/10 text-emerald-400' : 'text-gray-500' }}">
                <i class="fas fa-microchip text-xs mb-1"></i>
                <span class="text-[10px] font-bold">Produits</span>
            </a>
            <a href="{{ route('luckywheel') }}" class="flex-1 flex flex-col items-center py-3 rounded-2xl transition {{ $currentRoute === 'luckywheel' ? 'bg-white/10 text-emerald-400' : 'text-gray-500' }}">
                <i class="fas fa-dharmachakra text-xs mb-1"></i>
                <span class="text-[10px] font-bold">Lucky</span>
            </a>
            <a href="{{ route('share') }}" class="flex-1 flex flex-col items-center py-3 rounded-2xl transition {{ $currentRoute === 'share' ? 'bg-white/10 text-emerald-400' : 'text-gray-500' }}">
                <i class="fas fa-users text-xs mb-1"></i>
                <span class="text-[10px] font-bold">Équipe</span>
            </a>
            <a href="{{ route('profile') }}" class="flex-1 flex flex-col items-center py-3 rounded-2xl transition {{ $currentRoute === 'profile' ? 'bg-white/10 text-emerald-400' : 'text-gray-500' }}">
                <i class="fas fa-user-astronaut text-xs mb-1"></i>
                <span class="text-[10px] font-bold">Profil</span>
            </a>
        </div>
    </nav>

    <!-- Notifications Hub -->
    @if(session('success'))
    <div class="fixed top-20 left-1/2 -translate-x-1/2 z-[200] w-full max-w-xs animate__animated animate__fadeInDown">
        <div class="bg-emerald-500 text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-4 border border-white/20">
            <i class="fas fa-check-circle"></i>
            <span class="text-[11px] font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-20 left-1/2 -translate-x-1/2 z-[200] w-full max-w-xs animate__animated animate__shakeX">
        <div class="bg-slate-900 text-red-400 px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-4 border border-red-400/20">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="text-[11px] font-bold">{{ session('error') }}</span>
        </div>
    </div>
    @endif

</body>
</html>