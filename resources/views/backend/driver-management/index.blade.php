@extends('backend.layouts.master')

@section('title')
    Driver Management
@endsection


@push('styles')

<link rel="stylesheet"
      href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>

    /*
    |--------------------------------------------------------------------------
    | Driver Photo
    |--------------------------------------------------------------------------
    */

    .driver-photo {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #dee2e6;
    }


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    .table td,
    .table th {
        vertical-align: middle;
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Code
    |--------------------------------------------------------------------------
    */

    .driver-code {
        font-weight: 600;
        color: #023a85;
    }


    /*
    |--------------------------------------------------------------------------
    | Action Buttons
    |--------------------------------------------------------------------------
    */

    .driver-action-btn {
        min-width: 75px;
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Name
    |--------------------------------------------------------------------------
    */

    .driver-name {
        font-weight: 600;
        color: #212529;
    }


    /*
    |--------------------------------------------------------------------------
    | Mobile Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767px) {

        .page-header .text-right {
            text-align: left !important;
            margin-top: 15px;
        }

    }

</style>

@endpush


@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        @php

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $user = auth()->user();

            $userRole = $user->role ?? null;


            /*
            |--------------------------------------------------------------------------
            | Role Permissions
            |--------------------------------------------------------------------------
            */

            $isAdmin      = $userRole === 'admin';
            $isOperations = $userRole === 'operations';
            $isAccountant = $userRole === 'accountant';
            $isDriver     = $userRole === 'driver';


            /*
            |--------------------------------------------------------------------------
            | Driver Management Permissions
            |--------------------------------------------------------------------------
            |
            | Admin       -> Full access
            | Operations  -> Full access
            | Driver      -> Own profile only
            | Accountant  -> No access
            |
            */

            $canViewDrivers =
                $isAdmin ||
                $isOperations ||
                $isDriver;


            $canCreateDriver =
                $isAdmin ||
                $isOperations;


            $canEditDriver =
                $isAdmin ||
                $isOperations ||
                $isDriver;


            $canDeleteDriver =
                $isAdmin ||
                $isOperations;


        @endphp


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                {{-- ================================================= --}}
                {{-- TITLE --}}
                {{-- ================================================= --}}

                <div class="col-md-7 col-sm-12">

                    <h4 class="text-blue mb-1">

                        @if($isDriver)

                            My Driver Profile

                        @else

                            Driver Management

                        @endif

                    </h4>


                    <p class="mb-0 text-muted">

                        @if($isDriver)

                            View and update your driver information and licence details.

                        @else

                            Manage drivers, personal information, employment and licence details.

                        @endif

                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- ADD DRIVER --}}
                {{-- ADMIN / OPERATIONS ONLY --}}
                {{-- ================================================= --}}

                @if($canCreateDriver)

                    <div class="col-md-5 col-sm-12 text-right">

                        <a
                            href="{{ route('driver-management.create') }}"
                            class="btn btn-primary">

                            <i class="fa fa-plus"></i>

                            Add New Driver

                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACCESS INFORMATION --}}
        {{-- ========================================================= --}}

        @if($isDriver)

            <div class="alert alert-info d-flex align-items-center mb-30">

                <i class="fa fa-info-circle mr-2"></i>

                <span>

                    You can view and update your own driver profile.
                    Other drivers' information is not accessible.

                </span>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- DRIVER LIST CARD --}}
        {{-- ========================================================= --}}

        @if($canViewDrivers)

            <div class="card-box mb-30">


                {{-- ================================================= --}}
                {{-- CARD HEADER --}}
                {{-- ================================================= --}}

                <div class="pd-20">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <div>

                            <h4 class="text-blue h4 mb-1">

                                @if($isDriver)

                                    My Driver Profile

                                @else

                                    All Drivers

                                @endif

                            </h4>

                            <small class="text-muted">

                                @if($isDriver)

                                    Your registered driver information

                                @else

                                    Complete driver records

                                @endif

                            </small>

                        </div>


                        <span class="badge badge-primary">

                            Total :

                            {{ $drivers->count() }}

                        </span>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TABLE --}}
                {{-- ================================================= --}}

                <div class="pb-20">

                    <table
                        class="table hover multiple-select-row data-table-export1 nowrap p-3"
                        data-title="Driver Management">


                        {{-- =========================================== --}}
                        {{-- TABLE HEADER --}}
                        {{-- =========================================== --}}

                        <thead>

                            <tr>

                                <th>
                                    Sr. No.
                                </th>

                                <th>
                                    Photo
                                </th>

                                <th>
                                    Driver Code
                                </th>

                                <th>
                                    Driver Name
                                </th>

                                <th>
                                    Mobile
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    City
                                </th>

                                <th>
                                    Licence No.
                                </th>

                                <th>
                                    Licence Issue Date
                                </th>

                                <th>
                                    Licence Expiry Date
                                </th>

                                <th>
                                    Licence Type
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="no-export">
                                    View
                                </th>

                                @if($canEditDriver)

                                    <th class="no-export">
                                        Edit
                                    </th>

                                @endif

                                @if($canDeleteDriver)

                                    <th class="no-export">
                                        Delete
                                    </th>

                                @endif

                            </tr>

                        </thead>


                        {{-- =========================================== --}}
                        {{-- TABLE BODY --}}
                        {{-- =========================================== --}}

                        <tbody>

                            @forelse($drivers as $key => $driver)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Driver Ownership
                                    |--------------------------------------------------------------------------
                                    */

                                    $isOwnDriver = false;

                                    if ($isDriver) {

                                        $isOwnDriver =
                                            (int) ($driver->user_id ?? 0) ===
                                            (int) $user->id;

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Driver Row Permissions
                                    |--------------------------------------------------------------------------
                                    */

                                    $canViewThisDriver =
                                        !$isDriver ||
                                        $isOwnDriver;


                                    $canEditThisDriver =
                                        ($isAdmin || $isOperations) ||
                                        ($isDriver && $isOwnDriver);


                                    $canDeleteThisDriver =
                                        $isAdmin ||
                                        $isOperations;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Driver Photo
                                    |--------------------------------------------------------------------------
                                    */

                                    $driverPhoto =
                                        $driver->driver_photo;


                                    if ($driverPhoto) {

                                        if (
                                            str_starts_with(
                                                $driverPhoto,
                                                'driver/'
                                            )
                                        ) {

                                            $driverPhotoUrl =
                                                asset(
                                                    'storage/' . $driverPhoto
                                                );

                                        } else {

                                            $driverPhotoUrl =
                                                asset(
                                                    'backend/assets/uploads/driver/' .
                                                    $driverPhoto
                                                );

                                        }

                                    } else {

                                        $driverPhotoUrl =
                                            asset(
                                                'backend/assets/img/logo/user.png'
                                            );

                                    }

                                @endphp


                                @if($canViewThisDriver)

                                    <tr>


                                        {{-- ================================= --}}
                                        {{-- SR NO --}}
                                        {{-- ================================= --}}

                                        <td>

                                            {{ $key + 1 }}

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- PHOTO --}}
                                        {{-- ================================= --}}

                                        <td>

                                            <img
                                                src="{{ $driverPhotoUrl }}"
                                                alt="{{ $driver->driver_name ?? 'Driver' }}"
                                                class="img-fluid driver-photo"
                                                loading="lazy"
                                                decoding="async"
                                                data-no-optimize="1"
                                                onerror="
                                                    this.onerror=null;
                                                    this.src='{{ asset('backend/assets/img/logo/user.png') }}';
                                                ">

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- DRIVER CODE --}}
                                        {{-- ================================= --}}

                                        <td>

                                            <strong class="driver-code">

                                                {{ $driver->driver_code ?? '-' }}

                                            </strong>

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- DRIVER NAME --}}
                                        {{-- ================================= --}}

                                        <td>

                                            <div class="driver-name">

                                                {{ trim(
                                                    ($driver->first_name ?? '') .
                                                    ' ' .
                                                    ($driver->last_name ?? '')
                                                ) ?: '-' }}

                                            </div>


                                            @if(!empty($driver->father_name))

                                                <small class="text-muted d-block mt-1">

                                                    <i class="fa fa-user mr-1"></i>

                                                    Father:
                                                    {{ $driver->father_name }}

                                                </small>

                                            @endif

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- MOBILE --}}
                                        {{-- ================================= --}}

                                        <td>

                                            {{ $driver->mobile ?? '-' }}


                                            @if(!empty($driver->alternate_mobile))

                                                <br>

                                                <small class="text-muted">

                                                    {{ $driver->alternate_mobile }}

                                                </small>

                                            @endif

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- EMAIL --}}
                                        {{-- ================================= --}}

                                        <td>

                                            {{ $driver->email ?? '-' }}

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- CITY --}}
                                        {{-- ================================= --}}

                                        <td>

                                            {{ $driver->city ?? '-' }}

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- LICENCE NUMBER --}}
                                        {{-- ================================= --}}

                                        <td>

                                            {{ $driver->license_number ?? '-' }}

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- LICENCE ISSUE DATE --}}
                                        {{-- ================================= --}}

                                        <td>

                                            @if(!empty($driver->license_issue_date))

                                                {{ \Carbon\Carbon::parse(
                                                    $driver->license_issue_date
                                                )->format('d-m-Y') }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- LICENCE EXPIRY DATE --}}
                                        {{-- ================================= --}}

                                        <td>

                                            @if(!empty($driver->license_expiry_date))

                                                {{ \Carbon\Carbon::parse(
                                                    $driver->license_expiry_date
                                                )->format('d-m-Y') }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- LICENCE TYPE --}}
                                        {{-- ================================= --}}

                                        <td>

                                            @if(!empty($driver->license_type))

                                                <span class="badge badge-info">

                                                    <i class="fa fa-id-card-o mr-1"></i>

                                                    {{ $driver->license_type }}

                                                </span>

                                            @else

                                                <span class="badge badge-secondary">

                                                    <i class="fa fa-minus-circle mr-1"></i>

                                                    Not Available

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- STATUS --}}
                                        {{-- ================================= --}}

                                        <td>

                                            @if($driver->termination_date)

                                                <span
                                                    class="badge badge-danger badge-pill px-3 py-2"
                                                    title="Terminated on {{ \Carbon\Carbon::parse($driver->termination_date)->format('d M Y') }}">

                                                    <i class="fa fa-ban"></i>

                                                    Terminated

                                                </span>

                                            @elseif($driver->resignation_date)

                                                <span
                                                    class="badge badge-warning badge-pill px-3 py-2"
                                                    title="Resigned on {{ \Carbon\Carbon::parse($driver->resignation_date)->format('d M Y') }}">

                                                    <i class="fa fa-sign-out"></i>

                                                    Resigned

                                                </span>

                                            @elseif($driver->status)

                                                <span
                                                    class="badge badge-success badge-pill px-3 py-2"
                                                    title="Currently Active">

                                                    <i class="fa fa-check-circle"></i>

                                                    Active

                                                </span>

                                            @else

                                                <span
                                                    class="badge badge-secondary badge-pill px-3 py-2"
                                                    title="Currently Inactive">

                                                    <i class="fa fa-pause-circle"></i>

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- VIEW --}}
                                        {{-- ================================= --}}

                                        <td class="no-export">

                                            <a
                                                href="{{ route(
                                                    'driver-management.show',
                                                    $driver->id
                                                ) }}"
                                                class="btn btn-info btn-sm driver-action-btn">

                                                <i class="fa fa-eye"></i>

                                                View

                                            </a>

                                        </td>


                                        {{-- ================================= --}}
                                        {{-- EDIT --}}
                                        {{-- ================================= --}}

                                        @if($canEditDriver)

                                            <td class="no-export">

                                                @if($canEditThisDriver)

                                                    <a
                                                        href="{{ route(
                                                            'driver-management.edit',
                                                            $driver->id
                                                        ) }}"
                                                        class="btn btn-warning btn-sm driver-action-btn">

                                                        <i class="dw dw-pencil-1"></i>

                                                        Edit

                                                    </a>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>

                                        @endif


                                        {{-- ================================= --}}
                                        {{-- DELETE --}}
                                        {{-- ================================= --}}

                                        @if($canDeleteDriver)

                                            <td class="no-export">

                                                @if($canDeleteThisDriver)

                                                    <form
                                                        action="{{ route(
                                                            'driver-management.destroy',
                                                            $driver->id
                                                        ) }}"
                                                        method="POST"
                                                        class="delete-form d-inline">

                                                        @csrf

                                                        @method('DELETE')


                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger btn-sm driver-action-btn">

                                                            <i class="dw dw-trash"></i>

                                                            Delete

                                                        </button>

                                                    </form>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>

                                        @endif

                                    </tr>

                                @endif

                            @empty

                                <tr>

                                    <td
                                        colspan="15"
                                        class="text-center py-4">

                                        <div class="text-muted">

                                            <i class="fa fa-users fa-2x mb-2"></i>

                                            <br>

                                            @if($isDriver)

                                                Driver profile not found.

                                            @else

                                                No Drivers Found.

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @else

            {{-- ========================================================= --}}
            {{-- ACCESS DENIED --}}
            {{-- ========================================================= --}}

            <div class="card-box mb-30">

                <div class="pd-40 text-center">

                    <i class="fa fa-lock text-danger"
                       style="font-size:50px;"></i>

                    <h5 class="mt-3">

                        Access Restricted

                    </h5>

                    <p class="text-muted mb-0">

                        You do not have permission to access Driver Management.

                    </p>

                </div>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <x-backend.footer />


</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();


            Swal.fire({

                title: 'Are you sure?',

                text: 'This driver will be moved to trash.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Yes, Delete',

                cancelButtonText: 'Cancel',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Success Message
    |--------------------------------------------------------------------------
    */

    @if(session('message'))

        Swal.fire({

            icon: 'success',

            title: 'Success',

            text: @json(session('message')),

            timer: 2200,

            showConfirmButton: false

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | Error Message
    |--------------------------------------------------------------------------
    */

    @if(session('error'))

        Swal.fire({

            icon: 'error',

            title: 'Access Denied',

            text: @json(session('error')),

            confirmButtonText: 'OK'

        });

    @endif

});

</script>


<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush