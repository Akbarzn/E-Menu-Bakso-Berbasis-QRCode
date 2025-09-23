<!DOCTYPE html>
<html>
<head><title>Data Pelanggan</title></head>
<body>
    <h1>Isi Data Pelanggan</h1>

    <form method="POST" action="{{ route('order.submit') }}">
        @csrf

        <input type="hidden" name="menus" value="{{ base64_encode(serialize($menuDipilih)) }}">

        <label>Nama Pelanggan:</label><br>
        <input type="text" name="nama_pelanggan" required><br><br>

        <label>Nomor Meja:</label><br>
        <input type="text" name="nomor_meja" required><br><br>

        <label>Metode Pembayaran:</label><br>
        <select name="metode_pembayaran" required>
            <option value="manual">Manual</option>
            <option value="midtrans">Midtrans</option>
        </select><br><br>

        <button type="submit">Pesan Sekarang</button>
    </form>

    <hr>
    <h3>Ringkasan Pesanan</h3>
    <ul>
        @foreach ($menuDipilih as $item)
            <li>{{ $item['menu']->nama_menu }} x {{ $item['jumlah'] }} = Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</li>
        @endforeach
    </ul>
</body>
</html>
