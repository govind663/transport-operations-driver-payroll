@extends('backend.layouts.master')

@section('title')
    Duty Slips
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

                        Duty Slips

                    </h4>

                    <p class="mb-0">

                        Manage all duty slips, trip details, vehicle usage
                        and fuel information.

                    </p>

                </div>


                {{-- Add Duty Slip --}}
                <div class="col-md-6 col-sm-12 text-right">

                    <a
                        href="{{ route('duty-slips.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Duty Slip

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- DUTY SLIP LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- Card Header --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Duty Slips

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $dutySlips->count() }}

                    </span>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Duty Slips">


                    {{-- ================================================= --}}
                    {{-- TABLE HEADER --}}
                    {{-- ================================================= --}}

                    <thead>

                        <tr>

                            <th class="text-wrap">
                                Sr. No.
                            </th>

                            <th class="text-wrap">
                                Slip No.
                            </th>

                            <th class="text-wrap">
                                Duty Assignment
                            </th>

                            <th class="text-wrap">
                                Duty Date
                            </th>

                            <th class="text-wrap">
                                Start Time
                            </th>

                            <th class="text-wrap">
                                End Time
                            </th>

                            <th class="text-wrap">
                                Opening Meter
                            </th>

                            <th class="text-wrap">
                                Closing Meter
                            </th>

                            <th class="text-wrap">
                                Total KM
                            </th>

                            <th class="text-wrap">
                                Fuel Quantity
                            </th>

                            <th class="text-wrap">
                                Fuel Amount
                            </th>

                            <th class="text-wrap">
                                Status
                            </th>

                            <th class="text-wrap">
                                Remarks
                            </th>

                            <th class="text-wrap no-export">
                                Edit
                            </th>

                            <th class="text-wrap no-export">
                                Delete
                            </th>

                        </tr>

                    </thead>



                    {{-- ================================================= --}}
                    {{-- TABLE BODY --}}
                    {{-- ================================================= --}}

                    <tbody>

                        @forelse($dutySlips as $key => $dutySlip)

                            <tr>


                                {{-- ========================================= --}}
                                {{-- Sr No --}}
                                {{-- ========================================= --}}

                                <td>

                                    {{ $key + 1 }}

                                </td>



                                {{-- ========================================= --}}
                                {{-- Slip Number --}}
                                {{-- ========================================= --}}

                                <td>

                                    <strong class="slip-code">

                                        {{ $dutySlip->slip_no ?? '-' }}

                                    </strong>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Duty Assignment --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->dutyAssignment)

                                        <strong class="text-dark">

                                            {{ $dutySlip->dutyAssignment->assignment_no ?? '-' }}

                                        </strong>


                                        @if(
                                            !empty(
                                                $dutySlip
                                                    ->dutyAssignment
                                                    ->driver
                                                    ->driver_code
                                            )
                                        )

                                            <small class="text-muted d-block mt-1">

                                                <i class="fa fa-user"></i>

                                                Driver:

                                                {{ $dutySlip->dutyAssignment->driver->driver_code }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Duty Date --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->duty_date)

                                        {{ $dutySlip->duty_date->format('d-m-Y') }}

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Start Time --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->start_time)

                                        {{ $dutySlip->start_time->format('d-m-Y') }}

                                        <small class="text-muted d-block">

                                            <i class="fa fa-clock-o"></i>

                                            {{ $dutySlip->start_time->format('h:i A') }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- End Time --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->end_time)

                                        {{ $dutySlip->end_time->format('d-m-Y') }}

                                        <small class="text-muted d-block">

                                            <i class="fa fa-clock-o"></i>

                                            {{ $dutySlip->end_time->format('h:i A') }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Opening Meter --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->opening_meter !== null)

                                        <span class="amount-text">

                                            {{ number_format(
                                                (float) $dutySlip->opening_meter,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Closing Meter --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->closing_meter !== null)

                                        <span class="amount-text">

                                            {{ number_format(
                                                (float) $dutySlip->closing_meter,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Total KM --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->total_km !== null)

                                        <span class="badge badge-info">

                                            {{ number_format(
                                                (float) $dutySlip->total_km,
                                                2
                                            ) }}

                                            KM

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Fuel Quantity --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->fuel_quantity !== null)

                                        {{ number_format(
                                            (float) $dutySlip->fuel_quantity,
                                            2
                                        ) }}

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Fuel Amount --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($dutySlip->fuel_amount !== null)

                                        <span class="amount-text">

                                            ₹{{ number_format(
                                                (float) $dutySlip->fuel_amount,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Status --}}
                                {{-- ========================================= --}}

                                <td>

                                    @php

                                        $status = $dutySlip->status;

                                        $statusClass = match ($status) {

                                            'open' =>
                                                'badge-primary',

                                            'started' =>
                                                'badge-info',

                                            'completed' =>
                                                'badge-success',

                                            'cancelled' =>
                                                'badge-danger',

                                            default =>
                                                'badge-secondary',

                                        };


                                        $statusIcon = match ($status) {

                                            'open' =>
                                                'fa-folder-open',

                                            'started' =>
                                                'fa-play',

                                            'completed' =>
                                                'fa-check-circle',

                                            'cancelled' =>
                                                'fa-times-circle',

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

                                    @if(!empty($dutySlip->remarks))

                                        <span
                                            title="{{ $dutySlip->remarks }}">

                                            {{ \Illuminate\Support\Str::limit(
                                                $dutySlip->remarks,
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
                                            'duty-slips.edit',
                                            $dutySlip->id
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
                                            'duty-slips.destroy',
                                            $dutySlip->id
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
                                    colspan="16"
                                    class="text-center">

                                    No Duty Slips Found

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
    | Duty Slip Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();


            Swal.fire({

                title: 'Are you sure?',

                text: 'This duty slip will be moved to trash.',

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