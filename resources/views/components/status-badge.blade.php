@props(['statut'])

@php
    $defaults = [
        'label' => ucfirst(str_replace('_', ' ', (string) $statut)),
        'color' => 'gray',
    ];

    $meta = config('certificat.statuses.' . $statut, $defaults);

    $colors = [
        'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
        'indigo' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'border' => 'border-indigo-200'],
        'emerald' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
        'gray' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200'],
    ];

    $color = $colors[$meta['color'] ?? 'gray'];
@endphp

<span
    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full border {{ $color['bg'] }} {{ $color['text'] }} {{ $color['border'] }}"
    aria-label="Statut : {{ $meta['label'] }}"
>
    <span class="sr-only">Statut :</span>
    {{ $meta['label'] }}
</span>
