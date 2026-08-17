@extends('backend.layouts.master')

@section('title')
    Working Sheets
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

                        Working Sheets

                    </h4>

                    <p class="mb-0">

                        Manage working sheets, meter readings,
                        working hours and payment details.

                    </p>

                </div>


                {{-- Add Working Sheet --}}
                <div class="col-md-6 col-sm-12 text-right">

                    <a
                        href="{{ route('working-sheets.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Working Sheet

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- WORKING SHEET LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- Card Header --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Working Sheets

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $workingSheets->count() }}

                    </span>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Working Sheets">


                    {{-- ================================================= --}}
                    {{-- TABLE HEADER --}}
                    {{-- ================================================= --}}

                    <thead>

                        <tr>

                            <th class="text-wrap">
                                Sr. No.
                            </th>

                            <th class="text-wrap">
                                Sheet No.
                            </th>

                            <th class="text-wrap">
                                Duty Slip
                            </th>

                            <th class="text-wrap">
                                Work Date
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
                                Total Hours
                            </th>

                            <th class="text-wrap">
                                Overtime Hours
                            </th>

                            <th class="text-wrap">
                                Base Amount
                            </th>

                            <th class="text-wrap">
                                Extra KM Amount
                            </th>

                            <th class="text-wrap">
                                Overtime Amount
                            </th>

                            <th class="text-wrap">
                                Other Amount
                            </th>

                            <th class="text-wrap">
                                Total Amount
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

                        @forelse($workingSheets as $key => $workingSheet)

                            <tr>


                                {{-- ========================================= --}}
                                {{-- Sr No --}}
                                {{-- ========================================= --}}

                                <td>

                                    {{ $key + 1 }}

                                </td>



                                {{-- ========================================= --}}
                                {{-- Sheet Number --}}
                                {{-- ========================================= --}}

                                <td>

                                    <strong class="sheet-code">

                                        {{ $workingSheet->sheet_no ?? '-' }}

                                    </strong>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Duty Slip --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->dutySlip)

                                        <strong class="text-dark">

                                            {{ $workingSheet->dutySlip->slip_no ?? '-' }}

                                        </strong>

                                        @if(
                                            $workingSheet->dutySlip->duty_date
                                        )

                                            <small class="text-muted d-block mt-1">

                                                <i class="fa fa-calendar"></i>

                                                {{ $workingSheet->dutySlip->duty_date->format('d-m-Y') }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Work Date --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->work_date)

                                        {{ $workingSheet->work_date->format('d-m-Y') }}

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Opening Meter --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->opening_meter !== null)

                                        {{ number_format(
                                            (float) $workingSheet->opening_meter,
                                            2
                                        ) }}

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Closing Meter --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->closing_meter !== null)

                                        {{ number_format(
                                            (float) $workingSheet->closing_meter,
                                            2
                                        ) }}

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Total KM --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->total_km !== null)

                                        <span class="badge badge-info">

                                            {{ number_format(
                                                (float) $workingSheet->total_km,
                                                2
                                            ) }}

                                            KM

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Total Hours --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->total_hours !== null)

                                        {{ number_format(
                                            (float) $workingSheet->total_hours,
                                            2
                                        ) }}

                                        hrs

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Overtime Hours --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->overtime_hours !== null)

                                        <span class="badge badge-warning">

                                            {{ number_format(
                                                (float) $workingSheet->overtime_hours,
                                                2
                                            ) }}

                                            hrs

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Base Amount --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->base_amount !== null)

                                        <span class="amount-text">

                                            ₹{{ number_format(
                                                (float) $workingSheet->base_amount,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Extra KM Amount --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->extra_km_amount !== null)

                                        <span class="amount-text">

                                            ₹{{ number_format(
                                                (float) $workingSheet->extra_km_amount,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Overtime Amount --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->overtime_amount !== null)

                                        <span class="amount-text">

                                            ₹{{ number_format(
                                                (float) $workingSheet->overtime_amount,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Other Amount --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->other_amount !== null)

                                        <span class="amount-text">

                                            ₹{{ number_format(
                                                (float) $workingSheet->other_amount,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Total Amount --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($workingSheet->total_amount !== null)

                                        <strong class="text-success">

                                            ₹{{ number_format(
                                                (float) $workingSheet->total_amount,
                                                2
                                            ) }}

                                        </strong>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Status --}}
                                {{-- ========================================= --}}

                                <td>

                                    @php

                                        $status = $workingSheet->status;

                                        $statusClass = match ($status) {

                                            'draft' =>
                                                'badge-secondary',

                                            'submitted' =>
                                                'badge-info',

                                            'approved' =>
                                                'badge-primary',

                                            'rejected' =>
                                                'badge-danger',

                                            'completed' =>
                                                'badge-success',

                                            default =>
                                                'badge-secondary',

                                        };


                                        $statusIcon = match ($status) {

                                            'draft' =>
                                                'fa-pencil',

                                            'submitted' =>
                                                'fa-paper-plane',

                                            'approved' =>
                                                'fa-check',

                                            'rejected' =>
                                                'fa-times',

                                            'completed' =>
                                                'fa-check-circle',

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

                                    @if(!empty($workingSheet->remarks))

                                        <div
                                            class="remarks-text"
                                            title="{{ $workingSheet->remarks }}">

                                            {{ \Illuminate\Support\Str::limit(
                                                $workingSheet->remarks,
                                                50
                                            ) }}

                                        </div>

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
                                            'working-sheets.edit',
                                            $workingSheet->id
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
                                            'working-sheets.destroy',
                                            $workingSheet->id
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
                                    colspan="19"
                                    class="text-center">

                                    No Working Sheets Found

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
    | Working Sheet Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();


            Swal.fire({

                title: 'Are you sure?',

                text: 'This working sheet will be moved to trash.',

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