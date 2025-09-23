@extends('user.layout.master')


@section('content')
    <div class="container my-4">

        <div class="row g-4">
            <!-- Gambar / Video Produk -->
            <div class="col-md-4">
                <div class="border rounded p-2 mb-3">
                    <img src="{{ asset('storage/produk/' . $product->foto) }}"
                        class="img-fluid rounded align-items-center items-center" alt="{{ $product->nama_produk }}">
                </div>

                <!-- Thumbnail -->
                <div class="d-flex flex-wrap gap-2">
                    <img src="{{ asset('storage/produk/' . $product->foto) }}" class="img-thumbnail"
                        style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;">

                </div>
            </div>

            <!-- Detail Produk -->
            <div class="col-md-6">
                <h3 class="fw-bold">{{ $product->nama_produk }}</h3>

                <div class="d-flex align-items-center mb-2">
                    <span class="text-warning me-2">⭐ {{ number_format($product->rating, 1) }}</span>
                    <small class="text-muted">({{ $product->jumlah_review }} Ulasan)</small>
                    <small class="text-muted ms-2">| {{ $product->stok }} Stok</small>
                    <small class="text-muted ms-2">• {{ $product->stok_out }} Terjual</small>
                </div>

                <!-- Harga -->
                <div class="mb-3">
                    <span class="fs-3 fw-bold text-danger">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                    @if ($product->harga_diskon)
                        <span class="text-muted text-decoration-line-through ms-2">
                            Rp{{ number_format($product->harga_asli, 0, ',', '.') }}
                        </span>
                    @endif
                </div>

                <!-- Variasi -->
                <div class="mb-4">
                    <h6 class="fw-semibold">Ukuran</h6>
                    <div class="d-flex flex-wrap gap-2">
                        {{-- @foreach ($product->varian as $v)
                            <button class="btn btn-outline-secondary btn-sm">{{ $v->nama }}</button>
                        @endforeach --}}
                    </div>
                </div>


                    <div class="d-flex align-items-center mb-2">
                        <button class="btn btn-outline-secondary btn-sm minus me-1" type="button">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control form-control-sm text-center mx-1" name="stok"
                            value="1" min="1" style="width: 60px;" readonly>
                        <button class="btn btn-outline-secondary btn-sm plus ms-1" type="button">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>


                <!-- Tombol -->
                <div class="d-flex gap-3 mt-4">
                    <form action="{{ route('add.to.cart', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="idProduct" value="{{ $product->id }}">
                        <button class="btn btn-outline-dark px-4">+ Keranjang</button>
                    </form>
                    <button class="btn btn-danger px-4">Beli Sekarang</button>
                </div>
            </div>
        </div>

        <!-- Deskripsi & Review -->
        <div class="mt-5">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#deskripsi"
                        type="button" role="tab">Deskripsi</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button"
                        role="tab">Penilaian</button>
                </li>
            </ul>

            <div class="tab-content border border-top-0 p-4" id="productTabContent">
                <!-- Deskripsi -->
                <div class="tab-pane fade show active" id="deskripsi" role="tabpanel">
                    <h5 class="fw-bold">Spesifikasi Produk</h5>
                    <ul>
                        @foreach (explode("\n", $product->spesifikasi) as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-3 text-muted" style="white-space: pre-line;">{{ $product->deskripsi }}</p>
                </div>

                <!-- Review -->
                <div class="tab-pane fade" id="review" role="tabpanel">
                    <h5 class="fw-bold mb-3">Penilaian Produk</h5>
                    {{-- @foreach ($product->reviews as $review)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $review->user->name }}</strong>
                                <span class="text-warning">⭐ {{ $review->rating }}</span>
                            </div>
                            <p class="mb-1">{{ $review->komentar }}</p>
                            @if ($review->foto)
                                <img src="{{ asset('storage/' . $review->foto) }}" class="img-thumbnail mt-2"
                                    style="width:100px">
                            @endif
                        </div>
                    @endforeach --}}
                </div>
            </div>
        </div>

    </div>

    {{-- <script src="{{ asset('js/custom.js') }}"></script> --}}

    @endsection

