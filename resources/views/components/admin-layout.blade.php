<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin • {{ $title ?? 'Tableau de bord' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind + Flowbite (pour les menus, modals, etc.) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        :root {
            --admin-primary: #059669;
            --admin-primary-dark: #047857;
        }
        .sidebar-link:hover {
            background: rgba(5, 150, 105, 0.1);
            color: var(--admin-primary);
        }
        .sidebar-link.active {
            background: rgba(5, 150, 105, 0.15);
            color: var(--admin-primary);
            border-left: 4px solid var(--admin-primary);
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Sidebar Mobile + Desktop -->
    <div class="fixed inset-0 z-50 pointer-events-none lg:pointer-events-auto">
        <div id="sidebar" class="pointer-events-auto w-64 h-full bg-white shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-center h-20 bg-gradient-to-r from-emerald-600 to-teal-700 text-white">
                <i class="fas fa-cogs text-3xl mr-3"></i>
                <span class="text-2xl font-extrabold">Admin</span>
            </div>

            <nav class="mt-8 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link flex items-center gap-4 py-4 px-5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home text-xl"></i>
                    <span>Tableau de bord</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link flex items-center gap-4 py-4 px-5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users text-xl"></i>
                    <span>Utilisateurs</span>
                </a>

                <a href="{{ route('admin.transactions') }}"
                   class="sidebar-link flex items-center gap-4 py-4 px-5 rounded-xl {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt text-xl"></i>
                    <span>Transactions</span>
                </a>

                <a href="{{ route('admin.bonus.index') }}"
                   class="sidebar-link flex items-center gap-4 py-4 px-5 rounded-xl {{ request()->routeIs('admin.bonus*') ? 'active' : '' }}">
                    <i class="fas fa-gift text-xl"></i>
                    <span>Codes Bonus</span>
                </a>

                <a href="{{ route('admin.emploi.index') }}"
                   class="sidebar-link flex items-center gap-4 py-4 px-5 rounded-xl {{ request()->routeIs('admin.emploi*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase text-xl"></i>
                    <span>Gestion Emploi</span>
                </a>

                <div class="border-t border-gray-200 my-6"></div>

                <form action="{{ route('logout') }}" method="POST" class="px-5">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-4 py-4 px-5 rounded-xl text-red-600 hover:bg-red-50 transition font-medium">
                        <i class="fas fa-sign-out-alt text-xl"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Overlay mobile -->
        <div id="overlay" class="fixed inset-0 bg-black/50 lg:hidden hidden" onclick="closeSidebar()"></div>
    </div>

    <!-- Topbar Mobile + Bouton Burger -->
    <header class="lg:hidden bg-white shadow-md fixed top-0 left-0 right-0 z-40 flex items-center justify-between px-5 py-4">
        <button onclick="toggleSidebar()" class="text-2xl text-gray-700">
            <i class="fas fa-bars"></i>
        </button>
        <div class="text-xl font-bold text-emerald-600">Admin Panel</div>
        <div class="w-10"></div> <!-- espace pour centrer -->
    </header>

    <!-- Contenu principal -->
    <main class="flex-1 lg:ml-64 pt-20 lg:pt-10 pb-10 px-4 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Messages flash -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl text-center font-medium shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl text-center font-medium shadow">
                    {{ session('error') }}
                </div>
            </div>
            @endif

            <!-- Injection du contenu -->
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="lg:ml-64 bg-gradient-to-r from-emerald-600 to-teal-700 text-white text-center py-5 mt-auto">
        <p class="text-sm opacity-90">
            © {{ date('Y') }} <strong>BioEnergy</strong> • Panel Administrateur
        </p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('overlay').classList.toggle('hidden');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('overlay').classList.add('hidden');
        }

        // Ferme le menu mobile si on clique dehors
        document.getElementById('overlay').addEventListener('click', closeSidebar);
    </script>
</body>
</html>