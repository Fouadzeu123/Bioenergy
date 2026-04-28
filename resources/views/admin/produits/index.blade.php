<x-admin-layout title="Produits">
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Produits d'investissement</h1>
            <p style="font-size: 13px; color: #4b5563; margin-top: 2px;">Gérez le catalogue des produits disponibles</p>
        </div>
        <a href="{{ route('admin.produits.create') }}" class="btn-primary-admin flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Nouveau produit
        </a>
    </div>

    <!-- Table -->
    <div class="card-admin overflow-hidden">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th class="text-left">Produit</th>
                    <th class="text-left">Niveau VIP</th>
                    <th class="text-left">Montant Min</th>
                    <th class="text-left">Taux / jour</th>
                    <th class="text-left">Limite</th>
                    <th class="text-left">Investisseurs</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $p)
                    <tr>
                        <td>
                            <p class="font-semibold text-white" style="font-size: 13px;">{{ $p->name }}</p>
                            <p style="font-size: 11px; color: #4b5563; margin-top: 2px;">{{ Str::limit($p->description, 50) }}</p>
                        </td>
                        <td>
                            <span class="badge-status badge-gray">VIP {{ $p->level }}</span>
                        </td>
                        <td class="font-semibold text-cyan-400" style="font-size: 13px;">
                            {{ fmtCurrency($p->min_amount ?? 0) }}
                        </td>
                        <td>
                            <span class="badge-status badge-success">{{ $p->rate ?? 0 }}%</span>
                        </td>
                        <td style="color: #6b7280; font-size: 13px;">{{ $p->limit_order }}×</td>
                        <td style="color: #d1d5db; font-size: 13px;">{{ $p->orders->count() }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.produits.edit', $p) }}" class="btn-primary-admin py-1.5 px-3 text-xs">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.produits.destroy', $p) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-admin py-1.5 px-3 text-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-10" style="color: #374151;">
                            <i class="fas fa-box-open text-3xl mb-3 block" style="color: #1f2937;"></i>
                            Aucun produit pour l'instant
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-admin-layout>
