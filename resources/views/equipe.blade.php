<x-layouts :title="'Mon Équipe'" :level="Auth::user()->level">

@php
    $USD_TO_XAF = env('USD_TO_XAF', 600);
@endphp

<div class="max-w-7xl mx-auto px-5 py-10">

    <!-- Bannière premium -->
    <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-12">
        <img src="{{ asset('images/equipe.jpg') }}" alt="Mon Équipe" class="w-full h-80 object-cover brightness-75">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-10 text-white">
            <h1 class="text-5xl font-extrabold tracking-tight mb-3">Mon Équipe VIP</h1>
            <p class="text-xl opacity-90">Suivez la croissance de votre réseau et vos gains passifs</p>
        </div>
    </div>

    <!-- Cartes de gains -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-3xl p-8 shadow-xl transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <i class="fas fa-crown text-3xl"></i>
                </div>
                <span class="text-4xl font-bold">{{ fmtUsd($gainsParrainageVip ?? 0) }}</span>
            </div>
            <p class="text-emerald-100 text-sm uppercase tracking-wider">Parrainage VIP</p>
            <p class="text-3xl font-bold mt-2">{{ fmtXaf(($gainsParrainageVip ?? 0) * $USD_TO_XAF) }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-3xl p-8 shadow-xl transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
                <span class="text-4xl font-bold">{{ fmtUsd($gainsJournalier ?? 0) }}</span>
            </div>
            <p class="text-blue-100 text-sm uppercase tracking-wider">Revenus journaliers</p>
            <p class="text-3xl font-bold mt-2">{{ fmtXaf(($gainsJournalier ?? 0) * $USD_TO_XAF) }}</p>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-3xl p-8 shadow-xl transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <i class="fas fa-trophy text-3xl"></i>
                </div>
                <span class="text-4xl font-bold">{{ fmtUsd($gainsTotaux ?? 0) }}</span>
            </div>
            <p class="text-amber-100 text-sm uppercase tracking-wider">Gains totaux réseau</p>
            <p class="text-3xl font-bold mt-2">{{ fmtXaf(($gainsTotaux ?? 0) * $USD_TO_XAF) }}</p>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center border border-slate-100">
            <p class="text-slate-500 text-sm uppercase tracking-wider">Total équipe</p>
            <p class="text-5xl font-extrabold text-slate-800 mt-3">{{ $taille_equipe ?? 0 }}</p>
            <p class="text-xs text-slate-400 mt-2">sur 3 niveaux</p>
        </div>
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl shadow-lg p-6 text-center border-2 border-emerald-200">
            <p class="text-emerald-700 text-sm uppercase tracking-wider">Niveau 1</p>
            <p class="text-5xl font-extrabold text-emerald-700 mt-3">{{ count($niveau1 ?? []) }}</p>
        </div>
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 text-center border-2 border-blue-200">
            <p class="text-blue-700 text-sm uppercase tracking-wider">Niveau 2</p>
            <p class="text-5xl font-extrabold text-blue-700 mt-3">{{ count($niveau2 ?? []) }}</p>
        </div>
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl shadow-lg p-6 text-center border-2 border-amber-200">
            <p class="text-amber-700 text-sm uppercase tracking-wider">Niveau 3</p>
            <p class="text-5xl font-extrabold text-amber-700 mt-3">{{ count($niveau3 ?? []) }}</p>
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
                    <div class="overflow-x-auto">
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
                                        // Dépôts du filleul
                                        $deposits = $filleul->deposits_sum ?? 0;
                                        //bonus genere par le filleul
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
                                                    <p class="text-xs text-slate-500">{{ $filleul->name ?? 'Membre VIP' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5 font-mono text-slate-700">{{ $filleul->phone ?? 'Non renseigné' }}</td>
                                        <td class="px-10 py-5 ">
                                            <span class="px-4 py-2 rounded-full text-white font-bold text-sm
                                                {{ $filleul->level >= 5 ? 'bg-gradient-to-r from-amber-500 to-orange-600' : 'bg-emerald-600' }}">
                                                {{ $filleul->level ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-center text-slate-600">{{ $filleul->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-5 text-right">
                                            <p class="font-bold text-slate-800">{{ fmtUsd($deposits) }}</p>
                                            <p class="text-xs text-slate-500">{{ fmtXaf($deposits * $USD_TO_XAF) }}</p>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <p class="font-bold text-emerald-600">{{ fmtUsd($bonusGenere) }}</p>
                                            <p class="text-xs text-emerald-500">{{ fmtXaf($bonusGenere * $USD_TO_XAF) }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

    <!-- Modal détail membre (inchangé) -->
    <div id="memberModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 p-5">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
            <div class="text-center mb-8">
                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white text-4xl font-bold shadow-xl">
                    <span id="modalAvatar">U</span>
                </div>
                <h3 id="modalName" class="text-3xl font-extrabold text-slate-800 mt-6"></h3>
                <p id="modalPhone" class="text-slate-600 text-lg"></p>
            </div>

            <div class="space-y-6 text-center">
                <div class="bg-slate-50 rounded-2xl p-6">
                    <p class="text-slate-500 text-sm">Niveau VIP</p>
                    <p id="modalLevel" class="text-4xl font-bold text-emerald-600"></p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-6">
                    <p class="text-slate-500 text-sm">Inscrit le</p>
                    <p id="modalDate" class="text-xl font-semibold text-slate-800"></p>
                </div>
            </div>

            <button onclick="closeMemberModal()" class="mt-10 w-full py-5 bg-slate-800 text-white font-bold rounded-2xl hover:bg-slate-900 transition">
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