@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'w-full rounded-lg border border-slate-600 bg-slate-800/80 text-slate-50 placeholder-slate-400 shadow-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-300 focus:ring-offset-0',
]) }}>
