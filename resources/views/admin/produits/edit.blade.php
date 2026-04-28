<x-admin-layout title="Modifier le produit">
<div class="max-w-2xl space-y-6">

    <div>
        <a href="{{ route('admin.produits.index') }}" style="font-size: 12px; color: #4b5563; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left text-xs"></i> Retour aux produits
        </a>
        <h1 class="text-2xl font-bold text-white mt-3">Modifier — {{ $produit->name }}</h1>
    </div>

    <form action="{{ route('admin.produits.update', $produit) }}" method="POST" enctype="multipart/form-data" class="card-admin p-6 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Nom du produit</label>
                <input type="text" name="name" value="{{ old('name', $produit->name) }}" required class="input-dark">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Niveau VIP requis</label>
                <input type="number" name="level" value="{{ old('level', $produit->level) }}" min="0" max="5" required class="input-dark">
            </div>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Image du produit (Optionnel)</label>
            @if($produit->image)
                <div class="mb-2">
                    <img src="{{ asset($produit->image) }}" alt="Image actuelle" class="w-20 h-20 rounded-xl object-cover" style="border: 1px solid rgba(255,255,255,0.1);">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="input-dark bg-slate-800 p-2 text-xs">
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Description</label>
            <textarea name="description" rows="3" class="input-dark">{{ old('description', $produit->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Montant minimum</label>
                <input type="number" name="min_amount" value="{{ old('min_amount', $produit->min_amount) }}" min="0" step="100" required class="input-dark">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Montant maximum</label>
                <input type="number" name="max_amount" value="{{ old('max_amount', $produit->max_amount) }}" min="0" step="100" class="input-dark">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Taux journalier (%)</label>
                <input type="number" name="rate" value="{{ old('rate', $produit->rate) }}" min="0" step="0.01" required class="input-dark">
            </div>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 600; color: #4b5563; display: block; margin-bottom: 6px;">Limite d'achat par utilisateur</label>
            <input type="number" name="limit_order" value="{{ old('limit_order', $produit->limit_order) }}" min="1" required class="input-dark">
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="btn-primary-admin">Enregistrer les modifications</button>
        </div>
    </form>
</div>
</x-admin-layout>
