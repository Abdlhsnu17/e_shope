<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Abdishope') — Belanja lebih mudah</title>
    <meta name="description" content="@yield('description', 'Abdishope menghadirkan pilihan terbaik untuk Anda.')">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|playfair-display:500,600,700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f8f7f4] font-sans text-stone-900 antialiased">
    <div class="bg-stone-900 px-5 py-2.5 text-center text-[11px] font-semibold tracking-[0.12em] text-stone-100 uppercase">Gratis pengiriman untuk pembelian di atas Rp500.000</div>
    <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-stone-200 bg-[#f8f7f4]/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-5 px-5 py-4 lg:px-8">
            <a href="{{ route('home') }}" aria-label="Abdishope beranda"><x-logo class="h-11 w-auto" /></a>
            <nav class="hidden items-center gap-7 text-sm font-medium lg:flex"><a href="{{ route('shop') }}" class="transition hover:text-[#d46b42]">Belanja</a><a href="{{ route('shop', ['category' => 'new']) }}" class="transition hover:text-[#d46b42]">Koleksi Baru</a><a href="#journal" class="transition hover:text-[#d46b42]">Journal</a><a href="#about" class="transition hover:text-[#d46b42]">Tentang Kami</a></nav>
            <div class="flex items-center gap-3">
                <a href="{{ route('shop') }}" aria-label="Cari produk" class="hidden rounded-full p-2 transition hover:bg-stone-200 sm:block"><x-icon name="search" class="size-5" /></a>
                <a href="{{ route('cart') }}" aria-label="Keranjang" class="relative rounded-full p-2 transition hover:bg-stone-200"><x-icon name="cart" class="size-5" /><span class="absolute -right-0.5 -top-0.5 grid size-4 place-items-center rounded-full bg-[#d46b42] text-[9px] font-bold text-white">{{ count(session('cart', [])) }}</span></a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('home') }}" class="hidden text-xs font-bold sm:block">Akun</a>
                @else
                    <a href="{{ route('login') }}" class="hidden text-xs font-bold sm:block">Masuk</a>
                @endauth
                <button type="button" @click="open = !open" aria-label="Buka menu" class="rounded-full p-2 lg:hidden"><x-icon name="menu" class="size-5" /></button>
            </div>
        </div>
        <nav x-show="open" x-cloak class="border-t border-stone-200 bg-[#f8f7f4] px-5 py-5 lg:hidden"><div class="flex flex-col gap-4 text-sm font-semibold"><a href="{{ route('shop') }}">Belanja</a><a href="{{ route('shop', ['category' => 'new']) }}">Koleksi Baru</a><a href="#journal">Journal</a></div></nav>
    </header>
    <main>@yield('content')</main>
    <footer id="about" class="mt-20 bg-stone-900 text-stone-300"><div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">    <div><a href="{{ route('home') }}" aria-label="Abdishope beranda"><x-logo class="h-20 w-auto rounded bg-white p-1" /></a><p class="mt-5 max-w-xs text-sm leading-6 text-stone-400">Pilihan esensial yang dirancang untuk menemani ritme hidup sehari-hari.</p></div><div><p class="text-xs font-bold tracking-[0.16em] text-white uppercase">Belanja</p><div class="mt-5 space-y-3 text-sm"><a href="{{ route('shop') }}" class="block hover:text-white">Semua produk</a><a href="{{ route('shop', ['category' => 'home']) }}" class="block hover:text-white">Untuk rumah</a><a href="{{ route('shop', ['category' => 'wear']) }}" class="block hover:text-white">Busana</a></div></div><div><p class="text-xs font-bold tracking-[0.16em] text-white uppercase">Bantuan</p><div class="mt-5 space-y-3 text-sm"><a href="#" class="block hover:text-white">Pengiriman & pengembalian</a><a href="#" class="block hover:text-white">Cara berbelanja</a><a href="#" class="block hover:text-white">Hubungi kami</a></div></div><div><p class="text-xs font-bold tracking-[0.16em] text-white uppercase">Dapatkan kabar baik</p><p class="mt-5 text-sm leading-6 text-stone-400">Cerita baru dan akses awal koleksi kami.</p><form class="mt-4 flex border-b border-stone-600"><input aria-label="Alamat email" placeholder="Email Anda" class="min-w-0 flex-1 bg-transparent py-3 text-sm text-white outline-none placeholder:text-stone-500"><button class="text-xs font-bold text-white">DAFTAR</button></form></div></div><div class="border-t border-white/10 px-5 py-5 text-center text-xs text-stone-500">© {{ date('Y') }} Abdishope. Made with intention.</div></footer>
</body>
</html>
