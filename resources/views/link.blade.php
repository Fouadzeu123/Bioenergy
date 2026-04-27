<x-layouts :title="'Lien d\'invitation'">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8">

    <!-- Hero Parrainage Sleeker -->
    <div class="relative overflow-hidden rounded-3xl bg-emerald-600 p-8 text-white shadow-xl">
        <div class="relative z-10 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-md border border-white/10">
                <i class="fas fa-users-crown text-2xl"></i>
            </div>
            <h1 class="text-xl font-bold">Faites grandir votre réseau</h1>
            <p class="text-[10px] font-semibold text-emerald-100 mt-2">Partagez • Gagnez • Progressez</p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Carte Lien Sleeker -->
    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-50 space-y-8">
        <div class="text-center">
            <p class="text-[10px] font-bold text-gray-400 mb-4">Votre lien exclusif</p>
            <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-6 relative group active:bg-gray-100 transition" onclick="copyReferralLink()">
                <p id="refLink" class="text-xs font-bold text-emerald-600 break-all select-all">{{ $refUrl }}</p>
                <div id="copyMsg" class="absolute inset-0 bg-emerald-600 flex items-center justify-center rounded-2xl text-white text-[10px] font-bold hidden animate__animated animate__fadeIn">
                    Lien copié !
                </div>
            </div>
        </div>

        <!-- Social Share Grid -->
        <div class="grid grid-cols-4 gap-4">
            <a href="https://wa.me/?text={{ urlencode('Rejoins BioEnergy et gagne tous les jours ! 👉 ' . $refUrl) }}" target="_blank"
               class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center border border-emerald-100 shadow-sm active:scale-90 transition">
                    <i class="fab fa-whatsapp text-lg"></i>
                </div>
                <span class="text-[8px] font-black text-gray-400 uppercase">WhatsApp</span>
            </a>
            <a href="https://t.me/share/url?url={{ urlencode($refUrl) }}&text={{ urlencode('Rejoins BioEnergy et gagne tous les jours !') }}" target="_blank"
               class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100 shadow-sm active:scale-90 transition">
                    <i class="fab fa-telegram-plane text-lg"></i>
                </div>
                <span class="text-[8px] font-black text-gray-400 uppercase">Telegram</span>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($refUrl) }}" target="_blank"
               class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100 shadow-sm active:scale-90 transition">
                    <i class="fab fa-facebook-f text-lg"></i>
                </div>
                <span class="text-[8px] font-black text-gray-400 uppercase">Facebook</span>
            </a>
            <button onclick="copyReferralLink()" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center border border-gray-100 shadow-sm active:scale-90 transition">
                    <i class="fas fa-copy text-lg"></i>
                </div>
                <span class="text-[9px] font-bold text-gray-400">Copier</span>
            </button>
        </div>
    </div>

    <!-- Mini Stats Grid -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50">
            <p class="text-[10px] font-bold text-gray-400 mb-1">Code Promo</p>
            <p class="text-xl font-bold text-gray-800">{{ Auth::user()->invitation_code }}</p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 text-right">
            <p class="text-[10px] font-bold text-gray-400 mb-1">Total Réseau</p>
            <p class="text-xl font-bold text-emerald-600">{{ $total }} <span class="text-[10px] font-medium opacity-30">pers.</span></p>
        </div>
    </div>

    <!-- Why Section Sleeker -->
    <div class="bg-slate-900 rounded-[32px] p-8 text-white space-y-6">
        <h3 class="text-sm font-bold text-center text-emerald-400">Pourquoi parrainer ?</h3>
        <div class="space-y-4">
            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
                <div class="text-2xl font-bold text-emerald-400 w-12 text-center">15%</div>
                <p class="text-[10px] font-semibold text-gray-300 leading-relaxed">Sur le premier dépôt de vos filleuls directs</p>
            </div>
            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
                <div class="text-2xl font-black text-emerald-400 w-12 text-center">5%</div>
                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-wide leading-relaxed">Des revenus journaliers de votre niveau 1</p>
            </div>
            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
                <div class="text-2xl font-black text-emerald-400 w-12 text-center">2%</div>
                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-wide leading-relaxed">Commission sur les niveaux 2 et 3</p>
            </div>
        </div>
    </div>
    
    <div class="pb-10 text-center">
        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">BioEnergy Network • {{ date('Y') }}</p>
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