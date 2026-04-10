<x-layouts :title="'Mon Équipe'" :level="Auth::user()->level">

@php
    $USD_TO_XAF = env('USD_TO_XAF', 600);
@endphp

<div class="max-w-7xl mx-auto px-5 py-10">

    <!-- Bannière premium -->
    <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 sm:mb-12">
        <img src="{{ asset('images/equipe.jpg') }}" alt="Mon Équipe" class="w-full h-48 sm:h-80 object-cover brightness-75">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-2 sm:mb-3">Mon Équipe VIP</h1>
            <p class="text-sm sm:text-xl opacity-90">Suivez la croissance de votre réseau et vos gains passifs</p>
        </div>
    </div>

    <!-- Cartes de gains -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-8 mb-8 sm:mb-12">
        @php
            $gainCards = [
                ['label' => 'Parrainage VIP', 'usd' => $gainsParrainageVip, 'from' => 'emerald', 'to' => 'emerald', 'icon' => 'crown'],
                ['label' => 'Revenus journaliers', 'usd' => $gainsJournalier, 'from' => 'blue', 'to' => 'blue', 'icon' => 'chart-line'],
                ['label' => 'Gains totaux réseau', 'usd' => $gainsTotaux, 'from' => 'amber', 'to' => 'amber', 'icon' => 'trophy'],
            ];
        @endphp
        @foreach($gainCards as $card)
            <div class="bg-gradient-to-br from-{{$card['from']}}-500 to-{{$card['to']}}-600 text-white rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                        <i class="fas fa-{{$card['icon']}} text-xl sm:text-3xl"></i>
                    </div>
                    <span class="text-2xl sm:text-4xl font-bold">{{ fmtUsd($card['usd'] ?? 0) }}</span>
                </div>
                <p class="{{$card['from']}}-100 text-[10px] sm:text-sm uppercase tracking-wider opacity-80">{{ $card['label'] }}</p>
                <p class="text-xl sm:text-3xl font-black mt-1 sm:mt-2">{{ fmtXaf(($card['usd'] ?? 0) * $USD_TO_XAF) }}</p>
            </div>
        @endforeach
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 text-center border border-slate-100">
            <p class="text-slate-400 text-[10px] sm:text-xs uppercase tracking-widest font-bold">Total équipe</p>
            <p class="text-3xl sm:text-5xl font-black text-slate-800 mt-2">{{ $taille_equipe ?? 0 }}</p>
            <p class="text-[10px] text-slate-400 mt-1 uppercase">3 niveaux</p>
        </div>
        <div class="bg-emerald-50 rounded-2xl shadow-lg p-4 sm:p-6 text-center border border-emerald-100">
            <p class="text-emerald-700 text-[10px] sm:text-xs uppercase tracking-widest font-bold">Niveau 1</p>
            <p class="text-3xl sm:text-5xl font-black text-emerald-800 mt-2">{{ count($niveau1 ?? []) }}</p>
        </div>
        <div class="bg-blue-50 rounded-2xl shadow-lg p-4 sm:p-6 text-center border border-blue-100">
            <p class="text-blue-700 text-[10px] sm:text-xs uppercase tracking-widest font-bold">Niveau 2</p>
            <p class="text-3xl sm:text-5xl font-black text-blue-800 mt-2">{{ count($niveau2 ?? []) }}</p>
        </div>
        <div class="bg-amber-50 rounded-2xl shadow-lg p-4 sm:p-6 text-center border border-amber-100">
            <p class="text-amber-700 text-[10px] sm:text-xs uppercase tracking-widest font-bold">Niveau 3</p>
            <p class="text-3xl sm:text-5xl font-black text-amber-800 mt-2">{{ count($niveau3 ?? []) }}</p>
        </div>
    </div>

    <!-- Tableaux par niveau avec Bonus généré -->
    <div class="space-y-12">
        @foreach([1 => $niveau1 ?? collect(), 2 => $niveau2 ?? collect(), 3 => $niveau3 ?? collect()] as $level => $membres)
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 text-white p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-extrabold">
                            Niveau {{ $level }}
                            <span class="text-lg font-normal opacity-90">({{ $membres->count() }} membre{{ $membres->count() > 1 ? 's' : '' }})</span>
                        </h3>
                        <div class="flex gap-3">
                            <span class="px-4 py-2 bg-emerald-600 rounded-full text-sm font-medium">
                                {{ $level == 1 ? '20%' : ($level == 2 ? '7%' : '2%') }} commission
                            </span>
                        </div>
                    </div>
                </div>

                @if($membres->count() > 0)
                    <!-- Liste version DESKTOP -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b-2 border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-700">Membre</th>
                                    <th class="px-6 py-4 text-left font-semibold text-slate-700">Téléphone</th>
                                    <th class="px-6 py-4 text-center font-semibold text-slate-700">VIP</th>
                                    <th class="px-6 py-4 text-center font-semibold text-slate-700">Inscrit le</th>
                                    <th class="px-6 py-4 text-right font-semibold text-slate-700">Dépôts</th>
                                    <th class="px-6 py-4 text-right font-semibold text-emerald-600">Bonus généré</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($membres as $filleul)
                                    @php
                                        $deposits = $filleul->deposits_sum ?? 0;
                                        $bonusGenere = App\Models\Transaction::where('user_id', Auth::id())
                                            ->where('type', 'bonus_vip')
                                            ->where('from_user_id', $filleul->id)
                                            ->sum('montant');
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="openMemberModal({{ $filleul->id }})">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                    {{ strtoupper(substr($filleul->username ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $filleul->username }}</p>
                                                    <p class="text-xs text-slate-500">Membre VIP</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5 font-mono text-slate-700">{{ $filleul->phone ?? '—' }}</td>
                                        <td class="px-10 py-5 text-center">
                                            <span class="px-3 py-1 bg-emerald-600 text-white rounded-full text-xs font-bold">{{ $filleul->level ?? 0 }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-center text-slate-600">{{ $filleul->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-5 text-right">
                                            <p class="font-bold text-slate-800">{{ fmtUsd($deposits) }}</p>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <p class="font-bold text-emerald-600">{{ fmtUsd($bonusGenere) }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Liste version MOBILE -->
                    <div class="md:hidden divide-y divide-slate-100">
                        @foreach($membres as $filleul)
                            @php
                                $deposits = $filleul->deposits_sum ?? 0;
                                $bonusGenere = App\Models\Transaction::where('user_id', Auth::id())
                                    ->where('type', 'bonus_vip')
                                    ->where('from_user_id', $filleul->id)
                                    ->sum('montant');
                            @endphp
                            <div class="p-4 flex items-center justify-between gap-4" onclick="openMemberModal({{ $filleul->id }})">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                                        {{ strtoupper(substr($filleul->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $filleul->username }}</p>
                                        <p class="text-[10px] text-slate-500">VIP {{ $filleul->level ?? 0 }} • {{ $filleul->created_at->format('d/m/y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 uppercase">Commission</p>
                                    <p class="font-bold text-emerald-600 text-sm">{{ fmtUsd($bonusGenere) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-16 text-center text-slate-400">
                        <i class="fas fa-users text-6xl mb-4 opacity-20"></i>
                        <p class="text-lg">Aucun membre à ce niveau pour le moment</p>
                        <p class="text-sm mt-2">Continuez à partager votre lien !</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Modal détail membre -->
    <div id="memberModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 p-4">
        <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 sm:p-8 animate__animated animate__zoomIn">
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white text-3xl font-bold">
                    <span id="modalAvatar">U</span>
                </div>
                <h3 id="modalName" class="text-2xl font-black text-slate-800 mt-4"></h3>
                <p id="modalPhone" class="text-slate-500 font-mono"></p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-slate-400 text-[10px] uppercase">Niveau</p>
                    <p id="modalLevel" class="text-2xl font-black text-emerald-600"></p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-slate-400 text-[10px] uppercase">Inscription</p>
                    <p id="modalDate" class="text-sm font-bold text-slate-800"></p>
                </div>
            </div>

            <button onclick="closeMemberModal()" class="mt-8 w-full py-4 bg-slate-900 text-white font-bold rounded-xl active:scale-95 transition">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
    const members = @json($allMembers ?? []);

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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</x-layouts>