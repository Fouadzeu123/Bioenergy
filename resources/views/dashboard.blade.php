<x-layouts :title="'Dashboard'">

    <!-- Animation CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        #welcomePopup {
            font-family: 'Inter', sans-serif;
        }

        @keyframes gradientShiftDark {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .popup-header-bg {
            background: linear-gradient(135deg, #1e1b4b, #1e3a8a, #0e7490, #1d4ed8, #312e81);
            background-size: 300% 300%;
            animation: gradientShiftDark 6s ease infinite;
        }

        @keyframes floatIcon {

            0%,
            100% {
                transform: translateY(0px) rotate(-3deg);
            }

            50% {
                transform: translateY(-8px) rotate(3deg);
            }
        }

        .float-icon {
            animation: floatIcon 3s ease-in-out infinite;
        }

        .bonus-chip {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }

        .popup-card {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(24px);
        }

        .telegram-btn {
            background: linear-gradient(135deg, #2563eb, #0891b2);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
        }

        .telegram-btn:hover {
            box-shadow: 0 12px 35px rgba(37, 99, 235, 0.6);
            transform: translateY(-2px) scale(1.02);
        }

        .feature-item {
            transition: transform 0.2s ease;
        }

        .feature-item:hover {
            transform: translateX(4px);
        }
    </style>

    <!-- POPUP BIENVENUE -->
    <div id="welcomePopup" class="fixed inset-0 z-[9999] flex items-center justify-center hidden p-5">

        <!-- Overlay -->
        <div id="popupOverlay" class="absolute inset-0 backdrop-blur-sm" style="background: rgba(0,0,0,0.75);"></div>

        <!-- Carte popup -->
        <div class="popup-card relative w-full max-w-sm rounded-[2rem] shadow-2xl overflow-hidden max-h-[90dvh] flex flex-col animate__animated animate__zoomIn">

            <!-- En-tête gradient animé -->
            <div class="popup-header-bg px-6 pt-8 pb-12 text-center relative overflow-hidden flex-shrink-0">
                <div class="absolute top-0 left-0 w-28 h-28 bg-white/5 rounded-full -translate-x-10 -translate-y-10">
                </div>
                <div class="absolute bottom-0 right-0 w-36 h-36 bg-white/5 rounded-full translate-x-14 translate-y-14">
                </div>

                <button id="closePopup"
                    class="absolute top-3 right-3 w-10 h-10 rounded-full flex items-center justify-center text-white text-base font-bold touch-manipulation transition"
                    style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.15);">
                    <i class="fas fa-times"></i>
                </button>

                <div class="float-icon inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-2xl sm:rounded-3xl mb-3 mx-auto"
                    style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-leaf text-white text-3xl sm:text-4xl"></i>
                </div>

                <h2 class="text-xl sm:text-2xl font-extrabold text-white leading-tight mb-1">
                    Bienvenue chez<br>
                    <span style="color: #93c5fd;">BioEnergy Investment</span>
                </h2>
                <p class="text-xs sm:text-sm" style="color: rgba(147,197,253,0.7);">La plateforme d'investissement vert
                    qui rapporte</p>
            </div>

            <!-- Corps -->
            <div class="px-5 sm:px-7 pt-5 pb-6 overflow-y-auto overscroll-contain">

                <!-- Avantages -->
                <div class="space-y-2 sm:space-y-3 mb-5">
                    <div class="feature-item flex items-center gap-3 rounded-xl px-3 sm:px-4 py-3"
                        style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);">
                        <div class="w-9 h-9 flex-shrink-0 rounded-xl flex items-center justify-center"
                            style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3);">
                            <i class="fas fa-users text-emerald-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-xs sm:text-sm">Parrainage multi-niveaux</p>
                            <p class="text-[11px] sm:text-xs" style="color: #4b5563;">Gagnez sur 3 niveaux de filleuls
                            </p>
                        </div>
                    </div>

                    <div class="feature-item flex items-center gap-3 rounded-xl px-3 sm:px-4 py-3"
                        style="background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.2);">
                        <div class="w-9 h-9 flex-shrink-0 rounded-xl flex items-center justify-center"
                            style="background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.3);">
                            <i class="fas fa-trophy text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-xs sm:text-sm">Récompenses exclusives</p>
                            <p class="text-[11px] sm:text-xs" style="color: #4b5563;">Jusqu'à 1 000 000
                                {{ Auth::user()->currency }} pour nos meilleurs investisseurs</p>
                        </div>
                    </div>

                    <div class="feature-item flex items-center gap-3 rounded-xl px-3 sm:px-4 py-3"
                        style="background: rgba(139,92,246,0.08); border: 1px solid rgba(139,92,246,0.2);">
                        <div class="w-9 h-9 flex-shrink-0 rounded-xl flex items-center justify-center"
                            style="background: rgba(139,92,246,0.2); border: 1px solid rgba(139,92,246,0.3);">
                            <i class="fas fa-briefcase text-violet-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white text-xs sm:text-sm">Programme Emploi</p>
                            <p class="text-[11px] sm:text-xs" style="color: #4b5563;">Jusqu'à 1 200 000
                                {{ Auth::user()->currency }}/mois selon votre poste</p>
                        </div>
                    </div>
                </div>

                <button id="startJourneyBtn" class="flex items-center justify-center w-full text-white font-bold text-[13px] py-3.5 rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);">
                    Commencer
                </button>
            </div>
        </div>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="max-w-xl mx-auto pt-5 px-4 space-y-6">

        <!-- Hero Card -->
        <div class="relative overflow-hidden rounded-[2rem] p-7 text-white"
            style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 40%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.4);">
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-1">
                    <div>
                        <p class="text-[11px] font-medium" style="color: rgba(147,197,253,0.8);">Bonjour,</p>
                        <p class="text-2xl font-bold tracking-tight">+{{ Auth::user()->country_code }} {{ Auth::user()->phone }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center"
                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fas fa-bell text-xs text-blue-200"></i>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl p-4"
                        style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                        <p class="text-[10px] font-medium mb-1" style="color: rgba(147,197,253,0.7);">Solde disponible
                        </p>
                        <p class="text-xl font-bold">{{ fmtCurrency(Auth::user()->account_balance) }}</p>
                    </div>
                    <div class="rounded-2xl p-4"
                        style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                        <p class="text-[10px] font-medium mb-1" style="color: rgba(147,197,253,0.7);">Gains du jour</p>
                        <p class="text-xl font-bold text-cyan-300">+{{ fmtCurrency(0) }}</p>
                    </div>
                </div>
            </div>
            <!-- Déco -->
            <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full"
                style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
            <div class="absolute -left-8 -bottom-8 w-32 h-32 rounded-full"
                style="background: rgba(6,182,212,0.1); filter: blur(24px);"></div>
        </div>

        <!-- Carousel -->
        <div class="relative rounded-2xl overflow-hidden shadow-lg aspect-[16/7]"
            style="border: 1px solid rgba(255,255,255,0.06);">
            <div id="carousel" class="flex transition-transform duration-700 ease-in-out h-full">
                <img src="{{ asset('images/slide5.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide6.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide7.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide8.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide9.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide10.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide1.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide2.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
                <img src="{{ asset('images/slide3.jpg') }}" class="w-full h-full object-cover flex-shrink-0">
            </div>
            <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(7,9,15,0.4), transparent);">
            </div>
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                <div class="w-1.5 h-1.5 rounded-full bg-white opacity-40"></div>
                <div class="w-3 h-1.5 rounded-full bg-white"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-white opacity-40"></div>
            </div>
        </div>

        <!-- Grille d'actions rapide -->
        <div class="grid grid-cols-4 gap-3">
            <a href="{{ route('deposit') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-blue-400 group-hover:text-blue-300 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                    <i class="fas fa-arrow-down text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Dépôt</span>
            </a>
            <a href="{{ route('retrait') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-cyan-400 group-hover:text-cyan-300 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(6,182,212,0.12); border: 1px solid rgba(6,182,212,0.2);">
                    <i class="fas fa-arrow-up text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Retrait</span>
            </a>
            <a href="{{ route('fond.index') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-emerald-400 group-hover:text-emerald-300 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2);">
                    <i class="fas fa-leaf text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Fonds</span>
            </a>
            <a href="{{ route('team') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-violet-400 group-hover:text-violet-300 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.2);">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Équipe</span>
            </a>
            <a href="{{ route('share') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-indigo-400 group-hover:text-indigo-300 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.2);">
                    <i class="fas fa-share-nodes text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Lien</span>
            </a>
            <a href="{{ route('bonus.code') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-amber-400 group-hover:text-amber-300 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.2);">
                    <i class="fas fa-gift text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Bonus</span>
            </a>
            <a href="{{ route('emploi') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-rose-400 group-hover:text-rose-300 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(244,63,94,0.12); border: 1px solid rgba(244,63,94,0.2);">
                    <i class="fas fa-briefcase text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Emploi</span>
            </a>
            <a href="{{ route('presentation') }}" class="group flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-[1.25rem] flex items-center justify-center text-gray-400 group-hover:text-gray-200 transition-all duration-300 group-hover:scale-105"
                    style="background: rgba(107,114,128,0.12); border: 1px solid rgba(107,114,128,0.2);">
                    <i class="fas fa-info text-lg"></i>
                </div>
                <span class="text-[10px] font-medium" style="color: #6b7280;">Infos</span>
            </a>
        </div>

        <!-- Section Impact & Vidéo -->
        <div class="space-y-4 pb-2">
            <div class="rounded-2xl p-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <h3 class="text-[11px] font-semibold mb-4" style="color: #4b5563;">Notre impact</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xl font-bold text-blue-400">+12k</p>
                        <p class="text-[10px] font-medium" style="color: #4b5563;">GWh produits</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-cyan-400">210t</p>
                        <p class="text-[10px] font-medium" style="color: #4b5563;">CO₂ évitées</p>
                    </div>
                </div>
            </div>

            <div class="relative rounded-2xl overflow-hidden" style="border: 1px solid rgba(255,255,255,0.06);">
                <div class="aspect-video relative group">
                    <iframe class="absolute inset-0 w-full h-full opacity-70 group-hover:opacity-100 transition-opacity"
                        src="https://www.youtube.com/embed/yHWcddUZ35s?controls=0" title="BioEnergy"
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <div class="absolute inset-0 flex items-end p-5 pointer-events-none"
                        style="background: linear-gradient(to top, rgba(7,9,15,0.8), transparent);">
                        <p class="text-white font-semibold text-sm">Comprendre la biomasse en 2 minutes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activité Récente (Tableau) -->
        <div class="rounded-2xl p-5 mb-10" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <h3 class="text-[11px] font-semibold mb-4 flex items-center gap-2" style="color: #4b5563;">
                <i class="fas fa-broadcast-tower text-blue-500 animate-pulse"></i>
                Activité en direct
            </h3>
            <div class="overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <th class="pb-2 text-[10px] font-semibold w-1/3" style="color: #374151;">Membre</th>
                            <th class="pb-2 text-[10px] font-semibold w-1/3 text-center" style="color: #374151;">Action
                            </th>
                            <th class="pb-2 text-[10px] font-semibold w-1/3 text-right" style="color: #374151;">Montant
                            </th>
                        </tr>
                    </thead>
                    <tbody id="live-activity-table" class="text-xs" style="--tw-divide-color: rgba(255,255,255,0.04);">
                        <!-- Rempli par JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // POPUP BIENVENUE – FONCTIONNE À 100%
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('welcomePopup');
            const overlay = document.getElementById('popupOverlay');
            const closeBtn = document.getElementById('closePopup');

            // Afficher après 1,5s
            setTimeout(() => popup.classList.remove('hidden'), 1500);

            // Fermer avec le X ou le bouton Commencer
            closeBtn.onclick = () => popup.classList.add('hidden');
            document.getElementById('startJourneyBtn').onclick = () => popup.classList.add('hidden');

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
            if (index === 9) {
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
                    const prefixes = ['650', '651', '652', '653', '655', '656', '657', '658', '659', '670', '671', '672', '673', '680', '681', '682', '683', '690', '691', '692', '693', '694', '695', '696'];
                    p = prefixes[Math.floor(Math.random() * prefixes.length)];
                } else {
                    const prefixes = ['01', '05', '07'];
                    p = prefixes[Math.floor(Math.random() * prefixes.length)];
                }

                const mid = Math.floor(Math.random() * 90) + 10;
                const last = Math.floor(Math.random() * 900) + 100;
                return `+${country} ${p}**${mid}**${last}`;
            }

            const phone = randomPhone();
            const currency = "{{ Auth::user()->currency }}";

            // Choisir un type de notification au hasard
            const type = Math.floor(Math.random() * 3); // 0 = parrainage, 1 = dépôt, 2 = retrait

            let actionText, amountText;

            if (type === 0) {
                // ── PARRAINAGE ──
                const reward = Math.floor(Math.random() * (50000 - 500 + 1)) + 500;
                actionText = `<span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded-md font-bold text-[9px]">Gagné</span>`;
                amountText = `<span class="text-emerald-600 font-bold">+${reward.toLocaleString()}</span>`;
            } else if (type === 1) {
                // ── DÉPÔT ──
                const amount = Math.floor(Math.random() * (500000 - 5000 + 1)) + 5000;
                actionText = `<span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-md font-bold text-[9px]">Dépôt</span>`;
                amountText = `<span class="text-blue-600 font-bold">+${amount.toLocaleString()}</span>`;
            } else {
                // ── RETRAIT ──
                const amount = Math.floor(Math.random() * (200000 - 5000 + 1)) + 5000;
                actionText = `<span class="bg-violet-50 text-violet-600 px-2 py-1 rounded-md font-bold text-[9px]">Retrait</span>`;
                amountText = `<span class="text-violet-600 font-bold">-${amount.toLocaleString()}</span>`;
            }

            const tr = document.createElement('tr');
            tr.className = `animate__animated animate__fadeIn bg-emerald-50/30 transition-all duration-500`;
            tr.innerHTML = `
        <td class="py-3 font-semibold text-gray-700 truncate w-1/3">${phone}</td>
        <td class="py-3 text-center w-1/3">${actionText}</td>
        <td class="py-3 text-right w-1/3">${amountText} <span class="text-[9px] text-gray-400 ml-1">${currency}</span></td>
    `;

            const tbody = document.getElementById('live-activity-table');

            // Enlever le fond coloré après l'animation
            setTimeout(() => {
                tr.classList.remove('bg-emerald-50/30');
            }, 1000);

            tbody.prepend(tr);

            // Garder 5 éléments max
            if (tbody.children.length > 5) {
                const last = tbody.lastElementChild;
                last.classList.replace('animate__fadeIn', 'animate__fadeOut');
                setTimeout(() => last.remove(), 800);
            }
        }
        setInterval(showNotif, 3500);
        setTimeout(showNotif, 1000);
        setTimeout(showNotif, 2500);
        setTimeout(showNotif, 4500);
        setTimeout(showNotif, 6500);
    </script>

</x-layouts>
