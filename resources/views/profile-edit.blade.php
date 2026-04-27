<x-layouts :title="'Modifier mon profil'" :level="Auth::user()->level">

<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">

        <!-- Titre élégant -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-green-800 mb-3">
                Modifier mon profil
            </h1>
            <p class="text-gray-600 text-lg">Mettez à jour vos informations personnelles</p>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-100 to-emerald-100 border border-green-300 text-green-800 px-6 py-4 rounded-2xl mb-8 shadow-lg text-center font-semibold animate-pulse">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-6 py-4 rounded-2xl mb-8 shadow-lg">
                <ul class="list-disc pl-6 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire principal -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" 
              class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-green-100">

            @csrf
            @method('PUT')

            <div class="p-8 space-y-10">

                <!-- Photo de profil -->
                <div class="flex flex-col items-center">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-full overflow-hidden ring-8 ring-green-100 ring-offset-4 shadow-xl">
                            @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                                <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                     alt="Photo de profil" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" 
                                     alt="Avatar par défaut" class="w-full h-full object-cover">
                            @endif
                        </div>

                        <!-- Icône appareil photo -->
                        <label for="profile_image" class="absolute bottom-2 right-2 bg-green-600 text-white p-3 rounded-full cursor-pointer shadow-lg hover:bg-green-700 transition transform hover:scale-110">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
                    </div>

                    <p class="mt-4 text-sm text-gray-600">Cliquez sur l'appareil photo pour changer votre image</p>
                    <p class="text-xs text-gray-500">JPG, PNG, WebP • Max 2 Mo</p>
                </div>

                <!-- Informations personnelles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Nom d'utilisateur
                        </label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition text-lg font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <div class="flex items-center gap-3 px-5 py-4 border-2 border-gray-200 rounded-2xl focus-within:border-green-500 transition-all duration-300">
                            <span class="text-green-600 font-bold border-r border-gray-200 pr-3">+{{ $user->country_code }}</span>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="flex-1 bg-transparent focus:outline-none text-lg font-medium">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Adresse email
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-5 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition text-lg font-medium">
                    </div>
                </div>

                <!-- Changement de mot de passe -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-3xl p-8 border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">
                        Changer le mot de passe (facultatif)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Mot de passe actuel
                            </label>
                            <input type="password" name="current_password"
                                   class="w-full px-5 py-4 border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition text-lg">
                            <p class="text-xs text-gray-500 mt-2">Requis pour modifier le mot de passe</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Nouveau mot de passe
                            </label>
                            <input type="password" name="password"
                                   class="w-full px-5 py-4 border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition text-lg">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Confirmer le nouveau mot de passe
                            </label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-5 py-4 border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition text-lg">
                        </div>
                    </div>
                </div>

                <!-- Bouton Enregistrer -->
                <div class="flex justify-center pt-6">
                    <button type="submit"
                            class="bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold text-xl px-12 py-5 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 flex items-center gap-3">
                        Enregistrer les modifications
                        <i class="fas fa-check-circle text-2xl"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Petit lien retour -->
        <div class="text-center mt-8">
            <a href="{{ route('dashboard') }}" class="text-green-600 hover:text-green-800 font-medium underline">
                ← Retour au tableau de bord
            </a>
        </div>
    </div>
</div>

</x-layouts>