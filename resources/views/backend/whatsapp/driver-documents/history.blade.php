@extends('backend.layouts.master')

@section('title')

    Driver Document Dispatch History

@endsection

@push('styles')

    <link
        rel="stylesheet"
        href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}"
    >

    <style>

        .table td,
        .table th {
            vertical-align: middle;
        }

        .reference-number {
            font-weight: 600;
            letter-spacing: 0.3px;
            font-size: 12px;
        }

        .recipient-number {
            font-weight: 600;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .driver-name {
            font-weight: 600;
        }

        .template-name {
            font-weight: 600;
        }

        .date-text {
            white-space: nowrap;
        }

        .filter-card {
            background: #f8f9fa;
            border-radius: 6px;
        }

        .message-error {
            max-width: 250px;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .document-filename {
            max-width: 200px;
            overflow-wrap: anywhere;
            font-size: 12px;
        }

        .history-status {
            white-space: nowrap;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .history-mode {
            white-space: nowrap;
            font-size: 11px;
            font-weight: 600;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 7px;
        }

        .history-count {
            font-size: 13px;
            font-weight: 600;
        }

        .table-responsive {
            min-height: 150px;
        }

        .empty-history-icon {
            font-size: 36px;
        }

        @media (max-width: 767px) {

            .history-card-header {
                display: block !important;
            }

            .history-card-header .badge {
                display: inline-block;
                margin-top: 10px;
            }

            .filter-actions {
                margin-top: 10px;
            }

        }

    </style>

@endpush

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED USER
    |--------------------------------------------------------------------------
    */

    $authUser = $user ?? auth()->user();

    /*
    |--------------------------------------------------------------------------
    | HISTORY STATUS CONFIGURATION
    |--------------------------------------------------------------------------
    */

    $statusBadgeClasses = [
        'pending' => 'badge-warning',
        'local_test' => 'badge-secondary',
        'sent' => 'badge-primary',
        'delivered' => 'badge-info',
        'read' => 'badge-success',
        'failed' => 'badge-danger',
    ];

    $statusIcons = [
        'pending' => 'fa-clock-o',
        'local_test' => 'fa-flask',
        'sent' => 'fa-paper-plane',
        'delivered' => 'fa-check',
        'read' => 'fa-check-circle',
        'failed' => 'fa-times-circle',
    ];

    /*
    |--------------------------------------------------------------------------
    | DRIVER LOOKUP
    |--------------------------------------------------------------------------
    |
    | WhatsAppLogService returns database records as arrays.
    | Driver collection is converted into an ID-based lookup.
    */

    $driverLookup = collect($drivers ?? [])
        ->keyBy('id');

    /*
    |--------------------------------------------------------------------------
    | TEMPLATE LOOKUP
    |--------------------------------------------------------------------------
    |
    | getTemplates() returns an associative array:
    | template_key => template details.
    */

    $templateLookup = is_array($templates ?? null)
        ? $templates
        : [];

    /*
    |--------------------------------------------------------------------------
    | PAGINATION INFORMATION
    |--------------------------------------------------------------------------
    */

    $totalLogs = $logs->total();

    $currentPage = $logs->currentPage();

    $currentPerPage = $logs->perPage();

    $firstItem = $logs->firstItem() ?? 0;

    $lastItem = $logs->lastItem() ?? 0;

@endphp

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                {{-- ================================================= --}}
                {{-- PAGE TITLE --}}
                {{-- ================================================= --}}

                <div class="col-md-8 col-sm-12">

                    <h4 class="text-blue">

                        Driver Document Dispatch History

                    </h4>

                    <p class="mb-0">

                        View WhatsApp document sharing history, recipient
                        details, message status and dispatch information.

                    </p>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- FILTER CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">

            <div class="pd-20">

                <h4 class="text-blue h4 mb-20">

                    Dispatch History Filters

                </h4>

                <form
                    method="GET"
                    action="{{ route('driver-document-sharing.history') }}"
                    class="form-horizontal filter-card"
                    style="border: 1px solid #023a85; padding: 20px; border-radius: 6px;"
                >

                    <div class="row">

                        {{-- ================================================= --}}
                        {{-- DRIVER FILTER --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                Driver

                            </label>

                            <select
                                name="driver_id"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Drivers

                                </option>

                                @foreach($drivers as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ (string) ($filters['driver_id'] ?? '') === (string) $driver->id ? 'selected' : '' }}
                                    >

                                        {{ trim(
                                            ($driver->first_name ?? '') . ' ' .
                                            ($driver->last_name ?? '')
                                        ) ?: '-' }}

                                        @if(!empty($driver->driver_code))

                                            - {{ $driver->driver_code }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- ================================================= --}}
                        {{-- RECIPIENT FILTER --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                Recipient Number

                            </label>

                            <input
                                type="text"
                                name="recipient"
                                value="{{ $filters['recipient'] ?? '' }}"
                                class="form-control"
                                maxlength="20"
                                placeholder="Search WhatsApp number"
                            >

                        </div>

                        {{-- ================================================= --}}
                        {{-- TEMPLATE FILTER --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                WhatsApp Template

                            </label>

                            <select
                                name="template_key"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Templates

                                </option>

                                @foreach($templates as $templateKey => $template)

                                    <option
                                        value="{{ $templateKey }}"
                                        {{ ($filters['template_key'] ?? '') === $templateKey ? 'selected' : '' }}
                                    >

                                        {{ $template['title']
                                            ?? $template['name']
                                            ?? $templateKey }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- ================================================= --}}
                        {{-- MODE FILTER --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                WhatsApp Mode

                            </label>

                            <select
                                name="mode"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Modes

                                </option>

                                @foreach($modeOptions as $modeKey => $modeLabel)

                                    <option
                                        value="{{ $modeKey }}"
                                        {{ ($filters['mode'] ?? '') === $modeKey ? 'selected' : '' }}
                                    >

                                        {{ $modeLabel }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- ================================================= --}}
                        {{-- STATUS FILTER --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                Message Status

                            </label>

                            <select
                                name="status"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Statuses

                                </option>

                                @foreach($statusOptions as $statusKey => $statusLabel)

                                    <option
                                        value="{{ $statusKey }}"
                                        {{ ($filters['status'] ?? '') === $statusKey ? 'selected' : '' }}
                                    >

                                        {{ $statusLabel }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- ================================================= --}}
                        {{-- DATE FROM --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                Date From

                            </label>

                            <input
                                type="date"
                                name="date_from"
                                value="{{ $filters['date_from'] ?? '' }}"
                                class="form-control"
                            >

                        </div>

                        {{-- ================================================= --}}
                        {{-- DATE TO --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                Date To

                            </label>

                            <input
                                type="date"
                                name="date_to"
                                value="{{ $filters['date_to'] ?? '' }}"
                                class="form-control"
                            >

                        </div>

                        {{-- ================================================= --}}
                        {{-- PER PAGE --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label class="filter-label">

                                Records Per Page

                            </label>

                            <select
                                name="per_page"
                                class="form-control custom-select2"
                            >

                                @foreach([10, 25, 50, 100] as $pageSize)

                                    <option
                                        value="{{ $pageSize }}"
                                        {{ (int) $perPage === $pageSize ? 'selected' : '' }}
                                    >

                                        {{ $pageSize }} Records

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- ================================================= --}}
                        {{-- FILTER BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="col-md-12 col-sm-12 mb-0 filter-actions">

                            <button
                                type="submit"
                                class="btn btn-primary mr-2"
                            >

                                <i class="fa fa-search"></i>

                                Filter

                            </button>

                            <a
                                href="{{ route('driver-document-sharing.history') }}"
                                class="btn btn-secondary"
                            >

                                <i class="fa fa-refresh"></i>

                                Reset

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

                {{-- ========================================================= --}}
        {{-- DISPATCH HISTORY LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">

            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center history-card-header">

                    <h4 class="text-blue h4 mb-0">

                        All Dispatch Records

                    </h4>

                    <span class="badge badge-primary history-count">

                        Total : {{ $totalLogs }}

                    </span>

                </div>

                @if($totalLogs > 0)

                    <small class="text-muted d-block mt-2">

                        Showing {{ $firstItem }}
                        to {{ $lastItem }}
                        of {{ $totalLogs }} records

                    </small>

                @endif

            </div>

            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <div class="table-responsive">

                    <table
                        class="table hover multiple-select-row data-table-export1 nowrap p-3"
                        data-title="Driver Document Dispatch History"
                    >

                        {{-- ================================================= --}}
                        {{-- TABLE HEADER --}}
                        {{-- ================================================= --}}

                        <thead>

                            <tr>

                                <th class="text-wrap">

                                    Sr. No.

                                </th>

                                <th class="text-wrap">

                                    Reference

                                </th>

                                <th class="text-wrap">

                                    Driver

                                </th>

                                <th class="text-wrap">

                                    Recipient

                                </th>

                                <th class="text-wrap">

                                    Template

                                </th>

                                <th class="text-wrap">

                                    Mode

                                </th>

                                <th class="text-wrap">

                                    Status

                                </th>

                                <th class="text-wrap">

                                    Document

                                </th>

                                <th class="text-wrap">

                                    Sent At

                                </th>

                                <th class="text-wrap">

                                    Status Updated

                                </th>

                                <th class="text-wrap">

                                    Created At

                                </th>

                                <th class="text-wrap">

                                    Error Details

                                </th>

                            </tr>

                        </thead>

                        {{-- ================================================= --}}
                        {{-- TABLE BODY --}}
                        {{-- ================================================= --}}

                        <tbody>

                            @forelse($logs as $key => $log)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | LOG RECORD
                                    |--------------------------------------------------------------------------
                                    |
                                    | WhatsAppLogService returns arrays,
                                    | not Eloquent model instances.
                                    */

                                    $logId = $log['id'] ?? null;

                                    $reference = $log['reference'] ?? null;

                                    $driverId = $log['driver_id'] ?? null;

                                    $recipient = $log['recipient'] ?? null;

                                    $templateKey = $log['template_key'] ?? null;

                                    $mode = $log['mode'] ?? null;

                                    $status = $log['status'] ?? 'unknown';

                                    $documentFilename = $log['document_filename'] ?? null;

                                    $errorMessage = $log['error_message'] ?? null;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | DRIVER DETAILS
                                    |--------------------------------------------------------------------------
                                    */

                                    $driver = $driverId
                                        ? $driverLookup->get($driverId)
                                        : null;

                                    $driverName = $driver
                                        ? trim(
                                            ($driver->first_name ?? '') . ' ' .
                                            ($driver->last_name ?? '')
                                        )
                                        : '';

                                    /*
                                    |--------------------------------------------------------------------------
                                    | TEMPLATE DETAILS
                                    |--------------------------------------------------------------------------
                                    */

                                    $templateDetails = $templateLookup[$templateKey] ?? [];

                                    $templateTitle = $templateDetails['title']
                                        ?? $templateDetails['name']
                                        ?? $templateKey
                                        ?? '-';

                                    /*
                                    |--------------------------------------------------------------------------
                                    | STATUS BADGE
                                    |--------------------------------------------------------------------------
                                    */

                                    $statusClass = $statusBadgeClasses[$status]
                                        ?? 'badge-secondary';

                                    $statusIcon = $statusIcons[$status]
                                        ?? 'fa-info-circle';

                                    /*
                                    |--------------------------------------------------------------------------
                                    | MODE DISPLAY
                                    |--------------------------------------------------------------------------
                                    */

                                    $modeLabel = match ($mode) {

                                        'local' => 'Local Testing',

                                        'live' => 'Live WhatsApp',

                                        default => ucfirst(
                                            str_replace('_', ' ', (string) $mode)
                                        ),

                                    };

                                    $modeClass = match ($mode) {

                                        'local' => 'badge-secondary',

                                        'live' => 'badge-success',

                                        default => 'badge-light',

                                    };

                                @endphp

                                <tr>

                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ ($currentPage - 1) * $currentPerPage + $key + 1 }}

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- REFERENCE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($reference))

                                            <strong class="reference-number text-dark">

                                                {{ $reference }}

                                            </strong>

                                        @else

                                            <span class="text-muted">

                                                #{{ $logId ?? '-' }}

                                            </span>

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($driver)

                                            <strong class="driver-name text-dark">

                                                {{ $driverName !== '' ? $driverName : '-' }}

                                            </strong>

                                            @if(!empty($driver->driver_code))

                                                <small class="text-muted d-block mt-1">

                                                    <i class="fa fa-id-card"></i>

                                                    {{ $driver->driver_code }}

                                                </small>

                                            @endif

                                        @elseif(!empty($driverId))

                                            <span class="text-muted">

                                                Driver #{{ $driverId }}

                                            </span>

                                        @else

                                            <span class="text-muted">

                                                -

                                            </span>

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- RECIPIENT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($recipient))

                                            <span class="recipient-number text-dark">

                                                <i class="fa fa-whatsapp text-success"></i>

                                                {{ $recipient }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- TEMPLATE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($templateKey))

                                            <strong class="template-name text-dark">

                                                {{ $templateTitle }}

                                            </strong>

                                            <small class="text-muted d-block mt-1">

                                                {{ $templateKey }}

                                            </small>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- MODE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="badge {{ $modeClass }} history-mode">

                                            @if($mode === 'local')

                                                <i class="fa fa-flask"></i>

                                            @elseif($mode === 'live')

                                                <i class="fa fa-cloud"></i>

                                            @else

                                                <i class="fa fa-info-circle"></i>

                                            @endif

                                            {{ $modeLabel ?: '-' }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="badge {{ $statusClass }} badge-pill px-3 py-2 history-status">

                                            <i class="fa {{ $statusIcon }}"></i>

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    (string) $status
                                                )
                                            ) }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- DOCUMENT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($documentFilename))

                                            <div class="document-filename">

                                                <i class="fa fa-file-pdf-o text-danger"></i>

                                                {{ $documentFilename }}

                                            </div>

                                        @else

                                            <span class="text-muted">

                                                No Document

                                            </span>

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- SENT AT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($log['sent_at']))

                                            <span class="date-text">

                                                {{ \Illuminate\Support\Carbon::parse($log['sent_at'])->format('d-m-Y h:i A') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- STATUS UPDATED --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($log['status_updated_at']))

                                            <span class="date-text">

                                                {{ \Illuminate\Support\Carbon::parse($log['status_updated_at'])->format('d-m-Y h:i A') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- CREATED AT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($log['created_at']))

                                            <span class="date-text">

                                                {{ \Illuminate\Support\Carbon::parse($log['created_at'])->format('d-m-Y h:i A') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                                                        {{-- ===================================== --}}
                                    {{-- ERROR DETAILS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($errorMessage))

                                            <div class="message-error">

                                                <span
                                                    class="text-danger"
                                                    title="{{ $errorMessage }}"
                                                >

                                                    <i class="fa fa-exclamation-circle"></i>

                                                    {{ \Illuminate\Support\Str::limit(
                                                        $errorMessage,
                                                        80
                                                    ) }}

                                                </span>

                                            </div>

                                        @else

                                            <span class="text-muted">

                                                -

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                {{-- ========================================= --}}
                                {{-- NO DATA --}}
                                {{-- ========================================= --}}

                                <tr>

                                    <td
                                        colspan="12"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i
                                                class="fa fa-whatsapp empty-history-icon text-muted mb-3"
                                            ></i>

                                            <h5 class="text-muted">

                                                No Dispatch History Found

                                            </h5>

                                            <p class="text-muted mb-0">

                                                No WhatsApp document dispatch records
                                                match the selected filters.

                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- ===================================================== --}}
                {{-- PAGINATION --}}
                {{-- ===================================================== --}}

                @if($logs->hasPages())

                    <div class="pd-20 pt-0">

                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            <div class="text-muted mb-2">

                                Showing

                                <strong>

                                    {{ $firstItem }}

                                </strong>

                                to

                                <strong>

                                    {{ $lastItem }}

                                </strong>

                                of

                                <strong>

                                    {{ $totalLogs }}

                                </strong>

                                dispatch records

                            </div>

                            <div class="mb-2">

                                {{ $logs->appends(
                                    request()->except('page')
                                )->links() }}

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <x-backend.footer />

</div>

@endsection

@push('scripts')

    <script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush
