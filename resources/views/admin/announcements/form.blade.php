@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Konten' : 'Tulis Konten Baru')

@section('content')
    <a href="{{ route('admin.announcements.index') }}" class="text-sm font-medium text-slate-500 hover:text-brand-700">
        &larr; Kembali ke daftar konten
    </a>

    <form method="POST"
          action="{{ $item->exists ? route('admin.announcements.update', $item) : route('admin.announcements.store') }}"
          enctype="multipart/form-data"
          class="mt-4 grid max-w-5xl gap-6 lg:grid-cols-3">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-card lg:col-span-2">
            <x-form.input name="judul" label="Judul" required :value="$item->judul" />
            <x-form.textarea name="ringkasan" label="Ringkasan" :value="$item->ringkasan" rows="2"
                             hint="Opsional. Bila kosong, ringkasan diambil otomatis dari isi konten." />
            <x-form.textarea name="konten" label="Isi Konten" required :value="$item->konten" rows="14"
                             hint="Pisahkan antar paragraf dengan satu baris kosong." />
        </div>

        <div class="space-y-6">
            <div class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
                <x-form.select name="kategori" label="Kategori" required :value="$item->kategori"
                               :options="collect(\App\Models\Announcement::KATEGORI)->mapWithKeys(fn ($k) => [$k => ucfirst($k)])" />

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published ?? true))
                           class="size-4 rounded border-slate-300 text-brand-700 focus:ring-brand-500">
                    Publikasikan konten ini
                </label>

                <div>
                    <label for="gambar" class="block text-sm font-medium text-slate-700">Gambar Sampul</label>
                    @if ($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Sampul saat ini"
                             class="mt-2 aspect-video w-full rounded-lg object-cover">
                    @endif
                    <input type="file" name="gambar" id="gambar" accept=".jpg,.jpeg,.png,.webp"
                           class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-800">
                    <p class="mt-1 text-xs text-slate-500">JPG/PNG/WEBP, maksimal 2 MB.</p>
                    @error('gambar')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
                <button class="w-full rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800">
                    {{ $item->exists ? 'Simpan Perubahan' : 'Terbitkan' }}
                </button>
                <a href="{{ route('admin.announcements.index') }}"
                   class="mt-3 block rounded-lg bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </div>
    </form>
@endsection
