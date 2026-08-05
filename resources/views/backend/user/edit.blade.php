@extends('backend.layouts.master')

@section('title')
    Edit User
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">
                        <h4>Edit User</h4>
                    </div>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('users.index') }}">
                                    User Management
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Edit User
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('users.update', $user->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">

                <div class="mb-4">

                    <h5 class="text-primary" style="color:#023a85 !important;">
                        <b>Basic Information</b>
                    </h5>

                </div>

                <div class="row">

                    {{-- Full Name --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Full Name
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name',$user->name) }}"
                                placeholder="Enter Full Name">

                            @error('name')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">

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
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email',$user->email) }}"
                                placeholder="Enter Email Address">

                            @error('email')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Phone Number</b>
                            </label>

                            <input
                                type="text"
                                name="phone"
                                maxlength="10"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone',$user->phone) }}"
                                placeholder="Enter Phone Number">

                            @error('phone')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Role --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Role
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <select
                                name="role"
                                class="form-control custom-select2">

                                <option value="admin"
                                    {{ old('role',$user->role)=='admin' ? 'selected':'' }}>
                                    Admin
                                </option>

                                <option value="operations"
                                    {{ old('role',$user->role)=='operations' ? 'selected':'' }}>
                                    Operations
                                </option>

                                <option value="accountant"
                                    {{ old('role',$user->role)=='accountant' ? 'selected':'' }}>
                                    Accountant
                                </option>

                                <option value="driver"
                                    {{ old('role',$user->role)=='driver' ? 'selected':'' }}>
                                    Driver
                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- Status --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>Status</b>

                            </label>

                            <select
                                name="status"
                                class="form-control custom-select2">

                                <option value="1"
                                    {{ old('status',$user->status)==1 ? 'selected':'' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status',$user->status)==0 ? 'selected':'' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- SECURITY INFORMATION --}}
                    {{-- ========================================================= --}}
                    {{-- <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Security Information</b>
                        </h5>

                        <hr>

                        <small class="text-muted">
                            Leave the password fields blank if you don't want to change the current password.
                        </small>

                    </div> --}}

                    {{-- Password --}}
                    {{-- <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    New Password
                                </b>

                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter New Password">

                                <div class="input-group-append">

                                    <span
                                        class="input-group-text"
                                        style="cursor:pointer"
                                        onclick="togglePassword('password','passwordIcon')">

                                        <i
                                            id="passwordIcon"
                                            class="fa fa-eye">
                                        </i>

                                    </span>

                                </div>

                            </div>

                            <small class="text-muted">
                                Leave blank to keep the existing password.
                            </small>

                            @error('password')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div> --}}

                    {{-- Confirm Password --}}
                    {{-- <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Confirm New Password
                                </b>

                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirm New Password">

                                <div class="input-group-append">

                                    <span
                                        class="input-group-text"
                                        style="cursor:pointer"
                                        onclick="togglePassword('password_confirmation','confirmPasswordIcon')">

                                        <i
                                            id="confirmPasswordIcon"
                                            class="fa fa-eye">
                                        </i>

                                    </span>

                                </div>

                            </div>

                            <small
                                id="password-match"
                                class="text-muted">

                                Re-enter the same password.

                            </small>

                        </div>

                    </div> --}}

                    {{-- ========================================================= --}}
                    {{-- PROFILE INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Profile Information</b>
                        </h5>

                        <hr>

                    </div>

                    {{-- Profile Image --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>Profile Image</b>

                            </label>

                            <input
                                type="file"
                                name="profile_image"
                                id="profile_image"
                                class="form-control @error('profile_image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                                onchange="previewImage('profile_image','profile-preview')">

                            <small class="text-muted">
                                Leave blank to keep the existing profile image.
                            </small>

                            @error('profile_image')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Current Profile Preview --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>Current Profile</b>

                            </label>

                            <div id="profile-preview">

                                <img
                                    src="{{ $user->profile_image
                                        ? asset('backend/assets/uploads/profile/' . $user->profile_image)
                                        : asset('backend/assets/img/logo/mastermind-logo.webp') }}"
                                    alt="{{ $user->name }}"
                                    data-no-optimize="1"
                                    style="
                                        width:120px;
                                        height:120px;
                                        object-fit:cover;
                                        border-radius:10px;
                                        border:2px solid #dee2e6;
                                        box-shadow:0 2px 8px rgba(0,0,0,.15);
                                    "
                                    onerror="this.onerror=null;this.src='{{ asset('backend/assets/img/logo/mastermind-logo.webp') }}';">

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- ACTION BUTTONS --}}
                {{-- ========================================================= --}}
                <div class="text-right mt-4">

                    <a
                        href="{{ route('users.index') }}"
                        class="btn btn-danger">

                        <i class="fa fa-times"></i>

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="fa fa-save"></i>

                        Update User

                    </button>

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
| Image Preview
|--------------------------------------------------------------------------
*/
function previewImage(inputId, previewId)
{
    const fileInput = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if(fileInput.files && fileInput.files[0])
    {
        const reader = new FileReader();

        reader.onload = function(e)
        {
            preview.innerHTML = `
                <img
                    src="${e.target.result}"
                    alt="Profile Preview"
                    style="
                        width:120px;
                        height:120px;
                        object-fit:cover;
                        border-radius:10px;
                        border:2px solid #dee2e6;
                        box-shadow:0 2px 8px rgba(0,0,0,.15);
                    ">
            `;
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
}

/*
|--------------------------------------------------------------------------
| Show / Hide Password
|--------------------------------------------------------------------------
*/
function togglePassword(fieldId, iconId)
{
    let input = document.getElementById(fieldId);
    let icon = document.getElementById(iconId);

    if(input.type === 'password')
    {
        input.type = 'text';

        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
    else
    {
        input.type = 'password';

        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

/*
|--------------------------------------------------------------------------
| Password Match Validation
|--------------------------------------------------------------------------
*/
$('#password, #password_confirmation').on('keyup', function () {

    let password = $('#password').val();
    let confirmPassword = $('#password_confirmation').val();

    if(password === '' && confirmPassword === '')
    {
        $('#password-match')
            .html('Leave blank to keep the current password.')
            .css('color', '#6c757d');

        return;
    }

    if(confirmPassword === '')
    {
        $('#password-match')
            .html('Re-enter the same password.')
            .css('color', '#6c757d');

        return;
    }

    if(password === confirmPassword)
    {
        $('#password-match')
            .html('✔ Password Matched')
            .css('color', 'green');
    }
    else
    {
        $('#password-match')
            .html('✘ Password Not Matched')
            .css('color', 'red');
    }

});

/*
|--------------------------------------------------------------------------
| Phone Number Validation
|--------------------------------------------------------------------------
*/
$('#phone').on('input', function () {

    this.value = this.value.replace(/[^0-9]/g, '');

});

/*
|--------------------------------------------------------------------------
| Prevent Space in Phone Number
|--------------------------------------------------------------------------
*/
$('#phone').on('keypress', function(e){

    if(e.which === 32)
    {
        return false;
    }

});

</script>

@endpush