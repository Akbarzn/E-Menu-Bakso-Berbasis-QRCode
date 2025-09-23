{{-- @extends('layouts.app') atau sesuaikan dengan layout kamu
@section('content') --}}
<div class="text-center mt-10">
    <h2 class="text-2xl font-bold mb-4">Konfirmasi Pembayaran</h2>
    <p class="mb-2">Terima kasih, <strong>{{ $nama_pelanggan }}</strong>.</p>
    <p class="mb-6">Silakan lakukan pembayaran ke <strong>kasir</strong> dan tunjukkan kode transaksi Anda.</p>

    <div class="bg-gray-100 p-4 rounded shadow inline-block">
        <p><strong>Kode Transaksi:</strong> {{ $kode_transaksi }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('menu') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Kembali ke Menu</a>
    </div>
</div>

{{-- @endsection --}}
