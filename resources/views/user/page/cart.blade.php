@extends('user.layout.master')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Keranjang Belanja
                </h3>

                {{-- Alert untuk user yang belum login --}}
                @if (!auth()->check())
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Anda belum login. Untuk melanjutkan checkout, silakan login terlebih
                        dahulu.
                        <a href="{{ route('login') }}" class="alert-link">Login sekarang</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Cart Items --}}
                @if ($data->count() > 0 || (isset($is_guest) && $is_guest && $data->isNotEmpty()))
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    @if (isset($is_guest) && $is_guest)
                                        {{-- GUEST USER - Data dari Session --}}
                                        @foreach ($data as $productId => $item)
                                            <div class="row align-items-center py-3 border-bottom cart-item"
                                                data-id="{{ $item['id_barang'] }}">
                                                <div class="col-md-2">
                                                    <img src="{{ asset('storage/produk/' . $item['foto']) }}"
                                                        class="img-fluid rounded" alt="Product" style="max-height: 80px;">
                                                </div>
                                                <div class="col-md-3">
                                                    <h6 class="mb-1">{{ $item['nama_produk'] }}</h6>
                                                    <small class="text-muted">Produk</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="fw-bold">Rp
                                                        {{ number_format($item['harga_satuan']) }}</span><br>
                                                    <small class="text-muted">per item</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <button class="btn btn-outline-secondary btn-sm minus me-1"
                                                            type="button">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number"
                                                            class="form-control form-control-sm text-center mx-1"
                                                            name="stok" value="{{ $item['stok'] }}" min="1"
                                                            style="width: 60px;">
                                                        <button class="btn btn-outline-secondary btn-sm plus ms-1"
                                                            type="button">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    {{-- Hidden input untuk harga (angka mentah) --}}
                                                    <input type="hidden" name="harga"
                                                        value="{{ $item['harga_satuan'] }}">
                                                    <input type="hidden" name="total" value="{{ $item['total_harga'] }}">
                                                    <small class="text-primary fw-bold total-display">
                                                        Total: <span class="total-text">Rp
                                                            {{ number_format($item['total_harga']) }}</span>
                                                    </small>
                                                </div>
                                                <div class="col-md-2 text-end">
                                                    <form action="{{ route('delete.cart', $productId) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Hapus item ini dari keranjang?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- USER LOGIN - Data dari Database --}}
                                        @foreach ($data as $item)
                                            <div class="row align-items-center py-3 border-bottom cart-item"
                                                data-id="{{ $item->id }}">
                                                <div class="col-md-2">
                                                    <img src="{{ asset('storage/produk/' . $item->product->foto) }}"
                                                        class="img-fluid rounded" alt="Product" style="max-height: 80px;">
                                                </div>
                                                <div class="col-md-3">
                                                    <h6 class="mb-1">{{ $item->product->nama_produk }}</h6>
                                                    <small class="text-muted">Produk</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="fw-bold">Rp
                                                        {{ number_format($item->product->harga) }}</span><br>
                                                    <small class="text-muted">per item</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <button class="btn btn-outline-secondary btn-sm minus me-1"
                                                            type="button">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number"
                                                            class="form-control form-control-sm text-center mx-1"
                                                            name="stok" value="{{ $item->stok }}" min="1"
                                                            style="width: 60px;">
                                                        <button class="btn btn-outline-secondary btn-sm plus ms-1"
                                                            type="button">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>

                                                    {{-- Hidden input untuk harga (angka mentah) --}}
                                                    <input type="hidden" name="harga"
                                                        value="{{ $item->product->harga }}">
                                                    <input type="hidden" name="total" value="{{ $item->harga }}">
                                                    <small class="text-primary fw-bold total-display">
                                                        Total: <span class="total-text">Rp
                                                            {{ number_format($item->harga) }}</span>
                                                    </small>
                                                </div>
                                                <div class="col-md-2 text-end">
                                                    <form action="{{ route('delete.cart', $item->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Hapus item ini dari keranjang?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Order Summary --}}
                        <div class="col-lg-4 mt-4 mt-lg-0">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $subtotal = 0;
                                        if (isset($is_guest) && $is_guest) {
                                            $subtotal = $data->sum('total_harga');
                                        } else {
                                            $subtotal = $data->sum('harga');
                                        }
                                    @endphp

                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Subtotal:</span>
                                        <span class="fw-bold" id="subtotal-keseluruhan">Rp
                                            {{ number_format($subtotal) }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Total:</strong>
                                        <strong class="text-primary" id="grandtotal">Rp
                                            {{ number_format($subtotal) }}</strong>
                                    </div>
                                    <input type="hidden" id="totalpembayaran" value="{{ $subtotal }}">

                                    <small class="text-muted d-block mb-3">
                                        *Ongkir dan biaya layanan akan dihitung di halaman checkout
                                    </small>

                                    @auth
                                        {{-- Jika sudah login, bisa langsung checkout --}}
                                        <form action="{{ route('checkout.proses') }}" method="POST" id="checkout-form">
                                            @csrf
                                            {{-- Dynamic inputs akan diisi via JavaScript custom.js --}}
                                            <div id="dynamic-inputs">
                                                {{-- Fallback static inputs jika JavaScript tidak jalan --}}
                                                @foreach ($data as $item)
                                                    <input type="hidden" name="items[{{ $item->id }}][id_barang]"
                                                        value="{{ $item->id_barang }}">
                                                    <input type="hidden" name="items[{{ $item->id }}][stok]"
                                                        value="{{ $item->stok }}">
                                                    <input type="hidden" name="items[{{ $item->id }}][harga]"
                                                        value="{{ $item->product->harga }}">
                                                    <input type="hidden" name="items[{{ $item->id }}][total]"
                                                        value="{{ $item->harga }}">
                                                @endforeach
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-credit-card me-2"></i>
                                                Lanjut ke Checkout
                                            </button>
                                        </form>
                                        <small class="text-muted d-block text-center mt-2">
                                            Ongkir dan metode pembayaran akan dipilih saat checkout
                                        </small>
                                    @else
                                        {{-- Jika belum login, tampilkan tombol yang redirect ke login --}}
                                        <button type="button" class="btn btn-warning w-100 mb-2"
                                            onclick="showCheckoutLoginModal()">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            Login untuk Checkout
                                        </button>
                                        <small class="text-muted d-block text-center">
                                            Anda perlu login untuk melanjutkan checkout
                                        </small>
                                    @endauth
                                </div>
                            </div>

                            {{-- Continue Shopping --}}
                            <div class="mt-3 text-center">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Lanjut Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Empty Cart --}}
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h4>Keranjang Kosong</h4>
                        <p class="text-muted mb-4">Belum ada produk di keranjang Anda</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Login untuk Checkout - Hanya untuk Guest --}}
    @guest
        <div class="modal fade" id="checkoutLoginModal" tabindex="-1" aria-labelledby="checkoutLoginModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="checkoutLoginModalLabel">
                            <i class="fas fa-credit-card text-primary me-2"></i>
                            Login untuk Checkout
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-user-lock fa-3x text-muted mb-3"></i>
                        <p class="mb-3">Untuk melanjutkan ke checkout dan menyelesaikan pesanan, Anda perlu login terlebih
                            dahulu.</p>
                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle me-1"></i>
                                Jangan khawatir, barang di keranjang tidak akan hilang setelah login!</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nanti Saja</button>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            Login Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showCheckoutLoginModal() {
                var checkoutModal = new bootstrap.Modal(document.getElementById('checkoutLoginModal'));
                checkoutModal.show();
            }
        </script>
    @endguest
@endsection
