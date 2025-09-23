@extends('user.layout.master')
<link rel="stylesheet" href="{{ asset('css/user/index.css') }}">
@section('content')
    {{-- Search & Filter - Enhanced Mobile First Design --}}
    <div class="container-fluid px-2 px-sm-3 mt-3 mt-md-4">
        <!-- Search dan Filter Container -->
        <div class="row g-2 g-md-3 mb-3 mb-md-4">

            <!-- Search & Filter -->
            <div class="row mb-4">
                <!-- Search Bar -->
                <div class="col-12 col-md-8 col-lg-9">
                    <form method="GET" action="{{ route('home') }}" class="d-flex" role="search" id="searchForm">
                        <input class="form-control me-2" type="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari produk..." aria-label="Search">
                        <!-- Preserve filter values in search -->
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        <input type="hidden" name="stok" value="{{ request('stok') }}">
                        <button class="btn btn-search btn-outline-dark flex-shrink-0" type="submit">
                            <span class="d-none d-sm-inline">Search</span>
                            <i class="fa-solid fa-search d-sm-none"></i>
                        </button>
                    </form>
                </div>

                <!-- Filter -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="dropdown w-100">
                        <a href="#" class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                            id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-filter me-2"></i>Filter
                        </a>
                        <div class="dropdown-menu p-3 w-100" aria-labelledby="filterDropdown">
                            <form method="GET" action="{{ route('home') }}" id="filterForm">
                                <!-- Preserve search value in filter -->
                                <input type="hidden" name="search" value="{{ request('search') }}">

                                <!-- Harga -->
                                <h6 class="dropdown-header">Price Range</h6>
                                <div class="mb-2 d-flex gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        class="form-control" placeholder="Min">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        class="form-control" placeholder="Max">
                                </div>

                                <hr class="dropdown-divider">

                                <!-- Stok -->
                                <h6 class="dropdown-header">Stok</h6>
                                <select name="stok" class="form-select mb-2">
                                    <option value="">-- Semua Stok --</option>
                                    <option value="available" {{ request('stok') == 'available' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="empty" {{ request('stok') == 'empty' ? 'selected' : '' }}>Habis
                                    </option>
                                </select>

                                <hr class="dropdown-divider">

                                <!-- Tombol -->
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-filter me-1"></i> Terapkan
                                    </button>
                                    <a href="{{ route('home') }}" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-xmark me-1"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Alert untuk user yang belum login --}}
    @if (!auth()->check())
        <div class="container-fluid px-2 px-sm-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-info-circle me-2"></i>
                <strong>Informasi:</strong> Ketika anda ingin checkout,
                Anda perlu login terlebih dahulu.
                <a href="{{ route('login') }}" class="alert-link">Login sekarang</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- Best Seller Section --}}
    @if ($best->count() == 0)
        <div class="container"></div>
    @else
        <div class="container-fluid px-2 px-sm-3">
            <div class="row g-2 g-sm-3 g-lg-4 mb-4 mb-md-5">
                <h4 class="mb-2 mb-md-3 fw-bold">Best Seller</h4>
                @foreach ($best as $b)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('detail', $b->id) }}" class="product-link">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <div class="position-relative overflow-hidden">
                                    <img src="{{ asset('storage/produk/' . $b->foto) }}" class="card-img-top"
                                        alt="New Product" loading="lazy">
                                    <!-- New Badge -->
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">Best</span>
                                </div>
                                <div class="card-body p-2 p-sm-3">
                                    <h6 class="card-title mb-1 text-truncate">{{ $b->nama_produk }}</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <small class="text-muted">• {{ $b->stok }} Terjual</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="price-container">
                                            <span class="fw-bold text-primary price-text">Rp.
                                                {{ number_format($b->harga) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- New Product Section --}}
    <div class="container-fluid px-2 px-sm-3">
        @if (request()->hasAny(['search', 'min_price', 'max_price', 'stok']))
            <h4 class="mb-2 mb-md-3 fw-bold">Hasil Pencarian</h4>
        @else
            <h4 class="mb-2 mb-md-3 fw-bold">New Product</h4>
        @endif

        <!-- Cards Grid -->
        <div class="row g-2 g-sm-3 g-lg-4 mb-4 mb-md-5">
            @if ($products->isEmpty())
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fa-solid fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada produk yang ditemukan</h5>
                        <p class="text-muted">Coba ubah kriteria pencarian Anda</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Semua Produk
                        </a>
                    </div>
                </div>
            @else
                @foreach (request()->hasAny(['search', 'min_price', 'max_price', 'stok']) ? $products : $newProduct as $p)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('detail', $p->id) }}" class="product-link">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <div class="position-relative overflow-hidden">
                                    <img src="{{ asset('storage/produk/' . $p->foto) }}" class="card-img-top"
                                        alt="{{ $p->nama_produk }}" loading="lazy">
                                    @if (request()->hasAny(['search', 'min_price', 'max_price', 'stok']))
                                        <span class="badge bg-info position-absolute top-0 start-0 m-2">Found</span>
                                    @else
                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">New</span>
                                    @endif
                                </div>
                                <div class="card-body p-2 p-sm-3">
                                    <h6 class="card-title mb-1 text-truncate">{{ $p->nama_produk }}</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <small class="text-muted">• {{ $p->stok }} Terjual</small>
                                    </div>
                                    <div class="fw-bold text-primary">
                                        Rp. {{ number_format($p->harga) }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Hapus modal dan script yang tidak diperlukan --}}
@endsection
