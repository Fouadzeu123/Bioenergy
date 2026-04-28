@props(['tx', 'types', 'statuses'])

@php
    $montant = $tx->montant;
    $typeLabel = $types[$tx->type] ?? ucfirst($tx->type);

    $icon = 'exchange-alt';
    $iconBg = 'rgba(107,114,128,0.12)';
    $iconColor = '#9ca3af';
    $amountColor = '#9ca3af';
    $amountSign = '';

    if ($tx->type === 'depot') {
        $icon = 'arrow-down';
        $iconBg = 'rgba(59,130,246,0.12)';
        $iconColor = '#60a5fa';
        $amountColor = '#60a5fa';
        $amountSign = '+';
    } elseif ($tx->type === 'retrait') {
        $icon = 'arrow-up';
        $iconBg = 'rgba(239,68,68,0.12)';
        $iconColor = '#f87171';
        $amountColor = '#f87171';
        $amountSign = '-';
    } elseif (in_array($tx->type, ['bonus_vip', 'bonus_journalier', 'gain_journalier', 'parrainage', 'bonus', 'cadeau'])) {
        $icon = 'gift';
        $iconBg = 'rgba(139,92,246,0.12)';
        $iconColor = '#a78bfa';
        $amountColor = '#a78bfa';
        $amountSign = '+';
    } elseif ($tx->type === 'achat') {
        $icon = 'shopping-cart';
        $iconBg = 'rgba(6,182,212,0.12)';
        $iconColor = '#22d3ee';
        $amountColor = '#22d3ee';
        $amountSign = '-';
    }

    $statusData  = $statuses[$tx->status] ?? ['label' => ucfirst($tx->status), 'color' => 'gray'];
    $statusColor = $statusData['color'];
    $statusLabel = $statusData['label'];

    $statusStyle = 'color: #6b7280;';
    if ($statusColor === 'green')  $statusStyle = 'color: #22d3ee;';
    elseif ($statusColor === 'red') $statusStyle = 'color: #f87171;';
    elseif ($statusColor === 'yellow') $statusStyle = 'color: #fbbf24;';

    $txJson = json_encode([
        'reference'  => $tx->reference,
        'type'       => $tx->type,
        'montant'    => $montant,
        'method'     => $tx->method,
        'status'     => $tx->status,
        'created_at' => $tx->created_at ? $tx->created_at->toDateTimeString() : null,
        'description'=> $tx->description,
    ], JSON_UNESCAPED_UNICODE);
@endphp

<div class="rounded-2xl p-4 flex items-center justify-between cursor-pointer active:scale-95 transition"
     style="background: #0d1117; border: 1px solid rgba(255,255,255,0.06);"
     data-tx="{{ $txJson }}"
     onclick="openTxModal(this.getAttribute('data-tx'))">

    <div class="flex items-center gap-3">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl flex-shrink-0" style="background: {{ $iconBg }};">
            <i class="fas fa-{{ $icon }} text-sm" style="color: {{ $iconColor }};"></i>
        </div>
        <div>
            <p class="text-[12px] font-semibold text-gray-200">{{ $typeLabel }}</p>
            <p class="text-[10px] font-medium mt-0.5" style="color: #4b5563;">{{ $tx->created_at->format('d M, H:i') }} @if($tx->method) · {{ $tx->method }} @endif</p>
        </div>
    </div>

    <div class="text-right">
        <p class="text-xs font-bold" style="color: {{ $amountColor }};">
            {{ $amountSign }}{{ fmtCurrency($montant) }}
        </p>
        <span class="text-[10px] font-semibold mt-0.5 inline-block" style="{{ $statusStyle }}">
            {{ $statusLabel }}
        </span>
    </div>
</div>
