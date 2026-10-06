@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white shadow-card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-4">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / NIS / NISN..."
                       class="w-56 rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <select name="kelas" class="rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400">
                    <option value="">Semua kelas</option>
                    @foreach ($daftarKelas as $kelas)
                        <option value="{{ $kelas }}" @selected($filterKelas === $kelas)>{{ $kelas }}</option>
                    @endforeach
                </select>
                <button class="rounded-lg bg-brand-950 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-900">Filter</button>
            </form>

            <a href="{{ route('admin.students.create') }}"
               class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-800">
                + Tambah Siswa
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Kelas</th>
                        {{-- Kolom sekunder disembunyikan di layar sempit agar tabel tetap muat --}}
                        <th class="hidden px-4 py-3 xl:table-cell">Jurusan</th>
                        <th class="hidden px-4 py-3 lg:table-cell">L/P</th>
                        <th class="px-4 py-3">Status</th>
                        {{-- Kolom aksi menempel di kanan agar tak pernah tertutup saat tabel digeser --}}
                        <th class="sticky right-0 bg-slate-50 px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <tr class="group hover:bg-slate-50">
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $item->nis }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-brand-950">{{ $item->nama }}</p>
                                <p class="text-xs text-slate-500">{{ $item->email ?: 'Tanpa email' }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->kelas ?: '—' }}</td>
                            <td class="hidden px-4 py-3 text-slate-600 xl:table-cell">{{ $item->jurusan ?: '—' }}</td>
                            <td class="hidden px-4 py-3 text-slate-600 lg:table-cell">{{ $item->jenis_kelamin }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $item->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="sticky right-0 bg-white px-4 py-3 group-hover:bg-slate-50">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.students.edit', $item) }}" class="font-semibold text-brand-700 hover:text-brand-900">Ubah</a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $item) }}"
                                          onsubmit="return confirm('Hapus data siswa ini?')">
                                        @csrf @method('DELETE')
                                        <button class="font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center text-slate-500">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $items->links() }}</div>
    </div>
@endsection
