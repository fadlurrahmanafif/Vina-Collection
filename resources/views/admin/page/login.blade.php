<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome (untuk ikon Facebook/Google) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <title>Vina Collection | {{ $title }}</title>
</head>

<body>
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
                    <form action="{{ route('login.admin') }} " method="POST">
                        @csrf

                        <!-- Email input -->
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

                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Checkbox -->
                            <div class="form-check mb-0">
                                <input class="form-check-input me-2" type="checkbox" value=""
                                    id="form2Example3" />
                                <label class="form-check-label" for="form2Example3">
                                    Remember me
                                </label>
                            </div>
                            <a href="/forgot" class="text-body text-decoration-none">Forgot password?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-outline-dark btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>

</html>
