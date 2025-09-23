<?php

namespace App\Http\Controllers;

use App\Http\Requests\prosesPembayaranRequest;
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

    private function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where('id_user', Auth::id())->where('status', 0)->count();
        } else {
            $sessionCart = Session::get('guest_cart', []);
            return count($sessionCart);
        }
    }

    private function mergeGuestCartToUser()
    {
        if (!Auth::check()) return;

        $guestCart = Session::get('guest_cart', []);

        foreach ($guestCart as $item) {
            $existingCart = Cart::where([
                'id_user' => Auth::id(),
                'id_barang' => $item['id_barang'],
                'status' => 0,
            ])->first();

            if ($existingCart) {
                $existingCart->stok += $item['stok'];
                $existingCart->harga += $item['total_harga'];
                $existingCart->save();
            } else {

                Cart::create([
                    'id_user' => Auth::id(),
                    'id_barang' => $item['id_barang'],
                    'stok' => $item['stok'],
                    'harga' => $item['total_harga'],
                    'status' => 0,
                ]);
            }
        }

        Session::forget('guest_cart');
    }

    // home - DIPERBAIKI UNTUK SEARCH & FILTER
    public function index(Request $request)
    {
        $search = $request->input('search');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $stok = $request->input('stok');

        // Produk umum
        $products = Product::query()
            ->when($search, function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', '%' . $search . '%');
            })
            ->when($min_price, function ($q) use ($min_price) {
                $q->where('harga', '>=', $min_price);
            })
            ->when($max_price, function ($q) use ($max_price) {
                $q->where('harga', '<=', $max_price);
            })
            ->when($stok, function ($q) use ($stok) {
                $q->where('stok', '>=', $stok);
            })
            ->get();

        // Best Seller (pakai stok_out >= 10 + ikut filter)
        $best = Product::query()
            ->where('stok_out', '>=', 10)
            ->when($search, function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', '%' . $search . '%');
            })
            ->when($min_price, function ($q) use ($min_price) {
                $q->where('harga', '>=', $min_price);
            })
            ->when($max_price, function ($q) use ($max_price) {
                $q->where('harga', '<=', $max_price);
            })
            ->when($stok, function ($q) use ($stok) {
                $q->where('stok', '>=', $stok);
            })
            ->orderBy('stok_out', 'desc')
            ->take(5)
            ->get();

        // Produk Terbaru
        $newProduct = Product::query()
            ->latest()
            ->when($search, function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', '%' . $search . '%');
            })
            ->when($min_price, function ($q) use ($min_price) {
                $q->where('harga', '>=', $min_price);
            })
            ->when($max_price, function ($q) use ($max_price) {
                $q->where('harga', '<=', $max_price);
            })
            ->when($stok, function ($q) use ($stok) {
                $q->where('stok', '>=', $stok);
            })
            ->take(5)
            ->get();

        $countKeranjang = $this->getCartCount();

        return view('user.page.index', [
            'title' => 'Home',
            'products' => $products,   // produk umum
            'best' => $best,           // best seller
            'newProduct' => $newProduct, // produk terbaru
            'count' => $countKeranjang,
        ]);
    }

    // detail barang/product
    public function detail($id)
    {
        $product = Product::findOrFail($id);
        // dd($product);die;

        return view('user.page.detail',[
            'title' => 'Detail Produk',
            'product' => $product,
        ]);
    }

    // cart
    public function addCart(Request $request)
    {
        $idProduct = $request->input('idProduct');
        $product = Product::find($idProduct);

        if (!$product) {
            Alert::error('Error', 'Produk tidak di temukan');
            return redirect()->back();
        }

        if ($product->stok <= 0) {
            Alert::error('Error', 'Stok produk habis');
            return redirect()->back();
        }

        if (Auth::check()) {
            $existingCart = Cart::where([
                'id_user' => Auth::id(),
                'id_barang' => $idProduct,
                'status' => 0,
            ])->first();

            if ($existingCart) {
                $existingCart->stok += 1;
                $existingCart->harga += $product->harga;
                $existingCart->save();
            } else {
                Cart::create([
                    'id_user' => Auth::id(),
                    'id_barang' => $idProduct,
                    'stok' => 1,
                    'harga' => $product->harga,
                    'status' => 0,
                ]);
            }
        } else {
            $guestCart = Session::get('guest_cart', []);

            if (isset($guestCart[$idProduct])) {
                $guestCart[$idProduct]['stok'] += 1;
                $guestCart[$idProduct]['total_harga'] += $product->harga;
            } else {
                $guestCart[$idProduct] = [
                    'id_barang' => $idProduct,
                    'nama_produk' => $product->nama_produk,
                    'harga_satuan' => $product->harga,
                    'stok' => 1,
                    'total_harga' => $product->harga,
                    'foto' => $product->foto,
                ];
            }

            Session::put('guest_cart', $guestCart);
        }

        Alert::success('Berhasil!', 'Produk berhasil di tambahkan ke keranjang');
        return redirect('/');
    }

    public function destroyCart($id)
    {
        if (Auth::check()) {
            $cartItems = Cart::where('id_user', Auth::id())
                ->where('id', $id)
                ->first();

            if ($cartItems) {
                $cartItems->delete();
                Alert::success('Berhasil!', 'Item berhasil di hapus dari keranjang');
            } else {
                Alert::error('Error', 'Item tidak ditemukan');
            }
        } else {
            $guestCart = Session::get('guest_cart', []);

            if (isset($guestCart[$id])) {
                unset($guestCart[$id]);
                Session::put('guest_cart', $guestCart);
                Alert::success('Berhasil!', 'Item berhasil di hapus dari keranjang');
            } else {
                Alert::error('Error', 'Item tidak ditemukan');
            }
        }

        return redirect()->route('keranjang');
    }

    public function cart()
    {
        $countKeranjang = $this->getCartCount();

        if (Auth::check()) {

            $guestCart = Session::get('guest_cart', []);
            if (!empty($guestCart)) {
                $this->mergeGuestCartTouser();

                $countKeranjang = $this->getCartCount();
            }

            $cartItems = Cart::with('product')
                ->where('id_user', Auth::id())
                ->where('status', 0)
                ->whereHas('product')
                ->get();

            return view('user.page.cart', [
                'title' => 'Keranjang',
                'count' => $countKeranjang,
                'data' => $cartItems,
                'is_guest' => false,
            ]);
        } else {
            $sessionCart = Session::get('guest_cart', []);
            $cartItems = collect($sessionCart);

            return view('user.page.cart', [
                'title' => 'Keranjang',
                'count' => $countKeranjang,
                'data' => $cartItems,
                'is_guest' => true,
            ]);
        }
    }

    // Status pesanan
    public function statusPesanan()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silahkan login untuk melihat status pesanan');
        }

        $countKeranjang = $this->getCartCount();

        // Ambil data transaksi user dengan relasi details
        $transaksiUser = transaksi::with(['details.product'])
            ->where('id_user', Auth::id())
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
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Anda harus login terlebih dahulu untuk melakukan checkout.')
                ->with('checkout_redirect', true);
        }

        // Merge session cart ke database jika user baru login
        $this->mergeGuestCartToUser();

        // Ambil data user yang login
        $user = Auth::user();

        // PERBAIKAN: Ambil cart items yang sudah di-checkout (status = 1)
        // Ini adalah barang yang tadi di-checkout dari halaman cart
        $cartItems = Cart::with('product')
            ->where([
                'id_user' => Auth::id(),
                'status' => 1  // Status 1 = sudah di-checkout
            ])
            ->get();

        // Hitung total dari cart items yang sudah di-checkout
        $detailBelanja = $cartItems->sum('harga');

        // Count keranjang yang belum di-checkout untuk navbar
        $countKeranjang = $this->getCartCount();

        return view('user.page.checkout', [
            'title' => 'Checkout',
            'user' => $user,
            'count' => $countKeranjang,
            'cartItems' => $cartItems,  // Barang yang sudah di-checkout
            'detailBelanja' => $detailBelanja,
        ]);
    }

    // Method checkoutProses() - pastikan ini benar
    public function checkoutProses(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $items = $request->input('items', []);
        $subtotal = 0;

        foreach ($items as $cartId => $item) {
            $stok  = (int) $item['stok'];
            $harga = (int) $item['harga'];
            $total = $stok * $harga;

            $subtotal += $total;

            // Update masing-masing cart
            Cart::where('id', $cartId)
                ->where('id_user', Auth::id())
                ->update([
                    'stok' => $stok,
                    'harga' => $total,
                    'status' => 1,
                ]);
        }

        // Kalau mau simpan transaksi di tabel orders, bisa lanjut di sini
        // Order::create([...])

        Alert::toast('Barang Berhasil  di Checkout.' , 'success');
        return redirect()->route('checkout');
    }

    public function prosesPembayaran(prosesPembayaranRequest $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $data = $request->validated();

        try {
            $data = $request->all();

            // Generate kode transaksi unik
            $count = transaksi::count() + 1;
            $codeTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Ambil cart items yang sudah di-checkout (status = 1)
            $cartItems = Cart::with('product')
                ->where([
                    'id_user' => Auth::id(),
                    'status' => 1
                ])
                ->get();

            if ($cartItems->isEmpty()) {
                Alert::error('Error', 'Tidak ada item untuk diproses');
                return redirect()->route('keranjang');
            }

            // if (!empty($data['latitude']) && !empty($data['longitude'])) {
            //     $isValidation = $this->validateCoordinates(
            //         $data['latitude'],
            //         $data['longitude'],
            //         $data['alamatAnda'],
            //     );

            //     if (!$isValidation) {
            //         Alert::warning('Peringatan', 'Koordinat tidak sesuai dengan alamat. Pesanan tetap di proses');
            //     }
            // }

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
                'id_user' => Auth::id(),
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
                'id_user' => Auth::id(),
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
