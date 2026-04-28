<x-layouts :title="'Notifications'">
<div class="max-w-xl mx-auto pt-5 px-4 space-y-5 pb-24">

    <!-- Header Notifications -->
    <div class="relative overflow-hidden rounded-[2rem] p-7 text-white text-center" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0e7490 100%); box-shadow: 0 0 40px rgba(30,64,175,0.35);">
        <div class="relative z-10">
            <h1 class="text-2xl font-bold">Messagerie</h1>
            <p class="text-[11px] font-medium mt-1" style="color: rgba(147,197,253,0.8);">Dernières alertes & notifications</p>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.05); filter: blur(30px);"></div>
    </div>

    @if($notifications->where('is_read', false)->count() > 0)
    <div class="flex justify-center">
        <form method="POST" action="{{ route('messages.markAllAsRead') }}">
            @csrf
            <button type="submit" class="px-6 py-2.5 rounded-full text-[11px] font-semibold active:scale-95 transition" style="background: rgba(59,130,246,0.12); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2);">
                Tout marquer comme lu
            </button>
        </form>
    </div>
    @endif

    <div class="space-y-3">
        @forelse($notifications as $notif)
            <div class="rounded-2xl p-5 flex items-start gap-4 transition {{ $notif->is_read ? 'opacity-40' : '' }}" style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="{{ $notif->is_read ? 'background: rgba(107,114,128,0.12);' : 'background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.2);' }}">
                    <i class="fas fa-{{ $notif->is_read ? 'envelope-open' : 'envelope' }} text-xs {{ $notif->is_read ? 'text-gray-600' : 'text-blue-400' }}"></i>
                </div>
                <div class="space-y-1 flex-1">
                    <p class="text-[12px] font-semibold text-gray-200 leading-relaxed">{{ $notif->content }}</p>
                    <p class="text-[10px] font-medium" style="color: #4b5563;">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <i class="fas fa-inbox text-2xl" style="color: #1f2937;"></i>
                </div>
                <p class="text-[12px] font-semibold" style="color: #374151;">Aucune notification</p>
            </div>
        @endforelse
    </div>
</div>
</x-layouts>