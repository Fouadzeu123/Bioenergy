<x-layouts :title="'Mon Équipe'">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-8">

    <!-- Header Equipe Sleeker -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-6 text-white shadow-xl">
        <div class="relative z-10">
            <h1 class="text-xl font-bold">Mon Réseau</h1>
            <p class="text-[10px] font-semibold text-gray-400 mt-1">Croissance & Revenus Passifs</p>
            
            <div class="mt-6 flex justify-between items-end">
                <div>
                    <p class="text-[10px] font-semibold opacity-50 mb-1">Taille totale</p>
                    <p class="text-3xl font-bold">{{ $taille_equipe ?? 0 }} <span class="text-[10px] font-medium opacity-50">membres</span></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-semibold opacity-50 mb-1">Gains Totaux</p>
                    <p class="text-lg font-bold text-emerald-400">{{ fmtCurrency($gainsTotaux ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-50">
            <p class="text-[10px] font-bold text-gray-400 mb-1">Parrainage VIP</p>
            <p class="text-sm font-bold text-gray-800">{{ fmtCurrency($gainsParrainageVip ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-50 text-right">
            <p class="text-[10px] font-bold text-gray-400 mb-1">Gains Journaliers</p>
            <p class="text-sm font-bold text-blue-600">{{ fmtCurrency($gainsJournalier ?? 0) }}</p>
        </div>
    </div>

    <!-- Tabs Niveaux Sleeker -->
    <div class="space-y-6">
        @foreach([1 => $niveau1 ?? collect(), 2 => $niveau2 ?? collect(), 3 => $niveau3 ?? collect()] as $level => $membres)
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-[10px] font-bold text-gray-400">Niveau {{ $level }} ({{ $membres->count() }})</h3>
                    <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">
                        {{ $level == 1 ? '15%' : ($level == 2 ? '5%' : '1%') }} com.
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($membres->take(10) as $filleul)
                        @php
                            $bonusGenere = App\Models\Transaction::where('user_id', Auth::id())
                                ->where('type', 'bonus_vip')
                                ->where('from_user_id', $filleul->id)
                                ->sum('montant');
                        @endphp
                        <div class="bg-white rounded-2xl p-4 flex items-center justify-between border border-gray-50 shadow-sm active:scale-95 transition" onclick="openMemberModal({{ $filleul->id }})">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center font-black text-xs uppercase">
                                    {{ substr($filleul->username ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-gray-800">{{ $filleul->username }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">VIP {{ $filleul->level ?? 0 }} • {{ $filleul->created_at->format('d/m/y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-emerald-600">{{ fmtCurrency($bonusGenere) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 bg-gray-50/50 rounded-2xl border border-dashed border-gray-100">
                            <p class="text-[9px] font-black text-gray-300 uppercase">Aucun membre</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Détail Sleeker -->
<div id="memberModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4">
    <div class="bg-white rounded-[40px] shadow-2xl max-w-sm w-full p-8 text-center animate__animated animate__zoomIn">
        <div class="w-20 h-20 mx-auto rounded-[30px] bg-slate-900 flex items-center justify-center text-white text-3xl font-black mb-6">
            <span id="modalAvatar">U</span>
        </div>
        <h3 id="modalName" class="text-2xl font-black text-gray-800"></h3>
        <p id="modalPhone" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1"></p>

        <div class="grid grid-cols-2 gap-4 mt-8">
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-[9px] font-bold text-gray-400 uppercase mb-1">Niveau</p>
                <p id="modalLevel" class="text-sm font-black text-emerald-600"></p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-[9px] font-bold text-gray-400 uppercase mb-1">Depuis</p>
                <p id="modalDate" class="text-sm font-black text-gray-800"></p>
            </div>
        </div>

        <button onclick="closeMemberModal()" class="mt-8 w-full py-4 bg-slate-900 text-white font-black text-xs rounded-2xl active:scale-95 transition">
            FERMER
        </button>
    </div>
</div>

<script>
    const members = @json($niveau1->merge($niveau2)->merge($niveau3)->toArray());

    function openMemberModal(id) {
        const m = members.find(u => u.id == id);
        if (!m) return;

        document.getElementById('modalAvatar').textContent = (m.username?.[0] ?? 'U').toUpperCase();
        document.getElementById('modalName').textContent = m.username;
        document.getElementById('modalPhone').textContent = m.phone || 'Non renseigné';
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