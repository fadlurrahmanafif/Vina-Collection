@extends('user.layout.master')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@section('content')
    <style>
        /* Additional responsive styles jika diperlukan */
    </style>
    
    <div class="container-fluid checkout-container">
        <div class="row mt-5">
            <!-- Form Data Section -->
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Data</h3>

                        <div class="row mb-3 mt-3 align-items-center">
                            <label for="nama_anda" class="col-form-label col-lg-3 col-md-4 col-sm-12">Nama Anda</label>
                            <div class="col-lg-9 col-md-8 col-sm-12">
                                <input type="text" class="form-control" id="nama_anda" name="namaAnda"
                                    placeholder="Masukkan Nama Anda" autofocus>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="alamat_anda" class="col-form-label col-lg-3 col-md-4 col-sm-12">Alamat Anda</label>
                            <div class="col-lg-9 col-md-8 col-sm-12">
                                <input type="text" class="form-control" id="alamat_anda" name="alamatAnda"
                                    placeholder="Masukkan Alamat Anda">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="tlp" class="col-form-label col-lg-3 col-md-4 col-sm-12">No.tlp Anda</label>
                            <div class="col-lg-9 col-md-8 col-sm-12">
                                <input type="number" class="form-control" id="tlp" name="tlp"
                                    placeholder="Masukkan Nomor Telepon">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="ekspedisi" class="col-form-label col-lg-3 col-md-4 col-sm-12">Ekspedisi</label>
                            <div class="col-lg-9 col-md-8 col-sm-12">
                                <select name="ekspedisi" id="ekspedisi" class="form-control">
                                    <option value="">-- Pilih Ekspedisi --</option>
                                    <option value="jnt">J&T Ekspress</option>
                                    <option value="jne">JNE Ekspress</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="metode" class="col-form-label col-lg-3 col-md-4 col-sm-12">Metode Pembayaran</label>
                            <div class="col-lg-9 col-md-8 col-sm-12">
                                <select name="metode" id="metode" class="form-control">
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="cod">COD (Cash On Delivery)</option>
                                    <option value="dana">Dana</option>
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
                            <label for="subtotal" class="col-form-label col-lg-5 col-md-6 col-sm-12">Subtotal Pesanan </label>
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <input type="text" class="form-control bg-transparent" id="subtotal" name="subtotal"
                                    readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="ongkir" class="col-form-label col-lg-5 col-md-6 col-sm-12">Ongkir </label>
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <input type="text" class="form-control bg-transparent" id="ongkir" name="ongkir"
                                    readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="layanan" class="col-form-label col-lg-5 col-md-6 col-sm-12">Biaya layanan </label>
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <input type="text" class="form-control bg-transparent" id="layanan" name="layanan"
                                    readonly>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mb-3">
                            <label for="totalpembayaran" class="col-form-label col-lg-5 col-md-6 col-sm-12">Total Pembayaran </label>
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <input type="text" class="form-control bg-transparent fw-bold" id="totalpembayaran"
                                    name="totalpembayaran" readonly>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-outline-dark">Buat Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Card Section -->
        <div class="card mb-3 mt-5">
            <div class="card-body d-flex gap-5 align-items-center">
                <div class="product-image-container">
                    <img src="{{ asset('asset/images/Logo.jpg') }}" class="card-img-top img-fluid" alt="Product"
                        style="width: 100%; max-width: 200px; height: auto;">
                </div>
                <div class="desc w-100">
                    <p class="product-title" style="font-size: 24px; font-weight: 700; margin-bottom: 0.5rem;">Item 1</p>
                    <input type="number" class="form-control border-0 price-input" id="harga" value="200000" readonly>
                    
                    <div class="row mb-3 mt-3 align-items-center">
                        <label for="qty" class="col-lg-2 col-md-3 col-sm-12 col-form-label quantity-label">Jumlah</label>
                        <div class="d-flex col-lg-5 col-md-6 col-sm-12 justify-content-start justify-content-lg-start">
                            <input type="number" name="qty" class="form-control quantity-input border-0" id="qty"
                                min="1" max="999" value="1" readonly>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <label for="total" class="col-lg-2 col-md-3 col-sm-12 total-label">Total</label>
                        <div class="col-lg-5 col-md-6 col-sm-12">
                            <input type="text" class="form-control border-0 total-input" readonly id="total">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk responsive behavior -->
    <script>
        // Handle responsive layout changes
        function handleResponsiveLayout() {
            const screenWidth = window.innerWidth;
            const productCard = document.querySelector('.card-body.d-flex');
            const productImage = document.querySelector('.card-img-top');
            const desc = document.querySelector('.desc');
            
            if (screenWidth <= 767) {
                // Mobile layout
                if (productCard) {
                    productCard.style.flexDirection = 'column';
                    productCard.style.textAlign = 'center';
                }
                if (desc) {
                    desc.style.textAlign = 'center';
                }
            } else if (screenWidth <= 991) {
                // Tablet layout
                if (productCard) {
                    productCard.style.flexDirection = 'column';
                }
                if (desc) {
                    desc.style.textAlign = 'center';
                }
            } else {
                // Desktop layout
                if (productCard) {
                    productCard.style.flexDirection = 'row';
                    productCard.style.textAlign = 'left';
                }
                if (desc) {
                    desc.style.textAlign = 'left';
                }
            }
        }
        
        // Call on load and resize
        window.addEventListener('load', handleResponsiveLayout);
        window.addEventListener('resize', handleResponsiveLayout);
        
        // Calculate total (existing functionality)
        function calculateTotal() {
            const harga = document.getElementById('harga').value || 0;
            const qty = document.getElementById('qty').value || 1;
            const total = harga * qty;
            document.getElementById('total').value = new Intl.NumberFormat('id-ID').format(total);
        }
        
        // Update total when quantity changes
        document.getElementById('qty').addEventListener('input', calculateTotal);
        
        // Initial calculation
        calculateTotal();
    </script>
@endsection