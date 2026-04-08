<x-layouts>
<div class="p-4">
    <h2 class="text-xl font-bold text-green-700 mb-4">📨 Mes notifications</h2>

    <div class="mb-4">
        <form method="POST" action="{{ route('messages.markAllAsRead') }}">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-center">
                ✅ Tout marquer comme lu
            </button>
        </form>
    </div>

    @forelse($notifications as $notif)
        <div class="bg-white shadow rounded-lg p-4 mb-3 border-l-4 {{ $notif->is_read ? 'border-gray-300' : 'border-green-500' }}">
            <p class="text-gray-800">{{ $notif->content }}</p>
            <p class="text-xs text-gray-500">{{ $notif->created_at->diffForHumans() }}</p>
        </div>
    @empty
        <p class="text-gray-600">Aucune notification pour le moment.</p>
    @endforelse
</div>


</x-layouts>