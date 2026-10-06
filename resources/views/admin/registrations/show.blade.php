@extends('layouts.admin')

@section('title', 'Detail Pendaftar')

@section('content')
    <a href="{{ route('admin.registrations.index') }}" class="text-sm font-medium text-slate-500 hover:text-brand-700">
        &larr; Kembali ke daftar
    </a>

    <div class="mt-4 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-bold text-brand-950">{{ $item->nama_lengkap }}</h2>
                        <p class="mt-0.5 font-mono text-xs text-slate-500">{{ $item->nomor_pendaftaran }}</p>
                    </div>
                    <x-status-badge :status="$item->status" size="lg" />
                </div>

                <dl class="grid gap-x-8 gap-y-5 px-5 py-6 sm:grid-cols-2">
                    @foreach ([
                        'NISN' => $item->nisn,
                        'Jenis Kelamin' => $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                        'Tempat, Tanggal Lahir' => trim(($item->tempat_lahir ?: '—') . ', ' . optional($item->tanggal_lahir)->translatedFormat('d F Y')),
                        'Asal Sekolah' => $item->asal_sekolah,
                        'Jurusan Pilihan' => $item->jurusan_pilihan,
                        'Email' => $item->email,
                        'Telepon' => $item->telepon,
                        'Nama Wali' => $item->nama_wali,
                        'Telepon Wali' => $item->telepon_wali,
                        'Tanggal Daftar' => $item->created_at->translatedFormat('d F Y, H:i'),
                        'Alamat' => $item->alamat,
                    ] as $label => $nilai)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                            <dd class="mt-1 text-sm font-medium text-brand-950">{{ $nilai ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-card">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-bold text-brand-950">
                        Dokumen <span class="text-sm font-normal text-slate-500">({{ $item->documents->count() }})</span>
                    </h2>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($item->documents as $doc)
                        <li class="flex items-center justify-between gap-4 px-5 py-3.5">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-brand-950">{{ $doc->jenis }}</p>
                                <p class="mt-0.5 truncate text-xs text-slate-500">{{ $doc->nama_file }} &middot; {{ $doc->ukuran_label }}</p>
                            </div>
                            <a href="{{ route('admin.registrations.dokumen', [$item, $doc->id]) }}"
                               class="shrink-0 text-sm font-semibold text-brand-700 hover:text-brand-900">Unduh</a>
                        </li>
                    @empty
                        <li class="px-5 py-10 text-center text-sm text-slate-500">Pendaftar belum mengunggah dokumen.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.registrations.update', $item) }}"
                  class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card">
                @csrf @method('PUT')
                <h2 class="text-base font-bold text-brand-950">Perbarui Status</h2>

                <div class="mt-4 space-y-4">
                    <x-form.select name="status" label="Status Seleksi" required :value="$item->status"
                                   :options="collect(\App\Models\Registration::STATUSES)->mapWithKeys(fn ($s) => [$s => ucfirst($s)])" />
                    <x-form.textarea name="catatan" label="Catatan untuk Pendaftar" :value="$item->catatan" rows="4"
                                     hint="Catatan ini tampil pada halaman cek status." />
                </div>

                <button class="mt-5 w-full rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800">
                    Simpan Perubahan
                </button>
            </form>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card">
                <h2 class="text-base font-bold text-brand-950">Tindakan Lanjutan</h2>

                <form method="POST" action="{{ route('admin.registrations.terima', $item) }}" class="mt-4"
                      onsubmit="return confirm('Jadikan pendaftar ini sebagai siswa aktif?')">
                    @csrf
                    <button class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50"
                            @disabled($item->status !== 'diterima')>
                        Jadikan Siswa Aktif
                    </button>
                </form>
                <p class="mt-2 text-xs text-slate-500">
                    Tersedia setelah status pendaftar diubah menjadi "diterima".
                </p>

                <form method="POST" action="{{ route('admin.registrations.destroy', $item) }}" class="mt-5 border-t border-slate-100 pt-5"
                      onsubmit="return confirm('Hapus data pendaftaran ini beserta dokumennya?')">
                    @csrf @method('DELETE')
                    <button class="w-full rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-rose-600 ring-1 ring-rose-200 transition hover:bg-rose-50">
                        Hapus Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
