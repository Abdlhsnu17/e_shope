@extends('layouts.public')

@section('title', 'Berita & Pengumuman')

@section('content')
    <x-page-header eyebrow="Informasi Terkini" judul="Berita & Pengumuman"
                   teks="Kabar terbaru, pengumuman resmi, dan agenda kegiatan dari lingkungan sekolah." />

    <section class="mx-auto max-w-6xl px-6 py-12">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('berita') }}"
                   class="rounded-lg px-3.5 py-2 text-sm font-medium transition {{ ! $kategoriAktif ? 'bg-brand-700 text-white shadow-card' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                    Semua
                </a>
                @foreach (\App\Models\Announcement::KATEGORI as $kategori)
                    <a href="{{ route('berita', ['kategori' => $kategori]) }}"
                       class="rounded-lg px-3.5 py-2 text-sm font-medium capitalize transition {{ $kategoriAktif === $kategori ? 'bg-brand-700 text-white shadow-card' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                        {{ $kategori }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('berita') }}" class="relative">
                @if ($kategoriAktif)
                    <input type="hidden" name="kategori" value="{{ $kategoriAktif }}">
                @endif
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul berita..."
                       class="w-64 rounded-lg border border-slate-200 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </form>
        </div>

        @if (request('q'))
            <p class="mt-6 text-sm text-slate-500">
                Menampilkan {{ $items->total() }} hasil untuk <span class="font-semibold text-brand-900">"{{ request('q') }}"</span>
            </p>
        @endif

        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($items as $item)
                <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card transition hover:-translate-y-1 hover:shadow-lift">
                    <a href="{{ route('berita.detail', $item->slug) }}" class="block aspect-16/10 overflow-hidden bg-slate-100">
                        @if ($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
                                 class="size-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <span class="grid size-full place-items-center bg-brand-900 text-brand-300">
                                <x-icon name="newspaper" class="size-10" />
                            </span>
                        @endif
                    </a>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="rounded-full bg-brand-50 px-2.5 py-1 font-semibold capitalize text-brand-700">{{ $item->kategori }}</span>
                            <span class="text-slate-400">{{ optional($item->published_at)->translatedFormat('d M Y') }}</span>
                        </div>
                        <h2 class="mt-3.5 text-base font-bold leading-snug tracking-tight text-brand-950">
                            <a href="{{ route('berita.detail', $item->slug) }}" class="transition hover:text-brand-700">{{ $item->judul }}</a>
                        </h2>
                        <p class="mt-2.5 flex-1 text-sm leading-relaxed text-slate-600">{{ Str::limit($item->ringkasan_teks, 120) }}</p>
                        <a href="{{ route('berita.detail', $item->slug) }}"
                           class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">
                            Baca selengkapnya <x-icon name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" />
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-16 text-center">
                    <span class="mx-auto grid size-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                        <x-icon name="search" class="size-6" />
                    </span>
                    <p class="mt-4 text-sm font-medium text-slate-700">Tidak ada berita yang cocok</p>
                    <p class="mt-1 text-sm text-slate-500">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $items->links() }}</div>
    </section>
@endsection
