<x-layouts :title="'Éditer le profil'">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-10 pb-20">

    <!-- Header Profil Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl text-center">
        <h1 class="text-2xl font-bold">Paramètres du Compte</h1>
        <p class="text-[11px] font-semibold text-gray-400 mt-2">Mettez à jour votre identité digitale</p>
        <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Formulaire Sleeker -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Avatar Selection -->
        <div class="flex flex-col items-center space-y-6">
            <div class="relative">
                <div class="w-32 h-32 rounded-[40px] overflow-hidden bg-slate-50 border-4 border-white shadow-xl">
                    @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" class="w-full h-full object-cover opacity-50 grayscale">
                    @endif
                </div>
                <label for="profile_image" class="absolute -bottom-2 -right-2 w-12 h-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg cursor-pointer active:scale-90 transition">
                    <i class="fas fa-camera text-sm"></i>
                </label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
            </div>
            <p class="text-[10px] font-bold text-gray-400">Cliquez pour modifier la photo</p>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-50 space-y-6">
            <h3 class="text-[10px] font-bold text-gray-400 text-center">Informations Personnelles</h3>
            
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 px-2">Pseudo</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold focus:bg-white focus:border-emerald-500 transition outline-none"
                           placeholder="Pseudo">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 px-2">Téléphone</label>
                    <div class="flex items-center gap-4 bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4">
                        <span class="text-sm font-bold text-emerald-600">+{{ $user->country_code }}</span>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="flex-1 bg-transparent text-sm font-bold outline-none" placeholder="600000000">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 px-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold focus:bg-white focus:border-emerald-500 transition outline-none"
                           placeholder="email@example.com">
                </div>
            </div>
        </div>

        <!-- Password Card -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-50 space-y-6">
            <h3 class="text-[10px] font-bold text-gray-400 text-center">Sécurité (Optionnel)</h3>
            
            <div class="space-y-6">
                <input type="password" name="current_password"
                       class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none"
                       placeholder="MOT DE PASSE ACTUEL">
                
                <div class="grid grid-cols-1 gap-4">
                    <input type="password" name="password"
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none"
                           placeholder="NOUVEAU MOT DE PASSE">
                    
                    <input type="password" name="password_confirmation"
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-center focus:bg-white focus:border-emerald-500 transition outline-none"
                           placeholder="CONFIRMER">
                </div>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-6 bg-slate-900 text-white text-[11px] font-bold rounded-2xl shadow-xl active:scale-95 transition">
                Sauvegarder les changements
            </button>
        </div>
    </form>
</div>
</x-layouts>