<svg xmlns="http://www.w3.org/2000/svg"
     viewBox="0 0 128 128"
     aria-label="Logo"
     {{ $attributes->merge(['class' => '']) }}>
    <g fill="currentColor">
        <!-- Gear base -->
        <g transform="translate(64 82)">
            <circle r="26"/>
            <rect x="-5" y="-52" width="10" height="12" rx="2"/>
            <rect x="-5" y="40" width="10" height="12" rx="2"/>
            <rect x="40" y="-5" width="10" height="12" rx="2" transform="rotate(90 45 1)"/>
            <rect x="-50" y="-5" width="10" height="12" rx="2" transform="rotate(90 -45 1)"/>
            <rect x="28" y="-38" width="10" height="12" rx="2" transform="rotate(45 33 -32)"/>
            <rect x="-38" y="28" width="10" height="12" rx="2" transform="rotate(45 -33 34)"/>
            <rect x="28" y="28" width="10" height="12" rx="2" transform="rotate(135 33 34)"/>
            <rect x="-38" y="-38" width="10" height="12" rx="2" transform="rotate(135 -33 -32)"/>
        </g>

        <!-- Ground plate -->
        <rect x="34" y="70" width="60" height="8" rx="3"/>

        <!-- Back tower -->
        <rect x="42" y="30" width="16" height="44" rx="2"/>
        <rect x="45" y="36" width="10" height="10" rx="1" fill="#ffffff"/>
        <rect x="45" y="50" width="10" height="10" rx="1" fill="#ffffff"/>
        <rect x="45" y="64" width="10" height="10" rx="1" fill="#ffffff"/>

        <!-- Main building -->
        <rect x="60" y="18" width="28" height="56" rx="3"/>
        <g fill="#ffffff">
            <rect x="66" y="24" width="8" height="10" rx="1"/>
            <rect x="78" y="24" width="8" height="10" rx="1"/>
            <rect x="66" y="38" width="8" height="10" rx="1"/>
            <rect x="78" y="38" width="8" height="10" rx="1"/>
            <rect x="66" y="52" width="8" height="10" rx="1"/>
            <rect x="78" y="52" width="8" height="10" rx="1"/>
        </g>

        <!-- Porch -->
        <rect x="66" y="70" width="16" height="8" rx="1"/>
    </g>
</svg>
