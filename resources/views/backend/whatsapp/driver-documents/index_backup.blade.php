@extends('backend.layouts.master')

@section('title')
    Driver Document Dispatch
@endsection


@push('styles')

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* =====================================================
           01. MAIN LAYOUT
        ===================================================== */

        .dispatch-page {
            --dispatch-blue: #2563eb;
            --dispatch-dark: #172554;
            --dispatch-muted: #64748b;
            --dispatch-border: #e2e8f0;
            --dispatch-green: #16a34a;
            color: #1e293b;
        }

        .dispatch-page .page-header {
            margin-bottom: 22px;
        }

        .dispatch-page .page-header h4 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.4px;
            margin-bottom: 6px;
        }

        .dispatch-page .page-header p {
            color: var(--dispatch-muted);
            font-size: 13px;
        }

        .dispatch-page .page-header .btn {
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(37, 99, 235, .15);
        }


        /* =====================================================
           02. INFORMATION BANNER
        ===================================================== */

        .dispatch-page .dispatch-info {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px 18px;
            border: 1px solid #bfdbfe;
            border-left: 4px solid var(--dispatch-blue);
            border-radius: 10px;
            background: linear-gradient(110deg, #eff6ff, #f8fbff);
            color: #1e40af;
            margin-bottom: 22px;
            font-size: 13px;
            line-height: 1.6;
        }

        .dispatch-page .dispatch-info-icon {
            font-size: 19px;
            margin-top: 1px;
            flex: 0 0 auto;
        }


        /* =====================================================
           03. COMMON CARDS
        ===================================================== */

        .dispatch-page .filter-card,
        .dispatch-page .driver-summary-card,
        .dispatch-page .dispatch-card,
        .dispatch-page .bottom-info-card {
            background: #fff;
            border: 1px solid var(--dispatch-border);
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, .045);
        }

        .dispatch-page .filter-card,
        .dispatch-page .driver-summary-card,
        .dispatch-page .bottom-info-card {
            margin-bottom: 22px;
        }

        .dispatch-page .card-body {
            padding: 22px;
        }

        .dispatch-page .section-heading {
            font-size: 16px;
            font-weight: 750;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .dispatch-page .section-subtitle {
            color: var(--dispatch-muted);
            font-size: 12px;
            margin-bottom: 0;
        }


        /* =====================================================
           04. FILTER SECTION
        ===================================================== */

        .dispatch-page .filter-heading-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            border-radius: 9px;
            background: #eff6ff;
            color: var(--dispatch-blue);
            margin-right: 9px;
            font-size: 17px;
        }

        .dispatch-page .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
        }

        .dispatch-page .form-control {
            min-height: 42px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #334155;
            font-size: 13px;
            box-shadow: none;
        }

        .dispatch-page .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .dispatch-page .filter-submit {
            min-height: 42px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(110deg, #2563eb, #1d4ed8);
            border: 0;
            box-shadow: 0 4px 10px rgba(37, 99, 235, .18);
            transition: all .2s ease;
        }

        .dispatch-page .filter-submit:hover {
            background: linear-gradient(110deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
        }


        /* =====================================================
           05. DRIVER DOCUMENT OVERVIEW
        ===================================================== */

        .dispatch-page .driver-summary-card {
            overflow: hidden;
            border-left: 4px solid var(--dispatch-blue);
            background: linear-gradient(110deg, #ffffff, #f8fbff);
        }

        .dispatch-page .driver-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            border-radius: 14px;
            color: #fff;
            font-size: 22px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            box-shadow: 0 5px 12px rgba(37, 99, 235, .2);
        }

        .dispatch-page .driver-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--dispatch-dark);
            margin-bottom: 5px;
        }

        .dispatch-page .overview-label {
            display: inline-block;
            color: var(--dispatch-blue);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .8px;
            margin-bottom: 6px;
        }

        .dispatch-page .driver-information {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px 12px;
        }

        .dispatch-page .driver-detail {
            display: inline-block;
            color: var(--dispatch-muted);
            font-size: 12px;
            margin-right: 10px;
            margin-top: 3px;
        }

        .dispatch-page .document-overview-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .dispatch-page .document-overview-count {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
        }

        .dispatch-page .overview-count-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            border-radius: 9px;
            background: #dbeafe;
            color: var(--dispatch-blue);
            font-size: 21px;
        }

        .dispatch-page .document-overview-count h5 {
            color: #1d4ed8;
            font-size: 21px;
            font-weight: 800;
            margin: 0 0 2px;
        }

        .dispatch-page .document-overview-count span {
            color: #475569;
            font-size: 11px;
            font-weight: 600;
        }

        .dispatch-page .document-month-label {
            margin-top: 9px;
            color: var(--dispatch-muted);
            font-size: 11px;
            font-weight: 600;
        }


        /* =====================================================
           06. DOCUMENT CARDS
        ===================================================== */

        .dispatch-page .dispatch-card {
            height: 100%;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .dispatch-page .dispatch-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .09);
        }

        .dispatch-page .dispatch-card .card-body {
            padding: 21px;
        }

        .dispatch-page .document-top {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 17px;
        }

        .dispatch-page .document-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            border-radius: 13px;
            font-size: 22px;
        }

        .dispatch-page .document-icon.attendance {
            color: #2563eb;
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
        }

        .dispatch-page .document-icon.working {
            color: #059669;
            background: linear-gradient(135deg, #d1fae5, #ecfdf5);
        }

        .dispatch-page .document-icon.salary {
            color: #d97706;
            background: linear-gradient(135deg, #fef3c7, #fffbeb);
        }

        .dispatch-page .document-title {
            color: #1e293b;
            font-size: 14px;
            line-height: 1.45;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .dispatch-page .document-type {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 5px;
            color: #64748b;
            background: #f1f5f9;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .dispatch-page .document-description {
            min-height: 54px;
            color: var(--dispatch-muted);
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .dispatch-page .document-details {
            border-top: 1px solid #edf0f4;
            padding-top: 14px;
            margin-bottom: 15px;
        }

        .dispatch-page .document-meta {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: var(--dispatch-muted);
            font-size: 11px;
            margin-bottom: 9px;
            overflow-wrap: anywhere;
        }

        .dispatch-page .document-meta i {
            width: 14px;
            flex: 0 0 14px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .dispatch-page .document-meta strong {
            color: #334155;
            font-weight: 700;
        }


        /* =====================================================
           07. DOCUMENT STATUS AND ACTIONS
        ===================================================== */

        .dispatch-page .status-note {
            min-height: 62px;
            padding: 11px 12px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: var(--dispatch-muted);
            font-size: 11px;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .dispatch-page .document-actions .btn {
            min-height: 39px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 700;
            transition: all .2s ease;
        }

        .dispatch-page .document-actions .btn:disabled {
            opacity: .55;
            cursor: not-allowed;
            box-shadow: none;
        }

        .dispatch-page .document-actions .btn-download {
            color: #475569;
            border: 1px solid #cbd5e1;
            background: #fff;
        }

        .dispatch-page .document-actions .btn-download:hover:not(:disabled) {
            color: var(--dispatch-blue);
            border-color: var(--dispatch-blue);
            background: #eff6ff;
        }

        .dispatch-page .document-actions .btn-whatsapp {
            color: #fff;
            border: 1px solid #16a34a;
            background: linear-gradient(110deg, #22c55e, #15803d);
        }

        .dispatch-page .document-actions .btn-whatsapp:hover:not(:disabled) {
            background: linear-gradient(110deg, #16a34a, #15803d);
            transform: translateY(-1px);
        }


        /* =====================================================
           08. WHATSAPP INFORMATION
        ===================================================== */

        .dispatch-page .whatsapp-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            border-radius: 12px;
            color: #16a34a;
            background: #dcfce7;
            font-size: 26px;
        }

        .dispatch-page .whatsapp-title {
            font-size: 14px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .dispatch-page .whatsapp-description {
            color: var(--dispatch-muted);
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 9px;
        }

        .dispatch-page .mode-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 5px;
            background: #dcfce7;
            color: #166534;
            font-size: 10px;
            font-weight: 800;
        }

        .dispatch-page .mode-badge.unavailable {
            color: #64748b;
            background: #f1f5f9;
        }


        /* =====================================================
           09. DISPATCH GUIDELINES
        ===================================================== */

        .dispatch-page .guideline-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dispatch-page .guideline-list li {
            position: relative;
            padding-left: 25px;
            color: #475569;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 11px;
        }

        .dispatch-page .guideline-list li:last-child {
            margin-bottom: 0;
        }

        .dispatch-page .guideline-list li i {
            position: absolute;
            left: 0;
            top: 3px;
            color: var(--dispatch-green);
        }


        /* =====================================================
           10. EMPTY STATE
        ===================================================== */

        .dispatch-page .empty-state {
            padding: 48px 20px;
            text-align: center;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8fafc, #fff);
        }

        .dispatch-page .empty-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            border-radius: 22px;
            color: var(--dispatch-blue);
            background: #eff6ff;
            font-size: 32px;
            margin-bottom: 17px;
        }

        .dispatch-page .empty-state h5 {
            color: #1e293b;
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .dispatch-page .empty-state p {
            max-width: 460px;
            margin: 0 auto;
            color: var(--dispatch-muted);
            font-size: 12px;
            line-height: 1.7;
        }


        /* =====================================================
           11. TABLET RESPONSIVE
        ===================================================== */

        @media (max-width: 991px) {

            .dispatch-page .card-body {
                padding: 18px;
            }

            .dispatch-page .document-description {
                min-height: 48px;
            }

            .dispatch-page .document-overview-right {
                align-items: flex-start;
            }
        }


        /* =====================================================
           12. MOBILE RESPONSIVE
        ===================================================== */

        @media (max-width: 767px) {

            .dispatch-page .page-header h4 {
                font-size: 21px;
            }

            .dispatch-page .page-header .text-right {
                text-align: left !important;
                margin-top: 14px;
            }

            .dispatch-page .page-header .btn {
                width: 100%;
            }

            .dispatch-page .dispatch-info {
                padding: 12px;
                font-size: 12px;
            }

            .dispatch-page .card-body {
                padding: 16px;
            }

            .dispatch-page .filter-submit {
                margin-top: 2px;
            }

            .dispatch-page .document-overview-count {
                width: 100%;
            }

            .dispatch-page .document-overview-right {
                align-items: flex-start;
                padding-left: 0;
            }

            .dispatch-page .document-description {
                min-height: auto;
            }

            .dispatch-page .empty-state {
                padding: 35px 15px;
            }
        }


        /* =====================================================
           13. SMALL MOBILE RESPONSIVE
        ===================================================== */

        @media (max-width: 575px) {

            .dispatch-page .page-header h4 {
                font-size: 19px;
            }

            .dispatch-page .driver-avatar {
                width: 46px;
                height: 46px;
                flex-basis: 46px;
                font-size: 19px;
            }

            .dispatch-page .driver-name {
                font-size: 16px;
            }

            .dispatch-page .driver-detail {
                display: block;
                margin-right: 0;
            }

            .dispatch-page .document-top {
                gap: 10px;
            }

            .dispatch-page .document-icon {
                width: 46px;
                height: 46px;
                flex-basis: 46px;
                font-size: 19px;
            }

            .dispatch-page .document-actions .btn {
                width: 100%;
            }

            .dispatch-page .document-overview-count h5 {
                font-size: 19px;
            }

            .dispatch-page .whatsapp-icon {
                width: 42px;
                height: 42px;
                flex-basis: 42px;
                font-size: 23px;
            }
        }

    </style>

@endpush

@section('content')
@php
    $currentDriverId = old(
        'driver_id',
        data_get($filters ?? [], 'driver_id', $selectedDriver->id ?? '')
    );

    $currentMonth = old(
        'month',
        data_get($filters ?? [], 'month', $selectedMonth ?? now()->format('Y-m'))
    );

    $driverName = $selectedDriver
        ? trim(($selectedDriver->first_name ?? '') . ' ' . ($selectedDriver->last_name ?? ''))
        : null;

    $documentCards = [
        'attendance' => [
            'title' => 'Monthly Attendance Sheet',
            'description' => 'Monthly attendance records, present days, absent days and leave details.',
            'icon' => 'bi bi-calendar-check',
            'theme' => 'attendance',
            'short' => 'ATTENDANCE',
        ],
        'working_sheet' => [
            'title' => 'Monthly Working Sheet Report',
            'description' => 'Monthly duty records, working hours and completed work details.',
            'icon' => 'bi bi-file-earmark-text',
            'theme' => 'working',
            'short' => 'WORKING SHEET',
        ],
        'salary_slip' => [
            'title' => 'Monthly Salary Slip',
            'description' => 'Monthly salary calculation, allowances, deductions and payable amount.',
            'icon' => 'bi bi-wallet2',
            'theme' => 'salary',
            'short' => 'SALARY SLIP',
        ],
    ];

    $formattedMonth = null;

    try {
        $formattedMonth = \Illuminate\Support\Carbon::createFromFormat(
            'Y-m',
            $currentMonth
        )->format('F Y');
    } catch (\Throwable $exception) {
        $formattedMonth = now()->format('F Y');
    }
@endphp

<div class="container-fluid dispatch-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="row align-items-center">

            <div class="col-md-6 col-sm-12">
                <h4 class="text-blue">
                    <i class="bi bi-send mr-2"></i>
                    Document Dispatch
                </h4>

                <p class="mb-0">
                    Generate and share monthly driver documents through WhatsApp.
                </p>
            </div>

            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('driver-document-sharing.history') }}"
                   class="btn btn-primary">
                    <i class="bi bi-clock-history mr-1"></i>
                    Dispatch History
                </a>
            </div>

        </div>
    </div>

    {{-- Information Banner --}}
    <div class="dispatch-info">
        <div class="dispatch-info-icon">
            <i class="bi bi-info-circle-fill"></i>
        </div>

        <div>
            <strong>Monthly Driver Document Dispatch</strong>

            <div>
                Select a driver and month to manage the three separate monthly
                documents. Each document is intended to be shared individually
                through WhatsApp.
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card filter-card">
        <div class="card-body">

            <div class="d-flex align-items-center mb-4">
                <div class="filter-heading-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>

                <div>
                    <h6 class="section-heading mb-0">Document Filters</h6>
                    <p class="section-subtitle">
                        Select driver and reporting month.
                    </p>
                </div>
            </div>

            <form method="GET"
                  action="{{ route('driver-document-sharing.index') }}">

                <div class="row align-items-end">

                    <div class="col-lg-5 col-md-5 col-sm-12 mb-3">
                        <label for="driver_id" class="form-label">
                            Select Driver <span class="text-danger">*</span>
                        </label>

                        <select name="driver_id"
                                id="driver_id"
                                class="form-control custom-select2"
                                required>

                            <option value="">-- Select Driver --</option>

                            @foreach($drivers as $driver)
                                @php
                                    $fullName = trim(
                                        ($driver->first_name ?? '') . ' ' .
                                        ($driver->last_name ?? '')
                                    );
                                @endphp

                                <option value="{{ $driver->id }}"
                                    {{ (string) $currentDriverId === (string) $driver->id ? 'selected' : '' }}>

                                    {{ $fullName ?: 'Unnamed Driver' }}

                                    @if(!empty($driver->driver_code))
                                        ({{ $driver->driver_code }})
                                    @endif
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                        <label for="month" class="form-label">
                            Select Month <span class="text-danger">*</span>
                        </label>

                        <input type="month"
                               name="month"
                               id="month"
                               class="form-control"
                               value="{{ $currentMonth }}"
                               max="{{ now()->format('Y-m') }}"
                               required>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                        <button type="submit"
                                class="btn btn-primary btn-block filter-submit">
                            <i class="bi bi-search mr-1"></i>
                            Load Documents
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    @if($selectedDriver)

        {{-- Monthly Driver Document Overview --}}
        <div class="card driver-summary-card document-overview-card">

            <div class="card-body">

                <div class="row align-items-center">

                    {{-- Driver Information --}}
                    <div class="col-lg-7 col-md-7 col-sm-12">

                        <div class="d-flex align-items-center">

                            <div class="driver-avatar mr-3">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div>

                                <span class="overview-label">
                                    MONTHLY DRIVER DOCUMENTS
                                </span>

                                <h5 class="driver-name mb-2">
                                    {{ $driverName ?: 'Driver' }}
                                </h5>

                                <div class="driver-information">

                                    @if(!empty($selectedDriver->driver_code))

                                        <span class="driver-detail">
                                            <i class="bi bi-person-badge mr-1"></i>
                                            {{ $selectedDriver->driver_code }}
                                        </span>

                                    @endif

                                    <span class="driver-detail">
                                        <i class="bi bi-calendar-event mr-1"></i>
                                        {{ $formattedMonth }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Document Information --}}
                    <div class="col-lg-5 col-md-5 col-sm-12 mt-3 mt-md-0">

                        <div class="document-overview-right">

                            <div class="document-overview-count">

                                <div class="overview-count-icon">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </div>

                                <div>
                                    <h5>03</h5>
                                    <span>Separate PDF Documents</span>
                                </div>

                            </div>

                            <div class="document-month-label">
                                <i class="bi bi-whatsapp mr-1"></i>
                                Individual WhatsApp Dispatch
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Document Cards --}}
        <div class="row">

            @foreach($documentCards as $documentKey => $document)

                @php
                    $configuredDocument = data_get(
                        $documentTypes ?? [],
                        $documentKey,
                        []
                    );

                    $documentTitle = data_get(
                        $configuredDocument,
                        'title',
                        $document['title']
                    );

                    $documentDescription = data_get(
                        $configuredDocument,
                        'description',
                        $document['description']
                    );

                    $templateKey = data_get(
                        $configuredDocument,
                        'template_key'
                    );

                    $filenamePrefix = data_get(
                        $configuredDocument,
                        'filename_prefix'
                    );
                @endphp

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">

                    <div class="card dispatch-card">
                        <div class="card-body">

                            <div class="document-top">
                                <div class="document-icon {{ $document['theme'] }}">
                                    <i class="{{ $document['icon'] }}"></i>
                                </div>

                                <div>
                                    <h6 class="document-title">
                                        {{ $documentTitle }}
                                    </h6>

                                    <span class="document-type">
                                        {{ $document['short'] }} · PDF
                                    </span>
                                </div>
                            </div>

                            <p class="document-description">
                                {{ $documentDescription }}
                            </p>

                            <div class="document-details">

                                <div class="document-meta">
                                    <i class="bi bi-person"></i>
                                    <span>
                                        Driver:
                                        <strong>
                                            {{ $driverName ?: 'Selected Driver' }}
                                        </strong>
                                    </span>
                                </div>

                                <div class="document-meta">
                                    <i class="bi bi-calendar3"></i>
                                    <span>
                                        Month:
                                        <strong>{{ $formattedMonth }}</strong>
                                    </span>
                                </div>

                                <div class="document-meta">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>
                                        Format:
                                        <strong>PDF Document</strong>
                                    </span>
                                </div>

                                @if($templateKey)
                                    <div class="document-meta">
                                        <i class="bi bi-whatsapp"></i>
                                        <span>
                                            Template:
                                            <strong>{{ $templateKey }}</strong>
                                        </span>
                                    </div>
                                @endif

                                @if($filenamePrefix)
                                    <div class="document-meta">
                                        <i class="bi bi-file-earmark"></i>
                                        <span>
                                            Filename:
                                            <strong>{{ $filenamePrefix }}</strong>
                                        </span>
                                    </div>
                                @endif

                            </div>

                            <div class="status-note">
                                <i class="bi bi-info-circle mr-1"></i>
                                This document will be generated separately and
                                sent only after Admin initiates its dispatch.
                            </div>

                            {{-- Document actions continue in Part 3 --}}

                            {{-- API-ready Document Actions --}}
                            <div class="document-actions">

                                {{-- PDF Preview / Download --}}
                                <button type="button"
                                        class="btn btn-download btn-block"
                                        disabled
                                        data-action="download-document"
                                        data-document-type="{{ $documentKey }}"
                                        data-driver-id="{{ $selectedDriver->id }}"
                                        data-month="{{ $currentMonth }}"
                                        title="PDF generation backend is not connected yet">

                                    <i class="bi bi-file-earmark-arrow-down mr-1"></i>
                                    PDF Preview / Download

                                </button>

                                {{-- Individual WhatsApp Dispatch --}}
                                <button type="button"
                                        class="btn btn-whatsapp btn-block mt-2"
                                        disabled
                                        data-action="send-whatsapp-document"
                                        data-document-type="{{ $documentKey }}"
                                        data-driver-id="{{ $selectedDriver->id }}"
                                        data-month="{{ $currentMonth }}"
                                        data-template-key="{{ $templateKey ?? '' }}"
                                        title="WhatsApp API backend is not connected yet">

                                    <i class="bi bi-whatsapp mr-1"></i>
                                    Send via WhatsApp

                                </button>

                            </div>

                        </div>
                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- Empty State --}}
        <div class="empty-state mb-4">

            <div class="empty-state-icon">
                <i class="bi bi-file-earmark-person"></i>
            </div>

            <h5>Select Driver to Continue</h5>

            <p>
                Please select a driver and reporting month, then click
                Load Documents to view the monthly Attendance Sheet,
                Working Sheet Report and Salary Slip.
            </p>

        </div>

    @endif

    {{-- WhatsApp API Configuration Information --}}
    <div class="card bottom-info-card">
        <div class="card-body">

            <div class="d-flex align-items-start">

                <div class="whatsapp-icon mr-3">
                    <i class="bi bi-whatsapp"></i>
                </div>

                <div>
                    <h6 class="whatsapp-title">
                        WhatsApp Communication
                    </h6>

                    <p class="whatsapp-description">
                        All monthly documents will use the common WhatsApp
                        Master Service. Each document will be sent separately
                        using its configured WhatsApp template.
                    </p>

                    @if(!empty($whatsappMode))

                        <span class="mode-badge">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            WhatsApp Mode: {{ ucfirst($whatsappMode) }}
                        </span>

                    @else

                        <span class="mode-badge unavailable">
                            <i class="bi bi-clock-history mr-1"></i>
                            API Integration Pending
                        </span>

                    @endif

                </div>

            </div>

        </div>
    </div>

    {{-- Dispatch Guidelines --}}
    <div class="card bottom-info-card">
        <div class="card-body">

            <div class="d-flex align-items-center mb-4">

                <div class="filter-heading-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <div>
                    <h6 class="section-heading mb-0">
                        Document Dispatch Guidelines
                    </h6>

                    <p class="section-subtitle">
                        Important rules for monthly driver document sharing.
                    </p>
                </div>

            </div>

            <ul class="guideline-list">

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Attendance Sheet, Working Sheet Report and Salary Slip
                    must be generated as three separate PDF files.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Documents must belong to the selected driver and selected month.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Admin must manually initiate each WhatsApp dispatch.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Sending one document must not automatically send the other documents.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    WhatsApp message templates and document attachments must
                    be handled through the common WhatsApp Master Service.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Dispatch history should record the document type, driver,
                    reporting month, recipient and delivery status.
                </li>

            </ul>

        </div>
    </div>

    <x-backend.footer />

</div>
@endsection

@push('scripts')
@endpush
