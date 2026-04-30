<x-layouts :title="'Retrait'" :level="Auth::user()->level">

    @php
        $user = Auth::user();
        $currency = $user->currency;
        $MIN_WITHDRAWAL = 1000;
        $FEE_PERCENT = 10;
        $balance = $user->account_balance ?? 0;
    @endphp

    <div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

        <!-- Card Solde -->
        <div class="relative overflow-hidden rounded-[2rem] p-7 text-white"
            style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
            <div class="relative z-10 flex justify-between items-end">
                <div class="space-y-1">
                    <p class="text-[11px] font-medium" style="color: rgba(147,197,253,0.8);">Disponible pour retrait</p>
                    <h2 class="text-4xl font-bold tracking-tight">{{ fmtCurrency($balance) }}</h2>
                </div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center"
                    style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                    <i class="fas fa-arrow-up-from-bracket text-blue-200 text-lg"></i>
                </div>
            </div>
            <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full"
                style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
        </div>

        <!-- Conditions -->
        <div class="rounded-2xl p-4 flex items-center justify-center gap-8"
            style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);">
            <div class="text-center">
                <p class="text-[10px] font-semibold text-amber-500">Min. Retrait</p>
                <p class="text-[12px] font-bold text-amber-400">{{ number_format($MIN_WITHDRAWAL, 0, '.', ' ') }}
                    {{ $currency }}
                </p>
            </div>
            <div class="w-px h-8" style="background: rgba(245,158,11,0.2);"></div>
            <div class="text-center">
                <p class="text-[10px] font-semibold text-amber-500">Frais Service</p>
                <p class="text-[12px] font-bold text-amber-400">{{ $FEE_PERCENT }}%</p>
            </div>
            <div class="w-px h-8" style="background: rgba(245,158,11,0.2);"></div>
            <div class="text-center">
                <p class="text-[10px] font-semibold text-amber-500">Traitement</p>
                <p class="text-[12px] font-bold text-amber-400">Lun–Ven</p>
            </div>
        </div>

        <!-- Formulaire Retrait -->
        <form id="withdrawForm" method="POST" class="space-y-5">
            @csrf

            <div class="rounded-2xl p-6 space-y-5 text-center"
                style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <div class="space-y-3">
                    <label class="block text-left text-[11px] font-semibold px-2" style="color: #4b5563;">Sélectionnez
                        le montant ({{ $currency }})</label>

                    <input type="hidden" name="amount" id="amountInput" required>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach([1000, 5000, 15000, 50000, 150000, 500000, 1500000, 3000000] as $amt)
                            <button type="button" onclick="setAmount({{ $amt }}, this)"
                                class="amount-btn py-4 rounded-xl text-[14px] font-bold transition-all active:scale-95"
                                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: #9ca3af;">
                                {{ number_format($amt, 0, '.', ' ') }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Aperçu Net -->
                <div id="feePreview" class="hidden animate__animated animate__fadeIn">
                    <div class="rounded-2xl p-4"
                        style="background: rgba(6,182,212,0.08); border: 1px solid rgba(6,182,212,0.2);">
                        <p class="text-[10px] font-semibold text-cyan-500 mb-1">Montant Net (estimé)</p>
                        <p class="text-xl font-bold text-cyan-400" id="netAmount">--</p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-5 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition"
                    style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 24px rgba(59,130,246,0.3);">
                    Confirmer le retrait
                </button>

                <!-- Instructions de retrait -->
                <div class="mt-6 pt-6 border-t border-white/5 space-y-4 text-left">
                    <div class="space-y-3 text-[10px] sm:text-[11px] leading-relaxed text-gray-400">
                        <p>
                            <span class="text-gray-300 font-bold">Délai de traitement :</span> 0 à 3 jours ouvrables
                            (sous 72 heures).
                        </p>
                        <p>
                            <span class="text-gray-300 font-bold">Frais de gestion :</span> Chaque transaction entraîne
                            des frais de gestion de 10%. Ces frais couvrent les coûts administratifs et opérationnels
                            liés à la gestion de l'unité de l'employé (préparation de projet, supervision, rapports
                            financiers, etc.).
                        </p>
                        <p>
                            <span class="text-gray-300 font-bold">Montants fixes :</span>
                            1 000 / 5 000 / 15 000 / 50 000 / 150 000 / 500 000 / 1 500 000 / 3 000 000.
                        </p>
                        <p>
                            <span class="text-gray-300 font-bold text-red">Heures de retrait :</span> 9h00 à 18h00 les
                            jours
                            ouvrables.
                        </p>
                        <div class="p-3 rounded-xl bg-rose-500/5 border border-rose-500/10">
                            <p class="text-rose-400/90 font-medium">
                                <i class="fas fa-triangle-exclamation mr-1"></i>
                                Veuillez vérifier vos informations de compte. Toute perte due à des coordonnées de
                                paiement erronées sera à la charge de l'employé.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Historique Mini -->
        @if($retraits->count() > 0)
            <div class="space-y-3">
                <h3 class="text-[11px] font-semibold px-1" style="color: #4b5563;">Opérations récentes</h3>
                <div class="space-y-3">
                    @foreach($retraits->take(3) as $retrait)
                        <div class="rounded-2xl p-4 flex items-center justify-between"
                            style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                    style="background: rgba(6,182,212,0.12); border: 1px solid rgba(6,182,212,0.2);">
                                    <i class="fas fa-arrow-up text-cyan-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-white">{{ fmtCurrency($retrait->montant) }}</p>
                                    <p class="text-[10px] font-medium" style="color: #4b5563;">
                                        {{ $retrait->created_at->format('d M, H:i') }}
                                    </p>
                                </div>
                            </div>
                            @php
                                $sc = 'background: rgba(107,114,128,0.15); color: #9ca3af;';
                                if ($retrait->status === 'completed')
                                    $sc = 'background: rgba(6,182,212,0.15); color: #22d3ee;';
                                elseif (in_array($retrait->status, ['failed', 'canceled', 'rejected']))
                                    $sc = 'background: rgba(239,68,68,0.15); color: #f87171;';
                            @endphp
                            <span class="text-[9px] font-bold px-3 py-1 rounded-full"
                                style="{{ $sc }}">{{ $retrait->status }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Confirmation -->
    <div id="confirmModal"
        class="fixed inset-0 z-[110] hidden flex items-end sm:items-center justify-center backdrop-blur-sm p-0 sm:p-4"
        style="background: rgba(0,0,0,0.7);">
        <div class="rounded-t-[2rem] sm:rounded-3xl shadow-2xl max-w-lg w-full p-7 space-y-6 animate__animated animate__slideInUp"
            style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
            <div class="text-center space-y-1">
                <h4 class="text-xl font-bold text-white">Finaliser le transfert</h4>
                <p class="text-[11px] font-medium" style="color: #4b5563;">Confidentialité & Sécurité</p>
            </div>

            <div class="rounded-2xl p-5 space-y-4"
                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                <div class="flex justify-between items-center">
                    <p class="text-[10px] font-semibold" style="color: #4b5563;">Recevable net</p>
                    <p id="finalNet" class="text-lg font-bold text-cyan-400">--</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-[10px] font-semibold" style="color: #4b5563;">Frais déduits</p>
                    <p class="text-[11px] font-semibold text-red-400">{{ $FEE_PERCENT }}%</p>
                </div>
                <div class="pt-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <p class="text-[10px] font-semibold text-center mb-1" style="color: #4b5563;">Destination</p>
                    <p class="text-xs font-bold text-center text-white">{{ $user->withdrawal_method }} •
                        {{ $user->withdrawal_account }}
                    </p>
                </div>
            </div>

            <form action="{{ route('retrait.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="amount" id="confirmedAmount">
                <div class="space-y-2">
                    <label class="text-[10px] font-semibold px-1" style="color: #4b5563;">Code de retrait secret</label>
                    <input type="password" name="withdrawal_password" required autocomplete="off"
                        class="w-full rounded-2xl px-5 py-4 text-sm font-bold text-center text-white outline-none transition"
                        style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                        placeholder="••••••••">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="closeConfirmModal()"
                        class="py-4 rounded-2xl text-[11px] font-semibold transition active:scale-95"
                        style="background: rgba(255,255,255,0.04); color: #6b7280; border: 1px solid rgba(255,255,255,0.06);">
                        Annuler
                    </button>
                    <button type="submit"
                        class="py-4 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition"
                        style="background: linear-gradient(135deg, #2563eb, #0891b2);">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const FEE = {{ $FEE_PERCENT / 100 }};
        const CURRENCY = "{{ $currency }}";
        const MIN_W = {{ $MIN_WITHDRAWAL }};

        function setAmount(amt, btnElement) {
            document.getElementById('amountInput').value = amt;

            // Réinitialiser le style de tous les boutons
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.style.background = 'rgba(255,255,255,0.03)';
                btn.style.borderColor = 'rgba(255,255,255,0.08)';
                btn.style.color = '#9ca3af';
                btn.style.boxShadow = 'none';
            });

            // Appliquer le style au bouton sélectionné
            if (btnElement) {
                btnElement.style.background = 'rgba(6,182,212,0.1)';
                btnElement.style.borderColor = 'rgba(6,182,212,0.3)';
                btnElement.style.color = '#22d3ee';
                btnElement.style.boxShadow = '0 0 15px rgba(6,182,212,0.2)';
            }

            updatePreview();
        }

        function updatePreview() {
            const val = parseFloat(document.getElementById('amountInput').value) || 0;
            const net = Math.round(val * (1 - FEE));
            if (val >= MIN_W) {
                document.getElementById('netAmount').textContent = net.toLocaleString('fr-FR') + ' ' + CURRENCY;
                document.getElementById('feePreview').classList.remove('hidden');
            } else {
                document.getElementById('feePreview').classList.add('hidden');
            }
        }

        document.getElementById('withdrawForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const amountStr = document.getElementById('amountInput').value;
            if (!amountStr) { alert('Veuillez sélectionner un montant.'); return; }
            const amount = parseFloat(amountStr);
            if (amount < MIN_W) { alert('Le montant minimum est de ' + MIN_W + ' ' + CURRENCY); return; }
            const net = Math.round(amount * (1 - FEE));
            document.getElementById('finalNet').textContent = net.toLocaleString('fr-FR') + ' ' + CURRENCY;
            document.getElementById('confirmedAmount').value = amount;
            document.getElementById('confirmModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</x-layouts>
