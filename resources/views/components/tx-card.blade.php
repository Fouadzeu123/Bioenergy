@props(['tx', 'types', 'statuses'])

@php
    $montant = $tx->montant;
    $typeLabel = $types[$tx->type] ?? ucfirst($tx->type);
    
    // Configurer l'icône, la couleur, selon le type
    $icon = 'exchange-alt';
    $iconColor = 'text-gray-600';
    $bgIcon = 'bg-gray-100';
    $amountColor = 'text-gray-800';
    $amountSign = '';

    if ($tx->type === 'depot') {
        $icon = 'arrow-down';
        $iconColor = 'text-green-600';
        $bgIcon = 'bg-green-100';
        $amountColor = 'text-green-600';
        $amountSign = '+';
    } elseif ($tx->type === 'retrait') {
        $icon = 'arrow-up';
        $iconColor = 'text-red-600';
        $bgIcon = 'bg-red-100';
        $amountColor = 'text-red-600';
        $amountSign = '-';
    } elseif (in_array($tx->type, ['bonus_vip', 'bonus_journalier', 'gain_journalier', 'parrainage', 'bonus', 'cadeau'])) {
        $icon = 'gift';
        $iconColor = 'text-purple-600';
        $bgIcon = 'bg-purple-100';
        $amountColor = 'text-purple-600';
        $amountSign = '+';
    } elseif ($tx->type === 'achat') {
        $icon = 'shopping-cart';
        $iconColor = 'text-blue-600';
        $bgIcon = 'bg-blue-100';
        $amountColor = 'text-blue-600';
        $amountSign = '-';
    }

    $statusData = $statuses[$tx->status] ?? ['label' => ucfirst($tx->status), 'color' => 'gray'];
    $statusColor = $statusData['color'];
    $statusLabel = $statusData['label'];

    $txJson = json_encode([
        'reference' => $tx->reference,
        'type' => $tx->type,
        'montant' => $montant,
        'method' => $tx->method,
        'status' => $tx->status,
        'created_at' => $tx->created_at ? $tx->created_at->toDateTimeString() : null,
        'description' => $tx->description,
    ], JSON_UNESCAPED_UNICODE);
@endphp

<div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow cursor-pointer mb-4" 
     data-tx="{{ $txJson }}" 
     onclick="openTxModal(this.getAttribute('data-tx'))">
    <div class="flex items-center justify-between">
        
        <!-- Icon & Info -->
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl {{ $bgIcon }} {{ $iconColor }}">
                <i class="fas fa-{{ $icon }} text-xl"></i>
            </div>
            <div>
                <p class="font-bold text-gray-800 text-base">{{ $typeLabel }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-400"><i class="far fa-calendar-alt mr-1"></i>{{ $tx->created_at->format('d M, H:i') }}</span>
                    @if($tx->method)
                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 uppercase">{{ $tx->method }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Amount & Status -->
        <div class="text-right">
            <p class="font-bold {{ $amountColor }} text-lg">
                {{ $amountSign }}{{ fmtCurrency($montant) }}
            </p>
            
            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide mt-1
                @if($statusColor === 'green') bg-green-100 text-green-700
                @elseif($statusColor === 'red') bg-red-100 text-red-700
                @elseif($statusColor === 'yellow') bg-yellow-100 text-yellow-700
                @else bg-gray-100 text-gray-700 @endif
            ">
                {{ $statusLabel }}
            </span>
        </div>

    </div>
    
    <!-- Reference (click to copy) -->
    <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between">
        <div class="text-xs text-gray-400 font-mono flex items-center gap-2" onclick="event.stopPropagation(); copyText('{{ $tx->reference }}', this)">
            <i class="far fa-copy cursor-pointer hover:text-gray-700"></i>
            <span class="truncate max-w-[150px] sm:max-w-none">{{ $tx->reference }}</span>
            <span class="copy-feedback text-green-500 font-bold hidden ml-2">Copié!</span>
        </div>
        <div class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1">
            Détails <i class="fas fa-chevron-right text-[10px]"></i>
        </div>
    </div>
</div>
