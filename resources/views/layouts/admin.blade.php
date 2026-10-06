<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Panel Admin</title>
    <meta name="theme-color" content="#111d34">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:600,700,800&display=swap">
    {{-- Dijalankan sebelum render agar sidebar tidak berkedip lebar saat pindah halaman --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('sidebar') === 'collapsed') {
                    document.documentElement.dataset.sidebar = 'collapsed';
                }
            } catch (e) { /* localStorage diblokir — biarkan sidebar terbentang */ }

            window.toggleSidebar = function () {
                var html = document.documentElement;
                var ciut = html.dataset.sidebar === 'collapsed';

                if (ciut) {
                    delete html.dataset.sidebar;
                } else {
                    html.dataset.sidebar = 'collapsed';
                }

                try {
                    localStorage.setItem('sidebar', ciut ? 'expanded' : 'collapsed');
                } catch (e) { /* abaikan bila penyimpanan tidak tersedia */ }
            };
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell min-h-screen font-sans text-slate-700 antialiased" x-data="{ sidebar: false }">

@php
    $menu = [
        ['rute' => 'admin.dashboard', 'label' => 'Dashboard', 'aktif' => 'admin.dashboard', 'ikon' => 'home'],
        ['rute' => 'admin.products.index', 'label' => 'Produk', 'aktif' => 'admin.products.*', 'ikon' => 'building'],
        ['rute' => 'admin.announcements.index', 'label' => 'Berita & Pemberitahuan', 'aktif' => 'admin.announcements.*', 'ikon' => 'newspaper'],
    ];

    if (auth()->user()?->isAdmin()) {
        $menu[] = ['rute' => 'admin.users.index', 'label' => 'Pengguna', 'aktif' => 'admin.users.*', 'ikon' => 'cog'];
    }
@endphp

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    {{-- Posisi awal ditulis statis agar tidak menutupi konten sebelum Alpine aktif --}}
    <aside :class="sidebar && 'translate-x-0!'"
           class="admin-sidebar fixed inset-y-0 left-0 z-40 flex shrink-0 -translate-x-full flex-col overflow-y-auto overflow-x-hidden border-r border-white/10 bg-brand-950 shadow-2xl shadow-brand-950/20 transition-transform lg:static lg:translate-x-0 lg:shadow-none">
        <div class="sidebar-link flex h-16 items-center gap-3 border-b border-white/10 px-5">
            <x-logo compact class="size-9 shrink-0" />
            <div class="sidebar-label leading-tight">
                <p class="whitespace-nowrap text-sm font-bold text-white">Panel Admin</p>
                <p class="whitespace-nowrap text-[11px] font-medium text-brand-100/60">Abdishope</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1.5 p-3">
            @foreach ($menu as $item)
                @php $aktif = request()->routeIs($item['aktif']); @endphp
                <a href="{{ route($item['rute']) }}" title="{{ $item['label'] }}"
                   class="sidebar-link group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ $aktif ? 'bg-white text-brand-950 shadow-card' : 'text-brand-100/60 hover:bg-white/10 hover:text-white' }}">
                    @if ($aktif)
                        <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 rounded-r-full bg-accent-500"></span>
                    @endif
                    <x-icon :name="$item['ikon']" class="size-5 shrink-0" />
                    <span class="sidebar-label whitespace-nowrap">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="space-y-3 border-t border-white/10 p-3">
            <a href="{{ route('home') }}" target="_blank" title="Lihat Situs"
               class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold text-brand-100/70 transition hover:bg-white/10 hover:text-white">
                <x-icon name="external" class="size-5 shrink-0" />
                <span class="sidebar-label whitespace-nowrap">Lihat Situs</span>
            </a>
            <div class="sidebar-label rounded-lg border border-white/10 bg-white/5 px-3 py-2.5">
                <p class="text-[11px] font-semibold uppercase text-brand-100/50">Masuk sebagai</p>
                <p class="mt-1 truncate text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </aside>

    <div x-show="sidebar" x-cloak @click="sidebar = false" x-transition.opacity
         class="fixed inset-0 z-30 bg-brand-950/60 lg:hidden"></div>

    {{-- Konten --}}
    <div class="relative flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-slate-200/80 bg-white/88 px-4 shadow-[0_1px_0_rgba(15,23,42,0.03)] backdrop-blur-xl sm:px-6">
            <div class="flex min-w-0 items-center gap-3">
                {{-- Layar kecil: buka laci sidebar --}}
                <button @click="sidebar = !sidebar" aria-label="Buka menu"
                        class="grid size-9 shrink-0 place-items-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 lg:hidden">
                    <x-icon name="menu" />
                </button>

                {{-- Layar lebar: ciutkan sidebar agar area kerja melebar --}}
                <button type="button" onclick="toggleSidebar()" aria-label="Ciutkan menu"
                        title="Ciutkan / bentangkan menu"
                        class="hidden size-9 shrink-0 place-items-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 lg:grid">
                    <x-icon name="menu" />
                </button>
                <div class="min-w-0">
                    <p class="hidden text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-400 sm:block">Administrasi</p>
                    <h1 class="truncate text-base font-bold tracking-tight text-brand-950">@yield('title', 'Dashboard')</h1>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative shrink-0">
                <button @click="open = !open"
                        class="flex items-center gap-2.5 rounded-lg border border-transparent px-2 py-1.5 transition hover:border-slate-200 hover:bg-white hover:shadow-card">
                    <span class="grid size-8 place-items-center rounded-lg bg-brand-700 text-xs font-bold text-white shadow-card">
                        {{ auth()->user()->initials }}
                    </span>
                    <span class="hidden text-left sm:block">
                        <span class="block text-sm font-semibold leading-4 text-slate-800">{{ auth()->user()->name }}</span>
                        <span class="block text-[11px] font-medium capitalize text-slate-500">{{ auth()->user()->role }}</span>
                    </span>
                </button>

                <div x-show="open" x-cloak @click.outside="open = false" x-transition.opacity
                     class="absolute right-0 z-30 mt-2 w-64 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lift">
                    <div class="border-b border-slate-100 px-4 py-3">
                        <p class="text-sm font-semibold text-brand-950">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        <p class="mt-2 inline-block rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold capitalize text-brand-700">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="p-1.5">
                        @csrf
                        <button class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                            <x-icon name="logout" class="size-4" /> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="relative flex-1 p-4 sm:p-6 lg:p-8">
            <x-alert />
            <div class="mt-4">
                @yield('content')
            </div>
        </main>
    </div>
</div>

</body>
</html>
