@extends('backend.layouts.master')

@section('title')
    Duty Assignments
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

    <style>
        /*
        |--------------------------------------------------------------------------
        | Table Alignment
        |--------------------------------------------------------------------------
        */
        .table td,
        .table th {
            vertical-align: middle;
        }

        /*
        |--------------------------------------------------------------------------
        | Assignment Number
        |--------------------------------------------------------------------------
        */
        .assignment-code {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        .location-text {
            max-width: 250px;
            white-space: normal;
            word-break: break-word;
        }

        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */
        .remarks-text {
            max-width: 250px;
            white-space: normal;
            word-break: break-word;
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        .status-badge {
            white-space: nowrap;
            min-width: 105px;
            text-align: center;
        }

        /*
        |--------------------------------------------------------------------------
        | Driver / Vehicle
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Action Buttons
        |--------------------------------------------------------------------------
        */
        .action-btn {
            white-space: nowrap;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */
        .empty-state-icon {
            font-size: 36px;
            margin-bottom: 10px;
            opacity: .6;
        }
    </style>
@endpush

@section('content')

    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="min-height-200px">

            {{-- ================================================================= --}}
            {{-- PAGE HEADER                                                        --}}
            {{-- ================================================================= --}}
            <div class="page-header">

                <div class="row">

                    <div class="col-md-6 col-sm-12">

                        <div class="title">

                            <h4>
                                Duty Assignments
                            </h4>

                        </div>

                        <p class="mb-0">
                            Manage driver and vehicle assignments for travel requests.
                        </p>

                    </div>


                    <div class="col-md-6 col-sm-12 text-right">

                        <a href="{{ route('duty-assignments.create') }}" class="btn btn-primary">

                            <i class="fa fa-plus"></i>

                            Add New Duty Assignment

                        </a>

                    </div>

                </div>

            </div>

            {{-- ================================================================= --}}
            {{-- SUCCESS MESSAGE                                                    --}}
            {{-- ================================================================= --}}
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fa fa-check-circle mr-1"></i>

                    {{ session('message') }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>
            @endif

            {{-- ================================================================= --}}
            {{-- ERROR MESSAGE                                                      --}}
            {{-- ================================================================= --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="fa fa-exclamation-circle mr-1"></i>

                    {{ session('error') }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>
            @endif

            {{-- ================================================================= --}}
            {{-- DUTY ASSIGNMENT CARD                                               --}}
            {{-- ================================================================= --}}
            <div class="card-box mb-30">

                {{-- ================================================================= --}}
                {{-- CARD HEADER                                                        --}}
                {{-- ================================================================= --}}
                <div class="pd-20">

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="text-blue h4 mb-0">
                            All Duty Assignments
                        </h4>


                        <span class="badge badge-primary px-3 py-2">

                            Total:
                            {{ $dutyAssignments->count() }}

                        </span>

                    </div>

                </div>

                {{-- ================================================================= --}}
                {{-- TABLE                                                              --}}
                {{-- ================================================================= --}}
                <div class="pb-20">

                    <div class="table-responsive">

                        <table class="table hover multiple-select-row data-table-export1 nowrap p-3" data-title="Duty Assignments">

                            {{-- ===================================================== --}}
                            {{-- HEADER                                                  --}}
                            {{-- ===================================================== --}}
                            <thead>

                                <tr>

                                    <th>
                                        Sr. No.
                                    </th>

                                    <th>
                                        Assignment No.
                                    </th>

                                    <th>
                                        Travel Request
                                    </th>

                                    <th>
                                        Driver
                                    </th>

                                    <th>
                                        Vehicle
                                    </th>

                                    <th>
                                        Assigned Date
                                    </th>

                                    <th>
                                        Reporting Time
                                    </th>

                                    <th>
                                        Reporting Location
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Remarks
                                    </th>

                                    <th class="no-export">
                                        Edit
                                    </th>

                                    <th class="no-export">
                                        Delete
                                    </th>

                                </tr>

                            </thead>


                            {{-- ===================================================== --}}
                            {{-- BODY                                                    --}}
                            {{-- ===================================================== --}}
                            <tbody>

                                @forelse($dutyAssignments
                                as $key => $dutyAssignment)
                                    @php

                                        /*
                                        |--------------------------------------------------------------------------
                                        | DRIVER
                                        |--------------------------------------------------------------------------
                                        */
                                        $driverName = '-';

                                        if ($dutyAssignment->driver) {
                                            $driverName = trim(
                                                ($dutyAssignment->driver->first_name ?? '') .
                                                    ' ' .
                                                    ($dutyAssignment->driver->last_name ?? ''),
                                            );

                                            if ($driverName === '' && !empty($dutyAssignment->driver->driver_name)) {
                                                $driverName = $dutyAssignment->driver->driver_name;
                                            }
                                        }

                                        $driverCode = $dutyAssignment->driver->driver_code ?? null;

                                        /*
                                        |--------------------------------------------------------------------------
                                        | VEHICLE
                                        |--------------------------------------------------------------------------
                                        */
                                        $vehicleNumber =
                                            $dutyAssignment->vehicle->vehicle_number ??
                                            ($dutyAssignment->vehicle->registration_number ?? null);

                                        $vehicleName =
                                            $dutyAssignment->vehicle->vehicle_name ??
                                            ($dutyAssignment->vehicle->vehicle_model ?? null);

                                        /*
                                        |--------------------------------------------------------------------------
                                        | TRAVEL REQUEST
                                        |--------------------------------------------------------------------------
                                        */
                                        $travelRequestNo =
                                            $dutyAssignment->travelRequest->request_no ??
                                            ($dutyAssignment->travelRequest->id ?? null);

                                        $passengerName = $dutyAssignment->travelRequest->passenger_name ?? null;

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

                                        $statusLabel = $status ? ucfirst(str_replace('_', ' ', $status)) : 'Unknown';
                                    @endphp


                                    <tr>


                                        {{-- ================================================= --}}
                                        {{-- SR NO --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            {{ $key + 1 }}

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- ASSIGNMENT NUMBER --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if (!empty($dutyAssignment->assignment_no))
                                                <strong class="assignment-code">

                                                    {{ $dutyAssignment->assignment_no }}

                                                </strong>
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- TRAVEL REQUEST --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if ($dutyAssignment->travelRequest)
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
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- DRIVER --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if ($dutyAssignment->driver)
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


                                        {{-- ================================================= --}}
                                        {{-- VEHICLE --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if ($dutyAssignment->vehicle)
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


                                        {{-- ================================================= --}}
                                        {{-- ASSIGNED DATE --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if ($dutyAssignment->assigned_at)
                                                <strong>

                                                    {{ $dutyAssignment->assigned_at->format('d-m-Y') }}

                                                </strong>
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- REPORTING TIME --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if ($dutyAssignment->reporting_time)
                                                @php

                                                    try {
                                                        $reportingTime = \Carbon\Carbon::parse(
                                                            $dutyAssignment->reporting_time,
                                                        )->format('h:i A');
                                                    } catch (\Throwable $exception) {
                                                        $reportingTime = $dutyAssignment->reporting_time;
                                                    }

                                                @endphp

                                                <strong>
                                                    {{ $reportingTime }}
                                                </strong>
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- REPORTING LOCATION --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if (!empty($dutyAssignment->reporting_location))
                                                <div class="location-text"
                                                    title="{{ $dutyAssignment->reporting_location }}">

                                                    <i class="fa fa-map-marker text-danger mr-1"></i>

                                                    {{ $dutyAssignment->reporting_location }}

                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- STATUS --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            <span
                                                class="
                                            badge
                                            {{ $statusClass }}
                                            badge-pill
                                            px-3
                                            py-2
                                            status-badge
                                        ">

                                                <i class="fa {{ $statusIcon }} mr-1"></i>

                                                {{ $statusLabel }}

                                            </span>

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- REMARKS --}}
                                        {{-- ================================================= --}}
                                        <td>

                                            @if (!empty($dutyAssignment->remarks))
                                                <span class="remarks-text" title="{{ $dutyAssignment->remarks }}">

                                                    {{ \Illuminate\Support\Str::limit($dutyAssignment->remarks, 40) }}

                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- EDIT --}}
                                        {{-- ================================================= --}}
                                        <td class="no-export">

                                            <a href="{{ route('duty-assignments.edit', $dutyAssignment->id) }}"
                                                class="btn btn-warning btn-sm action-btn">

                                                <i class="dw dw-pencil-1"></i>

                                                Edit

                                            </a>

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- DELETE --}}
                                        {{-- ================================================= --}}
                                        <td class="no-export">

                                            {{-- ================================================= --}}
                                            {{-- DELETE FUNCTIONALITY - UNCHANGED --}}
                                            {{-- ================================================= --}}

                                            <form
                                                action="{{ route('duty-assignments.destroy', $dutyAssignment->id) }}"
                                                method="POST" class="delete-form">

                                                @csrf

                                                @method('DELETE')


                                                <button type="submit" class="btn btn-danger btn-sm action-btn">

                                                    <i class="dw dw-trash"></i>

                                                    Delete

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    {{-- ================================================= --}}
                                    {{-- EMPTY STATE --}}
                                    {{-- ================================================= --}}
                                    <tr>

                                        <td colspan="12" class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="fa fa-inbox empty-state-icon d-block"></i>

                                                <strong>
                                                    No Duty Assignments Found
                                                </strong>

                                                <div class="small mt-1">
                                                    Add a new duty assignment to get started.
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


        {{-- ================================================================= --}}
        {{-- FOOTER                                                             --}}
        {{-- ================================================================= --}}

        <x-backend.footer />

    </div>

@endsection

@push('scripts')
    {{-- ===================================================================== --}}
    {{-- SWEETALERT                                                             --}}
    {{-- ===================================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ===================================================================== --}}
    {{-- DELETE FUNCTIONALITY - UNCHANGED                                      --}}
    {{-- ===================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.addEventListener('submit', function(e) {

                const form =
                    e.target.closest('.delete-form');

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

    {{-- ===================================================================== --}}
    {{-- DATATABLE                                                              --}}
    {{-- ===================================================================== --}}
    <script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>
@endpush
