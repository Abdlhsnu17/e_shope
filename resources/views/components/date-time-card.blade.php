@props([
    'tone' => 'light',
    'class' => '',
])

@php
    $now = now('Asia/Jakarta');
    $isDark = $tone === 'dark';
@endphp

<div
    x-data="{
        time: '{{ $now->format('H:i:s') }}',
        day: '{{ $now->translatedFormat('l') }}',
        date: '{{ $now->translatedFormat('d F Y') }}',
        tick() {
            const now = new Date();
            this.time = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            }).format(now).replace(/\./g, ':');
            this.day = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                timeZone: 'Asia/Jakarta'
            }).format(now);
            this.date = new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            }).format(now);
        }
    }"
    x-init="tick(); setInterval(() => tick(), 1000)"
    {{ $attributes->merge(['class' => trim("flex flex-col gap-3 rounded-lg border px-4 py-3 sm:flex-row sm:items-center sm:justify-between {$class} " . ($isDark ? 'border-white/15 bg-white/10 text-white ring-1 ring-white/10 backdrop-blur' : 'border-slate-200 bg-white text-brand-950 shadow-card'))]) }}
>
    <div class="flex items-center gap-3">
        <span class="grid size-10 shrink-0 place-items-center rounded-lg {{ $isDark ? 'bg-white/10 text-accent-300 ring-1 ring-white/15' : 'bg-brand-50 text-brand-700 ring-1 ring-brand-100' }}">
            <x-icon name="clock" class="size-5" />
        </span>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.12em] {{ $isDark ? 'text-brand-100/60' : 'text-slate-500' }}">Waktu</p>
            <p class="metric mt-0.5 text-2xl leading-none tabular" x-text="time">{{ $now->format('H:i:s') }}</p>
        </div>
    </div>

    <div class="border-t pt-3 text-left sm:border-l sm:border-t-0 sm:pl-5 sm:pt-0 sm:text-right {{ $isDark ? 'border-white/10' : 'border-slate-200' }}">
        <p class="text-sm font-bold capitalize" x-text="day">{{ $now->translatedFormat('l') }}</p>
        <p class="mt-0.5 text-sm {{ $isDark ? 'text-brand-100/70' : 'text-slate-500' }}" x-text="date">{{ $now->translatedFormat('d F Y') }}</p>
    </div>
</div>
