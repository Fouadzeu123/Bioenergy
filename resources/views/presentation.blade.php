<x-layouts :title="'Présentation'">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-8 pb-24">

    <!-- Hero Présentation -->
    <div class="relative overflow-hidden rounded-[2rem] p-8 text-white text-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #1e3a8a 50%, #0e7490 100%); box-shadow: 0 0 50px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <div class="w-20 h-20 rounded-[1.5rem] flex items-center justify-center mx-auto mb-5" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                <i class="fas fa-leaf text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold">BioEnergy</h1>
            <p class="text-[11px] font-medium mt-2" style="color: rgba(147,197,253,0.7);">Clean Tech • Real Yield</p>
        </div>
        <div class="absolute -right-12 -bottom-12 w-48 h-48 rounded-full" style="background: rgba(6,182,212,0.1); filter: blur(30px);"></div>
        <div class="absolute -left-12 -top-12 w-40 h-40 rounded-full" style="background: rgba(99,102,241,0.1); filter: blur(30px);"></div>
    </div>

    <!-- Mission -->
    <div class="space-y-3 px-1">
        <p class="text-[11px] font-bold text-blue-400 tracking-wide">Notre Mission</p>
        <p class="text-xl font-bold text-white leading-tight">Accélérer la transition énergétique par l'investissement participatif.</p>
        <p class="text-[12px] font-medium leading-relaxed" style="color: #6b7280;">
            BioEnergy développe et finance des projets d'énergies renouvelables (solaire, biomasse, éolien)
            en associant impact environnemental et rendement financier pour nos partenaires.
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl p-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Projets</p>
            <p class="text-2xl font-bold text-white">48 <span class="text-[11px] font-medium opacity-30">unités</span></p>
        </div>
        <div class="rounded-2xl p-5 text-right" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Impact CO₂</p>
            <p class="text-2xl font-bold text-cyan-400">-9.2k <span class="text-[11px] font-medium opacity-40">tonnes</span></p>
        </div>
    </div>

    <!-- Timeline -->
    <div class="space-y-5 px-1">
        <h2 class="text-[12px] font-semibold" style="color: #4b5563;">Notre Parcours</h2>

        <div class="relative space-y-6">
            <!-- Ligne verticale -->
            <div class="absolute left-3 top-2 bottom-2 w-px" style="background: rgba(59,130,246,0.2);"></div>

            <div class="relative pl-10">
                <div class="absolute left-2 w-3 h-3 rounded-full" style="background: #2563eb; box-shadow: 0 0 10px rgba(59,130,246,0.5); top: 3px;"></div>
                <p class="text-[11px] font-bold text-blue-400 mb-1">2016 • Fondation</p>
                <p class="text-[12px] font-medium leading-relaxed" style="color: #6b7280;">Création de l'entité par un groupe d'ingénieurs visionnaires.</p>
            </div>

            <div class="relative pl-10">
                <div class="absolute left-2 w-3 h-3 rounded-full" style="background: rgba(59,130,246,0.3); border: 1px solid rgba(59,130,246,0.4); top: 3px;"></div>
                <p class="text-[11px] font-bold text-gray-400 mb-1">2018 • Solaire</p>
                <p class="text-[12px] font-medium leading-relaxed" style="color: #6b7280;">Mise en service du premier parc solaire communautaire.</p>
            </div>

            <div class="relative pl-10">
                <div class="absolute left-2 w-3 h-3 rounded-full" style="background: rgba(59,130,246,0.3); border: 1px solid rgba(59,130,246,0.4); top: 3px;"></div>
                <p class="text-[11px] font-bold text-gray-400 mb-1">2022 • Plateforme</p>
                <p class="text-[12px] font-medium leading-relaxed" style="color: #6b7280;">Ouverture de la plateforme digitale d'investissement.</p>
            </div>
        </div>
    </div>

    <!-- Contact Card -->
    <div class="rounded-2xl p-7 text-center space-y-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
        <p class="text-[12px] font-semibold" style="color: #4b5563;">Restons Connectés</p>
        <div class="space-y-1">
            <p class="text-sm font-bold text-white">contact@bioenergy.com</p>
            <p class="text-[11px] font-medium" style="color: #4b5563;">+1 (232) 2781 5376</p>
        </div>
        <p class="text-[11px] font-medium leading-relaxed" style="color: #374151;">
            7440 E Pinnacle Peak Rd, Scottsdale, AZ 85255
        </p>
        <a href="{{ route('contact') }}" class="inline-block text-white text-[12px] font-bold px-8 py-3.5 rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 20px rgba(59,130,246,0.25);">
            Nous Contacter
        </a>
    </div>
</div>
</x-layouts>