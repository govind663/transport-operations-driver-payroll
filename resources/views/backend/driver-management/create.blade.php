@extends('backend.layouts.master')

@section('title')
    Create Driver
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
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="driver_code"
                                id="driver_code"
                                class="form-control @error('driver_code') is-invalid @enderror"
                                value="{{ old('driver_code') }}"
                                placeholder="Generating..."
                                readonly
                            >

                            <small class="text-muted">
                                Driver Code will be generated automatically.
                                Example: DRV001, DRV002
                            </small>

                            @error('driver_code')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
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
                    {{-- DRIVER QUALIFICATION --}}
                    {{-- ========================================================= --}}
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

                                        <th style="width:20%;">
                                            Qualification
                                        </th>

                                        <th style="width:22%;">
                                            Institute / Board
                                        </th>

                                        <th style="width:12%;">
                                            Passing Year
                                        </th>

                                        <th style="width:14%;">
                                            Percentage / Grade
                                        </th>

                                        <th style="width:22%;">
                                            Document
                                        </th>

                                        <th style="width:10%;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody id="qualification-wrapper">

                                    <tr
                                        class="qualification-row"
                                        data-index="0">

                                        {{-- Qualification --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[0][qualification]"
                                                class="form-control"
                                                placeholder="e.g. HSC, Diploma, B.Com">

                                            @error('qualifications.0.qualification')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Institute / Board --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[0][institute]"
                                                class="form-control"
                                                placeholder="Institute / Board">

                                            @error('qualifications.0.institute')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Passing Year --}}
                                        <td>

                                            <input
                                                type="number"
                                                name="qualifications[0][passing_year]"
                                                class="form-control"
                                                min="1900"
                                                max="{{ date('Y') }}"
                                                placeholder="YYYY">

                                            @error('qualifications.0.passing_year')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Percentage / Grade --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="qualifications[0][grade]"
                                                class="form-control"
                                                placeholder="72% / A">

                                            @error('qualifications.0.grade')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Document --}}
                                        <td>

                                            <input
                                                type="file"
                                                name="qualification_documents[0]"
                                                class="form-control qualification-document"
                                                accept=".jpg,.jpeg,.png,.webp,.pdf">

                                            <small class="text-muted d-block mt-1">
                                                JPG, JPEG, PNG, WEBP or PDF. Max 5 MB.
                                            </small>

                                            <div
                                                class="qualification-document-preview mt-2">
                                            </div>

                                            @error('qualification_documents.0')

                                                <small class="text-danger d-block">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Action --}}
                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm remove-qualification"
                                                disabled
                                                title="Remove">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

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

                                        <th style="width:18%;">
                                            Address
                                        </th>

                                        <th style="width:8%;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody id="nominee-wrapper">

                                    <tr
                                        class="nominee-row"
                                        data-index="0">

                                        {{-- Profile Image --}}
                                        <td>

                                            <input
                                                type="file"
                                                name="nominee_profile_images[0]"
                                                class="form-control nominee-profile-image"
                                                accept=".jpg,.jpeg,.png,.webp">

                                            <small class="text-muted d-block mt-1">
                                                JPG, JPEG, PNG or WEBP. Max 2 MB.
                                            </small>

                                            <div
                                                class="nominee-profile-preview mt-2">
                                            </div>

                                            @error('nominee_profile_images.0')

                                                <small class="text-danger d-block">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Nominee Name --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[0][name]"
                                                class="form-control"
                                                placeholder="Nominee Name">

                                            @error('nominees.0.name')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Relationship --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[0][relationship]"
                                                class="form-control"
                                                placeholder="Father / Mother / Wife">

                                            @error('nominees.0.relationship')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Date of Birth --}}
                                        <td>

                                            <input
                                                type="date"
                                                name="nominees[0][date_of_birth]"
                                                class="form-control">

                                            @error('nominees.0.date_of_birth')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Mobile --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="nominees[0][mobile]"
                                                maxlength="10"
                                                inputmode="numeric"
                                                class="form-control nominee-mobile"
                                                placeholder="10 Digit">

                                            @error('nominees.0.mobile')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Percentage --}}
                                        <td>

                                            <input
                                                type="number"
                                                name="nominees[0][percentage]"
                                                class="form-control nominee-percentage"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                placeholder="100">

                                            @error('nominees.0.percentage')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Address --}}
                                        <td>

                                            <textarea
                                                name="nominees[0][address]"
                                                class="form-control"
                                                rows="2"
                                                placeholder="Nominee Address"></textarea>

                                            @error('nominees.0.address')

                                                <small class="text-danger">
                                                    {{ $message }}
                                                </small>

                                            @enderror

                                        </td>


                                        {{-- Action --}}
                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm remove-nominee"
                                                disabled
                                                title="Remove">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

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
                                                placeholder="Account Holder Name">

                                        </td>


                                        {{-- Bank Name --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="bank_details[bank_name]"
                                                id="bank_name"
                                                class="form-control"
                                                placeholder="Bank Name">

                                        </td>


                                        {{-- Account Number --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="bank_details[account_number]"
                                                id="bank_account_number"
                                                class="form-control"
                                                placeholder="Account Number"
                                                autocomplete="off">

                                        </td>


                                        {{-- IFSC --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="bank_details[ifsc_code]"
                                                id="bank_ifsc_code"
                                                maxlength="11"
                                                class="form-control text-uppercase"
                                                placeholder="HDFC0001234"
                                                autocomplete="off">

                                        </td>


                                        {{-- Branch --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="bank_details[branch_name]"
                                                id="bank_branch_name"
                                                class="form-control"
                                                placeholder="Branch Name">

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

                                                <option value="savings">
                                                    Savings
                                                </option>

                                                <option value="current">
                                                    Current
                                                </option>

                                            </select>

                                        </td>


                                        {{-- UPI --}}
                                        <td>

                                            <input
                                                type="text"
                                                name="bank_details[upi_id]"
                                                id="bank_upi_id"
                                                class="form-control"
                                                placeholder="example@upi"
                                                autocomplete="off">

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
                    {{-- PF STATUS --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="pf_status">

                                <b>
                                    PF Applicable

                                    <span class="text-danger">
                                        *
                                    </span>
                                </b>

                            </label>

                            <select
                                name="pf_status"
                                id="pf_status"
                                class="form-control custom-select2 @error('pf_status') is-invalid @enderror">

                                <option value="">
                                    Select PF Status
                                </option>

                                @foreach(\App\Models\Driver::PF_STATUSES as $pfStatus)

                                    <option
                                        value="{{ $pfStatus }}"
                                        {{ old('pf_status') === $pfStatus ? 'selected' : '' }}>

                                        {{ ucfirst($pfStatus) }}

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">
                                Select whether PF is applicable for this driver.
                            </small>

                            @error('pf_status')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

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

                                    <span class="text-danger">
                                        *
                                    </span>
                                </b>

                            </label>

                            <select
                                name="document_status"
                                id="document_status"
                                class="form-control custom-select2 @error('document_status') is-invalid @enderror">

                                <option value="">
                                    Select Document Status
                                </option>

                                @foreach(\App\Models\Driver::DOCUMENT_STATUSES as $documentStatus)

                                    <option
                                        value="{{ $documentStatus }}"
                                        {{ old('document_status') === $documentStatus ? 'selected' : '' }}>

                                        {{ ucfirst($documentStatus) }}

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">
                                Current status of required driver documents.
                            </small>

                            @error('document_status')

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


        const IMAGE_TYPES = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        const DOCUMENT_TYPES = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'application/pdf'
        ];


        const PAN_REGEX =
            /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;


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
        | IMAGE CHECK
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
                IMAGE_TYPES.includes(file.type) ||
                [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ].includes(extension)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PDF CHECK
        |--------------------------------------------------------------------------
        */

        function isPdfFile(file)
        {
            if (!file) {
                return false;
            }

            return (
                file.type === 'application/pdf' ||
                getFileExtension(file) === 'pdf'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESET FILE
        |--------------------------------------------------------------------------
        */

        function resetFile(
            input,
            preview
        )
        {
            if (input) {
                input.value = '';
            }

            if (preview) {
                preview.innerHTML = '';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | GENERIC FILE PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewFile(
            inputId,
            previewId,
            maxSize = DOCUMENT_MAX_SIZE,
            title = 'Document'
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
                return;
            }


            preview.innerHTML = '';


            if (
                !fileInput.files ||
                !fileInput.files[0]
            ) {
                return;
            }


            const file =
                fileInput.files[0];


            /*
            |--------------------------------------------------------------------------
            | FILE TYPE
            |--------------------------------------------------------------------------
            */

            if (
                !DOCUMENT_TYPES.includes(file.type) &&
                ![
                    'jpg',
                    'jpeg',
                    'png',
                    'webp',
                    'pdf'
                ].includes(
                    getFileExtension(file)
                )
            ) {

                alert(
                    `${title} must be JPG, JPEG, PNG, WEBP or PDF.`
                );

                resetFile(
                    fileInput,
                    preview
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | FILE SIZE
            |--------------------------------------------------------------------------
            */

            if (
                file.size > maxSize
            ) {

                alert(
                    `${title} size must not exceed ${
                        maxSize / 1024 / 1024
                    } MB.`
                );

                resetFile(
                    fileInput,
                    preview
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | IMAGE PREVIEW
            |--------------------------------------------------------------------------
            */

            if (
                isImageFile(file)
            ) {

                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        preview.innerHTML = `

                            <div>

                                <img
                                    src="${event.target.result}"
                                    alt="${escapeHtml(title)} Preview"
                                    class="img-thumbnail"
                                    style="
                                        width:160px;
                                        height:120px;
                                        object-fit:cover;
                                        border-radius:10px;
                                        border:2px solid #28a745;
                                        box-shadow:0 2px 8px rgba(0,0,0,.15);
                                        cursor:pointer;
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
                                        class="text-success">

                                        <i class="fa fa-check-circle"></i>

                                        ${escapeHtml(file.name)}

                                    </small>

                                </div>

                            </div>
                        `;
                    };


                reader.onerror =
                    function () {

                        alert(
                            `Unable to preview ${title}.`
                        );

                        resetFile(
                            fileInput,
                            preview
                        );
                    };


                reader.readAsDataURL(file);

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | PDF PREVIEW
            |--------------------------------------------------------------------------
            */

            if (
                isPdfFile(file)
            ) {

                const pdfUrl =
                    URL.createObjectURL(file);


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

                        </div>


                        <div class="mt-1">

                            <small
                                class="text-success">

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
        | DRIVER PHOTO PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewDriverPhoto()
        {
            const fileInput =
                document.getElementById(
                    'driver_photo'
                );

            const preview =
                document.getElementById(
                    'driver-photo-preview'
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
                !fileInput.files[0]
            ) {
                return;
            }


            const file =
                fileInput.files[0];


            if (
                !IMAGE_TYPES.includes(
                    file.type
                ) &&
                ![
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ].includes(
                    getFileExtension(file)
                )
            ) {

                alert(
                    'Driver photo must be JPG, JPEG, PNG or WEBP.'
                );

                resetFile(
                    fileInput,
                    preview
                );

                return;
            }


            if (
                file.size >
                DRIVER_PHOTO_MAX_SIZE
            ) {

                alert(
                    'Driver photo size must not exceed 2 MB.'
                );

                resetFile(
                    fileInput,
                    preview
                );

                return;
            }


            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    preview.innerHTML = `

                        <div>

                            <img
                                src="${event.target.result}"
                                alt="Driver Photo Preview"
                                class="img-thumbnail"
                                style="
                                    width:120px;
                                    height:120px;
                                    object-fit:cover;
                                    border-radius:10px;
                                    border:2px solid #28a745;
                                    box-shadow:0 2px 8px rgba(0,0,0,.15);
                                    cursor:pointer;
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
                                    class="text-success">

                                    <i class="fa fa-check-circle"></i>

                                    Driver Photo Selected

                                </small>

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
                        fileInput,
                        preview
                    );
                };


            reader.readAsDataURL(file);
        }


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
                !input.files[0]
            ) {
                return;
            }


            const file =
                input.files[0];


            if (
                !DOCUMENT_TYPES.includes(
                    file.type
                ) &&
                ![
                    'jpg',
                    'jpeg',
                    'png',
                    'webp',
                    'pdf'
                ].includes(
                    getFileExtension(file)
                )
            ) {

                alert(
                    'Qualification document must be JPG, JPEG, PNG, WEBP or PDF.'
                );

                input.value = '';

                return;
            }


            if (
                file.size >
                DOCUMENT_MAX_SIZE
            ) {

                alert(
                    'Qualification document size must not exceed 5 MB.'
                );

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
                                        font-size:30px;
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
        | ADD QUALIFICATION
        |--------------------------------------------------------------------------
        */

        $('#add-qualification').on(
            'click',
            function () {

                const index =
                    qualificationIndex;


                const currentYear =
                    new Date().getFullYear();


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
                                Max 5 MB.

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
        | QUALIFICATION DOCUMENT CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.qualification-document',
            function () {

                previewQualificationDocument(
                    this
                );
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
            function () {

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
                !input.files[0]
            ) {
                return;
            }


            const file =
                input.files[0];


            if (
                !IMAGE_TYPES.includes(
                    file.type
                ) &&
                ![
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ].includes(
                    getFileExtension(file)
                )
            ) {

                alert(
                    'Nominee profile image must be JPG, JPEG, PNG or WEBP.'
                );

                input.value = '';

                return;
            }


            if (
                file.size >
                NOMINEE_PHOTO_MAX_SIZE
            ) {

                alert(
                    'Nominee profile image size must not exceed 2 MB.'
                );

                input.value = '';

                return;
            }


            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    preview.html(`

                        <div>

                            <img
                                src="${event.target.result}"
                                alt="Nominee Profile"
                                class="img-thumbnail"
                                style="
                                    width:75px;
                                    height:75px;
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

                                    Selected

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
        | ADD NOMINEE
        |--------------------------------------------------------------------------
        */

        $('#add-nominee').on(
            'click',
            function () {

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
                                Max 2 MB.

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
                                class="form-control">

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
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE PROFILE IMAGE CHANGE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.nominee-profile-image',
            function () {

                previewNomineeProfile(
                    this
                );
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
            function () {

                $(this)
                    .closest(
                        '.nominee-row'
                    )
                    .remove();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOMINEE MOBILE VALIDATION
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.nominee-mobile',
            function () {

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
        | NOMINEE PERCENTAGE VALIDATION
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.nominee-percentage',
            function () {

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
        | BANK IFSC FORMATTING
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#bank_ifsc_code',
            function () {

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
            function () {

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
        | BANK ACCOUNT HOLDER
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#bank_account_holder_name',
            function () {

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
        | BANK NAME
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#bank_name',
            function () {

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
        | BANK BRANCH
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'blur',
            '#bank_branch_name',
            function () {

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
            function () {

                this.value =
                    this.value
                        .trim()
                        .toLowerCase();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | MOBILE NUMBER
        |--------------------------------------------------------------------------
        */

        $('#mobile, #alternate_mobile')
            .on(
                'input',
                function () {

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
        | AADHAAR NUMBER
        |--------------------------------------------------------------------------
        */

        $('#aadhar_number')
            .on(
                'input',
                function () {

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

        $('#pincode')
            .on(
                'input',
                function () {

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

        $('#pan_number')
            .on(
                'input',
                function () {

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

        $('#license_number')
            .on(
                'input',
                function () {

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

        $('#driver_code')
            .on(
                'blur',
                function () {

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
        | NAME FORMATTING
        |--------------------------------------------------------------------------
        */

        $('#first_name, #last_name, #father_name')
            .on(
                'blur',
                function () {

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

        $('#license_issuing_authority')
            .on(
                'blur',
                function () {

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
        | COUNTRY / STATE / CITY / ADDRESS
        |--------------------------------------------------------------------------
        */

        $('#country, #state, #city, #address')
            .on(
                'blur',
                function () {

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

        $('#email')
            .on(
                'blur',
                function () {

                    this.value =
                        this.value
                            .trim()
                            .toLowerCase();
                }
            );


        /*
        |--------------------------------------------------------------------------
        | DRIVER PHOTO EVENT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#driver_photo',
            function () {

                previewDriverPhoto();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DRIVING LICENSE DOCUMENT EVENT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#driving_license_document',
            function () {

                previewFile(
                    'driving_license_document',
                    'driving-license-document-preview',
                    DOCUMENT_MAX_SIZE,
                    'Driving Licence Document'
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | AADHAAR DOCUMENT EVENT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#aadhar_document',
            function () {

                previewFile(
                    'aadhar_document',
                    'aadhar-document-preview',
                    DOCUMENT_MAX_SIZE,
                    'Aadhaar Document'
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PAN DOCUMENT EVENT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '#pan_document',
            function () {

                previewFile(
                    'pan_document',
                    'pan-document-preview',
                    DOCUMENT_MAX_SIZE,
                    'PAN Document'
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DATE HELPER
        |--------------------------------------------------------------------------
        */

        function parseDate(
            dateValue
        )
        {
            if (!dateValue) {
                return null;
            }


            const date =
                new Date(
                    dateValue + 'T00:00:00'
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
        | DATE OF BIRTH VALIDATION
        |--------------------------------------------------------------------------
        */

        $('#date_of_birth')
            .on(
                'change',
                function () {

                    if (!this.value) {
                        return;
                    }


                    const dob =
                        parseDate(
                            this.value
                        );


                    const today =
                        getToday();


                    if (!dob) {

                        this.value = '';

                        alert(
                            'Please select a valid Date of Birth.'
                        );

                        return;
                    }


                    if (
                        dob > today
                    ) {

                        this.value = '';

                        alert(
                            'Date of Birth cannot be a future date.'
                        );

                        return;
                    }


                    const joiningDate =
                        parseDate(
                            $('#joining_date').val()
                        );


                    if (
                        joiningDate &&
                        joiningDate < dob
                    ) {

                        this.value = '';

                        alert(
                            'Date of Birth cannot be after Joining Date.'
                        );
                    }
                }
            );


        /*
        |--------------------------------------------------------------------------
        | JOINING DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        $('#joining_date')
            .on(
                'change',
                function () {

                    validateEmploymentDates();
                }
            );


        /*
        |--------------------------------------------------------------------------
        | RESIGNATION DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        $('#resignation_date')
            .on(
                'change',
                function () {

                    validateEmploymentDates();
                }
            );


        /*
        |--------------------------------------------------------------------------
        | LAST WORKING DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        $('#last_working_date')
            .on(
                'change',
                function () {

                    validateEmploymentDates();
                }
            );


        /*
        |--------------------------------------------------------------------------
        | TERMINATION DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        $('#termination_date')
            .on(
                'change',
                function () {

                    validateEmploymentDates();
                }
            );


        /*
        |--------------------------------------------------------------------------
        | EMPLOYMENT DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        function validateEmploymentDates()
        {
            const dob =
                parseDate(
                    $('#date_of_birth').val()
                );


            const joining =
                parseDate(
                    $('#joining_date').val()
                );


            const resignation =
                parseDate(
                    $('#resignation_date').val()
                );


            const lastWorking =
                parseDate(
                    $('#last_working_date').val()
                );


            const termination =
                parseDate(
                    $('#termination_date').val()
                );


            const today =
                getToday();


            /*
            |--------------------------------------------------------------------------
            | DOB -> Joining
            |--------------------------------------------------------------------------
            */

            if (
                dob &&
                joining &&
                joining < dob
            ) {

                alert(
                    'Joining Date cannot be before Date of Birth.'
                );

                $('#joining_date').focus();

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Joining Date
            |--------------------------------------------------------------------------
            */

            if (
                joining &&
                joining > today
            ) {

                alert(
                    'Joining Date cannot be a future date.'
                );

                $('#joining_date')
                    .focus();

                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | Resignation Date
            |--------------------------------------------------------------------------
            */

            if (
                resignation
            ) {

                if (
                    resignation > today
                ) {

                    alert(
                        'Resignation Date cannot be a future date.'
                    );

                    $('#resignation_date')
                        .focus();

                    return false;
                }


                if (
                    joining &&
                    resignation < joining
                ) {

                    alert(
                        'Resignation Date cannot be before Joining Date.'
                    );

                    $('#resignation_date')
                        .focus();

                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Last Working Date
            |--------------------------------------------------------------------------
            */

            if (
                lastWorking
            ) {

                if (
                    lastWorking > today
                ) {

                    alert(
                        'Last Working Date cannot be a future date.'
                    );

                    $('#last_working_date')
                        .focus();

                    return false;
                }


                if (
                    joining &&
                    lastWorking < joining
                ) {

                    alert(
                        'Last Working Date cannot be before Joining Date.'
                    );

                    $('#last_working_date')
                        .focus();

                    return false;
                }


                if (
                    resignation &&
                    lastWorking < resignation
                ) {

                    alert(
                        'Last Working Date cannot be before Resignation Date.'
                    );

                    $('#last_working_date')
                        .focus();

                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Termination Date
            |--------------------------------------------------------------------------
            */

            if (
                termination
            ) {

                if (
                    termination > today
                ) {

                    alert(
                        'Termination Date cannot be a future date.'
                    );

                    $('#termination_date')
                        .focus();

                    return false;
                }


                if (
                    joining &&
                    termination < joining
                ) {

                    alert(
                        'Termination Date cannot be before Joining Date.'
                    );

                    $('#termination_date')
                        .focus();

                    return false;
                }


                if (
                    resignation &&
                    termination < resignation
                ) {

                    alert(
                        'Termination Date cannot be before Resignation Date.'
                    );

                    $('#termination_date')
                        .focus();

                    return false;
                }


                if (
                    lastWorking &&
                    termination < lastWorking
                ) {

                    alert(
                        'Termination Date cannot be before Last Working Date.'
                    );

                    $('#termination_date')
                        .focus();

                    return false;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | EXIT EVENT CHECK
            |--------------------------------------------------------------------------
            */

            if (
                resignation &&
                termination
            ) {

                alert(
                    'A driver cannot have both Resignation Date and Termination Date.'
                );

                $('#termination_date')
                    .focus();

                return false;
            }


            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | LICENSE ISSUE DATE
        |--------------------------------------------------------------------------
        */

        $('#license_issue_date')
            .on(
                'change',
                function () {

                    validateLicenseDates();
                }
            );


        /*
        |--------------------------------------------------------------------------
        | LICENSE EXPIRY DATE
        |--------------------------------------------------------------------------
        */

        $('#license_expiry_date')
            .on(
                'change',
                function () {

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
                parseDate(
                    $('#license_issue_date').val()
                );


            const expiryDate =
                parseDate(
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
        | NOMINEE TOTAL PERCENTAGE
        |--------------------------------------------------------------------------
        */

        function validateNomineePercentages()
        {
            let total = 0;


            $('.nominee-percentage')
                .each(
                    function () {

                        const value =
                            parseFloat(
                                $(this).val()
                            );


                        if (
                            !isNaN(value)
                        ) {

                            total += value;
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
        | PAN VALIDATION
        |--------------------------------------------------------------------------
        */

        function validatePan()
        {
            const pan =
                $('#pan_number')
                    .val()
                    .trim()
                    .toUpperCase();


            if (
                !pan
            ) {
                return true;
            }


            if (
                !PAN_REGEX.test(pan)
            ) {

                alert(
                    'Please enter a valid PAN number.'
                );

                $('#pan_number')
                    .focus();

                return false;
            }


            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | FORM SUBMIT
        |--------------------------------------------------------------------------
        */

        $('form')
            .on(
                'submit',
                function (event) {

                    const form =
                        this;


                    /*
                    |--------------------------------------------------------------------------
                    | Employment Date Validation
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !validateEmploymentDates()
                    ) {

                        event.preventDefault();

                        return false;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | License Date Validation
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
                    | Nominee Percentage Validation
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
                    | PAN Validation
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !validatePan()
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
                        function () {

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
                            function () {

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
                        function () {

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
                    | QUALIFICATION FIELDS
                    |--------------------------------------------------------------------------
                    */

                    $(
                        '#qualification-wrapper input[type="text"]'
                    ).each(
                        function () {

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
                    | NOMINEE FIELDS
                    |--------------------------------------------------------------------------
                    */

                    $(
                        '.nominee-row input[type="text"], .nominee-row textarea'
                    ).each(
                        function () {

                            if (
                                $(this).hasClass(
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
                            function () {

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

                    $('#bank_account_holder_name, #bank_name, #bank_branch_name')
                        .each(
                            function () {

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
                                '<i class="fa fa-spinner fa-spin"></i> Saving Driver...'
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
                function () {

                    /*
                    |--------------------------------------------------------------------------
                    | DEFAULT COUNTRY
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $('#country').length &&
                        $('#country').val() === ''
                    ) {

                        $('#country')
                            .val(
                                'India'
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DATE MAXIMUMS
                    |--------------------------------------------------------------------------
                    */

                    const todayString =
                        new Date()
                            .toISOString()
                            .split('T')[0];


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


                    $('.nominee-row input[type="date"]')
                        .attr(
                            'max',
                            todayString
                        );
                }
            );

    })(jQuery);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const driverCodeInput = document.getElementById('driver_code');

    if (!driverCodeInput) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE DRIVER CODE
    |--------------------------------------------------------------------------
    */

    function generateDriverCode() {

        const prefix = 'DRV';

        /*
        | Random 6 digit number
        */
        const randomNumber =
            Math.floor(100000 + Math.random() * 900000);

        const driverCode =
            prefix + randomNumber;

        driverCodeInput.value = driverCode;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE ONLY IF EMPTY
    |--------------------------------------------------------------------------
    */

    if (!driverCodeInput.value.trim()) {
        generateDriverCode();
    }

});
</script>

@endpush