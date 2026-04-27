<x-layouts :title="'Assistance'">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-10 pb-20">

    <!-- Header Support Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl">
        <div class="relative z-10 text-center">
            <div class="w-16 h-16 bg-emerald-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-md border border-white/10">
                <i class="fas fa-headset text-2xl text-emerald-400"></i>
            </div>
            <h1 class="text-2xl font-bold">Assistance Client</h1>
            <p class="text-[10px] font-semibold text-gray-400 mt-2">Réponse moyenne en moins de 15min</p>
        </div>
        <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Social Hub Sleeker -->
    <div class="space-y-4">
        <h3 class="text-[10px] font-bold text-gray-400 px-2">Canaux Officiels</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="https://t.me/+MBOmbS0qokZkMmY8" target="_blank" 
               class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex flex-col items-center text-center hover:bg-slate-50 transition active:scale-95">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fab fa-telegram-plane text-xl"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-800">Telegram</p>
                <p class="text-[9px] font-medium text-gray-400 mt-1">Communauté</p>
            </a>
            <a href="https://chat.whatsapp.com/JHIsnbvCzw43KssWzSJ1Qr?mode=gi_t" target="_blank"
               class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 flex flex-col items-center text-center hover:bg-slate-50 transition active:scale-95">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fab fa-whatsapp text-xl"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-800">WhatsApp</p>
                <p class="text-[9px] font-medium text-gray-400 mt-1">Canal Info</p>
            </a>
        </div>
    </div>

    <!-- Direct Support Sleeker -->
    <div class="space-y-4">
        <h3 class="text-[10px] font-bold text-gray-400 px-2">Assistance Directe</h3>
        
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                        <i class="fas fa-user-tie text-xs text-gray-400"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Service Client</p>
                        <p class="text-[9px] font-medium text-gray-400">Support Général & Aide</p>
                    </div>
                </div>
                <a href="https://wa.me/2376686812801" target="_blank" class="bg-slate-900 text-white text-[10px] font-bold px-4 py-2 rounded-xl active:scale-95 transition">
                    Contact 1
                </a>
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                        <i class="fas fa-receipt text-xs text-gray-400"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Service RH & Finance</p>
                        <p class="text-[9px] font-medium text-gray-400">Questions de recrutement</p>
                    </div>
                </div>
                <a href="https://wa.me/237689910071" target="_blank" class="bg-emerald-600 text-white text-[10px] font-bold px-4 py-2 rounded-xl active:scale-95 transition">
                    Contact 2
                </a>
            </div>
        </div>
    </div>

    <!-- Meta Info Sleeker -->
    <div class="bg-slate-50 rounded-[32px] p-8 border border-gray-100">
        <div class="grid grid-cols-2 gap-8">
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-gray-400">Email</p>
                <p class="text-[10px] font-bold text-gray-800">contact@bioenergy01.com</p>
            </div>
            <div class="space-y-1 text-right">
                <p class="text-[9px] font-bold text-gray-400">Siège Social</p>
                <p class="text-[10px] font-bold text-gray-800">Delaware, USA</p>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-200 text-center">
            <p class="text-[9px] font-medium text-gray-300 leading-relaxed">
                BioEnergy • Tech for good • Sustainable investment
            </p>
        </div>
    </div>
</div>
</x-layouts>
