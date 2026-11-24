
@extends('backend.layouts.master')

@section('title', 'Document Dispatch')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | CURRENT FILTER VALUES
    |--------------------------------------------------------------------------
    */

    $currentDriverId = old(
        'driver_id',
        data_get($filters ?? [], 'driver_id', $selectedDriver->id ?? '')
    );

    $currentMonth = old(
        'month',
        data_get($filters ?? [], 'month', $selectedMonth ?? now()->format('Y-m'))
    );

    try {
        $formattedMonth = \Illuminate\Support\Carbon::createFromFormat(
            '!Y-m',
            $currentMonth
        )->format('F Y');
    } catch (\Throwable $exception) {
        $currentMonth = now()->format('Y-m');
        $formattedMonth = now()->format('F Y');
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER DETAILS
    |--------------------------------------------------------------------------
    */

    $driverName = $selectedDriver
        ? trim(
            ($selectedDriver->first_name ?? '') . ' ' .
            ($selectedDriver->last_name ?? '')
        )
        : 'Driver';

    $driverName = $driverName ?: 'Driver';

    $validDriverPhone = (
        !empty($isValidDriverWhatsAppNumber)
        && !empty($normalizedDriverPhone)
    ) ? $normalizedDriverPhone : null;

    /*
    |--------------------------------------------------------------------------
    | WHATSAPP MODE
    |--------------------------------------------------------------------------
    */

    $currentWhatsAppMode = strtolower(trim((string) (
        $whatsappMode ?? config('services.whatsapp.mode', 'local')
    )));

    $isLiveWhatsAppMode = $currentWhatsAppMode === 'live';
    $isPersonalWhatsAppMode = $currentWhatsAppMode === 'local';

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT CARD CONFIGURATION
    |--------------------------------------------------------------------------
    |
    | All three documents are enabled.
    | Controller must generate the PDFs and provide their URLs/status.
    |
    */

    $documentCards = [
        'attendance' => [
            'title' => data_get($documentTypes ?? [], 'attendance.title', 'Monthly Attendance Sheet'),
            'description' => data_get($documentTypes ?? [], 'attendance.description', 'Monthly driver attendance calendar and summary.'),
            'icon' => 'bi-calendar2-check',
            'theme' => 'attendance',
            'short' => 'ATTENDANCE',
            'pdf_stored' => (bool) ($attendancePdfStored ?? false),
            'pdf_url' => data_get($pdfUrls ?? [], 'attendance'),
            'dispatch_route' => 'driver-document-sharing.attendance-dispatch',
            'document_label' => 'Attendance',
            'dispatch_label' => 'Send Attendance PDF',
            'local_label' => 'Open Personal WhatsApp',
        ],

        'working_sheet' => [
            'title' => data_get($documentTypes ?? [], 'working_sheet.title', 'Monthly Working Sheet Report'),
            'description' => data_get($documentTypes ?? [], 'working_sheet.description', 'Monthly duty records, working hours and completed work details.'),
            'icon' => 'bi-file-earmark-spreadsheet',
            'theme' => 'working',
            'short' => 'WORKING SHEET',
            'pdf_stored' => (bool) ($workingSheetPdfStored ?? false),
            'pdf_url' => data_get($pdfUrls ?? [], 'working_sheet'),
            'dispatch_route' => 'driver-document-sharing.working-sheet-dispatch',
            'document_label' => 'Working Sheet',
            'dispatch_label' => 'Send Working Sheet PDF',
            'local_label' => 'Open Personal WhatsApp',
        ],

        'salary_slip' => [
            'title' => data_get($documentTypes ?? [], 'salary_slip.title', 'Monthly Salary Slip'),
            'description' => data_get($documentTypes ?? [], 'salary_slip.description', 'Monthly salary calculation, allowances, deductions and payable amount.'),
            'icon' => 'bi-wallet2',
            'theme' => 'salary',
            'short' => 'SALARY SLIP',
            'pdf_stored' => (bool) ($salarySlipPdfStored ?? false),
            'pdf_url' => data_get($pdfUrls ?? [], 'salary_slip'),
            'dispatch_route' => 'driver-document-sharing.salary-slip-dispatch',
            'document_label' => 'Salary Slip',
            'dispatch_label' => 'Send Salary Slip PDF',
            'local_label' => 'Open Personal WhatsApp',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | VALIDATE PDF URLS
    |--------------------------------------------------------------------------
    */

    foreach ($documentCards as $key => $document) {
        $url = $document['pdf_url'];

        $validUrl = false;

        if (is_string($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            $validUrl = in_array($scheme, ['http', 'https'], true);
        }

        $documentCards[$key]['valid_pdf_url'] = $validUrl ? $url : null;
        $documentCards[$key]['available'] = (
            $document['pdf_stored'] && $validUrl
        );
    }

    $availableDocumentCount = collect($documentCards)
        ->where('available', true)
        ->count();
@endphp

@push('styles')
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
.dispatch-page {
    --dispatch-blue: #2563eb;
    --dispatch-dark: #172554;
    --dispatch-muted: #64748b;
    --dispatch-border: #e2e8f0;
    color: #1e293b;
}

.dispatch-page .page-header { margin-bottom: 22px; }
.dispatch-page .page-header h4 {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -.4px;
    margin-bottom: 6px;
}
.dispatch-page .page-header p,
.dispatch-page .section-subtitle {
    color: #64748b;
    font-size: 13px;
}
.dispatch-page .page-header .btn {
    border-radius: 8px;
    padding: 10px 16px;
    font-weight: 600;
}
.dispatch-page .dispatch-info {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px 18px;
    border: 1px solid #bfdbfe;
    border-left: 4px solid #2563eb;
    border-radius: 10px;
    background: linear-gradient(110deg, #eff6ff, #f8fbff);
    color: #1e40af;
    margin-bottom: 22px;
    font-size: 13px;
}
.dispatch-page .dispatch-info-icon { font-size: 19px; flex: 0 0 auto; }
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
.dispatch-page .bottom-info-card { margin-bottom: 22px; }
.dispatch-page .card-body { padding: 22px; }
.dispatch-page .section-heading {
    font-size: 16px;
    font-weight: 750;
    color: #1e293b;
    margin-bottom: 5px;
}
.dispatch-page .section-subtitle { font-size: 12px; margin-bottom: 0; }
.dispatch-page .filter-heading-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: #eff6ff;
    color: #2563eb;
    margin-right: 9px;
    font-size: 17px;
    flex: 0 0 36px;
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
    background: linear-gradient(110deg, #2563eb, #1d4ed8);
    border: 0;
}
.dispatch-page .driver-summary-card {
    overflow: hidden;
    border-left: 4px solid #2563eb;
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
}
.dispatch-page .driver-name {
    font-size: 18px;
    font-weight: 800;
    color: #172554;
    margin-bottom: 5px;
}
.dispatch-page .driver-detail {
    display: inline-block;
    color: #64748b;
    font-size: 12px;
    margin-right: 14px;
    margin-top: 3px;
}
.dispatch-page .document-count {
    display: inline-block;
    padding: 9px 13px;
    border-radius: 8px;
    color: #1d4ed8;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    font-size: 12px;
    font-weight: 700;
}
.dispatch-page .dispatch-card {
    height: 100%;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
}
.dispatch-page .dispatch-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .09);
}
.dispatch-page .dispatch-card .card-body { padding: 21px; }
.dispatch-page .document-top {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 17px;
}
.dispatch-page .document-icon {
    width: 52px;
    height: 52px;
    flex: 0 0 52px;
    display: flex;
    align-items: center;
    justify-content: center;
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
    color: #64748b;
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
    color: #64748b;
    font-size: 11px;
    margin-bottom: 9px;
    overflow-wrap: anywhere;
}
.dispatch-page .document-meta i {
    width: 14px;
    color: #94a3b8;
    margin-top: 2px;
    flex: 0 0 14px;
}
.dispatch-page .document-meta strong { color: #334155; font-weight: 700; }
.dispatch-page .status-note {
    min-height: 62px;
    padding: 11px 12px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 11px;
    line-height: 1.5;
    margin-bottom: 16px;
}
.dispatch-page .status-note.available {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #166534;
}
.dispatch-page .status-note.warning {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}
.dispatch-page .document-actions .btn {
    min-height: 40px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.dispatch-page .btn-whatsapp {
    color: #fff !important;
    border: 1px solid #16a34a !important;
    background: linear-gradient(110deg, #22c55e, #15803d) !important;
}
.dispatch-page .btn-cloud-api {
    color: #fff !important;
    border: 1px solid #2563eb !important;
    background: linear-gradient(110deg, #3b82f6, #1d4ed8) !important;
}
.dispatch-page .btn-download {
    color: #475569;
    border: 1px solid #cbd5e1;
    background: #fff;
}
.dispatch-page .btn-download:hover {
    color: #1d4ed8;
    border-color: #93c5fd;
    background: #eff6ff;
}
.dispatch-page .btn-disabled { opacity: .6; cursor: not-allowed; }
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
    color: #64748b;
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
.dispatch-page .mode-badge.live { background: #dbeafe; color: #1d4ed8; }
.dispatch-page .guideline-list { list-style: none; padding: 0; margin: 0; }
.dispatch-page .guideline-list li {
    position: relative;
    padding-left: 25px;
    color: #475569;
    font-size: 12px;
    line-height: 1.6;
    margin-bottom: 11px;
}
.dispatch-page .guideline-list li:last-child { margin-bottom: 0; }
.dispatch-page .guideline-list li i {
    position: absolute;
    left: 0;
    top: 3px;
    color: #16a34a;
}
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
    color: #2563eb;
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
    color: #64748b;
    font-size: 12px;
    line-height: 1.7;
}
@media (max-width: 991px) {
    .dispatch-page .card-body { padding: 18px; }
    .dispatch-page .document-description { min-height: 48px; }
}
@media (max-width: 767px) {
    .dispatch-page .page-header h4 { font-size: 21px; }
    .dispatch-page .page-header .text-right {
        text-align: left !important;
        margin-top: 14px;
    }
    .dispatch-page .page-header .btn { width: 100%; }
    .dispatch-page .dispatch-info { padding: 12px; font-size: 12px; }
    .dispatch-page .card-body { padding: 16px; }
    .dispatch-page .filter-submit { margin-top: 2px; }
    .dispatch-page .driver-summary-card .text-md-right { text-align: left !important; }
    .dispatch-page .document-count { margin-top: 12px; }
    .dispatch-page .document-description { min-height: auto; }
    .dispatch-page .empty-state { padding: 35px 15px; }
}
@media (max-width: 575px) {
    .dispatch-page .page-header h4 { font-size: 19px; }
    .dispatch-page .driver-avatar {
        width: 46px; height: 46px; flex-basis: 46px; font-size: 19px;
    }
    .dispatch-page .driver-name { font-size: 16px; }
    .dispatch-page .driver-detail { display: block; margin-right: 0; }
    .dispatch-page .document-top { gap: 10px; }
    .dispatch-page .document-icon {
        width: 46px; height: 46px; flex-basis: 46px; font-size: 19px;
    }
    .dispatch-page .document-description { min-height: auto; }
    .dispatch-page .document-actions .btn { width: 100%; }
}
</style>
@endpush

<div class="container-fluid dispatch-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-6 col-sm-12">
                <h4 class="text-blue">
                    <i class="bi bi-whatsapp mr-2"></i>
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

    {{-- SESSION MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- INFORMATION BANNER --}}
    <div class="dispatch-info">
        <div class="dispatch-info-icon">
            <i class="bi bi-info-circle-fill"></i>
        </div>
        <div>
            <strong>Monthly Driver Document Dispatch</strong>
            <div>
                Select a driver and month to generate the Attendance PDF,
                Working Sheet PDF and Salary Slip PDF. Each document can be
                previewed, downloaded and dispatched independently.
            </div>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="card filter-card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div class="filter-heading-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>
                <div>
                    <h6 class="section-heading mb-0">Document Filters</h6>
                    <p class="section-subtitle">Select driver and reporting month.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('driver-document-sharing.index') }}">
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
                        <button type="submit" class="btn btn-primary btn-block filter-submit">
                            <i class="bi bi-search mr-1"></i>
                            Load Documents
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @if($selectedDriver)

        {{-- DRIVER SUMMARY --}}
        <div class="card driver-summary-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="driver-avatar mr-3">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div>
                                <h6 class="section-heading mb-1">
                                    Monthly Driver Documents
                                </h6>

                                <h5 class="driver-name">
                                    {{ $driverName }}
                                </h5>

                                <div>
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

                                    <span class="driver-detail">
                                        <i class="bi bi-whatsapp mr-1"></i>
                                        {{ $validDriverPhone
                                            ? '+' . $validDriverPhone
                                            : 'Mobile number unavailable' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-md-right">
                        <span class="document-count">
                            <i class="bi bi-file-earmark-pdf mr-1"></i>
                            {{ $availableDocumentCount }} / 3 PDFs Available
                        </span>
                    </div>
                </div>

                <div class="mt-3">
                    <p class="section-subtitle">
                        Attendance, Working Sheet and Salary Slip are
                        independently generated and dispatched for the
                        selected driver and reporting month.
                    </p>
                </div>
            </div>
        </div>

        {{-- ALL THREE DOCUMENT CARDS --}}
        <div class="row">

            @foreach($documentCards as $documentKey => $document)

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                    <div class="card dispatch-card">

                        <div class="card-body">

                            {{-- DOCUMENT HEADING --}}
                            <div class="document-top">
                                <div class="document-icon {{ $document['theme'] }}">
                                    <i class="bi {{ $document['icon'] }}"></i>
                                </div>

                                <div>
                                    <h6 class="document-title">
                                        {{ $document['title'] }}
                                    </h6>

                                    <span class="document-type">
                                        {{ $document['short'] }} · PDF
                                    </span>
                                </div>
                            </div>

                            {{-- DESCRIPTION --}}
                            <p class="document-description">
                                {{ $document['description'] }}
                            </p>

                            {{-- DOCUMENT DETAILS --}}
                            <div class="document-details">

                                <div class="document-meta">
                                    <i class="bi bi-person"></i>
                                    <span>
                                        Driver:
                                        <strong>{{ $driverName }}</strong>
                                    </span>
                                </div>

                                <div class="document-meta">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>
                                        Month:
                                        <strong>{{ $formattedMonth }}</strong>
                                    </span>
                                </div>

                                <div class="document-meta">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>
                                        PDF Status:
                                        <strong>
                                            {{ $document['available']
                                                ? 'PDF Available'
                                                : 'PDF Not Available' }}
                                        </strong>
                                    </span>
                                </div>

                                <div class="document-meta">
                                    <i class="bi bi-whatsapp"></i>
                                    <span>
                                        Recipient:
                                        <strong>
                                            {{ $validDriverPhone
                                                ? '+' . $validDriverPhone
                                                : 'Mobile number unavailable' }}
                                        </strong>
                                    </span>
                                </div>

                            </div>

                            {{-- PDF AVAILABILITY STATUS --}}
                            @if($document['available'])

                                <div class="status-note available">
                                    <i class="bi bi-check-circle-fill mr-1"></i>

                                    <strong>{{ $document['document_label'] }} PDF</strong>
                                    is available for {{ $formattedMonth }}.

                                    You can preview, download or dispatch
                                    this document independently.
                                </div>

                            @else

                                <div class="status-note warning">
                                    <i class="bi bi-exclamation-triangle-fill mr-1"></i>

                                    <strong>{{ $document['document_label'] }} PDF</strong>
                                    is not available.

                                    Please verify the document generation
                                    and report data for this driver and month.
                                </div>

                            @endif

                            {{-- DOCUMENT ACTIONS --}}
                            <div class="document-actions">

                                {{-- PDF PREVIEW / DOWNLOAD --}}
                                @if($document['available'])

                                    <a href="{{ $document['valid_pdf_url'] }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-download btn-block">

                                        <i class="bi bi-file-earmark-pdf"></i>
                                        PDF Preview / Download

                                    </a>

                                @else

                                    <button type="button"
                                            class="btn btn-download btn-block btn-disabled"
                                            disabled>

                                        <i class="bi bi-file-earmark-pdf"></i>
                                        PDF Not Available

                                    </button>

                                @endif

                                {{-- WHATSAPP DISPATCH --}}
                                @if($validDriverPhone && $document['available'])

                                    <form method="POST"
                                          action="{{ route($document['dispatch_route'], [], false) }}"
                                          class="mt-2">

                                        @csrf

                                        <input type="hidden"
                                               name="driver_id"
                                               value="{{ $selectedDriver->id }}">

                                        <input type="hidden"
                                               name="month"
                                               value="{{ $currentMonth }}">

                                        @if($isPersonalWhatsAppMode)

                                            {{-- LOCAL MODE: PERSONAL WHATSAPP --}}
                                            <button type="submit"
                                                    class="btn btn-whatsapp btn-block">

                                                <i class="bi bi-whatsapp"></i>
                                                {{ $document['local_label'] }}

                                            </button>

                                        @elseif($isLiveWhatsAppMode)

                                            {{-- LIVE MODE: CLOUD API --}}
                                            <button type="submit"
                                                    class="btn btn-cloud-api btn-block"
                                                    onclick="return confirm('Are you sure you want to dispatch the {{ $document['document_label'] }} PDF through WhatsApp Cloud API?');">

                                                <i class="bi bi-send-fill"></i>
                                                {{ $document['dispatch_label'] }}

                                            </button>

                                        @else

                                            <button type="button"
                                                    class="btn btn-secondary btn-block btn-disabled"
                                                    disabled>
                                                Invalid WhatsApp Mode
                                            </button>

                                        @endif

                                    </form>

                                @else

                                    <button type="button"
                                            class="btn btn-secondary btn-block mt-2 btn-disabled"
                                            disabled>

                                        <i class="bi bi-exclamation-circle"></i>

                                        @if(!$validDriverPhone)
                                            Valid Mobile Number Required
                                        @else
                                            PDF Required Before Dispatch
                                        @endif

                                    </button>

                                @endif

                            </div>

                        </div>
                    </div>
                </div>

            @endforeach

        </div>

    @else

        {{-- EMPTY STATE --}}
        <div class="empty-state mb-4">

            <div class="empty-state-icon">
                <i class="bi bi-file-earmark-pdf"></i>
            </div>

            <h5>Select Driver to Continue</h5>

            <p>
                Please select a driver and reporting month, then click
                Load Documents to generate and view the monthly Attendance,
                Working Sheet and Salary Slip PDFs.
            </p>

        </div>

    @endif


    {{-- WHATSAPP MODE INFORMATION --}}
    <div class="card bottom-info-card">
        <div class="card-body">

            <div class="d-flex align-items-start">

                <div class="whatsapp-icon mr-3">
                    <i class="bi bi-whatsapp"></i>
                </div>

                <div>
                    <h6 class="whatsapp-title">
                        WhatsApp Integration
                    </h6>

                    @if($isPersonalWhatsAppMode)

                        <p class="whatsapp-description">
                            Local Mode is active. Dispatching any of the
                            three documents will generate a Personal WhatsApp
                            chat link through the Controller.

                            The selected document's message will be
                            pre-filled with its secure PDF download link.
                            Admin must review the message and manually
                            press Send in WhatsApp.

                            Generating this link does not mean the message
                            has been sent, delivered or read.
                        </p>

                        <span class="mode-badge">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            LOCAL · PERSONAL WHATSAPP
                        </span>

                    @elseif($isLiveWhatsAppMode)

                        <p class="whatsapp-description">
                            Live Mode is active. Dispatching any of the
                            three documents will use the official WhatsApp
                            Business Cloud API with the configured template
                            and media services.

                            The selected PDF will be sent as a document
                            attachment. API failures will be reported
                            without falling back to Personal WhatsApp.
                        </p>

                        <span class="mode-badge live">
                            <i class="bi bi-cloud-check-fill mr-1"></i>
                            LIVE · WHATSAPP CLOUD API
                        </span>

                    @else

                        <p class="whatsapp-description">
                            WhatsApp mode configuration is invalid.
                            Please check WHATSAPP_MODE in your environment
                            configuration.
                        </p>

                        <span class="mode-badge">
                            INVALID CONFIGURATION
                        </span>

                    @endif

                </div>
            </div>

        </div>
    </div>


    {{-- DISPATCH GUIDELINES --}}
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
                    Attendance, Working Sheet and Salary Slip PDFs
                    are independently generated for the selected driver
                    and reporting month.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Each document must belong to the selected driver
                    and selected reporting month.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Each document has its own PDF preview, download
                    and WhatsApp dispatch action.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    The Controller validates the driver's WhatsApp number
                    and the selected document before dispatch.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Local mode generates a Personal WhatsApp chat link.
                    It does not confirm that a message has been sent,
                    delivered or read.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Live mode dispatches the selected PDF using the
                    official WhatsApp Cloud API and configured template.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Live API failures must not automatically fall back
                    to Personal WhatsApp.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Every dispatch attempt must follow the existing
                    WhatsApp logging and status management rules.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Personal WhatsApp messages contain a temporary
                    signed PDF download link. The PDF is not automatically
                    attached by a wa.me link.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Salary Slip contains confidential salary information.
                    Verify the selected driver and recipient before dispatch.
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    PDF download routes must remain protected by valid,
                    temporary signed URLs.
                </li>

            </ul>

        </div>
    </div>

</div>

@endsection
