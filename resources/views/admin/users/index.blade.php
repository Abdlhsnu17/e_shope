@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white shadow-card">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-4">
            <form method="GET" class="flex gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / email..."
                       class="w-64 rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <button class="rounded-lg bg-brand-950 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-900">Cari</button>
            </form>

            <a href="{{ route('admin.users.create') }}"
               class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-800">
                + Tambah Pengguna
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Peran</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Login Terakhir</th>
                        <th class="sticky right-0 bg-slate-50 px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($items as $item)
                        <tr class="group hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-full bg-brand-100 text-xs font-bold text-brand-800">
                                        {{ $item->initials }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-brand-950">
                                            {{ $item->name }}
                                            @if ($item->is(auth()->user()))
                                                <span class="ml-1 text-xs font-normal text-slate-500">(Anda)</span>
                                            @endif
                                        </p>
                                        <p class="truncate text-xs text-slate-500">{{ $item->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize text-slate-700">
                                    {{ $item->role }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">
                                {{ $item->last_login_at ? $item->last_login_at->diffForHumans() : 'Belum pernah' }}
                            </td>
                            <td class="sticky right-0 bg-white px-4 py-3 group-hover:bg-slate-50">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.users.edit', $item) }}" class="font-semibold text-brand-700 hover:text-brand-900">Ubah</a>
                                    @unless ($item->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.users.destroy', $item) }}"
                                              onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button class="font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $items->links() }}</div>
    </div>
@endsection
