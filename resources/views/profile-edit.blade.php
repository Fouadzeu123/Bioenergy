<x-layouts :title="'Éditer le profil'">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-6 pb-24">

    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white text-center" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <h1 class="text-2xl font-bold">Paramètres du Compte</h1>
            <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Mettez à jour votre identité digitale</p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Avatar -->
        <div class="flex flex-col items-center space-y-4">
            <div class="relative">
                <div class="w-28 h-28 rounded-2xl overflow-hidden" style="border: 2px solid rgba(59,130,246,0.3);">
                    @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-blue-400" style="background: rgba(59,130,246,0.12);">
                            {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <label for="profile_image" class="absolute -bottom-2 -right-2 w-10 h-10 rounded-xl flex items-center justify-center cursor-pointer active:scale-90 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2);">
                    <i class="fas fa-camera text-white text-sm"></i>
                </label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
            </div>
            <p class="text-[11px] font-medium" style="color: #4b5563;">Cliquez pour modifier la photo</p>
        </div>

        <!-- Informations Personnelles -->
        <div class="rounded-2xl p-6 space-y-5" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <h3 class="text-[11px] font-semibold text-center" style="color: #4b5563;">Informations Personnelles</h3>

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-[11px] font-semibold px-1" style="color: #4b5563;">Pseudo</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-white outline-none transition"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                           placeholder="Pseudo">
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-semibold px-1" style="color: #4b5563;">Téléphone</label>
                    <div class="flex items-center gap-3 rounded-2xl px-5 py-4" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                        <span class="text-sm font-bold text-blue-400">+{{ $user->country_code }}</span>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="flex-1 bg-transparent text-sm font-semibold text-white outline-none" placeholder="600000000">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-semibold px-1" style="color: #4b5563;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-white outline-none transition"
                           style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                           placeholder="email@example.com">
                </div>
            </div>
        </div>

        <!-- Sécurité -->
        <div class="rounded-2xl p-6 space-y-4" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
            <h3 class="text-[11px] font-semibold text-center" style="color: #4b5563;">Sécurité (Optionnel)</h3>

            <input type="password" name="current_password"
                   class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-center text-white outline-none transition"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                   placeholder="Mot de passe actuel">

            <input type="password" name="password"
                   class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-center text-white outline-none transition"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                   placeholder="Nouveau mot de passe">

            <input type="password" name="password_confirmation"
                   class="w-full rounded-2xl px-5 py-4 text-sm font-semibold text-center text-white outline-none transition"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                   placeholder="Confirmer">
        </div>

        <button type="submit" class="w-full py-5 text-white text-[12px] font-bold rounded-2xl active:scale-95 transition" style="background: linear-gradient(135deg, #2563eb, #0891b2); box-shadow: 0 0 24px rgba(59,130,246,0.3);">
            Sauvegarder les changements
        </button>
    </form>
</div>
</x-layouts>