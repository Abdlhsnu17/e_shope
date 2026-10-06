@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Data Guru' : 'Tambah Guru')

@section('content')
    <a href="{{ route('admin.teachers.index') }}" class="text-sm font-medium text-slate-500 hover:text-brand-700">
        &larr; Kembali ke daftar guru
    </a>

    <form method="POST"
          action="{{ $item->exists ? route('admin.teachers.update', $item) : route('admin.teachers.store') }}"
          class="mt-4 max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.input name="nip" label="NIP" required :value="$item->nip" />
            <x-form.input name="nama" label="Nama Lengkap" required :value="$item->nama" />
            <x-form.input name="email" label="Email" type="email" :value="$item->email" />
            <x-form.input name="telepon" label="Telepon" :value="$item->telepon" />
            <x-form.select name="jenis_kelamin" label="Jenis Kelamin" required :value="$item->jenis_kelamin"
                           :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" />
            <x-form.input name="mata_pelajaran" label="Mata Pelajaran" :value="$item->mata_pelajaran" />
            <x-form.input name="jabatan" label="Jabatan" :value="$item->jabatan" placeholder="Contoh: Wali Kelas X-1" />
            <x-form.input name="tanggal_bergabung" label="Tanggal Bergabung" type="date"
                          :value="optional($item->tanggal_bergabung)->format('Y-m-d')" />
            <x-form.select name="status" label="Status" required :value="$item->status"
                           :options="['aktif' => 'Aktif', 'cuti' => 'Cuti', 'nonaktif' => 'Nonaktif']" />
            <x-form.textarea name="alamat" label="Alamat" :value="$item->alamat" class="sm:col-span-2" />
        </div>

        <div class="mt-6 flex gap-3">
            <button class="rounded-lg bg-brand-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800">
                {{ $item->exists ? 'Simpan Perubahan' : 'Tambah Guru' }}
            </button>
            <a href="{{ route('admin.teachers.index') }}"
               class="rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
@endsection
