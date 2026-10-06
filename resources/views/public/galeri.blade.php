@extends('layouts.public')

@section('title', 'Galeri')

@section('content')
    <x-page-header eyebrow="Dokumentasi" judul="Galeri Sekolah"
                   teks="Rekam jejak kegiatan, fasilitas, dan prestasi di lingkungan sekolah kami." />

    <section class="mx-auto max-w-6xl px-6 py-12">
        <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-6">
            <a href="{{ route('galeri') }}"
               class="rounded-lg px-3.5 py-2 text-sm font-medium transition {{ ! $kategoriAktif ? 'bg-brand-700 text-white shadow-card' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                Semua
            </a>
            @foreach (\App\Models\Gallery::KATEGORI as $kategori)
                <a href="{{ route('galeri', ['kategori' => $kategori]) }}"
                   class="rounded-lg px-3.5 py-2 text-sm font-medium capitalize transition {{ $kategoriAktif === $kategori ? 'bg-brand-700 text-white shadow-card' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                    {{ $kategori }}
                </a>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($items as $foto)
                <figure class="group relative aspect-4/3 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-200 transition hover:ring-brand-300">
                    <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $foto->judul }}"
                         class="size-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-linear-to-t from-brand-950/85 via-brand-950/10 to-transparent opacity-90 transition group-hover:opacity-100"></div>
                    <figcaption class="absolute inset-x-0 bottom-0 p-4">
                        <p class="text-sm font-semibold leading-snug text-white">{{ $foto->judul }}</p>
                        <p class="mt-0.5 text-xs capitalize text-white/60">{{ $foto->kategori }}</p>
                    </figcaption>
                </figure>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-16 text-center">
                    <span class="mx-auto grid size-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                        <x-icon name="photo" class="size-6" />
                    </span>
                    <p class="mt-4 text-sm font-medium text-slate-700">Belum ada foto pada kategori ini</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $items->links() }}</div>
    </section>
@endsection
