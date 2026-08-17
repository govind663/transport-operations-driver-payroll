@extends('backend.layouts.master')

@section('title')
    Edit Client
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">
                        <h4>Edit Client</h4>
                    </div>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('client-management.index') }}">
                                    Client Management
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Edit Client
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('client-management.update', $client->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            @method('PUT')

            <input type="hidden" name="id" value="{{ $client->id }}">

            <div class="card-box pd-20 mb-30">

                {{-- ================= BASIC INFORMATION ================= --}}
                <div class="mb-4">

                    <h5 class="text-primary" style="color:#023a85 !important;">
                        <b>Basic Information</b>
                    </h5>

                    <hr>

                </div>

                <div class="row">

                    {{-- Client Code --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Client Code
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="client_code"
                                id="client_code"
                                class="form-control @error('client_code') is-invalid @enderror"
                                value="{{ old('client_code', $client->client_code) }}"
                                placeholder="Enter Client Code">

                            <small class="text-muted">
                                Example : CLI001 / MMT001 / CORP001
                            </small>

                            @error('client_code')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Company Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Company Name
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="company_name"
                                id="company_name"
                                class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name', $client->company_name) }}"
                                placeholder="Enter Company Name">

                            @error('company_name')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Contact Person --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Contact Person
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="contact_person"
                                id="contact_person"
                                class="form-control @error('contact_person') is-invalid @enderror"
                                value="{{ old('contact_person', $client->contact_person) }}"
                                placeholder="Enter Contact Person">

                            @error('contact_person')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Client Category --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Client Category
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <select
                                name="category"
                                id="category"
                                class="form-control custom-select2 @error('category') is-invalid @enderror">

                                <option value="">Select Client Category</option>

                                <option value="RIL"
                                    {{ old('category', $client->category) === 'RIL' ? 'selected' : '' }}>
                                    RIL
                                </option>

                                <option value="OTHER"
                                    {{ old('category', $client->category) === 'OTHER' ? 'selected' : '' }}>
                                    OTHER
                                </option>

                            </select>

                            @error('category')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- GST Number --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>GST Number</b>
                            </label>

                            <input
                                type="text"
                                name="gst_number"
                                id="gst_number"
                                class="form-control @error('gst_number') is-invalid @enderror"
                                value="{{ old('gst_number', $client->gst_number) }}"
                                placeholder="Enter GST Number">

                            @error('gst_number')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- PAN Number --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>PAN Number</b>
                            </label>

                            <input
                                type="text"
                                name="pan_number"
                                id="pan_number"
                                class="form-control @error('pan_number') is-invalid @enderror"
                                value="{{ old('pan_number', $client->pan_number) }}"
                                placeholder="Enter PAN Number">

                            @error('pan_number')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- CONTACT INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Contact Information</b>
                        </h5>

                        <hr>

                    </div>

                    {{-- Mobile Number --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Mobile Number
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="mobile"
                                id="mobile"
                                maxlength="10"
                                class="form-control @error('mobile') is-invalid @enderror"
                                value="{{ old('mobile', $client->mobile) }}"
                                placeholder="Enter Mobile Number">

                            @error('mobile')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Alternate Mobile --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Alternate Mobile</b>
                            </label>

                            <input
                                type="text"
                                name="alternate_mobile"
                                id="alternate_mobile"
                                maxlength="10"
                                class="form-control @error('alternate_mobile') is-invalid @enderror"
                                value="{{ old('alternate_mobile', $client->alternate_mobile) }}"
                                placeholder="Enter Alternate Mobile">

                            @error('alternate_mobile')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
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
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $client->email) }}"
                                placeholder="Enter Email Address">

                            @error('email')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Website --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Website</b>
                            </label>

                            <input
                                type="url"
                                name="website"
                                id="website"
                                class="form-control @error('website') is-invalid @enderror"
                                value="{{ old('website', $client->website) }}"
                                placeholder="https://example.com">

                            @error('website')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- COMPANY LOGO --}}
                    {{-- ========================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Company Logo</b>
                            </label>

                            <input
                                type="file"
                                name="company_logo"
                                id="company_logo"
                                class="form-control @error('company_logo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                                onchange="previewImage('company_logo','company-logo-preview')">

                            <small class="text-muted">
                                Leave blank to keep the existing logo.
                                Allowed: JPG, JPEG, PNG & WEBP (Maximum 2 MB)
                            </small>

                            @error('company_logo')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror


                            {{-- ===================================================== --}}
                            {{-- LOGO PREVIEW --}}
                            {{-- ===================================================== --}}
                            <div
                                id="company-logo-preview"
                                class="mt-3">

                                @if($client->company_logo)

                                    @php

                                        $companyLogo = trim($client->company_logo);

                                        /*
                                        |--------------------------------------------------------------------------
                                        | Generate Company Logo URL
                                        |--------------------------------------------------------------------------
                                        */

                                        if (str_starts_with($companyLogo, 'client/')) {

                                            // New FileUploadService path
                                            $logoUrl = asset(
                                                'storage/' . ltrim($companyLogo, '/')
                                            );

                                        } else {

                                            // Old / Legacy logo path
                                            $logoUrl = asset(
                                                'backend/assets/uploads/client/' . ltrim($companyLogo, '/')
                                            );

                                        }

                                    @endphp


                                    {{-- ================================================= --}}
                                    {{-- CURRENT LOGO --}}
                                    {{-- ================================================= --}}
                                    <div class="position-relative d-inline-block">

                                        <img
                                            src="{{ $logoUrl }}"
                                            alt="{{ $client->company_name }}"
                                            id="current-company-logo"
                                            class="img-thumbnail"
                                            loading="lazy"
                                            decoding="async"
                                            data-no-optimize="1"
                                            style="
                                                width:120px;
                                                height:120px;
                                                object-fit:cover;
                                                border-radius:10px;
                                                border:2px solid #dee2e6;
                                                box-shadow:0 2px 8px rgba(0,0,0,.15);
                                            "
                                            onerror="
                                                this.onerror=null;
                                                this.src='{{ asset('backend/assets/img/logo/company.png') }}';
                                            ">

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- CURRENT LOGO INFO --}}
                                    {{-- ================================================= --}}
                                    <div class="mt-2">

                                        <small class="text-muted">

                                            <i class="fa fa-info-circle"></i>

                                            Current Company Logo

                                        </small>

                                    </div>


                                @else

                                    {{-- ================================================= --}}
                                    {{-- DEFAULT LOGO --}}
                                    {{-- ================================================= --}}
                                    <div>

                                        <img
                                            src="{{ asset('backend/assets/img/logo/company.png') }}"
                                            alt="Default Company Logo"
                                            id="current-company-logo"
                                            class="img-thumbnail"
                                            loading="lazy"
                                            decoding="async"
                                            data-no-optimize="1"
                                            style="
                                                width:120px;
                                                height:120px;
                                                object-fit:cover;
                                                border-radius:10px;
                                                border:2px solid #dee2e6;
                                                box-shadow:0 2px 8px rgba(0,0,0,.15);
                                            ">

                                        <div class="mt-2">

                                            <small class="text-muted">

                                                <i class="fa fa-info-circle"></i>

                                                No company logo uploaded.

                                            </small>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- ADDRESS INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Address Information</b>
                        </h5>

                        <hr>

                    </div>

                    {{-- Country --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>
                                    Country
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="country"
                                class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country', $client->country) }}"
                                placeholder="Enter Country">

                            @error('country')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
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
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="state"
                                class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state', $client->state) }}"
                                placeholder="Enter State">

                            @error('state')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
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
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="city"
                                class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city', $client->city) }}"
                                placeholder="Enter City">

                            @error('city')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
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
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="text"
                                name="pincode"
                                maxlength="6"
                                class="form-control @error('pincode') is-invalid @enderror"
                                value="{{ old('pincode', $client->pincode) }}"
                                placeholder="Enter Pincode">

                            @error('pincode')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Address --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>
                                <b>
                                    Address
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter Complete Address">{{ old('address', $client->address) }}</textarea>

                            @error('address')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- BILLING ADDRESS INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Billing Address</b>
                        </h5>

                        <hr>

                    </div>

                    {{-- Same as Business Address --}}
                    <div class="col-12 mb-3">

                        <div class="custom-control custom-checkbox">

                            <input
                                type="checkbox"
                                class="custom-control-input"
                                name="same_as_business"
                                id="same_as_business"
                                value="1"
                                {{ old(
                                    'same_as_business',
                                    (
                                        $client->billing_address === $client->address &&
                                        $client->billing_city === $client->city &&
                                        $client->billing_state === $client->state &&
                                        $client->billing_country === $client->country &&
                                        $client->billing_pincode === $client->pincode
                                    )
                                ) ? 'checked' : '' }}>

                            <label
                                class="custom-control-label"
                                for="same_as_business">

                                <b>Same as Business Address</b>

                            </label>

                        </div>

                    </div>

                    {{-- Billing Country --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Billing Country</b>
                            </label>

                            <input
                                type="text"
                                name="billing_country"
                                class="form-control @error('billing_country') is-invalid @enderror"
                                value="{{ old('billing_country', $client->billing_country) }}"
                                placeholder="Enter Billing Country">

                            @error('billing_country')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Billing State --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Billing State</b>
                            </label>

                            <input
                                type="text"
                                name="billing_state"
                                class="form-control @error('billing_state') is-invalid @enderror"
                                value="{{ old('billing_state', $client->billing_state) }}"
                                placeholder="Enter Billing State">

                            @error('billing_state')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Billing City --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Billing City</b>
                            </label>

                            <input
                                type="text"
                                name="billing_city"
                                class="form-control @error('billing_city') is-invalid @enderror"
                                value="{{ old('billing_city', $client->billing_city) }}"
                                placeholder="Enter Billing City">

                            @error('billing_city')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Billing Pincode --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Billing Pincode</b>
                            </label>

                            <input
                                type="text"
                                name="billing_pincode"
                                maxlength="6"
                                class="form-control @error('billing_pincode') is-invalid @enderror"
                                value="{{ old('billing_pincode', $client->billing_pincode) }}"
                                placeholder="Enter Billing Pincode">

                            @error('billing_pincode')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Billing Address --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>
                                <b>Billing Address</b>
                            </label>

                            <textarea
                                name="billing_address"
                                rows="4"
                                class="form-control @error('billing_address') is-invalid @enderror"
                                placeholder="Enter Billing Address">{{ old('billing_address', $client->billing_address) }}</textarea>

                            @error('billing_address')
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

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Status</b>
                        </h5>

                        <hr>

                    </div>

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

                                <option value="1"
                                    {{ old('status', $client->status) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status', $client->status) == 0 ? 'selected' : '' }}>
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
                                href="{{ route('client-management.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Client

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
| Company Logo Preview - EDIT
|--------------------------------------------------------------------------
*/
function previewImage(inputId, previewId)
{
    const fileInput = document.getElementById(inputId);
    const preview   = document.getElementById(previewId);

    if (!fileInput || !preview) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | No New File Selected
    |--------------------------------------------------------------------------
    */
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

        alert('Please select a valid JPG, JPEG, PNG or WEBP image.');

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

        alert('Company logo size must not exceed 2 MB.');

        fileInput.value = '';

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Preview New Logo
    |--------------------------------------------------------------------------
    */
    const reader = new FileReader();

    reader.onload = function(e)
    {
        preview.innerHTML = `
            <div>

                <img
                    src="${e.target.result}"
                    alt="New Company Logo"
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

                        New Logo Selected

                    </small>

                </div>

            </div>
        `;
    };

    reader.onerror = function()
    {
        alert('Unable to preview the selected image.');

        fileInput.value = '';
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
| Business & Billing Pincode Validation
|--------------------------------------------------------------------------
*/
$('#pincode, #billing_pincode').on('input', function(){

    this.value = this.value
        .replace(/[^0-9]/g, '')
        .slice(0, 6);

});


/*
|--------------------------------------------------------------------------
| GST Number Formatting
|--------------------------------------------------------------------------
*/
$('#gst_number').on('input', function(){

    this.value = this.value
        .toUpperCase()
        .replace(/\s/g, '');

});


/*
|--------------------------------------------------------------------------
| PAN Number Formatting
|--------------------------------------------------------------------------
*/
$('#pan_number').on('input', function(){

    this.value = this.value
        .toUpperCase()
        .replace(/\s/g, '');

});


/*
|--------------------------------------------------------------------------
| Client Code Formatting
|--------------------------------------------------------------------------
*/
$('#client_code').on('blur', function(){

    this.value = this.value
        .trim()
        .toUpperCase()
        .replace(/\s+/g, '');

});


/*
|--------------------------------------------------------------------------
| Company Name Formatting
|--------------------------------------------------------------------------
*/
$('#company_name').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Contact Person Formatting
|--------------------------------------------------------------------------
*/
$('#contact_person').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Business Address Formatting
|--------------------------------------------------------------------------
*/
$('#address').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Billing Address Formatting
|--------------------------------------------------------------------------
*/
$('#billing_address').on('blur', function(){

    this.value = this.value
        .replace(/\s+/g, ' ')
        .trim();

});


/*
|--------------------------------------------------------------------------
| Country / State / City Formatting
|--------------------------------------------------------------------------
*/
$(
    '#country, #state, #city, ' +
    '#billing_country, #billing_state, #billing_city'
).on('blur', function(){

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
| Website Formatting
|--------------------------------------------------------------------------
*/
$('#website').on('blur', function(){

    let url = $(this).val().trim();

    if (url === '') {
        return;
    }

    if (!/^https?:\/\//i.test(url)) {

        url = 'https://' + url;

    }

    $(this).val(url);

});


/*
|--------------------------------------------------------------------------
| Copy Business Address → Billing Address
|--------------------------------------------------------------------------
*/
function copyBusinessAddress()
{
    $('#billing_country').val($('#country').val());
    $('#billing_state').val($('#state').val());
    $('#billing_city').val($('#city').val());
    $('#billing_pincode').val($('#pincode').val());
    $('#billing_address').val($('#address').val());
}


/*
|--------------------------------------------------------------------------
| Same as Business Address
|--------------------------------------------------------------------------
*/
$('#same_as_business').on('change', function(){

    if ($(this).is(':checked')) {

        /*
        |--------------------------------------------------------------------------
        | Copy Current Business Address
        |--------------------------------------------------------------------------
        */
        copyBusinessAddress();

    }

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | Billing fields are NOT readonly.
    | User can still manually edit them.
    |--------------------------------------------------------------------------
    */

});


/*
|--------------------------------------------------------------------------
| Keep Billing Address Synced
|--------------------------------------------------------------------------
|
| Only works while checkbox is checked.
|
*/
$(
    '#country, #state, #city, #pincode, #address'
).on('input', function(){

    if ($('#same_as_business').is(':checked')) {

        copyBusinessAddress();

    }

});


/*
|--------------------------------------------------------------------------
| Initialize Same as Business Address
|--------------------------------------------------------------------------
|
| If checkbox is already checked after validation error/page reload,
| copy business address automatically.
|
*/
$(document).ready(function(){

    if ($('#same_as_business').is(':checked')) {

        copyBusinessAddress();

    }

});


/*
|--------------------------------------------------------------------------
| Prevent Double Form Submission
|--------------------------------------------------------------------------
*/
$('form').on('submit', function(){

    const form = this;

    const submitButton = $(form)
        .find('button[type="submit"]');

    if (submitButton.length) {

        submitButton
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Updating Client...'
            );

    }

});


</script>

@endpush