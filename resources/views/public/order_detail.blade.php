<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-2xl">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Detail Pesanan</h1>

        <div class="mb-4">
            <p><span class="font-semibold">Kode Transaksi:</span> {{ $transaction->kode_transaksi }}</p>
            <p><span class="font-semibold">Nama Pelanggan:</span> {{ $transaction->nama_pelanggan }}</p>
            <p><span class="font-semibold">Nomor Meja:</span> {{ $transaction->nomor_meja }}</p>
            <p><span class="font-semibold">Metode Pembayaran:</span> {{ ucfirst($transaction->metode_pembayaran) }}</p>
            <p><span class="font-semibold">Status:</span> 
                <span class="px-2 py-1 rounded text-white 
                    {{ $transaction->status == 'menunggu' ? 'bg-yellow-500' : '' }}
                    {{ $transaction->status == 'diproses' ? 'bg-blue-500' : '' }}
                    {{ $transaction->status == 'selesai' ? 'bg-green-500' : '' }}
                    {{ $transaction->status == 'batal' ? 'bg-red-500' : '' }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </p>
        </div>

        <h2 class="text-lg font-semibold mb-2">Pesanan</h2>
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Menu</th>
                    <th class="p-2 text-center">Jumlah</th>
                    <th class="p-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->menuTransactions as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->menu->nama_menu }}</td>
                    <td class="p-2 text-center">{{ $item->jumlah }}</td>
                    <td class="p-2 text-right">Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-xl font-bold text-gray-800">
            Total: Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
        </div>
    </div>
</body>
</html>
