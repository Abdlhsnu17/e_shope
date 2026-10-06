@props(['status', 'size' => 'sm'])

@php
    /*
     * Peran status memakai palet status baku. Warna tidak pernah berdiri sendiri —
     * setiap lencana selalu membawa ikon dan label teks.
     */
    $peta = [
        'menunggu' => ['ikon' => 'clock', 'kelas' => 'bg-amber-50 text-amber-800 ring-amber-200'],
        'diverifikasi' => ['ikon' => 'document', 'kelas' => 'bg-blue-50 text-blue-800 ring-blue-200'],
        'diterima' => ['ikon' => 'check-circle', 'kelas' => 'bg-green-50 text-green-800 ring-green-200'],
        'ditolak' => ['ikon' => 'x-circle', 'kelas' => 'bg-red-50 text-red-800 ring-red-200'],
    ];

    $gaya = $peta[$status] ?? ['ikon' => 'clock', 'kelas' => 'bg-slate-50 text-slate-700 ring-slate-200'];
    $ukuran = $size === 'lg' ? 'px-3.5 py-1.5 text-sm' : 'px-2.5 py-1 text-xs';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full font-semibold capitalize ring-1 ring-inset {$gaya['kelas']} {$ukuran}"]) }}>
    <x-icon :name="$gaya['ikon']" :class="$size === 'lg' ? 'size-4' : 'size-3.5'" />
    {{ $status }}
</span>
