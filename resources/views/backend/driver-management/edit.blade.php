@extends('backend.layouts.master')

@section('title')
    Edit Driver
@endsection

@push('styles')
<style>
    .table-bordered, .table-bordered td, .table-bordered th {
        border: 2px solid #023a85;
    }

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

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>
                            Edit Driver
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

                                <a href="{{ route('driver-management.index') }}">
                                    Driver Management
                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Edit Driver

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('driver-management.update', $driver->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            @method('PUT')

            <input type="hidden" name="driver_id" value="{{ $driver->id }}">

            <div class="card-box pd-20 mb-30">

                {{-- Section Heading --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Driver Basic Information
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="row">


                    {{-- ========================================================= --}}
                    {{-- DRIVER CODE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="driver_code">

                                <b>

                                    Driver Code

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="driver_code"
                                id="driver_code"
                                class="form-control @error('driver_code') is-invalid @enderror"
                                value="{{ old('driver_code', $driver->driver_code) }}"
                                placeholder="Enter Driver Code (e.g. DRV001)"
                                maxlength="30"
                                autocomplete="off">

                            <small class="text-muted">

                                Unique Driver Code

                                (Example: DRV001, DRV002)

                            </small>

                            @error('driver_code')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- DRIVER TYPE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="driver_type">

                                <b>

                                    Driver Type

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <select
                                name="driver_type"
                                id="driver_type"
                                class="form-control custom-select2 @error('driver_type') is-invalid @enderror">

                                <option value="">
                                    Select Driver Type
                                </option>

                                @foreach(\App\Models\Driver::DRIVER_TYPES as $driverType)

                                    <option
                                        value="{{ $driverType }}"
                                        {{ old('driver_type', $driver->driver_type) === $driverType ? 'selected' : '' }}>

                                        @if(
                                            $driverType ===
                                            \App\Models\Driver::DRIVER_FIXED_DUTY
                                        )

                                            Fixed Duty Driver

                                        @elseif(
                                            $driverType ===
                                            \App\Models\Driver::DRIVER_GENERAL_DUTY
                                        )

                                            General Duty Driver

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('driver_type')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- FIRST NAME --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="first_name">

                                <b>

                                    First Name

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="first_name"
                                id="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name', $driver->first_name) }}"
                                placeholder="Enter First Name"
                                maxlength="100"
                                autocomplete="off">

                            @error('first_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- LAST NAME --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="last_name">

                                <b>
                                    Last Name
                                </b>

                            </label>

                            <input
                                type="text"
                                name="last_name"
                                id="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $driver->last_name) }}"
                                placeholder="Enter Last Name"
                                maxlength="100"
                                autocomplete="off">

                            @error('last_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- FATHER NAME --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="father_name">

                                <b>

                                    Father Name

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="father_name"
                                id="father_name"
                                class="form-control @error('father_name') is-invalid @enderror"
                                value="{{ old('father_name', $driver->father_name) }}"
                                placeholder="Enter Father Name"
                                maxlength="150"
                                autocomplete="off">

                            @error('father_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- DATE OF BIRTH --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="date_of_birth">

                                <b>
                                    Date of Birth
                                </b>

                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                id="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old(
                                    'date_of_birth',
                                    optional($driver->date_of_birth)->format('Y-m-d')
                                ) }}">

                            @error('date_of_birth')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- GENDER --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="gender">

                                <b>
                                    Gender
                                </b>

                            </label>

                            <select
                                name="gender"
                                id="gender"
                                class="form-control custom-select2 @error('gender') is-invalid @enderror">

                                <option value="">
                                    Select Gender
                                </option>

                                <option
                                    value="male"
                                    {{ old('gender', $driver->gender) === 'male'
                                        ? 'selected'
                                        : '' }}>

                                    Male

                                </option>

                                <option
                                    value="female"
                                    {{ old('gender', $driver->gender) === 'female'
                                        ? 'selected'
                                        : '' }}>

                                    Female

                                </option>

                                <option
                                    value="other"
                                    {{ old('gender', $driver->gender) === 'other'
                                        ? 'selected'
                                        : '' }}>

                                    Other

                                </option>

                            </select>

                            @error('gender')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- MARITAL STATUS --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="marital_status">

                                <b>
                                    Marital Status
                                </b>

                            </label>

                            <select
                                name="marital_status"
                                id="marital_status"
                                class="form-control custom-select2 @error('marital_status') is-invalid @enderror">

                                <option value="">
                                    Select Marital Status
                                </option>

                                <option
                                    value="single"
                                    {{ old(
                                        'marital_status',
                                        $driver->marital_status
                                    ) === 'single'
                                        ? 'selected'
                                        : '' }}>

                                    Single

                                </option>

                                <option
                                    value="married"
                                    {{ old(
                                        'marital_status',
                                        $driver->marital_status
                                    ) === 'married'
                                        ? 'selected'
                                        : '' }}>

                                    Married

                                </option>

                                <option
                                    value="divorced"
                                    {{ old(
                                        'marital_status',
                                        $driver->marital_status
                                    ) === 'divorced'
                                        ? 'selected'
                                        : '' }}>

                                    Divorced

                                </option>

                                <option
                                    value="widowed"
                                    {{ old(
                                        'marital_status',
                                        $driver->marital_status
                                    ) === 'widowed'
                                        ? 'selected'
                                        : '' }}>

                                    Widowed

                                </option>

                            </select>

                            @error('marital_status')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- EMPLOYMENT INFORMATION --}}
                {{-- ========================================================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Employment Information
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="row">
                    {{-- Joining Date --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>

                                    Joining Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="date"
                                name="joining_date"
                                id="joining_date"
                                class="form-control @error('joining_date') is-invalid @enderror"
                                value="{{ old('joining_date', $driver->joining_date ? \Carbon\Carbon::parse($driver->joining_date)->format('Y-m-d') : '') }}">

                            @error('joining_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Resignation Date --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Resignation Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="resignation_date"
                                id="resignation_date"
                                class="form-control @error('resignation_date') is-invalid @enderror"
                                value="{{ old('resignation_date', $driver->resignation_date ? \Carbon\Carbon::parse($driver->resignation_date)->format('Y-m-d') : '') }}">

                            <small class="text-muted">
                                Required only when driver resigns.
                            </small>

                            @error('resignation_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Last Working Date --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Last Working Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="last_working_date"
                                id="last_working_date"
                                class="form-control @error('last_working_date') is-invalid @enderror"
                                value="{{ old('last_working_date', $driver->last_working_date ? \Carbon\Carbon::parse($driver->last_working_date)->format('Y-m-d') : '') }}">

                            <small class="text-muted">
                                Driver's final working date.
                            </small>

                            @error('last_working_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Termination Date --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Termination Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="termination_date"
                                id="termination_date"
                                class="form-control @error('termination_date') is-invalid @enderror"
                                value="{{ old('termination_date', $driver->termination_date ? \Carbon\Carbon::parse($driver->termination_date)->format('Y-m-d') : '') }}">

                            <small class="text-muted">
                                Required only when terminated.
                            </small>

                            @error('termination_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- CONTACT INFORMATION --}}
                {{-- ========================================================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Contact Information
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="row">

                    {{-- ========================================================= --}}
                    {{-- MOBILE NUMBER --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="mobile">

                                <b>

                                    Mobile Number

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="mobile"
                                id="mobile"
                                maxlength="10"
                                inputmode="numeric"
                                class="form-control @error('mobile') is-invalid @enderror"
                                value="{{ old('mobile', $driver->mobile) }}"
                                placeholder="Enter Mobile Number"
                                autocomplete="off">

                            <small class="text-muted">
                                Enter 10 digit mobile number.
                            </small>

                            @error('mobile')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- ALTERNATE MOBILE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="alternate_mobile">

                                <b>
                                    Alternate Mobile
                                </b>

                            </label>

                            <input
                                type="text"
                                name="alternate_mobile"
                                id="alternate_mobile"
                                maxlength="10"
                                inputmode="numeric"
                                class="form-control @error('alternate_mobile') is-invalid @enderror"
                                value="{{ old('alternate_mobile', $driver->alternate_mobile) }}"
                                placeholder="Enter Alternate Mobile Number"
                                autocomplete="off">

                            <small class="text-muted">
                                Optional alternate mobile number.
                            </small>

                            @error('alternate_mobile')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- EMAIL --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="email">

                                <b>
                                    Email Address
                                </b>

                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $driver->email) }}"
                                placeholder="Enter Email Address"
                                autocomplete="off">

                            @error('email')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- ADDRESS INFORMATION --}}
                {{-- ========================================================= --}}
                <div class="mt-4 mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Address Information
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="row">

                    {{-- ========================================================= --}}
                    {{-- COUNTRY --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label for="country">

                                <b>
                                    Country
                                </b>

                            </label>

                            <input
                                type="text"
                                name="country"
                                id="country"
                                class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country', $driver->country ?? 'India') }}"
                                placeholder="Enter Country"
                                maxlength="100"
                                autocomplete="off">

                            @error('country')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- STATE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label for="state">

                                <b>
                                    State
                                </b>

                            </label>

                            <input
                                type="text"
                                name="state"
                                id="state"
                                class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state', $driver->state) }}"
                                placeholder="Enter State"
                                maxlength="100"
                                autocomplete="off">

                            @error('state')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- CITY --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label for="city">

                                <b>
                                    City
                                </b>

                            </label>

                            <input
                                type="text"
                                name="city"
                                id="city"
                                class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city', $driver->city) }}"
                                placeholder="Enter City"
                                maxlength="100"
                                autocomplete="off">

                            @error('city')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- PINCODE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label for="pincode">

                                <b>
                                    Pincode
                                </b>

                            </label>

                            <input
                                type="text"
                                name="pincode"
                                id="pincode"
                                maxlength="6"
                                inputmode="numeric"
                                class="form-control @error('pincode') is-invalid @enderror"
                                value="{{ old('pincode', $driver->pincode) }}"
                                placeholder="Enter Pincode"
                                autocomplete="off">

                            @error('pincode')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- COMPLETE ADDRESS --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label for="address">

                                <b>
                                    Complete Address
                                </b>

                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="4"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter Complete Address"
                                autocomplete="off">{{ old('address', $driver->address) }}</textarea>

                            @error('address')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- SECTION HEADING --}}
                {{-- ========================================================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Driving Licence Information
                        </b>

                    </h5>

                    <hr>

                </div>
                
                <div class="row">

                    {{-- ========================================================= --}}
                    {{-- LICENCE NUMBER --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="license_number">

                                <b>

                                    Licence Number

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="license_number"
                                id="license_number"
                                class="form-control @error('license_number') is-invalid @enderror"
                                value="{{ old('license_number', $driver->license_number) }}"
                                placeholder="Enter Licence Number"
                                maxlength="50"
                                autocomplete="off">

                            <small class="text-muted">
                                Enter valid driving licence number.
                            </small>

                            @error('license_number')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- LICENCE TYPE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="license_type">

                                <b>
                                    Licence Type
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <select
                                name="license_type"
                                id="license_type"
                                class="form-control custom-select2 @error('license_type') is-invalid @enderror"
                            >

                                <option value="">
                                    Select Licence Type
                                </option>


                                @foreach(\App\Models\Driver::LICENSE_TYPES as $licenseType)

                                    <option
                                        value="{{ $licenseType }}"
                                        {{ old('license_type', $driver->license_type) === $licenseType
                                            ? 'selected'
                                            : '' }}
                                    >

                                        {{ \App\Models\Driver::getLicenseTypeLabel($licenseType) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('license_type')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- LICENCE ISSUING AUTHORITY --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="license_issuing_authority">

                                <b>
                                    Licence Issuing Authority
                                </b>

                            </label>

                            <input
                                type="text"
                                name="license_issuing_authority"
                                id="license_issuing_authority"
                                class="form-control @error('license_issuing_authority') is-invalid @enderror"
                                value="{{ old(
                                    'license_issuing_authority',
                                    $driver->license_issuing_authority
                                ) }}"
                                placeholder="Enter Issuing Authority"
                                maxlength="150"
                                autocomplete="off">

                            @error('license_issuing_authority')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- LICENCE ISSUE DATE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="license_issue_date">

                                <b>
                                    Licence Issue Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="license_issue_date"
                                id="license_issue_date"
                                class="form-control @error('license_issue_date') is-invalid @enderror"
                                value="{{ old(
                                    'license_issue_date',
                                    optional($driver->license_issue_date)->format('Y-m-d')
                                ) }}">

                            @error('license_issue_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- LICENCE EXPIRY DATE --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="license_expiry_date">

                                <b>

                                    Licence Expiry Date

                                    <span class="text-danger">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="date"
                                name="license_expiry_date"
                                id="license_expiry_date"
                                class="form-control @error('license_expiry_date') is-invalid @enderror"
                                value="{{ old(
                                    'license_expiry_date',
                                    optional($driver->license_expiry_date)->format('Y-m-d')
                                ) }}">

                            <small class="text-muted">
                                Licence expiry date cannot be before issue date.
                            </small>

                            @error('license_expiry_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- DOCUMENTS HEADING --}}
                {{-- ========================================================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Documents
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="row">

                    {{-- ========================================================= --}}
                    {{-- DRIVER PHOTO --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="driver_photo">
                                <b>
                                    Driver Photo
                                </b>
                            </label>

                            {{-- ================================================= --}}
                            {{-- FILE INPUT --}}
                            {{-- ================================================= --}}

                            <input
                                type="file"
                                name="driver_photo"
                                id="driver_photo"
                                class="form-control @error('driver_photo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted d-block mt-1">
                                JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                            </small>

                            @error('driver_photo')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror


                            {{-- ================================================= --}}
                            {{-- EXISTING DRIVER PHOTO --}}
                            {{-- ================================================= --}}
                            @if(!empty($driver->driver_photo))

                                <div
                                    id="existing-driver-photo"
                                    class="mt-3">

                                    <p class="mb-2">

                                        <b>
                                            Current Driver Photo:
                                        </b>

                                    </p>

                                    <div>

                                        <img
                                            src="{{ $driver->driver_photo_url }}"
                                            alt="{{ $driver->full_name }}"
                                            class="img-thumbnail"
                                            style="
                                                width:150px;
                                                height:150px;
                                                object-fit:cover;
                                                border-radius:10px;
                                                border:2px solid #dee2e6;
                                                box-shadow:0 2px 8px rgba(0,0,0,.15);
                                            "
                                            loading="lazy"
                                            decoding="async"
                                            data-no-optimize="1"
                                            onerror="
                                                this.style.display='none';
                                                document.getElementById('driver-photo-load-error').style.display='block';
                                            ">

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- IMAGE LOAD ERROR --}}
                                    {{-- ================================================= --}}

                                    <div
                                        id="driver-photo-load-error"
                                        class="alert alert-danger mt-2"
                                        style="display:none;">

                                        <strong>
                                            Unable to load uploaded driver photo.
                                        </strong>

                                        <br>

                                        <small>
                                            {{ $driver->driver_photo_url }}
                                        </small>

                                    </div>

                                </div>

                            @else

                                <div class="mt-3">

                                    <small class="text-muted">
                                        No driver photo uploaded.
                                    </small>

                                </div>

                            @endif


                            {{-- ================================================= --}}
                            {{-- NEW DRIVER PHOTO PREVIEW --}}
                            {{-- ================================================= --}}

                            <div
                                id="driver-photo-preview"
                                class="mt-3">
                            </div>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- DRIVING LICENCE DOCUMENT --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="driving_license_document">
                                <b>Driving Licence Document</b>
                            </label>


                            {{-- ================================================= --}}
                            {{-- FILE INPUT --}}
                            {{-- ================================================= --}}
                            <input
                                type="file"
                                name="driving_license_document"
                                id="driving_license_document"
                                class="form-control @error('driving_license_document') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp,.pdf"
                            >

                            <small class="text-muted d-block mt-1">
                                JPG, JPEG, PNG, WEBP or PDF. Maximum 2 MB.
                            </small>


                            @error('driving_license_document')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror


                            {{-- ================================================= --}}
                            {{-- EXISTING DRIVING LICENCE DOCUMENT --}}
                            {{-- ================================================= --}}

                            @if(!empty($driver->driving_license_document))

                                @php

                                    $licenseDocument = $driver->driving_license_document;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Existing Document URL
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        str_starts_with(
                                            $licenseDocument,
                                            'driver/'
                                        )
                                    ) {

                                        $licenseDocumentUrl = asset(
                                            'storage/' . $licenseDocument
                                        );

                                    } else {

                                        $licenseDocumentUrl = asset(
                                            'backend/assets/uploads/driver/' .
                                            $licenseDocument
                                        );

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | File Extension
                                    |--------------------------------------------------------------------------
                                    */

                                    $licenseExtension = strtolower(
                                        pathinfo(
                                            $licenseDocument,
                                            PATHINFO_EXTENSION
                                        )
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Image Check
                                    |--------------------------------------------------------------------------
                                    */

                                    $isLicenseImage = in_array(
                                        $licenseExtension,
                                        [
                                            'jpg',
                                            'jpeg',
                                            'png',
                                            'webp'
                                        ],
                                        true
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | PDF Check
                                    |--------------------------------------------------------------------------
                                    */

                                    $isLicensePdf =
                                        $licenseExtension === 'pdf';

                                @endphp


                                <div
                                    id="existing-license-document"
                                    class="mt-3"
                                >

                                    <p class="mb-2">

                                        <b>
                                            Current Driving Licence Document:
                                        </b>

                                    </p>


                                    {{-- ================================================= --}}
                                    {{-- EXISTING IMAGE --}}
                                    {{-- ================================================= --}}

                                    @if($isLicenseImage)

                                        <div>

                                            <img
                                                src="{{ $licenseDocumentUrl }}"
                                                alt="Driving Licence Document"
                                                class="img-thumbnail"
                                                loading="lazy"
                                                decoding="async"
                                                data-no-optimize="1"
                                                style="
                                                    width:180px;
                                                    height:130px;
                                                    object-fit:cover;
                                                    border-radius:10px;
                                                    border:2px solid #dee2e6;
                                                    box-shadow:0 2px 8px rgba(0,0,0,.10);
                                                    cursor:pointer;
                                                "
                                                onclick="window.open(
                                                    '{{ $licenseDocumentUrl }}',
                                                    '_blank'
                                                )"
                                                onerror="
                                                    this.onerror=null;
                                                    this.style.display='none';
                                                    document.getElementById(
                                                        'driving-license-image-error'
                                                    ).style.display='block';
                                                "
                                            >


                                            {{-- IMAGE ERROR --}}

                                            <div
                                                id="driving-license-image-error"
                                                style="display:none;"
                                                class="alert alert-danger mt-2"
                                            >

                                                Unable to load licence image.

                                                <br>

                                                <small>
                                                    {{ $licenseDocumentUrl }}
                                                </small>

                                            </div>


                                            <div class="mt-2">

                                                <a
                                                    href="{{ $licenseDocumentUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-info btn-sm"
                                                >

                                                    <i class="fa fa-eye"></i>

                                                    View Licence Image

                                                </a>

                                            </div>

                                        </div>


                                    {{-- ================================================= --}}
                                    {{-- EXISTING PDF --}}
                                    {{-- ================================================= --}}

                                    @elseif($isLicensePdf)

                                        <div>

                                            <div
                                                style="
                                                    width:180px;
                                                    height:130px;
                                                    border:2px solid #dc3545;
                                                    border-radius:10px;
                                                    display:flex;
                                                    align-items:center;
                                                    justify-content:center;
                                                    background:#f8f9fa;
                                                "
                                            >

                                                <div class="text-center">

                                                    <i
                                                        class="fa fa-file-pdf-o"
                                                        style="
                                                            font-size:50px;
                                                            color:#dc3545;
                                                        "
                                                    ></i>

                                                    <br>

                                                    <small>
                                                        PDF Document
                                                    </small>

                                                </div>

                                            </div>


                                            <div class="mt-2">

                                                <a
                                                    href="{{ $licenseDocumentUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-danger btn-sm"
                                                >

                                                    <i class="fa fa-file-pdf-o"></i>

                                                    View Licence PDF

                                                </a>

                                            </div>

                                        </div>


                                    {{-- ================================================= --}}
                                    {{-- UNKNOWN FILE TYPE --}}
                                    {{-- ================================================= --}}

                                    @else

                                        <div>

                                            <a
                                                href="{{ $licenseDocumentUrl }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-info btn-sm"
                                            >

                                                <i class="fa fa-file"></i>

                                                View Current Licence

                                            </a>

                                        </div>

                                    @endif

                                </div>


                            @else

                                <div class="mt-3">

                                    <small class="text-muted">

                                        <i class="fa fa-info-circle"></i>

                                        No driving licence document uploaded.

                                    </small>

                                </div>

                            @endif


                            {{-- ================================================= --}}
                            {{-- NEW LICENCE DOCUMENT PREVIEW --}}
                            {{-- ================================================= --}}

                            <div
                                id="driving-license-document-preview"
                                class="mt-3"
                            >
                            </div>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- AADHAR NUMBER --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="aadhar_number">

                                <b>
                                    Aadhar Number
                                </b>

                            </label>

                            <input
                                type="text"
                                name="aadhar_number"
                                id="aadhar_number"
                                maxlength="12"
                                inputmode="numeric"
                                class="form-control @error('aadhar_number') is-invalid @enderror"
                                value="{{ old('aadhar_number', $driver->aadhar_number) }}"
                                placeholder="Enter Aadhar Number"
                                autocomplete="off">

                            @error('aadhar_number')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- AADHAR DOCUMENT --}}
                    {{-- ========================================================= --}}
                    <div class="form-group">

                        <label for="aadhar_document">
                            <b>Aadhar Document</b>
                        </label>


                        {{-- ================================================= --}}
                        {{-- FILE INPUT --}}
                        {{-- ================================================= --}}

                        <input
                            type="file"
                            name="aadhar_document"
                            id="aadhar_document"
                            class="form-control @error('aadhar_document') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <small class="text-muted d-block mt-1">
                            JPG, JPEG, PNG, WEBP or PDF. Maximum 2 MB.
                        </small>


                        @error('aadhar_document')

                            <span class="invalid-feedback d-block">

                                <strong>
                                    {{ $message }}
                                </strong>

                            </span>

                        @enderror


                        {{-- ================================================= --}}
                        {{-- EXISTING AADHAR DOCUMENT --}}
                        {{-- ================================================= --}}

                        @if(!empty($driver->aadhar_document))

                            @php

                                $aadharDocument = $driver->aadhar_document;


                                /*
                                |--------------------------------------------------------------------------
                                | Generate Document URL
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    str_starts_with(
                                        $aadharDocument,
                                        'driver/'
                                    )
                                ) {

                                    $aadharDocumentUrl = asset(
                                        'storage/' . $aadharDocument
                                    );

                                } else {

                                    $aadharDocumentUrl = asset(
                                        'backend/assets/uploads/driver/' .
                                        $aadharDocument
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Get File Extension
                                |--------------------------------------------------------------------------
                                */

                                $aadharExtension = strtolower(
                                    pathinfo(
                                        $aadharDocument,
                                        PATHINFO_EXTENSION
                                    )
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Check Image
                                |--------------------------------------------------------------------------
                                */

                                $isAadharImage = in_array(
                                    $aadharExtension,
                                    [
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'webp'
                                    ],
                                    true
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Check PDF
                                |--------------------------------------------------------------------------
                                */

                                $isAadharPdf =
                                    $aadharExtension === 'pdf';

                            @endphp


                            <div
                                id="existing-aadhar-document"
                                class="mt-3"
                            >

                                <p class="mb-2">

                                    <b>
                                        Current Aadhar Document:
                                    </b>

                                </p>


                                {{-- ================================================= --}}
                                {{-- EXISTING IMAGE --}}
                                {{-- ================================================= --}}

                                @if($isAadharImage)

                                    <div>

                                        <img
                                            src="{{ $aadharDocumentUrl }}"
                                            alt="Aadhar Document"
                                            class="img-thumbnail"
                                            loading="lazy"
                                            decoding="async"
                                            data-no-optimize="1"
                                            style="
                                                width:180px;
                                                height:130px;
                                                object-fit:cover;
                                                border-radius:10px;
                                                border:2px solid #dee2e6;
                                                box-shadow:0 2px 8px rgba(0,0,0,.10);
                                                cursor:pointer;
                                            "
                                            onclick="window.open(
                                                '{{ $aadharDocumentUrl }}',
                                                '_blank'
                                            )"
                                            onerror="
                                                this.onerror=null;
                                                this.style.display='none';
                                                document.getElementById(
                                                    'aadhar-image-error'
                                                ).style.display='block';
                                            "
                                        >


                                        {{-- IMAGE ERROR --}}

                                        <div
                                            id="aadhar-image-error"
                                            style="display:none;"
                                            class="alert alert-danger mt-2"
                                        >

                                            Unable to load Aadhar image.

                                            <br>

                                            <small>
                                                {{ $aadharDocumentUrl }}
                                            </small>

                                        </div>


                                        <div class="mt-2">

                                            <a
                                                href="{{ $aadharDocumentUrl }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-info btn-sm"
                                            >

                                                <i class="fa fa-eye"></i>

                                                View Aadhar Image

                                            </a>

                                        </div>

                                    </div>


                                {{-- ================================================= --}}
                                {{-- EXISTING PDF --}}
                                {{-- ================================================= --}}

                                @elseif($isAadharPdf)

                                    <div>

                                        <div
                                            style="
                                                width:180px;
                                                height:130px;
                                                border:2px solid #dc3545;
                                                border-radius:10px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                background:#f8f9fa;
                                            "
                                        >

                                            <div class="text-center">

                                                <i
                                                    class="fa fa-file-pdf-o"
                                                    style="
                                                        font-size:50px;
                                                        color:#dc3545;
                                                    "
                                                ></i>

                                                <br>

                                                <small>
                                                    PDF Document
                                                </small>

                                            </div>

                                        </div>


                                        <div class="mt-2">

                                            <a
                                                href="{{ $aadharDocumentUrl }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-danger btn-sm"
                                            >

                                                <i class="fa fa-file-pdf-o"></i>

                                                View Aadhar PDF

                                            </a>

                                        </div>

                                    </div>


                                {{-- ================================================= --}}
                                {{-- UNKNOWN FILE TYPE --}}
                                {{-- ================================================= --}}

                                @else

                                    <div>

                                        <a
                                            href="{{ $aadharDocumentUrl }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="btn btn-info btn-sm"
                                        >

                                            <i class="fa fa-file"></i>

                                            View Current Aadhar

                                        </a>

                                    </div>

                                @endif

                            </div>


                        @else

                            <div class="mt-3">

                                <small class="text-muted">

                                    <i class="fa fa-info-circle"></i>

                                    No Aadhar document uploaded.

                                </small>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- NEW AADHAR DOCUMENT PREVIEW --}}
                        {{-- ================================================= --}}

                        <div
                            id="aadhar-document-preview"
                            class="mt-3"
                        >
                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- PAN NUMBER --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="pan_number">

                                <b>
                                    PAN Number
                                </b>

                            </label>

                            <input
                                type="text"
                                name="pan_number"
                                id="pan_number"
                                maxlength="10"
                                class="form-control @error('pan_number') is-invalid @enderror"
                                value="{{ old('pan_number', $driver->pan_number) }}"
                                placeholder="Enter PAN Number"
                                autocomplete="off">

                            @error('pan_number')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- PAN DOCUMENT --}}
                    {{-- ========================================================= --}}
                    <div class="form-group">

                        <label for="pan_document">
                            <b>PAN Document</b>
                        </label>


                        {{-- ================================================= --}}
                        {{-- FILE INPUT --}}
                        {{-- ================================================= --}}

                        <input
                            type="file"
                            name="pan_document"
                            id="pan_document"
                            class="form-control @error('pan_document') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <small class="text-muted d-block mt-1">
                            JPG, JPEG, PNG, WEBP or PDF. Maximum 2 MB.
                        </small>


                        @error('pan_document')

                            <span class="invalid-feedback d-block">

                                <strong>
                                    {{ $message }}
                                </strong>

                            </span>

                        @enderror


                        {{-- ================================================= --}}
                        {{-- EXISTING PAN DOCUMENT --}}
                        {{-- ================================================= --}}

                        @if(!empty($driver->pan_document))

                            @php

                                $panDocument = $driver->pan_document;


                                /*
                                |--------------------------------------------------------------------------
                                | Generate PAN Document URL
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    str_starts_with(
                                        $panDocument,
                                        'driver/'
                                    )
                                ) {

                                    $panDocumentUrl = asset(
                                        'storage/' . $panDocument
                                    );

                                } else {

                                    $panDocumentUrl = asset(
                                        'backend/assets/uploads/driver/' .
                                        $panDocument
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Get File Extension
                                |--------------------------------------------------------------------------
                                */

                                $panExtension = strtolower(
                                    pathinfo(
                                        $panDocument,
                                        PATHINFO_EXTENSION
                                    )
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Check Image
                                |--------------------------------------------------------------------------
                                */

                                $isPanImage = in_array(
                                    $panExtension,
                                    [
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'webp'
                                    ],
                                    true
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Check PDF
                                |--------------------------------------------------------------------------
                                */

                                $isPanPdf =
                                    $panExtension === 'pdf';

                            @endphp


                            <div
                                id="existing-pan-document"
                                class="mt-3"
                            >

                                <p class="mb-2">

                                    <b>
                                        Current PAN Document:
                                    </b>

                                </p>


                                {{-- ================================================= --}}
                                {{-- EXISTING PAN IMAGE --}}
                                {{-- ================================================= --}}

                                @if($isPanImage)

                                    <div>

                                        <img
                                            src="{{ $panDocumentUrl }}"
                                            alt="PAN Document"
                                            class="img-thumbnail"
                                            loading="lazy"
                                            decoding="async"
                                            data-no-optimize="1"
                                            style="
                                                width:180px;
                                                height:130px;
                                                object-fit:cover;
                                                border-radius:10px;
                                                border:2px solid #dee2e6;
                                                box-shadow:0 2px 8px rgba(0,0,0,.10);
                                                cursor:pointer;
                                            "
                                            onclick="window.open(
                                                '{{ $panDocumentUrl }}',
                                                '_blank'
                                            )"
                                            onerror="
                                                this.onerror=null;
                                                this.style.display='none';
                                                document.getElementById(
                                                    'pan-image-error'
                                                ).style.display='block';
                                            "
                                        >


                                        {{-- ================================================= --}}
                                        {{-- IMAGE ERROR --}}
                                        {{-- ================================================= --}}

                                        <div
                                            id="pan-image-error"
                                            style="display:none;"
                                            class="alert alert-danger mt-2"
                                        >

                                            Unable to load PAN image.

                                            <br>

                                            <small>
                                                {{ $panDocumentUrl }}
                                            </small>

                                        </div>


                                        <div class="mt-2">

                                            <a
                                                href="{{ $panDocumentUrl }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-info btn-sm"
                                            >

                                                <i class="fa fa-eye"></i>

                                                View PAN Image

                                            </a>

                                        </div>

                                    </div>


                                {{-- ================================================= --}}
                                {{-- EXISTING PAN PDF --}}
                                {{-- ================================================= --}}

                                @elseif($isPanPdf)

                                    <div>

                                        <div
                                            style="
                                                width:180px;
                                                height:130px;
                                                border:2px solid #dc3545;
                                                border-radius:10px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                background:#f8f9fa;
                                            "
                                        >

                                            <div class="text-center">

                                                <i
                                                    class="fa fa-file-pdf-o"
                                                    style="
                                                        font-size:50px;
                                                        color:#dc3545;
                                                    "
                                                ></i>

                                                <br>

                                                <small>
                                                    PDF Document
                                                </small>

                                            </div>

                                        </div>


                                        <div class="mt-2">

                                            <a
                                                href="{{ $panDocumentUrl }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-danger btn-sm"
                                            >

                                                <i class="fa fa-file-pdf-o"></i>

                                                View PAN PDF

                                            </a>

                                        </div>

                                    </div>


                                {{-- ================================================= --}}
                                {{-- UNKNOWN FILE TYPE --}}
                                {{-- ================================================= --}}

                                @else

                                    <div>

                                        <a
                                            href="{{ $panDocumentUrl }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="btn btn-info btn-sm"
                                        >

                                            <i class="fa fa-file"></i>

                                            View Current PAN

                                        </a>

                                    </div>

                                @endif

                            </div>


                        @else

                            <div class="mt-3">

                                <small class="text-muted">

                                    <i class="fa fa-info-circle"></i>

                                    No PAN document uploaded.

                                </small>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- NEW PAN DOCUMENT PREVIEW --}}
                        {{-- ================================================= --}}

                        <div
                            id="pan-document-preview"
                            class="mt-3"
                        >
                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- DRIVER QUALIFICATION --}}
                {{-- ========================================================= --}}
                @php

                    $driverQualifications =
                        is_array($driver->driver_qualifications)
                            ? $driver->driver_qualifications
                            : [];

                @endphp

                <div class="col-12 mt-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Driver Qualification
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="col-12">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-striped"
                            id="qualification-table">

                            <thead>

                                <tr>

                                    <th style="width:18%;">
                                        Qualification
                                    </th>

                                    <th style="width:20%;">
                                        Institute / Board
                                    </th>

                                    <th style="width:12%;">
                                        Passing Year
                                    </th>

                                    <th style="width:14%;">
                                        Percentage / Grade
                                    </th>

                                    <th style="width:25%;">
                                        Document
                                    </th>

                                    <th style="width:8%;">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="qualification-wrapper">

                                @forelse(
                                    $driverQualifications
                                    as $index => $qualification
                                )

                                    @php

                                        $qualificationDocument =
                                            $qualification['document']
                                            ?? null;

                                        $qualificationDocumentUrl =
                                            null;

                                        $qualificationExtension =
                                            null;

                                        $isQualificationImage =
                                            false;

                                        $isQualificationPdf =
                                            false;


                                        if (
                                            !empty(
                                                $qualificationDocument
                                            )
                                        ) {

                                            if (
                                                str_starts_with(
                                                    $qualificationDocument,
                                                    'driver/'
                                                )
                                            ) {

                                                $qualificationDocumentUrl =
                                                    asset(
                                                        'storage/' .
                                                        $qualificationDocument
                                                    );

                                            } else {

                                                $qualificationDocumentUrl =
                                                    asset(
                                                        'backend/assets/uploads/driver/' .
                                                        $qualificationDocument
                                                    );
                                            }


                                            $qualificationExtension =
                                                strtolower(
                                                    pathinfo(
                                                        $qualificationDocument,
                                                        PATHINFO_EXTENSION
                                                    )
                                                );


                                            $isQualificationImage =
                                                in_array(
                                                    $qualificationExtension,
                                                    [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'webp'
                                                    ],
                                                    true
                                                );


                                            $isQualificationPdf =
                                                $qualificationExtension ===
                                                'pdf';
                                        }

                                    @endphp


                                    <tr
                                        class="qualification-row"
                                        data-index="{{ $index }}">

                                        {{-- Qualification --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[{{ $index }}][qualification]"
                                                class="form-control"
                                                value="{{ old(
                                                    "qualifications.$index.qualification",
                                                    $qualification['qualification'] ?? ''
                                                ) }}"
                                                placeholder="e.g. HSC, Diploma, B.Com">


                                            @error(
                                                "qualifications.$index.qualification"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Institute --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[{{ $index }}][institute]"
                                                class="form-control"
                                                value="{{ old(
                                                    "qualifications.$index.institute",
                                                    $qualification['institute'] ?? ''
                                                ) }}"
                                                placeholder="Institute / Board">


                                            @error(
                                                "qualifications.$index.institute"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Passing Year --}}
                                        <td>

                                            <input
                                                type="number"
                                                name="qualifications[{{ $index }}][passing_year]"
                                                class="form-control"
                                                min="1900"
                                                max="{{ date('Y') }}"
                                                value="{{ old(
                                                    "qualifications.$index.passing_year",
                                                    $qualification['passing_year'] ?? ''
                                                ) }}"
                                                placeholder="YYYY">


                                            @error(
                                                "qualifications.$index.passing_year"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Grade --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[{{ $index }}][grade]"
                                                class="form-control"
                                                value="{{ old(
                                                    "qualifications.$index.grade",
                                                    $qualification['grade'] ?? ''
                                                ) }}"
                                                placeholder="72% / A">


                                            @error(
                                                "qualifications.$index.grade"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Document --}}
                                        <td>

                                            {{-- New file --}}
                                            <input
                                                type="file"
                                                name="qualification_documents[{{ $index }}]"
                                                class="form-control qualification-document"
                                                accept=".jpg,.jpeg,.png,.webp,.pdf">


                                            <small class="text-muted d-block mt-1">

                                                JPG, JPEG, PNG, WEBP or PDF.
                                                Maximum 5 MB.

                                            </small>


                                            @error(
                                                "qualification_documents.$index"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror


                                            {{-- Existing Document --}}
                                            @if(
                                                !empty(
                                                    $qualificationDocument
                                                )
                                            )

                                                <div
                                                    class="existing-qualification-document mt-3">

                                                    <small class="d-block mb-2">

                                                        <b>
                                                            Current Document:
                                                        </b>

                                                    </small>


                                                    @if(
                                                        $isQualificationImage
                                                    )

                                                        <img
                                                            src="{{ $qualificationDocumentUrl }}"
                                                            alt="Qualification Document"
                                                            class="img-thumbnail"
                                                            loading="lazy"
                                                            decoding="async"
                                                            data-no-optimize="1"
                                                            style="
                                                                width:120px;
                                                                height:90px;
                                                                object-fit:cover;
                                                                border-radius:8px;
                                                                cursor:pointer;
                                                                border:2px solid #dee2e6;
                                                            "
                                                            onclick="
                                                                window.open(
                                                                    '{{ $qualificationDocumentUrl }}',
                                                                    '_blank'
                                                                );
                                                            ">


                                                        <div class="mt-2">

                                                            <a
                                                                href="{{ $qualificationDocumentUrl }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="btn btn-info btn-sm">

                                                                <i class="fa fa-eye"></i>

                                                                View

                                                            </a>

                                                        </div>

                                                    @elseif(
                                                        $isQualificationPdf
                                                    )

                                                        <div
                                                            style="
                                                                width:120px;
                                                                height:90px;
                                                                border:2px solid #dc3545;
                                                                border-radius:8px;
                                                                display:flex;
                                                                align-items:center;
                                                                justify-content:center;
                                                                background:#f8f9fa;
                                                            ">

                                                            <div class="text-center">

                                                                <i
                                                                    class="fa fa-file-pdf-o"
                                                                    style="
                                                                        font-size:32px;
                                                                        color:#dc3545;
                                                                    ">
                                                                </i>

                                                                <br>

                                                                <small>
                                                                    PDF
                                                                </small>

                                                            </div>

                                                        </div>


                                                        <div class="mt-2">

                                                            <a
                                                                href="{{ $qualificationDocumentUrl }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="btn btn-danger btn-sm">

                                                                <i class="fa fa-file-pdf-o"></i>

                                                                View PDF

                                                            </a>

                                                        </div>

                                                    @else

                                                        <a
                                                            href="{{ $qualificationDocumentUrl }}"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="btn btn-info btn-sm">

                                                            <i class="fa fa-file"></i>

                                                            View Document

                                                        </a>

                                                    @endif

                                                </div>

                                            @endif


                                            {{-- New file preview --}}
                                            <div
                                                class="qualification-document-preview mt-3">
                                            </div>

                                        </td>


                                        {{-- Action --}}
                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm remove-qualification"
                                                title="Remove Qualification">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    {{-- Empty initial row --}}
                                    <tr
                                        class="qualification-row"
                                        data-index="0">

                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[0][qualification]"
                                                class="form-control"
                                                placeholder="e.g. HSC, Diploma, B.Com">

                                        </td>

                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[0][institute]"
                                                class="form-control"
                                                placeholder="Institute / Board">

                                        </td>

                                        <td>

                                            <input
                                                type="number"
                                                name="qualifications[0][passing_year]"
                                                class="form-control"
                                                min="1900"
                                                max="{{ date('Y') }}"
                                                placeholder="YYYY">

                                        </td>

                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[0][grade]"
                                                class="form-control"
                                                placeholder="72% / A">

                                        </td>

                                        <td>

                                            <input
                                                type="file"
                                                name="qualification_documents[0]"
                                                class="form-control qualification-document"
                                                accept=".jpg,.jpeg,.png,.webp,.pdf">

                                            <small class="text-muted d-block mt-1">

                                                JPG, JPEG, PNG, WEBP or PDF.
                                                Maximum 5 MB.

                                            </small>

                                            <div
                                                class="qualification-document-preview mt-2">
                                            </div>

                                        </td>

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm remove-qualification"
                                                disabled>

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    <div class="mt-2">

                        <button
                            type="button"
                            id="add-qualification"
                            class="btn btn-primary btn-sm">

                            <i class="fa fa-plus"></i>

                            Add More Qualification

                        </button>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- DRIVER NOMINEE --}}
                {{-- ========================================================= --}}
                @php

                    $driverNominees =
                        is_array($driver->driver_nominees)
                            ? $driver->driver_nominees
                            : [];

                @endphp

                <div class="col-12 mt-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Driver Nominee
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="col-12">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-striped"
                            id="nominee-table">

                            <thead>

                                <tr>

                                    <th style="width:15%;">
                                        Profile Image
                                    </th>

                                    <th style="width:15%;">
                                        Nominee Name
                                    </th>

                                    <th style="width:14%;">
                                        Relationship
                                    </th>

                                    <th style="width:13%;">
                                        Date of Birth
                                    </th>

                                    <th style="width:13%;">
                                        Mobile
                                    </th>

                                    <th style="width:10%;">
                                        Percentage
                                    </th>

                                    <th style="width:17%;">
                                        Address
                                    </th>

                                    <th style="width:8%;">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="nominee-wrapper">

                                @forelse(
                                    $driverNominees
                                    as $index => $nominee
                                )

                                    @php

                                        $nomineeProfileImage =
                                            $nominee['profile_image']
                                            ?? null;

                                        $nomineeProfileImageUrl =
                                            null;


                                        if (
                                            !empty(
                                                $nomineeProfileImage
                                            )
                                        ) {

                                            if (
                                                str_starts_with(
                                                    $nomineeProfileImage,
                                                    'driver/'
                                                )
                                            ) {

                                                $nomineeProfileImageUrl =
                                                    asset(
                                                        'storage/' .
                                                        $nomineeProfileImage
                                                    );

                                            } else {

                                                $nomineeProfileImageUrl =
                                                    asset(
                                                        'backend/assets/uploads/driver/' .
                                                        $nomineeProfileImage
                                                    );
                                            }
                                        }

                                    @endphp


                                    <tr
                                        class="nominee-row"
                                        data-index="{{ $index }}">

                                        {{-- Profile Image --}}
                                        <td>

                                            <input
                                                type="file"
                                                name="nominee_profile_images[{{ $index }}]"
                                                class="form-control nominee-profile-image"
                                                accept=".jpg,.jpeg,.png,.webp">


                                            <small class="text-muted d-block mt-1">

                                                JPG, JPEG, PNG or WEBP.
                                                Maximum 2 MB.

                                            </small>


                                            @error(
                                                "nominee_profile_images.$index"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror


                                            {{-- Existing Image --}}
                                            @if(
                                                !empty(
                                                    $nomineeProfileImage
                                                )
                                            )

                                                <div
                                                    class="existing-nominee-profile mt-3">

                                                    <small class="d-block mb-2">

                                                        <b>
                                                            Current Profile:
                                                        </b>

                                                    </small>


                                                    <img
                                                        src="{{ $nomineeProfileImageUrl }}"
                                                        alt="Nominee Profile"
                                                        class="img-thumbnail"
                                                        loading="lazy"
                                                        decoding="async"
                                                        data-no-optimize="1"
                                                        style="
                                                            width:80px;
                                                            height:80px;
                                                            object-fit:cover;
                                                            border-radius:50%;
                                                            border:2px solid #dee2e6;
                                                            cursor:pointer;
                                                        "
                                                        onclick="
                                                            window.open(
                                                                '{{ $nomineeProfileImageUrl }}',
                                                                '_blank'
                                                            );
                                                        ">


                                                    <div class="mt-2">

                                                        <a
                                                            href="{{ $nomineeProfileImageUrl }}"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="btn btn-info btn-sm">

                                                            <i class="fa fa-eye"></i>

                                                            View

                                                        </a>

                                                    </div>

                                                </div>

                                            @endif


                                            {{-- New Preview --}}
                                            <div
                                                class="nominee-profile-preview mt-3">
                                            </div>

                                        </td>


                                        {{-- Name --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[{{ $index }}][name]"
                                                class="form-control"
                                                value="{{ old(
                                                    "nominees.$index.name",
                                                    $nominee['name'] ?? ''
                                                ) }}"
                                                placeholder="Nominee Name">


                                            @error(
                                                "nominees.$index.name"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Relationship --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[{{ $index }}][relationship]"
                                                class="form-control"
                                                value="{{ old(
                                                    "nominees.$index.relationship",
                                                    $nominee['relationship'] ?? ''
                                                ) }}"
                                                placeholder="Relationship">


                                            @error(
                                                "nominees.$index.relationship"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- DOB --}}
                                        <td>

                                            <input
                                                type="date"
                                                name="nominees[{{ $index }}][date_of_birth]"
                                                class="form-control"
                                                value="{{ old(
                                                    "nominees.$index.date_of_birth",
                                                    $nominee['date_of_birth'] ?? ''
                                                ) }}">


                                            @error(
                                                "nominees.$index.date_of_birth"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Mobile --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[{{ $index }}][mobile]"
                                                maxlength="10"
                                                inputmode="numeric"
                                                class="form-control nominee-mobile"
                                                value="{{ old(
                                                    "nominees.$index.mobile",
                                                    $nominee['mobile'] ?? ''
                                                ) }}"
                                                placeholder="10 Digit">


                                            @error(
                                                "nominees.$index.mobile"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Percentage --}}
                                        <td>

                                            <input
                                                type="number"
                                                name="nominees[{{ $index }}][percentage]"
                                                class="form-control nominee-percentage"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                value="{{ old(
                                                    "nominees.$index.percentage",
                                                    $nominee['percentage'] ?? ''
                                                ) }}"
                                                placeholder="100">


                                            @error(
                                                "nominees.$index.percentage"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Address --}}
                                        <td>

                                            <textarea
                                                name="nominees[{{ $index }}][address]"
                                                class="form-control"
                                                rows="2"
                                                placeholder="Nominee Address">{{ old(
                                                    "nominees.$index.address",
                                                    $nominee['address'] ?? ''
                                                ) }}</textarea>


                                            @error(
                                                "nominees.$index.address"
                                            )

                                                <small class="text-danger d-block">

                                                    {{ $message }}

                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Action --}}
                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm remove-nominee"
                                                title="Remove Nominee">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    <tr
                                        class="nominee-row"
                                        data-index="0">

                                        <td>

                                            <input
                                                type="file"
                                                name="nominee_profile_images[0]"
                                                class="form-control nominee-profile-image"
                                                accept=".jpg,.jpeg,.png,.webp">

                                            <small class="text-muted d-block mt-1">

                                                JPG, JPEG, PNG or WEBP.
                                                Maximum 2 MB.

                                            </small>

                                            <div
                                                class="nominee-profile-preview mt-2">
                                            </div>

                                        </td>

                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[0][name]"
                                                class="form-control"
                                                placeholder="Nominee Name">

                                        </td>

                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[0][relationship]"
                                                class="form-control"
                                                placeholder="Relationship">

                                        </td>

                                        <td>

                                            <input
                                                type="date"
                                                name="nominees[0][date_of_birth]"
                                                class="form-control">

                                        </td>

                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[0][mobile]"
                                                maxlength="10"
                                                class="form-control nominee-mobile"
                                                placeholder="10 Digit">

                                        </td>

                                        <td>

                                            <input
                                                type="number"
                                                name="nominees[0][percentage]"
                                                class="form-control nominee-percentage"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                placeholder="100">

                                        </td>

                                        <td>

                                            <textarea
                                                name="nominees[0][address]"
                                                class="form-control"
                                                rows="2"
                                                placeholder="Nominee Address"></textarea>

                                        </td>

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm remove-nominee"
                                                disabled>

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    <div class="mt-2">

                        <button
                            type="button"
                            id="add-nominee"
                            class="btn btn-primary btn-sm">

                            <i class="fa fa-plus"></i>

                            Add More Nominee

                        </button>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- DRIVER BANK DETAILS --}}
                {{-- ========================================================= --}}
                @php

                    $driverBankDetails =
                        is_array($driver->driver_bank_details)
                            ? $driver->driver_bank_details
                            : [];

                @endphp

                <div class="col-12 mt-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Driver Bank Details
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="col-12">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-striped">

                            <thead>

                                <tr>

                                    <th>
                                        Account Holder Name
                                    </th>

                                    <th>
                                        Bank Name
                                    </th>

                                    <th>
                                        Account Number
                                    </th>

                                    <th>
                                        IFSC Code
                                    </th>

                                    <th>
                                        Branch Name
                                    </th>

                                    <th>
                                        Account Type
                                    </th>

                                    <th>
                                        UPI ID
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <tr>

                                    {{-- Account Holder --}}
                                    <td>

                                        <input
                                            type="text"
                                            name="bank_details[account_holder_name]"
                                            id="bank_account_holder_name"
                                            class="form-control"
                                            value="{{ old(
                                                'bank_details.account_holder_name',
                                                $driverBankDetails['account_holder_name'] ?? ''
                                            ) }}"
                                            placeholder="Account Holder Name">

                                        @error(
                                            'bank_details.account_holder_name'
                                        )

                                            <small class="text-danger d-block">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </td>


                                    {{-- Bank --}}
                                    <td>

                                        <input
                                            type="text"
                                            name="bank_details[bank_name]"
                                            id="bank_name"
                                            class="form-control"
                                            value="{{ old(
                                                'bank_details.bank_name',
                                                $driverBankDetails['bank_name'] ?? ''
                                            ) }}"
                                            placeholder="Bank Name">

                                        @error(
                                            'bank_details.bank_name'
                                        )

                                            <small class="text-danger d-block">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </td>


                                    {{-- Account Number --}}
                                    <td>

                                        <input
                                            type="text"
                                            name="bank_details[account_number]"
                                            id="bank_account_number"
                                            class="form-control"
                                            value="{{ old(
                                                'bank_details.account_number',
                                                $driverBankDetails['account_number'] ?? ''
                                            ) }}"
                                            placeholder="Account Number"
                                            autocomplete="off">

                                        @error(
                                            'bank_details.account_number'
                                        )

                                            <small class="text-danger d-block">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </td>


                                    {{-- IFSC --}}
                                    <td>

                                        <input
                                            type="text"
                                            name="bank_details[ifsc_code]"
                                            id="bank_ifsc_code"
                                            maxlength="11"
                                            class="form-control text-uppercase"
                                            value="{{ old(
                                                'bank_details.ifsc_code',
                                                $driverBankDetails['ifsc_code'] ?? ''
                                            ) }}"
                                            placeholder="HDFC0001234"
                                            autocomplete="off">

                                        @error(
                                            'bank_details.ifsc_code'
                                        )

                                            <small class="text-danger d-block">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </td>


                                    {{-- Branch --}}
                                    <td>

                                        <input
                                            type="text"
                                            name="bank_details[branch_name]"
                                            id="bank_branch_name"
                                            class="form-control"
                                            value="{{ old(
                                                'bank_details.branch_name',
                                                $driverBankDetails['branch_name'] ?? ''
                                            ) }}"
                                            placeholder="Branch Name">

                                        @error(
                                            'bank_details.branch_name'
                                        )

                                            <small class="text-danger d-block">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </td>


                                    {{-- Account Type --}}
                                    <td>

                                        <select
                                            name="bank_details[account_type]"
                                            id="bank_account_type"
                                            class="form-control">

                                            <option value="">
                                                Select
                                            </option>

                                            <option
                                                value="savings"
                                                @selected(
                                                    old(
                                                        'bank_details.account_type',
                                                        $driverBankDetails['account_type'] ?? ''
                                                    ) === 'savings'
                                                )>

                                                Savings

                                            </option>

                                            <option
                                                value="current"
                                                @selected(
                                                    old(
                                                        'bank_details.account_type',
                                                        $driverBankDetails['account_type'] ?? ''
                                                    ) === 'current'
                                                )>

                                                Current

                                            </option>

                                        </select>

                                        @error(
                                            'bank_details.account_type'
                                        )

                                            <small class="text-danger d-block">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </td>


                                    {{-- UPI --}}
                                    <td>

                                        <input
                                            type="text"
                                            name="bank_details[upi_id]"
                                            id="bank_upi_id"
                                            class="form-control"
                                            value="{{ old(
                                                'bank_details.upi_id',
                                                $driverBankDetails['upi_id'] ?? ''
                                            ) }}"
                                            placeholder="example@upi"
                                            autocomplete="off">

                                        @error(
                                            'bank_details.upi_id'
                                        )

                                            <small class="text-danger d-block">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- EMPLOYMENT STATUS --}}
                {{-- ========================================================= --}}
                <div class="col-12 mt-3">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Employment Status
                        </b>

                    </h5>

                    <hr>

                </div>

                {{-- ========================================================= --}}
                {{-- EMPLOYMENT STATUS --}}
                {{-- ========================================================= --}}
                <div class="col-md-4">

                    <div class="form-group">

                        <label for="status">
                            <b>
                                Employment Status
                                <span class="text-danger">*</span>
                            </b>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-control custom-select2 @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Employment Status
                            </option>

                            <option
                                value="active"
                                @selected(old('status', 'active') === 'active')
                            >
                                Active
                            </option>

                            <option
                                value="on_leave"
                                @selected(old('status') === 'on_leave')
                            >
                                On Leave
                            </option>

                            <option
                                value="notice_period"
                                @selected(old('status') === 'notice_period')
                            >
                                Notice Period
                            </option>

                            <option
                                value="resigned"
                                @selected(old('status') === 'resigned')
                            >
                                Resigned
                            </option>

                            <option
                                value="terminated"
                                @selected(old('status') === 'terminated')
                            >
                                Terminated
                            </option>

                            <option
                                value="inactive"
                                @selected(old('status') === 'inactive')
                            >
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

                    <div
                        class="d-flex justify-content-end align-items-center mt-4"
                        style="gap:10px;">

                        {{-- Cancel --}}
                        <a
                            href="{{ route('driver-management.index') }}"
                            class="btn btn-danger">

                            <i class="fa fa-times"></i>

                            Cancel

                        </a>


                        {{-- Update Driver --}}
                        <button
                            type="submit"
                            class="btn btn-success">

                            <i class="fa fa-save"></i>

                            Update Driver

                        </button>

                    </div>

                </div>

            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function ($) {

        'use strict';


        /*
        |--------------------------------------------------------------------------
        | CONFIGURATION
        |--------------------------------------------------------------------------
        */

        const DRIVER_PHOTO_MAX_SIZE =
            2 * 1024 * 1024; // 2 MB

        const NOMINEE_PHOTO_MAX_SIZE =
            2 * 1024 * 1024; // 2 MB

        const DOCUMENT_MAX_SIZE =
            5 * 1024 * 1024; // 5 MB


        const IMAGE_EXTENSIONS = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


        const DOCUMENT_EXTENSIONS = [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'pdf'
        ];


        const IMAGE_MIME_TYPES = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        const PDF_MIME_TYPE =
            'application/pdf';


        /*
        |--------------------------------------------------------------------------
        | HTML ESCAPE
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value)
        {
            return $('<div>')
                .text(value || '')
                .html();
        }


        /*
        |--------------------------------------------------------------------------
        | GET FILE EXTENSION
        |--------------------------------------------------------------------------
        */

        function getFileExtension(file)
        {
            if (!file || !file.name) {
                return '';
            }

            const parts =
                file.name.split('.');

            if (parts.length < 2) {
                return '';
            }

            return parts
                .pop()
                .toLowerCase();
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK IMAGE
        |--------------------------------------------------------------------------
        */

        function isImageFile(file)
        {
            if (!file) {
                return false;
            }

            const extension =
                getFileExtension(file);

            return (
                IMAGE_MIME_TYPES.includes(file.type) ||
                IMAGE_EXTENSIONS.includes(extension)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK PDF
        |--------------------------------------------------------------------------
        */

        function isPdfFile(file)
        {
            if (!file) {
                return false;
            }

            const extension =
                getFileExtension(file);

            return (
                file.type === PDF_MIME_TYPE ||
                extension === 'pdf'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESET FILE
        |--------------------------------------------------------------------------
        */

        function resetFile(
            inputId,
            previewId
        )
        {
            const input =
                document.getElementById(inputId);

            const preview =
                document.getElementById(previewId);

            if (input) {
                input.value = '';
            }

            if (preview) {
                preview.innerHTML = '';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE IMAGE
        |--------------------------------------------------------------------------
        */

        function validateImage(
            file,
            fieldName,
            maxSize = DRIVER_PHOTO_MAX_SIZE
        )
        {
            if (!file) {
                return false;
            }

            if (!isImageFile(file)) {

                alert(
                    fieldName +
                    ' must be JPG, JPEG, PNG or WEBP.'
                );

                return false;
            }

            if (file.size > maxSize) {

                alert(
                    fieldName +
                    ' size must not exceed ' +
                    (maxSize / 1024 / 1024) +
                    ' MB.'
                );

                return false;
            }

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE DOCUMENT
        |--------------------------------------------------------------------------
        */

        function validateDocument(
            file,
            fieldName = 'Document',
            maxSize = DOCUMENT_MAX_SIZE
        )
        {
            if (!file) {
                return false;
            }

            const extension =
                getFileExtension(file);

            const validExtension =
                DOCUMENT_EXTENSIONS.includes(
                    extension
                );

            const validMime =
                IMAGE_MIME_TYPES.includes(
                    file.type
                ) ||
                file.type === PDF_MIME_TYPE;

            if (
                !validExtension &&
                !validMime
            ) {

                alert(
                    fieldName +
                    ' must be JPG, JPEG, PNG, WEBP or PDF.'
                );

                return false;
            }

            if (
                file.size >
                maxSize
            ) {

                alert(
                    fieldName +
                    ' size must not exceed ' +
                    (maxSize / 1024 / 1024) +
                    ' MB.'
                );

                return false;
            }

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | GENERIC IMAGE DOCUMENT PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewImageDocument(
            file,
            inputId,
            previewId,
            title
        )
        {
            const preview =
                document.getElementById(
                    previewId
                );

            if (!preview) {
                return;
            }

            const reader =
                new FileReader();

            reader.onload =
                function (event) {

                    const imageUrl =
                        event.target.result;

                    preview.innerHTML = `

                        <div
                            class="new-document-preview"
                            style="margin-top:10px;"
                        >

                            <p class="mb-2">

                                <b>
                                    New ${escapeHtml(title)} Preview:
                                </b>

                            </p>

                            <img
                                src="${imageUrl}"
                                alt="${escapeHtml(title)} Preview"
                                class="img-thumbnail"
                                style="
                                    width:180px;
                                    height:130px;
                                    object-fit:cover;
                                    border-radius:10px;
                                    border:2px solid #28a745;
                                    box-shadow:0 2px 8px rgba(0,0,0,.15);
                                    cursor:pointer;
                                    display:block;
                                "
                                onclick="
                                    window.open(
                                        this.src,
                                        '_blank'
                                    );
                                "
                            >

                            <div class="mt-2">

                                <small
                                    class="text-success"
                                >

                                    <i class="fa fa-check-circle"></i>

                                    ${escapeHtml(file.name)}

                                </small>

                            </div>

                            <div class="mt-2">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="
                                        clearSelectedFile(
                                            '${inputId}',
                                            '${previewId}'
                                        );
                                    "
                                >

                                    <i class="fa fa-times"></i>

                                    Remove New File

                                </button>

                            </div>

                        </div>
                    `;
                };

            reader.onerror =
                function () {

                    alert(
                        'Unable to preview the selected image.'
                    );

                    resetFile(
                        inputId,
                        previewId
                    );
                };

            reader.readAsDataURL(file);
        }


        /*
        |--------------------------------------------------------------------------
        | GENERIC PDF PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewPdfDocument(
            file,
            inputId,
            previewId,
            title
        )
        {
            const preview =
                document.getElementById(
                    previewId
                );

            if (!preview) {
                return;
            }

            const pdfUrl =
                URL.createObjectURL(file);

            preview.innerHTML = `

                <div
                    class="new-document-preview"
                    style="margin-top:10px;"
                >

                    <p class="mb-2">

                        <b>
                            New ${escapeHtml(title)} Preview:
                        </b>

                    </p>

                    <div
                        style="
                            width:180px;
                            height:130px;
                            border:2px solid #dc3545;
                            border-radius:10px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            background:#f8f9fa;
                            cursor:pointer;
                        "
                        onclick="
                            window.open(
                                '${pdfUrl}',
                                '_blank'
                            );
                        "
                    >

                        <div class="text-center">

                            <i
                                class="fa fa-file-pdf-o"
                                style="
                                    font-size:50px;
                                    color:#dc3545;
                                "
                            ></i>

                            <br>

                            <small>
                                PDF Document
                            </small>

                        </div>

                    </div>

                    <div class="mt-2">

                        <a
                            href="${pdfUrl}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-danger btn-sm"
                        >

                            <i class="fa fa-file-pdf-o"></i>

                            View New PDF

                        </a>

                    </div>

                    <div class="mt-2">

                        <small
                            class="text-success"
                        >

                            <i class="fa fa-check-circle"></i>

                            ${escapeHtml(file.name)}

                        </small>

                    </div>

                    <div class="mt-2">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="
                                clearSelectedFile(
                                    '${inputId}',
                                    '${previewId}'
                                );
                            "
                        >

                            <i class="fa fa-times"></i>

                            Remove New File

                        </button>

                    </div>

                </div>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | GENERIC DOCUMENT PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewDocument(
            inputId,
            previewId,
            title,
            maxSize = DOCUMENT_MAX_SIZE
        )
        {
            const fileInput =
                document.getElementById(
                    inputId
                );

            const preview =
                document.getElementById(
                    previewId
                );

            if (
                !fileInput ||
                !preview
            ) {

                console.warn(
                    'Preview element not found:',
                    inputId,
                    previewId
                );

                return;
            }

            preview.innerHTML = '';

            if (
                !fileInput.files ||
                fileInput.files.length === 0
            ) {
                return;
            }

            const file =
                fileInput.files[0];

            if (
                !validateDocument(
                    file,
                    title,
                    maxSize
                )
            ) {

                resetFile(
                    inputId,
                    previewId
                );

                return;
            }

            if (
                isImageFile(file)
            ) {

                previewImageDocument(
                    file,
                    inputId,
                    previewId,
                    title
                );

                return;
            }

            if (
                isPdfFile(file)
            ) {

                previewPdfDocument(
                    file,
                    inputId,
                    previewId,
                    title
                );

                return;
            }

            resetFile(
                inputId,
                previewId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DRIVER PHOTO PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewDriverPhoto()
        {
            const inputId =
                'driver_photo';

            const previewId =
                'driver-photo-preview';

            const fileInput =
                document.getElementById(
                    inputId
                );

            const preview =
                document.getElementById(
                    previewId
                );

            if (
                !fileInput ||
                !preview
            ) {
                return;
            }

            preview.innerHTML = '';

            if (
                !fileInput.files ||
                fileInput.files.length === 0
            ) {
                return;
            }

            const file =
                fileInput.files[0];

            if (
                !validateImage(
                    file,
                    'Driver Photo',
                    DRIVER_PHOTO_MAX_SIZE
                )
            ) {

                resetFile(
                    inputId,
                    previewId
                );

                return;
            }

            const reader =
                new FileReader();

            reader.onload =
                function (event) {

                    const imageUrl =
                        event.target.result;

                    preview.innerHTML = `

                        <div
                            class="new-driver-photo-preview"
                            style="margin-top:10px;"
                        >

                            <p class="mb-2">

                                <b>
                                    New Driver Photo Preview:
                                </b>

                            </p>

                            <img
                                src="${imageUrl}"
                                alt="New Driver Photo"
                                class="img-thumbnail"
                                style="
                                    width:150px;
                                    height:150px;
                                    object-fit:cover;
                                    border-radius:10px;
                                    border:2px solid #28a745;
                                    box-shadow:0 2px 8px rgba(0,0,0,.15);
                                    cursor:pointer;
                                    display:block;
                                "
                                onclick="
                                    window.open(
                                        this.src,
                                        '_blank'
                                    );
                                "
                            >

                            <div class="mt-2">

                                <small
                                    class="text-success"
                                >

                                    <i class="fa fa-check-circle"></i>

                                    ${escapeHtml(file.name)}

                                </small>

                            </div>

                            <div class="mt-2">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="
                                        clearSelectedFile(
                                            'driver_photo',
                                            'driver-photo-preview'
                                        );
                                    "
                                >

                                    <i class="fa fa-times"></i>

                                    Remove New Photo

                                </button>

                            </div>

                        </div>
                    `;
                };

            reader.onerror =
                function () {

                    alert(
                        'Unable to preview the selected driver photo.'
                    );

                    resetFile(
                        inputId,
                        previewId
                    );
                };

            reader.readAsDataURL(file);
        }


        /*
        |--------------------------------------------------------------------------
        | CLEAR SELECTED FILE
        |--------------------------------------------------------------------------
        */

        window.clearSelectedFile =
            function (
                inputId,
                previewId
            )
            {
                const input =
                    document.getElementById(
                        inputId
                    );

                const preview =
                    document.getElementById(
                        previewId
                    );

                if (input) {
                    input.value = '';
                }

                if (preview) {
                    preview.innerHTML = '';
                }
            };


        /*
        |--------------------------------------------------------------------------
        | DRIVER PHOTO CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#driver_photo',
            function ()
            {
                previewDriverPhoto();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DRIVING LICENCE DOCUMENT CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#driving_license_document',
            function ()
            {
                previewDocument(
                    'driving_license_document',
                    'driving-license-document-preview',
                    'Driving Licence Document',
                    DOCUMENT_MAX_SIZE
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | AADHAAR DOCUMENT CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#aadhar_document',
            function ()
            {
                previewDocument(
                    'aadhar_document',
                    'aadhar-document-preview',
                    'Aadhar Document',
                    DOCUMENT_MAX_SIZE
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PAN DOCUMENT CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#pan_document',
            function ()
            {
                previewDocument(
                    'pan_document',
                    'pan-document-preview',
                    'PAN Document',
                    DOCUMENT_MAX_SIZE
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | QUALIFICATION INDEX
        |--------------------------------------------------------------------------
        */

        let qualificationIndex =
            $('#qualification-wrapper')
                .find('.qualification-row')
                .length;


        /*
        |--------------------------------------------------------------------------
        | QUALIFICATION DOCUMENT PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewQualificationDocument(
            input
        )
        {
            const row =
                $(input).closest(
                    '.qualification-row'
                );

            const preview =
                row.find(
                    '.qualification-document-preview'
                );

            if (
                !preview.length
            ) {
                return;
            }

            preview.html('');

            if (
                !input.files ||
                input.files.length === 0
            ) {
                return;
            }

            const file =
                input.files[0];

            if (
                !validateDocument(
                    file,
                    'Qualification Document',
                    DOCUMENT_MAX_SIZE
                )
            ) {

                input.value = '';

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | IMAGE
            |--------------------------------------------------------------------------
            */

            if (
                isImageFile(file)
            ) {

                const reader =
                    new FileReader();

                reader.onload =
                    function (event) {

                        preview.html(`

                            <div>

                                <p class="mb-2">

                                    <b>
                                        New Qualification Document:
                                    </b>

                                </p>

                                <img
                                    src="${event.target.result}"
                                    alt="Qualification Document"
                                    class="img-thumbnail"
                                    style="
                                        width:120px;
                                        height:90px;
                                        object-fit:cover;
                                        border-radius:8px;
                                        border:2px solid #28a745;
                                        cursor:pointer;
                                    "
                                    onclick="
                                        window.open(
                                            this.src,
                                            '_blank'
                                        );
                                    "
                                >

                                <div class="mt-1">

                                    <small
                                        class="text-success">

                                        <i class="fa fa-check-circle"></i>

                                        ${escapeHtml(file.name)}

                                    </small>

                                </div>

                            </div>
                        `);
                    };

                reader.readAsDataURL(file);

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | PDF
            |--------------------------------------------------------------------------
            */

            if (
                isPdfFile(file)
            ) {

                const pdfUrl =
                    URL.createObjectURL(file);

                preview.html(`

                    <div>

                        <p class="mb-2">

                            <b>
                                New Qualification Document:
                            </b>

                        </p>

                        <div
                            style="
                                width:120px;
                                height:90px;
                                border:2px solid #dc3545;
                                border-radius:8px;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                background:#f8f9fa;
                                cursor:pointer;
                            "
                            onclick="
                                window.open(
                                    '${pdfUrl}',
                                    '_blank'
                                );
                            "
                        >

                            <div class="text-center">

                                <i
                                    class="fa fa-file-pdf-o"
                                    style="
                                        font-size:32px;
                                        color:#dc3545;
                                    ">
                                </i>

                                <br>

                                <small>
                                    PDF
                                </small>

                            </div>

                        </div>

                        <div class="mt-1">

                            <small
                                class="text-success">

                                <i class="fa fa-check-circle"></i>

                                ${escapeHtml(file.name)}

                            </small>

                        </div>

                        <div class="mt-1">

                            <a
                                href="${pdfUrl}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-outline-danger btn-xs">

                                <i class="fa fa-eye"></i>

                                View

                            </a>

                        </div>

                    </div>
                `);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | QUALIFICATION DOCUMENT CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.qualification-document',
            function ()
            {
                previewQualificationDocument(
                    this
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | ADD QUALIFICATION
        |--------------------------------------------------------------------------
        */

        $('#add-qualification').on(
            'click',
            function ()
            {
                const index =
                    qualificationIndex;

                const currentYear =
                    new Date()
                        .getFullYear();

                const row = `

                    <tr
                        class="qualification-row"
                        data-index="${index}">

                        <td>

                            <input
                                type="text"
                                name="qualifications[${index}][qualification]"
                                class="form-control"
                                placeholder="e.g. HSC, Diploma, B.Com">

                        </td>

                        <td>

                            <input
                                type="text"
                                name="qualifications[${index}][institute]"
                                class="form-control"
                                placeholder="Institute / Board">

                        </td>

                        <td>

                            <input
                                type="number"
                                name="qualifications[${index}][passing_year]"
                                class="form-control"
                                min="1900"
                                max="${currentYear}"
                                placeholder="YYYY">

                        </td>

                        <td>

                            <input
                                type="text"
                                name="qualifications[${index}][grade]"
                                class="form-control"
                                placeholder="72% / A">

                        </td>

                        <td>

                            <input
                                type="file"
                                name="qualification_documents[${index}]"
                                class="form-control qualification-document"
                                accept=".jpg,.jpeg,.png,.webp,.pdf">

                            <small
                                class="text-muted d-block mt-1">

                                JPG, JPEG, PNG, WEBP or PDF.
                                Maximum 5 MB.

                            </small>

                            <div
                                class="qualification-document-preview mt-2">
                            </div>

                        </td>

                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-danger btn-sm remove-qualification"
                                title="Remove Qualification">

                                <i class="fa fa-trash"></i>

                            </button>

                        </td>

                    </tr>
                `;

                $('#qualification-wrapper')
                    .append(row);

                qualificationIndex++;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | REMOVE QUALIFICATION
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.remove-qualification',
            function ()
            {
                $(this)
                    .closest(
                        '.qualification-row'
                    )
                    .remove();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE INDEX
        |--------------------------------------------------------------------------
        */

        let nomineeIndex =
            $('#nominee-wrapper')
                .find('.nominee-row')
                .length;


        /*
        |--------------------------------------------------------------------------
        | NOMINEE PROFILE IMAGE PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewNomineeProfile(
            input
        )
        {
            const row =
                $(input).closest(
                    '.nominee-row'
                );

            const preview =
                row.find(
                    '.nominee-profile-preview'
                );

            if (
                !preview.length
            ) {
                return;
            }

            preview.html('');

            if (
                !input.files ||
                input.files.length === 0
            ) {
                return;
            }

            const file =
                input.files[0];

            if (
                !validateImage(
                    file,
                    'Nominee Profile Image',
                    NOMINEE_PHOTO_MAX_SIZE
                )
            ) {

                input.value = '';

                return;
            }

            const reader =
                new FileReader();

            reader.onload =
                function (event) {

                    preview.html(`

                        <div>

                            <p class="mb-2">

                                <b>
                                    New Profile:
                                </b>

                            </p>

                            <img
                                src="${event.target.result}"
                                alt="Nominee Profile"
                                class="img-thumbnail"
                                style="
                                    width:80px;
                                    height:80px;
                                    object-fit:cover;
                                    border-radius:50%;
                                    border:2px solid #28a745;
                                    cursor:pointer;
                                "
                                onclick="
                                    window.open(
                                        this.src,
                                        '_blank'
                                    );
                                "
                            >

                            <div class="mt-1">

                                <small
                                    class="text-success">

                                    <i class="fa fa-check-circle"></i>

                                    ${escapeHtml(file.name)}

                                </small>

                            </div>

                        </div>
                    `);
                };

            reader.onerror =
                function () {

                    alert(
                        'Unable to preview nominee profile image.'
                    );

                    input.value = '';

                    preview.html('');
                };

            reader.readAsDataURL(file);
        }


        /*
        |--------------------------------------------------------------------------
        | NOMINEE PROFILE IMAGE CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.nominee-profile-image',
            function ()
            {
                previewNomineeProfile(
                    this
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | ADD NOMINEE
        |--------------------------------------------------------------------------
        */

        $('#add-nominee').on(
            'click',
            function ()
            {
                const index =
                    nomineeIndex;

                const row = `

                    <tr
                        class="nominee-row"
                        data-index="${index}">

                        <td>

                            <input
                                type="file"
                                name="nominee_profile_images[${index}]"
                                class="form-control nominee-profile-image"
                                accept=".jpg,.jpeg,.png,.webp">

                            <small
                                class="text-muted d-block mt-1">

                                JPG, JPEG, PNG or WEBP.
                                Maximum 2 MB.

                            </small>

                            <div
                                class="nominee-profile-preview mt-2">
                            </div>

                        </td>

                        <td>

                            <input
                                type="text"
                                name="nominees[${index}][name]"
                                class="form-control"
                                placeholder="Nominee Name">

                        </td>

                        <td>

                            <input
                                type="text"
                                name="nominees[${index}][relationship]"
                                class="form-control"
                                placeholder="Relationship">

                        </td>

                        <td>

                            <input
                                type="date"
                                name="nominees[${index}][date_of_birth]"
                                class="form-control nominee-dob">

                        </td>

                        <td>

                            <input
                                type="text"
                                name="nominees[${index}][mobile]"
                                maxlength="10"
                                inputmode="numeric"
                                class="form-control nominee-mobile"
                                placeholder="10 Digit">

                        </td>

                        <td>

                            <input
                                type="number"
                                name="nominees[${index}][percentage]"
                                class="form-control nominee-percentage"
                                min="0"
                                max="100"
                                step="0.01"
                                placeholder="100">

                        </td>

                        <td>

                            <textarea
                                name="nominees[${index}][address]"
                                class="form-control"
                                rows="2"
                                placeholder="Nominee Address"></textarea>

                        </td>

                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-danger btn-sm remove-nominee"
                                title="Remove Nominee">

                                <i class="fa fa-trash"></i>

                            </button>

                        </td>

                    </tr>
                `;

                $('#nominee-wrapper')
                    .append(row);

                nomineeIndex++;

                setNomineeDobMaxDates();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | REMOVE NOMINEE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.remove-nominee',
            function ()
            {
                $(this)
                    .closest(
                        '.nominee-row'
                    )
                    .remove();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE MOBILE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.nominee-mobile',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /[^0-9]/g,
                            ''
                        )
                        .slice(
                            0,
                            10
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE PERCENTAGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.nominee-percentage',
            function ()
            {
                let value =
                    parseFloat(
                        this.value
                    );

                if (
                    isNaN(value)
                ) {
                    return;
                }

                if (
                    value < 0
                ) {
                    value = 0;
                }

                if (
                    value > 100
                ) {
                    value = 100;
                }

                this.value =
                    value;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE DOB MAX DATE
        |--------------------------------------------------------------------------
        */

        function setNomineeDobMaxDates()
        {
            const today =
                new Date()
                    .toISOString()
                    .split('T')[0];

            $('.nominee-dob')
                .attr(
                    'max',
                    today
                );
        }


        /*
        |--------------------------------------------------------------------------
        | BANK IFSC
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#bank_ifsc_code',
            function ()
            {
                this.value =
                    this.value
                        .toUpperCase()
                        .replace(
                            /[^A-Z0-9]/g,
                            ''
                        )
                        .slice(
                            0,
                            11
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | BANK ACCOUNT NUMBER
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#bank_account_number',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /[^0-9]/g,
                            ''
                        )
                        .slice(
                            0,
                            50
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | BANK TEXT FIELDS
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#bank_account_holder_name, #bank_name, #bank_branch_name',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | BANK UPI
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#bank_upi_id',
            function ()
            {
                this.value =
                    this.value
                        .trim()
                        .toLowerCase();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#mobile, #alternate_mobile',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /[^0-9]/g,
                            ''
                        )
                        .slice(
                            0,
                            10
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | AADHAAR
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#aadhar_number',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /[^0-9]/g,
                            ''
                        )
                        .slice(
                            0,
                            12
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PINCODE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#pincode',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /[^0-9]/g,
                            ''
                        )
                        .slice(
                            0,
                            6
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PAN NUMBER
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#pan_number',
            function ()
            {
                this.value =
                    this.value
                        .toUpperCase()
                        .replace(
                            /[^A-Z0-9]/g,
                            ''
                        )
                        .slice(
                            0,
                            10
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | LICENSE NUMBER
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#license_number',
            function ()
            {
                this.value =
                    this.value
                        .toUpperCase()
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DRIVER CODE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#driver_code',
            function ()
            {
                this.value =
                    this.value
                        .trim()
                        .toUpperCase()
                        .replace(
                            /\s+/g,
                            ''
                        );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NAME FIELDS
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#first_name, #last_name, #father_name',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | LICENSE AUTHORITY
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#license_issuing_authority',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | ADDRESS FIELDS
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#country, #state, #city, #address',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#email',
            function ()
            {
                this.value =
                    this.value
                        .trim()
                        .toLowerCase();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DATE HELPER
        |--------------------------------------------------------------------------
        */

        function parseDateValue(value)
        {
            if (!value) {
                return null;
            }

            const date =
                new Date(
                    value + 'T00:00:00'
                );

            if (
                isNaN(
                    date.getTime()
                )
            ) {
                return null;
            }

            return date;
        }


        /*
        |--------------------------------------------------------------------------
        | TODAY
        |--------------------------------------------------------------------------
        */

        function getToday()
        {
            const today =
                new Date();

            today.setHours(
                0,
                0,
                0,
                0
            );

            return today;
        }


        /*
        |--------------------------------------------------------------------------
        | DATE OF BIRTH
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#date_of_birth',
            function ()
            {
                if (!this.value) {
                    return;
                }

                const selectedDate =
                    parseDateValue(
                        this.value
                    );

                const today =
                    getToday();

                if (
                    selectedDate &&
                    selectedDate > today
                ) {

                    alert(
                        'Date of Birth cannot be a future date.'
                    );

                    this.value = '';

                    return;
                }

                const joiningDate =
                    parseDateValue(
                        $('#joining_date').val()
                    );

                if (
                    selectedDate &&
                    joiningDate &&
                    joiningDate < selectedDate
                ) {

                    alert(
                        'Date of Birth cannot be after Joining Date.'
                    );

                    this.value = '';
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | LICENSE DATES
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#license_issue_date, #license_expiry_date',
            function ()
            {
                validateLicenseDates();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | LICENSE DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        function validateLicenseDates()
        {
            const issueDate =
                parseDateValue(
                    $('#license_issue_date').val()
                );

            const expiryDate =
                parseDateValue(
                    $('#license_expiry_date').val()
                );

            const today =
                getToday();


            if (
                issueDate &&
                issueDate > today
            ) {

                alert(
                    'Licence Issue Date cannot be a future date.'
                );

                $('#license_issue_date')
                    .val('')
                    .focus();

                return false;
            }


            if (
                issueDate &&
                expiryDate &&
                expiryDate < issueDate
            ) {

                alert(
                    'Licence Expiry Date cannot be before Licence Issue Date.'
                );

                $('#license_expiry_date')
                    .val('')
                    .focus();

                return false;
            }


            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | EMPLOYMENT DATES
        |--------------------------------------------------------------------------
        */

        function validateEmploymentDates(
            showAlert = true
        )
        {
            const joiningDate =
                $('#joining_date').val();

            const resignationDate =
                $('#resignation_date').val();

            const lastWorkingDate =
                $('#last_working_date').val();

            const terminationDate =
                $('#termination_date').val();


            if (!joiningDate) {

                if (showAlert) {

                    alert(
                        'Joining Date is required.'
                    );

                    $('#joining_date')
                        .focus();
                }

                return false;
            }


            const joining =
                parseDateValue(
                    joiningDate
                );

            const resignation =
                parseDateValue(
                    resignationDate
                );

            const lastWorking =
                parseDateValue(
                    lastWorkingDate
                );

            const termination =
                parseDateValue(
                    terminationDate
                );

            const today =
                getToday();


            /*
            |--------------------------------------------------------------------------
            | Joining Date
            |--------------------------------------------------------------------------
            */

            if (
                joining > today
            ) {

                if (showAlert) {

                    alert(
                        'Joining Date cannot be a future date.'
                    );

                    $('#joining_date')
                        .val('')
                        .focus();
                }

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | DOB -> Joining
            |--------------------------------------------------------------------------
            */

            const dob =
                parseDateValue(
                    $('#date_of_birth').val()
                );

            if (
                dob &&
                joining < dob
            ) {

                if (showAlert) {

                    alert(
                        'Joining Date cannot be before Date of Birth.'
                    );

                    $('#joining_date')
                        .val('')
                        .focus();
                }

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Resignation
            |--------------------------------------------------------------------------
            */

            if (
                resignation
            ) {

                if (
                    resignation > today
                ) {

                    if (showAlert) {

                        alert(
                            'Resignation Date cannot be a future date.'
                        );

                        $('#resignation_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }


                if (
                    resignation < joining
                ) {

                    if (showAlert) {

                        alert(
                            'Resignation Date cannot be before Joining Date.'
                        );

                        $('#resignation_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Last Working
            |--------------------------------------------------------------------------
            */

            if (
                lastWorking
            ) {

                if (
                    lastWorking > today
                ) {

                    if (showAlert) {

                        alert(
                            'Last Working Date cannot be a future date.'
                        );

                        $('#last_working_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }


                if (
                    lastWorking < joining
                ) {

                    if (showAlert) {

                        alert(
                            'Last Working Date cannot be before Joining Date.'
                        );

                        $('#last_working_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }


                if (
                    resignation &&
                    lastWorking < resignation
                ) {

                    if (showAlert) {

                        alert(
                            'Last Working Date cannot be before Resignation Date.'
                        );

                        $('#last_working_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Termination
            |--------------------------------------------------------------------------
            */

            if (
                termination
            ) {

                if (
                    termination > today
                ) {

                    if (showAlert) {

                        alert(
                            'Termination Date cannot be a future date.'
                        );

                        $('#termination_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }


                if (
                    termination < joining
                ) {

                    if (showAlert) {

                        alert(
                            'Termination Date cannot be before Joining Date.'
                        );

                        $('#termination_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }


                if (
                    resignation &&
                    termination < resignation
                ) {

                    if (showAlert) {

                        alert(
                            'Termination Date cannot be before Resignation Date.'
                        );

                        $('#termination_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }


                if (
                    lastWorking &&
                    termination < lastWorking
                ) {

                    if (showAlert) {

                        alert(
                            'Termination Date cannot be before Last Working Date.'
                        );

                        $('#termination_date')
                            .val('')
                            .focus();
                    }

                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Resignation + Termination
            |--------------------------------------------------------------------------
            */

            if (
                resignation &&
                termination
            ) {

                if (showAlert) {

                    alert(
                        'A driver cannot have both Resignation Date and Termination Date.'
                    );

                    $('#termination_date')
                        .val('')
                        .focus();
                }

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Last Working Required
            |--------------------------------------------------------------------------
            */

            if (
                resignation &&
                !lastWorkingDate
            ) {

                if (showAlert) {

                    alert(
                        'Last Working Date is required when Resignation Date is provided.'
                    );

                    $('#last_working_date')
                        .focus();
                }

                return false;
            }


            if (
                termination &&
                !lastWorkingDate
            ) {

                if (showAlert) {

                    alert(
                        'Last Working Date is required when Termination Date is provided.'
                    );

                    $('#last_working_date')
                        .focus();
                }

                return false;
            }


            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | EMPLOYMENT DATE EVENTS
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#joining_date, #resignation_date, #last_working_date, #termination_date',
            function ()
            {
                validateEmploymentDates();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE PERCENTAGE VALIDATION
        |--------------------------------------------------------------------------
        */

        function validateNomineePercentages()
        {
            let total =
                0;

            let hasPercentage =
                false;


            $('.nominee-percentage')
                .each(
                    function ()
                    {
                        const value =
                            parseFloat(
                                $(this).val()
                            );

                        if (
                            !isNaN(value)
                        ) {

                            total += value;

                            hasPercentage =
                                true;
                        }
                    }
                );


            if (
                total > 100
            ) {

                alert(
                    'Total nominee percentage cannot exceed 100%.'
                );

                return false;
            }


            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | QUALIFICATION FORMATTING
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#qualification-wrapper input[type="text"]',
            function ()
            {
                this.value =
                    this.value
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE FORMATTING
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '.nominee-row input[type="text"], .nominee-row textarea',
            function ()
            {
                if (
                    $(this)
                        .hasClass(
                            'nominee-mobile'
                        )
                ) {
                    return;
                }

                this.value =
                    this.value
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | BANK ACCOUNT TYPE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#bank_account_type',
            function ()
            {
                const value =
                    $(this).val();

                if (
                    value &&
                    ![
                        'savings',
                        'current'
                    ].includes(value)
                ) {

                    $(this).val('');
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | FORM SUBMIT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'submit',
            'form',
            function (event)
            {
                const form =
                    this;


                /*
                |--------------------------------------------------------------------------
                | EMPLOYMENT VALIDATION
                |--------------------------------------------------------------------------
                */

                if (
                    !validateEmploymentDates(
                        true
                    )
                ) {

                    event.preventDefault();

                    return false;
                }


                /*
                |--------------------------------------------------------------------------
                | LICENSE VALIDATION
                |--------------------------------------------------------------------------
                */

                if (
                    !validateLicenseDates()
                ) {

                    event.preventDefault();

                    return false;
                }


                /*
                |--------------------------------------------------------------------------
                | NOMINEE PERCENTAGE
                |--------------------------------------------------------------------------
                */

                if (
                    !validateNomineePercentages()
                ) {

                    event.preventDefault();

                    return false;
                }


                /*
                |--------------------------------------------------------------------------
                | DRIVER CODE
                |--------------------------------------------------------------------------
                */

                $('#driver_code')
                    .val(
                        $('#driver_code')
                            .val()
                            .trim()
                            .toUpperCase()
                            .replace(
                                /\s+/g,
                                ''
                            )
                    );


                /*
                |--------------------------------------------------------------------------
                | NAMES
                |--------------------------------------------------------------------------
                */

                $(
                    '#first_name, #last_name, #father_name'
                ).each(
                    function ()
                    {
                        $(this)
                            .val(
                                $(this)
                                    .val()
                                    .replace(
                                        /\s+/g,
                                        ' '
                                    )
                                    .trim()
                            );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | MOBILE
                |--------------------------------------------------------------------------
                */

                $('#mobile, #alternate_mobile')
                    .each(
                        function ()
                        {
                            $(this)
                                .val(
                                    $(this)
                                        .val()
                                        .replace(
                                            /[^0-9]/g,
                                            ''
                                        )
                                        .slice(
                                            0,
                                            10
                                        )
                                );
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | EMAIL
                |--------------------------------------------------------------------------
                */

                $('#email')
                    .val(
                        $('#email')
                            .val()
                            .trim()
                            .toLowerCase()
                    );


                /*
                |--------------------------------------------------------------------------
                | ADDRESS
                |--------------------------------------------------------------------------
                */

                $(
                    '#country, #state, #city, #address'
                ).each(
                    function ()
                    {
                        $(this)
                            .val(
                                $(this)
                                    .val()
                                    .replace(
                                        /\s+/g,
                                        ' '
                                    )
                                    .trim()
                            );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | PINCODE
                |--------------------------------------------------------------------------
                */

                $('#pincode')
                    .val(
                        $('#pincode')
                            .val()
                            .replace(
                                /[^0-9]/g,
                                ''
                            )
                            .slice(
                                0,
                                6
                            )
                    );


                /*
                |--------------------------------------------------------------------------
                | LICENSE
                |--------------------------------------------------------------------------
                */

                $('#license_number')
                    .val(
                        $('#license_number')
                            .val()
                            .trim()
                            .toUpperCase()
                            .replace(
                                /\s+/g,
                                ' '
                            )
                    );


                /*
                |--------------------------------------------------------------------------
                | LICENSE AUTHORITY
                |--------------------------------------------------------------------------
                */

                $('#license_issuing_authority')
                    .val(
                        $('#license_issuing_authority')
                            .val()
                            .replace(
                                /\s+/g,
                                ' '
                            )
                            .trim()
                    );


                /*
                |--------------------------------------------------------------------------
                | AADHAAR
                |--------------------------------------------------------------------------
                */

                $('#aadhar_number')
                    .val(
                        $('#aadhar_number')
                            .val()
                            .replace(
                                /[^0-9]/g,
                                ''
                            )
                            .slice(
                                0,
                                12
                            )
                    );


                /*
                |--------------------------------------------------------------------------
                | PAN
                |--------------------------------------------------------------------------
                */

                $('#pan_number')
                    .val(
                        $('#pan_number')
                            .val()
                            .toUpperCase()
                            .replace(
                                /[^A-Z0-9]/g,
                                ''
                            )
                            .slice(
                                0,
                                10
                            )
                    );


                /*
                |--------------------------------------------------------------------------
                | QUALIFICATIONS
                |--------------------------------------------------------------------------
                */

                $('#qualification-wrapper')
                    .find(
                        'input[type="text"]'
                    )
                    .each(
                        function ()
                        {
                            $(this)
                                .val(
                                    $(this)
                                        .val()
                                        .replace(
                                            /\s+/g,
                                            ' '
                                        )
                                        .trim()
                                );
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | NOMINEES
                |--------------------------------------------------------------------------
                */

                $('.nominee-row')
                    .find(
                        'input[type="text"], textarea'
                    )
                    .each(
                        function ()
                        {
                            if (
                                $(this)
                                    .hasClass(
                                        'nominee-mobile'
                                    )
                            ) {
                                return;
                            }

                            $(this)
                                .val(
                                    $(this)
                                        .val()
                                        .replace(
                                            /\s+/g,
                                            ' '
                                        )
                                        .trim()
                                );
                        }
                    );


                $('.nominee-mobile')
                    .each(
                        function ()
                        {
                            $(this)
                                .val(
                                    $(this)
                                        .val()
                                        .replace(
                                            /[^0-9]/g,
                                            ''
                                        )
                                        .slice(
                                            0,
                                            10
                                        )
                                );
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | BANK DETAILS
                |--------------------------------------------------------------------------
                */

                $(
                    '#bank_account_holder_name, #bank_name, #bank_branch_name'
                ).each(
                    function ()
                    {
                        $(this)
                            .val(
                                $(this)
                                    .val()
                                    .replace(
                                        /\s+/g,
                                        ' '
                                    )
                                    .trim()
                            );
                    }
                );


                $('#bank_ifsc_code')
                    .val(
                        $('#bank_ifsc_code')
                            .val()
                            .toUpperCase()
                            .replace(
                                /[^A-Z0-9]/g,
                                ''
                            )
                            .slice(
                                0,
                                11
                            )
                    );


                $('#bank_account_number')
                    .val(
                        $('#bank_account_number')
                            .val()
                            .replace(
                                /[^0-9]/g,
                                ''
                            )
                            .slice(
                                0,
                                50
                            )
                    );


                $('#bank_upi_id')
                    .val(
                        $('#bank_upi_id')
                            .val()
                            .trim()
                            .toLowerCase()
                    );


                /*
                |--------------------------------------------------------------------------
                | DOUBLE SUBMIT PROTECTION
                |--------------------------------------------------------------------------
                */

                const submitButton =
                    $(form)
                        .find(
                            'button[type="submit"]'
                        );


                if (
                    submitButton.length &&
                    !submitButton.data(
                        'submitted'
                    )
                ) {

                    submitButton
                        .data(
                            'submitted',
                            true
                        )
                        .prop(
                            'disabled',
                            true
                        )
                        .html(
                            '<i class="fa fa-spinner fa-spin"></i> Updating Driver...'
                        );
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DOCUMENT READY
        |--------------------------------------------------------------------------
        */

        $(document)
            .ready(
                function ()
                {

                    /*
                    |--------------------------------------------------------------------------
                    | DEFAULT COUNTRY
                    |--------------------------------------------------------------------------
                    */

                    const countryInput =
                        $('#country');

                    if (
                        countryInput.length &&
                        countryInput.val() === ''
                    ) {

                        countryInput.val(
                            'India'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | TODAY
                    |--------------------------------------------------------------------------
                    */

                    const todayString =
                        new Date()
                            .toISOString()
                            .split('T')[0];


                    /*
                    |--------------------------------------------------------------------------
                    | MAIN DATE LIMITS
                    |--------------------------------------------------------------------------
                    */

                    $('#date_of_birth')
                        .attr(
                            'max',
                            todayString
                        );


                    $('#joining_date')
                        .attr(
                            'max',
                            todayString
                        );


                    $('#resignation_date')
                        .attr(
                            'max',
                            todayString
                        );


                    $('#last_working_date')
                        .attr(
                            'max',
                            todayString
                        );


                    $('#termination_date')
                        .attr(
                            'max',
                            todayString
                        );


                    $('#license_issue_date')
                        .attr(
                            'max',
                            todayString
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | NOMINEE DOB LIMIT
                    |--------------------------------------------------------------------------
                    */

                    setNomineeDobMaxDates();


                    /*
                    |--------------------------------------------------------------------------
                    | DEBUG / VALIDATION CHECK
                    |--------------------------------------------------------------------------
                    */

                    console.log(
                        'Driver Edit Script Loaded'
                    );

                    console.log(
                        'Qualification Rows:',
                        $('#qualification-wrapper .qualification-row').length
                    );

                    console.log(
                        'Nominee Rows:',
                        $('#nominee-wrapper .nominee-row').length
                    );

                    console.log(
                        'Qualification Add Button:',
                        $('#add-qualification').length
                    );

                    console.log(
                        'Nominee Add Button:',
                        $('#add-nominee').length
                    );

                    console.log(
                        'Driver Photo:',
                        $('#driver_photo').length
                    );

                    console.log(
                        'Driving Licence:',
                        $('#driving_license_document').length
                    );

                    console.log(
                        'Aadhar:',
                        $('#aadhar_document').length
                    );

                    console.log(
                        'PAN:',
                        $('#pan_document').length
                    );

                    console.log(
                        'Bank Details:',
                        $('#bank_account_holder_name').length
                    );

                }
            );


    })(jQuery);
</script>
@endpush