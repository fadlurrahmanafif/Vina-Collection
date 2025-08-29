@extends('user.layout.master')

@section('content')
<style>
    .card-header{
        background-color: #00D4E7;
    } 
    
    .btn:hover{
        background-color: #00D4E7;
    }

    a{
        color: black;
        text-decoration: none;
    }

    a:hover{
        color: #00D4E7
    }
</style>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card text-center" style="width: 300px;">
            <div class="card-header h5 text-white">Reset Password</div>
            <div class="card-body px-5">
                <p class="card-text py-2">
                    Masukkan alamat email Anda dan kami akan mengirimkan email berisi instruksi untuk mereset kata sandi Anda.
                </p>
                <div data-mdb-input-init class="form-outline">
                    <input type="email" id="typeEmail" class="form-control my-3" placeholder="Enter your email" />
                </div>
                <br>
                <a href="#" data-mdb-ripple-init class="btn btn-outline-dark w-100">Reset Password</a>
                <div class="d-flex justify-content-between mt-4">
                    <a href="/login">Login</a>
                    <a href="/regis">Register</a>
                </div>
            </div>
        </div>
    </div>
@endsection
