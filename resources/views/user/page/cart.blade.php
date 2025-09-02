@extends('user.layout.master')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@section('content')
    <h3 class="mt-5">Kerajang Belanja</h3>
    <div class="card mb-3">
        <div class="card-body d-flex gap-5 align-items-center">
            <img src="{{ asset('asset/images/Logo.jpg') }}" class="card-img-top" alt="Product"
                style="width: 20%; height: 20%;">
            <div class="desc w-100">
                <p style="font-size: 24px; font-weight: 700;">Item 1</p>
                <input type="number" class="form-control border-0 fs-1" id="harga" value="200000"></input>
                <div class="row mb-3 mt-3 align-items-center">
                    <label for="qty" class="col-sm-2 col-form-label fs-5">Jumlah</label>
                    <div class="d-flex col-sm-5">
                        <button class="rounded-start bg-secondary p-2 border border-0" id="plus">+</button>
                        <input type="number" name="qty" class="form-control w-25 text-center" id="qty"
                            min="0" max="999" value="1">
                        <button class="rounded-end bg-secondary p-2 border border-0" id="minus" disabled>-</button>
                    </div>
                </div>
                <div class="row align-items-center">
                    <label for="price" class="col-sm-2 fs-5">Total</label>
                    <input type="text" class="col-sm-5 form-control w-25 border-0 fs-5" readonly id="total">
                </div>
                <div class="row d-flex gap-4">
                    <a href="/checkout" class="btn btn-outline-dark mt-3 col-sm-4 btn-c">
                        <i class="fa fa-shopping-cart"></i>
                        Checkout
                    </a>
                    <div class="btn btn-outline-dark mt-3 col-sm-4 btn-d">
                        <i class="fa fa-trash"></i>
                        Delete
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
