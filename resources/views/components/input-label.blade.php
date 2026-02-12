@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs uppercase tracking-wide text-slate-200']) }}>
    {{ $value ?? $slot }}
</label>
