<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailTransaksi;
use App\Models\Product;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    // home
    public function index(Request $request)
    {
        $query = Product::query();

        //search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('stok')) {
            if ($request->stock === 'avaible') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'empty') {
                $query->where('stock', '=', 0);
            }
        }

        $best = Product::where('stok_out', '>=', '10')->get();
        $data = Product::all();
        $countKeranjang = Cart::where(['id_user' => 'guest123', 'status' => 0])->count();
        return view('user.page.index', [
            'title' => 'Home',
            'data' =>  $data,
            'best' => $best,
            'count' => $countKeranjang,
        ]);
    }

    // cart
    public function addCart(Request $request)
    {
        $idProduct = $request->input('idProduct');

        $db = new Cart;
        $product = Product::find($idProduct);
        $harga = $product->harga;

        $field = [
            'id_user' => 'guest123',
            'id_barang' => $idProduct,
            'stok' => 1,
            'harga' => $harga, // Simpan angka murni saja
        ];

        $db::create($field);
        return redirect('/');
    }

    public function destroyCart($id)
    {
        $data = Cart::findOrFail($id);
        $data->delete();
        Alert::toast('Data berhasil dihapus', 'success');
        return redirect()->route('keranjang');
    }

    public function cart()
    {
        $db = Cart::with('product')->where(['id_user' => 'guest123', 'status' => 0])->get();
        // dd($db->product->nama_produk);die;
        $countKeranjang = Cart::where(['id_user' => 'guest123', 'status' => 0])->count();
        return view('user.page.cart', [
            'title' => 'Keranjang',
            'count' => $countKeranjang,
            'data' => $db,
        ]);
    }

    // Status pesanan
    public function statusPesanan()
    {

        $countKeranjang = Cart::where(['id_user' => 'guest123', 'status' => 0])->count();

        // Ambil data transaksi user dengan relasi details
        $transaksiUser = transaksi::with(['details.product'])
            ->where('id_user', 'guest123')
            ->orderBy('tanggal_pemesanan', 'desc')
            ->get();

        return view('user.page.status', [
            'count' => $countKeranjang,
            'title' => 'Status Pesanan',
            'transaksiUser' => $transaksiUser,
        ]);
    }

    public function konfirmasiPesanan(Request $request)
    {
        try {
            $codeTransaksi = $request->code_transaksi;

            $transaksi = transaksi::where('code_transaksi', $codeTransaksi)->first();

            if (!$transaksi) {
                Alert::error('Error', 'Pesanan tidak di temukan');
                return redirect()->back();
            }

            if ($transaksi->status_pesanan !== 'selesai') {
                Alert::error('Error', 'Pesanan belum di konfirmasi. Status' . $transaksi->getStatusText());
                return redirect()->back();
            }

            DB::beginTransaction();

            DetailTransaksi::where('id_transaksi_code', $codeTransaksi)->delete();

            $transaksi->delete();

            DB::commit();

            Alert::success('Berhasil!', 'Pesanan telah di terima oleh anda');
            return redirect()->route('status.pesanan');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function batalkanPesanan(Request $request)
    {
        try {
            $codeTransaksi = $request->code_transaksi;

            $transaksi = transaksi::where('code_transaksi', $codeTransaksi)->first();

            if (!$transaksi) {
                Alert::error('Error', 'Pesanan tidak di temukan');
                return redirect()->back();
            }

            if (!in_array($transaksi->status_pesanan, ['pending', 'dikonfirmasi'])) {
                Alert::error('Error', 'Pesannan tidak dapat di batalkan. Status saat ini' . $transaksi->getStatusText());
                return redirect()->back();
            }

            // PERBAIKAN: Gunakan DB Transaction
            DB::beginTransaction();

            // Kembalikan stok produk
            $details = DetailTransaksi::where('id_transaksi_code', $codeTransaksi)->get();
            foreach ($details as $detail) {
                $product = Product::find($detail->id_barang);
                if ($product) {
                    $product->stok += $detail->stok;
                    $product->stok_out -= $detail->stok;
                    $product->save();
                }
            }

            // PERBAIKAN: Hanya update status, JANGAN hapus data
            // Data akan dihapus oleh admin nanti
            $transaksi->update([
                'status_pesanan' => 'dibatalkan',
            ]);

            DB::commit();

            Alert::success('Berhasil!', 'Pesanan berhasil di batalkan. Admin akan menghapus data pesanan ini.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // checkout
    public function checkout()
    {
        // Ambil data user yang login
        $user = Auth::user();

        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Anda harus login terlebih dahulu untuk melakukan checkout.')
                ->with('redirect_after_login', url()->current());
        }

        // PERBAIKAN: Ambil cart items yang sudah di-checkout (status = 1)
        // Ini adalah barang yang tadi di-checkout dari halaman cart
        $cartItems = Cart::with('product')
            ->where([
                'id_user' => 'guest123',
                'status' => 1  // Status 1 = sudah di-checkout
            ])
            ->get();

        // Hitung total dari cart items yang sudah di-checkout
        $detailBelanja = 0;
        foreach ($cartItems as $item) {
            // Gunakan harga yang tersimpan di cart (sudah dikalikan dengan stok)
            $detailBelanja += $item->harga;
        }

        // Count keranjang yang belum di-checkout untuk navbar
        $countKeranjang = Cart::where(['id_user' => 'guest123', 'status' => 0])->count();

        return view('user.page.checkout', [
            'title' => 'Checkout',
            'user' => $user,
            'count' => $countKeranjang,
            'cartItems' => $cartItems,  // Barang yang sudah di-checkout
            'detailBelanja' => $detailBelanja,
        ]);
    }

    // Method checkoutProses() - pastikan ini benar
    public function checkoutProses(Request $request, $id)
    {
        $data = $request->all();

        // Generate kode transaksi
        $code = transaksi::count();
        $codeTransaksi = date('Ymd') . $code;

        // Bersihkan format harga/total sebelum disimpan
        $totalBersih = (int) preg_replace('/[^0-9]/', '', $data['total']);

        // // Simpan detail barang ke tabel detail_transaksi
        // DetailTransaksi::create([
        //     'id_transaksi' => $codeTransaksi,
        //     'id_barang' => $data['id_barang'],
        //     'stok' => $data['stok'],
        //     'harga' => $totalBersih,
        // ]);

        // PENTING: Update cart dengan status = 1 (sudah di-checkout)
        Cart::where('id', $id)->update([
            'stok' => $data['stok'],
            'harga' => $totalBersih,  // Total harga (harga satuan * stok)
            'status' => 1,  // Ubah status menjadi 1 = sudah di-checkout
        ]);

        Alert::toast('Berhasil Checkout', 'success');
        return redirect()->route('checkout');
    }

    public function prosesPembayaran(Request $request)
    {
        try {
            $data = $request->all();

            // Generate kode transaksi unik
            $count = transaksi::count() + 1;
            $codeTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Ambil cart items yang sudah di-checkout (status = 1)
            $cartItems = Cart::with('product')
                ->where([
                    'id_user' => 'guest123',
                    'status' => 1
                ])
                ->get();

            if ($cartItems->isEmpty()) {
                Alert::error('Error', 'Tidak ada item untuk diproses');
                return redirect()->route('keranjang');
            }

            DB::beginTransaction();

            // Hitung total belanja dan quantity
            $totalBelanja = 0;
            $totalQty = 0;
            foreach ($cartItems as $item) {
                $totalBelanja += $item->harga;
                $totalQty += $item->stok;
            }

            // Ambil biaya ongkir berdasarkan ekspedisi yang dipilih
            $ongkir = 0;
            switch ($data['ekspedisi']) {
                case 'jnt':
                    $ongkir = 15000;
                    break;
                case 'jne':
                    $ongkir = 20000;
                    break;
                case 'pos':
                    $ongkir = 12000;
                    break;
                case 'sicepat':
                    $ongkir = 18000;
                    break;
            }

            // Hitung biaya layanan berdasarkan metode pembayaran
            $biayaLayanan = 0;
            switch ($data['metode']) {
                case 'cod':
                    $biayaLayanan = 1000;
                    break;
                case 'dana':
                    $biayaLayanan = 1500;
                    break;
                case 'gopay':
                    $biayaLayanan = 1000;
                    break;
                case 'transfer':
                    $biayaLayanan = 0;
                    break;
            }

            $totalPembayaran = $totalBelanja + $ongkir + $biayaLayanan;

            // Simpan data transaksi utama - sesuai dengan struktur tabel existing
            $transaksi = transaksi::create([
                'code_transaksi' => $codeTransaksi,
                'total_qty' => $totalQty,
                'total_harga' => $totalBelanja,  // untuk backward compatibility
                'nama_pelanggan' => $data['namaAnda'],
                'alamat' => $data['alamatAnda'],
                'no_telp' => $data['tlp'],
                'ekspedisi' => $data['ekspedisi'],
                'metode_pembayaran' => $data['metode'],
                'subtotal' => $totalBelanja,
                'ongkir' => $ongkir,
                'biaya_layanan' => $biayaLayanan,
                'total_pembayaran' => $totalPembayaran,
                'status_pesanan' => 'pending',
                'tanggal_pemesanan' => now(),
                'id_user' => 'guest123',
            ]);

            // Simpan detail transaksi untuk setiap item
            foreach ($cartItems as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,  // ID dari tabel transaksis
                    'id_transaksi_code' => $codeTransaksi,  // Code transaksi untuk relasi
                    'id_barang' => $item->id_barang,
                    'nama_barang' => $item->product->nama_produk,
                    'harga_satuan' => $item->product->harga,
                    'stok' => $item->stok,
                    'harga' => $item->harga, // total harga per item
                    'status' => 1,  // sesuai dengan struktur existing
                ]);

                // Kurangi stok produk
                $product = Product::find($item->id_barang);
                if ($product) {
                    $product->stok -= $item->stok;
                    $product->stok_out += $item->stok; // untuk best seller
                    $product->save();
                }
            }

            // Hapus cart items yang sudah diproses (status = 1)
            Cart::where([
                'id_user' => 'guest123',
                'status' => 1
            ])->delete();

            DB::commit();

            Alert::success('Berhasil!', 'Pesanan berhasil dibuat dengan kode: ' . $codeTransaksi);
            return redirect()->route('status.pesanan');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
