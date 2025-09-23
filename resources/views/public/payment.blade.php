<!DOCTYPE html>
<html>
<head>
    <title>Bayar Pesanan</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <h2>Proses Pembayaran</h2>
    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            window.snap.pay('{{ $snapToken }}');
        };
    </script>
</body>
</html>
