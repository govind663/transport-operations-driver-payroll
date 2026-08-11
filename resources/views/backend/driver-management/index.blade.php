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

    .driver-photo{

        width:45px;
        height:45px;

        object-fit:cover;

        border-radius:50%;

        border:2px solid #dee2e6;

    }


    /*
    |--------------------------------------------------------------------------
    | Table Alignment
    |--------------------------------------------------------------------------
    */

    .table td,
    .table th{

        vertical-align:middle;

    }


    /*
    |--------------------------------------------------------------------------
    | Driver Code
    |--------------------------------------------------------------------------
    */

    .driver-code{

        font-weight:600;

        color:#023a85;

    }

</style>

@endpush


@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                {{-- Page Title --}}
                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">

                        Driver Management

                    </h4>

                    <p class="mb-0">

                        Manage all drivers, personal information and licence details.

                    </p>

                </div>


                {{-- Add Driver --}}
                <div class="col-md-6 col-sm-12 text-right">

                    <a
                        href="{{ route('driver-management.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Driver

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- DRIVER LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- Card Header --}}
            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Drivers

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $drivers->count() }}

                    </span>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Driver Management">


                    {{-- ================================================= --}}
                    {{-- TABLE HEADER --}}
                    {{-- ================================================= --}}

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

                            <th>
                                Created At
                            </th>

                            <th class="no-export">
                                Edit
                            </th>

                            <th class="no-export">
                                Delete
                            </th>

                        </tr>

                    </thead>



                    {{-- ================================================= --}}
                    {{-- TABLE BODY --}}
                    {{-- ================================================= --}}
                    <tbody>

                        @forelse($drivers as $key => $driver)
                            <tr>

                                {{-- ========================================= --}}
                                {{-- Sr No --}}
                                {{-- ========================================= --}}
                                <td>

                                    {{ $key + 1 }}

                                </td>

                                {{-- ========================================= --}}
                                {{-- Driver Photo --}}
                                {{-- ========================================= --}}
                                <td>

                                    @php

                                        $driverPhoto = $driver->driver_photo;

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


                                    <img
                                        src="{{ $driverPhotoUrl }}"
                                        alt="{{ $driver->driver_name }}"
                                        class="img-fluid driver-photo"
                                        loading="lazy"
                                        decoding="async"
                                        data-no-optimize="1"
                                        onerror="
                                            this.onerror=null;
                                            this.src='{{ asset('backend/assets/img/logo/user.png') }}';
                                        ">

                                </td>

                                {{-- ========================================= --}}
                                {{-- Driver Code --}}
                                {{-- ========================================= --}}
                                <td>

                                    <strong class="driver-code">

                                        {{ $driver->driver_code }}

                                    </strong>

                                </td>

                                {{-- ========================================= --}}
                                {{-- Driver Name --}}
                                {{-- ========================================= --}}
                                <td>

                                    <div>
                                        <strong class="text-dark">

                                            {{ trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) }}

                                        </strong>
                                    </div>

                                    @if(!empty($driver->father_name))

                                        <small class="text-muted d-block mt-1">

                                            <i class="fa fa-user"></i>

                                            Father:
                                            {{ $driver->father_name }}

                                        </small>

                                    @endif

                                </td>

                                {{-- ========================================= --}}
                                {{-- Mobile --}}
                                {{-- ========================================= --}}
                                <td>

                                    {{ $driver->mobile ?? '-' }}


                                    @if(
                                        !empty($driver->alternate_mobile)
                                    )

                                        <br>

                                        <small class="text-muted">

                                            {{ $driver->alternate_mobile }}

                                        </small>

                                    @endif

                                </td>

                                {{-- ========================================= --}}
                                {{-- Email --}}
                                {{-- ========================================= --}}
                                <td>

                                    {{ $driver->email ?? '-' }}

                                </td>

                                {{-- ========================================= --}}
                                {{-- City --}}
                                {{-- ========================================= --}}
                                <td>

                                    {{ $driver->city ?? '-' }}

                                </td>

                                {{-- ========================================= --}}
                                {{-- Driving Licence --}}
                                {{-- ========================================= --}}
                                <td>

                                    {{ $driver->license_number ?? '-' }}

                                </td>

                                {{-- ========================================= --}}
                                {{-- Licence Issue Date --}}
                                {{-- ========================================= --}}
                                <td>

                                    @if(
                                        !empty($driver->license_issue_date)
                                    )

                                        {{ \Carbon\Carbon::parse(
                                            $driver->license_issue_date
                                        )->format('d-m-Y') }}                                

                                    @else

                                        -

                                    @endif

                                </td>

                                {{-- ========================================= --}}
                                {{-- Licence Expiry Date --}}
                                {{-- ========================================= --}}
                                <td>

                                    @if(
                                        !empty($driver->license_expiry_date)
                                    )

                                        {{ \Carbon\Carbon::parse(
                                            $driver->license_expiry_date
                                        )->format('d-m-Y') }}

                                    @else

                                        -

                                    @endif

                                </td>

                                {{-- ========================================= --}}
                                {{-- Licence Type --}}
                                {{-- ========================================= --}}
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

                                {{-- ========================================= --}}
                                {{-- Employment Status --}}
                                {{-- ========================================= --}}
                                <td>

                                    {{-- ========================================= --}}
                                    {{-- Terminated --}}
                                    {{-- ========================================= --}}
                                    @if($driver->termination_date)

                                        <span
                                            class="badge badge-danger badge-pill px-3 py-2"
                                            title="Terminated on {{ \Carbon\Carbon::parse($driver->termination_date)->format('d M Y') }}"
                                        >

                                            <i class="fa fa-ban"></i>

                                            Terminated

                                        </span>


                                    {{-- ========================================= --}}
                                    {{-- Resigned --}}
                                    {{-- ========================================= --}}
                                    @elseif($driver->resignation_date)

                                        <span
                                            class="badge badge-warning badge-pill px-3 py-2"
                                            title="Resigned on {{ \Carbon\Carbon::parse($driver->resignation_date)->format('d M Y') }}"
                                        >

                                            <i class="fa fa-sign-out"></i>

                                            Resigned

                                        </span>


                                    {{-- ========================================= --}}
                                    {{-- Active --}}
                                    {{-- ========================================= --}}
                                    @elseif($driver->status)

                                        <span
                                            class="badge badge-success badge-pill px-3 py-2"
                                            title="Currently Active"
                                        >

                                            <i class="fa fa-check-circle"></i>

                                            Active

                                        </span>


                                    {{-- ========================================= --}}
                                    {{-- Inactive --}}
                                    {{-- ========================================= --}}
                                    @else

                                        <span
                                            class="badge badge-secondary badge-pill px-3 py-2"
                                            title="Currently Inactive"
                                        >

                                            <i class="fa fa-pause-circle"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>

                                {{-- ========================================= --}}
                                {{-- Created Date --}}
                                {{-- ========================================= --}}
                                <td>

                                    {{ optional(
                                        $driver->created_at
                                    )->format('d-m-Y') }}

                                </td>

                                {{-- ========================================= --}}
                                {{-- Edit --}}
                                {{-- ========================================= --}}
                                <td class="no-export">

                                    <a
                                        href="{{ route(
                                            'driver-management.edit',
                                            $driver->id
                                        ) }}"
                                        class="btn btn-warning btn-sm">

                                        <i class="dw dw-pencil-1"></i>

                                        Edit

                                    </a>

                                </td>

                                {{-- ========================================= --}}
                                {{-- Delete --}}
                                {{-- ========================================= --}}
                                <td class="no-export">

                                    <form
                                        action="{{ route(
                                            'driver-management.destroy',
                                            $driver->id
                                        ) }}"
                                        method="POST"
                                        class="delete-form">

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">

                                            <i class="dw dw-trash"></i>

                                            Delete

                                        </button>

                                    </form>

                                </td>
                            </tr>
                        @empty
                            {{-- ================================================= --}}
                            {{-- NO DATA --}}
                            {{-- ================================================= --}}
                            <tr>
                                <td
                                    colspan="13"
                                    class="text-center">

                                    No Drivers Found

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Footer --}}
    <x-backend.footer />


</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Driver Delete Confirmation
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

});
</script>

<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush