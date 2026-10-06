<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — Abdishope</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f8f7f4] font-sans text-stone-900">
<main class="grid min-h-screen lg:grid-cols-2">
    <section class="hidden bg-stone-900 p-14 text-white lg:flex lg:flex-col lg:justify-between">
        <a href="{{ route('home') }}" aria-label="Abdishope beranda"><x-logo class="h-28 w-auto rounded bg-white p-2" /></a>
        <div>
            <p class="text-xs font-bold tracking-[.18em] text-[#e8875e] uppercase">Merchant workspace</p>
            <h1 class="mt-4 max-w-md font-display text-5xl leading-tight">Kelola toko Anda dalam satu tempat.</h1>
            <p class="mt-6 max-w-md leading-7 text-stone-400">Atur produk, pelanggan, pesanan, stok, dan transaksi Abdishope melalui dashboard admin.</p>
        </div>
        <p class="text-sm text-stone-500">© {{ date('Y') }} Abdishope</p>
    </section>
    <section class="flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            <a href="{{ route('home') }}" aria-label="Abdishope beranda" class="lg:hidden"><x-logo class="h-14 w-auto" /></a>
            <h2 class="mt-10 font-display text-4xl lg:mt-0">Masuk</h2>
            <p class="mt-2 text-sm text-stone-500">Masuk ke akun Abdishope Anda.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            @if (session('success'))
                <div class="mt-5 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                @csrf
                <label class="block text-sm font-semibold">Email<input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-lg border border-stone-300 bg-white p-3"></label>
                <label class="block text-sm font-semibold">Kata sandi<input type="password" name="password" required class="mt-2 w-full rounded-lg border border-stone-300 bg-white p-3"></label>
                <label class="flex items-center gap-2 text-sm text-stone-600"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                <button class="w-full rounded-full bg-stone-900 py-3.5 text-sm font-bold text-white">Masuk</button>
            </form>
            <p class="mt-7 text-center text-sm text-stone-500">Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-stone-900">Daftar sekarang</a></p>
            <a href="{{ route('home') }}" class="mt-5 block text-center text-sm font-semibold text-stone-600 underline underline-offset-4">← Kembali ke beranda toko</a>
        </div>
    </section>
</main>
</body>
</html>
