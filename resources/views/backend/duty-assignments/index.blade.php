@extends('backend.layouts.master')

@section('title')
    Duty Assignments
@endsection

@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

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
    | Status Badge
    |--------------------------------------------------------------------------
    */

    .status-badge {

        white-space: nowrap;

    }

</style>

@endpush


@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                {{-- Page Title --}}

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">

                        Duty Assignments

                    </h4>

                    <p class="mb-0">

                        Manage driver and vehicle assignments
                        for travel requests.

                    </p>

                </div>


                {{-- Add Duty Assignment --}}

                <div class="col-md-6 col-sm-12 text-right">

                    <a
                        href="{{ route('duty-assignments.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Duty Assignment

                    </a>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('message'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="fa fa-check-circle mr-1"></i>

                {{ session('message') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="fa fa-exclamation-circle mr-1"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- DUTY ASSIGNMENT LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- Card Header --}}

            <div class="pd-20">

                <div
                    class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Duty Assignments

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $dutyAssignments->count() }}

                    </span>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Duty Assignments">


                    {{-- ================================================= --}}
                    {{-- TABLE HEADER --}}
                    {{-- ================================================= --}}

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


                    {{-- ================================================= --}}
                    {{-- TABLE BODY --}}
                    {{-- ================================================= --}}

                    <tbody>

                        @forelse($dutyAssignments as $key => $dutyAssignment)

                            <tr>


                                {{-- ========================================= --}}
                                {{-- Sr No --}}
                                {{-- ========================================= --}}

                                <td>

                                    {{ $key + 1 }}

                                </td>


                                {{-- ========================================= --}}
                                {{-- Assignment Number --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if(!empty($dutyAssignment->assignment_no))

                                        <strong class="assignment-code">

                                            {{ $dutyAssignment->assignment_no }}

                                        </strong>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Travel Request --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->travelRequest)

                                        <strong class="text-dark">

                                            {{ $dutyAssignment->travelRequest->request_no
                                                ?? 'TR-' . $dutyAssignment->travelRequest->id }}

                                        </strong>


                                        @if(
                                            !empty(
                                                $dutyAssignment
                                                    ->travelRequest
                                                    ->passenger_name
                                            )
                                        )

                                            <small
                                                class="text-muted d-block mt-1">

                                                <i class="fa fa-user"></i>

                                                {{ $dutyAssignment->travelRequest->passenger_name }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Driver --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->driver)

                                        @php

                                            $driverName = trim(
                                                ($dutyAssignment->driver->first_name ?? '') .
                                                ' ' .
                                                ($dutyAssignment->driver->last_name ?? '')
                                            );

                                        @endphp


                                        <strong class="text-dark">

                                            {{ $dutyAssignment->driver->driver_name
                                                ?? ($driverName ?: '-') }}

                                        </strong>


                                        @if(
                                            !empty(
                                                $dutyAssignment->driver->driver_code
                                            )
                                        )

                                            <small
                                                class="text-muted d-block mt-1">

                                                <i class="fa fa-id-card-o"></i>

                                                {{ $dutyAssignment->driver->driver_code }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="badge badge-secondary">

                                            Not Assigned

                                        </span>

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Vehicle --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->vehicle)

                                        <strong class="text-dark">

                                            {{ $dutyAssignment->vehicle->vehicle_number
                                                ?? $dutyAssignment->vehicle->registration_number
                                                ?? '-' }}

                                        </strong>


                                        @if(
                                            !empty(
                                                $dutyAssignment->vehicle->vehicle_name
                                            )
                                        )

                                            <small
                                                class="text-muted d-block mt-1">

                                                {{ $dutyAssignment->vehicle->vehicle_name }}

                                            </small>

                                        @elseif(
                                            !empty(
                                                $dutyAssignment->vehicle->vehicle_model
                                            )
                                        )

                                            <small
                                                class="text-muted d-block mt-1">

                                                {{ $dutyAssignment->vehicle->vehicle_model }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="badge badge-secondary">

                                            Not Assigned

                                        </span>

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Assigned Date --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->assigned_at)

                                        <strong>

                                            {{ $dutyAssignment->assigned_at->format('d-m-Y') }}

                                        </strong>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Reporting Time --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->reporting_time)

                                        <strong>

                                            {{ \Carbon\Carbon::parse(
                                                $dutyAssignment->reporting_time
                                            )->format('h:i A') }}

                                        </strong>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Reporting Location --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if(
                                        !empty(
                                            $dutyAssignment->reporting_location
                                        )
                                    )

                                        <div class="location-text">

                                            <i
                                                class="fa fa-map-marker text-danger mr-1">
                                            </i>

                                            {{ $dutyAssignment->reporting_location }}

                                        </div>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Status --}}
                                {{-- ========================================= --}}

                                <td>

                                    @php

                                        $status =
                                            $dutyAssignment->status;

                                        $statusClass = match ($status) {

                                            \App\Models\DutyAssignment::STATUS_PENDING =>
                                                'badge-warning',

                                            \App\Models\DutyAssignment::STATUS_ASSIGNED =>
                                                'badge-primary',

                                            \App\Models\DutyAssignment::STATUS_ACCEPTED =>
                                                'badge-info',

                                            \App\Models\DutyAssignment::STATUS_REJECTED =>
                                                'badge-danger',

                                            \App\Models\DutyAssignment::STATUS_STARTED =>
                                                'badge-dark',

                                            \App\Models\DutyAssignment::STATUS_COMPLETED =>
                                                'badge-success',

                                            \App\Models\DutyAssignment::STATUS_CANCELLED =>
                                                'badge-secondary',

                                            default =>
                                                'badge-secondary',

                                        };


                                        $statusIcon = match ($status) {

                                            \App\Models\DutyAssignment::STATUS_PENDING =>
                                                'fa-clock-o',

                                            \App\Models\DutyAssignment::STATUS_ASSIGNED =>
                                                'fa-car',

                                            \App\Models\DutyAssignment::STATUS_ACCEPTED =>
                                                'fa-check',

                                            \App\Models\DutyAssignment::STATUS_REJECTED =>
                                                'fa-times',

                                            \App\Models\DutyAssignment::STATUS_STARTED =>
                                                'fa-play',

                                            \App\Models\DutyAssignment::STATUS_COMPLETED =>
                                                'fa-check-circle',

                                            \App\Models\DutyAssignment::STATUS_CANCELLED =>
                                                'fa-ban',

                                            default =>
                                                'fa-info-circle',

                                        };


                                        $statusLabel = $status
                                            ? ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status
                                                )
                                            )
                                            : 'Unknown';

                                    @endphp


                                    <span
                                        class="
                                            badge
                                            {{ $statusClass }}
                                            badge-pill
                                            px-3
                                            py-2
                                            status-badge
                                        ">

                                        <i
                                            class="fa {{ $statusIcon }}">
                                        </i>

                                        {{ $statusLabel }}

                                    </span>

                                </td>


                                {{-- ========================================= --}}
                                {{-- Remarks --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if(!empty($dutyAssignment->remarks))

                                        <span
                                            class="remarks-text"
                                            title="{{ $dutyAssignment->remarks }}">

                                            {{ \Illuminate\Support\Str::limit(
                                                $dutyAssignment->remarks,
                                                40
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- ========================================= --}}
                                {{-- Edit --}}
                                {{-- ========================================= --}}

                                <td class="no-export">

                                    <a
                                        href="{{ route(
                                            'duty-assignments.edit',
                                            $dutyAssignment->id
                                        ) }}"
                                        class="btn btn-warning btn-sm">

                                        <i class="dw dw-pencil-1"></i>

                                        Edit

                                    </a>

                                </td>


                                {{-- ========================================= --}}
                                {{-- Delete --}}
                                {{-- ========================================= --}}

                                <td class="no-export">

                                    <form
                                        action="{{ route('duty-assignments.destroy', $dutyAssignment->id) }}"
                                        method="POST"
                                        class="delete-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                        >
                                            <i class="dw dw-trash"></i>
                                            Delete
                                        </button>
                                    </form>

                                </td>


                            </tr>

                        @empty


                            {{-- ================================================= --}}
                            {{-- NO DATA --}}
                            {{-- ================================================= --}}

                            <tr>

                                <td
                                    colspan="12"
                                    class="text-center py-4">

                                    <div class="text-muted">

                                        <i
                                            class="fa fa-inbox fa-2x mb-2 d-block">
                                        </i>

                                        No Duty Assignments Found

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('submit', function (e) {

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
        }).then(function (result) {

            if (result.isConfirmed) {

                HTMLFormElement.prototype.submit.call(form);

            }

        });

    }, true);

});
</script>

<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush