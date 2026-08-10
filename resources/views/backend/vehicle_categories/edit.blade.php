@extends('backend.layouts.master')

@section('title')
    Edit Vehicle Category
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">
                        <h4>Edit Vehicle Category</h4>
                    </div>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('admin.dashboard') }}">

                                    Dashboard

                                </a>

                            </li>

                            <li class="breadcrumb-item">

                                <a href="{{ route('vehicle-categories.index') }}">

                                    Vehicle Categories

                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Edit Vehicle Category

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('vehicle-categories.update', $vehicleCategory->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ================= BASIC INFORMATION ================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>Vehicle Category Information</b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- Category Name --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Category Name
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $vehicleCategory->name) }}"
                                placeholder="Enter Vehicle Category Name">

                            @error('name')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Category Code --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Category Code
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="text"
                                name="code"
                                id="code"
                                class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code', $vehicleCategory->code) }}"
                                placeholder="Enter Category Code">

                            <small class="text-muted">

                                Unique code for the vehicle category.

                            </small>

                            @error('code')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>

                                <b>
                                    Description
                                </b>

                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter vehicle category description">{{ old('description', $vehicleCategory->description) }}</textarea>

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
                                    {{ old('status', $vehicleCategory->status) == 1 ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option
                                    value="0"
                                    {{ old('status', $vehicleCategory->status) == 0 ? 'selected' : '' }}>

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
                                href="{{ route('vehicle-categories.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Vehicle Category

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
| Category Name Formatting
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
| Category Code Formatting
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