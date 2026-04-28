<x-admin-layout :title="'Modifier ' . $user->username" :level="'admin'">

<div class="max-w-2xl space-y-6">

    <div>
        <a href="{{ route('admin.users.show', $user->id) }}" style="font-size: 12px; color: #4b5563; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left text-xs"></i> Retour au profil
        </a>
        <h1 class="text-2xl font-bold text-white mt-3">Modifier — {{ $user->username }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="card-admin p-6 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Nom d'utilisateur</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="input-dark">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-dark">
            </div>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input-dark">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Niveau VIP</label>
                <select name="level" class="input-dark">
                    @for($i = 0; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('level', $user->level) == $i ? 'selected' : '' }} style="background: var(--admin-card);">
                            VIP {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Rôle</label>
                <select name="role" class="input-dark">
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }} style="background: var(--admin-card);">Utilisateur</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }} style="background: var(--admin-card);">Administrateur</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Solde actuel ({{ $user->currency }})</label>
                <input type="number" name="account_balance" value="{{ old('account_balance', round($user->account_balance ?? 0)) }}" step="1" min="0" required class="input-dark font-bold text-cyan-400">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Tours Lucky Wheel</label>
                <input type="number" name="lucky_spins" value="{{ old('lucky_spins', $user->lucky_spins ?? 0) }}" step="1" min="0" required class="input-dark font-bold text-amber-400">
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-primary-admin py-3 px-8 text-sm">
                Enregistrer
            </button>
        </div>
    </form>
</div>

</x-admin-layout>