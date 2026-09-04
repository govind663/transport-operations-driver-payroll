@extends('backend.layouts.master')

@section('title')
    Edit Vehicle Price
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ================================================================ --}}
        {{-- PAGE HEADER --}}
        {{-- ================================================================ --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>
                            Edit Vehicle Price
                        </h4>

                    </div>


                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>

                            </li>


                            <li class="breadcrumb-item">

                                <a href="{{ route('vehicle-management.index') }}">
                                    Vehicle Management
                                </a>

                            </li>


                            <li class="breadcrumb-item">

                                <a href="{{ route('vehicle-price.index') }}">
                                    Vehicle Price Management
                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                Edit Vehicle Price

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================================================================ --}}
        {{-- FORM --}}
        {{-- ================================================================ --}}

        <form
            action="{{ route(
                'vehicle-price.update',
                $vehiclePrice->id
            ) }}"
            method="POST">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ======================================================== --}}
                {{-- VEHICLE INFORMATION --}}
                {{-- ======================================================== --}}

                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Vehicle Information
                        </b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ==================================================== --}}
                    {{-- VEHICLE NUMBER --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Vehicle Number</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $vehiclePrice->vehicle->vehicle_number ?? '-' }}"
                                readonly>

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- REGISTRATION NUMBER --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Registration Number</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $vehiclePrice->vehicle->registration_number ?? '-' }}"
                                readonly>

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- VEHICLE CATEGORY --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Vehicle Category</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $vehiclePrice->vehicle->vehicleCategory->name ?? '-' }}"
                                readonly>

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- VEHICLE TYPE --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Vehicle Type</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $vehiclePrice->vehicle->vehicleType->name ?? '-' }}"
                                readonly>

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- MANUFACTURER --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Manufacturer</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $vehiclePrice->vehicle->manufacturer ?? '-' }}"
                                readonly>

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- MODEL --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Model</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $vehiclePrice->vehicle->model ?? '-' }}"
                                readonly>

                        </div>

                    </div>


                    {{-- ======================================================== --}}
                    {{-- PRICING INFORMATION --}}
                    {{-- ======================================================== --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Pricing Information
                            </b>

                        </h5>

                        <hr>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- PRICE --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>

                                    Vehicle Price

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>


                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <span class="input-group-text">
                                        ₹
                                    </span>

                                </div>


                                <input
                                    type="number"
                                    name="price"
                                    id="price"
                                    step="0.01"
                                    min="0"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old(
                                        'price',
                                        $vehiclePrice->price
                                    ) }}"
                                    placeholder="Enter vehicle price">


                            </div>


                            @error('price')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- EFFECTIVE DATE --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Effective Date
                                </b>

                            </label>


                            <input
                                type="date"
                                name="effective_date"
                                id="effective_date"
                                class="form-control @error('effective_date') is-invalid @enderror"
                                value="{{ old(
                                    'effective_date',
                                    $vehiclePrice->effective_date
                                        ? $vehiclePrice->effective_date->format('Y-m-d')
                                        : ''
                                ) }}">


                            @error('effective_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- REMARKS --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-12">

                        <div class="form-group">

                            <label>

                                <b>
                                    Remarks
                                </b>

                            </label>


                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter any additional remarks">{{ old(
                                    'remarks',
                                    $vehiclePrice->remarks
                                ) }}</textarea>


                            @error('remarks')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ======================================================== --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ======================================================== --}}

                    <div class="col-12">

                        <div class="text-right mt-4">


                            <a
                                href="{{ route('vehicle-price.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Vehicle Price

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>


    <x-backend.footer />

</div>

@endsection


@push('scripts')

<script>

/*
|--------------------------------------------------------------------------
| Price Validation
|--------------------------------------------------------------------------
*/

$('#price').on('input', function () {

    if (this.value < 0) {

        this.value = 0;

    }

});


/*
|--------------------------------------------------------------------------
| Remarks Formatting
|--------------------------------------------------------------------------
*/

$('#remarks').on('blur', function () {

    let value = $(this).val();

    value = value
        .replace(/\s+/g, ' ')
        .trim();

    $(this).val(value);

});

</script>

@endpush