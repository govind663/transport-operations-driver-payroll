@extends('backend.layouts.master')

@section('title')
    Create Driver
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
                            Create New Driver
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

                                Create Driver

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('driver-management.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf


            <div class="card-box pd-20 mb-30">


                {{-- ========================================================= --}}
                {{-- DRIVER BASIC INFORMATION --}}
                {{-- ========================================================= --}}
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

                    {{-- Driver Code --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                value="{{ old('driver_code') }}"
                                placeholder="Enter Driver Code (e.g. DRV001)">

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

                    {{-- Driver Type --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                        {{ old('driver_type', $driver->driver_type ?? '') === $driverType ? 'selected' : '' }}>

                                        @if($driverType === \App\Models\Driver::DRIVER_FIXED_DUTY)
                                            Fixed Duty Driver
                                        @elseif($driverType === \App\Models\Driver::DRIVER_GENERAL_DUTY)
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

                    {{-- First Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                value="{{ old('first_name') }}"
                                placeholder="Enter First Name">

                            @error('first_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Last Name
                                </b>

                            </label>

                            <input
                                type="text"
                                name="last_name"
                                id="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name') }}"
                                placeholder="Enter Last Name">

                            @error('last_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Father Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                value="{{ old('father_name') }}"
                                placeholder="Enter Father Name">

                            @error('father_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Date of Birth --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Date of Birth
                                </b>

                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                id="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}">

                            @error('date_of_birth')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Gender --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                    {{ old('gender') == 'male' ? 'selected' : '' }}>

                                    Male

                                </option>

                                <option
                                    value="female"
                                    {{ old('gender') == 'female' ? 'selected' : '' }}>

                                    Female

                                </option>

                                <option
                                    value="other"
                                    {{ old('gender') == 'other' ? 'selected' : '' }}>

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

                    {{-- Marital Status --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                    {{ old('marital_status') == 'single' ? 'selected' : '' }}>

                                    Single

                                </option>

                                <option
                                    value="married"
                                    {{ old('marital_status') == 'married' ? 'selected' : '' }}>

                                    Married

                                </option>

                                <option
                                    value="divorced"
                                    {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>

                                    Divorced

                                </option>

                                <option
                                    value="widowed"
                                    {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>

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

                    {{-- ========================================================= --}}
                    {{-- EMPLOYMENT INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Employment Information
                            </b>

                        </h5>

                        <hr>

                    </div>

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
                                value="{{ old('joining_date') }}">

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
                                value="{{ old('resignation_date') }}">

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
                                value="{{ old('last_working_date') }}">

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
                                value="{{ old('termination_date') }}">

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

                    {{-- ========================================================= --}}
                    {{-- CONTACT INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Contact Information
                            </b>

                        </h5>

                        <hr>

                    </div>

                    {{-- Mobile --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                class="form-control @error('mobile') is-invalid @enderror"
                                value="{{ old('mobile') }}"
                                placeholder="Enter Mobile Number">

                            @error('mobile')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Alternate Mobile --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Alternate Mobile
                                </b>

                            </label>

                            <input
                                type="text"
                                name="alternate_mobile"
                                id="alternate_mobile"
                                maxlength="10"
                                class="form-control @error('alternate_mobile') is-invalid @enderror"
                                value="{{ old('alternate_mobile') }}"
                                placeholder="Enter Alternate Mobile Number">

                            @error('alternate_mobile')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Email --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Email Address
                                </b>

                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Enter Email Address">

                            @error('email')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- ADDRESS INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Address Information
                            </b>

                        </h5>

                        <hr>

                    </div>

                    {{-- Country --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Country
                                </b>

                            </label>

                            <input
                                type="text"
                                name="country"
                                id="country"
                                class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country', 'India') }}"
                                placeholder="Enter Country">

                            @error('country')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- State --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    State
                                </b>

                            </label>

                            <input
                                type="text"
                                name="state"
                                id="state"
                                class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state') }}"
                                placeholder="Enter State">

                            @error('state')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- City --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    City
                                </b>

                            </label>

                            <input
                                type="text"
                                name="city"
                                id="city"
                                class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city') }}"
                                placeholder="Enter City">

                            @error('city')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Pincode
                                </b>

                            </label>

                            <input
                                type="text"
                                name="pincode"
                                id="pincode"
                                maxlength="6"
                                class="form-control @error('pincode') is-invalid @enderror"
                                value="{{ old('pincode') }}"
                                placeholder="Enter Pincode">

                            @error('pincode')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Address --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>

                                <b>
                                    Complete Address
                                </b>

                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="4"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter Complete Address">{{ old('address') }}</textarea>

                            @error('address')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- DRIVING LICENCE INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Driving Licence Information
                            </b>

                        </h5>

                        <hr>

                    </div>

                    {{-- Licence Number --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                value="{{ old('license_number') }}"
                                placeholder="Enter Licence Number">

                            @error('license_number')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Licence Type --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Licence Type
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <select
                                name="license_type"
                                id="license_type"
                                class="form-control custom-select2 @error('license_type') is-invalid @enderror">

                                <option value="">
                                    Select Licence Type
                                </option>

                                @foreach(\App\Models\Driver::LICENSE_TYPES as $licenseType)

                                    <option
                                        value="{{ $licenseType }}"
                                        {{ old('license_type') === $licenseType ? 'selected' : '' }}>

                                        {{ \App\Models\Driver::getLicenseTypeLabel($licenseType) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('license_type')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Issuing Authority --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Licence Issuing Authority
                                </b>

                            </label>

                            <input
                                type="text"
                                name="license_issuing_authority"
                                id="license_issuing_authority"
                                class="form-control @error('license_issuing_authority') is-invalid @enderror"
                                value="{{ old('license_issuing_authority') }}"
                                placeholder="Enter Issuing Authority">

                            @error('license_issuing_authority')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Licence Issue Date --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Licence Issue Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="license_issue_date"
                                id="license_issue_date"
                                class="form-control @error('license_issue_date') is-invalid @enderror"
                                value="{{ old('license_issue_date') }}">

                            @error('license_issue_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Licence Expiry Date --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

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
                                value="{{ old('license_expiry_date') }}">

                            @error('license_expiry_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- DOCUMENTS --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Documents</b>
                        </h5>

                        <hr>

                    </div>

                    {{-- Driver Photo --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Driver Photo</b>
                            </label>

                            <input
                                type="file"
                                name="driver_photo"
                                id="driver_photo"
                                class="form-control @error('driver_photo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                                onchange="previewImage(
                                    'driver_photo',
                                    'driver-photo-preview'
                                )">

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                            </small>

                            @error('driver_photo')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div
                                id="driver-photo-preview"
                                class="mt-3">
                            </div>

                        </div>

                    </div>

                    {{-- Driving Licence Document --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Driving Licence Document</b>
                            </label>

                            <input
                                type="file"
                                name="driving_license_document"
                                id="driving_license_document"
                                class="form-control @error('driving_license_document') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.pdf">

                            <small class="text-muted">
                                JPG, JPEG, PNG or PDF. Maximum 2 MB.
                            </small>

                            @error('driving_license_document')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            {{-- Licence Document Preview --}}
                            <div
                                id="driving-license-document-preview"
                                class="mt-3">
                            </div>

                        </div>

                    </div>

                    {{-- Aadhar Number --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Aadhar Number</b>
                            </label>

                            <input
                                type="text"
                                name="aadhar_number"
                                id="aadhar_number"
                                maxlength="12"
                                class="form-control @error('aadhar_number') is-invalid @enderror"
                                value="{{ old('aadhar_number') }}"
                                placeholder="Enter Aadhar Number">

                            @error('aadhar_number')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Aadhar Document --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Aadhar Document</b>
                            </label>

                            <input
                                type="file"
                                name="aadhar_document"
                                id="aadhar_document"
                                class="form-control @error('aadhar_document') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp,.pdf">

                            <small class="text-muted">
                                JPG, JPEG, PNG, WEBP or PDF. Maximum 2 MB.
                            </small>

                            @error('aadhar_document')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            {{-- Aadhar Document Preview --}}
                            <div
                                id="aadhar-document-preview"
                                class="mt-3">
                            </div>

                        </div>

                    </div>

                    {{-- PAN Number --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>PAN Number</b>
                            </label>

                            <input
                                type="text"
                                name="pan_number"
                                id="pan_number"
                                maxlength="10"
                                class="form-control @error('pan_number') is-invalid @enderror"
                                value="{{ old('pan_number') }}"
                                placeholder="Enter PAN Number">

                            @error('pan_number')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- PAN Document --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>PAN Document</b>
                            </label>

                            <input
                                type="file"
                                name="pan_document"
                                id="pan_document"
                                class="form-control @error('pan_document') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp,.pdf">

                            <small class="text-muted">
                                JPG, JPEG, PNG, WEBP or PDF. Maximum 2 MB.
                            </small>

                            @error('pan_document')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            {{-- PAN Document Preview --}}
                            <div
                                id="pan-document-preview"
                                class="mt-3">
                            </div>

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

                                    <span class="text-danger">
                                        *
                                    </span>
                                </b>

                            </label>


                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                {{-- Placeholder --}}
                                <option value="">
                                    Select Employment Status
                                </option>


                                {{-- Active --}}
                                <option
                                    value="active"
                                    {{ old('status') === 'active' ? 'selected' : '' }}>

                                    Active

                                </option>


                                {{-- On Leave --}}
                                <option
                                    value="on_leave"
                                    {{ old('status') === 'on_leave' ? 'selected' : '' }}>

                                    On Leave

                                </option>


                                {{-- Notice Period --}}
                                <option
                                    value="notice_period"
                                    {{ old('status') === 'notice_period' ? 'selected' : '' }}>

                                    Notice Period

                                </option>


                                {{-- Resigned --}}
                                <option
                                    value="resigned"
                                    {{ old('status') === 'resigned' ? 'selected' : '' }}>

                                    Resigned

                                </option>


                                {{-- Terminated --}}
                                <option
                                    value="terminated"
                                    {{ old('status') === 'terminated' ? 'selected' : '' }}>

                                    Terminated

                                </option>


                                {{-- Inactive --}}
                                <option
                                    value="inactive"
                                    {{ old('status') === 'inactive' ? 'selected' : '' }}>

                                    Inactive

                                </option>

                            </select>


                            {{-- Validation Error --}}
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

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('driver-management.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Save Driver

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
| Generic File Preview
|--------------------------------------------------------------------------
*/
function previewFile(inputId, previewId)
{
    const fileInput = document.getElementById(inputId);
    const preview   = document.getElementById(previewId);

    if (!fileInput || !preview) {
        return;
    }

    preview.innerHTML = '';

    if (!fileInput.files || !fileInput.files[0]) {
        return;
    }

    const file = fileInput.files[0];

    /*
    |--------------------------------------------------------------------------
    | Allowed File Types
    |--------------------------------------------------------------------------
    */
    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf'
    ];

    if (!allowedTypes.includes(file.type)) {

        alert(
            'Please select a valid JPG, JPEG, PNG, WEBP or PDF file.'
        );

        fileInput.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Maximum File Size - 2 MB
    |--------------------------------------------------------------------------
    */
    const maxSize = 2 * 1024 * 1024;

    if (file.size > maxSize) {

        alert(
            'File size must not exceed 2 MB.'
        );

        fileInput.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Image Preview
    |--------------------------------------------------------------------------
    */
    if (file.type.startsWith('image/')) {

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.innerHTML = `
                <div>

                    <img
                        src="${e.target.result}"
                        alt="Document Preview"
                        class="img-thumbnail"
                        style="
                            width:160px;
                            height:120px;
                            object-fit:cover;
                            border-radius:10px;
                            border:2px solid #28a745;
                            box-shadow:0 2px 8px rgba(0,0,0,.15);
                        ">

                    <div class="mt-2">

                        <small style="color:#28a745;">

                            <i class="fa fa-check-circle"></i>

                            ${escapeHtml(file.name)}

                        </small>

                    </div>

                </div>
            `;
        };

        reader.onerror = function() {

            alert(
                'Unable to preview the selected file.'
            );

            fileInput.value = '';

            preview.innerHTML = '';

        };

        reader.readAsDataURL(file);

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | PDF Preview
    |--------------------------------------------------------------------------
    */
    if (file.type === 'application/pdf') {

        const pdfUrl = URL.createObjectURL(file);

        preview.innerHTML = `
            <div>

                <div
                    style="
                        width:160px;
                        height:120px;
                        border:2px solid #dc3545;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        background:#f8f9fa;
                    ">

                    <div class="text-center">

                        <i
                            class="fa fa-file-pdf-o"
                            style="
                                font-size:45px;
                                color:#dc3545;
                            ">
                        </i>

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
                        class="btn btn-sm btn-outline-danger">

                        <i class="fa fa-eye"></i>

                        View PDF

                    </a>

                    <small
                        class="d-block mt-1"
                        style="color:#28a745;">

                        <i class="fa fa-check-circle"></i>

                        ${escapeHtml(file.name)}

                    </small>

                </div>

            </div>
        `;
    }
}


/*
|--------------------------------------------------------------------------
| Escape HTML
|--------------------------------------------------------------------------
*/
function escapeHtml(value)
{
    return $('<div>')
        .text(value)
        .html();
}


/*
|--------------------------------------------------------------------------
| Driver Photo Preview
|--------------------------------------------------------------------------
*/
function previewImage(inputId, previewId)
{
    const fileInput = document.getElementById(inputId);
    const preview   = document.getElementById(previewId);

    if (!fileInput || !preview) {
        return;
    }

    preview.innerHTML = '';

    if (!fileInput.files || !fileInput.files[0]) {
        return;
    }

    const file = fileInput.files[0];

    /*
    |--------------------------------------------------------------------------
    | Allowed Image Types
    |--------------------------------------------------------------------------
    */
    const allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!allowedTypes.includes(file.type)) {

        alert(
            'Please select a valid JPG, JPEG, PNG or WEBP image.'
        );

        fileInput.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Maximum Size - 2 MB
    |--------------------------------------------------------------------------
    */
    const maxSize = 2 * 1024 * 1024;

    if (file.size > maxSize) {

        alert(
            'Driver photo size must not exceed 2 MB.'
        );

        fileInput.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Image Reader
    |--------------------------------------------------------------------------
    */
    const reader = new FileReader();

    reader.onload = function(e) {

        preview.innerHTML = `
            <div>

                <img
                    src="${e.target.result}"
                    alt="Driver Photo Preview"
                    class="img-thumbnail"
                    style="
                        width:120px;
                        height:120px;
                        object-fit:cover;
                        border-radius:10px;
                        border:2px solid #28a745;
                        box-shadow:0 2px 8px rgba(0,0,0,.15);
                    ">

                <div class="mt-2">

                    <small style="color:#28a745;">

                        <i class="fa fa-check-circle"></i>

                        Driver Photo Selected

                    </small>

                </div>

            </div>
        `;
    };

    reader.onerror = function() {

        alert(
            'Unable to preview the selected image.'
        );

        fileInput.value = '';

        preview.innerHTML = '';

    };

    reader.readAsDataURL(file);
}


/*
|--------------------------------------------------------------------------
| Mobile Number Validation
|--------------------------------------------------------------------------
*/
$('#mobile, #alternate_mobile').on('input', function(){

    this.value = this.value
        .replace(/[^0-9]/g, '')
        .slice(0, 10);

});


/*
|--------------------------------------------------------------------------
| Aadhar Number Validation
|--------------------------------------------------------------------------
*/
$('#aadhar_number').on('input', function(){

    this.value = this.value
        .replace(/[^0-9]/g, '')
        .slice(0, 12);

});


/*
|--------------------------------------------------------------------------
| Pincode Validation
|--------------------------------------------------------------------------
*/
$('#pincode').on('input', function(){

    this.value = this.value
        .replace(/[^0-9]/g, '')
        .slice(0, 6);

});


/*
|--------------------------------------------------------------------------
| PAN Number Formatting
|--------------------------------------------------------------------------
*/
$('#pan_number').on('input', function(){

    this.value = this.value
        .toUpperCase()
        .replace(/[^A-Z0-9]/g, '')
        .slice(0, 10);

});


/*
|--------------------------------------------------------------------------
| Licence Number Formatting
|--------------------------------------------------------------------------
*/
$('#license_number').on('input', function(){

    this.value = this.value
        .toUpperCase()
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Driver Code Formatting
|--------------------------------------------------------------------------
*/
$('#driver_code').on('blur', function(){

    this.value = this.value
        .trim()
        .toUpperCase()
        .replace(/\s+/g, '');

});


/*
|--------------------------------------------------------------------------
| First Name Formatting
|--------------------------------------------------------------------------
*/
$('#first_name').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Last Name Formatting
|--------------------------------------------------------------------------
*/
$('#last_name').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Father Name Formatting
|--------------------------------------------------------------------------
*/
$('#father_name').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Licence Issuing Authority Formatting
|--------------------------------------------------------------------------
*/
$('#license_issuing_authority').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Country Formatting
|--------------------------------------------------------------------------
*/
$('#country').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| State Formatting
|--------------------------------------------------------------------------
*/
$('#state').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| City Formatting
|--------------------------------------------------------------------------
*/
$('#city').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Address Formatting
|--------------------------------------------------------------------------
*/
$('#address').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Email Formatting
|--------------------------------------------------------------------------
*/
$('#email').on('blur', function(){

    this.value = this.value
        .trim()
        .toLowerCase();

});


/*
|--------------------------------------------------------------------------
| Date Helper
|--------------------------------------------------------------------------
*/
function parseDate(dateValue)
{
    if (!dateValue) {
        return null;
    }

    const date = new Date(dateValue + 'T00:00:00');

    if (isNaN(date.getTime())) {
        return null;
    }

    return date;
}


/*
|--------------------------------------------------------------------------
| Get Today
|--------------------------------------------------------------------------
*/
function getToday()
{
    const today = new Date();

    today.setHours(0, 0, 0, 0);

    return today;
}


/*
|--------------------------------------------------------------------------
| Date of Birth Validation
|--------------------------------------------------------------------------
*/
$('#date_of_birth').on('change', function(){

    const dob = this.value;

    if (!dob) {
        return;
    }

    const selectedDob = parseDate(dob);
    const today       = getToday();

    if (!selectedDob) {
        this.value = '';

        alert(
            'Please select a valid Date of Birth.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | DOB Cannot Be Future
    |--------------------------------------------------------------------------
    */
    if (selectedDob > today) {

        alert(
            'Date of Birth cannot be a future date.'
        );

        this.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Existing Joining Date
    |--------------------------------------------------------------------------
    */
    const joiningDate = $('#joining_date').val();

    if (joiningDate) {

        const selectedJoiningDate = parseDate(joiningDate);

        if (selectedJoiningDate && selectedJoiningDate < selectedDob) {

            alert(
                'Date of Birth cannot be after Joining Date.'
            );

            this.value = '';

        }

    }

});


/*
|--------------------------------------------------------------------------
| Joining Date Validation
|--------------------------------------------------------------------------
*/
$('#joining_date').on('change', function(){

    const joiningDate = this.value;

    if (!joiningDate) {
        return;
    }

    const selectedJoiningDate = parseDate(joiningDate);
    const today               = getToday();

    if (!selectedJoiningDate) {

        this.value = '';

        alert(
            'Please select a valid Joining Date.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Joining Date Cannot Be Future
    |--------------------------------------------------------------------------
    */
    if (selectedJoiningDate > today) {

        alert(
            'Joining Date cannot be a future date.'
        );

        this.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Joining Date Cannot Be Before DOB
    |--------------------------------------------------------------------------
    */
    const dob = $('#date_of_birth').val();

    if (dob) {

        const selectedDob = parseDate(dob);

        if (selectedDob && selectedJoiningDate < selectedDob) {

            alert(
                'Joining Date cannot be before Date of Birth.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Validate Existing Resignation Date
    |--------------------------------------------------------------------------
    */
    const resignationDate = $('#resignation_date').val();

    if (resignationDate) {

        const selectedResignationDate = parseDate(resignationDate);

        if (
            selectedResignationDate &&
            selectedResignationDate < selectedJoiningDate
        ) {

            alert(
                'Joining Date cannot be after Resignation Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Validate Existing Last Working Date
    |--------------------------------------------------------------------------
    */
    const lastWorkingDate = $('#last_working_date').val();

    if (lastWorkingDate) {

        const selectedLastWorkingDate = parseDate(lastWorkingDate);

        if (
            selectedLastWorkingDate &&
            selectedLastWorkingDate < selectedJoiningDate
        ) {

            alert(
                'Joining Date cannot be after Last Working Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Validate Existing Termination Date
    |--------------------------------------------------------------------------
    */
    const terminationDate = $('#termination_date').val();

    if (terminationDate) {

        const selectedTerminationDate = parseDate(terminationDate);

        if (
            selectedTerminationDate &&
            selectedTerminationDate < selectedJoiningDate
        ) {

            alert(
                'Joining Date cannot be after Termination Date.'
            );

            this.value = '';

        }

    }

});


/*
|--------------------------------------------------------------------------
| Resignation Date Validation
|--------------------------------------------------------------------------
*/
$('#resignation_date').on('change', function(){

    const resignationDate = this.value;

    if (!resignationDate) {
        return;
    }

    const selectedResignationDate = parseDate(resignationDate);
    const today                  = getToday();

    if (!selectedResignationDate) {

        this.value = '';

        alert(
            'Please select a valid Resignation Date.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Resignation Date Cannot Be Future
    |--------------------------------------------------------------------------
    */
    if (selectedResignationDate > today) {

        alert(
            'Resignation Date cannot be a future date.'
        );

        this.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Joining Date Check
    |--------------------------------------------------------------------------
    */
    const joiningDate = $('#joining_date').val();

    if (joiningDate) {

        const selectedJoiningDate = parseDate(joiningDate);

        if (
            selectedJoiningDate &&
            selectedResignationDate < selectedJoiningDate
        ) {

            alert(
                'Resignation Date cannot be before Joining Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Last Working Date Check
    |--------------------------------------------------------------------------
    */
    const lastWorkingDate = $('#last_working_date').val();

    if (lastWorkingDate) {

        const selectedLastWorkingDate = parseDate(lastWorkingDate);

        if (
            selectedLastWorkingDate &&
            selectedResignationDate > selectedLastWorkingDate
        ) {

            alert(
                'Resignation Date cannot be after Last Working Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Termination Date Check
    |--------------------------------------------------------------------------
    */
    const terminationDate = $('#termination_date').val();

    if (terminationDate) {

        const selectedTerminationDate = parseDate(terminationDate);

        if (
            selectedTerminationDate &&
            selectedResignationDate > selectedTerminationDate
        ) {

            alert(
                'Resignation Date cannot be after Termination Date.'
            );

            this.value = '';

        }

    }

});


/*
|--------------------------------------------------------------------------
| Last Working Date Validation
|--------------------------------------------------------------------------
*/
$('#last_working_date').on('change', function(){

    const lastWorkingDate = this.value;

    if (!lastWorkingDate) {
        return;
    }

    const selectedLastWorkingDate = parseDate(lastWorkingDate);
    const today                  = getToday();

    if (!selectedLastWorkingDate) {

        this.value = '';

        alert(
            'Please select a valid Last Working Date.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Last Working Date Cannot Be Future
    |--------------------------------------------------------------------------
    */
    if (selectedLastWorkingDate > today) {

        alert(
            'Last Working Date cannot be a future date.'
        );

        this.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Joining Date Check
    |--------------------------------------------------------------------------
    */
    const joiningDate = $('#joining_date').val();

    if (joiningDate) {

        const selectedJoiningDate = parseDate(joiningDate);

        if (
            selectedJoiningDate &&
            selectedLastWorkingDate < selectedJoiningDate
        ) {

            alert(
                'Last Working Date cannot be before Joining Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Resignation Date Check
    |--------------------------------------------------------------------------
    */
    const resignationDate = $('#resignation_date').val();

    if (resignationDate) {

        const selectedResignationDate = parseDate(resignationDate);

        if (
            selectedResignationDate &&
            selectedLastWorkingDate < selectedResignationDate
        ) {

            alert(
                'Last Working Date cannot be before Resignation Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Termination Date Check
    |--------------------------------------------------------------------------
    */
    const terminationDate = $('#termination_date').val();

    if (terminationDate) {

        const selectedTerminationDate = parseDate(terminationDate);

        if (
            selectedTerminationDate &&
            selectedLastWorkingDate > selectedTerminationDate
        ) {

            alert(
                'Last Working Date cannot be after Termination Date.'
            );

            this.value = '';

        }

    }

});


/*
|--------------------------------------------------------------------------
| Termination Date Validation
|--------------------------------------------------------------------------
*/
$('#termination_date').on('change', function(){

    const terminationDate = this.value;

    if (!terminationDate) {
        return;
    }

    const selectedTerminationDate = parseDate(terminationDate);
    const today                   = getToday();

    if (!selectedTerminationDate) {

        this.value = '';

        alert(
            'Please select a valid Termination Date.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Termination Date Cannot Be Future
    |--------------------------------------------------------------------------
    */
    if (selectedTerminationDate > today) {

        alert(
            'Termination Date cannot be a future date.'
        );

        this.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Joining Date Check
    |--------------------------------------------------------------------------
    */
    const joiningDate = $('#joining_date').val();

    if (joiningDate) {

        const selectedJoiningDate = parseDate(joiningDate);

        if (
            selectedJoiningDate &&
            selectedTerminationDate < selectedJoiningDate
        ) {

            alert(
                'Termination Date cannot be before Joining Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Resignation Date Check
    |--------------------------------------------------------------------------
    */
    const resignationDate = $('#resignation_date').val();

    if (resignationDate) {

        const selectedResignationDate = parseDate(resignationDate);

        if (
            selectedResignationDate &&
            selectedTerminationDate < selectedResignationDate
        ) {

            alert(
                'Termination Date cannot be before Resignation Date.'
            );

            this.value = '';

            return;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Last Working Date Check
    |--------------------------------------------------------------------------
    */
    const lastWorkingDate = $('#last_working_date').val();

    if (lastWorkingDate) {

        const selectedLastWorkingDate = parseDate(lastWorkingDate);

        if (
            selectedTerminationDate < selectedLastWorkingDate
        ) {

            alert(
                'Termination Date cannot be before Last Working Date.'
            );

            this.value = '';

        }

    }

});


/*
|--------------------------------------------------------------------------
| Licence Issue Date Validation
|--------------------------------------------------------------------------
*/
$('#license_issue_date').on('change', function(){

    const issueDate  = this.value;
    const expiryDate = $('#license_expiry_date').val();

    if (!issueDate) {
        return;
    }

    const selectedIssueDate = parseDate(issueDate);
    const today             = getToday();

    if (!selectedIssueDate) {

        this.value = '';

        alert(
            'Please select a valid Licence Issue Date.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Issue Date Cannot Be Future
    |--------------------------------------------------------------------------
    */
    if (selectedIssueDate > today) {

        alert(
            'Licence Issue Date cannot be a future date.'
        );

        this.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Issue Date Cannot Be After Expiry
    |--------------------------------------------------------------------------
    */
    if (expiryDate) {

        const selectedExpiryDate = parseDate(expiryDate);

        if (
            selectedExpiryDate &&
            selectedIssueDate > selectedExpiryDate
        ) {

            alert(
                'Licence Issue Date cannot be after Licence Expiry Date.'
            );

            this.value = '';

        }

    }

});


/*
|--------------------------------------------------------------------------
| Licence Expiry Date Validation
|--------------------------------------------------------------------------
*/
$('#license_expiry_date').on('change', function(){

    const expiryDate = this.value;
    const issueDate  = $('#license_issue_date').val();

    if (!expiryDate) {
        return;
    }

    const selectedExpiryDate = parseDate(expiryDate);

    if (!selectedExpiryDate) {

        this.value = '';

        alert(
            'Please select a valid Licence Expiry Date.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Expiry Cannot Be Before Issue
    |--------------------------------------------------------------------------
    */
    if (issueDate) {

        const selectedIssueDate = parseDate(issueDate);

        if (
            selectedIssueDate &&
            selectedExpiryDate < selectedIssueDate
        ) {

            alert(
                'Licence Expiry Date cannot be before Licence Issue Date.'
            );

            this.value = '';

            return;
        }

    }

});


/*
|--------------------------------------------------------------------------
| Driver Photo - Validation + Preview
|--------------------------------------------------------------------------
*/
$('#driver_photo').on('change', function(){

    previewImage(
        'driver_photo',
        'driver-photo-preview'
    );

});


/*
|--------------------------------------------------------------------------
| Driving Licence Document - Preview
|--------------------------------------------------------------------------
*/
$('#driving_license_document').on('change', function(){

    previewFile(
        'driving_license_document',
        'driving-license-document-preview'
    );

});


/*
|--------------------------------------------------------------------------
| Aadhar Document - Preview
|--------------------------------------------------------------------------
*/
$('#aadhar_document').on('change', function(){

    previewFile(
        'aadhar_document',
        'aadhar-document-preview'
    );

});


/*
|--------------------------------------------------------------------------
| PAN Document - Preview
|--------------------------------------------------------------------------
*/
$('#pan_document').on('change', function(){

    previewFile(
        'pan_document',
        'pan-document-preview'
    );

});


/*
|--------------------------------------------------------------------------
| Validate Employment Dates Before Submit
|--------------------------------------------------------------------------
*/
function validateEmploymentDates()
{
    const dob            = $('#date_of_birth').val();
    const joiningDate    = $('#joining_date').val();
    const resignationDate = $('#resignation_date').val();
    const lastWorkingDate = $('#last_working_date').val();
    const terminationDate = $('#termination_date').val();

    const today = getToday();

    const dobDate = parseDate(dob);
    const joiningDateObj = parseDate(joiningDate);
    const resignationDateObj = parseDate(resignationDate);
    const lastWorkingDateObj = parseDate(lastWorkingDate);
    const terminationDateObj = parseDate(terminationDate);

    /*
    |--------------------------------------------------------------------------
    | DOB -> Joining Date
    |--------------------------------------------------------------------------
    */
    if (
        dobDate &&
        joiningDateObj &&
        joiningDateObj < dobDate
    ) {

        alert(
            'Joining Date cannot be before Date of Birth.'
        );

        $('#joining_date').focus();

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Joining Date Cannot Be Future
    |--------------------------------------------------------------------------
    */
    if (
        joiningDateObj &&
        joiningDateObj > today
    ) {

        alert(
            'Joining Date cannot be a future date.'
        );

        $('#joining_date').focus();

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Resignation Date
    |--------------------------------------------------------------------------
    */
    if (resignationDateObj) {

        if (resignationDateObj > today) {

            alert(
                'Resignation Date cannot be a future date.'
            );

            $('#resignation_date').focus();

            return false;
        }

        if (
            joiningDateObj &&
            resignationDateObj < joiningDateObj
        ) {

            alert(
                'Resignation Date cannot be before Joining Date.'
            );

            $('#resignation_date').focus();

            return false;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Last Working Date
    |--------------------------------------------------------------------------
    */
    if (lastWorkingDateObj) {

        if (lastWorkingDateObj > today) {

            alert(
                'Last Working Date cannot be a future date.'
            );

            $('#last_working_date').focus();

            return false;
        }

        if (
            joiningDateObj &&
            lastWorkingDateObj < joiningDateObj
        ) {

            alert(
                'Last Working Date cannot be before Joining Date.'
            );

            $('#last_working_date').focus();

            return false;
        }

        if (
            resignationDateObj &&
            lastWorkingDateObj < resignationDateObj
        ) {

            alert(
                'Last Working Date cannot be before Resignation Date.'
            );

            $('#last_working_date').focus();

            return false;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Termination Date
    |--------------------------------------------------------------------------
    */
    if (terminationDateObj) {

        if (terminationDateObj > today) {

            alert(
                'Termination Date cannot be a future date.'
            );

            $('#termination_date').focus();

            return false;
        }

        if (
            joiningDateObj &&
            terminationDateObj < joiningDateObj
        ) {

            alert(
                'Termination Date cannot be before Joining Date.'
            );

            $('#termination_date').focus();

            return false;
        }

        if (
            resignationDateObj &&
            terminationDateObj < resignationDateObj
        ) {

            alert(
                'Termination Date cannot be before Resignation Date.'
            );

            $('#termination_date').focus();

            return false;
        }

        if (
            lastWorkingDateObj &&
            terminationDateObj < lastWorkingDateObj
        ) {

            alert(
                'Termination Date cannot be before Last Working Date.'
            );

            $('#termination_date').focus();

            return false;
        }

    }


    return true;
}


/*
|--------------------------------------------------------------------------
| Trim Text Inputs Before Submit
|--------------------------------------------------------------------------
*/
$('form').on('submit', function(e){

    /*
    |--------------------------------------------------------------------------
    | Employment Date Validation
    |--------------------------------------------------------------------------
    */
    if (!validateEmploymentDates()) {

        e.preventDefault();

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Basic Information
    |--------------------------------------------------------------------------
    */
    $('#driver_code').val(
        $('#driver_code').val()
            .trim()
            .toUpperCase()
            .replace(/\s+/g, '')
    );

    $('#first_name').val(
        $('#first_name').val()
            .replace(/\s+/g, ' ')
            .trim()
    );

    $('#last_name').val(
        $('#last_name').val()
            .replace(/\s+/g, ' ')
            .trim()
    );

    $('#father_name').val(
        $('#father_name').val()
            .replace(/\s+/g, ' ')
            .trim()
    );


    /*
    |--------------------------------------------------------------------------
    | Contact Information
    |--------------------------------------------------------------------------
    */
    $('#mobile').val(
        $('#mobile').val()
            .replace(/[^0-9]/g, '')
            .slice(0, 10)
    );

    $('#alternate_mobile').val(
        $('#alternate_mobile').val()
            .replace(/[^0-9]/g, '')
            .slice(0, 10)
    );

    $('#email').val(
        $('#email').val()
            .trim()
            .toLowerCase()
    );


    /*
    |--------------------------------------------------------------------------
    | Address Information
    |--------------------------------------------------------------------------
    */
    $('#country').val(
        $('#country').val()
            .replace(/\s+/g, ' ')
            .trim()
    );

    $('#state').val(
        $('#state').val()
            .replace(/\s+/g, ' ')
            .trim()
    );

    $('#city').val(
        $('#city').val()
            .replace(/\s+/g, ' ')
            .trim()
    );

    $('#pincode').val(
        $('#pincode').val()
            .replace(/[^0-9]/g, '')
            .slice(0, 6)
    );

    $('#address').val(
        $('#address').val()
            .replace(/\s+/g, ' ')
            .trim()
    );


    /*
    |--------------------------------------------------------------------------
    | Licence Information
    |--------------------------------------------------------------------------
    */
    $('#license_number').val(
        $('#license_number').val()
            .trim()
            .toUpperCase()
            .replace(/\s+/g, ' ')
    );

    $('#license_issuing_authority').val(
        $('#license_issuing_authority').val()
            .replace(/\s+/g, ' ')
            .trim()
    );


    /*
    |--------------------------------------------------------------------------
    | Identity Documents
    |--------------------------------------------------------------------------
    */
    $('#aadhar_number').val(
        $('#aadhar_number').val()
            .replace(/[^0-9]/g, '')
            .slice(0, 12)
    );

    $('#pan_number').val(
        $('#pan_number').val()
            .toUpperCase()
            .replace(/[^A-Z0-9]/g, '')
            .slice(0, 10)
    );

});


/*
|--------------------------------------------------------------------------
| Prevent Double Form Submission
|--------------------------------------------------------------------------
*/
$('form').on('submit', function(e){

    /*
    |--------------------------------------------------------------------------
    | Do Not Disable Button If Validation Failed
    |--------------------------------------------------------------------------
    */
    if (e.isDefaultPrevented()) {
        return;
    }

    const form = this;

    const submitButton = $(form)
        .find('button[type="submit"]');

    if (submitButton.length) {

        submitButton
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Saving Driver...'
            );

    }

});


/*
|--------------------------------------------------------------------------
| Document Ready
|--------------------------------------------------------------------------
*/
$(document).ready(function(){

    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    */
    if ($('#country').val() === '') {

        $('#country').val('India');

    }
    

});

</script>

@endpush