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

    <!-- Nouveau Message Contact via Parrain -->
    <div class="space-y-6">
        <div class="rounded-[2rem] p-8 text-center space-y-6" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div class="w-20 h-20 rounded-3xl flex items-center justify-center mx-auto" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                <i class="fas fa-users text-3xl text-blue-400"></i>
            </div>
            <div class="space-y-2">
                <h2 class="text-xl font-bold text-white">Assistance Via Parrain</h2>
                <p class="text-xs leading-relaxed text-gray-400 px-4">
                    Pour toute question, assistance technique ou demande de bonus, veuillez contacter directement votre <span class="text-blue-400 font-bold">parrain</span> ou la personne qui vous a invité sur la plateforme.
                </p>
            </div>
            
            @php
                $parrain = Auth::user()->parrain;
            @endphp

            @if($parrain)
                <div class="pt-4">
                    <div class="rounded-2xl p-5 flex items-center justify-between text-left" style="background: rgba(59,130,246,0.04); border: 1px solid rgba(59,130,246,0.1);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.12);">
                                <i class="fas fa-phone-alt text-xs text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-[12px] font-semibold text-white">Votre Parrain</p>
                                <p class="text-[10px] font-medium" style="color: #4b5563;">+{{ $parrain->country_code }} {{ $parrain->phone }}</p>
                            </div>
                        </div>
                        <a href="https://wa.me/{{ $parrain->country_code }}{{ ltrim($parrain->phone, '0') }}" target="_blank" 
                           class="text-white text-[11px] font-bold px-4 py-2 rounded-xl active:scale-95 transition" 
                           style="background: linear-gradient(135deg, #10b981, #059669);">
                            WhatsApp
                        </a>
                    </div>
                </div>
            @else
                <div class="p-4 rounded-2xl bg-blue-500/5 border border-blue-500/10">
                    <p class="text-[11px] text-blue-300 font-medium italic">
                        "Votre parrain est votre guide privilégié pour une expérience optimale chez BioEnergy."
                    </p>
                </div>
            @endif
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
