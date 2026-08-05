<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    {{-- SEO --}}
    <meta name="description" content="Reset your TradeBO account password securely to regain access to the TradeBO Admin Portal, trading dashboard, memberships, reports, and account management features.">
    <meta name="keywords" content="TradeBO Reset Password, Forgot Password, Password Recovery, TradeBO Account Recovery, Secure Password Reset, Trading CRM, Admin Portal, User Authentication">
    <meta name="author" content="PixelPearl Technologies LLP">
    <meta name="robots" content="noindex, nofollow">

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/assets/favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/assets/favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/assets/favicon.ico') }}" />

    <!-- Title -->
    <title>{{ config('app.name', 'TradeBO') }} | Reset Password Link</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/assets/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/assets/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/assets/vendors/styles/style.css') }}" />

    {{-- Toaster Message --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('admin.login') }}">
                    <img src="{{ asset('/backend/assets/img/logo/mastermind-logo.webp') }}" data-no-optimize="1" alt="mastermind-logo" />
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><a href="{{ route('admin.login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('backend/assets/vendors/images/mastermind_reset_password.webp') }}" data-no-optimize="1" alt="forgot-password" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                       <div class="login-title d-flex flex-column align-items-center justify-content-center">
                            <img src="{{ asset('/backend/assets/img/logo/mastermind-logo.webp') }}"
                                data-no-optimize="1"
                                alt="mastermind-logo"
                                style="width: 280px !important; height: 100px !important;" />
                            <h2 class="text-primary mt-3">Reset Password Link</h2>
                        </div>

                        <form method="POST" action="{{ route('admin.password.email') }}" aria-label="{{ __('Reset Password') }}" enctype="multipart/form">
                            @csrf

                            <div class="input-group custom">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Enter Email Id">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-email"></i></span>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary w-100" type="submit">
                                        {{ __('Send Password Reset Link') }}
                                    </button>

                                    <div class="mt-2 text-center">
                                        <p class="mb-0">
                                            I Don't have an account ?
                                            <a href="{{ route('admin.register') }}" class="fw-semibold text-primary">
                                                <b>
                                                    Sign Up
                                                </b>
                                            </a>
                                        </p>
                                        <p>
                                            Already have an account?
                                            <a href="{{ route('admin.login') }}" class="fw-semibold text-primary">
                                                <b>
                                                    Sign In
                                                </b>
                                            </a>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="{{ asset('/backend/assets/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('/backend/assets/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('/backend/assets/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('/backend/assets/vendors/scripts/layout-settings.js') }}"></script>

    <script>
        @if(Session::has('message'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.success("{{ session('message') }}");
        @endif

        @if(Session::has('error'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.error("{{ session('error') }}");
        @endif

        @if(Session::has('info'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.info("{{ session('info') }}");
        @endif

        @if(Session::has('warning'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
                toastr.warning("{{ session('warning') }}");
        @endif
    </script>
</body>

</html>
