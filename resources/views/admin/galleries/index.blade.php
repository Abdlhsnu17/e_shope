@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
    <div class="grid gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data"
                  class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-card">
                @csrf
                <h2 class="text-base font-bold text-brand-950">Tambah Foto</h2>

                <x-form.input name="judul" label="Judul" required />
                <x-form.select name="kategori" label="Kategori" required
                               :options="collect(\App\Models\Gallery::KATEGORI)->mapWithKeys(fn ($k) => [$k => ucfirst($k)])" />
                <x-form.textarea name="deskripsi" label="Deskripsi" rows="2" />

                <div>
                    <label for="gambar" class="block text-sm font-medium text-slate-700">Foto <span class="text-rose-500">*</span></label>
                    <input type="file" name="gambar" id="gambar" required accept=".jpg,.jpeg,.png,.webp"
                           class="mt-1.5 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-800">
                    <p class="mt-1 text-xs text-slate-500">JPG/PNG/WEBP, maksimal 2 MB.</p>
                    @error('gambar')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <button class="w-full rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800">
                    Unggah Foto
                </button>
            </form>
        </div>

        <div class="lg:col-span-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-base font-bold text-brand-950">Koleksi Foto ({{ $items->total() }})</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.galleries.index') }}"
                           class="rounded-lg px-3 py-1.5 text-sm font-medium transition {{ ! $filterKategori ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Semua
                        </a>
                        @foreach (\App\Models\Gallery::KATEGORI as $kategori)
                            <a href="{{ route('admin.galleries.index', ['kategori' => $kategori]) }}"
                               class="rounded-lg px-3 py-1.5 text-sm font-medium capitalize transition {{ $filterKategori === $kategori ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $kategori }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @forelse ($items as $foto)
                        <figure class="overflow-hidden rounded-xl border border-slate-200">
                            <div class="aspect-4/3 bg-slate-100">
                                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $foto->judul }}" class="size-full object-cover">
                            </div>
                            <figcaption class="p-3">
                                <p class="truncate text-sm font-semibold text-brand-950">{{ $foto->judul }}</p>
                                <p class="mt-0.5 text-xs capitalize text-slate-500">{{ $foto->kategori }}</p>
                                <form method="POST" action="{{ route('admin.galleries.destroy', $foto) }}" class="mt-2"
                                      onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                                </form>
                            </figcaption>
                        </figure>
                    @empty
                        <p class="col-span-full rounded-xl border border-dashed border-slate-300 p-12 text-center text-sm text-slate-500">
                            Belum ada foto pada kategori ini.
                        </p>
                    @endforelse
                </div>

                <div class="mt-6">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
@endsection
