@extends('user.layout.master')
<link rel="stylesheet" href="{{ asset('css/user/checkout.css') }}">
@section('content')
    <div class="container-fluid checkout-container">
        <form action="{{ route('proses.pembayaran') }}" method="POST">
            @csrf

            <div class="row mt-5">

                <!-- Form Data Section -->
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>Data Pelanggan</h3>

                            <div class="row mb-3 mt-3 align-items-center">
                                <label for="nama_anda" class="col-form-label col-lg-3 col-md-4 col-sm-12">Nama Anda</label>
                                <div class="col-lg-9 col-md-8 col-sm-12">
                                    <input type="text" class="form-control" id="nama_anda" name="namaAnda"
                                        value="{{ $user ? $user->nama : '' }}" placeholder="Masukkan Nama Anda" autofocus
                                        required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="alamat_anda" class="col-form-label col-lg-3 col-md-4 col-sm-12">Alamat
                                    Anda</label>
                                <div class="col-lg-9 col-md-8 col-sm-12">
                                    <input type="text" class="form-control" id="alamat_anda" name="alamatAnda"
                                        placeholder="Masukkan Alamat Anda" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tlp" class="col-form-label col-lg-3 col-md-4 col-sm-12">No.tlp Anda</label>
                                <div class="col-lg-9 col-md-8 col-sm-12">
                                    <input type="text" class="form-control" id="tlp" name="tlp"
                                        value="{{ $user ? $user->no_hp : '' }}" placeholder="Masukkan Nomor Telepon"
                                        required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ekspedisi" class="col-form-label col-lg-3 col-md-4 col-sm-12">Ekspedisi</label>
                                <div class="col-lg-9 col-md-8 col-sm-12">
                                    <select name="ekspedisi" id="ekspedisi" class="form-control" required>
                                        <option value="">-- Pilih Ekspedisi --</option>
                                        <option value="jnt">J&T Express (Rp. 15.000)</option>
                                        <option value="jne">JNE Express (Rp. 20.000)</option>
                                        <option value="pos">Pos Indonesia (Rp. 12.000)</option>
                                        <option value="sicepat">SiCepat (Rp. 18.000)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="metode" class="col-form-label col-lg-3 col-md-4 col-sm-12">Metode
                                    Pembayaran</label>
                                <div class="col-lg-9 col-md-8 col-sm-12">
                                    <select name="metode" id="metode" class="form-control" required>
                                        <option value="">-- Pilih Metode Pembayaran --</option>
                                        <option value="cod">COD - Cash On Delivery</option>
                                        <option value="dana">DANA</option>
                                        <option value="gopay">GoPay</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary Section -->
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5 class="mb-0">Rincian Pembayaran</h5>
                        </div>
                        <div class="card-body align-items-center">
                            <div class="row mb-3">
                                <label for="subtotal" class="col-form-label col-lg-5 col-md-6 col-sm-12">Subtotal
                                    Pesanan</label>
                                <div class="col-lg-7 col-md-6 col-sm-12">
                                    <input type="text" class="form-control bg-transparent" id="subtotal" name="subtotal"
                                        readonly value="Rp.{{ $detailBelanja }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ongkir" class="col-form-label col-lg-5 col-md-6 col-sm-12">Ongkir</label>
                                <div class="col-lg-7 col-md-6 col-sm-12">
                                    <input type="text" class="form-control bg-transparent" id="ongkir" name="ongkir"
                                        readonly value="Rp. 0">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="layanan" class="col-form-label col-lg-5 col-md-6 col-sm-12">Biaya
                                    Layanan</label>
                                <div class="col-lg-7 col-md-6 col-sm-12">
                                    <input type="text" class="form-control bg-transparent" id="layanan" name="layanan"
                                        readonly value="Rp. 0">
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <label for="totalpembayaran" class="col-form-label col-lg-5 col-md-6 col-sm-12">
                                    <strong>Total Pembayaran</strong>
                                </label>
                                <div class="col-lg-7 col-md-6 col-sm-12">
                                    <input type="text" class="form-control bg-transparent fw-bold" id="totalpembayaran"
                                        name="totalpembayaran" readonly value="Rp. " style="font-size: 1.1em;">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-outline-dark btn-lg">
                                    <i class="fas fa-shopping-cart me-2"></i>Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <!-- Product Cart Items Display Section -->
        <!-- Product Cart Items Display Section -->
        @if (isset($cartItems) && count($cartItems) > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Item yang akan dibeli</h5>
                            <span class="badge bg-primary">{{ count($cartItems) }} Item</span>
                        </div>
                        <div class="card-body">
                            @foreach ($cartItems as $item)
                                <div class="row align-items-center mb-3 pb-3 border-bottom cart-item-display">
                                    <div class="col-md-2 col-3">
                                        <img src="{{ asset('storage/produk/' . $item->product->foto) }}"
                                            class="img-fluid rounded" alt="{{ $item->product->nama_produk }}"
                                            style="width: 100%; max-width: 100px; height: auto; object-fit: cover;">
                                    </div>
                                    <div class="col-md-4 col-9">
                                        <h6 class="mb-1 fw-bold">{{ $item->product->nama_produk }}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{ Str::limit($item->product->deskripsi ?? 'Produk berkualitas', 50) }}
                                        </p>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <small class="text-muted d-block">Harga Satuan</small>
                                        <span class="fw-bold">Rp.
                                            {{ number_format($item->product->harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <small class="text-muted d-block">Jumlah</small>
                                        <span class="fw-bold">{{ $item->stok }} pcs</span>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <small class="text-muted d-block">Subtotal</small>
                                        <!-- PERBAIKAN: Gunakan harga yang tersimpan di cart (sudah total) -->
                                        <span class="fw-bold text-primary">Rp.
                                            {{ number_format($item->harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Summary Row -->
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-md-8 col-6">
                                    <h6 class="mb-0">Total Item: <strong>{{ count($cartItems) }} produk</strong></h6>
                                </div>
                                <div class="col-md-4 col-6 text-end">
                                    <h6 class="mb-0">Subtotal: <strong class="text-primary">Rp.
                                            {{ number_format($detailBelanja, 0, ',', '.') }}</strong></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Tampilkan pesan ketika belum ada item yang di-checkout -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada item yang di-checkout</h5>
                            <p class="text-muted">
                                Silakan kembali ke <a href="{{ route('keranjang') }}"
                                    class="text-decoration-none">halaman
                                    keranjang</a>
                                dan checkout item yang ingin dibeli.
                            </p>
                            <a href="{{ route('keranjang') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Debug Script untuk memastikan data terkirim -->
        <script>
            console.log('=== DEBUG CHECKOUT ===');
            console.log('Cart items count:', {{ count($cartItems ?? []) }});
            console.log('Detail belanja total:', {{ $detailBelanja }});

            @if (isset($cartItems) && count($cartItems) > 0)
                console.log('Items yang di-checkout:');
                @foreach ($cartItems as $index => $item)
                    console.log('Item {{ $index + 1 }}:', {
                        nama: '{{ $item->product->nama_produk }}',
                        stok: {{ $item->stok }},
                        harga_satuan: {{ $item->product->harga }},
                        total_harga: {{ $item->harga }},
                        status: {{ $item->status }}
                    });
                @endforeach
            @else
                console.log('Tidak ada data cart dengan status = 1');
            @endif
        </script>
        {{-- </form> --}}
    </div>

    <!-- Include custom JavaScript -->
    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- Additional inline script for debugging -->
    <script>
        // Debug - tampilkan data user di console
        console.log('User data:', {
            nama: '{{ $user ? $user->nama : 'tidak ada' }}',
            no_hp: '{{ $user ? $user->no_hp : 'tidak ada' }}'
        });

        console.log('Detail belanja:', '{{ $detailBelanja }}');
        console.log('Cart items count:', '{{ count($cartItems ?? []) }}');
    </script>
@endsection
