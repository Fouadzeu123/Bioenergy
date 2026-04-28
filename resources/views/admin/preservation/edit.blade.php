<x-admin-layout title="Modifier le fond">
<div class="max-w-2xl space-y-6">

    <div>
        <a href="{{ route('admin.preservation.index') }}" style="font-size: 12px; color: #4b5563; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left text-xs"></i> Retour aux fonds
        </a>
        <h1 class="text-2xl font-bold text-white mt-3">Modifier — {{ $preservation->name }}</h1>
    </div>

    <form action="{{ route('admin.preservation.update', $preservation) }}" method="POST" class="card-admin p-6 space-y-5">
        @csrf @method('PUT')

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Nom du fond</label>
            <input type="text" name="name" value="{{ old('name', $preservation->name) }}" required class="input-dark">
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Description</label>
            <textarea name="description" rows="3" class="input-dark">{{ old('description', $preservation->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Montant minimum</label>
                <input type="number" name="min_amount" value="{{ old('min_amount', $preservation->min_amount) }}" min="0" step="100" required class="input-dark">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Taux de rendement (%)</label>
                <input type="number" name="rate" value="{{ old('rate', $preservation->rate) }}" min="0" step="0.1" required class="input-dark">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Durée (jours)</label>
                <input type="number" name="period_days" value="{{ old('period_days', $preservation->period_days) }}" min="1" required class="input-dark">
            </div>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Limite d'utilisation par utilisateur</label>
            <input type="number" name="limit_order" value="{{ old('limit_order', $preservation->limit_order) }}" min="1" class="input-dark">
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="btn-primary-admin">Enregistrer les modifications</button>
        </div>
    </form>
</div>
</x-admin-layout>
