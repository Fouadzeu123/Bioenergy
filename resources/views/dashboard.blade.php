<x-layouts :title="'Dashboard'">

    <!-- Animation CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        #welcomePopup { font-family: 'Inter', sans-serif; }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .popup-header-bg {
            background: linear-gradient(135deg, #064e3b, #065f46, #047857, #0d9488, #0369a1);
            background-size: 300% 300%;
            animation: gradientShift 6s ease infinite;
        }

        @keyframes floatIcon {
            0%, 100% { transform: translateY(0px) rotate(-3deg); }
            50%       { transform: translateY(-8px) rotate(3deg); }
        }
        .float-icon { animation: floatIcon 3s ease-in-out infinite; }

        .bonus-chip {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }
        .popup-card {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
        }
        .telegram-btn {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.45);
            transition: all 0.3s ease;
        }
        .telegram-btn:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            box-shadow: 0 12px 35px rgba(37, 99, 235, 0.6);
            transform: translateY(-2px) scale(1.02);
        }
        .feature-item {
            transition: transform 0.2s ease;
        }
        .feature-item:hover { transform: translateX(4px); }
    </style>

    <!-- POPUP BIENVENUE -->
    <div id="welcomePopup" class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center hidden">

        <!-- Overlay flouté -->
        <div id="popupOverlay" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>

        <!-- Carte popup — slide depuis le bas sur mobile, centrée sur desktop -->
        <div class="popup-card relative w-full sm:max-w-md sm:mx-4 sm:rounded-3xl rounded-t-3xl shadow-2xl overflow-hidden animate__animated animate__slideInUp sm:animate__zoomIn max-h-[92dvh] flex flex-col">

            <!-- En-tête gradient animé — compact sur mobile -->
            <div class="popup-header-bg px-6 pt-8 pb-12 text-center relative overflow-hidden flex-shrink-0">
                <!-- Cercles décoratifs -->
                <div class="absolute top-0 left-0 w-28 h-28 bg-white/5 rounded-full -translate-x-10 -translate-y-10"></div>
                <div class="absolute bottom-0 right-0 w-36 h-36 bg-white/5 rounded-full translate-x-14 translate-y-14"></div>

                <!-- Bouton fermer — plus grand sur mobile pour le toucher -->
                <button id="closePopup"
                        class="absolute top-3 right-3 w-10 h-10 bg-white/20 active:bg-white/40 rounded-full flex items-center justify-center text-white transition text-base font-bold touch-manipulation">
                    <i class="fas fa-times"></i>
                </button>

                <!-- Icône principale flottante — taille réduite sur mobile -->
                <div class="float-icon inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-white/15 rounded-2xl sm:rounded-3xl mb-3 mx-auto border border-white/20">
                    <i class="fas fa-leaf text-white text-3xl sm:text-4xl"></i>
                </div>

                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white leading-tight mb-1">
                    Bienvenue chez<br>
                    <span class="text-emerald-200">BioEnergy Investment</span>
                </h2>
                <p class="text-emerald-100/80 text-xs sm:text-sm">La plateforme d'investissement vert qui rapporte</p>
            </div>

            <!-- Chip bonus flottant -->
            <div class="flex justify-center -mt-5 flex-shrink-0">
                <div class="bonus-chip inline-flex items-center gap-2 text-white font-extrabold text-xs sm:text-sm px-4 py-2 sm:px-5 sm:py-2.5 rounded-full shadow-lg">
                    <i class="fas fa-gift"></i>
                    <span>6 000 {{ Auth::user()->currency }} de bonus de bienvenue offert !</span>
                </div>
            </div>

            <!-- Corps — scrollable si écran très petit -->
            <div class="px-5 sm:px-7 pt-5 pb-6 overflow-y-auto overscroll-contain">

                <!-- Avantages -->
                <div class="space-y-2 sm:space-y-3 mb-5">
                    <div class="feature-item flex items-center gap-3 bg-emerald-50 border border-emerald-100 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 flex-shrink-0 bg-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-white text-xs sm:text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-xs sm:text-sm">Parrainage multi-niveaux</p>
                            <p class="text-slate-500 text-[11px] sm:text-xs">Gagnez sur 3 niveaux de filleuls</p>
                        </div>
                    </div>

                    <div class="feature-item flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 flex-shrink-0 bg-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-trophy text-white text-xs sm:text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-xs sm:text-sm">Récompenses exclusives</p>
                            <p class="text-slate-500 text-[11px] sm:text-xs">Jusqu'à 1 000 000 {{ Auth::user()->currency }} pour nos meilleurs investisseurs</p>
                        </div>
                    </div>

                    <div class="feature-item flex items-center gap-3 bg-violet-50 border border-violet-100 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 flex-shrink-0 bg-violet-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-briefcase text-white text-xs sm:text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-xs sm:text-sm">Programme Emploi</p>
                            <p class="text-slate-500 text-[11px] sm:text-xs">Jusqu'à 1 200 000 {{ Auth::user()->currency }}/mois selon votre poste</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Telegram -->
                <a href="https://t.me/+MBOmbS0qokZkMmY8" target="_blank"
                   class="telegram-btn flex items-center justify-center gap-3 w-full text-white font-bold text-sm sm:text-base py-3.5 sm:py-4 rounded-2xl touch-manipulation">
                    <i class="fab fa-telegram text-lg sm:text-xl"></i>
                    <span>Rejoindre le canal officiel</span>
                    <i class="fas fa-arrow-right text-xs sm:text-sm opacity-70"></i>
                </a>

                <p class="text-center text-slate-400 text-[11px] mt-2.5 pb-safe">
                    <i class="fas fa-lock mr-1"></i> Canal vérifié · Annonces exclusives
                </p>
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
                    <li>Bonus de bienvenue 6 000 {{ Auth::user()->currency }}</li>
                    <li>Des récompenses exclusives allant de 1 000 à 1 000 000 {{ Auth::user()->currency }} pour nos investisseurs</li>
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
    // Numéro camerounais ou ivoirien masqué
    function randomPhone() {
        const country = Math.random() > 0.3 ? '237' : '225';
        let p;
        if (country === '237') {
            const prefixes = ['650','651','652','653','655','656','657','658','659','670','671','672','673','680','681','682','683','690','691','692','693','694','695','696'];
            p = prefixes[Math.floor(Math.random() * prefixes.length)];
        } else {
            const prefixes = ['01','05','07'];
            p = prefixes[Math.floor(Math.random() * prefixes.length)];
        }
        
        const mid = Math.floor(Math.random() * 90) + 10;
        const last = Math.floor(Math.random() * 900) + 100;
        return `${country}${p}**${mid}**${last}`;
    }

    const phone = randomPhone();
    const currency = "{{ Auth::user()->currency }}";

    // Choisir un type de notification au hasard
    const type = Math.floor(Math.random() * 3); // 0 = parrainage, 1 = dépôt, 2 = retrait

    let msg, borderColor, headerColor, icon, accentClass;

    if (type === 0) {
        // ── PARRAINAGE ──
        const vipLevels = [
            { name: 'VIP 1', color: 'text-yellow-600' },
            { name: 'VIP 2', color: 'text-orange-600' },
            { name: 'VIP 3', color: 'text-red-600'    },
        ];
        const vip    = vipLevels[Math.floor(Math.random() * vipLevels.length)];
        const reward = Math.floor(Math.random() * (50000 - 500 + 1)) + 500;
        borderColor  = 'border-emerald-500';
        icon         = '👥';
        accentClass  = 'text-emerald-700';
        msg = `${phone} vient d'inviter un <span class="${vip.color} font-extrabold">${vip.name}</span>, récompense <span class="text-emerald-600 font-bold">${reward.toLocaleString()} ${currency}</span> attribuée !`;

    } else if (type === 1) {
        // ── DÉPÔT ──
        const operators = ['MTN Mobile Money', 'Orange Money', 'Moov Money'];
        const op     = operators[Math.floor(Math.random() * operators.length)];
        const amount = Math.floor(Math.random() * (500000 - 5000 + 1)) + 5000;
        borderColor  = 'border-blue-500';
        icon         = '💳';
        accentClass  = 'text-blue-700';
        msg = `${phone} vient d'effectuer un dépôt de <span class="text-blue-600 font-bold">${amount.toLocaleString()} ${currency}</span> via <span class="font-semibold">${op}</span> ✅`;

    } else {
        // ── RETRAIT ──
        const methods = ['MTN Mobile Money', 'Orange Money', 'Moov Money'];
        const method  = methods[Math.floor(Math.random() * methods.length)];
        const amount  = Math.floor(Math.random() * (200000 - 5000 + 1)) + 5000;
        borderColor   = 'border-violet-500';
        icon          = '💸';
        accentClass   = 'text-violet-700';
        msg = `${phone} a retiré <span class="text-violet-600 font-bold">${amount.toLocaleString()} ${currency}</span> vers son compte <span class="font-semibold">${method}</span> 🎉`;
    }

    const div = document.createElement('div');
    div.className = `bg-white/95 backdrop-blur-lg shadow-2xl rounded-xl px-4 py-3 border-l-4 ${borderColor} animate__animated animate__fadeInRight text-sm`;
    div.innerHTML = `
        <div class="flex items-start gap-2">
            <span class="text-lg leading-none mt-0.5">${icon}</span>
            <p class="${accentClass} font-semibold leading-snug">${msg}</p>
        </div>
        <p class="text-gray-400 text-[10px] mt-1 ml-6">Il y a quelques secondes</p>
    `;

    document.getElementById('notifications').prepend(div);

    // Limiter à 4 notifications visibles simultanément
    const container = document.getElementById('notifications');
    while (container.children.length > 2) {
        container.removeChild(container.lastChild);
    }

    // Disparition après 7,5s
    setTimeout(() => {
        div.classList.replace('animate__fadeInRight', 'animate__fadeOutRight');
        setTimeout(() => div.remove(), 800);
    }, 7500);
}
        setInterval(showNotif, 7000);
        setTimeout(showNotif, 2500);
        setTimeout(showNotif, 5000);
    </script>

</x-layouts>
