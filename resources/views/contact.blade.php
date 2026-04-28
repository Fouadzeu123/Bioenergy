<x-layouts :title="'Assistance'">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Header Support -->
    <div class="relative overflow-hidden rounded-[2rem] p-8 text-white text-center" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <i class="fas fa-headset text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold">Assistance Client</h1>
            <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Réponse en moins de 15 minutes</p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Canaux Officiels -->
    <div class="space-y-3">
        <h3 class="text-[12px] font-semibold px-1" style="color: #4b5563;">Canaux Officiels</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="https://t.me/+MBOmbS0qokZkMmY8" target="_blank"
               class="rounded-2xl p-5 flex flex-col items-center text-center active:scale-95 transition hover:border-sky-500/30" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: rgba(0,136,204,0.12); border: 1px solid rgba(0,136,204,0.2);">
                    <i class="fab fa-telegram-plane text-xl text-sky-400"></i>
                </div>
                <p class="text-[12px] font-semibold text-white">Telegram</p>
                <p class="text-[10px] font-medium mt-0.5" style="color: #4b5563;">Communauté</p>
            </a>
            <a href="https://chat.whatsapp.com/JHIsnbvCzw43KssWzSJ1Qr?mode=gi_t" target="_blank"
               class="rounded-2xl p-5 flex flex-col items-center text-center active:scale-95 transition hover:border-green-500/20" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: rgba(37,211,102,0.12); border: 1px solid rgba(37,211,102,0.2);">
                    <i class="fab fa-whatsapp text-xl text-green-400"></i>
                </div>
                <p class="text-[12px] font-semibold text-white">WhatsApp</p>
                <p class="text-[10px] font-medium mt-0.5" style="color: #4b5563;">Canal Info</p>
            </a>
        </div>
    </div>

    <!-- Assistance Directe -->
    <div class="space-y-3">
        <h3 class="text-[12px] font-semibold px-1" style="color: #4b5563;">Assistance Directe</h3>

        <div class="rounded-2xl p-5 space-y-4" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.12);">
                        <i class="fas fa-user-tie text-xs text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-[12px] font-semibold text-white">Service Client</p>
                        <p class="text-[10px] font-medium" style="color: #4b5563;">Support Général & Aide</p>
                    </div>
                </div>
                <a href="https://wa.me/2376686812801" target="_blank" class="text-white text-[11px] font-bold px-4 py-2 rounded-xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2);">
                    Contact 1
                </a>
            </div>

            <div class="flex items-center justify-between pt-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(6,182,212,0.12);">
                        <i class="fas fa-receipt text-xs text-cyan-400"></i>
                    </div>
                    <div>
                        <p class="text-[12px] font-semibold text-white">Service RH & Finance</p>
                        <p class="text-[10px] font-medium" style="color: #4b5563;">Questions de recrutement</p>
                    </div>
                </div>
                <a href="https://wa.me/237689910071" target="_blank" class="text-white text-[11px] font-bold px-4 py-2 rounded-xl active:scale-95 transition" style="background: linear-gradient(135deg, #0891b2, #0d9488);">
                    Contact 2
                </a>
            </div>
        </div>
    </div>

    <!-- Meta Info -->
    <div class="rounded-2xl p-5" style="background: rgba(59,130,246,0.04); border: 1px solid rgba(59,130,246,0.1);">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Email</p>
                <p class="text-[11px] font-semibold text-blue-400">contact@bioenergy01.com</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Siège Social</p>
                <p class="text-[11px] font-semibold text-gray-400">Delaware, USA</p>
            </div>
        </div>
        <div class="mt-4 pt-4 text-center" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <p class="text-[10px] font-medium" style="color: #374151;">BioEnergy • Tech for good • Sustainable investment</p>
        </div>
    </div>
</div>
</x-layouts>
