@extends('backend.layouts.master')

@section('title')

    @if($isDriver)

        My Salary Processing

    @else

        Salary Processing

    @endif

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

    .driver-code {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .amount-text {
        font-weight: 600;
    }

    .salary-period {
        font-weight: 600;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 6px;
    }

    .processing-code {
        font-weight: 600;
        letter-spacing: 0.3px;
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

                {{-- ================================================= --}}
                {{-- PAGE TITLE --}}
                {{-- ================================================= --}}

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">

                        @if($isDriver)

                            My Salary Processing

                        @else

                            Salary Processing

                        @endif

                    </h4>

                    <p class="mb-0">

                        @if($isDriver)

                            View your salary processing records,
                            attendance and salary calculation details.

                        @else

                            Manage driver salary processing,
                            attendance and salary calculations.

                        @endif

                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- ADD SALARY PROCESSING --}}
                {{-- ================================================= --}}

                @if(!$isDriver)

                    <div class="col-md-6 col-sm-12 text-right">

                        <a
                            href="{{ route('salary-processing.create') }}"
                            class="btn btn-primary"
                        >

                            <i class="fa fa-plus"></i>

                            Add Salary Processing

                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTER CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">

            <div class="pd-20">

                <h4 class="text-blue h4 mb-20">

                    Salary Processing Filters

                </h4>


                <form
                    method="GET"
                    action="{{ route('salary-processing.index') }}"
                    class="form-horizontal"
                    style="border: 1px solid #023a85; padding: 20px; border-radius: 6px;"
                >

                    <div class="row">


                        {{-- ================================================= --}}
                        {{-- SEARCH --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label>

                                Search

                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Processing no, driver name or code"
                            >

                        </div>


                        {{-- ================================================= --}}
                        {{-- DRIVER --}}
                        {{-- ================================================= --}}

                        @if(!$isDriver)

                            <div class="col-md-3 col-sm-6 mb-20">

                                <label>

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
                                            {{ request('driver_id') == $driver->id ? 'selected' : '' }}
                                        >

                                            {{ $driver->first_name }} {{ $driver->last_name }}

                                            @if(!empty($driver->driver_code))

                                                -
                                                {{ $driver->driver_code }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- SALARY MONTH --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Salary Month

                            </label>

                            <select
                                name="salary_month"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Months

                                </option>


                                @for($month = 1; $month <= 12; $month++)

                                    <option
                                        value="{{ $month }}"
                                        {{ (string) request('salary_month') === (string) $month ? 'selected' : '' }}
                                    >

                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}

                                    </option>

                                @endfor

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SALARY YEAR --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Salary Year

                            </label>

                            <select
                                name="salary_year"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Years

                                </option>


                                @php

                                    $currentYear = now()->year;

                                @endphp


                                @for($year = $currentYear - 5; $year <= $currentYear + 1; $year++)

                                    <option
                                        value="{{ $year }}"
                                        {{ (string) request('salary_year') === (string) $year ? 'selected' : '' }}
                                    >

                                        {{ $year }}

                                    </option>

                                @endfor

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- STATUS --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Status

                            </label>

                            <select
                                name="status"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Status

                                </option>


                                @foreach($statuses as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}
                                    >

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $status
                                            )
                                        ) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary mr-2"
                            >

                                <i class="fa fa-search"></i>

                                Filter

                            </button>


                            <a
                                href="{{ route('salary-processing.index') }}"
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
        {{-- SALARY PROCESSING LIST --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        @if($isDriver)

                            My Salary Processing

                        @else

                            All Salary Processing

                        @endif

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $salaryProcessings->total() }}

                    </span>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <div class="table-responsive">

                    <table
                        class="table hover multiple-select-row data-table-export1 nowrap p-3"
                        data-title="Salary Processing"
                    >

                        {{-- ================================================= --}}
                        {{-- TABLE HEADER --}}
                        {{-- ================================================= --}}

                        <thead>

                            <tr>

                                <th class="text-wrap">

                                    Sr. No.

                                </th>


                                @if(!$isDriver)

                                    <th class="text-wrap">

                                        Driver

                                    </th>

                                @endif


                                <th class="text-wrap">

                                    Processing No.

                                </th>


                                <th class="text-wrap">

                                    Salary Period

                                </th>


                                <th class="text-wrap">

                                    Working Days

                                </th>


                                <th class="text-wrap">

                                    Present Days

                                </th>


                                <th class="text-wrap">

                                    Absent Days

                                </th>


                                <th class="text-wrap">

                                    Paid Days

                                </th>


                                <th class="text-wrap">

                                    Gross Salary

                                </th>


                                <th class="text-wrap">

                                    Total Deductions

                                </th>


                                <th class="text-wrap">

                                    Net Salary

                                </th>


                                <th class="text-wrap">

                                    Status

                                </th>


                                <th class="text-wrap">

                                    Processed By

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

                            @forelse($salaryProcessings as $key => $processing)

                                <tr>


                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{
                                            ($salaryProcessings->currentPage() - 1)
                                            * $salaryProcessings->perPage()
                                            + $key
                                            + 1
                                        }}

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}

                                    @if(!$isDriver)

                                        <td>

                                            @if($processing->driver)

                                                <strong class="text-dark">

                                                    {{ $processing->driver->name ?? '-' }}

                                                </strong>


                                                @if(!empty($processing->driver->driver_code))

                                                    <small class="text-muted d-block mt-1">

                                                        <i class="fa fa-id-card"></i>

                                                        {{ $processing->driver->driver_code }}

                                                    </small>

                                                @endif


                                                @if(!empty($processing->driver->mobile))

                                                    <small class="text-muted d-block">

                                                        <i class="fa fa-phone"></i>

                                                        {{ $processing->driver->mobile }}

                                                    </small>

                                                @endif

                                            @else

                                                -

                                            @endif

                                        </td>

                                    @endif


                                    {{-- ===================================== --}}
                                    {{-- PROCESSING NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($processing->processing_no))

                                            <strong class="processing-code text-dark">

                                                {{ $processing->processing_no }}

                                            </strong>

                                        @elseif(!empty($processing->process_no))

                                            <strong class="processing-code text-dark">

                                                {{ $processing->process_no }}

                                            </strong>

                                        @elseif(!empty($processing->code))

                                            <strong class="processing-code text-dark">

                                                {{ $processing->code }}

                                            </strong>

                                        @else

                                            <span class="text-muted">

                                                -

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- SALARY PERIOD --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(
                                            !empty($processing->salary_month) &&
                                            !empty($processing->salary_year)
                                        )

                                            <strong class="salary-period">

                                                {{
                                                    \Carbon\Carbon::create(
                                                        $processing->salary_year,
                                                        $processing->salary_month,
                                                        1
                                                    )->format('F Y')
                                                }}

                                            </strong>

                                            @if(
                                                $processing->period_from &&
                                                $processing->period_to
                                            )

                                                <small class="text-muted d-block mt-1">

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $processing->period_from
                                                        )->format('d-m-Y')
                                                    }}

                                                    -

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $processing->period_to
                                                        )->format('d-m-Y')
                                                    }}

                                                </small>

                                            @endif

                                        @elseif(
                                            $processing->period_from &&
                                            $processing->period_to
                                        )

                                            <strong>

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $processing->period_from
                                                    )->format('d-m-Y')
                                                }}

                                                -

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $processing->period_to
                                                    )->format('d-m-Y')
                                                }}

                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- WORKING DAYS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $workingDays =
                                                $processing->total_working_days
                                                ?? $processing->working_days
                                                ?? 0;

                                        @endphp

                                        <span class="badge badge-info">

                                            {{
                                                number_format(
                                                    (float) $workingDays,
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- PRESENT DAYS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="badge badge-success">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->present_days
                                                        ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- ABSENT DAYS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="badge badge-danger">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->absent_days
                                                        ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- PAID DAYS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="badge badge-primary">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->paid_days
                                                        ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- GROSS SALARY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="amount-text">

                                            ₹

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->gross_salary
                                                        ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- TOTAL DEDUCTIONS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="amount-text text-danger">

                                            ₹

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->total_deductions
                                                        ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- NET SALARY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="amount-text text-success">

                                            ₹

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->net_salary
                                                        ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </strong>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $status =
                                                $processing->status;

                                            $statusClass = match ($status) {

                                                'draft' =>
                                                    'badge-secondary',

                                                'processing' =>
                                                    'badge-warning',

                                                'generated' =>
                                                    'badge-info',

                                                'completed' =>
                                                    'badge-success',

                                                'approved' =>
                                                    'badge-primary',

                                                'cancelled' =>
                                                    'badge-danger',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match ($status) {

                                                'draft' =>
                                                    'fa-file-text-o',

                                                'processing' =>
                                                    'fa-spinner',

                                                'generated' =>
                                                    'fa-cogs',

                                                'completed' =>
                                                    'fa-check-circle',

                                                'approved' =>
                                                    'fa-check',

                                                'cancelled' =>
                                                    'fa-times-circle',

                                                default =>
                                                    'fa-info-circle',

                                            };

                                        @endphp


                                        <span
                                            class="badge {{ $statusClass }} badge-pill px-3 py-2"
                                        >

                                            <i class="fa {{ $statusIcon }}"></i>

                                            {{
                                                ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $status ?? 'Unknown'
                                                    )
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- PROCESSED BY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($processing->processedBy)

                                            {{ $processing->processedBy->name }}

                                        @elseif($processing->createdBy)

                                            {{ $processing->createdBy->name }}

                                        @elseif($processing->generatedBy)

                                            {{ $processing->generatedBy->name }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- EDIT --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(!$isDriver)

                                            <a
                                                href="{{ route(
                                                    'salary-processing.edit',
                                                    $processing->id
                                                ) }}"
                                                class="btn btn-warning btn-sm"
                                            >

                                                <i class="dw dw-pencil-1"></i>

                                                Edit

                                            </a>

                                        @else

                                            <a
                                                href="{{ route(
                                                    'salary-processing.show',
                                                    $processing->id
                                                ) }}"
                                                class="btn btn-info btn-sm"
                                            >

                                                <i class="fa fa-eye"></i>

                                                View

                                            </a>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DELETE --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(!$isDriver)

                                            <form
                                                action="{{ route(
                                                    'salary-processing.destroy',
                                                    $processing->id
                                                ) }}"
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

                                        @else

                                            -

                                        @endif

                                    </td>

                                </tr>


                            @empty

                                {{-- ========================================= --}}
                                {{-- NO DATA --}}
                                {{-- ========================================= --}}

                                <tr>

                                    <td
                                        colspan="{{ $isDriver ? 14 : 15 }}"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i
                                                class="fa fa-calculator fa-2x text-muted mb-3"
                                            ></i>


                                            <h5 class="text-muted">

                                                @if($isDriver)

                                                    No Salary Processing Found

                                                @else

                                                    No Salary Processing Found

                                                @endif

                                            </h5>


                                            <p class="text-muted mb-0">

                                                No salary processing records
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

                @if($salaryProcessings->hasPages())

                    <div class="pd-20">

                        {{ $salaryProcessings->links() }}

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Salary Processing Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (e) {

                e.preventDefault();


                Swal.fire({

                    title: 'Are you sure?',

                    text: 'This salary processing record will be moved to trash.',

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