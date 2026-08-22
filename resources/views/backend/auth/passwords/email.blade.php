<!DOCTYPE html>
<html lang="en">

<head>

    <!-- =========================================================
         BASIC PAGE INFO
    ========================================================== -->

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1"
    >


    <!-- =========================================================
         SEO
    ========================================================== -->

    <meta
        name="description"
        content="Securely reset your Mastermind Travels account password and regain access to the Mastermind Travels Management Portal."
    >

    <meta
        name="keywords"
        content="Mastermind Travels Password Reset, Forgot Password, Account Recovery, Mastermind Travels Portal, Driver Portal, Admin Portal, Transportation Management"
    >

    <meta
        name="author"
        content="Mastermind Travels"
    >

    <meta
        name="robots"
        content="noindex, nofollow"
    >


    <!-- =========================================================
         FAVICON
    ========================================================== -->

    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="{{ asset('backend/assets/favicon.ico') }}"
    >

    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{ asset('backend/assets/favicon.ico') }}"
    >

    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('backend/assets/favicon.ico') }}"
    >


    <!-- =========================================================
         TITLE
    ========================================================== -->

    <title>
        {{ config('app.name', 'Mastermind Travels') }}
        | Reset Password
    </title>


    <!-- =========================================================
         CSRF
    ========================================================== -->

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="csrf-param"
        content="_token"
    >


    <!-- =========================================================
         CSS
    ========================================================== -->

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('/backend/assets/vendors/styles/core.css') }}"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('/backend/assets/vendors/styles/icon-font.min.css') }}"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('/backend/assets/vendors/styles/style.css') }}"
    >


    <!-- =========================================================
         TOASTR
    ========================================================== -->

    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"
    ></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        type="text/css"
    >

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"
    ></script>


    <!-- =========================================================
         MASTER MIND TRAVELS CUSTOM STYLE
    ========================================================== -->

    <style>

        :root {
            --mastermind-navy: #0b2a5b;
            --mastermind-deep-navy: #071a3d;
            --mastermind-gold: #c58a24;
            --mastermind-gold-light: #e0ad45;
            --mastermind-bg: #f4f7fb;
            --mastermind-text: #111827;
            --mastermind-muted: #667085;
        }


        body.login-page {
            background: var(--mastermind-bg);
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .login-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }


        .login-header .brand-logo img {
            max-height: 65px;
            width: auto;
            object-fit: contain;
        }


        .login-menu ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }


        .login-menu ul li a {
            color: var(--mastermind-navy);
            font-weight: 600;
            text-decoration: none;
            transition: .2s ease;
        }


        .login-menu ul li a:hover {
            color: var(--mastermind-gold);
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN WRAPPER
        |--------------------------------------------------------------------------
        */

        .login-wrap {
            min-height: calc(100vh - 80px);
            padding: 45px 0;
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN BOX
        |--------------------------------------------------------------------------
        */

        .login-box {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN TITLE
        |--------------------------------------------------------------------------
        */

        .login-title img {
            object-fit: contain;
        }


        .login-title h2 {
            color: var(--mastermind-navy) !important;
            font-weight: 700;
            font-size: 27px;
        }


        .login-title p {
            color: var(--mastermind-muted);
            font-size: 14px;
            line-height: 24px;
        }


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        .custom .form-control {
            border-color: #d9e0ea;
            height: 52px;
        }


        .custom .form-control:focus {
            border-color: var(--mastermind-navy);
            box-shadow: 0 0 0 3px rgba(11, 42, 91, .08);
        }


        .input-group-text {
            background: #ffffff;
            border-color: #d9e0ea;
            color: var(--mastermind-navy);
        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        .mastermind-btn {
            background: var(--mastermind-navy);
            border: 1px solid var(--mastermind-navy);
            color: #ffffff;
            height: 52px;
            border-radius: 7px;
            font-weight: 600;
            transition: .25s ease;
        }


        .mastermind-btn:hover {
            background: var(--mastermind-deep-navy);
            border-color: var(--mastermind-deep-navy);
            color: #ffffff;
            transform: translateY(-1px);
        }


        /*
        |--------------------------------------------------------------------------
        | LINKS
        |--------------------------------------------------------------------------
        */

        .mastermind-link {
            color: var(--mastermind-navy);
            font-weight: 600;
            text-decoration: none;
        }


        .mastermind-link:hover {
            color: var(--mastermind-gold);
        }


        /*
        |--------------------------------------------------------------------------
        | SECURITY MESSAGE
        |--------------------------------------------------------------------------
        */

        .security-message {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--mastermind-gold);
            border-radius: 8px;
            padding: 14px 16px;
            margin-top: 22px;
        }


        .security-message p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
            line-height: 22px;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .mastermind-footer {
            text-align: center;
            margin-top: 25px;
            color: #94a3b8;
            font-size: 12px;
            line-height: 20px;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 767px) {

            .login-wrap {
                padding: 25px 15px;
            }

            .login-header .container-fluid {
                padding: 10px 15px;
            }

            .login-header .brand-logo img {
                max-height: 50px;
            }

            .login-title img {
                width: 220px !important;
                height: auto !important;
            }

            .login-title h2 {
                font-size: 23px;
            }

        }

    </style>

</head>


<body class="login-page">


    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="login-header box-shadow">

        <div
            class="container-fluid d-flex justify-content-between align-items-center"
        >

            <!-- Logo -->

            <div class="brand-logo">

                <a href="{{ route('admin.login') }}">

                    <img
                        src="{{ asset('/backend/assets/img/logo/mastermind-logo.webp') }}"
                        data-no-optimize="1"
                        alt="Mastermind Travels"
                    >

                </a>

            </div>


            <!-- Login -->

            <div class="login-menu">

                <ul>

                    <li>

                        <a href="{{ route('admin.login') }}">

                            Sign In

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </div>



    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->

    <div
        class="login-wrap d-flex align-items-center flex-wrap justify-content-center"
    >

        <div class="container">

            <div class="row align-items-center">


                <!-- =================================================
                     LEFT SIDE
                ================================================== -->

                <div class="col-md-6 col-lg-7 text-center">

                    <img
                        src="{{ asset('backend/assets/vendors/images/mastermind_reset_password.webp') }}"
                        data-no-optimize="1"
                        alt="Mastermind Travels Password Recovery"
                        class="img-fluid"
                    >

                    <div class="mt-3">

                        <h4
                            style="
                                color:#0b2a5b;
                                font-weight:700;
                            "
                        >
                            Secure Account Recovery
                        </h4>

                        <p
                            style="
                                color:#667085;
                                max-width:520px;
                                margin:10px auto;
                                line-height:25px;
                            "
                        >
                            Recover your Mastermind Travels account
                            securely and continue managing your
                            transportation operations.
                        </p>

                    </div>

                </div>



                <!-- =================================================
                     RIGHT SIDE
                ================================================== -->

                <div class="col-md-6 col-lg-5">

                    <div
                        class="login-box bg-white box-shadow"
                    >

                        <div class="pd-40">


                            <!-- =====================================
                                 TITLE
                            ====================================== -->

                            <div
                                class="login-title d-flex flex-column align-items-center justify-content-center"
                            >

                                <img
                                    src="{{ asset('/backend/assets/img/logo/mastermind-logo.webp') }}"
                                    data-no-optimize="1"
                                    alt="Mastermind Travels"
                                    style="
                                        width:280px !important;
                                        height:auto !important;
                                        max-height:100px;
                                    "
                                >


                                <h2 class="mt-3 mb-2">

                                    Reset Password

                                </h2>


                                <p class="text-center mb-0">

                                    Forgot your Mastermind Travels
                                    account password?

                                    <br>

                                    Enter your registered email address
                                    and we will send you a secure
                                    password reset link.

                                </p>

                            </div>



                            <!-- =====================================
                                 FORM
                            ====================================== -->

                            <form
                                method="POST"
                                action="{{ route('admin.password.email') }}"
                                aria-label="{{ __('Reset Password') }}"
                                enctype="multipart/form-data"
                                class="mt-4"
                            >

                                @csrf


                                <!-- =================================
                                     EMAIL
                                ================================== -->

                                <div class="input-group custom mb-3">

                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        autofocus
                                        required
                                        placeholder="Enter your registered email"
                                    >

                                    <div class="input-group-append custom">

                                        <span class="input-group-text">

                                            <i class="icon-copy dw dw-email"></i>

                                        </span>

                                    </div>


                                    @error('email')

                                        <span
                                            class="invalid-feedback"
                                            role="alert"
                                        >

                                            <strong>
                                                {{ $message }}
                                            </strong>

                                        </span>

                                    @enderror

                                </div>



                                <!-- =================================
                                     BUTTON
                                ================================== -->

                                <div class="row">

                                    <div class="col-sm-12">

                                        <button
                                            class="btn mastermind-btn w-100"
                                            type="submit"
                                        >

                                            <i class="dw dw-email mr-2"></i>

                                            {{ __('Send Password Reset Link') }}

                                        </button>


                                        <!-- =================================
                                             SECURITY NOTICE
                                        ================================== -->

                                        <div class="security-message">

                                            <p>

                                                <strong style="color:#0b2a5b;">
                                                    Security Notice:
                                                </strong>

                                                For your security, the
                                                password reset link will
                                                only be sent to the email
                                                address registered with your
                                                Mastermind Travels account.

                                            </p>

                                        </div>


                                        <!-- =================================
                                             NAVIGATION LINKS
                                        ================================== -->

                                        <div class="mt-4 text-center">

                                            <p class="mb-2">

                                                Remember your password?

                                                <a
                                                    href="{{ route('admin.login') }}"
                                                    class="mastermind-link"
                                                >

                                                    Sign In

                                                </a>

                                            </p>


                                            <p class="mb-0">

                                                Don't have an account?

                                                <a
                                                    href="{{ route('admin.register') }}"
                                                    class="mastermind-link"
                                                >

                                                    Create Account

                                                </a>

                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </form>


                            <!-- =====================================
                                 FOOTER
                            ====================================== -->

                            <div class="mastermind-footer">

                                <div>

                                    Mastermind Travels

                                </div>

                                <div>

                                    Car & Bus Rentals • Corporate Travel
                                    • Outstation • Airport Transfers

                                </div>

                                <div class="mt-1">

                                    © {{ date('Y') }}
                                    Mastermind Travels.
                                    All Rights Reserved.

                                </div>

                            </div>


                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->

    <script src="{{ asset('/backend/assets/vendors/scripts/core.js') }}"></script>

    <script src="{{ asset('/backend/assets/vendors/scripts/script.min.js') }}"></script>

    <script src="{{ asset('/backend/assets/vendors/scripts/process.js') }}"></script>

    <script src="{{ asset('/backend/assets/vendors/scripts/layout-settings.js') }}"></script>



    <!-- =========================================================
         TOASTR MESSAGES
    ========================================================== -->

    <script>

        @if(Session::has('message'))

            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };

            toastr.success(
                @json(session('message'))
            );

        @endif


        @if(Session::has('error'))

            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };

            toastr.error(
                @json(session('error'))
            );

        @endif


        @if(Session::has('info'))

            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };

            toastr.info(
                @json(session('info'))
            );

        @endif


        @if(Session::has('warning'))

            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };

            toastr.warning(
                @json(session('warning'))
            );

        @endif

    </script>


</body>

</html>