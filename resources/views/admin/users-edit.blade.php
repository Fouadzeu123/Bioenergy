<x-admin-layout :title="'Modifier ' . $user->username" :level="'admin'">

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-8 text-white">
            <h1 class="text-3xl font-extrabold">Modifier le profil</h1>
            <p class="mt-2 opacity-90">Utilisateur : <strong>{{ $user->username }}</strong></p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="p-8 space-y-8">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom d'utilisateur</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Niveau VIP</label>
                    <select name="level" class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('level', $user->level) == $i ? 'selected' : '' }}>
                                VIP {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rôle</label>
                    <select name="role" class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Solde actuel ($)</label>
                    <input type="number" name="account_balance" value="{{ old('account_balance', $user->account_balance ?? 0) }}" step="0.01" min="0" required
                           class="w-full border rounded-xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 text-lg font-bold">
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.users.show', $user->id) }}"
                   class="px-8 py-4 bg-gray-200 rounded-xl font-bold hover:bg-gray-300 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="px-8 py-4 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition">
                    Sauvegarder les modifications
                </button>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>