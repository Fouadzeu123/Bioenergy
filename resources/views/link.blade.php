<x-layouts :title="'Lien d\'invitation'">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Hero Parrainage -->
    <div class="relative overflow-hidden rounded-[2rem] p-8 text-white text-center" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <i class="fas fa-users-crown text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold">Faites grandir votre réseau</h1>
            <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Partagez • Gagnez • Progressez</p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Carte Lien -->
    <div class="rounded-2xl p-6 space-y-6" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
        <div class="text-center">
            <p class="text-[11px] font-semibold mb-4" style="color: #4b5563;">Votre lien exclusif</p>
            <div class="rounded-2xl p-5 relative cursor-pointer active:scale-95 transition border border-dashed" style="background: rgba(59,130,246,0.06); border-color: rgba(59,130,246,0.2);" onclick="copyReferralLink()">
                <p id="refLink" class="text-xs font-semibold text-blue-400 break-all select-all">{{ $refUrl }}</p>
                <div id="copyMsg" class="absolute inset-0 rounded-2xl flex items-center justify-center text-white text-[11px] font-bold hidden animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #2563eb, #0891b2);">
                    <i class="fas fa-check mr-2"></i>Lien copié !
                </div>
            </div>
        </div>

        <!-- Social Share -->
        <div class="grid grid-cols-4 gap-4">
            <a href="https://wa.me/?text={{ urlencode('Rejoins BioEnergy et gagne tous les jours ! 👉 ' . $refUrl) }}" target="_blank" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center active:scale-90 transition" style="background: rgba(37,211,102,0.12); border: 1px solid rgba(37,211,102,0.2);">
                    <i class="fab fa-whatsapp text-lg text-green-400"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #4b5563;">WhatsApp</span>
            </a>
            <a href="https://t.me/share/url?url={{ urlencode($refUrl) }}&text={{ urlencode('Rejoins BioEnergy !') }}" target="_blank" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center active:scale-90 transition" style="background: rgba(0,136,204,0.12); border: 1px solid rgba(0,136,204,0.2);">
                    <i class="fab fa-telegram-plane text-lg text-sky-400"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #4b5563;">Telegram</span>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($refUrl) }}" target="_blank" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center active:scale-90 transition" style="background: rgba(59,89,152,0.12); border: 1px solid rgba(59,89,152,0.2);">
                    <i class="fab fa-facebook-f text-lg text-indigo-400"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #4b5563;">Facebook</span>
            </a>
            <button onclick="copyReferralLink()" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center active:scale-90 transition" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                    <i class="fas fa-copy text-lg text-blue-400"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #4b5563;">Copier</span>
            </button>
        </div>
    </div>

    <!-- Mini Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl p-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Code D'invitation</p>
            <p class="text-xl font-bold text-white">{{ Auth::user()->invitation_code }}</p>
        </div>
        <div class="rounded-2xl p-5 text-right" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Total Réseau</p>
            <p class="text-xl font-bold text-blue-400">{{ $total }} <span class="text-[10px] font-medium opacity-40">pers.</span></p>
        </div>
    </div>

    <!-- Commissions -->
    <div class="rounded-2xl p-6 space-y-4" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
        <h3 class="text-sm font-bold text-center text-blue-400">Parrainage a 3 niveaux</h3>
        <div class="space-y-3">
            <div class="flex items-center gap-4 rounded-2xl p-4" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.12);">
                <div class="text-2xl font-bold text-blue-400 w-12 text-center flex-shrink-0">10%</div>
                <p class="text-[11px] font-medium leading-relaxed" style="color: #6b7280;">Sur le premier investissement de vos filleuls directs (niveau 1)</p>
            </div>
            <div class="flex items-center gap-4 rounded-2xl p-4" style="background: rgba(6,182,212,0.06); border: 1px solid rgba(6,182,212,0.12);">
                <div class="text-2xl font-bold text-cyan-400 w-12 text-center flex-shrink-0">3%</div>
                <p class="text-[11px] font-medium leading-relaxed" style="color: #6b7280;">Sur le premier investissement de vos filleuls niveau 2</p>
            </div>
            <div class="flex items-center gap-4 rounded-2xl p-4" style="background: rgba(99,102,241,0.06); border: 1px solid rgba(99,102,241,0.12);">
                <div class="text-2xl font-bold text-indigo-400 w-12 text-center flex-shrink-0">1%</div>
                <p class="text-[11px] font-medium leading-relaxed" style="color: #6b7280;">Sur le premier investissement de vos filleuls niveau 3</p>
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const link = document.getElementById('refLink').innerText;
    navigator.clipboard.writeText(link);
    const msg = document.getElementById('copyMsg');
    msg.classList.remove('hidden');
    setTimeout(() => msg.classList.add('hidden'), 2000);
}
</script>
</x-layouts>
