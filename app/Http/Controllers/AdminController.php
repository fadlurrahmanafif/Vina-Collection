<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProdukRequest;
use App\Models\Product;
use App\Models\User;
use App\Services\ProdukService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.page.dasboard', [
            'name' => 'Dashboard',
            'title' =>  'Admin Dasboard'
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

    public function dataPesanan()
    {
        return view('admin.page.pesanan', [
            'name' => 'Data Pesanan',
            'title' => 'Admin Data Pesanan'
        ]);
    }

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

    public function update(Request $request,$id)
    {
        $data = Product::findOrFail($id);

        if($request->file('foto')){
            $photo = $request->file('foto');
            $filename = date ('Ymd'). '_' . $photo->getClientOriginalName();
            $photo->move(public_path('storage/produk'), $filename);
            $data->foto = $filename;
        }else {
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

        $data::where('id',$id)->update($field);
        return redirect()
        ->route('product');
    }

    public function destroy(Request $request, $id)
    {
         $data = Product::findOrFail($id);
         $data->delete();
         Alert::toast('Data berhasil dihapus','success');
         return redirect()->route('product');
    }


    // auth admin

    public function showLogin()
    {
        return view('admin.page.login',[
            'title' => 'Admin Login'
        ]);
    }

        public function loginAdmin(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

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
