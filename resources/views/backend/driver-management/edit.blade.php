@extends('backend.layouts.master')

@section('title')
    Edit Driver
@endsection

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
                {{-- STATUS --}}
                {{-- ========================================================= --}}
                <div class="col-12 mt-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>
                            Status
                        </b>

                    </h5>

                    <hr>

                </div>

                <div class="col-md-4">

                    <div class="form-group">

                        <label for="status">

                            <b>

                                Status

                                <span class="text-danger">
                                    *
                                </span>

                            </b>

                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-control custom-select2 @error('status') is-invalid @enderror">

                            <option
                                value="1"
                                {{ old(
                                    'status',
                                    $driver->status ? 1 : 0
                                ) == 1 ? 'selected' : '' }}>

                                Active

                            </option>

                            <option
                                value="0"
                                {{ old(
                                    'status',
                                    $driver->status ? 1 : 0
                                ) == 0 ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                        @error('status')

                            <span class="invalid-feedback d-block">

                                <strong>
                                    {{ $message }}
                                </strong>

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

    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB


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


    const PDF_MIME_TYPE = 'application/pdf';


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

        const parts = file.name.split('.');

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
    | VALIDATE IMAGE
    |--------------------------------------------------------------------------
    */

    function validateImage(file, fieldName)
    {
        if (!file) {
            return false;
        }


        if (!isImageFile(file)) {

            alert(
                'Please select a valid JPG, JPEG, PNG or WEBP image.'
            );

            return false;
        }


        if (file.size > MAX_FILE_SIZE) {

            alert(
                fieldName + ' image size must not exceed 2 MB.'
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

    function validateDocument(file)
    {
        if (!file) {
            return false;
        }


        const extension =
            getFileExtension(file);


        const validExtension =
            DOCUMENT_EXTENSIONS.includes(extension);


        const validMime =
            IMAGE_MIME_TYPES.includes(file.type) ||
            file.type === PDF_MIME_TYPE;


        /*
        |--------------------------------------------------------------------------
        | Browser file.type empty ho sakta hai.
        | Isliye extension OR MIME dono allow hain.
        |--------------------------------------------------------------------------
        */

        if (
            !validExtension &&
            !validMime
        ) {

            alert(
                'Please select a valid JPG, JPEG, PNG, WEBP or PDF file.'
            );

            return false;
        }


        if (file.size > MAX_FILE_SIZE) {

            alert(
                'File size must not exceed 2 MB.'
            );

            return false;
        }


        return true;
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
    | PREVIEW IMAGE DOCUMENT
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
            document.getElementById(previewId);


        if (!preview) {
            return;
        }


        const reader =
            new FileReader();


        reader.onload = function (event)
        {
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
                        onclick="window.open(this.src, '_blank')"
                    >


                    <div class="mt-2">

                        <small
                            style="color:#28a745;"
                        >

                            <i class="fa fa-check-circle"></i>

                            ${escapeHtml(file.name)}

                        </small>

                    </div>


                    <div class="mt-2">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="clearSelectedFile(
                                '${inputId}',
                                '${previewId}'
                            )"
                        >

                            <i class="fa fa-times"></i>

                            Remove New File

                        </button>

                    </div>

                </div>

            `;
        };


        reader.onerror = function ()
        {
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
    | PREVIEW PDF DOCUMENT
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
            document.getElementById(previewId);


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
                        style="color:#28a745;"
                    >

                        <i class="fa fa-check-circle"></i>

                        ${escapeHtml(file.name)}

                    </small>

                </div>


                <div class="mt-2">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        onclick="clearSelectedFile(
                            '${inputId}',
                            '${previewId}'
                        )"
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
    |
    | Used for:
    |
    | driving_license_document
    | aadhar_document
    | pan_document
    |
    |--------------------------------------------------------------------------
    */

    function previewDocument(
        inputId,
        previewId,
        title
    )
    {
        const fileInput =
            document.getElementById(inputId);

        const preview =
            document.getElementById(previewId);


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


        /*
        |--------------------------------------------------------------------------
        | Clear only NEW preview
        |--------------------------------------------------------------------------
        */

        preview.innerHTML = '';


        /*
        |--------------------------------------------------------------------------
        | No selected file
        |--------------------------------------------------------------------------
        */

        if (
            !fileInput.files ||
            fileInput.files.length === 0
        ) {
            return;
        }


        const file =
            fileInput.files[0];


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        if (!validateDocument(file)) {

            resetFile(
                inputId,
                previewId
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        if (isImageFile(file)) {

            previewImageDocument(
                file,
                inputId,
                previewId,
                title
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        if (isPdfFile(file)) {

            previewPdfDocument(
                file,
                inputId,
                previewId,
                title
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | UNKNOWN FILE
        |--------------------------------------------------------------------------
        */

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
            document.getElementById(inputId);

        const preview =
            document.getElementById(previewId);


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


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        if (
            !validateImage(
                file,
                'Driver Photo'
            )
        ) {

            resetFile(
                inputId,
                previewId
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Read image
        |--------------------------------------------------------------------------
        */

        const reader =
            new FileReader();


        reader.onload = function (event)
        {
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
                        onclick="window.open(this.src, '_blank')"
                    >


                    <div class="mt-2">

                        <small
                            style="color:#28a745;"
                        >

                            <i class="fa fa-check-circle"></i>

                            ${escapeHtml(file.name)}

                        </small>

                    </div>


                    <div class="mt-2">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="clearSelectedFile(
                                'driver_photo',
                                'driver-photo-preview'
                            )"
                        >

                            <i class="fa fa-times"></i>

                            Remove New Photo

                        </button>

                    </div>

                </div>

            `;
        };


        reader.onerror = function ()
        {
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
    | GLOBAL CLEAR FUNCTION
    |--------------------------------------------------------------------------
    */

    window.clearSelectedFile =
        function (
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
    |
    | IMPORTANT:
    |
    | Input:
    | driving_license_document
    |
    | Preview:
    | driving-license-document-preview
    |
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
                'Driving Licence Document'
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | AADHAR DOCUMENT CHANGE
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
                'Aadhar Document'
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
                'PAN Document'
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | MOBILE NUMBER
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '#mobile, #alternate_mobile',
        function ()
        {
            this.value =
                this.value
                    .replace(/[^0-9]/g, '')
                    .slice(0, 10);
        }
    );


    /*
    |--------------------------------------------------------------------------
    | AADHAR NUMBER
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '#aadhar_number',
        function ()
        {
            this.value =
                this.value
                    .replace(/[^0-9]/g, '')
                    .slice(0, 12);
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
                    .replace(/[^0-9]/g, '')
                    .slice(0, 6);
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
                    .replace(/[^A-Z0-9]/g, '')
                    .slice(0, 10);
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
                    .replace(/\s+/g, ' ')
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
                    .replace(/\s+/g, '');
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
                    .replace(/\s+/g, ' ')
                    .trim();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | LICENSE ISSUING AUTHORITY
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'blur',
        '#license_issuing_authority',
        function ()
        {
            this.value =
                this.value
                    .replace(/\s+/g, ' ')
                    .trim();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | COUNTRY / STATE / CITY / ADDRESS
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'blur',
        '#country, #state, #city, #address',
        function ()
        {
            this.value =
                this.value
                    .replace(/\s+/g, ' ')
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
                new Date(this.value);


            const today =
                new Date();


            today.setHours(
                0,
                0,
                0,
                0
            );


            if (selectedDate > today) {

                alert(
                    'Date of Birth cannot be a future date.'
                );

                this.value = '';
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | LICENSE ISSUE DATE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '#license_issue_date',
        function ()
        {
            const issueDate =
                this.value;

            const expiryDate =
                $('#license_expiry_date').val();


            if (!issueDate) {
                return;
            }


            const selectedIssueDate =
                new Date(issueDate);


            const today =
                new Date();


            today.setHours(
                0,
                0,
                0,
                0
            );


            if (
                selectedIssueDate >
                today
            ) {

                alert(
                    'Licence Issue Date cannot be a future date.'
                );

                this.value = '';

                return;
            }


            if (expiryDate) {

                const selectedExpiryDate =
                    new Date(expiryDate);


                if (
                    selectedIssueDate >
                    selectedExpiryDate
                ) {

                    alert(
                        'Licence Issue Date cannot be after Licence Expiry Date.'
                    );

                    this.value = '';
                }
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | LICENSE EXPIRY DATE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '#license_expiry_date',
        function ()
        {
            const expiryDate =
                this.value;

            const issueDate =
                $('#license_issue_date').val();


            if (!expiryDate) {
                return;
            }


            const selectedExpiryDate =
                new Date(expiryDate);


            if (issueDate) {

                const selectedIssueDate =
                    new Date(issueDate);


                if (
                    selectedExpiryDate <
                    selectedIssueDate
                ) {

                    alert(
                        'Licence Expiry Date cannot be before Licence Issue Date.'
                    );

                    this.value = '';
                }
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
        function ()
        {
            const form =
                this;


            /*
            |--------------------------------------------------------------------------
            | Driver Code
            |--------------------------------------------------------------------------
            */

            $('#driver_code').val(
                $('#driver_code').val()
                    .trim()
                    .toUpperCase()
                    .replace(/\s+/g, '')
            );


            /*
            |--------------------------------------------------------------------------
            | Names
            |--------------------------------------------------------------------------
            */

            $(
                '#first_name, #last_name, #father_name'
            ).each(
                function ()
                {
                    $(this).val(
                        $(this).val()
                            .replace(/\s+/g, ' ')
                            .trim()
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Mobile
            |--------------------------------------------------------------------------
            */

            $('#mobile, #alternate_mobile').each(
                function ()
                {
                    $(this).val(
                        $(this).val()
                            .replace(/[^0-9]/g, '')
                            .slice(0, 10)
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            */

            $('#email').val(
                $('#email').val()
                    .trim()
                    .toLowerCase()
            );


            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            $(
                '#country, #state, #city, #address'
            ).each(
                function ()
                {
                    $(this).val(
                        $(this).val()
                            .replace(/\s+/g, ' ')
                            .trim()
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Pincode
            |--------------------------------------------------------------------------
            */

            $('#pincode').val(
                $('#pincode').val()
                    .replace(/[^0-9]/g, '')
                    .slice(0, 6)
            );


            /*
            |--------------------------------------------------------------------------
            | License Number
            |--------------------------------------------------------------------------
            */

            $('#license_number').val(
                $('#license_number').val()
                    .trim()
                    .toUpperCase()
                    .replace(/\s+/g, ' ')
            );


            /*
            |--------------------------------------------------------------------------
            | License Authority
            |--------------------------------------------------------------------------
            */

            $('#license_issuing_authority').val(
                $('#license_issuing_authority').val()
                    .replace(/\s+/g, ' ')
                    .trim()
            );


            /*
            |--------------------------------------------------------------------------
            | Aadhar
            |--------------------------------------------------------------------------
            */

            $('#aadhar_number').val(
                $('#aadhar_number').val()
                    .replace(/[^0-9]/g, '')
                    .slice(0, 12)
            );


            /*
            |--------------------------------------------------------------------------
            | PAN
            |--------------------------------------------------------------------------
            */

            $('#pan_number').val(
                $('#pan_number').val()
                    .toUpperCase()
                    .replace(/[^A-Z0-9]/g, '')
                    .slice(0, 10)
            );


            /*
            |--------------------------------------------------------------------------
            | DOUBLE SUBMIT PROTECTION
            |--------------------------------------------------------------------------
            */

            const submitButton =
                $(form).find(
                    'button[type="submit"]'
                );


            if (
                submitButton.length &&
                !submitButton.data('submitted')
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

    $(document).ready(
        function ()
        {
            /*
            |--------------------------------------------------------------------------
            | Default Country
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
            | DEBUG CHECK
            |--------------------------------------------------------------------------
            |
            | Agar preview nahi aaye to browser console me ye IDs
            | check hongi.
            |
            |--------------------------------------------------------------------------
            */

            console.log(
                'Driver Edit File Preview JS Loaded'
            );


            console.log(
                'Driving Licence Input:',
                $('#driving_license_document').length
            );


            console.log(
                'Driving Licence Preview:',
                $('#driving-license-document-preview').length
            );


            console.log(
                'Aadhar Input:',
                $('#aadhar_document').length
            );


            console.log(
                'Aadhar Preview:',
                $('#aadhar-document-preview').length
            );


            console.log(
                'PAN Input:',
                $('#pan_document').length
            );


            console.log(
                'PAN Preview:',
                $('#pan-document-preview').length
            );


            console.log(
                'Driver Photo Input:',
                $('#driver_photo').length
            );


            console.log(
                'Driver Photo Preview:',
                $('#driver-photo-preview').length
            );
        }
    );


})(jQuery);
</script>

@endpush