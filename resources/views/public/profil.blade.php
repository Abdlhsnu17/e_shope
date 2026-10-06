@extends('layouts.public')

@section('title', 'Profil Sekolah')

@section('content')
    <x-page-header eyebrow="Tentang Kami" :judul="'Profil ' . $profil->nama"
                   teks="Identitas resmi, arah pendidikan, serta tenaga pendidik yang menopang kegiatan belajar kami." />

    <section class="mx-auto max-w-6xl px-6 py-14">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="space-y-8 lg:col-span-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-card">
                    <div class="flex items-center gap-3">
                        <span class="grid size-10 place-items-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-100">
                            <x-icon name="sparkles" class="size-5" />
                        </span>
                        <h2 class="text-xl font-bold tracking-tight text-brand-950">Visi</h2>
                    </div>
                    <p class="mt-5 text-lg leading-relaxed text-slate-700">
                        {{ $profil->visi ?: 'Visi sekolah belum diisi oleh administrator.' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-card">
                    <div class="flex items-center gap-3">
                        <span class="grid size-10 place-items-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-100">
                            <x-icon name="check-circle" class="size-5" />
                        </span>
                        <h2 class="text-xl font-bold tracking-tight text-brand-950">Misi</h2>
                    </div>
                    @if (count($profil->misiList()))
                        <ol class="mt-6 space-y-4">
                            @foreach ($profil->misiList() as $i => $misi)
                                <li class="flex gap-4">
                                    <span class="tabular grid size-7 shrink-0 place-items-center rounded-lg bg-brand-700 text-xs font-bold text-white">
                                        {{ $i + 1 }}
                                    </span>
                                    <span class="leading-relaxed text-slate-700">{{ $misi }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="mt-5 text-slate-600">Misi sekolah belum diisi oleh administrator.</p>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-card">
                    <div class="flex items-center gap-3">
                        <span class="grid size-10 place-items-center rounded-xl bg-brand-50 text-brand-700 ring-1 ring-brand-100">
                            <x-icon name="building" class="size-5" />
                        </span>
                        <h2 class="text-xl font-bold tracking-tight text-brand-950">Sejarah Singkat</h2>
                    </div>
                    <div class="mt-5 space-y-4 leading-relaxed text-slate-700">
                        @forelse (preg_split('/\n{2,}/', (string) $profil->sejarah, -1, PREG_SPLIT_NO_EMPTY) as $paragraf)
                            <p>{{ $paragraf }}</p>
                        @empty
                            <p>Sejarah sekolah belum diisi oleh administrator.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-6 lg:col-span-4">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                    <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                        <p class="eyebrow text-slate-500">Identitas Sekolah</p>
                    </div>
                    <dl class="divide-y divide-slate-100">
                        @foreach ([
                            'Nama' => $profil->nama,
                            'NPSN' => $profil->npsn,
                            'Jenjang' => $profil->jenjang,
                            'Akreditasi' => $profil->akreditasi,
                            'Kepala Sekolah' => $profil->kepala_sekolah,
                            'Telepon' => $profil->telepon,
                            'Email' => $profil->email,
                            'Alamat' => $profil->alamat,
                        ] as $label => $nilai)
                            <div class="flex justify-between gap-4 px-6 py-3.5 text-sm">
                                <dt class="shrink-0 text-slate-500">{{ $label }}</dt>
                                <dd class="text-right font-semibold text-brand-950">{{ $nilai ?: '—' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="relative overflow-hidden rounded-2xl bg-brand-900 p-7 shadow-lift">
                    <div class="pointer-events-none absolute -right-16 -top-16 size-40 rounded-full bg-brand-600/30 blur-2xl"></div>
                    <div class="relative">
                        <h2 class="text-lg font-bold tracking-tight text-white">Tertarik bergabung?</h2>
                        <p class="mt-2.5 text-sm leading-relaxed text-brand-100/75">
                            Pendaftaran siswa baru dibuka secara daring. Prosesnya cepat, mudah, dan dapat dipantau.
                        </p>
                        <a href="{{ route('pendaftaran.form') }}"
                           class="group mt-5 inline-flex items-center gap-2 rounded-xl bg-accent-500 px-5 py-2.5 text-sm font-semibold text-brand-950 transition hover:bg-accent-400">
                            Daftar Sekarang
                            <x-icon name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" />
                        </a>
                    </div>
                </div>
            </aside>
        </div>

        <div class="mt-16">
            <p class="eyebrow text-brand-600">Tim Pengajar</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight text-brand-950">Tenaga Pendidik</h2>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($guruTeladan as $guru)
                    <div class="group rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-card transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-lift">
                        <span class="mx-auto grid size-16 place-items-center rounded-2xl bg-brand-700 text-xl font-bold text-white">
                            {{ strtoupper(substr($guru->nama, 0, 1)) }}
                        </span>
                        <p class="mt-4 font-bold leading-snug tracking-tight text-brand-950">{{ $guru->nama }}</p>
                        <p class="mt-1.5 text-sm text-slate-600">{{ $guru->mata_pelajaran ?: 'Guru' }}</p>
                        @if ($guru->jabatan)
                            <p class="mt-3 inline-block rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $guru->jabatan }}</p>
                        @endif
                    </div>
                @empty
                    <p class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-14 text-center text-sm text-slate-500">
                        Data guru belum tersedia.
                    </p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
