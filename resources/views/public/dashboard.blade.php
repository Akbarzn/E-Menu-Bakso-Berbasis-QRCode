<!DOCTYPE html>
<html>
<head>
    <title>Scan QR untuk Lihat Menu</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .qr-container { margin-top: 30px; }
    </style>
</head>
<body>
    <h1>Scan QR Code</h1>
    <p>Scan kode ini untuk melihat menu bakso</p>

    <div class="qr-container">
        {!! $qrCode !!}
    </div>

    <p>Atau buka langsung: <a href="{{ $link }}">{{ $link }}</a></p>
</body>
</html>
