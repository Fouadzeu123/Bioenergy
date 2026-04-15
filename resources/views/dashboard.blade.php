<x-layouts :title="'Dashboard'">

    <!-- Animation CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- POPUP BIENVENUE – FONCTIONNE À 100% -->
    <div id="welcomePopup"
         class="fixed inset-0 z-[9999] flex items-center justify-center hidden">

        <!-- Fond sombre -->
        <div id="popupOverlay" class="absolute inset-0 bg-black/70"></div>

        <!-- Carte popup -->
        <div class="relative bg-gradient-to-br from-white to-green-50 rounded-3xl shadow-2xl max-w-md w-11/12 p-8 animate__animated animate__zoomIn">

            <!-- Bouton fermer -->
            <button id="closePopup"
                    class="absolute top-4 right-4 w-10 h-10 bg-red-500/20 hover:bg-red-500/40 rounded-full flex items-center justify-center text-red-600 text-2xl font-bold transition hover:scale-110">
                X
            </button>

            <div class="text-center">
                <h2 class="text-2xl md:text-3xl font-extrabold text-green-700 mb-4">
                    Bienvenue chez BioEnergy Investment
                </h2>
                <p class="text-gray-700 text-lg leading-relaxed mb-8">
                    Vous avez <strong class="text-green-600">10 $ de bonus offert</strong> !<br>
                    Gagnez plus grâce au <strong class="text-blue-600">parrainage multi-niveaux et notre systeme de recompenses personalisés</strong>
                </p>
                <a href="https://t.me/+MBOmbS0qokZkMmY8" target="_blank"
                   class="block w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-green-700 hover:to-emerald-800 text-white font-bold text-lg py-5 rounded-2xl shadow-2xl transition transform hover:scale-105">
                    Rejoindre le canal d'annonce officiel telegram
                </a>
            </div>
        </div>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 pt-6 pb-24 px-4">

        <!-- Bienvenue -->
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-700 bg-clip-text text-transparent">
                Bonjour, {{ Auth::user()->username ?? 'Cher membre' }} !
            </h1>
            <p class="text-xl text-gray-700 mt-3">Construisons un avenir plus vert ensemble</p>
        </div>

        <!-- Carousel -->
        <div class="max-w-2xl mx-auto mb-12">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                <div id="carousel" class="flex transition-transform duration-1000 ease-in-out">
                    <img src="{{ asset('images/slide1.jpg') }}" class="w-full h-64 md:h-96 object-cover flex-shrink-0">
                    <img src="{{ asset('images/slide2.jpg') }}" class="w-full h-64 md:h-96 object-cover flex-shrink-0">
                    <img src="{{ asset('images/slide3.jpg') }}" class="w-full h-64 md:h-96 object-cover flex-shrink-0">
                    <img src="{{ asset('images/slide1.jpg') }}" class="w-full h-64 md:h-96 object-cover flex-shrink-0">
                </div>
            </div>
        </div>

      <!-- GRILLE D'ICÔNES – CORRIGÉE : PLUS JAMAIS DE DÉBORDEMENT -->
<div class="max-w-5xl mx-auto grid grid-cols-3 md:grid-cols-4 gap-5 md:gap-8 mb-12 px-2">

    <a href="{{ route('presentation') }}" class="group">
        <div class="bg-white rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 flex flex-col justify-between h-full">
            <i class="fas fa-info-circle text-blue-600 text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-blue-700 text-xs md:text-base leading-tight">Présentation</p>
        </div>
    </a>

    <a href="{{ route('team') }}" class="group">
        <div class="bg-white rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 flex flex-col justify-between h-full">
            <i class="fas fa-users text-green-600 text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-green-700 text-xs md:text-base leading-tight">Équipes</p>
        </div>
    </a>

    <a href="{{ route('share') }}" class="group">
        <div class="bg-white rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 flex flex-col justify-between h-full">
            <i class="fas fa-link text-blue-600 text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-blue-700 text-xs md:text-base leading-tight">Mon lien</p>
        </div>
    </a>

    <a href="{{ route('deposit') }}" class="group">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 text-white flex flex-col justify-between h-full">
            <i class="fas fa-wallet text-white text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-white text-sm md:text-lg leading-tight">Dépôt</p>
        </div>
    </a>

    <a href="{{ route('retrait') }}" class="group">
        <div class="bg-white rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 flex flex-col justify-between h-full">
            <i class="fas fa-hand-holding-usd text-blue-600 text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-blue-700 text-xs md:text-base leading-tight">Retrait</p>
        </div>
    </a>

    <a href="{{ route('fond.index') }}" class="group">
        <div class="bg-white rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 flex flex-col justify-between h-full">
            <i class="fas fa-leaf text-green-600 text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-green-700 text-xs md:text-base leading-tight break-words">Fond préservation</p>
        </div>
    </a>

    <a href="{{ route('bonus.code') }}" class="group">
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 text-white flex flex-col justify-between h-full">
            <i class="fas fa-gift text-white text-4xl md:text-5xl mb-3 group-hover:rotate-12 transition"></i>
            <p class="font-bold text-white text-sm md:text-lg leading-tight">Bonus</p>
        </div>
    </a>

    <a href="{{ route('contact') }}" class="group">
        <div class="bg-white rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 flex flex-col justify-between h-full">
            <i class="fas fa-phone text-pink-600 text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-pink-700 text-xs md:text-base leading-tight">Contact</p>
        </div>
    </a>

    <a href="{{ route('emploi') }}" class="group">
        <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl p-5 md:p-7 text-center shadow-xl hover:shadow-2xl hover:-translate-y-3 transition-all duration-300 text-white flex flex-col justify-between h-full">
            <i class="fas fa-briefcase text-white text-4xl md:text-5xl mb-3 group-hover:scale-110 transition"></i>
            <p class="font-bold text-white text-xs md:text-base leading-tight">Emploi</p>
        </div>
    </a>
</div>

        <!-- Impact & Avantages -->
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8 mb-12">
            <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-3xl p-8 text-white shadow-2xl">
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <span class="text-4xl">Lightning</span> Notre impact
                </h3>
                <ul class="space-y-4 text-lg">
                    <li>+12 000 GWh produits</li>
                    <li>300 arbres plantés</li>
                    <li>210 tonnes de CO₂ évitées</li>
                </ul>
            </div>

            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-2xl">
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <span class="text-4xl">Gift</span> Vos avantages
                </h3>
                <ul class="space-y-4 text-lg">
                    <li>Bonus de bienvenue 10$</li>
                    <li>Des récompense exclusive allant de 1 a 1000$ pour nos investisseurs</li>
                    <li>Parrainage 3 niveaux & Multi rémunération</li>
                    <li>Retrait instantané</li>
                    <li>Un suivi par nos experts financier</li>
                </ul>
            </div>
        </div>

        <!-- VIDÉO ÉNERGIE BIO – 100% FONCTIONNELLE -->
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-extrabold text-center text-gray-800 mb-8">
                L'énergie biomasse expliquée
            </h2>

            <div class="relative w-full overflow-hidden rounded-3xl shadow-2xl bg-black" style="padding-top: 56.25%;">
                <iframe class="absolute inset-0 w-full h-full"
                        src="https://www.youtube.com/embed/yHWcddUZ35s"
                        title="Biomass 101 - What is Biomass Energy?"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                </iframe>
            </div>

            <p class="text-center text-gray-600 text-lg mt-6 max-w-3xl mx-auto leading-relaxed">
                La biomasse transforme les déchets organiques en énergie propre. Une solution simple et durable pour un avenir vert.
            </p>
        </div>
    </div>

    <!-- Notifications -->
    <div id="notifications" class="fixed top-20 right-4 space-y-3 z-40 max-w-sm w-full"></div>

    <!-- SCRIPTS -->
    <script>
        // POPUP BIENVENUE – FONCTIONNE À 100%
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('welcomePopup');
            const overlay = document.getElementById('popupOverlay');
            const closeBtn = document.getElementById('closePopup');

            // Afficher après 1,5s
            setTimeout(() => popup.classList.remove('hidden'), 1500);

            // Fermer avec le X
            closeBtn.onclick = () => popup.classList.add('hidden');

            // Fermer en cliquant dehors
            overlay.onclick = () => popup.classList.add('hidden');

            // Fermer avec Échap
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') popup.classList.add('hidden');
            });
        });

        // Carousel infini
        const carousel = document.getElementById('carousel');
        let index = 0;
        setInterval(() => {
            index++;
            carousel.style.transition = "transform 1s ease-in-out";
            carousel.style.transform = `translateX(-${index * 100}%)`;
            if (index === 3) {
                setTimeout(() => {
                    carousel.style.transition = "none";
                    carousel.style.transform = "translateX(0)";
                    index = 0;
                }, 1000);
            }
        }, 4500);

function showNotif() {
    // Génère un numéro camerounais aléatoire (237 + 6 chiffres masqués + 3 visibles)
    function generateRandomPhone() {
        const prefixes = ['650', '651', '652', '653', '654', '655', '656', '657', '658', '659',
                          '680', '681', '682', '683', '690', '691', '692', '693', '694', '695'];
        const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
        const last3 = Math.floor(Math.random() * 900) + 100; // 100 à 999
        return `237${prefix.slice(0, 3)}****${last3}`; // ex: 237652****847
    }

    // Génère un niveau VIP aléatoire avec couleurs différentes
    const vipLevels = [
        { name: "VIP 1", color: "text-yellow-600" },
        { name: "VIP 2", color: "text-orange-600" },
        { name: "VIP 3", color: "text-red-600" },
    ];
    const vip = vipLevels[Math.floor(Math.random() * vipLevels.length)];

    // Montants aléatoires de récompense (pour plus de réalisme)
    const rewards = ["150.50$", "280.00$", "42.75$", "750.00$", "120.00$",
     "815.25$", "551.00$","260.46$",'52.15$','91.23$','190.45$'];
    const reward = rewards[Math.floor(Math.random() * rewards.length)];

    const phone = generateRandomPhone();
    const msg = `${phone} vient d'inviter un <span class="${vip.color} font-extrabold">${vip.name}</span>, récompense <span class="text-green-600 font-bold">${reward}</span> attribuée !`;

    const div = document.createElement('div');
    div.className = "bg-white/95 backdrop-blur-lg shadow-2xl rounded-xl px-5 py-4 border-l-4 border-green-500 animate__animated animate__fadeInRight text-sm md:text-base";
    div.innerHTML = `<strong class="text-green-700">${msg}</strong>`;

    document.getElementById('notifications').prepend(div);

    // Disparition douce après 7 secondes
    setTimeout(() => {
        div.classList.add('animate__animated', 'animate__fadeOutRight');
        setTimeout(() => div.remove(), 800);
    }, 7000);
}
        setInterval(showNotif, 8000);
        setTimeout(showNotif, 3000);
    </script>

</x-layouts>
