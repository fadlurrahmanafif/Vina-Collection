<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProdukRequest;
use App\Models\DetailTransaksi;
use App\Models\Product;
use App\Models\transaksi;
use App\Models\User;
use App\Services\ProdukService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use function Laravel\Prompts\alert;

class AdminController extends Controller
{
    protected $produkService;
    protected $userService;

    public function __construct(ProdukService $produkService, UserService $userService)
    {
        $this->produkService = $produkService;
        $this->userService = $userService;
    }
    public function dasboard()
    {
        $totalProduk = \App\Models\Product::count();
        $totalPesanan = \App\Models\transaksi::count();
        $totalUser = \App\Models\User::count();

        $recentProduk = \App\Models\Product::latest()->first();
        $recentPesanan = \App\Models\transaksi::latest()->first();
        $recentUser = \App\Models\User::latest()->first();

        return view('admin.page.dasboard', [
            'name' => 'Dashboard',
            'title' =>  'Admin Dasboard',
            'totalProduk' => $totalProduk,
            'totalPesanan' => $totalPesanan,
            'totalUser' => $totalUser,
            'recentProduk' => $recentProduk,
            'recentPesanan' => $recentPesanan,
            'recentUser' => $recentUser,
        ]);
    }

    public function product()
    {
        $data = Product::paginate(3);
        return view('admin.page.product', [
            'name' => 'Product',
            'title' => 'Admin Product',
            'data' => $data,
        ]);
    }

    // pesanan
    public function dataPesanan()
    {
        $pesanan = transaksi::with('details.products')
            ->orderBy('tanggal_pemesanan', 'desc')
            ->paginate(10);
        return view('admin.page.pesanan', [
            'name' => 'Data Pesanan',
            'title' => 'Admin Data Pesanan',
            'pesanan' => $pesanan,
        ]);
    }

    public function updateStatusPesanan(Request $request, $id)
    {
        try {
            $transaksi = transaksi::findOrFail($id);

            $request->validate([
                'status_pesanan' => 'required|in:pending,dikonfirmasi,diproses,dikirim,selesai,dibatalkan'
            ]);

            // PERBAIKAN: Jika admin mengubah status ke 'dibatalkan', kembalikan stok
            if ($request->status_pesanan === 'dibatalkan' && $transaksi->status_pesanan !== 'dibatalkan') {
                DB::beginTransaction();

                // Kembalikan stok produk jika belum dikembalikan
                $details = DetailTransaksi::where('id_transaksi_code', $transaksi->code_transaksi)->get();
                foreach ($details as $detail) {
                    $product = Product::find($detail->id_barang);
                    if ($product) {
                        $product->stok += $detail->stok;
                        $product->stok_out -= $detail->stok;
                        $product->save();
                    }
                }

                // Update status transaksi (data detail tetap ada, tidak dihapus)
                $transaksi->update([
                    'status_pesanan' => $request->status_pesanan
                ]);

                DB::commit();
            } else {
                // Status lainnya, update normal
                $transaksi->update([
                    'status_pesanan' => $request->status_pesanan
                ]);
            }

            Alert::success('Berhasil!', 'Status pesanan berhasil diupdate');
            return redirect()->back();
        } catch (\Exception $e) {
            if (isset($request->status_pesanan) && $request->status_pesanan === 'dibatalkan') {
                DB::rollback();
            }
            Alert::error('Error', 'Gagal mengupdate status: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroyPesanan(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = transaksi::findOrFail($id);

            DetailTransaksi::where('id_transaksi_code', $data->code_transaksi)->delete();

            $data->delete();


            DB::commit();

            Alert::toast('Data berhasil dihapus', 'success');
            return redirect()->route('data.pesanan');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal menghapus data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // user data
    public function userData()
    {
        $data = User::paginate(3);
        return view('admin.page.userdata', [
            'name' => 'User Data',
            'title' => 'Admin User Data',
            'data' => $data,
        ]);
    }

    // CRUD ADD PRODUK
    public function addModal()
    {
        return view('admin.modal.addModal', [
            'title' => 'Tambah Data Product',
        ]);
    }

    public function store(ProdukRequest $request)
    {
        // Jika sampai di sini berarti validation passed
        try {
            $this->produkService->store(
                $request->validated(),
                $request->file('foto')
            );

            return redirect()
                ->route('product')
                ->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->route('product')
                ->withInput()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage())
                ->with('showModal', true);
        }
    }

    public function editModal($id)
    {
        $data = Product::findOrFail($id);

        return view('admin.modal.editModal', [
            'title' => 'Edit Data Product',
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Product::findOrFail($id);

        if ($request->file('foto')) {
            $photo = $request->file('foto');
            $filename = date('Ymd') . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('storage/produk'), $filename);
            $data->foto = $filename;
        } else {
            $filename = $request->foto;
        }

        $field = [
            'nama_produk' => $request->nama_produk,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $filename,
        ];

        $data::where('id', $id)->update($field);
        return redirect()
            ->route('product');
    }

    public function destroy(Request $request, $id)
    {
        $data = Product::findOrFail($id);
        $data->delete();
        Alert::toast('Data berhasil dihapus', 'success');
        return redirect()->route('product');
    }


    // auth admin

    public function showLogin()
    {
        return view('admin.page.login', [
            'title' => 'Admin Login'
        ]);
    }

    public function loginAdmin(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dasboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function logoutadmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/adminlogin');
    }
}
