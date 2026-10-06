@extends('layouts.public')

@section('title', 'Formulir Pendaftaran')

@section('content')
    <x-page-header eyebrow="Penerimaan Siswa Baru" judul="Formulir Pendaftaran"
                   teks="Lengkapi data berikut dengan benar. Setelah formulir dikirim, sistem menerbitkan nomor pendaftaran untuk mengunggah dokumen dan memantau seleksi." />

    <section class="mx-auto max-w-4xl px-6 py-12">
        {{-- Penanda langkah --}}
        <ol class="mb-10 flex items-center gap-3 text-sm">
            @foreach ([['1', 'Isi formulir', true], ['2', 'Unggah dokumen', false], ['3', 'Pantau status', false]] as $i => [$no, $label, $aktif])
                <li class="flex items-center gap-3">
                    <span class="flex items-center gap-2.5 {{ $aktif ? 'text-brand-800' : 'text-slate-400' }}">
                        <span class="tabular grid size-7 shrink-0 place-items-center rounded-full text-xs font-bold {{ $aktif ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-500' }}">
                            {{ $no }}
                        </span>
                        <span class="hidden font-medium sm:block">{{ $label }}</span>
                    </span>
                    @if ($i < 2)
                        <span class="h-px w-6 bg-slate-200 sm:w-10"></span>
                    @endif
                </li>
            @endforeach
        </ol>

        <form method="POST" action="{{ route('pendaftaran.store') }}" class="space-y-6">
            @csrf

            <fieldset class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-7 py-4">
                    <x-icon name="academic" class="size-5 text-brand-600" />
                    <legend class="text-sm font-bold text-brand-950">Data Calon Siswa</legend>
                </div>
                <div class="grid gap-5 p-7 sm:grid-cols-2">
                    <x-form.input name="nama_lengkap" label="Nama Lengkap" required class="sm:col-span-2" />
                    <x-form.input name="nisn" label="NISN" placeholder="Opsional" />
                    <x-form.select name="jenis_kelamin" label="Jenis Kelamin" required
                                   :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" />
                    <x-form.input name="tempat_lahir" label="Tempat Lahir" />
                    <x-form.input name="tanggal_lahir" label="Tanggal Lahir" type="date" required />
                    <x-form.input name="asal_sekolah" label="Asal Sekolah" required />
                    <x-form.input name="jurusan_pilihan" label="Jurusan Pilihan" required placeholder="Contoh: MIPA" />
                </div>
            </fieldset>

            <fieldset class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-7 py-4">
                    <x-icon name="mail" class="size-5 text-brand-600" />
                    <legend class="text-sm font-bold text-brand-950">Kontak</legend>
                </div>
                <div class="grid gap-5 p-7 sm:grid-cols-2">
                    <x-form.input name="email" label="Email Aktif" type="email" required />
                    <x-form.input name="telepon" label="Nomor Telepon" required />
                    <x-form.textarea name="alamat" label="Alamat Lengkap" required class="sm:col-span-2" />
                </div>
            </fieldset>

            <fieldset class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-7 py-4">
                    <x-icon name="users" class="size-5 text-brand-600" />
                    <legend class="text-sm font-bold text-brand-950">Data Orang Tua / Wali</legend>
                </div>
                <div class="grid gap-5 p-7 sm:grid-cols-2">
                    <x-form.input name="nama_wali" label="Nama Orang Tua / Wali" required />
                    <x-form.input name="telepon_wali" label="Telepon Orang Tua / Wali" required />
                </div>
            </fieldset>

            <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 px-7 py-5">
                <p class="flex items-start gap-2.5 text-sm text-slate-600">
                    <x-icon name="shield-check" class="mt-0.5 size-4 shrink-0 text-brand-600" />
                    Pastikan email benar — nomor pendaftaran terhubung dengan alamat tersebut.
                </p>
                <button type="submit"
                        class="group inline-flex items-center gap-2 rounded-xl bg-brand-700 px-7 py-3.5 text-sm font-semibold text-white shadow-card transition hover:bg-brand-800 hover:shadow-lift">
                    Kirim Pendaftaran
                    <x-icon name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" />
                </button>
            </div>
        </form>
    </section>
@endsection
