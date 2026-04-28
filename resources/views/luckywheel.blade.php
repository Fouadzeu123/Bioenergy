<x-layouts :title="'Lucky Wheel'" :level="Auth::user()->level">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-8 pb-32">

    <!-- Hero Lucky Wheel -->
    <div class="relative overflow-hidden rounded-[2rem] p-8 text-white text-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #1e3a8a 100%); box-shadow: 0 0 50px rgba(99,102,241,0.3);">
        <div class="relative z-10 space-y-2">
            <h1 class="text-3xl font-extrabold tracking-tight">Roue de la Fortune</h1>
            <p class="text-[11px] font-medium" style="color: rgba(199,210,254,0.8);">Tentez votre chance</p>
        </div>
        <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full" style="background: rgba(139,92,246,0.15); filter: blur(40px);"></div>
        <div class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full" style="background: rgba(59,130,246,0.15); filter: blur(40px);"></div>
    </div>

    <!-- Wheel Section -->
    <div class="flex flex-col items-center space-y-8">

        <!-- Lucky Spins Badge -->
        <div class="inline-flex items-center gap-4 px-7 py-4 rounded-2xl" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 16px rgba(59,130,246,0.4);">
                <i class="fas fa-ticket text-white text-xs"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold" style="color: #4b5563;">Tours restants</p>
                <p id="spin-count" class="text-xl font-bold text-white">{{ $user->lucky_spins }}</p>
            </div>
        </div>

        <!-- The Wheel Container -->
        <div class="relative w-72 h-72 sm:w-80 sm:h-80">
            <!-- Indicator -->
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-20 text-3xl" style="color: #3b82f6; filter: drop-shadow(0 0 8px rgba(59,130,246,0.6));">
                <i class="fas fa-caret-down"></i>
            </div>

            <!-- Wheel -->
            <div id="wheel" class="w-full h-full rounded-full transition-transform duration-[5s] relative overflow-hidden" style="border: 8px solid #1e3a8a; box-shadow: 0 0 40px rgba(59,130,246,0.3), inset 0 0 30px rgba(0,0,0,0.5);">
                <canvas id="wheel-canvas" width="400" height="400" class="w-full h-full"></canvas>
            </div>

            <!-- Center Button -->
            <button id="spin-button" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-full border-4 border-white/10 flex items-center justify-center text-white text-[11px] font-bold active:scale-90 transition z-30" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 30px rgba(59,130,246,0.5);">
                Lancer
            </button>
        </div>
    </div>

    <!-- Live Winners Feed -->
    <div class="space-y-4">
        <div class="flex justify-between items-center px-2">
            <h3 class="text-[12px] font-semibold" style="color: #4b5563;">Gagnants en direct</h3>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-semibold text-blue-400">En direct</span>
            </div>
        </div>

        <div class="rounded-2xl overflow-hidden h-44 relative" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <div id="winners-feed" class="absolute inset-0 p-5 space-y-4 transition-all">
                @foreach($fictiveWinners as $winner)
                    <div class="flex justify-between items-center winner-item">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-[10px] font-bold text-blue-400" style="background: rgba(59,130,246,0.15);">
                                {{ substr($winner['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-white">{{ $winner['name'] }}</p>
                                <p class="text-[10px] font-medium" style="color: #4b5563;">{{ $winner['time'] }}</p>
                            </div>
                        </div>
                        <p class="text-[11px] font-bold text-cyan-400">+{{ $winner['prize'] }} XAF</p>
                    </div>
                @endforeach
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-14 pointer-events-none" style="background: linear-gradient(to top, #0d1117, transparent);"></div>
        </div>
    </div>

    <!-- Rules Section -->
    <div class="rounded-2xl p-6 space-y-4" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
        <h3 class="text-sm font-bold text-blue-400">Règles du jeu</h3>
        <ul class="space-y-4">
            @foreach([
                'Obtenez 1 tour gratuit après votre premier investissement.',
                'Recevez 1 tour pour chaque premier investissement de vos filleuls directs.',
                'Chaque passage à un niveau VIP supérieur vous offre 1 tour supplémentaire.',
                'Les gains sont instantanément crédités sur votre compte principal.',
            ] as $rule)
            <li class="flex gap-3 items-start">
                <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5" style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3);">
                    <i class="fas fa-check text-blue-400 text-[8px]"></i>
                </div>
                <p class="text-[11px] font-medium leading-relaxed" style="color: #6b7280;">{{ $rule }}</p>
            </li>
            @endforeach
        </ul>
    </div>

</div>

<script>
    const canvas = document.getElementById('wheel-canvas');
    const ctx = canvas.getContext('2d');
    const spinButton = document.getElementById('spin-button');
    const wheel = document.getElementById('wheel');
    const spinCountDisplay = document.getElementById('spin-count');

    const prizes = [
        { val: 500 },
        { val: 'Montre connectée', img: 'watch.png' },
        { val: 5000 },
        { val: 'Power Bank', img: 'powerbank.png' },
        { val: 150000 },
        { val: 500 },
        { val: 'Ventilateur', img: 'fan.png' },
        { val: 5000 }
    ];
    // Couleurs bleu nuit pour la roue
    const colors = ['#1e3a8a','#2563eb','#1e40af','#0891b2','#1e3a8a','#3b82f6','#1d4ed8','#06b6d4'];
    const totalSlices = prizes.length;
    const sliceDeg = 360 / totalSlices;

    const loadedImages = {};
    prizes.forEach(p => {
        if (p.img) {
            const img = new Image();
            img.src = '/images/' + p.img;
            loadedImages[p.img] = img;
            img.onload = () => drawWheel();
        }
    });

    function drawWheel() {
        ctx.clearRect(0, 0, 400, 400);
        for (let i = 0; i < totalSlices; i++) {
            ctx.beginPath();
            ctx.fillStyle = colors[i];
            ctx.moveTo(200, 200);
            ctx.arc(200, 200, 200, (i * sliceDeg * Math.PI) / 180, ((i + 1) * sliceDeg * Math.PI) / 180);
            ctx.fill();

            // Séparateur
            ctx.beginPath();
            ctx.moveTo(200, 200);
            ctx.lineTo(200 + 200 * Math.cos((i * sliceDeg * Math.PI) / 180), 200 + 200 * Math.sin((i * sliceDeg * Math.PI) / 180));
            ctx.strokeStyle = 'rgba(255,255,255,0.1)';
            ctx.lineWidth = 1;
            ctx.stroke();

            ctx.save();
            ctx.translate(200, 200);
            ctx.rotate(((i + 0.5) * sliceDeg * Math.PI) / 180);
            
            if (prizes[i].img && loadedImages[prizes[i].img] && loadedImages[prizes[i].img].complete) {
                // Dessiner l'image (X vers le bord extérieur, Y centré verticalement)
                ctx.drawImage(loadedImages[prizes[i].img], 120, -25, 50, 50);
            } else {
                ctx.textAlign = "right";
                ctx.fillStyle = "#ffffff";
                ctx.font = "bold 13px Inter";
                let text = typeof prizes[i].val === 'number' ? prizes[i].val.toLocaleString('fr-FR') : prizes[i].val;
                ctx.fillText(text, 175, 6);
            }
            
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
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.error) { alert(data.error); return; }

            isSpinning = true;
            const prizeIndex = prizes.findIndex(p => p.val == data.prize);
            const extraRounds = 5 + Math.floor(Math.random() * 5);
            const targetRotation = 360 * extraRounds + (360 - (prizeIndex * sliceDeg)) - (sliceDeg / 2);
            currentRotation += targetRotation;
            wheel.style.transform = `rotate(${currentRotation}deg)`;

            setTimeout(() => {
                isSpinning = false;
                spinCountDisplay.textContent = data.remaining_spins;
                alert(data.message);
                window.location.reload();
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
        scrollPos -= 0.6;
        if (Math.abs(scrollPos) >= feed.scrollHeight / 2) scrollPos = 0;
        feed.style.transform = `translateY(${scrollPos}px)`;
        requestAnimationFrame(scrollFeed);
    }
    feed.innerHTML += feed.innerHTML;
    scrollFeed();
</script>
</x-layouts>
