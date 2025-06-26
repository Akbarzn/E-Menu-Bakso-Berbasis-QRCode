<!DOCTYPE html>
<html>
<head>
    <title>Menu Pelanggan</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        .menu-item { display: flex; justify-content: space-between; align-items: center; margin: 10px 0; }
        .counter { display: flex; align-items: center; }
        .counter button { padding: 4px 10px; margin: 0 5px; cursor: pointer; }
        .total { font-weight: bold; margin-top: 20px; }
        button.order { margin-top: 20px; padding: 10px 20px; background: green; color: white; border: none; cursor: pointer; }

        .hidden { display: none; }
        .filter-btns button { margin: 5px; padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Menu Bakso</h1>

    <div class="filter-btns">
        <button onclick="showCategory('makanan')">🍜 Makanan</button>
        <button onclick="showCategory('minuman')">🥤 Minuman</button>
    </div>

    <form method="POST" action="#">
        @csrf

        <div id="makanan" class="category">
            <h2>Makanan</h2>
            @foreach ($makanan as $menu)
            <div class="menu-item">
                <div>
                    <strong>{{ $menu->nama_menu }}</strong><br>
                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                </div>
                <div class="counter">
                    <button type="button" class="minus" data-id="{{ $menu->id }}" data-harga="{{ $menu->harga }}">-</button>
                    <input type="number" name="jumlah[{{ $menu->id }}]" id="qty-{{ $menu->id }}" value="0" readonly>
                    <button type="button" class="plus" data-id="{{ $menu->id }}" data-harga="{{ $menu->harga }}">+</button>
                </div>
            </div>
            @endforeach
        </div>

        <div id="minuman" class="category hidden">
            <h2>Minuman</h2>
            @foreach ($minuman as $menu)
            <div class="menu-item">
                <div>
                    <strong>{{ $menu->nama_menu }}</strong><br>
                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                </div>
                <div class="counter">
                    <button type="button" class="minus" data-id="{{ $menu->id }}" data-harga="{{ $menu->harga }}">-</button>
                    <input type="number" name="jumlah[{{ $menu->id }}]" id="qty-{{ $menu->id }}" value="0" readonly>
                    <button type="button" class="plus" data-id="{{ $menu->id }}" data-harga="{{ $menu->harga }}">+</button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="total">
            Total: Rp <span id="total">0</span>
        </div>

        <button type="submit" class="order">Pesan Sekarang</button>
    </form>

    <script>
        function showCategory(kategori) {
            document.querySelectorAll('.category').forEach(el => el.classList.add('hidden'));
            document.getElementById(kategori).classList.remove('hidden');
        }

        const plusButtons = document.querySelectorAll('.plus');
        const minusButtons = document.querySelectorAll('.minus');
        const totalSpan = document.getElementById('total');

        plusButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const input = document.getElementById('qty-' + id);
                let jumlah = parseInt(input.value) || 0;
                input.value = jumlah + 1;
                updateTotal();
            });
        });

        minusButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const input = document.getElementById('qty-' + id);
                let jumlah = parseInt(input.value) || 0;
                if (jumlah > 0) {
                    input.value = jumlah - 1;
                    updateTotal();
                }
            });
        });

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('input[id^="qty-"]').forEach(input => {
                const harga = parseInt(input.previousElementSibling?.getAttribute('data-harga')) || 0;
                const qty = parseInt(input.value) || 0;
                total += qty * harga;
            });
            totalSpan.textContent = total.toLocaleString('id-ID');
        }

        // Default tampil makanan
        showCategory('makanan');
    </script>
</body>
</html>
