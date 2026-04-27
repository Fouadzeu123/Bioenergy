<x-layouts :title="'Présentation'">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-12 pb-20">

    <!-- Hero Présentation Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl">
        <div class="relative z-10 text-center">
            <div class="w-20 h-20 bg-emerald-500/20 rounded-[30px] flex items-center justify-center mx-auto mb-6 backdrop-blur-md border border-white/10">
                <i class="fas fa-leaf text-3xl text-emerald-400"></i>
            </div>
            <h1 class="text-3xl font-bold">BioEnergy</h1>
            <p class="text-[10px] font-semibold text-gray-400 mt-2">Clean Tech • Real Yield</p>
        </div>
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Mission Sleeker -->
    <div class="space-y-4 px-2">
        <h2 class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Notre Mission</h2>
        <p class="text-xl font-bold text-gray-800 leading-tight">Accélérer la transition énergétique par l'investissement participatif.</p>
        <p class="text-[11px] font-medium text-gray-500 leading-relaxed">
            BioEnergy développe et finance des projets d'énergies renouvelables (solaire, biomasse, éolien) 
            en associant impact environnemental et rendement financier pour nos partenaires.
        </p>
    </div>

    <!-- Stats Grid Sleeker -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50">
            <p class="text-[9px] font-bold text-gray-400 mb-1">Projets</p>
            <p class="text-2xl font-bold text-gray-800">48 <span class="text-[10px] opacity-20">unités</span></p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 text-right">
            <p class="text-[9px] font-bold text-gray-400 mb-1">Impact CO₂</p>
            <p class="text-2xl font-bold text-emerald-600">-9.2k <span class="text-[10px] opacity-30">tonnes</span></p>
        </div>
    </div>

    <!-- Timeline Sleeker -->
    <div class="space-y-8 px-2">
        <h2 class="text-[10px] font-bold text-gray-400">Notre Parcours</h2>
        <div class="space-y-10 relative">
            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-50"></div>
            
            <div class="relative pl-12">
                <div class="absolute left-3 w-2.5 h-2.5 bg-emerald-500 rounded-full border-4 border-white shadow-sm"></div>
                <p class="text-[10px] font-bold text-emerald-600 mb-1">2016 • Fondation</p>
                <p class="text-[11px] font-medium text-gray-500 leading-relaxed">Création de l'entité par un groupe d'ingénieurs visionnaires.</p>
            </div>

            <div class="relative pl-12">
                <div class="absolute left-3 w-2.5 h-2.5 bg-gray-200 rounded-full border-4 border-white"></div>
                <p class="text-[10px] font-bold text-gray-800 mb-1">2018 • Solaire</p>
                <p class="text-[11px] font-medium text-gray-500 leading-relaxed">Mise en service du premier parc solaire communautaire.</p>
            </div>

            <div class="relative pl-12">
                <div class="absolute left-3 w-2.5 h-2.5 bg-gray-200 rounded-full border-4 border-white"></div>
                <p class="text-[10px] font-bold text-gray-800 mb-1">2022 • Plateforme</p>
                <p class="text-[11px] font-medium text-gray-500 leading-relaxed">Ouverture de la plateforme digitale d'investissement.</p>
            </div>
        </div>
    </div>

    <!-- Contact Card Sleeker -->
    <div class="bg-white rounded-[40px] p-10 shadow-sm border border-gray-50 space-y-8 text-center">
        <h2 class="text-[10px] font-bold text-gray-400">Restons Connectés</h2>
        <div class="space-y-2">
            <p class="text-sm font-bold text-gray-800">contact@bioenergy.com</p>
            <p class="text-[10px] font-medium text-gray-400">+1 (232) 2781 5376</p>
        </div>
        <p class="text-[9px] font-medium text-gray-300 leading-relaxed max-w-[200px] mx-auto">
            7440 E Pinnacle Peak Rd, Scottsdale, AZ 85255
        </p>
        <div class="pt-4">
            <a href="{{ route('contact') }}" class="inline-block bg-slate-900 text-white text-[11px] font-bold px-8 py-4 rounded-2xl shadow-xl active:scale-95 transition">
                Nous Contacter
            </a>
        </div>
    </div>
</div>
</x-layouts>