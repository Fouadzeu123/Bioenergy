<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin • {{ $title ?? 'Tableau de bord' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --admin-bg:       #07090f;
            --admin-card:     #0d1117;
            --admin-border:   rgba(255,255,255,0.06);
            --admin-accent:   #3b82f6;
            --admin-accent2:  #06b6d4;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--admin-bg);
            color: #e2e8f0;
            min-height: 100vh;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(59,130,246,0.10) 0%, transparent 70%),
                radial-gradient(ellipse 40% 30% at 90% 90%, rgba(6,182,212,0.06) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }
        .sidebar {
            background: #0b0e16;
            border-right: 1px solid var(--admin-border);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            color: #4b5563;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .sidebar-link:hover {
            background: rgba(59,130,246,0.08);
            color: #60a5fa;
        }
        .sidebar-link.active {
            background: rgba(59,130,246,0.15);
            color: #60a5fa;
            border-left: 3px solid #3b82f6;
            padding-left: 13px;
            font-weight: 600;
        }
        .sidebar-link i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #374151;
            padding: 0 16px;
            margin: 16px 0 6px;
        }
        .stat-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.4);
        }
        .admin-table th {
            background: rgba(255,255,255,0.02);
            font-size: 11px;
            font-weight: 600;
            color: #4b5563;
            letter-spacing: 0.04em;
            padding: 12px 16px;
            border-bottom: 1px solid var(--admin-border);
        }
        .admin-table td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            font-size: 13px;
            color: #d1d5db;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(255,255,255,0.02); }
        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-success { background: rgba(6,182,212,0.15); color: #22d3ee; border: 1px solid rgba(6,182,212,0.25); }
        .badge-warning { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
        .badge-danger  { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
        .badge-gray    { background: rgba(107,114,128,0.15); color: #9ca3af; border: 1px solid rgba(107,114,128,0.2); }
        .input-dark {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            color: #e2e8f0;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 500;
            width: 100%;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-dark:focus { border-color: rgba(59,130,246,0.5); }
        .btn-primary-admin {
            background: linear-gradient(135deg, #2563eb, #0891b2);
            color: white;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 0 16px rgba(59,130,246,0.25);
        }
        .btn-primary-admin:hover { box-shadow: 0 0 24px rgba(59,130,246,0.45); transform: translateY(-1px); }
        .btn-danger-admin {
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            padding: 7px 16px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-danger-admin:hover { background: rgba(239,68,68,0.2); }
        .card-admin {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 18px;
        }
        .topbar-admin {
            background: rgba(7,9,15,0.9);
            border-bottom: 1px solid var(--admin-border);
            backdrop-filter: blur(20px);
        }
        main { position: relative; z-index: 1; }
    </style>
</head>
<body>

    <!-- Sidebar Desktop -->
    <aside class="sidebar fixed top-0 left-0 h-full w-60 z-[90] hidden lg:flex flex-col">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 16px rgba(59,130,246,0.4);">
                <i class="fas fa-shield-halved text-white text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-white leading-tight">BioEnergy</p>
                <p style="font-size: 10px; color: #374151; font-weight: 600;">Administration</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <p class="sidebar-section">Vue Générale</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> <span>Tableau de bord</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> <span>Utilisateurs</span>
            </a>
            <a href="{{ route('admin.transactions') }}" class="sidebar-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i> <span>Transactions</span>
            </a>

            <p class="sidebar-section">Catalogue</p>
            <a href="{{ route('admin.produits.index') }}" class="sidebar-link {{ request()->routeIs('admin.produits*') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i> <span>Produits</span>
            </a>
            <a href="{{ route('admin.preservation.index') }}" class="sidebar-link {{ request()->routeIs('admin.preservation*') ? 'active' : '' }}">
                <i class="fas fa-vault"></i> <span>Fonds Préservation</span>
            </a>
            <a href="{{ route('admin.emploi.index') }}" class="sidebar-link {{ request()->routeIs('admin.emploi*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> <span>Programme Emploi</span>
            </a>

            <p class="sidebar-section">Outils</p>
            <a href="{{ route('admin.bonus.index') }}" class="sidebar-link {{ request()->routeIs('admin.bonus*') ? 'active' : '' }}">
                <i class="fas fa-gift"></i> <span>Codes Bonus</span>
            </a>

            <div style="height: 1px; background: rgba(255,255,255,0.06); margin: 16px 0;"></div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left" style="color: #f87171;">
                    <i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Topbar Mobile -->
    <header class="topbar-admin lg:hidden fixed top-0 left-0 right-0 z-40 flex items-center justify-between px-5 h-14">
        <button onclick="toggleSidebar()" class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
            <i class="fas fa-bars text-gray-400 text-sm"></i>
        </button>
        <span class="text-sm font-bold text-white">Admin Panel</span>
        <div class="w-9"></div>
    </header>

    <!-- Sidebar Mobile overlay -->
    <div id="sidebarMobile" class="fixed inset-0 z-[95] hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeSidebar()"></div>
        <aside class="sidebar absolute left-0 top-0 h-full w-60 flex flex-col animate__animated animate__slideInLeft animate__faster">
            <div class="flex items-center gap-3 px-6 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #2563eb, #0891b2);">
                    <i class="fas fa-shield-halved text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">BioEnergy</p>
                    <p style="font-size: 10px; color: #374151; font-weight: 600;">Administration</p>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <p class="sidebar-section">Vue Générale</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                    <i class="fas fa-chart-line"></i> <span>Tableau de bord</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" onclick="closeSidebar()">
                    <i class="fas fa-users"></i> <span>Utilisateurs</span>
                </a>
                <a href="{{ route('admin.transactions') }}" class="sidebar-link" onclick="closeSidebar()">
                    <i class="fas fa-receipt"></i> <span>Transactions</span>
                </a>
                <p class="sidebar-section">Catalogue</p>
                <a href="{{ route('admin.produits.index') }}" class="sidebar-link {{ request()->routeIs('admin.produits*') ? 'active' : '' }}" onclick="closeSidebar()">
                    <i class="fas fa-microchip"></i> <span>Produits</span>
                </a>
                <a href="{{ route('admin.preservation.index') }}" class="sidebar-link {{ request()->routeIs('admin.preservation*') ? 'active' : '' }}" onclick="closeSidebar()">
                    <i class="fas fa-vault"></i> <span>Fonds Préservation</span>
                </a>
                <a href="{{ route('admin.emploi.index') }}" class="sidebar-link {{ request()->routeIs('admin.emploi*') ? 'active' : '' }}" onclick="closeSidebar()">
                    <i class="fas fa-briefcase"></i> <span>Programme Emploi</span>
                </a>
                <p class="sidebar-section">Outils</p>
                <a href="{{ route('admin.bonus.index') }}" class="sidebar-link {{ request()->routeIs('admin.bonus*') ? 'active' : '' }}" onclick="closeSidebar()">
                    <i class="fas fa-gift"></i> <span>Codes Bonus</span>
                </a>
                <div style="height: 1px; background: rgba(255,255,255,0.06); margin: 16px 0;"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link w-full text-left" style="color: #f87171;">
                        <i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span>
                    </button>
                </form>
            </nav>
        </aside>
    </div>

    <!-- Main Content -->
    <main class="lg:ml-60 pt-14 lg:pt-0 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-5 px-5 py-3.5 rounded-2xl flex items-center gap-3 animate__animated animate__fadeInDown" style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.25);">
                    <i class="fas fa-check-circle text-cyan-400 text-sm"></i>
                    <span style="font-size: 13px; font-weight: 500; color: #22d3ee;">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 px-5 py-3.5 rounded-2xl flex items-center gap-3 animate__animated animate__shakeX" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                    <i class="fas fa-exclamation-circle text-red-400 text-sm"></i>
                    <span style="font-size: 13px; font-weight: 500; color: #f87171;">{{ session('error') }}</span>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebarMobile').classList.toggle('hidden');
        }
        function closeSidebar() {
            document.getElementById('sidebarMobile').classList.add('hidden');
        }
    </script>
</body>
</html>