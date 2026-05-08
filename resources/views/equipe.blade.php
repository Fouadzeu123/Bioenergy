<x-layouts :title="'Mon Équipe'">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Header Equipe -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold">Mon Réseau</h1>
                <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Croissance & Revenus Passifs</p>

                <div class="mt-5">
                    <p class="text-[10px] font-semibold mb-1" style="color: rgba(147,197,253,0.6);">Membres Actifs</p>
                    <p class="text-3xl font-bold">{{ $taille_equipe ?? 0 }} <span class="text-[12px] font-medium" style="color: rgba(255,255,255,0.4);">actifs</span></p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-semibold mb-1" style="color: rgba(147,197,253,0.6);">Gains Totaux</p>
                <p class="text-xl font-bold text-cyan-300">{{ fmtCurrency($gainsTotaux ?? 0) }}</p>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-2xl p-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Parrainage VIP</p>
            <p class="text-sm font-bold text-blue-400">{{ fmtCurrency($gainsParrainageVip ?? 0) }}</p>
        </div>
        <div class="rounded-2xl p-5 text-right" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Gains Journaliers</p>
            <p class="text-sm font-bold text-cyan-400">{{ fmtCurrency($gainsJournalier ?? 0) }}</p>
        </div>
    </div>    <!-- Niveaux -->
    <div class="space-y-8">
        @foreach([1 => $niveau1 ?? collect(), 2 => $niveau2 ?? collect(), 3 => $niveau3 ?? collect()] as $level => $membres)
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-6 rounded-full {{ $level == 1 ? 'bg-blue-500' : ($level == 2 ? 'bg-cyan-500' : 'bg-violet-500') }}"></div>
                        <h3 class="text-[14px] font-bold text-white">Niveau {{ $level }}</h3>
                    </div>
                    <span class="text-[10px] font-extrabold px-3 py-1 rounded-lg" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: #9ca3af;">
                        {{ $membres->count() }} {{ Str::plural('membre', $membres->count()) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @forelse($membres as $filleul)
                        @php
                            $bonusGenere = App\Models\Transaction::where('user_id', Auth::id())
                                ->whereIn('type', ['bonus_vip', 'bonus_journalier'])
                                ->where('from_user_id', $filleul->id)
                                ->sum('montant');
                        @endphp
                        <div class="group relative overflow-hidden rounded-2xl p-4 transition-all active:scale-[0.98]" 
                             style="background: #0d1117; border: 1px solid rgba(255,255,255,0.04);"
                             onclick="openMemberModal({{ $filleul->id }})">
                            
                            <!-- Accent Glass -->
                            <div class="absolute -right-4 -top-4 w-16 h-16 rounded-full opacity-10 blur-xl" 
                                 style="background: {{ $level == 1 ? '#3b82f6' : ($level == 2 ? '#06b6d4' : '#8b5cf6') }}"></div>

                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-lg shadow-inner" 
                                         style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); color: white;">
                                        {{ substr($filleul->phone, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-white tracking-wide">
                                            +{{ $filleul->country_code }} {{ substr($filleul->phone, 0, 3) }}***{{ substr($filleul->phone, -2) }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded {{ $filleul->level >= 3 ? 'bg-amber-500/10 text-amber-500' : 'bg-blue-500/10 text-blue-400' }}">
                                                VIP {{ $filleul->level ?? 0 }}
                                            </span>
                                            <span class="text-[9px] font-medium text-gray-500">
                                                Inscrit le {{ $filleul->created_at->format('d/m/y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[12px] font-black text-emerald-400">+{{ number_format($bonusGenere, 0, '.', ' ') }}</p>
                                    <p class="text-[8px] font-bold text-gray-600 uppercase tracking-tighter">Profit généré</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 rounded-2xl border border-dashed border-white/5 bg-white/[0.01]">
                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-slash text-gray-600 text-xs"></i>
                            </div>
                            <p class="text-[11px] font-bold text-gray-600">Aucun membre actif à ce niveau</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Membre -->
<div id="memberModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center backdrop-blur-sm px-4" style="background: rgba(0,0,0,0.7);">
    <div class="rounded-3xl shadow-2xl max-w-sm w-full p-7 text-center animate__animated animate__zoomIn" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.08);">
        <div class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center text-white text-3xl font-bold mb-5" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 24px rgba(59,130,246,0.3);">
            <span id="modalAvatar">U</span>
        </div>
        <h3 id="modalName" class="text-2xl font-bold text-white"></h3>
        <p id="modalPhone" class="text-[11px] font-medium mt-1" style="color: #4b5563;"></p>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="rounded-2xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Niveau</p>
                <p id="modalLevel" class="text-sm font-bold text-blue-400"></p>
            </div>
            <div class="rounded-2xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                <p class="text-[10px] font-semibold mb-1" style="color: #4b5563;">Depuis</p>
                <p id="modalDate" class="text-sm font-bold text-gray-300"></p>
            </div>
        </div>

        <button onclick="closeMemberModal()" class="mt-6 w-full py-4 text-white font-bold text-[12px] rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2);">
            Fermer
        </button>
    </div>
</div>

<script>
    const members = @json($niveau1->merge($niveau2)->merge($niveau3)->toArray());

    function openMemberModal(id) {
        const m = members.find(u => u.id == id);
        if (!m) return;
        document.getElementById('modalAvatar').textContent = 'U';
        document.getElementById('modalName').textContent = '+' + m.country_code + ' ' + m.phone;
        document.getElementById('modalPhone').textContent = 'Niveau ' + (m.level ?? 0);
        document.getElementById('modalLevel').textContent = 'VIP ' + (m.level ?? 0);
        document.getElementById('modalDate').textContent = new Date(m.created_at).toLocaleDateString('fr-FR');
        document.getElementById('memberModal').classList.remove('hidden');
        document.getElementById('memberModal').classList.add('flex');
    }

    function closeMemberModal() {
        document.getElementById('memberModal').classList.add('hidden');
        document.getElementById('memberModal').classList.remove('flex');
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMemberModal(); });
</script>
</x-layouts>