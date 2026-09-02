@extends('backend.layouts.master')

@section('title')
    Travel Request Details
@endsection


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | Section Title
    |--------------------------------------------------------------------------
    */

    .form-section-title {
        color: #023a85 !important;
        font-weight: 600;
        margin-bottom: 10px;
    }


    /*
    |--------------------------------------------------------------------------
    | Card
    |--------------------------------------------------------------------------
    */

    .card-box {
        border-radius: 4px;
    }


    /*
    |--------------------------------------------------------------------------
    | Detail Item
    |--------------------------------------------------------------------------
    */

    .detail-item {
        margin-bottom: 18px;
    }


    /*
    |--------------------------------------------------------------------------
    | Detail Label
    |--------------------------------------------------------------------------
    */

    .detail-label {
        display: block;
        font-size: 13px;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 5px;
    }


    /*
    |--------------------------------------------------------------------------
    | Detail Value
    |--------------------------------------------------------------------------
    */

    .detail-value {
        font-size: 15px;
        color: #212529;
        font-weight: 500;
        word-break: break-word;
    }


    /*
    |--------------------------------------------------------------------------
    | Detail Value Strong
    |--------------------------------------------------------------------------
    */

    .detail-value strong {
        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | Text Block
    |--------------------------------------------------------------------------
    */

    .detail-text {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 12px 15px;
        min-height: 50px;
        white-space: pre-line;
        word-break: break-word;
    }


    /*
    |--------------------------------------------------------------------------
    | Request Number Badge
    |--------------------------------------------------------------------------
    */

    .request-number-badge {
        font-size: 14px;
        padding: 8px 14px;
    }


    /*
    |--------------------------------------------------------------------------
    | Status Badge
    |--------------------------------------------------------------------------
    */

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }


    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }


    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }


    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }


    .status-assigned {
        background-color: #cce5ff;
        color: #004085;
    }


    .status-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }


    .status-cancelled {
        background-color: #e2e3e5;
        color: #383d41;
    }


    /*
    |--------------------------------------------------------------------------
    | Audit Box
    |--------------------------------------------------------------------------
    */

    .audit-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 15px;
    }


    /*
    |--------------------------------------------------------------------------
    | Assignment Box
    |--------------------------------------------------------------------------
    */

    .assignment-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 15px;
    }


    /*
    |--------------------------------------------------------------------------
    | Divider
    |--------------------------------------------------------------------------
    */

    .section-divider {
        margin-top: 10px;
        margin-bottom: 25px;
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Value
    |--------------------------------------------------------------------------
    */

    .empty-value {
        color: #999;
        font-style: italic;
    }


    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767px) {

        .page-header .text-right {
            text-align: left !important;
            margin-top: 10px;
        }

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

                <div class="col-md-8 col-sm-12">

                    <div class="title">

                        <h4>
                            Travel Request Details
                        </h4>

                    </div>


                    {{-- Breadcrumb --}}

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>

                            </li>


                            <li class="breadcrumb-item">

                                <a href="{{ route('travel-requests.index') }}">
                                    Travel Requests
                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                View Travel Request

                            </li>

                        </ol>

                    </nav>

                </div>


                {{-- ================================================= --}}
                {{-- HEADER ACTIONS --}}
                {{-- ================================================= --}}

                <div class="col-md-4 col-sm-12 text-right">

                    <a
                        href="{{ route(
                            'travel-requests.edit',
                            $travelRequest->id
                        ) }}"
                        class="btn btn-warning">

                        <i class="fa fa-edit"></i>

                        Edit

                    </a>


                    <a
                        href="{{ route('travel-requests.index') }}"
                        class="btn btn-secondary">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- MAIN INFORMATION CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box pd-20 mb-30">


            {{-- ===================================================== --}}
            {{-- HEADER --}}
            {{-- ===================================================== --}}

            <div class="row align-items-center mb-3">

                <div class="col-md-8">

                    <h5 class="form-section-title">

                        <b>
                            Travel Request Information
                        </b>

                    </h5>

                </div>


                <div class="col-md-4 text-right">

                    <span class="badge badge-primary request-number-badge">

                        {{ $travelRequest->request_no ?? '-' }}

                    </span>

                </div>

            </div>


            <hr class="section-divider">



            {{-- ===================================================== --}}
            {{-- PART 1 : REQUEST INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="row">


                {{-- ================================================= --}}
                {{-- Request Number --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Request Number
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->request_no ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Company Name --}}
                {{-- ================================================= --}}
                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Company Name
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->company_name)

                                <strong>
                                    {{ $travelRequest->company_name }}
                                </strong>

                            @else

                                <span class="empty-value">
                                    Not Available
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Requested By --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Requested By
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->requested_by ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Employee Email --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Employee Email
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->employee_email)

                                <a href="mailto:{{ $travelRequest->employee_email }}">

                                    {{ $travelRequest->employee_email }}

                                </a>

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Travel ID --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Travel ID
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->travel_id ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Trip ID --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Trip ID
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->trip_id ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Vendor Name --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Vendor Name
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->vendor_name ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Vehicle Type --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Vehicle Type
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->vehicle_type ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Employee ID --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Employee ID
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->employee_id ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Cost Center --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Cost Center
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->cost_center ?? '-' }}

                        </div>

                    </div>

                </div>


            </div>



            {{-- ===================================================== --}}
            {{-- PART 2 : TRAVEL DATES & LOCATION --}}
            {{-- ===================================================== --}}

            <div class="mt-4">

                <h5 class="form-section-title">

                    <b>
                        Travel Dates &amp; Location
                    </b>

                </h5>

                <hr class="section-divider">

            </div>


            <div class="row">


                {{-- ================================================= --}}
                {{-- From Date --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            From Date
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->travel_from_date)

                                {{ $travelRequest->travel_from_date->format('d M Y') }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- To Date --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            To Date
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->travel_to_date)

                                {{ $travelRequest->travel_to_date->format('d M Y') }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Pickup Time --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Pick-up Time
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->pickup_time)

                                {{ \Carbon\Carbon::parse(
                                    $travelRequest->pickup_time
                                )->format('h:i A') }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- From City --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            From City
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->from_city ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Pickup Location --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Pick-up Location
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->pickup_location ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Drop Location --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Drop Location
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->drop_location ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Release Location --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Release Location
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->release_location ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Release Time --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Release Time
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->release_time)

                                {{ \Carbon\Carbon::parse(
                                    $travelRequest->release_time
                                )->format('h:i A') }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Travel Date Time --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Travel Date &amp; Time
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->travel_date_time)

                                {{ $travelRequest->travel_date_time->format(
                                    'd M Y, h:i A'
                                ) }}

                            @else

                                <span class="empty-value">
                                    Not Available
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Reporting Address --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Reporting Address
                        </span>

                        <div class="detail-text">

                            @if($travelRequest->reporting_address)

                                {{ $travelRequest->reporting_address }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Release Address --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Release Address
                        </span>

                        <div class="detail-text">

                            @if($travelRequest->release_address)

                                {{ $travelRequest->release_address }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


            </div>



            {{-- ===================================================== --}}
            {{-- PART 3 : PASSENGER INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="mt-4">

                <h5 class="form-section-title">

                    <b>
                        Passenger Information
                    </b>

                </h5>

                <hr class="section-divider">

            </div>


            <div class="row">


                {{-- ================================================= --}}
                {{-- Passenger Name --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Passenger Name
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->passenger_name ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Passenger Phone --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Passenger Phone
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->passenger_phone)

                                <a
                                    href="tel:{{ $travelRequest->passenger_phone }}">

                                    {{ $travelRequest->passenger_phone }}

                                </a>

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Traveler Mobile --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Traveler's Mobile
                        </span>

                        <div class="detail-value">

                            @if($travelRequest->traveler_mobile)

                                <a
                                    href="tel:{{ $travelRequest->traveler_mobile }}">

                                    {{ $travelRequest->traveler_mobile }}

                                </a>

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Passenger Count --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Number of Passengers
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->passenger_count ?? 1 }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Car Hire Type --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Car Hire Type
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->car_hire_type ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- For Use --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            For Use
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->for_use ?? '-' }}

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- GST Number --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            GST Number
                        </span>

                        <div class="detail-value">

                            {{ $travelRequest->gst_number ?? '-' }}

                        </div>

                    </div>

                </div>


            </div>



            {{-- ===================================================== --}}
            {{-- PART 4 : INSTRUCTIONS & STATUS --}}
            {{-- ===================================================== --}}

            <div class="mt-4">

                <h5 class="form-section-title">

                    <b>
                        Instructions &amp; Request Status
                    </b>

                </h5>

                <hr class="section-divider">

            </div>


            <div class="row">


                {{-- ================================================= --}}
                {{-- Purpose --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Purpose
                        </span>

                        <div class="detail-text">

                            @if($travelRequest->purpose)

                                {{ $travelRequest->purpose }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Specific Instruction --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Specific Instruction
                        </span>

                        <div class="detail-text">

                            @if($travelRequest->specific_instruction)

                                {{ $travelRequest->specific_instruction }}

                            @else

                                <span class="empty-value">
                                    Not Provided
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Status --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="detail-item">

                        <span class="detail-label">
                            Status
                        </span>

                        <div class="detail-value">

                            @php

                                $status = $travelRequest->status;

                                $statusClass = match ($status) {

                                    'pending' =>
                                        'status-pending',

                                    'approved' =>
                                        'status-approved',

                                    'rejected' =>
                                        'status-rejected',

                                    'assigned' =>
                                        'status-assigned',

                                    'completed' =>
                                        'status-completed',

                                    'cancelled' =>
                                        'status-cancelled',

                                    default =>
                                        'status-pending',

                                };

                            @endphp


                            <span class="status-badge {{ $statusClass }}">

                                {{ ucfirst($status ?? 'pending') }}

                            </span>

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- Remarks --}}
                {{-- ================================================= --}}

                <div class="col-md-8">

                    <div class="detail-item">

                        <span class="detail-label">
                            Remarks
                        </span>

                        <div class="detail-text">

                            @if($travelRequest->remarks)

                                {{ $travelRequest->remarks }}

                            @else

                                <span class="empty-value">
                                    No remarks available.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


            </div>



            {{-- ===================================================== --}}
            {{-- DUTY ASSIGNMENT --}}
            {{-- ===================================================== --}}

            @if($travelRequest->dutyAssignment)

                <div class="mt-4">

                    <h5 class="form-section-title">

                        <b>
                            Duty Assignment
                        </b>

                    </h5>

                    <hr class="section-divider">

                </div>


                <div class="assignment-box">

                    <div class="row">


                        {{-- Assignment Number --}}

                        <div class="col-md-4">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Assignment Number
                                </span>

                                <div class="detail-value">

                                    {{ $travelRequest->dutyAssignment->assignment_no ?? '-' }}

                                </div>

                            </div>

                        </div>



                        {{-- Driver --}}

                        <div class="col-md-4">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Driver
                                </span>

                                <div class="detail-value">

                                    @if($travelRequest->dutyAssignment->driver)

                                        {{ $travelRequest->dutyAssignment->driver->name ?? '-' }}

                                    @else

                                        <span class="empty-value">
                                            Not Assigned
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>



                        {{-- Vehicle --}}

                        <div class="col-md-4">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Vehicle
                                </span>

                                <div class="detail-value">

                                    @if($travelRequest->dutyAssignment->vehicle)

                                        {{ $travelRequest->dutyAssignment->vehicle->vehicle_number ?? '-' }}

                                    @else

                                        <span class="empty-value">
                                            Not Assigned
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>



                        {{-- Assignment Status --}}

                        <div class="col-md-4 mt-3">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Assignment Status
                                </span>

                                <div class="detail-value">

                                    {{ ucfirst(
                                        $travelRequest->dutyAssignment->status
                                        ?? '-'
                                    ) }}

                                </div>

                            </div>

                        </div>



                        {{-- Assigned At --}}

                        <div class="col-md-4 mt-3">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Assigned At
                                </span>

                                <div class="detail-value">

                                    @if(
                                        $travelRequest->dutyAssignment->assigned_at
                                    )

                                        {{ \Carbon\Carbon::parse(
                                            $travelRequest->dutyAssignment->assigned_at
                                        )->format('d M Y, h:i A') }}

                                    @else

                                        <span class="empty-value">
                                            Not Available
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>



                        {{-- Reporting Time --}}

                        <div class="col-md-4 mt-3">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Reporting Time
                                </span>

                                <div class="detail-value">

                                    @if(
                                        $travelRequest->dutyAssignment->reporting_time
                                    )

                                        {{ \Carbon\Carbon::parse(
                                            $travelRequest->dutyAssignment->reporting_time
                                        )->format('d M Y, h:i A') }}

                                    @else

                                        <span class="empty-value">
                                            Not Available
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>



                        {{-- Reporting Location --}}

                        <div class="col-md-6 mt-3">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Reporting Location
                                </span>

                                <div class="detail-value">

                                    {{ $travelRequest->dutyAssignment->reporting_location ?? '-' }}

                                </div>

                            </div>

                        </div>



                        {{-- Assignment Remarks --}}

                        <div class="col-md-6 mt-3">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Assignment Remarks
                                </span>

                                <div class="detail-value">

                                    {{ $travelRequest->dutyAssignment->remarks ?? '-' }}

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

            @else

                <div class="mt-4">

                    <h5 class="form-section-title">

                        <b>
                            Duty Assignment
                        </b>

                    </h5>

                    <hr class="section-divider">

                </div>


                <div class="alert alert-light border">

                    <i class="fa fa-info-circle mr-1"></i>

                    No duty assignment has been created for this travel request yet.

                </div>

            @endif



            {{-- ===================================================== --}}
            {{-- AUDIT INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="mt-4">

                <h5 class="form-section-title">

                    <b>
                        Audit Information
                    </b>

                </h5>

                <hr class="section-divider">

            </div>


            <div class="audit-box">

                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Created By --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="detail-item mb-0">

                            <span class="detail-label">
                                Created By
                            </span>

                            <div class="detail-value">

                                @if($travelRequest->createdBy)

                                    {{ $travelRequest->createdBy->name ?? '-' }}

                                @else

                                    <span class="empty-value">
                                        System / Not Available
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Created At --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="detail-item mb-0">

                            <span class="detail-label">
                                Created At
                            </span>

                            <div class="detail-value">

                                @if($travelRequest->created_at)

                                    {{ $travelRequest->created_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Updated By --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="detail-item mb-0">

                            <span class="detail-label">
                                Updated By
                            </span>

                            <div class="detail-value">

                                @if($travelRequest->updatedBy)

                                    {{ $travelRequest->updatedBy->name ?? '-' }}

                                @else

                                    <span class="empty-value">
                                        Not Available
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Updated At --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4 mt-3">

                        <div class="detail-item mb-0">

                            <span class="detail-label">
                                Updated At
                            </span>

                            <div class="detail-value">

                                @if($travelRequest->updated_at)

                                    {{ $travelRequest->updated_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Deleted By --}}
                    {{-- ================================================= --}}

                    @if($travelRequest->deleted_by)

                        <div class="col-md-4 mt-3">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Deleted By
                                </span>

                                <div class="detail-value">

                                    @if($travelRequest->deletedBy)

                                        {{ $travelRequest->deletedBy->name ?? '-' }}

                                    @else

                                        {{ $travelRequest->deleted_by }}

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endif



                    {{-- ================================================= --}}
                    {{-- Deleted At --}}
                    {{-- ================================================= --}}

                    @if($travelRequest->deleted_at)

                        <div class="col-md-4 mt-3">

                            <div class="detail-item mb-0">

                                <span class="detail-label">
                                    Deleted At
                                </span>

                                <div class="detail-value">

                                    {{ $travelRequest->deleted_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                </div>

                            </div>

                        </div>

                    @endif


                </div>

            </div>



            {{-- ================================================= --}}
            {{-- ACTION BUTTONS --}}
            {{-- ================================================= --}}
            <div class="col-12">

                <div class="text-right mt-4">


                    <a
                        href="{{ route('travel-requests.index') }}"
                        class="btn btn-danger">

                        <i class="fa fa-times"></i>

                        Cancel

                    </a>

                </div>

            </div>


        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <x-backend.footer />

</div>

@endsection