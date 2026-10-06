@extends('layouts.public')
@section('title', 'Pesanan berhasil')
@section('content')
<section class="mx-auto max-w-2xl px-5 py-20 text-center"><p class="text-xs font-bold tracking-[.16em] text-[#b75c37] uppercase">Pesanan berhasil dibuat</p><h1 class="mt-4 font-display text-5xl">Terima kasih!</h1><p class="mt-5 text-stone-600">Nomor pesanan Anda <strong>{{ $order->order_number }}</strong>. Status pembayaran: {{ $order->payment_status }}.</p><div class="mt-8 rounded-2xl bg-stone-100 p-6 text-left text-sm">@if($order->payment_method === 'bank_transfer') Transfer ke BCA 123 456 7890 a.n. Abdishope. @elseif($order->payment_method === 'ewallet') Instruksi pembayaran e-wallet akan dikirim ke email Anda. @else Siapkan pembayaran saat pesanan tiba. @endif</div><a href="{{ route('shop') }}" class="mt-8 inline-block rounded-full bg-stone-900 px-6 py-3 text-sm font-bold text-white">Lanjut belanja</a></section>
@endsection
