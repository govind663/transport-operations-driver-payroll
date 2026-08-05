<!doctype html>
<html lang="en-US">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    {{-- SEO --}}
    <meta name="description" content="Verify your email address to activate your TradeBO account and securely access the TradeBO Admin Portal, trading dashboard, memberships, reports, and account features.">
    <meta name="keywords" content="TradeBO Email Verification, Verify Email Address, TradeBO Account Verification, Email Confirmation, Secure Account Activation, Trading CRM, Admin Portal">
    <meta name="author" content="PixelPearl Technologies LLP">
    <meta name="robots" content="noindex, nofollow">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Responsive Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/assets/favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/assets/favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/assets/favicon.ico') }}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token">

    <title>{{ config('app.name', 'TradeBO') }} | Verify Your Email Address </title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        /* ==========================
        RESET
        ========================== */
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background: #f8f7f2;
            font-family: 'Inter', Arial, Helvetica, sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            color: #4b5563;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        img {
            border: 0;
            outline: none;
            display: block;
            max-width: 100%;
            height: auto;
        }

        /* ==========================
        LINKS
        ========================== */
        a {
            color: #023A85;
            text-decoration: none;
            transition: .3s;
        }

        a:hover {
            text-decoration: underline;
        }

        /* ==========================
        TYPOGRAPHY
        ========================== */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #023A85;
            font-weight: 700;
            line-height: 1.4;
            margin: 0;
        }

        p,
        td,
        span {
            color: #555555;
            font-size: 16px;
            line-height: 1.8;
        }

        /* ==========================
        EMAIL CONTAINER
        ========================== */
        .container {
            width: 100%;
            max-width: 650px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E8E3D6;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
        }

        /* ==========================
        HEADER
        ========================== */
        .header {
            background: linear-gradient(135deg, #FFF8E7, #F8F1E5);
            text-align: center;
            padding: 35px;
            border-bottom: 4px solid #023A85;
        }

        .header h1 {
            color: #023A85;
            font-size: 30px;
            margin-top: 15px;
            letter-spacing: .5px;
        }

        .header p {
            color: #6B7280;
            margin-top: 8px;
        }

        /* ==========================
        BODY
        ========================== */
        .content {
            padding: 40px;
        }

        /* ==========================
        BUTTON
        ========================== */
        .btn {
            display: inline-block;
            background: #023A85;
            color: #ffffff !important;
            padding: 15px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn:hover {
            background: #012b63;
            text-decoration: none;
        }

        /* ==========================
        FOOTER
        ========================== */
        .footer {
            background: #FAF7EF;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }

        .footer p {
            color: #6B7280;
            font-size: 14px;
            margin: 5px 0;
        }

        /* ==========================
        MOBILE
        ========================== */
        @media only screen and (max-width:600px) {

            .container {
                width: 100% !important;
                border-radius: 0;
            }

            .content {
                padding: 25px !important;
            }

            .header {
                padding: 25px !important;
            }

            h1 {
                font-size: 26px !important;
            }

            h2 {
                font-size: 22px !important;
            }

            p,
            td {
                font-size: 15px !important;
            }

            .btn {
                display: block;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Inter',Arial,sans-serif;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f7fb">
        <tr>
            <td align="center" style="padding:40px 15px;">

                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                    style="max-width:680px;margin:0 auto;">

                    <!-- Header -->
                    <tr>
                        <td align="center"
                            style="
                                padding:45px 30px;
                                background:linear-gradient(135deg,#FFF8E7 0%,#F8F1E5 100%);
                                border-radius:18px 18px 0 0;
                            ">

                            <img src="https://tradebo.net/wp-content/uploads/2026/01/tradebo-logo.png"
                                alt="TradeBO"
                                width="180" height="60"
                                style="margin:0 auto 15px;">

                        </td>
                    </tr>

                    <!-- Main Card -->
                    <tr>
                        <td
                            style="background:#ffffff;padding:50px 45px;box-shadow:0 10px 35px rgba(0,0,0,.08);">

                            <div style="text-align:center;">

                                <div style="font-size:60px;margin-bottom:20px;">
                                    🔐
                                </div>

                                <h2
                                    style="margin:0;font-size:30px;font-weight:700;color:#111827;">
                                    Reset Your Password
                                </h2>

                                <div
                                    style="width:80px;height:4px;background:#023A85;margin:22px auto;border-radius:10px;">
                                </div>

                            </div>

                            <p
                                style="margin:30px 0 0;color:#4B5563;font-size:17px;line-height:32px;text-align:justify;">

                                We received a request to reset the password associated
                                with your <strong>TradeBO</strong> account.

                                For your security, we never send passwords through
                                email.

                                Please click the button below to create a new password.
                                This password reset link will expire automatically after
                                a limited period.

                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:40px 0;">

                                        <a href="{{ route('admin.password.reset', ['token' => $token]) }}"
                                            style="
                                            display:inline-block;
                                            background:#023A85;
                                            color:#ffffff;
                                            padding:16px 38px;
                                            border-radius:8px;
                                            text-decoration:none;
                                            font-size:16px;
                                            font-weight:700;
                                            letter-spacing:.5px;">

                                            RESET PASSWORD

                                        </a>

                                    </td>
                                </tr>
                            </table>

                            <!-- Info Box -->

                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;">

                                <tr>
                                    <td style="padding:25px;">

                                        <h3
                                            style="margin:0 0 18px;color:#111827;font-size:20px;">
                                            🛡 Security Tips
                                        </h3>

                                        <ul
                                            style="margin:0;padding-left:20px;color:#475569;font-size:15px;line-height:30px;">

                                            <li>This password reset link expires automatically.</li>

                                            <li>Never share your password with anyone.</li>

                                            <li>TradeBO will never ask for your password via email.</li>

                                            <li>If you didn't request this password reset,
                                                simply ignore this email.</li>

                                        </ul>

                                    </td>
                                </tr>

                            </table>

                            <div
                                style="margin:35px 0;border-top:1px solid #E5E7EB;">
                            </div>

                            <p
                                style="margin:0;color:#64748B;font-size:15px;line-height:30px;text-align:center;">

                                Need help?

                                <br>

                                <a href="mailto:manager@pixelpearltechnologies.com"
                                    style="color:#023A85;text-decoration:none;">
                                    <b>
                                        manager@pixelpearltechnologies.com
                                    </b>
                                </a>

                                &nbsp;|&nbsp;

                                <a href="https://tradebo.net/membership-levels/"
                                    style="color:#023A85;text-decoration:none;">
                                    <b>
                                        TradeBO Memberships
                                    </b>
                                </a>

                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="
                                background:linear-gradient(135deg,#FFF8E7 0%,#F8F1E5 100%);
                                padding:40px 30px;
                                border-radius:0 0 18px 18px;
                            ">

                            <!-- Logo -->
                            <img src="https://tradebo.net/wp-content/uploads/2026/01/tradebo-logo.png"
                                alt="TradeBO"
                                width="180"
                                height="60"
                                style="margin:0 auto 20px;">

                            <!-- Company Name -->
                            <h3 style="
                                margin:0;
                                color:#023A85;
                                font-size:24px;
                                font-weight:700;
                                letter-spacing:.5px;
                            ">
                                Pixelpearl Technologies LLP
                            </h3>

                            <!-- Tagline -->
                            <p style="
                                margin:12px 0 20px;
                                color:#6B7280;
                                font-size:15px;
                                line-height:26px;
                            ">
                                Secure • Reliable • Smart Trading Platform
                            </p>

                            <!-- Copyright -->
                            <p style="
                                margin:0;
                                color:#6B7280;
                                font-size:14px;
                                line-height:24px;
                            ">
                                © 2025
                                <a href="{{ route('frontend.home') }}"
                                    style="
                                        color:#023A85;
                                        text-decoration:none;
                                        font-weight:700;
                                    ">
                                    TradeBO
                                </a>
                                . All Rights Reserved.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
