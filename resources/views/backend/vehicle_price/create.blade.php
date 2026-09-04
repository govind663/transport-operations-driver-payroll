@extends('backend.layouts.master')

@section('title')
    Add Vehicle Price
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
                            Add Vehicle Price
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

                                Add Vehicle Price

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
            action="{{ route('vehicle-price.store') }}"
            method="POST">

            @csrf


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
                    {{-- VEHICLE --}}
                    {{-- ==================================================== --}}

                    <div class="col-md-12">

                        <div class="form-group">

                            <label>

                                <b>

                                    Vehicle

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>


                            <select
                                name="vehicle_id"
                                id="vehicle_id"
                                class="form-control custom-select2 @error('vehicle_id') is-invalid @enderror">

                                <option value="">
                                    Select Vehicle
                                </option>


                                @foreach($vehicles as $vehicle)

                                    <option
                                        value="{{ $vehicle->id }}"
                                        {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>

                                        {{ $vehicle->vehicle_number }}

                                        @if($vehicle->registration_number)

                                            - {{ $vehicle->registration_number }}

                                        @endif

                                        @if($vehicle->manufacturer)

                                            - {{ $vehicle->manufacturer }}

                                        @endif

                                        @if($vehicle->model)

                                            {{ $vehicle->model }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            <small class="text-muted">

                                Select the vehicle for which the price is being added.

                            </small>


                            @error('vehicle_id')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- VEHICLE PREVIEW --}}
                    {{-- ==================================================== --}}

                    <div
                        class="col-md-12"
                        id="vehiclePreview"
                        style="display:none;">

                        <div class="alert alert-light border">

                            <div class="row">

                                <div class="col-md-4">

                                    <strong>
                                        Vehicle Number:
                                    </strong>

                                    <span id="previewVehicleNumber">
                                        -
                                    </span>

                                </div>


                                <div class="col-md-4">

                                    <strong>
                                        Category:
                                    </strong>

                                    <span id="previewCategory">
                                        -
                                    </span>

                                </div>


                                <div class="col-md-4">

                                    <strong>
                                        Vehicle Type:
                                    </strong>

                                    <span id="previewType">
                                        -
                                    </span>

                                </div>

                            </div>


                            <div class="row mt-2">

                                <div class="col-md-4">

                                    <strong>
                                        Manufacturer:
                                    </strong>

                                    <span id="previewManufacturer">
                                        -
                                    </span>

                                </div>


                                <div class="col-md-4">

                                    <strong>
                                        Model:
                                    </strong>

                                    <span id="previewModel">
                                        -
                                    </span>

                                </div>


                                <div class="col-md-4">

                                    <strong>
                                        Registration No.:
                                    </strong>

                                    <span id="previewRegistration">
                                        -
                                    </span>

                                </div>

                            </div>

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
                                    value="{{ old('price') }}"
                                    placeholder="Enter vehicle price">


                            </div>


                            <small class="text-muted">

                                Enter the vehicle price in Indian Rupees.

                            </small>


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
                                value="{{ old('effective_date', date('Y-m-d')) }}">


                            <small class="text-muted">

                                Date from which this price becomes applicable.

                            </small>


                            @error('effective_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ======================================================== --}}
                    {{-- REMARKS --}}
                    {{-- ======================================================== --}}

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
                                placeholder="Enter any additional remarks">{{ old('remarks') }}</textarea>


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

                                Save Vehicle Price

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
| Vehicle Data
|--------------------------------------------------------------------------
*/

const vehicles = {

    @foreach($vehicles as $vehicle)

        "{{ $vehicle->id }}": {

            vehicle_number:
                @json($vehicle->vehicle_number),

            registration_number:
                @json($vehicle->registration_number),

            manufacturer:
                @json($vehicle->manufacturer),

            model:
                @json($vehicle->model),

            category:
                @json(optional($vehicle->vehicleCategory)->name),

            type:
                @json(optional($vehicle->vehicleType)->name)

        },

    @endforeach

};


/*
|--------------------------------------------------------------------------
| Vehicle Selection Preview
|--------------------------------------------------------------------------
*/

$('#vehicle_id').on('change', function () {

    const vehicleId = $(this).val();

    const preview = $('#vehiclePreview');


    if (!vehicleId || !vehicles[vehicleId]) {

        preview.hide();

        return;

    }


    const vehicle = vehicles[vehicleId];


    $('#previewVehicleNumber').text(
        vehicle.vehicle_number || '-'
    );


    $('#previewCategory').text(
        vehicle.category || '-'
    );


    $('#previewType').text(
        vehicle.type || '-'
    );


    $('#previewManufacturer').text(
        vehicle.manufacturer || '-'
    );


    $('#previewModel').text(
        vehicle.model || '-'
    );


    $('#previewRegistration').text(
        vehicle.registration_number || '-'
    );


    preview.show();

});


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
| Initialize Selected Vehicle
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    $('#vehicle_id').trigger('change');

});

</script>

@endpush