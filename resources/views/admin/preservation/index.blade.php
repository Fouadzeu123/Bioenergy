<x-admin-layout title="Fonds de Préservation">
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Fonds de Préservation</h1>
            <p style="font-size: 13px; color: #4b5563; margin-top: 2px;">Gérez les fonds d'épargne disponibles</p>
        </div>
        <a href="{{ route('admin.preservation.create') }}" class="btn-primary-admin flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Nouveau fond
        </a>
    </div>

    <!-- Table -->
    <div class="card-admin overflow-hidden">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th class="text-left">Fond</th>
                    <th class="text-left">Montant Min</th>
                    <th class="text-left">Taux</th>
                    <th class="text-left">Durée</th>
                    <th class="text-left">Épargnants</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fonds as $f)
                    <tr>
                        <td>
                            <p class="font-semibold text-white" style="font-size: 13px;">{{ $f->name }}</p>
                            <p style="font-size: 11px; color: #4b5563; margin-top: 2px;">{{ Str::limit($f->description, 50) }}</p>
                        </td>
                        <td class="font-semibold text-cyan-400" style="font-size: 13px;">{{ fmtCurrency($f->min_amount) }}</td>
                        <td><span class="badge-status badge-success">{{ $f->rate }}%</span></td>
                        <td style="color: #6b7280; font-size: 13px;">{{ $f->period_days }} jours</td>
                        <td style="color: #d1d5db; font-size: 13px;">{{ $f->epargnes->count() }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.preservation.edit', $f) }}" class="btn-primary-admin py-1.5 px-3 text-xs">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.preservation.destroy', $f) }}" method="POST" onsubmit="return confirm('Supprimer ce fond ?')">
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
                        <td colspan="6" class="text-center py-10" style="color: #374151;">
                            <i class="fas fa-vault text-3xl mb-3 block" style="color: #1f2937;"></i>
                            Aucun fond de préservation
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-admin-layout>
