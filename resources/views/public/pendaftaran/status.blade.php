@extends('layouts.public')

@section('title', 'Cek Status Pendaftaran')

@section('content')
    <x-page-header eyebrow="Penerimaan Siswa Baru" judul="Cek Status Pendaftaran"
                   teks="Masukkan nomor pendaftaran dan email yang Anda gunakan saat mendaftar untuk melihat perkembangan seleksi." />

    <section class="mx-auto max-w-3xl px-6 py-12">
        <form method="POST" action="{{ route('pendaftaran.status') }}"
              class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
            @csrf
            <div class="grid gap-5 p-7 sm:grid-cols-2">
                <x-form.input name="nomor" label="Nomor Pendaftaran" required placeholder="PSB-{{ date('Y') }}-0001" />
                <x-form.input name="email" label="Email Terdaftar" type="email" required />
            </div>
            <div class="border-t border-slate-100 bg-slate-50/70 px-7 py-4">
                <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-700 px-8 py-3 text-sm font-semibold text-white transition hover:bg-brand-800 sm:w-auto">
                    <x-icon name="search" class="size-4" /> Cek Status
                </button>
            </div>
        </form>

        @if ($registration)
            <div class="mt-10 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 bg-slate-50/70 px-7 py-5">
                    <div>
                        <p class="eyebrow text-slate-500">Nomor Pendaftaran</p>
                        <p class="tabular mt-1.5 text-2xl font-bold tracking-tight text-brand-950">{{ $registration->nomor_pendaftaran }}</p>
                    </div>
                    <x-status-badge :status="$registration->status" size="lg" />
                </div>

                {{-- Lini masa seleksi --}}
                @php
                    $urutan = ['menunggu' => 1, 'diverifikasi' => 2, 'diterima' => 3, 'ditolak' => 3];
                    $tahapSaatIni = $urutan[$registration->status] ?? 1;
                    $ditolak = $registration->status === 'ditolak';
                @endphp
                <div class="border-b border-slate-100 px-7 py-6">
                    <ol class="flex items-center gap-2">
                        @foreach ([[1, 'Diterima sistem'], [2, 'Verifikasi berkas'], [3, $ditolak ? 'Hasil seleksi' : 'Kelulusan']] as $i => [$tahap, $label])
                            @php $lewat = $tahapSaatIni >= $tahap; @endphp
                            <li class="flex flex-1 items-center gap-2">
                                <div class="flex min-w-0 flex-1 flex-col gap-2">
                                    <div class="h-1.5 rounded-full {{ $lewat ? ($ditolak && $tahap === 3 ? 'bg-red-500' : 'bg-brand-600') : 'bg-slate-200' }}"></div>
                                    <span class="truncate text-xs font-medium {{ $lewat ? 'text-brand-900' : 'text-slate-400' }}">{{ $label }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <dl class="grid gap-x-8 gap-y-5 px-7 py-6 sm:grid-cols-2">
                    @foreach ([
                        'Nama Lengkap' => $registration->nama_lengkap,
                        'Asal Sekolah' => $registration->asal_sekolah,
                        'Jurusan Pilihan' => $registration->jurusan_pilihan,
                        'Tanggal Daftar' => $registration->created_at->translatedFormat('d F Y'),
                    ] as $label => $nilai)
                        <div>
                            <dt class="eyebrow text-slate-500">{{ $label }}</dt>
                            <dd class="mt-1.5 text-sm font-semibold text-brand-950">{{ $nilai ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if ($registration->catatan)
                    <div class="border-t border-slate-100 px-7 py-6">
                        <p class="eyebrow text-slate-500">Catatan dari Panitia</p>
                        <p class="mt-2.5 rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-700 ring-1 ring-slate-200">
                            {{ $registration->catatan }}
                        </p>
                    </div>
                @endif

                <div class="border-t border-slate-100 px-7 py-6">
                    <div class="flex items-center justify-between gap-4">
                        <p class="eyebrow text-slate-500">Dokumen</p>
                        <span class="tabular rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                            {{ $registration->documents->count() }}
                        </span>
                    </div>

                    @if ($registration->documents->isNotEmpty())
                        <ul class="mt-3.5 flex flex-wrap gap-2">
                            @foreach ($registration->documents as $doc)
                                <li class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">
                                    <x-icon name="document" class="size-3.5 text-slate-500" />{{ $doc->jenis }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2.5 text-sm text-slate-600">Anda belum mengunggah dokumen apa pun.</p>
                    @endif

                    <a href="{{ route('pendaftaran.dokumen', $registration->nomor_pendaftaran) }}"
                       class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800">
                        <x-icon name="upload" class="size-4" /> Kelola Dokumen
                    </a>
                </div>
            </div>
        @endif
    </section>
@endsection
