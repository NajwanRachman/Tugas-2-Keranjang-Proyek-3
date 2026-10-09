<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $barangs = Barang::where('stok', '>', 0)->get();

        $keranjang = session('keranjang', []);
        $jumlahItem = array_sum($keranjang);

        return view('barang.index', compact(
            'barangs',
            'jumlahItem'
        ));
    }

    public function keranjang()
    {
        $dataKeranjang = session('keranjang', []);

        $barangs = Barang::whereIn(
            'id',
            array_keys($dataKeranjang)
        )->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($dataKeranjang as $id => $jumlah) {
            if (!isset($barangs[$id])) {
                continue;
            }

            $barang = $barangs[$id];
            $subtotal = $barang->harga * $jumlah;

            $items[] = [
                'barang' => $barang,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        $jumlahItem = array_sum($dataKeranjang);

        return view('barang.keranjang', compact(
            'items',
            'total',
            'jumlahItem'
        ));
    }

    public function tambah(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $keranjang = session('keranjang', []);
        $jumlahSekarang = $keranjang[$id] ?? 0;

        if ($jumlahSekarang < $barang->stok) {
            $keranjang[$id] = $jumlahSekarang + 1;
        }

        session(['keranjang' => $keranjang]);

        return back()->with(
            'success',
            'Produk berhasil ditambahkan ke keranjang.'
        );
    }

    public function ubahJumlah(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:tambah,kurang',
        ]);

        $barang = Barang::findOrFail($id);

        $keranjang = session('keranjang', []);
        $jumlah = $keranjang[$id] ?? 0;

        if ($request->aksi === 'tambah') {
            if ($jumlah < $barang->stok) {
                $keranjang[$id] = $jumlah + 1;
            }
        } elseif ($request->aksi === 'kurang') {
            $jumlah--;

            if ($jumlah <= 0) {
                unset($keranjang[$id]);
            } else {
                $keranjang[$id] = $jumlah;
            }
        }

        session(['keranjang' => $keranjang]);

        return back();
    }

    public function hapus($id)
    {
        $keranjang = session('keranjang', []);

        unset($keranjang[$id]);

        session(['keranjang' => $keranjang]);

        return back();
    }

    // Mengosongkan seluruh keranjang
    public function kosongkan()
    {
        session()->forget('keranjang');

        return back();
    }
}
