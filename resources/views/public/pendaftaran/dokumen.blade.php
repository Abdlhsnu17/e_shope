@extends('layouts.public')

@section('title', 'Unggah Dokumen')

@section('content')
    <section class="mx-auto max-w-5xl px-6 py-12">
        {{-- Kartu nomor pendaftaran --}}
        <div class="relative overflow-hidden rounded-2xl bg-brand-900 p-8 shadow-lift">
            <div class="pointer-events-none absolute -right-20 -top-20 size-56 rounded-full bg-brand-600/30 blur-3xl"></div>
            <div class="relative flex flex-wrap items-end justify-between gap-6">
                <div>
                    <p class="eyebrow text-accent-400">Nomor Pendaftaran Anda</p>
                    <p class="tabular mt-3 text-4xl font-bold tracking-tight text-white">{{ $registration->nomor_pendaftaran }}</p>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-brand-100/70">
                        Simpan nomor ini. Gunakan bersama email Anda untuk memeriksa status seleksi kapan saja.
                    </p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1.5 text-sm font-semibold capitalize text-white ring-1 ring-white/20">
                    <x-icon name="clock" class="size-4" />{{ $registration->status }}
                </span>
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                    <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                        <x-icon name="upload" class="size-5 text-brand-600" />
                        <h2 class="text-sm font-bold text-brand-950">Unggah Dokumen</h2>
                    </div>

                    <form method="POST" action="{{ route('pendaftaran.dokumen.unggah', $registration->nomor_pendaftaran) }}"
                          enctype="multipart/form-data" class="space-y-5 p-6">
                        @csrf
                        <x-form.select name="jenis" label="Jenis Dokumen" required placeholder="— Pilih jenis —"
                                       :options="collect(['Kartu Keluarga', 'Akta Kelahiran', 'Ijazah/SKL', 'Rapor', 'Pas Foto', 'Lainnya'])->mapWithKeys(fn ($j) => [$j => $j])" />

                        <div>
                            <label for="berkas" class="block text-sm font-medium text-slate-700">Berkas <span class="text-rose-500">*</span></label>
                            <input type="file" name="berkas" id="berkas" required accept=".pdf,.jpg,.jpeg,.png"
                                   class="mt-1.5 w-full cursor-pointer rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-600 transition file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-700 hover:border-brand-300">
                            <p class="mt-1.5 text-xs text-slate-500">PDF, JPG, atau PNG. Maksimal 2 MB.</p>
                            @error('berkas')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit"
                                class="w-full rounded-xl bg-brand-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-800">
                            Unggah Dokumen
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                    <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                        <h2 class="text-sm font-bold text-brand-950">Dokumen Terunggah</h2>
                        <span class="tabular rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                            {{ $registration->documents->count() }}
                        </span>
                    </div>

                    <ul class="divide-y divide-slate-100">
                        @forelse ($registration->documents as $doc)
                            <li class="flex items-center justify-between gap-4 px-6 py-4">
                                <div class="flex min-w-0 items-center gap-3.5">
                                    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-100">
                                        <x-icon name="document" class="size-5" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-brand-950">{{ $doc->jenis }}</p>
                                        <p class="mt-0.5 truncate text-xs text-slate-500">
                                            {{ $doc->nama_file }} &middot; {{ $doc->ukuran_label }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <a href="{{ route('pendaftaran.dokumen.unduh', [$registration->nomor_pendaftaran, $doc->id]) }}"
                                       class="rounded-lg px-2.5 py-1.5 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">Unduh</a>
                                    <form method="POST"
                                          action="{{ route('pendaftaran.dokumen.hapus', [$registration->nomor_pendaftaran, $doc->id]) }}"
                                          onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg px-2.5 py-1.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-16 text-center">
                                <span class="mx-auto grid size-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <x-icon name="document" class="size-6" />
                                </span>
                                <p class="mt-4 text-sm font-medium text-slate-700">Belum ada dokumen</p>
                                <p class="mt-1 text-sm text-slate-500">Unggah berkas persyaratan melalui formulir di samping.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('pendaftaran.status') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-brand-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-900">
                        <x-icon name="shield-check" class="size-4" /> Cek Status Pendaftaran
                    </a>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
