@extends('user.layout.master')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@section('content')

{{-- Search & Filter - Enhanced Mobile First Design --}}
<div class="container-fluid px-2 px-sm-3 mt-3 mt-md-4">
    <!-- Search dan Filter Container -->
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <!-- Search Bar -->
        <div class="col-12 col-md-8 col-lg-9">
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
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
                <ul class="dropdown-menu w-100" aria-labelledby="filterDropdown">
                    <li><h6 class="dropdown-header">Category</h6></li>
                    <li><a class="dropdown-item" href="#">Electronics</a></li>
                    <li><a class="dropdown-item" href="#">Clothing</a></li>
                    <li><a class="dropdown-item" href="#">Books</a></li>
                    <li><a class="dropdown-item" href="#">Home & Garden</a></li>
                    <li><hr class="dropdown-divider"></li>
                    
                    <li><h6 class="dropdown-header">Price Range</h6></li>
                    <li><a class="dropdown-item" href="#">Under Rp 100.000</a></li>
                    <li><a class="dropdown-item" href="#">Rp 100.000 - Rp 500.000</a></li>
                    <li><a class="dropdown-item" href="#">Rp 500.000 - Rp 1.000.000</a></li>
                    <li><a class="dropdown-item" href="#">Over Rp 1.000.000</a></li>
                    <li><hr class="dropdown-divider"></li>
                    
                    <li><h6 class="dropdown-header">Rating</h6></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        5 Stars
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa-regular fa-star"></i>
                        4+ Stars
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#">
                        <i class="fa-solid fa-xmark me-1"></i>Clear All Filters
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Best Seller Section --}}
<div class="container-fluid px-2 px-sm-3">
    <h4 class="mb-2 mb-md-3 fw-bold">Best Seller</h4>
    
    <!-- Cards Grid - Enhanced Responsive -->
    <div class="row g-2 g-sm-3 g-lg-4 mb-4 mb-md-5">
        @for($i = 1; $i <= 6; $i++)
        <!-- Card {{ $i }} -->
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card product-card border-0 shadow-sm h-100">
                <div class="position-relative overflow-hidden">
                    <img src="{{ asset('asset/images/Logo.jpg') }}" 
                         class="card-img-top" 
                         alt="Product {{ $i }}"
                         loading="lazy">
                </div>
                <div class="card-body p-2 p-sm-3">
                    <h6 class="card-title mb-1 text-truncate">Item {{ $i }}</h6>
                    <div class="d-flex align-items-center mb-2">
                        <div class="stars-rating me-2">
                            <i class="fa fa-star text-warning"></i>
                        </div>
                        <small class="text-muted">5+</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price-container">
                            <span class="fw-bold text-primary price-text">Rp.xxx.xxx</span>
                        </div>
                        <button class="btn btn-outline-dark btn-sm cart-btn">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

{{-- New Product Section --}}
<div class="container-fluid px-2 px-sm-3">
    <h4 class="mb-2 mb-md-3 fw-bold">New Product</h4>
    
    <!-- Cards Grid -->
    <div class="row g-2 g-sm-3 g-lg-4 mb-4 mb-md-5">
        @for($i = 1; $i <= 6; $i++)
        <!-- New Product Card {{ $i }} -->
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card product-card border-0 shadow-sm h-100">
                <div class="position-relative overflow-hidden">
                    <img src="{{ asset('asset/images/Logo.jpg') }}" 
                         class="card-img-top" 
                         alt="New Product {{ $i }}"
                         loading="lazy">
                    <!-- New Badge -->
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">New</span>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <h6 class="card-title mb-1 text-truncate">New Item {{ $i }}</h6>
                    <div class="d-flex align-items-center mb-2">
                        <div class="stars-rating me-2">
                            <i class="fa fa-star text-warning"></i>
                        </div>
                        <small class="text-muted">5+</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price-container">
                            <span class="fw-bold text-primary price-text">Rp.xxx.xxx</span>
                        </div>
                        <button class="btn btn-outline-dark btn-sm cart-btn">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

@endsection