<x-admin-layout title="Nouveau produit">
<div class="max-w-2xl space-y-6">

    <div>
        <a href="{{ route('admin.produits.index') }}" style="font-size: 12px; color: #4b5563; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left text-xs"></i> Retour aux produits
        </a>
        <h1 class="text-2xl font-bold text-white mt-3">Nouveau produit</h1>
    </div>

    <form action="{{ route('admin.produits.store') }}" method="POST" enctype="multipart/form-data" class="card-admin p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Nom du produit</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="input-dark" placeholder="Pack Solaire Premium">
                @error('name') <p style="font-size: 11px; color: #f87171; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Niveau VIP requis</label>
                <input type="number" name="level" value="{{ old('level', 1) }}" min="0" required class="input-dark" placeholder="1">
            </div>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Image du produit (Optionnel)</label>
            <input type="file" name="image" accept="image/*" class="input-dark bg-slate-800 p-2 text-xs">
            @error('image') <p style="font-size: 11px; color: #f87171; margin-top: 4px;">{{ $message }}</p> @enderror
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Description</label>
            <textarea name="description" rows="3" class="input-dark" placeholder="Description du produit...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Montant minimum</label>
                <input type="number" name="min_amount" value="{{ old('min_amount') }}" min="0" step="100" required class="input-dark" placeholder="5000">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Montant maximum</label>
                <input type="number" name="max_amount" value="{{ old('max_amount') }}" min="0" step="100" class="input-dark" placeholder="Illimité">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Taux journalier (%)</label>
                <input type="number" name="rate" value="{{ old('rate') }}" min="0" step="0.01" required class="input-dark" placeholder="1.5">
            </div>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Limite d'achat par utilisateur</label>
            <input type="number" name="limit_order" value="{{ old('limit_order', 1) }}" min="1" required class="input-dark" placeholder="1">
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="btn-primary-admin">Créer le produit</button>
        </div>
    </form>
</div>
</x-admin-layout>
