@extends('backend.layouts.master')

@section('title')
    Create Vehicle
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">
                        <h4>Create New Vehicle</h4>
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

                            <li class="breadcrumb-item active">
                                Create Vehicle
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('vehicle-management.store') }}"
            method="POST">

            @csrf

            <div class="card-box pd-20 mb-30">


                {{-- ========================================================= --}}
                {{-- VEHICLE INFORMATION --}}
                {{-- ========================================================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>Vehicle Information</b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- Vehicle Category --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Vehicle Category
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <select
                                name="vehicle_category_id"
                                id="vehicle_category_id"
                                class="form-control custom-select2 @error('vehicle_category_id') is-invalid @enderror">

                                <option value="">
                                    Select Vehicle Category
                                </option>

                                @foreach($vehicleCategories as $vehicleCategory)

                                    <option
                                        value="{{ $vehicleCategory->id }}"
                                        {{ old('vehicle_category_id') == $vehicleCategory->id ? 'selected' : '' }}>

                                        {{ $vehicleCategory->name }}

                                        {{-- @if($vehicleCategory->code)
                                            ({{ $vehicleCategory->code }})
                                        @endif --}}

                                    </option>

                                @endforeach

                            </select>

                            @error('vehicle_category_id')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Vehicle Type --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Vehicle Type
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <select
                                name="vehicle_type_id"
                                id="vehicle_type_id"
                                class="form-control custom-select2 @error('vehicle_type_id') is-invalid @enderror">

                                <option value="">
                                    Select Vehicle Type
                                </option>

                                @foreach($vehicleTypes as $vehicleType)

                                    <option
                                        value="{{ $vehicleType->id }}"
                                        data-category="{{ $vehicleType->vehicle_category_id }}"
                                        {{ old('vehicle_type_id') == $vehicleType->id ? 'selected' : '' }}>

                                        {{ $vehicleType->name }}

                                        {{-- @if($vehicleType->code)
                                            ({{ $vehicleType->code }})
                                        @endif --}}

                                    </option>

                                @endforeach

                            </select>

                            @error('vehicle_type_id')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Vehicle Number --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Vehicle Number
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="text"
                                name="vehicle_number"
                                id="vehicle_number"
                                class="form-control @error('vehicle_number') is-invalid @enderror"
                                value="{{ old('vehicle_number') }}"
                                placeholder="e.g. MH04AB1234">

                            <small class="text-muted">
                                Internal / fleet vehicle number.
                            </small>

                            @error('vehicle_number')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Registration Number --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Registration Number
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="text"
                                name="registration_number"
                                id="registration_number"
                                class="form-control @error('registration_number') is-invalid @enderror"
                                value="{{ old('registration_number') }}"
                                placeholder="e.g. MH04AB1234">

                            @error('registration_number')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Chassis Number --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Chassis Number</b>
                            </label>

                            <input
                                type="text"
                                name="chassis_number"
                                id="chassis_number"
                                class="form-control @error('chassis_number') is-invalid @enderror"
                                value="{{ old('chassis_number') }}"
                                placeholder="Enter Chassis Number">

                            @error('chassis_number')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Engine Number --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Engine Number</b>
                            </label>

                            <input
                                type="text"
                                name="engine_number"
                                id="engine_number"
                                class="form-control @error('engine_number') is-invalid @enderror"
                                value="{{ old('engine_number') }}"
                                placeholder="Enter Engine Number">

                            @error('engine_number')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- VEHICLE SPECIFICATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Vehicle Specification</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Manufacturer --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Manufacturer</b>
                            </label>

                            <input
                                type="text"
                                name="manufacturer"
                                id="manufacturer"
                                class="form-control @error('manufacturer') is-invalid @enderror"
                                value="{{ old('manufacturer') }}"
                                placeholder="e.g. Tata">

                            @error('manufacturer')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Model --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Model</b>
                            </label>

                            <input
                                type="text"
                                name="model"
                                id="model"
                                class="form-control @error('model') is-invalid @enderror"
                                value="{{ old('model') }}"
                                placeholder="e.g. Ace Gold">

                            @error('model')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Manufacturing Year --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Manufacturing Year</b>
                            </label>

                            <input
                                type="number"
                                name="manufacturing_year"
                                id="manufacturing_year"
                                min="1900"
                                max="{{ date('Y') + 1 }}"
                                class="form-control @error('manufacturing_year') is-invalid @enderror"
                                value="{{ old('manufacturing_year') }}"
                                placeholder="e.g. {{ date('Y') }}">

                            @error('manufacturing_year')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Color --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Color</b>
                            </label>

                            <input
                                type="text"
                                name="color"
                                id="color"
                                class="form-control @error('color') is-invalid @enderror"
                                value="{{ old('color') }}"
                                placeholder="e.g. White">

                            @error('color')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Capacity --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Capacity</b>
                            </label>

                            <input
                                type="number"
                                name="capacity"
                                id="capacity"
                                step="0.01"
                                min="0"
                                class="form-control @error('capacity') is-invalid @enderror"
                                value="{{ old('capacity') }}"
                                placeholder="e.g. 1000">

                            @error('capacity')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Capacity Unit --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Capacity Unit</b>
                            </label>

                            <select
                                name="capacity_unit"
                                class="form-control custom-select2 @error('capacity_unit') is-invalid @enderror">

                                <option value="">
                                    Select Capacity Unit
                                </option>

                                <option
                                    value="kg"
                                    {{ old('capacity_unit') == 'kg' ? 'selected' : '' }}>
                                    KG
                                </option>

                                <option
                                    value="ton"
                                    {{ old('capacity_unit') == 'ton' ? 'selected' : '' }}>
                                    Ton
                                </option>

                                <option
                                    value="litre"
                                    {{ old('capacity_unit') == 'litre' ? 'selected' : '' }}>
                                    Litre
                                </option>

                                <option
                                    value="cubic_meter"
                                    {{ old('capacity_unit') == 'cubic_meter' ? 'selected' : '' }}>
                                    Cubic Meter
                                </option>

                                <option
                                    value="other"
                                    {{ old('capacity_unit') == 'other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                            @error('capacity_unit')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- STATUS --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Status</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Status
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <select
                                name="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                <option
                                    value="active"
                                    {{ old('status', 'active') == 'active' ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option
                                    value="inactive"
                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>

                                    Inactive

                                </option>

                                <option
                                    value="maintenance"
                                    {{ old('status') == 'maintenance' ? 'selected' : '' }}>

                                    Maintenance

                                </option>

                            </select>

                            @error('status')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>
                                <b>Remarks</b>
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter any additional remarks">{{ old('remarks') }}</textarea>

                            @error('remarks')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ========================================================= --}}
                    <div class="col-12">

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('vehicle-management.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Save Vehicle

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
| Vehicle Number Formatting
|--------------------------------------------------------------------------
*/
$('#vehicle_number, #registration_number').on('input', function () {

    this.value = this.value
        .toUpperCase()
        .replace(/\s+/g, '');

});


/*
|--------------------------------------------------------------------------
| Chassis / Engine Number Formatting
|--------------------------------------------------------------------------
*/
$('#chassis_number, #engine_number').on('input', function () {

    this.value = this.value
        .toUpperCase()
        .replace(/\s+/g, '');

});


/*
|--------------------------------------------------------------------------
| Text Field Formatting
|--------------------------------------------------------------------------
*/
$('#manufacturer, #model, #color').on('blur', function () {

    let value = $(this).val();

    value = value
        .replace(/\s+/g, ' ')
        .trim();

    $(this).val(value);

});


/*
|--------------------------------------------------------------------------
| Vehicle Type Filter By Category
|--------------------------------------------------------------------------
*/
function filterVehicleTypes()
{
    let categoryId = $('#vehicle_category_id').val();

    let vehicleTypeSelect = $('#vehicle_type_id');

    vehicleTypeSelect.find('option').each(function () {

        let option = $(this);

        if (!option.val()) {
            option.show();
            return;
        }

        if (option.data('category') == categoryId) {

            option.show();

        } else {

            option.hide();

        }

    });

    if (
        vehicleTypeSelect.find(
            'option:selected'
        ).data('category') != categoryId
    ) {

        vehicleTypeSelect.val('');

    }

    vehicleTypeSelect.trigger('change');
}


/*
|--------------------------------------------------------------------------
| Category Change
|--------------------------------------------------------------------------
*/
$('#vehicle_category_id').on('change', function () {

    filterVehicleTypes();

});


/*
|--------------------------------------------------------------------------
| Initial Vehicle Type Filter
|--------------------------------------------------------------------------
*/
$(document).ready(function () {

    filterVehicleTypes();

});

</script>

@endpush