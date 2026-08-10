@extends('backend.layouts.master')

@section('title')
    Edit Vehicle Type
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">
                        <h4>Edit Vehicle Type</h4>
                    </div>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('admin.dashboard') }}">

                                    Dashboard

                                </a>

                            </li>

                            <li class="breadcrumb-item">

                                <a href="{{ route('vehicle-types.index') }}">

                                    Vehicle Types

                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Edit Vehicle Type

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('vehicle-types.update', $vehicleType->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">

                {{-- ================= VEHICLE TYPE INFORMATION ================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>Vehicle Type Information</b>

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
                                        {{ old(
                                            'vehicle_category_id',
                                            $vehicleType->vehicle_category_id
                                        ) == $vehicleCategory->id ? 'selected' : '' }}>

                                        {{ $vehicleCategory->name }}

                                        @if($vehicleCategory->code)

                                            ({{ $vehicleCategory->code }})

                                        @endif

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


                    {{-- Vehicle Type Name --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Vehicle Type Name
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old(
                                    'name',
                                    $vehicleType->name
                                ) }}"
                                placeholder="Enter Vehicle Type Name">

                            @error('name')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Vehicle Type Code --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Vehicle Type Code
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="text"
                                name="code"
                                id="code"
                                class="form-control @error('code') is-invalid @enderror"
                                value="{{ old(
                                    'code',
                                    $vehicleType->code
                                ) }}"
                                placeholder="Enter Vehicle Type Code">

                            <small class="text-muted">

                                Unique code for the vehicle type.

                            </small>

                            @error('code')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Description
                                </b>

                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter vehicle type description">{{ old(
                                    'description',
                                    $vehicleType->description
                                ) }}</textarea>

                            @error('description')

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
                                    value="1"
                                    {{ old(
                                        'status',
                                        $vehicleType->status
                                    ) == 1 ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option
                                    value="0"
                                    {{ old(
                                        'status',
                                        $vehicleType->status
                                    ) == 0 ? 'selected' : '' }}>

                                    Inactive

                                </option>

                            </select>

                            @error('status')

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
                                href="{{ route('vehicle-types.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Vehicle Type

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
| Vehicle Type Name Formatting
|--------------------------------------------------------------------------
*/
$('#name').on('blur', function () {

    let value = $(this).val();

    value = value
        .replace(/\s+/g, ' ')
        .trim();

    $(this).val(value);

});


/*
|--------------------------------------------------------------------------
| Vehicle Type Code Formatting
|--------------------------------------------------------------------------
*/
$('#code').on('input', function () {

    this.value = this.value
        .toUpperCase()
        .replace(/\s+/g, '_')
        .replace(/[^A-Z0-9_-]/g, '');

});

</script>

@endpush