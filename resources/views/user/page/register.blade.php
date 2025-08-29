@extends('user.layout.master')

@section('content')
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .h-custom {
            height: calc(100% - 73px);
        }

        .footer {
            background-color: #00D4E7;
        }

        .btn {
            background-color: white;
        }

        .btn:hover {
            background-color: #00D4E7;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            /* bikin selalu tengah */
            cursor: pointer;
            font-size: 25px;
            color: #6c757d;
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
    </style>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="{{ asset('asset/images/Logo-removebg.png') }}" class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="form-outline mb-4">
                            <label class="form-label">Name</label>
                            <input type="text" name="nama"
                                class="form-control form-control-lg @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" placeholder="Enter name" />
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-outline mb-4">
                            <label class="form-label">Email address</label>
                            <input type="email" name="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Enter email address" />
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-outline mb-3">
                            <label class="form-label">Password</label>
                            <div class="position-relative">
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-lg pe-5 @error('password') is-invalid @enderror"
                                    placeholder="Enter password" />
                                <span class="toggle-password" onclick="togglePassword()">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>



                        <!-- No HP -->
                        <div class="form-outline mb-4">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp"
                                class="form-control form-control-lg @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp') }}" placeholder="Enter No Handphone" />
                            @error('no_hp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-outline-dark btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Register</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account?
                                <a href="{{ route('login') }}" class="link-danger">Login</a>
                            </p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 footer">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                Copyright © 2025. All rights reserved.
            </div>
            <!-- Copyright -->
        </div>
    </section>




    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("bi-eye-slash");
                toggleIcon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("bi-eye");
                toggleIcon.classList.add("bi-eye-slash");
            }
        }
    </script>
@endsection
