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

    .vehicle-type-code {
        font-weight: 600;
        letter-spacing: 0.5px;
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

                        Manage driver and vehicle assignments for travel requests.

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
        {{-- DUTY ASSIGNMENT LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- Card Header --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

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
                                Assigned At
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

                            <th>
                                Created At
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

                                    <strong class="assignment-code">

                                        {{ $dutyAssignment->assignment_no ?? '-' }}

                                    </strong>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Travel Request --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->travelRequest)

                                        <strong class="text-dark">

                                            {{ $dutyAssignment->travelRequest->request_no ?? '-' }}

                                        </strong>

                                        @if(
                                            !empty(
                                                $dutyAssignment
                                                    ->travelRequest
                                                    ->passenger_name
                                            )
                                        )

                                            <small class="text-muted d-block mt-1">

                                                <i class="fa fa-user"></i>

                                                {{ $dutyAssignment->travelRequest->passenger_name }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Driver --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->driver)

                                        <strong class="text-dark">

                                            {{ $dutyAssignment->driver->driver_name
                                                ?? trim(
                                                    ($dutyAssignment->driver->first_name ?? '') .
                                                    ' ' .
                                                    ($dutyAssignment->driver->last_name ?? '')
                                                )
                                                ?: '-' }}

                                        </strong>

                                        @if(
                                            !empty(
                                                $dutyAssignment->driver->driver_code
                                            )
                                        )

                                            <small class="text-muted d-block mt-1">

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

                                            <small class="text-muted d-block mt-1">

                                                {{ $dutyAssignment->vehicle->vehicle_name }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="badge badge-secondary">

                                            Not Assigned

                                        </span>

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Assigned At --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutyAssignment->assigned_at)

                                        <strong>

                                            {{ $dutyAssignment->assigned_at->format('d-m-Y') }}

                                        </strong>

                                        <small class="text-muted d-block">

                                            <i class="fa fa-clock-o"></i>

                                            {{ $dutyAssignment->assigned_at->format('h:i A') }}

                                        </small>

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

                                            {{ $dutyAssignment->reporting_time->format('d-m-Y') }}

                                        </strong>

                                        <small class="text-muted d-block">

                                            <i class="fa fa-clock-o"></i>

                                            {{ $dutyAssignment->reporting_time->format('h:i A') }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Reporting Location --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if(!empty($dutyAssignment->reporting_location))

                                        <div class="location-text">

                                            <i class="fa fa-map-marker text-danger mr-1"></i>

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

                                        $status = $dutyAssignment->status;

                                        $statusClass = match ($status) {

                                            'pending' =>
                                                'badge-warning',

                                            'assigned' =>
                                                'badge-primary',

                                            'accepted' =>
                                                'badge-info',

                                            'rejected' =>
                                                'badge-danger',

                                            'started' =>
                                                'badge-dark',

                                            'completed' =>
                                                'badge-success',

                                            'cancelled' =>
                                                'badge-secondary',

                                            default =>
                                                'badge-secondary',

                                        };


                                        $statusIcon = match ($status) {

                                            'pending' =>
                                                'fa-clock-o',

                                            'assigned' =>
                                                'fa-car',

                                            'accepted' =>
                                                'fa-check',

                                            'rejected' =>
                                                'fa-times',

                                            'started' =>
                                                'fa-play',

                                            'completed' =>
                                                'fa-check-circle',

                                            'cancelled' =>
                                                'fa-ban',

                                            default =>
                                                'fa-info-circle',

                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }} badge-pill px-3 py-2">

                                        <i class="fa {{ $statusIcon }}"></i>

                                        {{ ucfirst($status ?? 'Unknown') }}

                                    </span>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Remarks --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if(!empty($dutyAssignment->remarks))

                                        <span
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
                                {{-- Created At --}}
                                {{-- ========================================= --}}

                                <td>

                                    {{ optional(
                                        $dutyAssignment->created_at
                                    )->format('d-m-Y') }}

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
                                        action="{{ route(
                                            'duty-assignments.destroy',
                                            $dutyAssignment->id
                                        ) }}"
                                        method="POST"
                                        class="delete-form">

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">

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
                                    colspan="13"
                                    class="text-center">

                                    No Duty Assignments Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Footer --}}

    <x-backend.footer />


</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Duty Assignment Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();


            Swal.fire({

                title: 'Are you sure?',

                text: 'This duty assignment will be moved to trash.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Yes, Delete',

                cancelButtonText: 'Cancel',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    });


});

</script>


<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush