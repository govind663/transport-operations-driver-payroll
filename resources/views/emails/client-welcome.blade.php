<!doctype html>
<html lang="en-US">

<head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- SEO -->
    <meta name="description" content="Welcome to TradeBO. Your client account has been successfully created.">
    <meta name="keywords" content="TradeBO, Welcome Email, Client Registration, CRM, Business Management">
    <meta name="author" content="PixelPearl Technologies LLP">
    <meta name="robots" content="noindex,nofollow">

    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('backend/assets/favicon.ico') }}">

    <link rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{ asset('backend/assets/favicon.ico') }}">

    <link rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('backend/assets/favicon.ico') }}">

    <title>
        Welcome to TradeBO
    </title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

</head>

<body
    style="
        margin:0;
        padding:0;
        background:#f4f7fb;
        font-family:'Inter',Arial,sans-serif;
    ">

    <table
        width="100%"
        cellpadding="0"
        cellspacing="0"
        bgcolor="#f4f7fb">

    <tr>

    <td
        align="center"
        style="padding:40px 15px;">

    <table
        width="100%"
        cellpadding="0"
        cellspacing="0"
        style="
            max-width:680px;
            margin:0 auto;
        ">

    <!-- ==========================================================
    HEADER
    =========================================================== -->

    <tr>

    <td
        align="center"
        style="
            padding:45px 30px;
            background:linear-gradient(135deg,#FFF8E7 0%,#F8F1E5 100%);
            border-radius:18px 18px 0 0;
        ">

    <img
        src="https://tradebo.net/wp-content/uploads/2026/01/tradebo-logo.png"
        alt="TradeBO"
        width="180"
        style="margin:0 auto 18px;">

    <h2
        style="
            margin:0;
            color:#023A85;
            font-size:30px;
            font-weight:700;
        ">

    Welcome To TradeBO

    </h2>

    <p
        style="
            margin-top:12px;
            color:#6B7280;
            font-size:16px;
        ">

    Business Growth Starts Here

    </p>

    </td>

    </tr>

    <!-- ==========================================================
    MAIN CARD START
    =========================================================== -->
    <tr>

    <td
        style="
            background:#ffffff;
            padding:50px 45px;
            box-shadow:0 10px 35px rgba(0,0,0,.08);
        ">

    <div
        style="text-align:center;">

    <div
        style="
            font-size:65px;
            margin-bottom:20px;
        ">

    🎉

    </div>

    <h2
        style="
            margin:0;
            font-size:30px;
            color:#111827;
            font-weight:700;
        ">

    Welcome, {{ $client->name }}

    </h2>

    <div
        style="
            width:80px;
            height:4px;
            background:#023A85;
            margin:22px auto;
            border-radius:10px;
        ">
    </div>

    </div>

    <p
        style="
            margin-top:30px;
            color:#4B5563;
            font-size:17px;
            line-height:32px;
            text-align:justify;
        ">

    Dear
    <strong>{{ $client->name }}</strong>,

    <br><br>

    Congratulations and welcome to
    <strong>TradeBO</strong>!

    Your client account has been created successfully by our administration team.

    You can now securely access your TradeBO dashboard and start managing your business activities, communication, documents, reports and many other CRM features from one place.

    </p>

    <!-- ==========================================================
    CLIENT ACCOUNT INFORMATION
    =========================================================== -->
    <table width="100%" cellpadding="0" cellspacing="0"
        style="
            margin-top:35px;
            background:#F8FAFC;
            border:1px solid #E2E8F0;
            border-radius:12px;
        ">

        <tr>
            <td style="padding:28px;">

                <h3 style="
                    margin:0 0 20px;
                    color:#111827;
                    font-size:22px;
                    font-weight:700;
                ">
                    👤 Your Account Information
                </h3>

                <table width="100%" cellpadding="8" cellspacing="0">

                    <tr>
                        <td width="40%">
                            <strong style="color:#023A85;">
                                Client Code
                            </strong>
                        </td>

                        <td>
                            {{ $client->client_code }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong style="color:#023A85;">
                                Owner Name
                            </strong>
                        </td>

                        <td>
                            {{ $client->name }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong style="color:#023A85;">
                                Business Name
                            </strong>
                        </td>

                        <td>
                            {{ $client->business_name }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong style="color:#023A85;">
                                Client Type
                            </strong>
                        </td>

                        <td>
                            {{ ucfirst($client->client_type) }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong style="color:#023A85;">
                                Registered Email
                            </strong>
                        </td>

                        <td>
                            {{ $client->email }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong style="color:#023A85;">
                                Mobile Number
                            </strong>
                        </td>

                        <td>
                            {{ $client->phone }}
                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

    <!-- ==========================================================
    LOGIN CREDENTIALS
    =========================================================== -->
    <table width="100%" cellpadding="0" cellspacing="0"
        style="
            margin-top:30px;
            background:#FFF8E7;
            border:1px solid #F4D38B;
            border-radius:12px;
        ">

        <tr>

            <td style="padding:28px;">

                <h3 style="
                    margin:0 0 20px;
                    color:#111827;
                    font-size:22px;
                    font-weight:700;
                ">
                    🔐 Login Credentials
                </h3>

                <table width="100%" cellpadding="8" cellspacing="0">

                    <tr>

                        <td width="35%">
                            <strong style="color:#023A85;">
                                Username
                            </strong>
                        </td>

                        <td>
                            {{ $client->username }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong style="color:#023A85;">
                                Temporary Password
                            </strong>
                        </td>

                        <td>

                            <span style="
                                background:#ffffff;
                                padding:8px 14px;
                                border-radius:6px;
                                border:1px dashed #023A85;
                                font-weight:700;
                                letter-spacing:1px;
                                color:#111827;
                            ">
                                {{ $password }}
                            </span>

                        </td>

                    </tr>

                </table>

                <p style="
                    margin-top:18px;
                    color:#92400E;
                    font-size:15px;
                    line-height:28px;
                ">

                    <strong>Important:</strong>

                    Please keep your login credentials confidential.
                    For your account security, we strongly recommend changing your password immediately after your first successful login.

                </p>

            </td>

        </tr>

    </table>

    <!-- ==========================================================
    LOGIN BUTTON
    =========================================================== -->
    <table width="100%" cellpadding="0" cellspacing="0">

        <tr>

            <td align="center" style="padding:40px 0 20px;">

                <a href="{{ $loginUrl }}"
                    style="
                        display:inline-block;
                        background:#023A85;
                        color:#ffffff;
                        padding:16px 40px;
                        border-radius:8px;
                        text-decoration:none;
                        font-size:16px;
                        font-weight:700;
                        letter-spacing:.5px;
                    ">

                    LOGIN TO TRADEBO

                </a>

            </td>

        </tr>

    </table>


    <!-- ==========================================================
    WHAT YOU CAN DO WITH TRADEBO
    =========================================================== -->
    <table width="100%" cellpadding="0" cellspacing="0"
        style="
            margin-top:20px;
            background:#F8FAFC;
            border:1px solid #E2E8F0;
            border-radius:12px;
        ">

        <tr>

            <td style="padding:30px;">

                <h3 style="
                    margin:0 0 20px;
                    color:#111827;
                    font-size:22px;
                    font-weight:700;
                ">
                    🚀 What You Can Do with TradeBO
                </h3>

                <table width="100%" cellpadding="6" cellspacing="0">

                    <tr>
                        <td>✅ Manage your complete business profile</td>
                    </tr>

                    <tr>
                        <td>✅ Upload and manage business documents securely</td>
                    </tr>

                    <tr>
                        <td>✅ Track communication, follow-ups and activities</td>
                    </tr>

                    <tr>
                        <td>✅ Receive important notifications and updates</td>
                    </tr>

                    <tr>
                        <td>✅ Access your dashboard anytime from anywhere</td>
                    </tr>

                    <tr>
                        <td>✅ Securely manage your TradeBO account</td>
                    </tr>

                </table>

            </td>

        </tr>

    </table>


    <!-- ==========================================================
    SECURITY NOTICE
    =========================================================== -->
    <table width="100%" cellpadding="0" cellspacing="0"
        style="
            margin-top:30px;
            background:#FFF8E7;
            border:1px solid #F4D38B;
            border-radius:12px;
        ">

        <tr>

            <td style="padding:30px;">

                <h3 style="
                    margin:0 0 18px;
                    color:#111827;
                    font-size:20px;
                    font-weight:700;
                ">
                    🔒 Security Recommendations
                </h3>

                <ul style="
                    margin:0;
                    padding-left:20px;
                    color:#475569;
                    font-size:15px;
                    line-height:30px;
                ">

                    <li>Change your temporary password immediately after your first login.</li>

                    <li>Never share your username or password with anyone.</li>

                    <li>Always log out after using your account on shared devices.</li>

                    <li>TradeBO will never ask for your password via email, phone or WhatsApp.</li>

                    <li>Contact our support team immediately if you notice any suspicious activity.</li>

                </ul>

            </td>

        </tr>

    </table>


    <div style="margin:40px 0;border-top:1px solid #E5E7EB;"></div>

    <p style="
        margin:0;
        color:#4B5563;
        font-size:16px;
        line-height:30px;
        text-align:justify;
    ">

    We sincerely thank you for choosing
    <strong>TradeBO</strong> as your trusted business management platform.

    Our mission is to simplify your daily operations, improve productivity, and provide a secure, reliable, and efficient digital experience.

    We look forward to supporting your business growth.

    </p>

    <!-- ==========================================================
    SUPPORT SECTION
    =========================================================== -->

    <div style="margin:40px 0;border-top:1px solid #E5E7EB;"></div>

    <p
        style="
            margin:0;
            color:#64748B;
            font-size:15px;
            line-height:30px;
            text-align:center;
        ">

        Need assistance with your account?

        <br><br>

        📧
        <a href="mailto:manager@pixelpearltechnologies.com"
            style="
                color:#023A85;
                text-decoration:none;
                font-weight:700;
            ">
            manager@pixelpearltechnologies.com
        </a>

        <br>

        🌐
        <a href="https://tradebo.net"
            style="
                color:#023A85;
                text-decoration:none;
                font-weight:700;
            ">
            www.tradebo.net
        </a>

        <br>

        We are always happy to help you.

    </p>

    </td>
    </tr>

    <!-- ==========================================================
    FOOTER
    =========================================================== -->
    <tr>

    <td
        align="center"
        style="
            background:linear-gradient(135deg,#FFF8E7 0%,#F8F1E5 100%);
            padding:40px 30px;
            border-radius:0 0 18px 18px;
        ">

        <!-- Logo -->
        <img
            src="https://tradebo.net/wp-content/uploads/2026/01/tradebo-logo.png"
            alt="TradeBO"
            width="180"
            style="margin:0 auto 20px;">

        <!-- Company -->
        <h3
            style="
                margin:0;
                color:#023A85;
                font-size:24px;
                font-weight:700;
            ">
            TradeBO
        </h3>

        <p
            style="
                margin:12px 0;
                color:#6B7280;
                font-size:15px;
                line-height:28px;
            ">

            Smart CRM • Business Growth • Secure Platform

        </p>

        <p
            style="
                margin:0;
                color:#6B7280;
                font-size:14px;
                line-height:28px;
            ">

            Thank you for choosing
            <strong>TradeBO</strong>.

            <br>

            We look forward to building a long-term business relationship with you.

        </p>

        <div
            style="
                margin:25px 0;
                border-top:1px solid #D1D5DB;
            ">
        </div>

        <p
            style="
                margin:0;
                color:#9CA3AF;
                font-size:13px;
                line-height:24px;
            ">

            © {{ date('Y') }}

            <strong>TradeBO</strong>

            |

            Powered by
            <strong>PixelPearl Technologies LLP</strong>

            <br>

            All Rights Reserved.

        </p>

    </td>

    </tr>

    </table>

    </td>

    </tr>

    </table>

    </body>

    </html>