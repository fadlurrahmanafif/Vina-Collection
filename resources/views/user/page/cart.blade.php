@extends('user.layout.master')
<link rel="stylesheet" href="{{ asset('css/user/cart.css') }}">
@section('content')
    <h3 class="mt-5 mb-5">Keranjang Belanja</h3>

    @if (!$data || count($data) == 0)
        <div class="empty-cart text-center">
            <h4>Keranjang Belanja Kosong</h4>
            <p>Silahkan pilih produk terlebih dahulu</p>
            <a href="/" class="btn btn-outline-dark">Belanja Sekarang</a>
        </div>
    @else
        <div class="cart-container">
            @foreach ($data as $x)
                <div class="card mb-3 cart-item" data-product-id="{{ $x->product->id }}">
                    <div class="card-body d-flex gap-5 align-items-center">
                        <img src="{{ asset('storage/produk/' . $x->product->foto) }}" class="card-img-top" alt="Product"
                            style="width: 25%; height: 25%;">
                        <form action="{{ route('checkout.proses', $x->id) }}" method="POST" class="w-100">
                            @csrf
                            <div class="desc w-100">
                                <p style="font-size: 24px; font-weight: 700;">{{ $x->product->nama_produk }}</p>
                                <input type="hidden" name="id_barang" value="{{ $x->product->id }}">

                                <!-- Tampilkan harga dengan format Rupiah yang benar -->
                                <div class="price-display fs-4 mb-2">
                                    Rp. {{ number_format($x->product->harga, 0, ',', '.') }}
                                </div>

                                <!-- PENTING: Hidden input harga menggunakan harga dari produk, bukan dari cart -->
                                <input type="hidden" name="harga" class="harga" value="{{ $x->product->harga }}">

                                <div class="row mb-3 mt-3 align-items-center">
                                    <label for="qty" class="col-sm-2 col-form-label fs-5">Jumlah</label>
                                    <div class="d-flex col-sm-5">
                                        <button class="rounded-start bg-secondary p-2 border border-0 plus"
                                            type="button">+</button>

                                        <!-- Input quantity -->
                                        <input type="number" name="stok" class="form-control w-25 text-center qty"
                                            min="1" max="999"
                                            value="{{ $x->stok && $x->stok > 0 && $x->stok <= 999 ? $x->stok : 1 }}"
                                            readonly>
                                        <button class="rounded-end bg-secondary p-2 border border-0 minus"
                                            type="button">-</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <label for="price" class="col-sm-2 fs-5">Total</label>
                                <!-- Input total yang akan diisi oleh JavaScript -->
                                <input type="text" class="col-sm-5 form-control w-25 border-0 fs-5 total" name="total"
                                    readonly placeholder="Rp. 0">
                            </div>

                            <div class="row w-50 gap-1 mt-5">
                                <button type="submit" class="btn btn-outline-dark btn-c">
                                    <i class="fa fa-shopping-cart"></i>
                                    Checkout
                                </button>
                        </form>

                        <form action="{{ route('delete.cart', $x->id) }}" method="POST">
                            <div class="d-flex row">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-d btn-outline-danger delete-item" type="submit"
                                    data-product-id="{{ $x->product->id }}">
                                    <i class="fa fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Area untuk total keseluruhan -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>Subtotal: <span id="subtotal-keseluruhan">Rp. 0</span></h5>
                        <h4>Total Pembayaran: <span id="grandtotal">Rp. 0</span></h4>
                        <input type="hidden" id="totalpembayaran" value="0">
                        <input type="hidden" id="ongkir" value="0">
                        <input type="hidden" id="layanan" value="0">
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('checkout') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Checkout Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        // JavaScript khusus untuk cart (inline untuk memastikan dijalankan)
        $(document).ready(function() {
            console.log('Cart page loaded');

            // Function untuk format Rupiah
            function formatRupiah(angka) {
                return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Function untuk mengambil nilai numeric
            function getNumericValue(value) {
                if (typeof value === 'string') {
                    let cleanValue = value.replace(/[^0-9]/g, '');
                    return parseInt(cleanValue) || 0;
                }
                return parseInt(value) || 0;
            }

            // Function untuk menghitung total per item
            function hitungTotalPerItem(cartItem) {
                const hargaInput = cartItem.find('input[name="harga"]');
                const qtyInput = cartItem.find('input[name="stok"]');
                const totalInput = cartItem.find('input[name="total"]');

                const harga = getNumericValue(hargaInput.val());
                const qty = parseInt(qtyInput.val()) || 1;
                const total = harga * qty;

                totalInput.val(formatRupiah(total));
                return total;
            }

            // Function untuk menghitung subtotal keseluruhan
            function hitungSubtotalKeseluruhan() {
                let subtotal = 0;

                $('.cart-item').each(function() {
                    const total = hitungTotalPerItem($(this));
                    subtotal += total;
                });

                $('#subtotal-keseluruhan').text(formatRupiah(subtotal));
                $('#grandtotal').text(formatRupiah(subtotal));
                $('#totalpembayaran').val(subtotal);

                return subtotal;
            }

            // Event handlers
        //     $(document).on('click', '.plus', function(e) {
        //         e.preventDefault();
        //         const cartItem = $(this).closest('.cart-item');
        //         const qtyInput = cartItem.find('input[name="stok"]');
        //         let qty = parseInt(qtyInput.val()) || 1;
        //         if (qty < 999) {
        //             qtyInput.val(qty + 1);
        //             hitungSubtotalKeseluruhan();
        //         }
        //     });

        //     $(document).on('click', '.minus', function(e) {
        //         e.preventDefault();
        //         const cartItem = $(this).closest('.cart-item');
        //         const qtyInput = cartItem.find('input[name="stok"]');
        //         let qty = parseInt(qtyInput.val()) || 1;
        //         if (qty > 1) {
        //             qtyInput.val(qty - 1);
        //             hitungSubtotalKeseluruhan();
        //         }
        //     });

        //     // Inisialisasi
        //     hitungSubtotalKeseluruhan();
        // });
    </script>
@endsection
