<x-layouts :title="'Notifications'">
<div class="max-w-xl mx-auto pt-6 px-4 space-y-10 pb-20">

    <!-- Header Notifications Sleeker -->
    <div class="relative overflow-hidden rounded-[40px] bg-slate-900 p-10 text-white shadow-2xl text-center">
        <h1 class="text-2xl font-bold">Centre de Messagerie</h1>
        <p class="text-[11px] font-semibold text-gray-400 mt-2">Dernières alertes & notifications</p>
        <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    @if($notifications->where('is_read', false)->count() > 0)
    <div class="flex justify-center">
        <form method="POST" action="{{ route('messages.markAllAsRead') }}">
            @csrf
            <button type="submit" class="bg-emerald-500/10 text-emerald-400 px-6 py-2.5 rounded-full text-[10px] font-bold border border-emerald-500/20 active:scale-95 transition">
                Tout marquer comme lu
            </button>
        </form>
    </div>
    @endif

    <div class="space-y-4">
        @forelse($notifications as $notif)
            <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 flex items-start gap-4 {{ $notif->is_read ? 'opacity-50' : '' }}">
                <div class="w-10 h-10 rounded-2xl {{ $notif->is_read ? 'bg-slate-50 text-slate-400' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-{{ $notif->is_read ? 'envelope-open' : 'envelope' }} text-xs"></i>
                </div>
                <div class="space-y-1 flex-1">
                    <p class="text-[12px] font-bold text-gray-800 leading-relaxed">{{ $notif->content }}</p>
                    <p class="text-[10px] font-medium text-gray-400">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-inbox text-slate-200 text-2xl"></i>
                </div>
                <p class="text-[11px] font-bold text-gray-300">Aucune notification</p>
            </div>
        @endforelse
    </div>
</div>
</x-layouts>