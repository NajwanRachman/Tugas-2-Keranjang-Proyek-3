
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
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
            padding: 22px 6%;
        }
        header h1 { margin: 0; }
        main { max-width: 900px; margin: 35px auto; padding: 0 20px; }
        .item, .summary {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px #0000000d;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .muted { color: #64748b; }
        .actions { display: flex; align-items: center; gap: 8px; }
        button, .back {
            border: none;
            border-radius: 6px;
            padding: 9px 13px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .qty { background: #dbeafe; color: #1d4ed8; }
        .delete, .clear { background: #fee2e2; color: #b91c1c; }
        .primary { background: #2563eb; color: white; }
        .summary { display: flex; justify-content: space-between; font-weight: bold; }
        .empty { background: white; padding: 30px; text-align: center; border-radius: 12px; }
        .footer-actions { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        form { margin: 0; }
    </style>
</head>
<body>
    <header>
        <h1>Keranjang Belanja</h1>
        <p>Belanja tanpa login</p>
    </header>

    <main>
        @if (count($items) > 0)
            @foreach ($items as $item)
                <div class="item">
                    <div>
                        <h3>{{ $item['barang']->nama }}</h3>
                        <p class="muted">
                            Rp {{ number_format($item['barang']->harga, 0, ',', '.') }}
                            × {{ $item['jumlah'] }}
                            = Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="actions">
                        <form action="{{ route('keranjang.ubah', $item['barang']->id) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="aksi" value="kurang">
                            <button class="qty" type="submit">−</button>
                        </form>

                        <strong>{{ $item['jumlah'] }}</strong>

                        <form action="{{ route('keranjang.ubah', $item['barang']->id) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="aksi" value="tambah">
                            <button class="qty" type="submit"
                                    @disabled($item['jumlah'] >= $item['barang']->stok)>
                                +
                            </button>
                        </form>

                        <form action="{{ route('keranjang.hapus', $item['barang']->id) }}"
                              method="POST">
                            @csrf
                            <button class="delete" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="summary">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="footer-actions">
                <a class="back primary" href="{{ route('barang.index') }}">
                    ← Lanjut Belanja
                </a>

                <form action="{{ route('keranjang.kosongkan') }}" method="POST">
                    @csrf
                    <button class="clear" type="submit">
                        Kosongkan Keranjang
                    </button>
                </form>
            </div>
        @else
            <div class="empty">
                <h2>Keranjang masih kosong</h2>
                <p class="muted">Yuk, pilih barang yang ingin kamu beli.</p>
                <a class="back primary" href="{{ route('barang.index') }}">
                    Lihat Barang
                </a>
            </div>
        @endif
    </main>
</body>
</html>
