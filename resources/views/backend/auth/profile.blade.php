@extends('backend.layouts.master')

@section('title')
    Edit Client Profile Details
@endsection

@push('styles')
<style>
    .profile-photo {
        width: 464px !important;
        height: 180px !important;
        margin: 0 auto 15px;
        position: relative;
    }
</style>
@endpush

@section('content')
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Edit Client Profile Details</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Edit Client Profile Details
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                    <div class="pd-20 card-box height-100-p">
                        {{-- Profile Photo --}}
                        <div class="profile-photo text-center mb-2">
                            <img
                                src="{{ Auth::user()->profile_image
                                    ? asset('backend/assets/uploads/profile/' . Auth::user()->profile_image)
                                    : asset('backend/assets/img/logo/favicon.ico') }}"
                                alt="{{ Auth::user()->name }}"
                                class="img-fluid"
                                loading="lazy"
                                decoding="async"
                                style="
                                    width: 650px !important;
                                    height: 150px !important;
                                    object-fit: cover;
                                    display: block;
                                    margin: 0 auto;
                                "
                                data-no-optimize="1"
                                onerror="this.onerror=null; this.src='{{ asset('backend/assets/img/logo/favicon.ico') }}';"
                            >
                        </div>

                        {{-- User Name --}}
                        <h5 class="text-center h5 mb-1 text-capitalize">
                            {{ Auth::user()->name }}
                        </h5>

                        {{-- Role --}}
                        <div class="text-center mb-3">
                            @php
                                $role = strtolower(Auth::user()->role ?? 'user');

                                $roleConfig = match ($role) {
                                    'admin' => [
                                        'bg' => '#fff1f2',
                                        'color' => '#dc3545',
                                        'border' => '#fecdd3',
                                        'icon' => 'dw dw-user1',
                                        'label' => 'Administrator',
                                    ],

                                    'operations' => [
                                        'bg' => '#eff6ff',
                                        'color' => '#2563eb',
                                        'border' => '#bfdbfe',
                                        'icon' => 'dw dw-settings',
                                        'label' => 'Operations',
                                    ],

                                    'accountant' => [
                                        'bg' => '#fffbeb',
                                        'color' => '#d97706',
                                        'border' => '#fde68a',
                                        'icon' => 'dw dw-money',
                                        'label' => 'Accountant',
                                    ],

                                    'driver' => [
                                        'bg' => '#f0fdf4',
                                        'color' => '#16a34a',
                                        'border' => '#bbf7d0',
                                        'icon' => 'dw dw-car',
                                        'label' => 'Driver',
                                    ],

                                    'user' => [
                                        'bg' => '#eff6ff',
                                        'color' => '#0284c7',
                                        'border' => '#bae6fd',
                                        'icon' => 'dw dw-user1',
                                        'label' => 'User',
                                    ],

                                    default => [
                                        'bg' => '#f8fafc',
                                        'color' => '#64748b',
                                        'border' => '#e2e8f0',
                                        'icon' => 'dw dw-user1',
                                        'label' => ucwords(str_replace('_', ' ', $role)),
                                    ],
                                };
                            @endphp

                            <span
                                style="
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 7px;
                                    padding: 7px 14px;
                                    background: {{ $roleConfig['bg'] }};
                                    color: {{ $roleConfig['color'] }};
                                    border: 1px solid {{ $roleConfig['border'] }};
                                    border-radius: 50px;
                                    font-size: 12px;
                                    font-weight: 600;
                                    line-height: 1;
                                    white-space: nowrap;
                                    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                                "
                            >
                                <i
                                    class="{{ $roleConfig['icon'] }}"
                                    style="
                                        font-size: 14px;
                                        color: {{ $roleConfig['color'] }};
                                        display: inline-block;
                                        line-height: 1;
                                    "
                                ></i>

                                <span>{{ $roleConfig['label'] }}</span>
                            </span>
                        </div>

                        {{-- Contact Information --}}
                        <div class="profile-info">
                            <h5 class="mb-20 h5 text-blue">Contact Information</h5>
                            <ul>
                                <li>
                                    <span>Email Address:</span>
                                    {{ Auth::user()->email }}
                                </li>
                                <li>
                                    <span>Phone Number:</span>
                                    {{ Auth::user()->phone }}
                                </li>
                                {{-- <li>
                                    <span>Country:</span>
                                    India
                                </li>
                                <li>
                                    <span>Address:</span>
                                    1807 Holden Street<br />
                                    San Diego, CA 92115
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
                    <div class="card-box height-100-p overflow-hidden">
                        <div class="profile-tab height-100-p">
                            <div class="tab height-100-p">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#setting"  role="tab">
                                            Update Profile
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active height-100-p" id="setting" role="tabpanel">
                                        <div class="profile-setting">

                                            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <ul class="profile-edit-list row">

                                                    <!-- LEFT SIDE -->
                                                    <li class="weight-500 col-md-12">

                                                        <div class="row">
                                                            <!-- Full Name -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Full Name : <span class="text-danger">*</span></b></label>
                                                                <input class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                                    type="text"
                                                                    name="name"
                                                                    id="name"
                                                                    value="{{ Auth::user()->name }}">
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Email Id : <span class="text-danger">*</span></b></label>
                                                                <input class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                                    type="email"
                                                                    name="email"
                                                                    id="email"
                                                                    value="{{ Auth::user()->email }}">
                                                                @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <!-- Phone -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Phone : <span class="text-danger">*</span></b></label>
                                                                <input class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                                                    type="text"
                                                                    name="phone"
                                                                    id="phone"
                                                                    value="{{ Auth::user()->phone }}">
                                                                @error('phone')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <!-- Profile Image -->
                                                            <div class="form-group col-md-6">
                                                                <label><b>Profile Image : </label>
                                                                <input class="form-control form-control-lg @error('profile_image') is-invalid @enderror" type="file" name="profile_image" id="profile_image" onchange="agentPreviewFile()" accept=".png, .jpg, .jpeg, .webp" value="{{ old('profile_image') }}">
                                                                <small class="text-secondary"><b>Note : The file size  should be less than 2MB .</b></small>
                                                                <br>
                                                                <small class="text-secondary"><b>Note : Only files in .jpg, .jpeg, .png, .webp format can be uploaded .</b></small>
                                                                <br>
                                                                @error('profile_image')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                <br>
                                                                <div id="preview-container">
                                                                    <div id="file-preview"></div>
                                                                </div>
                                                            </div>  
                                                        </div>

                                                        <div class="form-group mb-0">
                                                            <input type="submit" class="btn btn-primary" value="Update Information">
                                                        </div>

                                                    </li>

                                                </ul>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Start -->
        <x-backend.footer />
        <!-- Footer Start -->
    </div>
@endsection

@push('scripts')
{{-- preview both image and PDF --}}
<script>
    function agentPreviewFile() {
        const fileInput = document.getElementById('profile_image');
        const previewContainer = document.getElementById('preview-container');
        const filePreview = document.getElementById('file-preview');
        const file = fileInput.files[0];

        if (file) {
            const fileType = file.type;
            const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            const validPdfTypes = ['application/pdf'];

            if (validImageTypes.includes(fileType)) {
                // Image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    filePreview.innerHTML = `<img src="${e.target.result}" alt="File Preview" width="50%" height="50">`;
                };
                reader.readAsDataURL(file);
            } else if (validPdfTypes.includes(fileType)) {
                // PDF preview using an embed element
                filePreview.innerHTML =
                    `<embed src="${URL.createObjectURL(file)}" type="application/pdf" width="100%" height="150px" />`;
            } else {
                // Unsupported file type
                filePreview.innerHTML = '<p>Unsupported file type</p>';
            }

            previewContainer.style.display = 'block';
        } else {
            // No file selected
            previewContainer.style.display = 'none';
        }

    }

</script>
@endpush
