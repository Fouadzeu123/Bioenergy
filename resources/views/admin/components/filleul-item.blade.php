<div class="flex items-center justify-between p-4 rounded-xl" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" style="background: rgba(59,130,246,0.1); color: #60a5fa;">
            {{ substr($f->phone, 0, 1) }}
        </div>
        <div>
            <p class="font-bold text-white text-sm">+{{ $f->country_code }} {{ $f->phone }}</p>
            <div class="flex gap-2 items-center mt-0.5">
                <span class="text-[10px] text-gray-500"><i class="fas fa-calendar-alt mr-1"></i> {{ $f->created_at->format('d/m/Y') }}</span>
                <span class="text-[10px] font-bold text-cyan-400">VIP {{ $f->level ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <div class="text-right">
            <p class="text-[11px] font-bold text-white">{{ number_format($f->account_balance, 0, '.', ' ') }} {{ $f->currency }}</p>
            <p class="text-[9px] text-gray-600">Solde actuel</p>
        </div>
        <a href="{{ route('admin.users.show', $f->id) }}" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 transition">
            <i class="fas fa-chevron-right text-xs"></i>
        </a>
    </div>
</div>
