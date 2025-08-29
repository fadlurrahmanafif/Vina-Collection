@extends('user.layout.master')

@section('content')
{{-- search --}}
    <div class="content mt-5 d-flex flex-lg-wrap gap-2 mb-5 justify-content-end">
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" style="width: 400px">
            <button class="btn btn-search btn-outline-dark" type="submit">Search</button>
        </form>
        {{-- end search --}}
        {{-- filter --}}
        <div class="d-flex align-items-start ms-5 gap-5">
            <div class="dropdown">
                <a href="#" class="text-decoration-none filter dropdown-toggle" style="font-size: 20px"
                    id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </a>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li>
                        <h6 class="dropdown-header">Category</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Electronics</a></li>
                    <li><a class="dropdown-item" href="#">Clothing</a></li>
                    <li><a class="dropdown-item" href="#">Books</a></li>
                    <li><a class="dropdown-item" href="#">Home & Garden</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <h6 class="dropdown-header">Price Range</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Under Rp 100.000</a></li>
                    <li><a class="dropdown-item" href="#">Rp 100.000 - Rp 500.000</a></li>
                    <li><a class="dropdown-item" href="#">Rp 500.000 - Rp 1.000.000</a></li>
                    <li><a class="dropdown-item" href="#">Over Rp 1.000.000</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <h6 class="dropdown-header">Rating</h6>
                    </li>
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
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li><a class="dropdown-item text-danger" href="#">
                            <i class="fa-solid fa-xmark me-1"></i>Clear All Filters
                        </a></li>
                </ul>
            </div>
        </div>
    </div>
{{-- end filter --}}

{{-- card best seller --}}
    <h4 class="mt-5">Best Seller</h4>
    <div class="content mt-5 d-flex flex-lg-wrap gap-5 mb-5">
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-dark" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
    </div>
{{-- end best seller --}}

{{-- card new produk --}}
    <h4 class="mt-5">New Product</h4>
    <div class="content mt-5 d-flex flex-lg-wrap gap-5 mb-5">
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
        <div class="card" style="width: 180px">
            <div class="card-header " style="border-radius:10px;">
                <img src="{{ asset('asset/images/Logo.jpg') }}" alt="" style="width: 100%; ">
            </div>
            <div class="card-body">
                <p class="m-0 text-justify">Item 1</p>
                <p class="m-0"><i class="fa fa-regular fa-star"></i>5+</p>
            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                <p class="m-0" style="font-size: 16px; font-weight: 600;">Rp.xxx.xxx</p>
                <button class="btn btn-outline-primary" style="font-size: 20px;">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
            </div>
        </div>
    </div>
    {{-- end new product --}}
@endsection
