<x-layouts :title="'Mon Profil'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-5 pb-24">

    <!-- Header Profil -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white text-3xl font-bold flex-shrink-0" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
            </div>
            <div class="space-y-1">
                <h1 class="text-2xl font-bold leading-tight">{{ Auth::user()->username }}</h1>
                <p class="text-[11px] font-medium" style="color: rgba(147,197,253,0.7);">{{ Auth::user()->phone ? substr(Auth::user()->phone,0,3).'****'.substr(Auth::user()->phone,-3) : 'Compte vérifié' }}</p>
                <div class="flex items-center gap-3 pt-1">
                    @php
                        $lvl = Auth::user()->level;
                        $vipStyles = [
                            0 => ['bg'=>'rgba(107,114,128,0.2)', 'border'=>'rgba(107,114,128,0.3)', 'text'=>'#9ca3af', 'icon'=>'fa-user',         'label'=>'Membre'],
                            1 => ['bg'=>'rgba(59,130,246,0.2)',  'border'=>'rgba(59,130,246,0.4)',  'text'=>'#60a5fa', 'icon'=>'fa-star',         'label'=>'Bronze'],
                            2 => ['bg'=>'rgba(100,116,139,0.2)', 'border'=>'rgba(148,163,184,0.4)', 'text'=>'#94a3b8', 'icon'=>'fa-medal',        'label'=>'Argent'],
                            3 => ['bg'=>'rgba(234,179,8,0.18)',  'border'=>'rgba(234,179,8,0.35)',  'text'=>'#fbbf24', 'icon'=>'fa-crown',        'label'=>'Or'],
                            4 => ['bg'=>'rgba(99,102,241,0.2)',  'border'=>'rgba(139,92,246,0.4)',  'text'=>'#a78bfa', 'icon'=>'fa-gem',          'label'=>'Platine'],
                            5 => ['bg'=>'rgba(6,182,212,0.2)',   'border'=>'rgba(6,182,212,0.4)',   'text'=>'#22d3ee', 'icon'=>'fa-diamond',      'label'=>'Diamant'],
                        ];
                        $style = $vipStyles[$lvl] ?? $vipStyles[0];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold" style="background: {{ $style['bg'] }}; color: {{ $style['text'] }}; border: 1px solid {{ $style['border'] }};">
                        <i class="fas {{ $style['icon'] }} text-[9px]"></i>
                        VIP {{ $lvl }} &bull; {{ $style['label'] }}
                    </span>
                    <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-xl flex items-center justify-center transition" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fas fa-cog text-xs text-blue-200"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Portefeuille -->
    <div class="rounded-2xl overflow-hidden" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
        <div class="p-6 text-center" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05);">
            <p class="text-[11px] font-semibold mb-2" style="color: #4b5563;">Actifs consolidés</p>
            <h2 class="text-4xl font-bold text-white tracking-tight">{{ fmtCurrency($solde_total) }}</h2>
        </div>

        <div class="grid grid-cols-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div class="p-5 text-center" style="border-right: 1px solid rgba(255,255,255,0.05);">
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Gains cumulés</p>
                <p class="text-sm font-bold text-cyan-400">{{ fmtCurrency($revenu_total) }}</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Total Retraits</p>
                <p class="text-sm font-bold text-red-400">{{ fmtCurrency($total_retraits) }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="p-5 text-center" style="border-right: 1px solid rgba(255,255,255,0.05);">
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Équipe Active</p>
                <p class="text-sm font-bold text-blue-400">{{ $taille_equipe }} pers.</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Rendement / j</p>
                <p class="text-sm font-bold text-amber-400">{{ fmtCurrency($capturer_benefices) }}</p>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="space-y-3">
        <p class="text-[11px] font-semibold px-1" style="color: #374151;">Actions rapides</p>

        <!-- Ligne 1 : Dépôt & Retrait -->
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('deposit') }}" class="flex flex-col items-center justify-center gap-2 py-5 rounded-2xl active:scale-95 transition" style="background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: 0 0 18px rgba(37,99,235,0.4);">
                    <i class="fas fa-download text-white text-sm"></i>
                </div>
                <span class="text-[12px] font-bold text-blue-400">Déposer</span>
            </a>
            <a href="{{ route('retrait') }}" class="flex flex-col items-center justify-center gap-2 py-5 rounded-2xl active:scale-95 transition" style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.25);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #0891b2, #0e7490); box-shadow: 0 0 18px rgba(6,182,212,0.35);">
                    <i class="fas fa-upload text-white text-sm"></i>
                </div>
                <span class="text-[12px] font-bold text-cyan-400">Retirer</span>
            </a>
        </div>

        <!-- Ligne 2 : Lucky & Bonus -->
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('luckywheel') }}" class="flex flex-col items-center justify-center gap-2 py-5 rounded-2xl active:scale-95 transition" style="background: rgba(124,58,237,0.12); border: 1px solid rgba(124,58,237,0.25);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 0 18px rgba(124,58,237,0.35);">
                    <i class="fas fa-dharmachakra text-white text-sm"></i>
                </div>
                <div class="text-center">
                    <span class="text-[12px] font-bold text-violet-400">Lucky Wheel</span>
                    <p class="text-[10px] font-medium" style="color: #4b5563;">{{ Auth::user()->lucky_spins }} tour(s)</p>
                </div>
            </a>
            <a href="{{ route('bonus.code') }}" class="flex flex-col items-center justify-center gap-2 py-5 rounded-2xl active:scale-95 transition" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #d97706, #b45309); box-shadow: 0 0 18px rgba(245,158,11,0.3);">
                    <i class="fas fa-gift text-white text-sm"></i>
                </div>
                <span class="text-[12px] font-bold text-amber-400">Mes Bonus</span>
            </a>
        </div>
    </div>

    <!-- Menu Navigation -->
    <div class="rounded-2xl overflow-hidden" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
        @php
            $menuItems = [
                ['route' => 'products',       'icon' => 'microchip',      'label' => 'Produits d\'investissement', 'color' => 'rgba(16,185,129,0.12)',  'text' => '#34d399'],
                ['route' => 'fond.index',     'icon' => 'vault',           'label' => 'Fonds de Préservation',    'color' => 'rgba(59,130,246,0.12)',   'text' => '#60a5fa'],
                ['route' => 'emploi',         'icon' => 'briefcase',       'label' => 'Programme Emploi',          'color' => 'rgba(139,92,246,0.12)',   'text' => '#a78bfa'],
                ['route' => 'transaction',    'icon' => 'receipt',         'label' => 'Historique Transactions',   'color' => 'rgba(6,182,212,0.12)',    'text' => '#22d3ee'],
                ['route' => 'team',           'icon' => 'users',           'label' => 'Réseau & Filleuls',         'color' => 'rgba(99,102,241,0.12)',   'text' => '#818cf8'],
                ['route' => 'share',          'icon' => 'share-nodes',     'label' => 'Inviter des amis',          'color' => 'rgba(251,191,36,0.12)',   'text' => '#fbbf24'],
                ['route' => 'withdraw_info',  'icon' => 'shield-halved',   'label' => 'Configuration Retrait',     'color' => 'rgba(239,68,68,0.1)',     'text' => '#f87171'],
            ];
        @endphp

        @foreach($menuItems as $i => $item)
            <a href="{{ route($item['route']) }}" class="flex items-center justify-between p-5 transition group" style="{{ $i < count($menuItems)-1 ? 'border-bottom: 1px solid rgba(255,255,255,0.05);' : '' }}">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition" style="background: {{ $item['color'] }};">
                        <i class="fas fa-{{ $item['icon'] }} text-xs" style="color: {{ $item['text'] }};"></i>
                    </div>
                    <span class="text-[12px] font-semibold text-gray-300">{{ $item['label'] }}</span>
                </div>
                <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition" style="color: #374151;"></i>
            </a>
        @endforeach
    </div>

    <!-- Déconnexion -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full py-4 text-[12px] font-semibold rounded-2xl transition active:scale-95" style="color: #f87171; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15);">
            Déconnexion sécurisée
        </button>
    </form>
</div>
</x-layouts>
