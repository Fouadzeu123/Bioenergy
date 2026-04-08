<x-layouts :title="'Mon Lien de Parrainage'" :level="Auth::user()->level">

@php
    $refUrl = route('register', ['ref' => Auth::user()->invitation_code]);
    $total = count($niveau1 ?? []) + count($niveau2 ?? []) + count($niveau3 ?? []);
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-emerald-900 to-slate-900">

    <!-- Hero magnifique -->
    <div class="relative h-64 md:h-80 overflow-hidden">
        <img src="{{ asset('images/lien.jpg') }}" alt="Parrainez vos amis"
             class="w-full h-full object-cover brightness-50">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-2xl">
                tracking-tight">
                Faites grandir votre empire
            </h1>
            <p class="text-xl md:text-2xl opacity-90 drop-shadow-lg">
                Partagez votre lien • Gagnez à chaque inscription
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-10 space-y-10">

        <!-- Carte principale : Lien + Actions -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-white mb-2">Votre lien de parrainage</h2>
                <p class="text-green-200">Copiez et partagez-le partout !</p>
            </div>

            <div class="flex flex-col lg:flex-row items-center gap-6">
                <!-- Le lien -->
                <div class="flex-1 w-full">
                    <div class="bg-white/20 border-2 border-dashed border-white/40 rounded-2xl p-5 text-center">
                        <p class="text-xs text-green-200 mb-2">Cliquez pour copier</p>
                        <code id="refLink" class="text-lg md:text-2xl font-mono text-white break-all cursor-pointer select-all"
                              onclick="copyReferralLink()">
                            {{ $refUrl }}
                        </code>
                        <p id="copyMsg" class="text-green-400 text-sm font-bold mt-3 hidden">Lien copié !</p>
                    </div>
                </div>

                <!-- Boutons de partage -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full lg:w-auto">
                    <a href="https://t.me/share/url?url={{ urlencode($refUrl) }}&text={{ urlencode('Rejoins BioEnergy et gagne tous les jours !') }}"
                       target="_blank"
                       class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-2xl text-center shadow-lg hover:scale-110 transition">
                        <i class="fab fa-telegram-plane text-3xl"></i>
                        <p class="text-xs mt-2 font-bold">Telegram</p>
                    </a>

                    <a href="https://wa.me/?text={{ urlencode('Rejoins BioEnergy et gagne tous les jours ! 👉 ' . $refUrl) }}"
                       target="_blank"
                       class="bg-gradient-to-br from-green-500 to-green-600 text-white p-5 rounded-2xl text-center shadow-lg hover:scale-110 transition">
                        <i class="fab fa-whatsapp text-3xl"></i>
                        <p class="text-xs mt-2 font-bold">WhatsApp</p>
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($refUrl) }}"
                       target="_blank"
                       class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-5 rounded-2xl text-center shadow-lg hover:scale-110 transition">
                        <i class="fab fa-facebook-f text-3xl"></i>
                        <p class="text-xs mt-2 font-bold">Facebook</p>
                    </a>

                    <button onclick="copyReferralLink()"
                            class="bg-gradient-to-br from-purple-600 to-pink-600 text-white p-5 rounded-2xl text-center shadow-lg hover:scale-110 transition">
                        <i class="fas fa-copy text-3xl"></i>
                        <p class="text-xs mt-2 font-bold">Copier</p>
                    </button>
                </div>
            </div>

            <!-- Code + Stats rapides -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-10 text-center">
                <div>
                    <p class="text-green-300 text-sm">Votre code</p>
                    <code class="text-2xl font-bold text-white">{{ Auth::user()->invitation_code }}</code>
                </div>
                <div>
                    <p class="text-green-300 text-sm">Total filleuls</p>
                    <p class="text-4xl font-extrabold text-white">{{ $total }}</p>
                </div>
                <div>
                    <p class="text-green-300 text-sm">Niveau 1</p>
                    <p class="text-3xl font-bold text-emerald-400">{{ count($niveau1 ?? []) }}</p>
                </div>
                <div>
                    <p class="text-green-300 text-sm">Équipe complète</p>
                    <p class="text-3xl font-bold text-yellow-400">{{ $total }}</p>
                </div>
            </div>
        </div>

        <!-- Aperçu équipe (style carte premium) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['data' => $niveau1 ?? [], 'level' => 1, 'color' => 'emerald', 'title' => 'Niveau 1 - Filleuls directs'],
                ['data' => $niveau2 ?? [], 'level' =>2, 'color' => 'yellow', 'title' => 'Niveau 2 - Indirects'],
                ['data' => $niveau3 ?? [], 'level' =>3, 'color' => 'purple', 'title' => 'Niveau 3 - Profonds'],
            ] as $item)
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white">{{ $item['title'] }}</h3>
                        <span class="text-3xl font-extrabold text-{{ $item['color'] }}-400">
                            {{ count($item['data']) }}
                        </span>
                    </div>

                    @if(count($item['data']) > 0)
                        <div class="space-y-3">
                            @foreach($item['data']->take(5) as $member)
                                <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl hover:bg-white/20 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-{{ $item['color'] }}-400 to-{{ $item['color'] }}-600 text-white font-bold flex items-center justify-center">
                                            {{ strtoupper(substr($member->username ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $member->username }}</p>
                                            <p class="text-xs text-gray-300">VIP {{ $member->level ?? 1 }}</p>
                                        </div>
                                    </div>
                                    <button onclick="openMemberModal({{ $member->id }})"
                                            class="text-{{ $item['color'] }}-300 hover:text-white text-sm font-medium">
                                        Voir
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        @if(count($item['data']) > 5)
                            <div class="text-center mt-4">
                                <a href="{{ route('team') }}" class="text-green-300 hover:text-white text-sm underline">
                                    Voir les {{ count($item['data']) - 5 }} autres...
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-center text-gray-400 py-10">Aucun membre à ce niveau</p>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Motivation + Bonus -->
        <div class="bg-gradient-to-r from-emerald-600/20 to-teal-600/20 backdrop-blur-xl border border-white/20 rounded-3xl p-8 text-white text-center shadow-2xl">
            <h3 class="text-3xl font-extrabold mb-6">Pourquoi parrainer ?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <div class="text-center">
                    <div class="text-5xl mb-4">15%</div>
                    <p class="font-bold">Sur le premier dépôt de vos filleuls directs</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-4">5%</div>
                    <p class="font-bold">Des revenus journaliers de votre niveau 1</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-4">1-5%</div>
                    <p class="font-bold">Sur les niveaux 2 et 3</p>
                </div>
            </div>
            <button onclick="copyReferralLink()"
                    class="mt-10 bg-white text-emerald-600 font-extrabold text-xl px-12 py-5 rounded-2xl hover:bg-emerald-50 transition transform hover:scale-105 shadow-2xl">
                Copier mon lien & Commencer à gagner
            </button>
        </div>
    </div>

    <!-- Modal membre -->
    <div id="memberModal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center">
            <button onclick="closeMemberModal()" class="float-right text-gray-500 hover:text-gray-700 text-2xl">×</button>
            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-emerald-400 to-teal-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mb-6">
                <span id="modalAvatar">U</span>
            </div>
            <h3 id="modalName" class="text-2xl font-bold text-gray-800 mb-2"></h3>
            <p id="modalPhone" class="text-gray-600 mb-4"></p>
            <p class="text-lg font-semibold text-emerald-600" id="modalLevel"></p>
            <p class="text-sm text-gray-500 mt-4" id="modalDate"></p>
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const link = document.getElementById('refLink').innerText;
    navigator.clipboard.writeText(link);
    const msg = document.getElementById('copyMsg');
    msg.classList.remove('hidden');
    setTimeout(() => msg.classList.add('hidden'), 2000);
}

const members = @json(array_merge(
    $niveau1?->toArray() ?? [],
    $niveau2?->toArray() ?? [],
    $niveau3?->toArray() ?? []
));

function openMemberModal(id) {
    const m = members.find(u => u.id == id);
    if (!m) return;

    document.getElementById('modalAvatar').textContent = (m.username?.[0] ?? 'U').toUpperCase();
    document.getElementById('modalName').textContent = m.username;
    document.getElementById('modalPhone').textContent = m.phone || 'Non renseigné';
    document.getElementById('modalLevel').textContent = 'VIP ' + (m.level ?? 1);
    document.getElementById('modalDate').textContent = new Date(m.created_at).toLocaleDateString('fr-FR');

    document.getElementById('memberModal').classList.remove('hidden');
    document.getElementById('memberModal').classList.add('flex');
}

function closeMemberModal() {
    document.getElementById('memberModal').classList.add('hidden');
    document.getElementById('memberModal').classList.remove('flex');
}
</script>

</x-layouts>