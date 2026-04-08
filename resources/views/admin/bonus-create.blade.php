<x-admin-layout :title="'Nouveau Code Bonus'" :level="'admin'">

<div class="max-w-full mx-auto py-6 space-y-6">

    <!-- Header compact mobile -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-xl text-center">
        <i class="fas fa-gift text-4xl mb-2"></i>
        <h1 class="text-2xl font-bold">Nouveau Code Bonus</h1>
        <p class="text-sm opacity-90 mt-1">Créez un code en 3 secondes</p>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-3 rounded-xl text-center font-medium text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-800 px-5 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire ultra-compact mobile -->
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-8">
        <form action="{{ route('admin.bonus.store') }}" method="POST">
            @csrf

            <!-- Code + Générateur -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Code promotionnel</label>
                <div class="flex gap-2">
                    <input type="text" name="code" id="bonusCode" value="{{ old('code') }}" required maxlength="20"
                           class="flex-1 text-center text-xl font-mono tracking-wider bg-gray-50 border rounded-xl py-4 focus:ring-2 focus:ring-emerald-500"
                           placeholder="Appuyez sur Générer">
                    <button type="button" onclick="generateCode()"
                            class="bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold px-4 py-4 rounded-xl whitespace-nowrap">
                        Générer
                    </button>
                </div>
                @error('code')
                    <p class="text-red-600 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <!-- Montant -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Montant du bonus ($)</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-2xl text-emerald-600 font-bold">$</span>
                    <input type="number" name="montant" value="{{ old('montant') }}" required min="1" step="0.01"
                           class="w-full text-center text-4xl font-bold bg-gray-50 border rounded-xl py-5 pl-14 focus:ring-2 focus:ring-emerald-500">
                </div>
                @error('montant')
                    <p class="text-red-600 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <!-- Utilisations max -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Utilisations max</label>
                <div class="grid grid-cols-3 gap-3 text-center">
                    @foreach([5, 10, 50, 100, 500, 1000] as $num)
                        <label class="block">
                            <input type="radio" name="max_usage" value="{{ $num }}"
                                   {{ old('max_usage', 10) == $num ? 'checked' : '' }} class="hidden peer">
                            <div class="py-4 bg-gray-100 peer-checked:bg-emerald-600 peer-checked:text-white rounded-xl font-bold text-lg hover:bg-emerald-100 hover:text-emerald-700 transition">
                                {{ $num === 1000 ? 'Illimité' : $num }}
                            </div>
                        </label>
                    @endforeach
                </div>
                <input type="number" name="max_usage" value="{{ old('max_usage', 10) }}" min="1" required
                       class="mt-4 w-full text-center text-2xl font-bold bg-emerald-50 border-2 border-emerald-400 rounded-xl py-4 focus:ring-2 focus:ring-emerald-500"
                       placeholder="ou personnalisé">
                @error('max_usage')
                    <p class="text-red-600 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 pt-4">
                <a href="{{ route('admin.bonus.index') }}"
                   class="flex-1 text-center bg-gray-200 text-gray-700 font-bold py-4 rounded-xl text-sm">
                    Retour
                </a>
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold py-4 rounded-xl text-sm hover:from-emerald-700 hover:to-teal-700 transition">
                    Créer le code
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function generateCode() {
    const prefixes = ['BIO', 'VIP', 'WIN', 'GOLD', 'BOOST', 'MONEY', 'ENERGY', 'GREEN', 'RICH', 'POWER'];
    const suffix = Math.floor(1000 + Math.random() * 900000000);
    const code = prefixes[Math.floor(Math.random() * prefixes.length)] + suffix;
    document.getElementById('bonusCode').value = code;
}
</script>

</x-admin-layout>