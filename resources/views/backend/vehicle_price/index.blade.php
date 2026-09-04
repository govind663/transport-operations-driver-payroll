@extends('backend.layouts.master')

@section('title')
    Vehicle Price Management
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>
    .vehicle-price {
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .vehicle-number {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .price-badge {
        font-weight: 600;
        font-size: 14px;
    }
</style>

@endpush

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================================================================ --}}
        {{-- PAGE HEADER --}}
        {{-- ================================================================ --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">
                        Vehicle Price Management
                    </h4>

                    <p class="mb-0">
                        Manage vehicle prices and pricing history.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a
                        href="{{ route('vehicle-price.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add Vehicle Price

                    </a>

                </div>

            </div>

        </div>


        {{-- ================================================================ --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ================================================================ --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <strong>
                    Success!
                </strong>

                {{ session('success') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>

        @endif


        {{-- ================================================================ --}}
        {{-- ERROR MESSAGE --}}
        {{-- ================================================================ --}}

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>
                    Error!
                </strong>

                {{ session('error') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>

        @endif


        {{-- ================================================================ --}}
        {{-- CARD --}}
        {{-- ================================================================ --}}

        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Vehicle Prices

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $prices->count() }}

                    </span>

                </div>

            </div>


            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Vehicle Price Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Vehicle No.</th>

                            <th>Category</th>

                            <th>Vehicle Type</th>

                            <th>Manufacturer</th>

                            <th>Model</th>

                            <th>Price</th>

                            <th>Effective Date</th>

                            <th>Remarks</th>

                            <th class="no-export">
                                View
                            </th>

                            <th class="no-export">
                                Edit
                            </th>

                            <th class="no-export">
                                Delete
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($prices as $key => $vehiclePrice)

                            <tr>

                                {{-- ================================================= --}}
                                {{-- Sr No --}}
                                {{-- ================================================= --}}

                                <td>

                                    {{ $key + 1 }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- Vehicle Number --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($vehiclePrice->vehicle)

                                        <strong class="vehicle-number">

                                            {{ $vehiclePrice->vehicle->vehicle_number }}

                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Category --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if(
                                        $vehiclePrice->vehicle &&
                                        $vehiclePrice->vehicle->vehicleCategory
                                    )

                                        <span class="badge badge-info">

                                            {{
                                                $vehiclePrice
                                                    ->vehicle
                                                    ->vehicleCategory
                                                    ->name
                                            }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Vehicle Type --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if(
                                        $vehiclePrice->vehicle &&
                                        $vehiclePrice->vehicle->vehicleType
                                    )

                                        <strong>

                                            {{
                                                $vehiclePrice
                                                    ->vehicle
                                                    ->vehicleType
                                                    ->name
                                            }}

                                        </strong>

                                        @if(
                                            $vehiclePrice
                                                ->vehicle
                                                ->vehicleType
                                                ->code
                                        )

                                            <br>

                                            <small class="text-muted">

                                                {{
                                                    $vehiclePrice
                                                        ->vehicle
                                                        ->vehicleType
                                                        ->code
                                                }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Manufacturer --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($vehiclePrice->vehicle)

                                        {{
                                            $vehiclePrice
                                                ->vehicle
                                                ->manufacturer
                                                ?? '-'
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Model --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($vehiclePrice->vehicle)

                                        {{
                                            $vehiclePrice
                                                ->vehicle
                                                ->model
                                                ?? '-'
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Price --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="badge badge-success price-badge">

                                        ₹
                                        {{
                                            number_format(
                                                (float) $vehiclePrice->price,
                                                2
                                            )
                                        }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- Effective Date --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($vehiclePrice->effective_date)

                                        {{
                                            $vehiclePrice
                                                ->effective_date
                                                ->format('d-m-Y')
                                        }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Remarks --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($vehiclePrice->remarks)

                                        <span
                                            title="{{ $vehiclePrice->remarks }}">

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $vehiclePrice->remarks,
                                                    40
                                                )
                                            }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- View --}}
                                {{-- ================================================= --}}

                                <td class="no-export">

                                    <a
                                        href="{{ route(
                                            'vehicle-price.show',
                                            $vehiclePrice->id
                                        ) }}"
                                        class="btn btn-info btn-sm">

                                        <i class="fa fa-eye"></i>

                                        View

                                    </a>

                                </td>


                                {{-- ================================================= --}}
                                {{-- Edit --}}
                                {{-- ================================================= --}}

                                <td class="no-export">

                                    <a
                                        href="{{ route(
                                            'vehicle-price.edit',
                                            $vehiclePrice->id
                                        ) }}"
                                        class="btn btn-warning btn-sm">

                                        <i class="dw dw-pencil-1"></i>

                                        Edit

                                    </a>

                                </td>


                                {{-- ================================================= --}}
                                {{-- Delete --}}
                                {{-- ================================================= --}}

                                <td class="no-export">

                                    <form
                                        action="{{ route(
                                            'vehicle-price.destroy',
                                            $vehiclePrice->id
                                        ) }}"
                                        method="POST"
                                        class="delete-price-form">

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

                            <tr>

                                <td
                                    colspan="12"
                                    class="text-center">

                                    No Vehicle Prices Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <x-backend.footer />

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

/*
|--------------------------------------------------------------------------
| Delete Vehicle Price Confirmation
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function () {

        document
            .querySelectorAll('.delete-price-form')
            .forEach(function (form) {

                form.addEventListener(
                    'submit',
                    function (e) {

                        e.preventDefault();


                        Swal.fire({

                            title: 'Are you sure?',

                            text:
                                'This vehicle price will be deleted!',

                            icon: 'warning',

                            showCancelButton: true,

                            confirmButtonColor: '#d33',

                            cancelButtonColor: '#6c757d',

                            confirmButtonText:
                                'Yes, Delete',

                            cancelButtonText:
                                'Cancel',

                            reverseButtons: true

                        }).then(function (result) {

                            if (result.isConfirmed) {

                                form.submit();

                            }

                        });

                    }
                );

            });

    }
);

</script>


<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush