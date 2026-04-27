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

<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-50 flex items-center justify-between cursor-pointer active:scale-95 transition" 
     data-tx="{{ $txJson }}" 
     onclick="openTxModal(this.getAttribute('data-tx'))">
    
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ $bgIcon }} {{ $iconColor }}">
            <i class="fas fa-{{ $icon }} text-sm"></i>
        </div>
        <div>
            <p class="text-xs font-black text-gray-800">{{ $typeLabel }}</p>
            <p class="text-[9px] font-bold text-gray-400 mt-0.5 uppercase tracking-tighter">{{ $tx->created_at->format('d M, H:i') }} @if($tx->method) • {{ $tx->method }} @endif</p>
        </div>
    </div>

    <div class="text-right">
        <p class="text-xs font-black {{ $amountColor }}">
            {{ $amountSign }}{{ fmtCurrency($montant) }}
        </p>
        
        <span class="text-[8px] font-black uppercase tracking-widest mt-1 inline-block
            @if($statusColor === 'green') text-emerald-600
            @elseif($statusColor === 'red') text-red-600
            @elseif($statusColor === 'yellow') text-amber-600
            @else text-gray-400 @endif
        ">
            {{ $statusLabel }}
        </span>
    </div>
</div>
