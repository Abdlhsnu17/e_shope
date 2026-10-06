@props(['eyebrow' => null, 'judul', 'teks' => null])

<section class="relative overflow-hidden border-b border-slate-200 bg-brand-950">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-32 -top-40 size-96 rounded-full bg-brand-600/25 blur-3xl"></div>
        <div class="absolute inset-0 opacity-[0.06]"
             style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 64px 64px;"></div>
    </div>

    <div class="relative mx-auto max-w-6xl px-6 py-16 lg:py-20">
        @if ($eyebrow)
            <p class="eyebrow text-accent-400">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-3 text-4xl font-bold tracking-tight text-white">{{ $judul }}</h1>
        @if ($teks)
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-brand-100/75">{{ $teks }}</p>
        @endif
    </div>
</section>
