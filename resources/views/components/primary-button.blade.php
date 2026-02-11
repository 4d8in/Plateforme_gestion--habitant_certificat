<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-sm tracking-wide shadow-sm transition ease-in-out duration-150
                    bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700
                    text-white
                    focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2',
    ]) }}
    style="background-color:#2563eb;color:#ffffff;border:1px solid #2563eb;"
>
    {{ $slot }}
</button>
