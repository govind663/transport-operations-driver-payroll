
@extends('backend.layouts.master')

@section('title')
    Duty Assignments
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .assignment-code {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .assignment-code a {
            color: inherit;
            text-decoration: none;
        }

        .assignment-code a:hover {
            color: #007bff;
            text-decoration: underline;
        }

        .location-text,
        .remarks-text {
            max-width: 250px;
            white-space: normal;
            word-break: break-word;
        }

        .status-badge {
            white-space: nowrap;
            min-width: 105px;
            text-align: center;
        }

        .entity-primary {
            font-weight: 600;
            color: #343a40;
        }

        .entity-secondary {
            display: block;
            margin-top: 2px;
            font-size: 12px;
            color: #6c757d;
        }

        .action-btn {
            white-space: nowrap;
        }

        .empty-state-icon {
            font-size: 36px;
            margin-bottom: 10px;
            opacity: .6;
        }

        .filter-card .form-group {
            margin-bottom: 15px;
        }

        .filter-card label {
            font-weight: 600;
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

        $authUser = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        $isDriver = $authUser && $authUser->isDriver();

        $canManageDutyAssignments = $authUser && (
            $authUser->isAdmin()
            || $authUser->isOperations()
            || $authUser->isAccountant()
        );

        /*
        |--------------------------------------------------------------------------
        | FILTER VALUES
        |--------------------------------------------------------------------------
        */

        $filters = $filters ?? [];
        $filterOptions = $filterOptions ?? [];

        $search = $filters['search'] ?? '';
        $selectedDriver = $filters['driver_id'] ?? '';
        $selectedVehicle = $filters['vehicle_id'] ?? '';
        $selectedStatus = $filters['status'] ?? '';
        $dateFrom = $filters['date_from'] ?? '';
        $dateTo = $filters['date_to'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | TABLE CONFIGURATION
        |--------------------------------------------------------------------------
        */

        $tableColumnCount = $canManageDutyAssignments ? 12 : 10;

        /*
        |--------------------------------------------------------------------------
        | FLASH MESSAGES
        |--------------------------------------------------------------------------
        */

        $successMessage = session('success') ?? session('message');
    @endphp

    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="min-height-200px">

            {{-- ============================================================= --}}
            {{-- PAGE HEADER                                                    --}}
            {{-- ============================================================= --}}

            <div class="page-header">

                <div class="row">

                    <div class="col-md-6 col-sm-12">

                        <div class="title">
                            <h4>Duty Assignments</h4>
                        </div>

                        <p class="mb-0">
                            @if ($isDriver)
                                View your assigned duties and related details.
                            @else
                                Manage driver and vehicle assignments for travel requests.
                            @endif
                        </p>

                    </div>

                    @if ($canManageDutyAssignments)
                        <div class="col-md-6 col-sm-12 text-right">

                            <a href="{{ route('duty-assignments.create') }}"
                                class="btn btn-primary">

                                <i class="fa fa-plus"></i>
                                Add New Duty Assignment

                            </a>

                        </div>
                    @endif

                </div>

            </div>

            {{-- ============================================================= --}}
            {{-- FLASH MESSAGES                                                 --}}
            {{-- ============================================================= --}}

            @if ($successMessage)
                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fa fa-check-circle mr-1"></i>
                    {{ $successMessage }}

                    <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="fa fa-exclamation-circle mr-1"></i>
                    {{ session('error') }}

                    <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">

                    <i class="fa fa-exclamation-circle mr-1"></i>
                    Please check the following errors:

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            {{-- ============================================================= --}}
            {{-- DRIVER INFORMATION                                              --}}
            {{-- ============================================================= --}}

            @if ($isDriver)
                <div class="alert alert-info">

                    <i class="fa fa-info-circle mr-1"></i>
                    You are viewing only the Duty Assignments linked to your Driver profile.

                </div>
            @endif

            {{-- ============================================================= --}}
            {{-- FILTER CARD                                                     --}}
            {{-- ============================================================= --}}

            <div class="card-box mb-30 filter-card">

                <div class="pd-20">

                    <h4 class="text-blue h4 mb-3">
                        <i class="fa fa-filter mr-1"></i>
                        Filter Duty Assignments
                    </h4>

                    <form action="{{ route('duty-assignments.index') }}"
                        method="GET">

                        <div class="row">

                            {{-- SEARCH --}}

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group">

                                    <label for="search">
                                        Search
                                    </label>

                                    <input type="text"
                                        name="search"
                                        id="search"
                                        class="form-control"
                                        placeholder="Assignment no, driver, vehicle..."
                                        value="{{ $search }}">

                                </div>

                            </div>

                            {{-- DRIVER FILTER: MANAGEMENT ONLY --}}

                            @if ($canManageDutyAssignments)

                                <div class="col-md-4 col-sm-12">

                                    <div class="form-group">

                                        <label for="driver_id">
                                            Driver
                                        </label>

                                        <select name="driver_id"
                                            id="driver_id"
                                            class="form-control custom-select2">

                                            <option value="">
                                                All Drivers
                                            </option>

                                            @foreach (($filterOptions['drivers'] ?? []) as $driverOption)

                                                @php
                                                    $optionDriverName = trim(
                                                        ($driverOption->first_name ?? '')
                                                        . ' '
                                                        . ($driverOption->last_name ?? '')
                                                    );
                                                @endphp

                                                <option value="{{ $driverOption->id }}"
                                                    {{ (string) $selectedDriver === (string) $driverOption->id ? 'selected' : '' }}>

                                                    {{ $optionDriverName ?: ($driverOption->driver_code ?? 'Driver #' . $driverOption->id) }}

                                                    @if (!empty($driverOption->driver_code))
                                                        ({{ $driverOption->driver_code }})
                                                    @endif

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                {{-- VEHICLE FILTER --}}

                                <div class="col-md-4 col-sm-12">

                                    <div class="form-group">

                                        <label for="vehicle_id">
                                            Vehicle
                                        </label>

                                        <select name="vehicle_id"
                                            id="vehicle_id"
                                            class="form-control custom-select2">

                                            <option value="">
                                                All Vehicles
                                            </option>

                                            @foreach (($filterOptions['vehicles'] ?? []) as $vehicleOption)

                                                @php
                                                    $optionVehicleNumber =
                                                        $vehicleOption->vehicle_number
                                                        ?? $vehicleOption->registration_number
                                                        ?? 'Vehicle #' . $vehicleOption->id;

                                                    $optionVehicleName =
                                                        $vehicleOption->name
                                                        ?? $vehicleOption->manufacturer
                                                        ?? $vehicleOption->model
                                                        ?? null;
                                                @endphp

                                                <option value="{{ $vehicleOption->id }}"
                                                    {{ (string) $selectedVehicle === (string) $vehicleOption->id ? 'selected' : '' }}>

                                                    {{ $optionVehicleNumber }}

                                                    @if ($optionVehicleName)
                                                        - {{ $optionVehicleName }}
                                                    @endif

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            @endif

                            {{-- STATUS FILTER --}}

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group">

                                    <label for="status">
                                        Status
                                    </label>

                                    <select name="status"
                                        id="status"
                                        class="form-control custom-select2">

                                        <option value="">
                                            All Statuses
                                        </option>

                                        <option value="pending"
                                            {{ $selectedStatus === 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="assigned"
                                            {{ $selectedStatus === 'assigned' ? 'selected' : '' }}>
                                            Assigned
                                        </option>

                                        <option value="accepted"
                                            {{ $selectedStatus === 'accepted' ? 'selected' : '' }}>
                                            Accepted
                                        </option>

                                        <option value="rejected"
                                            {{ $selectedStatus === 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>

                                        <option value="started"
                                            {{ $selectedStatus === 'started' ? 'selected' : '' }}>
                                            Started
                                        </option>

                                        <option value="completed"
                                            {{ $selectedStatus === 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>

                                        <option value="cancelled"
                                            {{ $selectedStatus === 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>

                                    </select>

                                </div>

                            </div>

                            {{-- DATE FROM --}}

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group">

                                    <label for="date_from">
                                        Assigned From Date
                                    </label>

                                    <input type="date"
                                        name="date_from"
                                        id="date_from"
                                        class="form-control"
                                        value="{{ $dateFrom }}">

                                </div>

                            </div>

                            {{-- DATE TO --}}

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group">

                                    <label for="date_to">
                                        Assigned To Date
                                    </label>

                                    <input type="date"
                                        name="date_to"
                                        id="date_to"
                                        class="form-control"
                                        value="{{ $dateTo }}">

                                </div>

                            </div>

                        </div>

                        {{-- FILTER ACTIONS --}}

                        <div class="row">

                            <div class="col-12">

                                <button type="submit"
                                    class="btn btn-primary">

                                    <i class="fa fa-search mr-1"></i>
                                    Apply Filters

                                </button>

                                <a href="{{ route('duty-assignments.index') }}"
                                    class="btn btn-secondary">

                                    <i class="fa fa-refresh mr-1"></i>
                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            {{-- ============================================================= --}}
            {{-- DUTY ASSIGNMENT LIST CARD                                       --}}
            {{-- ============================================================= --}}

            <div class="card-box mb-30">

                <div class="pd-20">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <h4 class="text-blue h4 mb-0">

                            @if ($isDriver)
                                My Duty Assignments
                            @else
                                All Duty Assignments
                            @endif

                        </h4>

                        <span class="badge badge-primary px-3 py-2">
                            Total: {{ $dutyAssignments->count() }}
                        </span>

                    </div>

                </div>

                {{-- TABLE --}}

                <div class="pb-20">

                    <div class="table-responsive">

                        <table class="table hover multiple-select-row data-table-export1 nowrap p-3"
                            data-title="Duty Assignments">

                            <thead>

                                <tr>

                                    <th>Sr. No.</th>
                                    <th>Assignment No.</th>
                                    <th>Travel Request</th>
                                    <th>Driver</th>
                                    <th>Vehicle</th>
                                    <th>Assigned Date</th>
                                    <th>Reporting Time</th>
                                    <th>Reporting Location</th>
                                    <th>Status</th>
                                    <th>Remarks</th>

                                    @if ($canManageDutyAssignments)
                                        <th class="no-export">Edit</th>
                                        <th class="no-export">Delete</th>
                                    @endif

                                </tr>

                            </thead>

                            <tbody>

                                @forelse ($dutyAssignments as $key => $dutyAssignment)

                                    @php
                                        /*
                                        |--------------------------------------------------------------------------
                                        | DRIVER
                                        |--------------------------------------------------------------------------
                                        */

                                        $driver = $dutyAssignment->driver;

                                        $driverName = '-';

                                        if ($driver) {
                                            $driverName = trim(
                                                ($driver->first_name ?? '')
                                                . ' '
                                                . ($driver->last_name ?? '')
                                            );

                                            if (
                                                $driverName === ''
                                                && !empty($driver->driver_name)
                                            ) {
                                                $driverName = $driver->driver_name;
                                            }

                                            if ($driverName === '') {
                                                $driverName = '-';
                                            }
                                        }

                                        $driverCode = $driver->driver_code ?? null;

                                        /*
                                        |--------------------------------------------------------------------------
                                        | VEHICLE
                                        |--------------------------------------------------------------------------
                                        */

                                        $vehicle = $dutyAssignment->vehicle;

                                        $vehicleNumber =
                                            $vehicle->vehicle_number
                                            ?? $vehicle->registration_number
                                            ?? null;

                                        $vehicleName =
                                            $vehicle->vehicle_name
                                            ?? $vehicle->vehicle_model
                                            ?? null;

                                        /*
                                        |--------------------------------------------------------------------------
                                        | TRAVEL REQUEST
                                        |--------------------------------------------------------------------------
                                        */

                                        $travelRequest = $dutyAssignment->travelRequest;

                                        $travelRequestNo =
                                            $travelRequest->request_no
                                            ?? $travelRequest->id
                                            ?? null;

                                        $passengerName =
                                            $travelRequest->passenger_name
                                            ?? null;

                                        /*
                                        |--------------------------------------------------------------------------
                                        | STATUS
                                        |--------------------------------------------------------------------------
                                        */

                                        $status = $dutyAssignment->status;

                                        $statusClass = match ($status) {
                                            \App\Models\DutyAssignment::STATUS_PENDING => 'badge-warning',
                                            \App\Models\DutyAssignment::STATUS_ASSIGNED => 'badge-primary',
                                            \App\Models\DutyAssignment::STATUS_ACCEPTED => 'badge-info',
                                            \App\Models\DutyAssignment::STATUS_REJECTED => 'badge-danger',
                                            \App\Models\DutyAssignment::STATUS_STARTED => 'badge-dark',
                                            \App\Models\DutyAssignment::STATUS_COMPLETED => 'badge-success',
                                            \App\Models\DutyAssignment::STATUS_CANCELLED => 'badge-secondary',
                                            default => 'badge-secondary',
                                        };

                                        $statusIcon = match ($status) {
                                            \App\Models\DutyAssignment::STATUS_PENDING => 'fa-clock-o',
                                            \App\Models\DutyAssignment::STATUS_ASSIGNED => 'fa-car',
                                            \App\Models\DutyAssignment::STATUS_ACCEPTED => 'fa-check',
                                            \App\Models\DutyAssignment::STATUS_REJECTED => 'fa-times',
                                            \App\Models\DutyAssignment::STATUS_STARTED => 'fa-play',
                                            \App\Models\DutyAssignment::STATUS_COMPLETED => 'fa-check-circle',
                                            \App\Models\DutyAssignment::STATUS_CANCELLED => 'fa-ban',
                                            default => 'fa-info-circle',
                                        };

                                        $statusLabel = $status
                                            ? ucfirst(str_replace('_', ' ', $status))
                                            : 'Unknown';
                                    @endphp

                                    <tr>

                                        {{-- SR NO --}}

                                        <td>
                                            {{ $key + 1 }}
                                        </td>

                                        {{-- ASSIGNMENT NUMBER --}}

                                        <td>

                                            @if (!empty($dutyAssignment->assignment_no))

                                                <strong class="assignment-code">

                                                    <a href="{{ route('duty-assignments.show', $dutyAssignment->id) }}"
                                                        title="View Duty Assignment">

                                                        {{ $dutyAssignment->assignment_no }}

                                                    </a>

                                                </strong>

                                            @else

                                                <a href="{{ route('duty-assignments.show', $dutyAssignment->id) }}"
                                                    class="text-primary">

                                                    #{{ $dutyAssignment->id }}

                                                </a>

                                            @endif

                                        </td>

                                        {{-- TRAVEL REQUEST --}}

                                        <td>

                                            @if ($travelRequest)

                                                <strong class="entity-primary">
                                                    {{ $travelRequestNo }}
                                                </strong>

                                                @if ($passengerName)

                                                    <span class="entity-secondary">

                                                        <i class="fa fa-user mr-1"></i>
                                                        {{ $passengerName }}

                                                    </span>

                                                @endif

                                            @else

                                                <span class="text-muted">-</span>

                                            @endif

                                        </td>

                                        {{-- DRIVER --}}

                                        <td>

                                            @if ($driver)

                                                <strong class="entity-primary">
                                                    {{ $driverName }}
                                                </strong>

                                                @if ($driverCode)

                                                    <span class="entity-secondary">

                                                        <i class="fa fa-id-card-o mr-1"></i>
                                                        {{ $driverCode }}

                                                    </span>

                                                @endif

                                            @else

                                                <span class="badge badge-secondary">
                                                    Not Assigned
                                                </span>

                                            @endif

                                        </td>

                                        {{-- VEHICLE --}}

                                        <td>

                                            @if ($vehicle)

                                                <strong class="entity-primary">
                                                    {{ $vehicleNumber ?: '-' }}
                                                </strong>

                                                @if ($vehicleName)

                                                    <span class="entity-secondary">

                                                        <i class="fa fa-car mr-1"></i>
                                                        {{ $vehicleName }}

                                                    </span>

                                                @endif

                                            @else

                                                <span class="badge badge-secondary">
                                                    Not Assigned
                                                </span>

                                            @endif

                                        </td>

                                        {{-- ASSIGNED DATE --}}

                                        <td>

                                            @if ($dutyAssignment->assigned_at)

                                                <strong>
                                                    {{ $dutyAssignment->assigned_at->format('d-m-Y') }}
                                                </strong>

                                            @else

                                                <span class="text-muted">-</span>

                                            @endif

                                        </td>

                                        {{-- REPORTING TIME --}}

                                        <td>

                                            @if ($dutyAssignment->reporting_time)

                                                @php
                                                    try {
                                                        $reportingTime = \Carbon\Carbon::parse(
                                                            $dutyAssignment->reporting_time
                                                        )->format('h:i A');
                                                    } catch (\Throwable $exception) {
                                                        $reportingTime = $dutyAssignment->reporting_time;
                                                    }
                                                @endphp

                                                <strong>
                                                    {{ $reportingTime }}
                                                </strong>

                                            @else

                                                <span class="text-muted">-</span>

                                            @endif

                                        </td>

                                        {{-- REPORTING LOCATION --}}

                                        <td>

                                            @if (!empty($dutyAssignment->reporting_location))

                                                <div class="location-text"
                                                    title="{{ $dutyAssignment->reporting_location }}">

                                                    <i class="fa fa-map-marker text-danger mr-1"></i>

                                                    {{ $dutyAssignment->reporting_location }}

                                                </div>

                                            @else

                                                <span class="text-muted">-</span>

                                            @endif

                                        </td>

                                        {{-- STATUS --}}

                                        <td>

                                            <span class="badge {{ $statusClass }} badge-pill px-3 py-2 status-badge">

                                                <i class="fa {{ $statusIcon }} mr-1"></i>

                                                {{ $statusLabel }}

                                            </span>

                                        </td>

                                        {{-- REMARKS --}}

                                        <td>

                                            @if (!empty($dutyAssignment->remarks))

                                                <span class="remarks-text"
                                                    title="{{ $dutyAssignment->remarks }}">

                                                    {{ \Illuminate\Support\Str::limit($dutyAssignment->remarks, 40) }}

                                                </span>

                                            @else

                                                <span class="text-muted">-</span>

                                            @endif

                                        </td>

                                        {{-- EDIT AND DELETE: MANAGEMENT ONLY --}}

                                        @if ($canManageDutyAssignments)

                                            <td class="no-export">

                                                <a href="{{ route('duty-assignments.edit', $dutyAssignment->id) }}"
                                                    class="btn btn-warning btn-sm action-btn">

                                                    <i class="dw dw-pencil-1"></i>
                                                    Edit

                                                </a>

                                            </td>

                                            <td class="no-export">

                                                <form
                                                    action="{{ route('duty-assignments.destroy', $dutyAssignment->id) }}"
                                                    method="POST"
                                                    class="delete-form">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm action-btn">

                                                        <i class="dw dw-trash"></i>
                                                        Delete

                                                    </button>

                                                </form>

                                            </td>

                                        @endif

                                    </tr>

                                @empty

                                    {{-- EMPTY STATE --}}

                                    <tr>

                                        <td colspan="{{ $tableColumnCount }}"
                                            class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="fa fa-inbox empty-state-icon d-block"></i>

                                                <strong>
                                                    No Duty Assignments Found
                                                </strong>

                                                <div class="small mt-1">

                                                    @if ($isDriver)
                                                        No duty assignments are currently linked to your Driver profile.
                                                    @else
                                                        No duty assignments match the selected filters.
                                                    @endif

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        {{-- FOOTER --}}

        <x-backend.footer />

    </div>

@endsection


@push('scripts')

    {{-- ============================================================= --}}
    {{-- SWEETALERT                                                     --}}
    {{-- ============================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ============================================================= --}}
    {{-- DELETE FUNCTIONALITY                                           --}}
    {{-- ============================================================= --}}

    @if ($canManageDutyAssignments)

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                document.addEventListener('submit', function(e) {

                    const form = e.target.closest('.delete-form');

                    if (!form) {
                        return;
                    }

                    e.preventDefault();
                    e.stopPropagation();

                    Swal.fire({

                        title: 'Are you sure?',

                        text: 'This duty assignment will be moved to trash!',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#d33',

                        cancelButtonColor: '#6c757d',

                        confirmButtonText: 'Yes, Delete',

                        cancelButtonText: 'Cancel',

                        reverseButtons: true,

                        allowOutsideClick: false,

                        allowEscapeKey: true

                    }).then(function(result) {

                        if (result.isConfirmed) {

                            HTMLFormElement.prototype.submit.call(form);

                        }

                    });

                }, true);

            });
        </script>

    @endif

    {{-- ============================================================= --}}
    {{-- DATATABLE                                                      --}}
    {{-- ============================================================= --}}

    <script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush
