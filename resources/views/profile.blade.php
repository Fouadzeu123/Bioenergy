<x-layouts :title="'Mon Profil'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8 pb-20">

    <!-- Header Profil Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl">
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl bg-emerald-500 flex items-center justify-center text-white text-3xl font-bold shadow-xl border border-white/10">
                {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
            </div>
            <div class="space-y-1">
                <h1 class="text-2xl font-bold leading-tight">{{ Auth::user()->username }}</h1>
                <p class="text-[10px] font-bold text-gray-400">{{ Auth::user()->phone ? substr(Auth::user()->phone,0,3).'****'.substr(Auth::user()->phone,-3) : 'Compte vérifié' }}</p>
                <div class="flex items-center gap-3 pt-2">
                    <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-[10px] font-bold">VIP {{ Auth::user()->level }}</span>
                    <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-gray-400 hover:text-white transition">
                        <i class="fas fa-cog text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Portefeuille Sleeker -->
    <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
        <div class="p-8 text-center bg-gray-50/30">
            <p class="text-[11px] font-bold text-gray-400 mb-2">Actifs consolidés</p>
            <h2 class="text-4xl font-bold text-gray-800 tracking-tight">{{ fmtCurrency($solde_total) }}</h2>
        </div>
        
        <div class="grid grid-cols-2 border-t border-gray-50 divide-x divide-gray-50">
            <div class="p-6 text-center">
                <p class="text-[10px] font-bold text-gray-400 mb-1">Gains cumulés</p>
                <p class="text-xs font-bold text-emerald-600">{{ fmtCurrency($revenu_total) }}</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-[10px] font-bold text-gray-400 mb-1">Total Retraits</p>
                <p class="text-xs font-bold text-red-400">{{ fmtCurrency($total_retraits) }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 border-t border-gray-50 divide-x divide-gray-50">
            <div class="p-6 text-center">
                <p class="text-[10px] font-bold text-gray-400 mb-1">Équipe Active</p>
                <p class="text-xs font-bold text-blue-600">{{ $taille_equipe }} pers.</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-[10px] font-bold text-gray-400 mb-1">Rendement / j</p>
                <p class="text-xs font-bold text-amber-500">{{ fmtCurrency($capturer_benefices) }}</p>
            </div>
        </div>
    </div>

    <!-- Menu Navigation Sleeker -->
    <div class="bg-white rounded-[32px] shadow-sm border border-gray-50 overflow-hidden">
        @php
            $menuItems = [
                ['route' => 'withdraw_info', 'icon' => 'credit-card', 'label' => 'Configuration Retrait', 'color' => 'emerald'],
                ['route' => 'transaction', 'icon' => 'receipt', 'label' => 'Historique Transactions', 'color' => 'blue'],
                ['route' => 'team', 'icon' => 'users', 'label' => 'Réseau & Filleuls', 'color' => 'purple'],
                ['route' => 'share', 'icon' => 'share-nodes', 'label' => 'Inviter des amis', 'color' => 'indigo'],
            ];
        @endphp

        @foreach($menuItems as $item)
            <a href="{{ route($item['route']) }}" class="flex items-center justify-between p-6 hover:bg-slate-50 transition border-b border-gray-50 last:border-0 group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-{{ $item['color'] }}-50 text-{{ $item['color'] }}-600 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-{{ $item['icon'] }} text-xs"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-700">{{ $item['label'] }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-[10px] group-hover:translate-x-1 transition"></i>
            </a>
        @endforeach
    </div>

    <!-- Déconnexion Sleeker -->
    <form action="{{ route('logout') }}" method="POST" class="pt-4">
        @csrf
        <button type="submit" class="w-full py-5 text-[11px] font-bold text-red-400 border border-red-100 rounded-2xl hover:bg-red-50 transition active:scale-95">
            Déconnexion sécurisée
        </button>
    </form>
</div>
</x-layouts>