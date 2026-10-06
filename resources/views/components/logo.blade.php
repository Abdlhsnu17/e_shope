@props(['compact' => false, 'class' => 'h-10 w-auto'])

@if ($compact)
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 64 64" role="img" aria-label="Logo Abdishope">
        <path d="M15 25h31l4 27H12z" fill="#15619b"/>
        <path d="M23 25v-5a9 9 0 0 1 18 0v5" fill="none" stroke="#15619b" stroke-width="5" stroke-linecap="round"/>
        <path d="M26 35c4 5 9 5 13 0" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
        <path d="M10 54 28 36l9 9 17-20" fill="none" stroke="#f47b20" stroke-width="6" stroke-linecap="square" stroke-linejoin="miter"/>
        <path d="m53 22 8-2-2 8z" fill="#f47b20"/>
    </svg>
@else
    <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 520 140" role="img" aria-label="Abdishope — Online Shopping Terpercaya">
        <g transform="translate(190 0)">
            <path d="M15 45h65l7 58H8z" fill="#15619b"/>
            <path d="M31 45V31a17 17 0 0 1 34 0v14" fill="none" stroke="#15619b" stroke-width="8" stroke-linecap="round"/>
            <path d="M38 66c8 9 18 9 26 0" fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
            <path d="M0 111 39 72l20 20 44-52" fill="none" stroke="#f47b20" stroke-width="11" stroke-linecap="square" stroke-linejoin="miter"/>
            <path d="m101 34 18-5-5 18z" fill="#f47b20"/>
        </g>
        <text x="70" y="125" fill="#15619b" font-family="Arial, sans-serif" font-size="57" font-weight="700">Abdi</text>
        <text x="197" y="125" fill="#f47b20" font-family="Arial, sans-serif" font-size="57" font-weight="700">shope</text>
        <text x="142" y="139" fill="#4b4b4b" font-family="Arial, sans-serif" font-size="11" font-weight="700" letter-spacing="1.2">ONLINE SHOPPING TERPERCAYA</text>
    </svg>
@endif
