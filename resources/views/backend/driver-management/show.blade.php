@extends('backend.layouts.master')

@section('title')
    View Driver Profile
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
                            View Driver Profile
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

                                View Driver Profile

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

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
                            autocomplete="off"
                            readonly>

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
                            class="form-control custom-select2 @error('driver_type') is-invalid @enderror"
                            disabled>

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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            disabled
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
                            disabled
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            readonly
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
                            disabled
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
                            readonly
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
                            readonly
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
                            readonly
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
                                Driver Photo:
                            </b>

                        </label>

                        {{-- ================================================= --}}
                        {{-- EXISTING DRIVER PHOTO --}}
                        {{-- ================================================= --}}
                        @if(!empty($driver->driver_photo))

                            <div
                                id="existing-driver-photo"
                                class="mt-3">

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

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- DRIVING LICENCE DOCUMENT --}}
                {{-- ========================================================= --}}
                <div class="col-md-6">

                    <div class="form-group">

                        <label for="driving_licence_number">

                            <b>
                                Driving Licence Document :
                            </b>

                        </label>

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
                            readonly
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
                <div class="col-md-6">

                    <div class="form-group">

                        <label for="aadhar_document">

                            <b>
                                Aadhar Document :
                            </b>

                        </label>

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
                            readonly
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
                <div class="col-md-6">

                    <div class="form-group">

                        <label for="pan_number">

                            <b>
                                PAN Document :
                            </b>

                        </label>

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

                                {{-- <th style="width:8%;">
                                    Action
                                </th> --}}

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
                                            readonly
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
                                            readonly
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
                                            readonly
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
                                            readonly
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
                                                        readonly
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
                                            readonly
                                            placeholder="e.g. HSC, Diploma, B.Com">

                                    </td>

                                    <td>

                                        <input
                                            type="text"
                                            name="qualifications[0][institute]"
                                            class="form-control"
                                            readonly
                                            placeholder="Institute / Board">

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            name="qualifications[0][passing_year]"
                                            class="form-control"
                                            min="1900"
                                            readonly
                                            max="{{ date('Y') }}"
                                            placeholder="YYYY">

                                    </td>

                                    <td>

                                        <input
                                            type="text"
                                            name="qualifications[0][grade]"
                                            class="form-control"
                                            readonly
                                            placeholder="72% / A">

                                    </td>

                                    <td>

                                        <input
                                            type="file"
                                            name="qualification_documents[0]"
                                            class="form-control qualification-document"
                                            readonly
                                            accept=".jpg,.jpeg,.png,.webp,.pdf">

                                        <small class="text-muted d-block mt-1">

                                            JPG, JPEG, PNG, WEBP or PDF.
                                            Maximum 5 MB.

                                        </small>

                                        <div
                                            class="qualification-document-preview mt-2">
                                        </div>

                                    </td>

                                    {{-- <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm remove-qualification"
                                            disabled>

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </td> --}}

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- <div class="mt-2">

                    <button
                        type="button"
                        id="add-qualification"
                        class="btn btn-primary btn-sm">

                        <i class="fa fa-plus"></i>

                        Add More Qualification

                    </button>

                </div> --}}

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

                                {{-- <th style="width:8%;">
                                    Action
                                </th> --}}

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

                                        {{-- Existing Image --}}
                                        @if(
                                            !empty(
                                                $nomineeProfileImage
                                            )
                                        )

                                            <div
                                                class="existing-nominee-profile mt-3">
                                                
                                                <img
                                                    src="{{ $nomineeProfileImageUrl }}"
                                                    alt="Nominee Profile"
                                                    class="img-thumbnail"
                                                    loading="lazy"
                                                    decoding="async"
                                                    data-no-optimize="1"
                                                    readonly
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
                                            readonly
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
                                            readonly
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
                                            readonly
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
                                            readonly
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
                                            readonly
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
                                            readonly
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
                                    {{-- <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm remove-nominee"
                                            title="Remove Nominee">

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </td> --}}

                                </tr>

                            @empty

                                <tr
                                    class="nominee-row"
                                    data-index="0">

                                    <td>

                                        <input
                                            type="file"
                                            name="nominee_profile_images[0]"
                                            class="form-control nominee-profile-image" readonly
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
                                            class="form-control" readonly
                                            placeholder="Nominee Name">

                                    </td>

                                    <td>

                                        <input
                                            type="text"
                                            name="nominees[0][relationship]"
                                            class="form-control" readonly
                                            placeholder="Relationship">

                                    </td>

                                    <td>

                                        <input
                                            type="date"
                                            name="nominees[0][date_of_birth]" readonly
                                            class="form-control">

                                    </td>

                                    <td>

                                        <input
                                            type="text"
                                            name="nominees[0][mobile]"
                                            maxlength="10"
                                            class="form-control nominee-mobile" readonly
                                            placeholder="10 Digit">

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            name="nominees[0][percentage]"
                                            class="form-control nominee-percentage" readonly
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            placeholder="100">

                                    </td>

                                    <td>

                                        <textarea
                                            name="nominees[0][address]"
                                            class="form-control"
                                            rows="2" readonly
                                            placeholder="Nominee Address"></textarea>

                                    </td>

                                    {{-- <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm remove-nominee"
                                            disabled>

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </td> --}}

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- <div class="mt-2">

                    <button
                        type="button"
                        id="add-nominee"
                        class="btn btn-primary btn-sm">

                        <i class="fa fa-plus"></i>

                        Add More Nominee

                    </button>

                </div> --}}

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
                                        readonly
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
                                        readonly
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
                                        readonly
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
                                        readonly
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
                                        readonly
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
                                        disabled
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
                                        readonly
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

            <div class="row">
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
                            disabled
                            class="form-control custom-select2 @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Employment Status
                            </option>

                            @foreach(\App\Models\Driver::EMPLOYMENT_STATUSES as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(old('status', $driver->status) === $status)
                                >
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>

                            @endforeach

                        </select>

                        @error('status')

                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>

                        @enderror

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- PF STATUS --}}
                {{-- ========================================================= --}}
                <div class="col-md-4">

                    <div class="form-group">

                        <label for="pf_status">

                            <b>
                                PF Applicable
                                <span class="text-danger">*</span>
                            </b>

                        </label>

                        <select
                            name="pf_status"
                            id="pf_status"
                            disabled
                            class="form-control custom-select2 @error('pf_status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select PF Status
                            </option>

                            @foreach(\App\Models\Driver::PF_STATUSES as $pfStatus)

                                <option
                                    value="{{ $pfStatus }}"
                                    @selected(old('pf_status', $driver->pf_status) === $pfStatus)
                                >

                                    @if($pfStatus === \App\Models\Driver::PF_YES)

                                        Yes

                                    @elseif($pfStatus === \App\Models\Driver::PF_NO)

                                        No

                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('pf_status')

                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>

                        @enderror

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- DOCUMENT STATUS --}}
                {{-- ========================================================= --}}
                <div class="col-md-4">

                    <div class="form-group">

                        <label for="document_status">

                            <b>
                                Document Status
                                <span class="text-danger">*</span>
                            </b>

                        </label>

                        <select
                            name="document_status"
                            id="document_status"
                            disabled
                            class="form-control custom-select2 @error('document_status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Document Status
                            </option>

                            @foreach(\App\Models\Driver::DOCUMENT_STATUSES as $documentStatus)

                                <option
                                    value="{{ $documentStatus }}"
                                    @selected(old('document_status', $driver->document_status) === $documentStatus)
                                >

                                    @if($documentStatus === \App\Models\Driver::DOCUMENT_RECEIVED)

                                        Received

                                    @elseif($documentStatus === \App\Models\Driver::DOCUMENT_PENDING)

                                        Pending

                                    @elseif($documentStatus === \App\Models\Driver::DOCUMENT_REJECTED)

                                        Rejected

                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('document_status')

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

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush