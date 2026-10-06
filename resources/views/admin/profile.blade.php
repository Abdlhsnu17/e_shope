@extends('layouts.admin')

@section('title', 'Profil Sekolah')

@section('content')
    <form method="POST" action="{{ route('admin.profile.update') }}" class="max-w-5xl space-y-6" x-data="{ tab: 'identitas' }">
        @csrf @method('PUT')

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-base font-bold text-brand-950">Identitas Sekolah</h2>
                <p class="mt-1 text-sm text-slate-600">Data ini tampil pada header, footer, dan halaman profil situs publik.</p>
            </div>

            <div class="grid lg:grid-cols-[14rem_1fr]">
                <nav class="flex gap-2 overflow-x-auto border-b border-slate-100 bg-slate-50 p-3 lg:flex-col lg:border-b-0 lg:border-r">
                    @foreach ([
                        ['id' => 'identitas', 'label' => 'Identitas', 'desc' => 'Nama, NPSN, jenjang'],
                        ['id' => 'kontak', 'label' => 'Kontak', 'desc' => 'Email, telepon, alamat'],
                        ['id' => 'profil', 'label' => 'Profil', 'desc' => 'Visi, misi, sejarah'],
                    ] as $tab)
                        <button type="button"
                                @click="tab = '{{ $tab['id'] }}'"
                                :class="tab === '{{ $tab['id'] }}' ? 'border-brand-200 bg-white text-brand-950 shadow-card' : 'border-transparent text-slate-600 hover:bg-white/70 hover:text-brand-900'"
                                class="min-w-40 rounded-lg border px-4 py-3 text-left transition lg:min-w-0">
                            <span class="block text-sm font-bold">{{ $tab['label'] }}</span>
                            <span class="mt-0.5 block text-xs text-slate-500">{{ $tab['desc'] }}</span>
                        </button>
                    @endforeach
                </nav>

                <div class="p-6">
                    <section x-show="tab === 'identitas'" x-cloak>
                        <div class="mb-5">
                            <h3 class="text-sm font-bold text-brand-950">Data Dasar</h3>
                            <p class="mt-1 text-sm text-slate-500">Informasi utama sekolah untuk identitas publik.</p>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-form.input name="nama" label="Nama Sekolah" required :value="$item->nama" class="sm:col-span-2" />
                            <x-form.input name="npsn" label="NPSN" :value="$item->npsn" />
                            <x-form.input name="jenjang" label="Jenjang" :value="$item->jenjang" placeholder="Contoh: SMA" />
                            <x-form.input name="akreditasi" label="Akreditasi" :value="$item->akreditasi" placeholder="Contoh: A" />
                            <x-form.input name="kepala_sekolah" label="Kepala Sekolah" :value="$item->kepala_sekolah" />
                        </div>
                    </section>

                    <section x-show="tab === 'kontak'" x-cloak>
                        <div class="mb-5">
                            <h3 class="text-sm font-bold text-brand-950">Kontak & Lokasi</h3>
                            <p class="mt-1 text-sm text-slate-500">Dipakai di header, footer, dan halaman profil.</p>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-form.input name="email" label="Email" type="email" :value="$item->email" />
                            <x-form.input name="telepon" label="Telepon" :value="$item->telepon" />
                            <x-form.textarea name="alamat" label="Alamat" :value="$item->alamat" class="sm:col-span-2" rows="5" />
                        </div>
                    </section>

                    <section x-show="tab === 'profil'" x-cloak>
                        <div class="mb-5">
                            <h3 class="text-sm font-bold text-brand-950">Visi, Misi &amp; Sejarah</h3>
                            <p class="mt-1 text-sm text-slate-500">Narasi ini tampil di halaman profil sekolah.</p>
                        </div>
                        <div class="space-y-5">
                            <x-form.textarea name="visi" label="Visi" :value="$item->visi" rows="3" />
                            <x-form.textarea name="misi" label="Misi" :value="$item->misi" rows="6"
                                             hint="Tulis satu poin misi per baris." />
                            <x-form.textarea name="sejarah" label="Sejarah Singkat" :value="$item->sejarah" rows="8"
                                             hint="Pisahkan antar paragraf dengan satu baris kosong." />
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button class="admin-primary">
                Simpan Profil Sekolah
            </button>
        </div>
    </form>
@endsection
