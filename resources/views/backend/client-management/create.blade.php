@extends('backend.layouts.master')

@section('title')
    Create Client
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">
                        <h4>Create New Client</h4>
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
                                Create Client
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('client-management.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

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
                                value="{{ old('client_code') }}"
                                placeholder="Enter Client Code (e.g. CLI001)">

                            <small class="text-muted">
                                Unique Client Code (Example: CLI001, MMT001, CORP001)
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
                                value="{{ old('company_name') }}"
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
                                value="{{ old('contact_person') }}"
                                placeholder="Enter Contact Person">

                            @error('contact_person')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- GST Number --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>GST Number</b>
                            </label>

                            <input
                                type="text"
                                name="gst_number"
                                class="form-control @error('gst_number') is-invalid @enderror"
                                value="{{ old('gst_number') }}"
                                placeholder="Enter GST Number">

                            @error('gst_number')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

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
                                value="{{ old('mobile') }}"
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
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Email Address --}}
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
                                value="{{ old('email') }}"
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

                                <b>
                                    Website
                                </b>

                            </label>

                            <input
                                type="url"
                                name="website"
                                id="website"
                                class="form-control @error('website') is-invalid @enderror"
                                value="{{ old('website') }}"
                                placeholder="https://example.com">

                            <small class="text-muted">
                                Optional (Company Website)
                            </small>

                            @error('website')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Company Logo --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Company Logo
                                </b>

                            </label>

                            <input
                                type="file"
                                name="company_logo"
                                id="company_logo"
                                class="form-control @error('company_logo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                                onchange="previewImage('company_logo','company-logo-preview')">

                            <small class="text-muted">
                                Allowed: JPG, JPEG, PNG & WEBP (Maximum 2 MB)
                            </small>

                            @error('company_logo')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            {{-- Logo Preview --}}
                            <div id="company-logo-preview" class="mt-3"></div>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- BUSINESS ADDRESS INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Business Address Information</b>
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
                                value="{{ old('country','India') }}"
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
                                value="{{ old('state') }}"
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
                                value="{{ old('city') }}"
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
                                value="{{ old('pincode') }}"
                                placeholder="Enter Pincode">

                            @error('pincode')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Business Address --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>
                                <b>
                                    Business Address
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter Complete Business Address">{{ old('address') }}</textarea>

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
                            <b>Billing Address Information</b>
                        </h5>

                        <hr>

                    </div>

                    <div class="col-12 mb-3">

                        <div class="custom-control custom-checkbox">

                            <input
                                type="checkbox"
                                class="custom-control-input"
                                id="same_as_business">

                            <label
                                class="custom-control-label"
                                for="same_as_business">

                                Same as Business Address

                            </label>

                        </div>

                    </div>

                    {{-- Billing Country --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label><b>Billing Country</b></label>

                            <input
                                type="text"
                                name="billing_country"
                                id="billing_country"
                                class="form-control"
                                value="{{ old('billing_country') }}"
                                placeholder="Billing Country">

                        </div>

                    </div>

                    {{-- Billing State --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label><b>Billing State</b></label>

                            <input
                                type="text"
                                name="billing_state"
                                id="billing_state"
                                class="form-control"
                                value="{{ old('billing_state') }}"
                                placeholder="Billing State">

                        </div>

                    </div>

                    {{-- Billing City --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label><b>Billing City</b></label>

                            <input
                                type="text"
                                name="billing_city"
                                id="billing_city"
                                class="form-control"
                                value="{{ old('billing_city') }}"
                                placeholder="Billing City">

                        </div>

                    </div>

                    {{-- Billing Pincode --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label><b>Billing Pincode</b></label>

                            <input
                                type="text"
                                name="billing_pincode"
                                id="billing_pincode"
                                maxlength="6"
                                class="form-control"
                                value="{{ old('billing_pincode') }}"
                                placeholder="Billing Pincode">

                        </div>

                    </div>

                    {{-- Billing Address --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label><b>Billing Address</b></label>

                            <textarea
                                name="billing_address"
                                id="billing_address"
                                rows="4"
                                class="form-control"
                                placeholder="Enter Billing Address">{{ old('billing_address') }}</textarea>

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

                                <option value="1" {{ old('status',1)==1 ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0" {{ old('status')==='0' ? 'selected' : '' }}>
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

                                Save Client

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
| Company Logo Preview
|--------------------------------------------------------------------------
*/
function previewImage(inputId, previewId)
{
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    preview.innerHTML = '';

    if (!input.files || !input.files[0]) {
        return;
    }

    const file = input.files[0];

    // Allow only images
    if (!file.type.startsWith('image/')) {

        alert('Please select a valid image.');

        input.value = '';

        return;
    }

    const reader = new FileReader();

    reader.onload = function (e) {

        preview.innerHTML = `
            <img
                src="${e.target.result}"
                alt="Company Logo Preview"
                class="img-thumbnail"
                style="
                    width:130px;
                    height:130px;
                    object-fit:cover;
                    border-radius:10px;
                    border:2px solid #dee2e6;
                    box-shadow:0 2px 10px rgba(0,0,0,.15);
                ">
        `;
    };

    reader.readAsDataURL(file);
}


/*
|--------------------------------------------------------------------------
| Mobile Number Validation
|--------------------------------------------------------------------------
*/
$('#mobile, #alternate_mobile').on('input', function () {

    this.value = this.value
        .replace(/\D/g, '')
        .slice(0, 10);

});


/*
|--------------------------------------------------------------------------
| Pincode Validation
|--------------------------------------------------------------------------
*/
$('#pincode, #billing_pincode').on('input', function () {

    this.value = this.value
        .replace(/\D/g, '')
        .slice(0, 6);

});


/*
|--------------------------------------------------------------------------
| GST Number Formatting
|--------------------------------------------------------------------------
*/
$('#gst_number').on('input', function () {

    this.value = this.value
        .toUpperCase()
        .replace(/\s/g, '');

});


/*
|--------------------------------------------------------------------------
| PAN Number Formatting
|--------------------------------------------------------------------------
*/
$('#pan_number').on('input', function () {

    this.value = this.value
        .toUpperCase()
        .replace(/\s/g, '');

});


/*
|--------------------------------------------------------------------------
| Company Name Formatting
|--------------------------------------------------------------------------
*/
$('#company_name').on('blur', function () {

    let value = $(this).val();

    value = value
        .replace(/\s+/g, ' ')
        .trim();

    $(this).val(value);

});


/*
|--------------------------------------------------------------------------
| Contact Person Formatting
|--------------------------------------------------------------------------
*/
$('#contact_person').on('blur', function () {

    let value = $(this).val();

    value = value
        .replace(/\s+/g, ' ')
        .trim();

    $(this).val(value);

});


/*
|--------------------------------------------------------------------------
| Website Formatting
|--------------------------------------------------------------------------
*/
$('#website').on('blur', function () {

    let value = $(this).val().trim();

    if (
        value !== '' &&
        !value.startsWith('http://') &&
        !value.startsWith('https://')
    ) {
        $(this).val('https://' + value);
    }

});


/*
|--------------------------------------------------------------------------
| Same As Business Address
|--------------------------------------------------------------------------
|
| Checkbox checked:
| Business Address → Billing Address
|
| Fields remain editable.
|--------------------------------------------------------------------------
*/
$('#same_as_business').on('change', function () {

    if ($(this).is(':checked')) {

        $('#billing_country').val(
            $('input[name="country"]').val()
        );

        $('#billing_state').val(
            $('input[name="state"]').val()
        );

        $('#billing_city').val(
            $('input[name="city"]').val()
        );

        $('#billing_pincode').val(
            $('input[name="pincode"]').val()
        );

        $('#billing_address').val(
            $('textarea[name="address"]').val()
        );

    }

});


/*
|--------------------------------------------------------------------------
| Auto Sync Business Address While Checkbox Is Checked
|--------------------------------------------------------------------------
|
| Agar checkbox checked hai aur user Business Address change karta hai,
| to Billing Address bhi automatically update hoga.
|--------------------------------------------------------------------------
*/
$('input[name="country"], \
  input[name="state"], \
  input[name="city"], \
  input[name="pincode"], \
  textarea[name="address"]')
.on('input', function () {

    if (!$('#same_as_business').is(':checked')) {
        return;
    }

    $('#billing_country').val(
        $('input[name="country"]').val()
    );

    $('#billing_state').val(
        $('input[name="state"]').val()
    );

    $('#billing_city').val(
        $('input[name="city"]').val()
    );

    $('#billing_pincode').val(
        $('input[name="pincode"]').val()
    );

    $('#billing_address').val(
        $('textarea[name="address"]').val()
    );

});


/*
|--------------------------------------------------------------------------
| Uncheck Behaviour
|--------------------------------------------------------------------------
|
| Uncheck karne par Billing Address ko clear karenge.
| User phir manually billing address enter kar sakta hai.
|--------------------------------------------------------------------------
*/
$('#same_as_business').on('change', function () {

    if (!$(this).is(':checked')) {

        $('#billing_country').val('');
        $('#billing_state').val('');
        $('#billing_city').val('');
        $('#billing_pincode').val('');
        $('#billing_address').val('');

    }

});

</script>

@endpush