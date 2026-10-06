@extends('layouts.public')

@section('title', $item->judul)
@section('description', $item->ringkasan_teks)

@section('content')
    <article class="mx-auto max-w-3xl px-6 py-14">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('berita') }}" class="transition hover:text-brand-700">Berita</a>
            <x-icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span class="capitalize text-slate-700">{{ $item->kategori }}</span>
        </nav>

        <div class="mt-6 flex items-center gap-2 text-xs">
            <span class="rounded-full bg-brand-50 px-2.5 py-1 font-semibold capitalize text-brand-700">{{ $item->kategori }}</span>
            <span class="text-slate-400">{{ optional($item->published_at)->translatedFormat('d F Y, H:i') }}</span>
        </div>

        <h1 class="mt-4 text-4xl font-bold leading-[1.15] tracking-tight text-brand-950">{{ $item->judul }}</h1>

        @if ($item->gambar)
            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
                 class="mt-9 aspect-video w-full rounded-2xl object-cover shadow-card ring-1 ring-slate-200">
        @endif

        @if ($item->ringkasan)
            <p class="mt-9 border-l-[3px] border-brand-600 bg-brand-50/60 py-4 pl-5 pr-4 text-lg font-medium leading-relaxed text-brand-950">
                {{ $item->ringkasan }}
            </p>
        @endif

        <div class="mt-8 space-y-5 text-[17px] leading-[1.75] text-slate-700">
            @foreach (preg_split('/\n{2,}/', (string) $item->konten, -1, PREG_SPLIT_NO_EMPTY) as $paragraf)
                <p>{{ $paragraf }}</p>
            @endforeach
        </div>

        <div class="mt-12 flex items-center justify-between gap-4 border-t border-slate-200 pt-6">
            <a href="{{ route('berita') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700 transition hover:text-brand-900">
                <x-icon name="arrow-left" class="size-4" /> Kembali ke daftar berita
            </a>
        </div>

        @if ($lainnya->isNotEmpty())
            <div class="mt-12 rounded-2xl border border-slate-200 bg-slate-50/70 p-7">
                <p class="eyebrow text-brand-600">Baca Juga</p>
                <ul class="mt-4 divide-y divide-slate-200">
                    @foreach ($lainnya as $lain)
                        <li>
                            <a href="{{ route('berita.detail', $lain->slug) }}"
                               class="group flex items-start justify-between gap-4 py-3.5">
                                <span class="text-sm font-semibold leading-snug text-brand-950 transition group-hover:text-brand-700">
                                    {{ $lain->judul }}
                                </span>
                                <span class="shrink-0 pt-0.5 text-xs text-slate-500">
                                    {{ optional($lain->published_at)->translatedFormat('d M Y') }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </article>
@endsection
