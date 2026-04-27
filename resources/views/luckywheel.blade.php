<x-layouts :title="'Lucky Wheel'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-10 pb-32">

    <!-- Hero Lucky Wheel -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl text-center">
        <div class="relative z-10 space-y-2">
            <h1 class="text-3xl font-bold tracking-tight leading-none">Roue de la Fortune</h1>
            <p class="text-[11px] font-semibold text-emerald-400 tracking-wide">Tentez votre chance</p>
        </div>
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Wheel Section -->
    <div class="flex flex-col items-center space-y-10">
        
        <!-- Lucky Spins Badge -->
        <div class="bg-white/50 backdrop-blur-md border border-white px-8 py-4 rounded-3xl shadow-sm inline-flex items-center gap-4">
            <div class="w-10 h-10 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-ticket text-xs"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400">Tours restants</p>
                <p id="spin-count" class="text-xl font-bold text-slate-900">{{ $user->lucky_spins }}</p>
            </div>
        </div>

        <!-- The Wheel Container -->
        <div class="relative w-72 h-72 sm:w-80 sm:h-80 group">
            <!-- Indicator -->
            <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-20 text-slate-900 text-3xl">
                <i class="fas fa-caret-down"></i>
            </div>
            
            <!-- Wheel Image/SVG -->
            <div id="wheel" class="w-full h-full rounded-full border-[8px] border-slate-900 shadow-2xl transition-transform duration-[5s] cubic-bezier(0.15, 0, 0.15, 1) bg-slate-50 relative overflow-hidden">
                <!-- Slices will be represented by background segments or just a nice image -->
                <canvas id="wheel-canvas" width="400" height="400" class="w-full h-full"></canvas>
            </div>

            <!-- Center Button -->
            <button id="spin-button" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-slate-900 rounded-full border-4 border-white shadow-2xl flex items-center justify-center text-white text-[10px] font-black uppercase tracking-widest active:scale-90 transition z-30 group-hover:bg-emerald-600">
                Lancer
            </button>
        </div>
    </div>

    <!-- Live Winners Feed -->
    <div class="space-y-6">
        <div class="flex justify-between items-end px-4">
            <h3 class="text-xs font-bold text-gray-400">Gagnants en direct</h3>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-emerald-600">En direct</span>
            </div>
        </div>

        <div class="bg-white rounded-[40px] border border-gray-50 shadow-sm overflow-hidden h-48 relative">
            <div id="winners-feed" class="absolute inset-0 p-6 space-y-4 transition-all">
                @foreach($fictiveWinners as $winner)
                    <div class="flex justify-between items-center winner-item">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-[10px] font-black text-slate-400">
                                {{ substr($winner['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-800">{{ $winner['name'] }}</p>
                                <p class="text-[8px] font-bold text-gray-400 uppercase">{{ $winner['time'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-emerald-600">+{{ $winner['prize'] }} XAF</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
        </div>
    </div>

    <!-- Rules Section -->
    <div class="bg-slate-900 rounded-[40px] p-8 text-white space-y-6">
        <h3 class="text-sm font-bold text-emerald-400">Règles du jeu</h3>
        <ul class="space-y-4">
            <li class="flex gap-4 items-start">
                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-check text-[8px]"></i>
                </div>
                <p class="text-[10px] font-medium text-gray-400 leading-relaxed uppercase tracking-wide">Obtenez 1 tour gratuit après votre premier investissement.</p>
            </li>
            <li class="flex gap-4 items-start">
                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-check text-[8px]"></i>
                </div>
                <p class="text-[10px] font-medium text-gray-400 leading-relaxed uppercase tracking-wide">Recevez 1 tour pour chaque premier investissement de vos filleuls directs.</p>
            </li>
            <li class="flex gap-4 items-start">
                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-check text-[8px]"></i>
                </div>
                <p class="text-[10px] font-medium text-gray-400 leading-relaxed uppercase tracking-wide">Chaque passage à un niveau VIP supérieur vous offre 1 tour supplémentaire.</p>
            </li>
            <li class="flex gap-4 items-start">
                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-check text-[8px]"></i>
                </div>
                <p class="text-[10px] font-medium text-gray-400 leading-relaxed">Les gains sont instantanément crédités sur votre compte principal.</p>
            </li>
        </ul>
    </div>

</div>

<script>
    const canvas = document.getElementById('wheel-canvas');
    const ctx = canvas.getContext('2d');
    const spinButton = document.getElementById('spin-button');
    const wheel = document.getElementById('wheel');
    const spinCountDisplay = document.getElementById('spin-count');

    const prizes = [500, 1200, 5000, 8000, 150000, 500, 1200, 5000];
    const colors = ['#0f172a', '#10b981', '#1e293b', '#34d399', '#0f172a', '#10b981', '#1e293b', '#34d399'];
    const totalSlices = prizes.length;
    const sliceDeg = 360 / totalSlices;

    function drawWheel() {
        ctx.clearRect(0, 0, 400, 400);
        for (let i = 0; i < totalSlices; i++) {
            ctx.beginPath();
            ctx.fillStyle = colors[i];
            ctx.moveTo(200, 200);
            ctx.arc(200, 200, 200, (i * sliceDeg * Math.PI) / 180, ((i + 1) * sliceDeg * Math.PI) / 180);
            ctx.fill();
            
            ctx.save();
            ctx.translate(200, 200);
            ctx.rotate(((i + 0.5) * sliceDeg * Math.PI) / 180);
            ctx.textAlign = "right";
            ctx.fillStyle = "#fff";
            ctx.font = "bold 14px Inter";
            ctx.fillText(prizes[i], 180, 10);
            ctx.restore();
        }
    }

    drawWheel();

    let isSpinning = false;
    let currentRotation = 0;

    spinButton.addEventListener('click', async () => {
        if (isSpinning) return;

        try {
            const response = await fetch("{{ route('luckywheel.spin') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.error) {
                alert(data.error);
                return;
            }

            isSpinning = true;
            const prizeIndex = prizes.indexOf(data.prize);
            // On veut que la roue s'arrête sur le bon index.
            // On rajoute 5-10 tours complets pour l'effet.
            const extraRounds = 5 + Math.floor(Math.random() * 5);
            const targetRotation = 360 * extraRounds + (360 - (prizeIndex * sliceDeg)) - (sliceDeg/2);
            
            currentRotation += targetRotation;
            wheel.style.transform = `rotate(${currentRotation}deg)`;

            setTimeout(() => {
                isSpinning = false;
                spinCountDisplay.textContent = data.remaining_spins;
                alert(data.message);
                window.location.reload(); // Pour rafraîchir le solde proprement ou via JS
            }, 5500);

        } catch (error) {
            console.error(error);
            isSpinning = false;
        }
    });

    // Fictive feed scrolling
    const feed = document.getElementById('winners-feed');
    let scrollPos = 0;
    function scrollFeed() {
        scrollPos -= 1;
        if (Math.abs(scrollPos) >= feed.scrollHeight / 2) {
            scrollPos = 0;
        }
        feed.style.transform = `translateY(${scrollPos}px)`;
        requestAnimationFrame(scrollFeed);
    }
    // Cloner le contenu pour un scroll infini
    feed.innerHTML += feed.innerHTML;
    scrollFeed();

</script>
</x-layouts>
