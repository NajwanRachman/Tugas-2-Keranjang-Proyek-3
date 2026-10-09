
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Alat Tulis</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }
        header {
            background: #2563eb;
            color: white;
            padding: 20px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        header h1 { margin: 0; font-size: 22px; }
        a { text-decoration: none; }
        .cart-link {
            background: white;
            color: #2563eb;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: bold;
        }
        main { max-width: 1000px; margin: 35px auto; padding: 0 20px; }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #0000000d;
        }
        .icon { font-size: 40px; }
        .price { color: #2563eb; font-weight: bold; font-size: 19px; }
        .button {
            display: block;
            width: 100%;
            border: none;
            background: #2563eb;
            color: white;
            padding: 11px;
            border-radius: 7px;
            cursor: pointer;
            margin-top: 12px;
        }
        .button:disabled { background: #94a3b8; cursor: not-allowed; }
        .alert { background: #dcfce7; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .stock { color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <header>
        <h1>Toko Alat Tulis</h1>
        <a class="cart-link" href="{{ route('keranjang.index') }}">
            Keranjang ({{ $jumlahItem }})
        </a>
    </header>

    <main>
        <h2>Daftar Barang</h2>

        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <div class="products">
            @forelse ($barangs as $barang)
                <div class="card">
                    <div class="icon">📚</div>
                    <h3>{{ $barang->nama }}</h3>
                    <p class="price">
                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                    </p>
                    <p class="stock">Stok: {{ $barang->stok }}</p>

                    <form action="{{ route('keranjang.tambah', $barang->id) }}"
                          method="POST">
                        @csrf
                        <button class="button" type="submit"
                                @disabled(($barang->stok <= 0))>
                            Masukkan ke Keranjang
                        </button>
                    </form>
                </div>
            @empty
                <p>Belum ada barang yang tersedia.</p>
            @endforelse
        </div>
    </main>
</body>
</html>
