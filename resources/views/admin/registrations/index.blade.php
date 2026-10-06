@extends('layouts.admin')

@section('title', 'Pendaftaran Siswa Baru')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white shadow-card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.registrations.index') }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium transition {{ ! $filterStatus ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                @foreach (\App\Models\Registration::STATUSES as $status)
                    <a href="{{ route('admin.registrations.index', ['status' => $status]) }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium capitalize transition {{ $filterStatus === $status ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $status }}
                    </a>
                @endforeach
            </div>

            <form method="GET" class="flex gap-2">
                @if ($filterStatus)<input type="hidden" name="status" value="{{ $filterStatus }}">@endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / nomor..."
                       class="w-56 rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <button class="rounded-lg bg-brand-950 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-900">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Nomor</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="hidden px-4 py-3 xl:table-cell">Asal Sekolah</th>
                        <th class="hidden px-4 py-3 lg:table-cell">Jurusan</th>
                        <th class="px-4 py-3 text-center">Dokumen</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="sticky right-0 bg-slate-50 px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <tr class="group hover:bg-slate-50">
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $item->nomor_pendaftaran }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-brand-950">{{ $item->nama_lengkap }}</p>
                                <p class="text-xs text-slate-500">{{ $item->email }}</p>
                            </td>
                            <td class="hidden px-4 py-3 text-slate-600 xl:table-cell">{{ $item->asal_sekolah ?: '—' }}</td>
                            <td class="hidden px-4 py-3 text-slate-600 lg:table-cell">{{ $item->jurusan_pilihan ?: '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $item->documents_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <x-status-badge :status="$item->status" />
                            </td>
                            <td class="sticky right-0 bg-white px-4 py-3 text-right group-hover:bg-slate-50">
                                <a href="{{ route('admin.registrations.show', $item) }}"
                                   class="font-semibold text-brand-700 hover:text-brand-900">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center text-slate-500">Tidak ada data pendaftaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $items->links() }}</div>
    </div>
@endsection
