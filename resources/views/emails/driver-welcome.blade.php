<!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">

    <title>Welcome to Mastermind Travels</title>

    <style>
        /* =========================================================
           EMAIL FONT & RESET
        ========================================================= */

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background: #f4f7fb;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        a {
            text-decoration: none;
        }

        /* =========================================================
           HEART ANIMATION
        ========================================================= */

        .heart {
            color: #ff3b3b;
            display: inline-block;
            font-size: 17px;
            line-height: 17px;
            font-weight: bold;
            animation: heartbeat 1.2s infinite;
        }

        @keyframes heartbeat {

            0%,
            100% {
                transform: scale(1);
            }

            25% {
                transform: scale(1.25);
            }

            40% {
                transform: scale(1);
            }

            60% {
                transform: scale(1.20);
            }
        }

        /* =========================================================
           MOBILE RESPONSIVE
        ========================================================= */

        @media only screen and (max-width: 600px) {

            .email-wrapper {
                padding: 20px 10px !important;
            }

            .main-card {
                padding: 30px 22px !important;
            }

            .header-section {
                padding: 35px 20px !important;
            }

            .header-title {
                font-size: 25px !important;
                line-height: 34px !important;
            }

            .profile-section,
            .credentials-section,
            .responsibility-section,
            .about-section,
            .security-section {
                padding: 22px !important;
            }

            .profile-image-cell {
                display: block !important;
                width: 100% !important;
                padding: 0 0 20px !important;
            }

            .profile-details-cell {
                display: block !important;
                width: 100% !important;
                padding: 0 !important;
            }

            .credential-label,
            .credential-value {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box;
            }

            .credential-label {
                padding-bottom: 2px !important;
            }

            .credential-value {
                padding-bottom: 12px !important;
            }

            .footer-section {
                padding: 32px 20px 25px !important;
            }
        }
    </style>

</head>

<body>

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    bgcolor="#f4f7fb"
>
    <tr>

        <td
            align="center"
            class="email-wrapper"
            style="
                padding:40px 15px;
                background:#f4f7fb;
            "
        >

            <!-- =====================================================
                 MAIN EMAIL CONTAINER
            ====================================================== -->

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                    max-width:680px;
                    margin:0 auto;
                "
            >

                <!-- =================================================
                     HEADER
                ================================================== -->

                <tr>

                    <td
                        align="center"
                        class="header-section"
                        style="
                            padding:45px 30px;
                            background:#0b2a5b;
                            border-radius:18px 18px 0 0;
                        "
                    >

                        <img
                            src="https://mastermindtravels.net/wp-content/uploads/2025/07/logo-300x88.png"
                            alt="Mastermind Travels"
                            width="190"
                            style="
                                width:190px;
                                max-width:190px;
                                height:auto;
                                display:block;
                                margin:0 auto 20px;
                            "
                        >

                        <h1
                            class="header-title"
                            style="
                                margin:0;
                                color:#ffffff;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:30px;
                                line-height:40px;
                                font-weight:700;
                            "
                        >
                            Welcome to Mastermind Travels
                        </h1>

                        <p
                            style="
                                margin:12px 0 0;
                                color:#dbe7f5;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:16px;
                                line-height:26px;
                            "
                        >
                            Professional Travel &amp; Transportation Services
                        </p>

                    </td>

                </tr>


                <!-- =================================================
                     MAIN CARD
                ================================================== -->

                <tr>

                    <td
                        class="main-card"
                        style="
                            background:#ffffff;
                            padding:50px 45px;
                            box-shadow:0 10px 35px rgba(0,0,0,.08);
                        "
                    >

                        <!-- =================================================
                             WELCOME
                        ================================================== -->

                        <div style="text-align:center;">

                            <div
                                style="
                                    font-size:52px;
                                    line-height:60px;
                                    margin-bottom:15px;
                                "
                            >
                                👋
                            </div>

                            <h2
                                style="
                                    margin:0;
                                    color:#111827;
                                    font-family:Arial, Helvetica, sans-serif;
                                    font-size:29px;
                                    line-height:38px;
                                    font-weight:700;
                                "
                            >
                                Welcome,
                                {{ $driver->first_name }}

                                @if(!empty($driver->last_name))
                                    {{ $driver->last_name }}
                                @endif
                            </h2>

                            <div
                                style="
                                    width:80px;
                                    height:4px;
                                    background:#c58a24;
                                    margin:22px auto;
                                    border-radius:10px;
                                "
                            ></div>

                        </div>


                        <!-- =================================================
                             INTRODUCTION
                        ================================================== -->

                        <p
                            style="
                                margin:30px 0 0;
                                color:#475569;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:15px;
                                line-height:29px;
                                text-align:justify;
                            "
                        >

                            Dear
                            <strong style="color:#111827;">
                                {{ $driver->first_name }}

                                @if(!empty($driver->last_name))
                                    {{ $driver->last_name }}
                                @endif
                            </strong>,

                            <br><br>

                            Welcome to
                            <strong style="color:#0b2a5b;">
                                Mastermind Travels
                            </strong>.

                            We are pleased to inform you that your
                            <strong>{{ $role }}</strong>
                            account has been successfully created in our
                            Driver Management Portal.

                            <br><br>

                            You can now securely access the portal using
                            the login credentials provided below.

                            <br><br>

                            As a member of the Mastermind Travels team,
                            you are expected to maintain professionalism,
                            punctuality, safety and excellent customer
                            service while performing your assigned duties.

                        </p>


                        <!-- =================================================
                             DRIVER PROFILE
                        ================================================== -->

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            class="profile-section"
                            style="
                                margin-top:35px;
                                background:#f8fafc;
                                border:1px solid #e2e8f0;
                                border-radius:12px;
                            "
                        >

                            <tr>

                                <td style="padding:28px;">

                                    <h3
                                        style="
                                            margin:0 0 25px;
                                            color:#111827;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:21px;
                                            line-height:29px;
                                            font-weight:700;
                                        "
                                    >
                                        👤 Your Profile
                                    </h3>


                                    <table
                                        width="100%"
                                        cellpadding="0"
                                        cellspacing="0"
                                        border="0"
                                    >

                                        <tr>

                                            <!-- PROFILE IMAGE -->

                                            <td
                                                width="125"
                                                valign="top"
                                                align="center"
                                                class="profile-image-cell"
                                                style="padding-right:20px;"
                                            >

                                                @if(!empty($driver->driver_photo))

                                                    <img
                                                        src="{{ asset('storage/' . $driver->driver_photo) }}"
                                                        alt="Driver Photo"
                                                        width="100"
                                                        height="100"
                                                        style="
                                                            width:100px;
                                                            height:100px;
                                                            object-fit:cover;
                                                            border-radius:50%;
                                                            border:4px solid #ffffff;
                                                            box-shadow:0 3px 12px rgba(0,0,0,.12);
                                                        "
                                                    >

                                                @else

                                                    <div
                                                        style="
                                                            width:100px;
                                                            height:100px;
                                                            line-height:100px;
                                                            background:#e8edf5;
                                                            color:#0b2a5b;
                                                            border-radius:50%;
                                                            font-size:35px;
                                                            font-family:Arial, Helvetica, sans-serif;
                                                            font-weight:bold;
                                                            text-align:center;
                                                        "
                                                    >
                                                        {{ strtoupper(substr($driver->first_name ?? 'D', 0, 1)) }}
                                                    </div>

                                                @endif

                                            </td>


                                            <!-- DRIVER DETAILS -->

                                            <td
                                                valign="top"
                                                class="profile-details-cell"
                                                style="padding-left:5px;"
                                            >

                                                <h3
                                                    style="
                                                        margin:0 0 12px;
                                                        color:#111827;
                                                        font-family:Arial, Helvetica, sans-serif;
                                                        font-size:19px;
                                                        line-height:27px;
                                                        font-weight:700;
                                                    "
                                                >

                                                    {{ $driver->first_name }}

                                                    @if(!empty($driver->last_name))
                                                        {{ $driver->last_name }}
                                                    @endif

                                                </h3>


                                                <p
                                                    style="
                                                        margin:6px 0;
                                                        color:#667085;
                                                        font-family:Arial, Helvetica, sans-serif;
                                                        font-size:14px;
                                                        line-height:23px;
                                                    "
                                                >
                                                    <strong style="color:#0b2a5b;">
                                                        Role:
                                                    </strong>
                                                    {{ $role }}
                                                </p>


                                                @if(!empty($driver->driver_code))

                                                    <p
                                                        style="
                                                            margin:6px 0;
                                                            color:#667085;
                                                            font-family:Arial, Helvetica, sans-serif;
                                                            font-size:14px;
                                                            line-height:23px;
                                                        "
                                                    >
                                                        <strong style="color:#0b2a5b;">
                                                            Driver Code:
                                                        </strong>
                                                        {{ $driver->driver_code }}
                                                    </p>

                                                @endif


                                                @if(!empty($driver->mobile))

                                                    <p
                                                        style="
                                                            margin:6px 0;
                                                            color:#667085;
                                                            font-family:Arial, Helvetica, sans-serif;
                                                            font-size:14px;
                                                            line-height:23px;
                                                        "
                                                    >
                                                        <strong style="color:#0b2a5b;">
                                                            Mobile:
                                                        </strong>
                                                        {{ $driver->mobile }}
                                                    </p>

                                                @endif


                                                @if(!empty($driver->email))

                                                    <p
                                                        style="
                                                            margin:6px 0;
                                                            color:#667085;
                                                            font-family:Arial, Helvetica, sans-serif;
                                                            font-size:14px;
                                                            line-height:23px;
                                                        "
                                                    >
                                                        <strong style="color:#0b2a5b;">
                                                            Email:
                                                        </strong>
                                                        {{ $driver->email }}
                                                    </p>

                                                @endif


                                                @if(!empty($driver->license_number))

                                                    <p
                                                        style="
                                                            margin:6px 0;
                                                            color:#667085;
                                                            font-family:Arial, Helvetica, sans-serif;
                                                            font-size:14px;
                                                            line-height:23px;
                                                        "
                                                    >
                                                        <strong style="color:#0b2a5b;">
                                                            Driving Licence:
                                                        </strong>
                                                        {{ $driver->license_number }}
                                                    </p>

                                                @endif

                                            </td>

                                        </tr>

                                    </table>

                                </td>

                            </tr>

                        </table>


                        <!-- =================================================
                             LOGIN CREDENTIALS
                        ================================================== -->

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            class="credentials-section"
                            style="
                                margin-top:30px;
                                background:#fff8e7;
                                border:1px solid #f4d38b;
                                border-radius:12px;
                            "
                        >

                            <tr>

                                <td style="padding:28px;">

                                    <h3
                                        style="
                                            margin:0 0 20px;
                                            color:#111827;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:21px;
                                            line-height:29px;
                                            font-weight:700;
                                        "
                                    >
                                        🔐 Your Login Credentials
                                    </h3>


                                    <table
                                        width="100%"
                                        cellpadding="8"
                                        cellspacing="0"
                                        border="0"
                                    >

                                        <tr>

                                            <td
                                                width="38%"
                                                valign="top"
                                                class="credential-label"
                                                style="
                                                    color:#111827;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:22px;
                                                "
                                            >
                                                <strong style="color:#0b2a5b;">
                                                    Login Email
                                                </strong>
                                            </td>

                                            <td
                                                valign="top"
                                                class="credential-value"
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:22px;
                                                    word-break:break-word;
                                                "
                                            >
                                                {{ $loginEmail }}
                                            </td>

                                        </tr>


                                        <tr>

                                            <td
                                                valign="top"
                                                class="credential-label"
                                                style="
                                                    color:#111827;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:22px;
                                                "
                                            >
                                                <strong style="color:#0b2a5b;">
                                                    Temporary Password
                                                </strong>
                                            </td>

                                            <td
                                                valign="top"
                                                class="credential-value"
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:22px;
                                                "
                                            >

                                                <span
                                                    style="
                                                        display:inline-block;
                                                        background:#ffffff;
                                                        padding:8px 14px;
                                                        border-radius:6px;
                                                        border:1px dashed #0b2a5b;
                                                        font-weight:700;
                                                        letter-spacing:1px;
                                                        color:#111827;
                                                    "
                                                >
                                                    {{ $temporaryPassword }}
                                                </span>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td
                                                valign="top"
                                                class="credential-label"
                                                style="
                                                    color:#111827;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:22px;
                                                "
                                            >
                                                <strong style="color:#0b2a5b;">
                                                    Account Role
                                                </strong>
                                            </td>

                                            <td
                                                valign="top"
                                                class="credential-value"
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:22px;
                                                "
                                            >
                                                {{ $role }}
                                            </td>

                                        </tr>

                                    </table>


                                    <p
                                        style="
                                            margin:20px 0 0;
                                            color:#92400e;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:13px;
                                            line-height:25px;
                                        "
                                    >

                                        <strong>Important:</strong>

                                        This is a temporary password.
                                        Please change your password after
                                        your first successful login.

                                        Never share your password with
                                        anyone.

                                    </p>

                                </td>

                            </tr>

                        </table>


                        <!-- =================================================
                             LOGIN BUTTON
                        ================================================== -->

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                        >

                            <tr>

                                <td
                                    align="center"
                                    style="padding:40px 0 20px;"
                                >

                                    <a
                                        href="{{ $loginUrl }}"
                                        target="_blank"
                                        style="
                                            display:inline-block;
                                            background:#0b2a5b;
                                            color:#ffffff;
                                            padding:16px 40px;
                                            border-radius:8px;
                                            text-decoration:none;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:15px;
                                            line-height:20px;
                                            font-weight:700;
                                            letter-spacing:.3px;
                                        "
                                    >
                                        ACCESS DRIVER PORTAL
                                    </a>

                                </td>

                            </tr>

                        </table>


                        <!-- =================================================
                             DRIVER RESPONSIBILITIES
                        ================================================== -->

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            class="responsibility-section"
                            style="
                                margin-top:20px;
                                background:#f8fafc;
                                border:1px solid #e2e8f0;
                                border-radius:12px;
                            "
                        >

                            <tr>

                                <td style="padding:30px;">

                                    <h3
                                        style="
                                            margin:0 0 20px;
                                            color:#111827;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:21px;
                                            line-height:29px;
                                            font-weight:700;
                                        "
                                    >
                                        🚘 Your Role at Mastermind Travels
                                    </h3>


                                    <table
                                        width="100%"
                                        cellpadding="6"
                                        cellspacing="0"
                                        border="0"
                                    >

                                        <tr>
                                            <td
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:24px;
                                                "
                                            >
                                                ✅ Provide safe and professional transportation services
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:24px;
                                                "
                                            >
                                                ✅ Maintain punctuality for assigned trips and bookings
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:24px;
                                                "
                                            >
                                                ✅ Maintain the assigned vehicle in clean and good condition
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:24px;
                                                "
                                            >
                                                ✅ Follow company transportation and safety guidelines
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:24px;
                                                "
                                            >
                                                ✅ Provide professional and respectful customer service
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:24px;
                                                "
                                            >
                                                ✅ Keep your driver profile and required documents updated
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                style="
                                                    color:#475569;
                                                    font-family:Arial, Helvetica, sans-serif;
                                                    font-size:14px;
                                                    line-height:24px;
                                                "
                                            >
                                                ✅ Use the Driver Portal for assigned operational activities
                                            </td>
                                        </tr>

                                    </table>

                                </td>

                            </tr>

                        </table>


                        <!-- =================================================
                             ABOUT MASTERMIND TRAVELS
                        ================================================== -->

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            class="about-section"
                            style="
                                margin-top:30px;
                                background:#ffffff;
                                border:1px solid #e2e8f0;
                                border-radius:12px;
                            "
                        >

                            <tr>

                                <td style="padding:30px;">

                                    <h3
                                        style="
                                            margin:0 0 20px;
                                            color:#111827;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:21px;
                                            line-height:29px;
                                            font-weight:700;
                                        "
                                    >
                                        🚌 About Mastermind Travels
                                    </h3>


                                    <p
                                        style="
                                            margin:0;
                                            color:#475569;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:14px;
                                            line-height:27px;
                                            text-align:justify;
                                        "
                                    >

                                        Mastermind Travels provides
                                        professional car and bus rental
                                        solutions for corporate and
                                        individual travel requirements.

                                        Our services include local travel,
                                        outstation trips, airport pickup and
                                        drop, group transportation and
                                        corporate travel solutions.

                                    </p>


                                    <p
                                        style="
                                            margin:20px 0 0;
                                            color:#475569;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:14px;
                                            line-height:27px;
                                            text-align:justify;
                                        "
                                    >

                                        As part of the Mastermind Travels
                                        team, your professionalism,
                                        punctuality, safety and customer
                                        service are important to maintaining
                                        the trust of our customers and
                                        corporate partners.

                                    </p>

                                </td>

                            </tr>

                        </table>


                        <!-- =================================================
                             SECURITY
                        ================================================== -->

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            class="security-section"
                            style="
                                margin-top:30px;
                                background:#fff8e7;
                                border:1px solid #f4d38b;
                                border-radius:12px;
                            "
                        >

                            <tr>

                                <td style="padding:30px;">

                                    <h3
                                        style="
                                            margin:0 0 18px;
                                            color:#111827;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:20px;
                                            line-height:28px;
                                            font-weight:700;
                                        "
                                    >
                                        🔒 Security Recommendations
                                    </h3>


                                    <ul
                                        style="
                                            margin:0;
                                            padding-left:20px;
                                            color:#475569;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:14px;
                                            line-height:29px;
                                        "
                                    >

                                        <li>
                                            Change your temporary password immediately after your first login.
                                        </li>

                                        <li>
                                            Never share your username or password with anyone.
                                        </li>

                                        <li>
                                            Always log out after using the portal on shared devices.
                                        </li>

                                        <li>
                                            Keep your personal and driver information accurate and up to date.
                                        </li>

                                        <li>
                                            Contact the Mastermind Travels administration team if you notice suspicious account activity.
                                        </li>

                                    </ul>

                                </td>

                            </tr>

                        </table>


                        <!-- =================================================
                             CLOSING
                        ================================================== -->

                        <div
                            style="
                                margin:40px 0;
                                border-top:1px solid #e5e7eb;
                                height:1px;
                                line-height:1px;
                            "
                        >
                            &nbsp;
                        </div>


                        <p
                            style="
                                margin:0;
                                color:#475569;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:14px;
                                line-height:29px;
                                text-align:justify;
                            "
                        >

                            We are pleased to have you as a part of
                            <strong style="color:#0b2a5b;">
                                Mastermind Travels
                            </strong>.

                            Our mission is to provide customers with
                            dependable transportation, outstanding service
                            and comfortable travel experiences at
                            competitive rates.

                            We look forward to your contribution in
                            delivering safe, timely and professional
                            transportation services.

                        </p>


                        <!-- =================================================
                             SUPPORT
                        ================================================== -->

                        <div
                            style="
                                margin:40px 0;
                                border-top:1px solid #e5e7eb;
                                height:1px;
                                line-height:1px;
                            "
                        >
                            &nbsp;
                        </div>


                        <p
                            style="
                                margin:0;
                                color:#64748b;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:13px;
                                line-height:27px;
                                text-align:center;
                            "
                        >

                            Need assistance with your account?

                            <br><br>

                            📞

                            <a
                                href="tel:+919833974458"
                                style="
                                    color:#0b2a5b;
                                    text-decoration:none;
                                    font-weight:700;
                                "
                            >
                                +91 9833974458
                            </a>

                            &nbsp; | &nbsp;

                            <a
                                href="tel:+917045599517"
                                style="
                                    color:#0b2a5b;
                                    text-decoration:none;
                                    font-weight:700;
                                "
                            >
                                +91 7045599517
                            </a>

                            <br>

                            📧

                            <a
                                href="mailto:mastermindtravels25@gmail.com"
                                style="
                                    color:#0b2a5b;
                                    text-decoration:none;
                                    font-weight:700;
                                "
                            >
                                mastermindtravels25@gmail.com
                            </a>

                            <br>

                            We are available 24×7 to assist you.

                        </p>

                    </td>

                </tr>


                <!-- =========================================================
                     CORPORATE FOOTER
                ========================================================== -->

                <tr>

                    <td
                        align="center"
                        class="footer-section"
                        style="
                            background:#0b2a5b;
                            padding:38px 30px 30px;
                            border-radius:0 0 18px 18px;
                        "
                    >

                        <!-- FOOTER LOGO -->

                        <img
                            src="https://mastermindtravels.net/wp-content/uploads/2025/07/logo-300x88.png"
                            alt="Mastermind Travels"
                            width="175"
                            style="
                                width:175px;
                                max-width:175px;
                                height:auto;
                                display:block;
                                margin:0 auto 18px;
                            "
                        >


                        <!-- COMPANY NAME -->

                        <div
                            style="
                                color:#ffffff;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:22px;
                                line-height:30px;
                                font-weight:700;
                                letter-spacing:.2px;
                            "
                        >
                            Mastermind Travels
                        </div>


                        <!-- TAGLINE -->

                        <div
                            style="
                                margin-top:7px;
                                color:#cbd8e8;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:13px;
                                line-height:22px;
                            "
                        >
                            Car &amp; Bus Rentals
                            &nbsp;•&nbsp;
                            Corporate Travel
                            &nbsp;•&nbsp;
                            Airport Transfers
                            &nbsp;•&nbsp;
                            Outstation
                        </div>


                        <!-- FOOTER DIVIDER -->

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="margin:25px 0 22px;"
                        >

                            <tr>

                                <td
                                    style="
                                        border-top:1px solid rgba(255,255,255,.18);
                                        font-size:1px;
                                        line-height:1px;
                                    "
                                >
                                    &nbsp;
                                </td>

                            </tr>

                        </table>


                        <!-- COPYRIGHT -->

                        <p
                            style="
                                margin:0;
                                color:#dbe5f1;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:12px;
                                line-height:22px;
                                text-align:center;
                            "
                        >

                            Copyright &copy; {{ date('Y') }}

                            <strong style="color:#ffffff;">
                                Mastermind Travels
                            </strong>.

                            All Rights Reserved.

                        </p>


                        <!-- DESIGNED & DEVELOPED -->

                        <p
                            style="
                                margin:9px 0 0;
                                color:#aebed3;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:12px;
                                line-height:22px;
                                text-align:center;
                            "
                        >

                            Designed &amp; Developed with

                            <span
                                class="heart"
                                style="
                                    color:#ff3b3b;
                                    display:inline-block;
                                    font-size:17px;
                                    line-height:17px;
                                    font-weight:bold;
                                    margin:0 4px;
                                "
                            >
                                ♥
                            </span>

                            by

                            <strong
                                style="
                                    color:#ffffff;
                                    font-weight:700;
                                "
                            >
                                PixelPearl Technologies LLP
                            </strong>

                        </p>


                        <!-- WEBSITE -->

                        <p
                            style="
                                margin:14px 0 0;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:11px;
                                line-height:20px;
                                text-align:center;
                            "
                        >

                            <a
                                href="https://mastermindtravels.net/"
                                target="_blank"
                                style="
                                    color:#dbe5f1;
                                    text-decoration:none;
                                    font-weight:600;
                                "
                            >
                                mastermindtravels.net
                            </a>

                        </p>


                        <!-- ADDRESS -->

                        <p
                            style="
                                margin:12px 0 0;
                                color:#8fa4bd;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:11px;
                                line-height:19px;
                                text-align:center;
                            "
                        >

                            OM Niwas, Plot No. 396, Sector 4,
                            Ghansoli, Navi Mumbai – 400 701

                        </p>


                        <!-- FINAL COPYRIGHT LINE -->

                        <p
                            style="
                                margin:18px 0 0;
                                color:#71859e;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:10px;
                                line-height:18px;
                                text-align:center;
                            "
                        >
                            Professional Travel &amp; Transportation Services
                        </p>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

</body>
</html>