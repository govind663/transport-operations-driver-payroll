{{-- Basic Page Info --}}
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- SEO --}}
<meta name="description" content="TradeBO Admin Dashboard - Manage users, trading operations, memberships, reports, transactions, and system settings from a secure and powerful administration panel.">
<meta name="keywords" content="TradeBO, TradeBO Admin, Trading CRM, Trading Management System, Admin Dashboard, Membership Management, User Management, Reports, Trading Platform, TradeBO Software">
<meta name="author" content="PixelPearl Technologies LLP">
<meta name="robots" content="noindex, nofollow">

{{-- Canonical --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Title --}}
<title>@yield('title') | {{ config('app.name', 'TradeBO') }}</title>

{{-- CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Site favicon --}}
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/assets/favicon.ico') }}" />
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/assets/favicon.ico') }}" />
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/assets/favicon.ico') }}" />

{{-- CSS (CRITICAL FIRST) --}}
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/core.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/icon-font.min.css') }}">

{{-- NON-CRITICAL CSS --}}
<link rel="preload" href="{{ asset('backend/assets/vendors/styles/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/styles/style.css') }}">
</noscript>

{{-- Plugins CSS --}}
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/jquery-steps/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

{{-- DataTables CSS --}}
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">

{{-- GOOGLE FONTS (OPTIMIZED) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link 
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&family=Poppins:wght@400;500;600&display=swap"
    rel="stylesheet"
>

{{-- Toaster Message --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
