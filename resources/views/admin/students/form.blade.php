@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Data Siswa' : 'Tambah Siswa')

@section('content')
    <a href="{{ route('admin.students.index') }}" class="text-sm font-medium text-slate-500 hover:text-brand-700">
        &larr; Kembali ke daftar siswa
    </a>

    <form method="POST"
          action="{{ $item->exists ? route('admin.students.update', $item) : route('admin.students.store') }}"
          class="mt-4 max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.input name="nis" label="NIS" required :value="$item->nis" />
            <x-form.input name="nisn" label="NISN" :value="$item->nisn" />
            <x-form.input name="nama" label="Nama Lengkap" required :value="$item->nama" class="sm:col-span-2" />
            <x-form.input name="email" label="Email" type="email" :value="$item->email" />
            <x-form.select name="jenis_kelamin" label="Jenis Kelamin" required :value="$item->jenis_kelamin"
                           :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" />
            <x-form.input name="kelas" label="Kelas" :value="$item->kelas" placeholder="Contoh: X-1" />
            <x-form.input name="jurusan" label="Jurusan" :value="$item->jurusan" />
            <x-form.input name="tanggal_lahir" label="Tanggal Lahir" type="date"
                          :value="optional($item->tanggal_lahir)->format('Y-m-d')" />
            <x-form.select name="status" label="Status" required :value="$item->status"
                           :options="['aktif' => 'Aktif', 'lulus' => 'Lulus', 'pindah' => 'Pindah', 'nonaktif' => 'Nonaktif']" />
            <x-form.input name="nama_wali" label="Nama Orang Tua / Wali" :value="$item->nama_wali" />
            <x-form.input name="telepon_wali" label="Telepon Wali" :value="$item->telepon_wali" />
            <x-form.textarea name="alamat" label="Alamat" :value="$item->alamat" class="sm:col-span-2" />
        </div>

        <div class="mt-6 flex gap-3">
            <button class="rounded-lg bg-brand-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800">
                {{ $item->exists ? 'Simpan Perubahan' : 'Tambah Siswa' }}
            </button>
            <a href="{{ route('admin.students.index') }}"
               class="rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
@endsection
