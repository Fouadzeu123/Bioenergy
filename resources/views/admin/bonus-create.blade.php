<x-admin-layout :title="'Nouveau Code Bonus'" :level="'admin'">

<div class="max-w-2xl space-y-6">

    <div>
        <a href="{{ route('admin.bonus.index') }}" style="font-size: 12px; color: #4b5563; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left text-xs"></i> Retour aux codes
        </a>
        <h1 class="text-2xl font-bold text-white mt-3">Nouveau Code Bonus</h1>
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

    <!-- Formulaire -->
    <div class="card-admin p-6 space-y-8">
        <form action="{{ route('admin.bonus.store') }}" method="POST">
            @csrf

            <!-- Code + Générateur -->
            <div class="mb-6">
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Code promotionnel</label>
                <div class="flex gap-2">
                    <input type="text" name="code" id="bonusCode" value="{{ old('code') }}" required maxlength="20"
                           class="input-dark flex-1 text-center text-xl font-mono tracking-wider py-4"
                           placeholder="Appuyez sur Générer">
                    <button type="button" onclick="generateCode()"
                            class="btn-primary-admin font-bold px-4 py-4 whitespace-nowrap" style="background: linear-gradient(135deg, #a855f7, #ec4899); border: none; box-shadow: 0 4px 15px rgba(236,72,153,0.3);">
                        Générer
                    </button>
                </div>
                @error('code')
                    <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <!-- Montant -->
            <div class="mb-6">
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Montant du bonus (FCFA)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-emerald-400 font-bold">FCFA</span>
                    <input type="number" name="montant" value="{{ old('montant') }}" required min="1" step="1"
                           class="input-dark w-full text-center text-4xl font-bold py-5 pl-14">
                </div>
                @error('montant')
                    <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <!-- Utilisations max -->
            <div class="mb-6">
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Utilisations max</label>
                <div class="grid grid-cols-3 gap-3 text-center mb-4">
                    @foreach([5, 10, 50, 100, 500, 1000] as $num)
                        <label class="block cursor-pointer">
                            <input type="radio" name="max_usage_radio" value="{{ $num }}"
                                   {{ old('max_usage', 10) == $num ? 'checked' : '' }} class="hidden peer" onchange="document.getElementById('max_usage_input').value = this.value;">
                            <div class="py-3 rounded-xl font-bold text-lg transition" style="background: var(--admin-card); border: 1px solid rgba(255,255,255,0.05); color: #9ca3af;">
                                {{ $num === 1000 ? 'Illimité' : $num }}
                            </div>
                        </label>
                    @endforeach
                </div>
                <input type="number" name="max_usage" id="max_usage_input" value="{{ old('max_usage', 10) }}" min="1" required
                       class="input-dark mt-4 w-full text-center text-2xl font-bold py-4" style="border-color: rgba(52,211,153,0.3); color: #34d399;"
                       placeholder="ou personnalisé">
                @error('max_usage')
                    <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <style>
                input[type="radio"]:checked + div {
                    background: rgba(16,185,129,0.15) !important;
                    border-color: rgba(16,185,129,0.5) !important;
                    color: #34d399 !important;
                }
            </style>

            <!-- Boutons -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary-admin py-3 px-8 text-sm">
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