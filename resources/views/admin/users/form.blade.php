@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('content')
    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-slate-500 hover:text-brand-700">
        &larr; Kembali ke daftar pengguna
    </a>

    <form method="POST"
          action="{{ $item->exists ? route('admin.users.update', $item) : route('admin.users.store') }}"
          class="mt-4 max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.input name="name" label="Nama Lengkap" required :value="$item->name" class="sm:col-span-2" />
            <x-form.input name="email" label="Email" type="email" required :value="$item->email" />
            <x-form.input name="telepon" label="Telepon" :value="$item->telepon" />
            <x-form.select name="role" label="Peran" required :value="$item->role"
                           :options="['admin' => 'Administrator', 'staf' => 'Staf', 'guru' => 'Guru']" />

            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))
                           class="size-4 rounded border-slate-300 text-brand-700 focus:ring-brand-500">
                    Akun aktif
                </label>
            </div>

            <div class="sm:col-span-2">
                <label for="password" class="block text-sm font-medium text-slate-700">
                    Kata Sandi @unless ($item->exists)<span class="text-rose-500">*</span>@endunless
                </label>
                <input type="password" name="password" id="password" @required(! $item->exists)
                       class="mt-1.5 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <p class="mt-1 text-xs text-slate-500">
                    Minimal 8 karakter.{{ $item->exists ? ' Kosongkan bila tidak ingin mengubah kata sandi.' : '' }}
                </p>
                @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" @required(! $item->exists)
                       class="mt-1.5 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="rounded-lg bg-brand-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-800">
                {{ $item->exists ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
            </button>
            <a href="{{ route('admin.users.index') }}"
               class="rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
@endsection
