@extends('backend.layouts.master')

@section('title')
    Travel Requests
@endsection


@push('styles')

<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>
    .table td,
    .table th {
        vertical-align: middle;
    }

    .request-code {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .location-text {
        min-width: 180px;
        max-width: 260px;
        white-space: normal;
        line-height: 1.5;
    }

    .purpose-text {
        min-width: 150px;
        max-width: 250px;
        white-space: normal;
    }

    .import-columns-table td,
    .import-columns-table th {
        font-size: 13px;
        padding: 8px 10px;
    }

    .required-star {
        color: #dc3545;
    }

    .import-help {
        font-size: 13px;
        line-height: 1.6;
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
                        Travel Requests
                    </h4>

                    <p class="mb-0">
                        Manage all travel requests and passenger details.
                    </p>

                </div>


                {{-- Action Buttons --}}
                <div class="col-md-6 col-sm-12 text-right">

                    {{-- Import Excel --}}
                    <button
                        type="button"
                        class="btn btn-success mr-2"
                        data-toggle="modal"
                        data-target="#travelRequestImportModal">

                        <i class="fa fa-file-excel-o mr-1"></i>

                        Import Excel

                    </button>


                    {{-- Add Travel Request --}}
                    <a
                        href="{{ route('travel-requests.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus mr-1"></i>

                        Add New Travel Request

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert">

                <i class="fa fa-check-circle mr-1"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- IMPORT ERROR --}}
        {{-- ========================================================= --}}

        @if(session('import_error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert">

                <i class="fa fa-exclamation-triangle mr-1"></i>

                <strong>
                    Import Failed:
                </strong>

                {{ session('import_error') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    <span>&times;</span>

                </button>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- TRAVEL REQUEST EXCEL IMPORT MODAL --}}
        {{-- ========================================================= --}}

        <div
            class="modal fade"
            id="travelRequestImportModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="travelRequestImportModalLabel"
            aria-hidden="true">

            <div
                class="modal-dialog modal-lg modal-dialog-centered"
                role="document">

                <div class="modal-content">


                    {{-- Modal Header --}}
                    <div class="modal-header">

                        <h5
                            class="modal-title"
                            id="travelRequestImportModalLabel">

                            <i class="fa fa-file-excel-o text-success mr-1"></i>

                            Import Travel Requests

                        </h5>


                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">

                            <span aria-hidden="true">
                                &times;
                            </span>

                        </button>

                    </div>



                    {{-- ================================================= --}}
                    {{-- IMPORT FORM --}}
                    {{-- ================================================= --}}

                    <form
                        action="{{ route('travel-requests.import') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="travelRequestImportForm">

                        @csrf


                        <div class="modal-body">


                            {{-- Information --}}
                            <div class="alert alert-info import-help">

                                <i class="fa fa-info-circle mr-1"></i>

                                Upload an Excel file containing Travel Request data.

                                <br>

                                Supported formats:

                                <strong>
                                    .xlsx, .xls, .csv
                                </strong>

                                <br>

                                Maximum file size:

                                <strong>
                                    10 MB
                                </strong>

                            </div>



                            {{-- Excel File --}}
                            <div class="form-group">

                                <label for="travel_request_excel">

                                    <b>
                                        Excel File
                                    </b>

                                    <span class="required-star">
                                        *
                                    </span>

                                </label>


                                <input
                                    type="file"
                                    name="excel_file"
                                    id="travel_request_excel"
                                    class="form-control-file @error('excel_file') is-invalid @enderror"
                                    accept=".xlsx,.xls,.csv"
                                    required
                                >


                                @error('excel_file')

                                    <span class="text-danger d-block mt-1">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>



                            {{-- ================================================= --}}
                            {{-- EXPECTED COLUMNS --}}
                            {{-- ================================================= --}}

                            <div class="mt-4">

                                <div class="d-flex justify-content-between align-items-center mb-2">

                                    <h6 class="font-weight-bold mb-0">

                                        Expected Excel Columns

                                    </h6>


                                    <a
                                        href="{{ route('travel-requests.import.template') }}"
                                        class="btn btn-sm btn-outline-success">

                                        <i class="fa fa-download mr-1"></i>

                                        Download Template

                                    </a>

                                </div>



                                <div class="table-responsive">

                                    <table
                                        class="table table-sm table-bordered import-columns-table">

                                        <thead class="thead-light">

                                            <tr>

                                                <th>
                                                    Column
                                                </th>

                                                <th>
                                                    Required
                                                </th>

                                                <th>
                                                    Description
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>


                                            {{-- Request No --}}
                                            <tr>

                                                <td>
                                                    <code>request_no</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-danger">
                                                        Yes
                                                    </span>
                                                </td>

                                                <td>
                                                    Travel Request Number
                                                </td>

                                            </tr>


                                            {{-- Company --}}
                                            <tr>

                                                <td>
                                                    <code>company_name</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-danger">
                                                        Yes
                                                    </span>
                                                </td>

                                                <td>
                                                    Client / Company Name
                                                </td>

                                            </tr>


                                            {{-- Requested By --}}
                                            <tr>

                                                <td>
                                                    <code>requested_by</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Requester Name
                                                </td>

                                            </tr>


                                            {{-- Employee Email --}}
                                            <tr>

                                                <td>
                                                    <code>employee_email</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Employee Email
                                                </td>

                                            </tr>


                                            {{-- Travel ID --}}
                                            <tr>

                                                <td>
                                                    <code>travel_id</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Travel ID
                                                </td>

                                            </tr>


                                            {{-- Trip ID --}}
                                            <tr>

                                                <td>
                                                    <code>trip_id</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Trip ID
                                                </td>

                                            </tr>


                                            {{-- Vendor --}}
                                            <tr>

                                                <td>
                                                    <code>vendor_name</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Vendor Name
                                                </td>

                                            </tr>


                                            {{-- Vehicle --}}
                                            <tr>

                                                <td>
                                                    <code>vehicle_type</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Vehicle Type
                                                </td>

                                            </tr>


                                            {{-- From Date --}}
                                            <tr>

                                                <td>
                                                    <code>travel_from_date</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Travel Start Date
                                                </td>

                                            </tr>


                                            {{-- To Date --}}
                                            <tr>

                                                <td>
                                                    <code>travel_to_date</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Travel End Date
                                                </td>

                                            </tr>


                                            {{-- Pickup Time --}}
                                            <tr>

                                                <td>
                                                    <code>pickup_time</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Pickup Time
                                                </td>

                                            </tr>


                                            {{-- From City --}}
                                            <tr>

                                                <td>
                                                    <code>from_city</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Starting City
                                                </td>

                                            </tr>


                                            {{-- Pickup Location --}}
                                            <tr>

                                                <td>
                                                    <code>pickup_location</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-danger">
                                                        Yes
                                                    </span>
                                                </td>

                                                <td>
                                                    Pickup Location
                                                </td>

                                            </tr>


                                            {{-- Drop Location --}}
                                            <tr>

                                                <td>
                                                    <code>drop_location</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-danger">
                                                        Yes
                                                    </span>
                                                </td>

                                                <td>
                                                    Drop Location
                                                </td>

                                            </tr>


                                            {{-- Release Location --}}
                                            <tr>

                                                <td>
                                                    <code>release_location</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Release Location
                                                </td>

                                            </tr>


                                            {{-- Passenger --}}
                                            <tr>

                                                <td>
                                                    <code>passenger_name</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-danger">
                                                        Yes
                                                    </span>
                                                </td>

                                                <td>
                                                    Passenger Name
                                                </td>

                                            </tr>


                                            {{-- Passenger Phone --}}
                                            <tr>

                                                <td>
                                                    <code>passenger_phone</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Passenger Phone
                                                </td>

                                            </tr>


                                            {{-- Traveler Mobile --}}
                                            <tr>

                                                <td>
                                                    <code>traveler_mobile</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Traveler Mobile
                                                </td>

                                            </tr>


                                            {{-- Employee ID --}}
                                            <tr>

                                                <td>
                                                    <code>employee_id</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Employee ID
                                                </td>

                                            </tr>


                                            {{-- Cost Center --}}
                                            <tr>

                                                <td>
                                                    <code>cost_center</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>

                                                </td>

                                                <td>
                                                    Cost Center
                                                </td>

                                            </tr>


                                            {{-- Car Hire --}}
                                            <tr>

                                                <td>
                                                    <code>car_hire_type</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Car Hire Type
                                                </td>

                                            </tr>


                                            {{-- For Use --}}
                                            <tr>

                                                <td>
                                                    <code>for_use</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Vehicle Usage
                                                </td>

                                            </tr>


                                            {{-- GST --}}
                                            <tr>

                                                <td>
                                                    <code>gst_number</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    GST Number
                                                </td>

                                            </tr>


                                            {{-- Reporting --}}
                                            <tr>

                                                <td>
                                                    <code>reporting_address</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Reporting Address
                                                </td>

                                            </tr>


                                            {{-- Release Address --}}
                                            <tr>

                                                <td>
                                                    <code>release_address</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Release Address
                                                </td>

                                            </tr>


                                            {{-- Release Time --}}
                                            <tr>

                                                <td>
                                                    <code>release_time</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Release Time
                                                </td>

                                            </tr>


                                            {{-- Travel Date Time --}}
                                            <tr>

                                                <td>
                                                    <code>travel_date_time</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Travel Date &amp; Time
                                                </td>

                                            </tr>


                                            {{-- Passenger Count --}}
                                            <tr>

                                                <td>
                                                    <code>passenger_count</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Number of Passengers
                                                </td>

                                            </tr>


                                            {{-- Purpose --}}
                                            <tr>

                                                <td>
                                                    <code>purpose</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Travel Purpose
                                                </td>

                                            </tr>


                                            {{-- Specific Instruction --}}
                                            <tr>

                                                <td>
                                                    <code>specific_instruction</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Special Instructions
                                                </td>

                                            </tr>


                                            {{-- Status --}}
                                            <tr>

                                                <td>
                                                    <code>status</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    pending / approved / rejected / assigned / completed / cancelled
                                                </td>

                                            </tr>


                                            {{-- Remarks --}}
                                            <tr>

                                                <td>
                                                    <code>remarks</code>
                                                </td>

                                                <td>
                                                    <span class="badge badge-secondary">
                                                        No
                                                    </span>
                                                </td>

                                                <td>
                                                    Additional Remarks
                                                </td>

                                            </tr>


                                        </tbody>

                                    </table>

                                </div>

                            </div>



                            {{-- ================================================= --}}
                            {{-- IMPORT NOTE --}}
                            {{-- ================================================= --}}

                            <div class="alert alert-warning mb-0 mt-3 import-help">

                                <i class="fa fa-lightbulb-o mr-1"></i>

                                <strong>Important:</strong>

                                The Excel column names should match the template
                                exactly.

                                <br>

                                Required fields:

                                <strong>
                                    request_no, company_name, passenger_name,
                                    pickup_location, drop_location
                                </strong>

                            </div>

                        </div>



                        {{-- ================================================= --}}
                        {{-- MODAL FOOTER --}}
                        {{-- ================================================= --}}

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">

                                <i class="fa fa-times mr-1"></i>

                                Cancel

                            </button>


                            <button
                                type="submit"
                                class="btn btn-success"
                                id="travelRequestImportBtn">

                                <i class="fa fa-upload mr-1"></i>

                                Import Excel

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- TRAVEL REQUEST LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- Card Header --}}
            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">
                        All Travel Requests
                    </h4>


                    <span class="badge badge-primary">

                        Total :
                        {{ $travelRequests->count() }}

                    </span>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <div class="table-responsive">

                    <table
                        class="table hover multiple-select-row data-table-export1 nowrap p-3"
                        data-title="Travel Requests">


                        {{-- ================================================= --}}
                        {{-- TABLE HEADER --}}
                        {{-- ================================================= --}}

                        <thead>

                            <tr>

                                <th class="text-wrap">
                                    Sr. No.
                                </th>

                                <th class="text-wrap">
                                    Request No.
                                </th>

                                <th class="text-wrap">
                                    Request Date
                                </th>

                                <th class="text-wrap">
                                    Company Name
                                </th>

                                <th class="text-wrap">
                                    Requested By
                                </th>

                                <th class="text-wrap">
                                    Passenger Name
                                </th>

                                <th class="text-wrap">
                                    Passenger Phone
                                </th>

                                <th class="text-wrap">
                                    Pickup Location
                                </th>

                                <th class="text-wrap">
                                    Drop Location
                                </th>

                                <th class="text-wrap">
                                    Travel Date &amp; Time
                                </th>

                                <th class="text-wrap">
                                    Passengers
                                </th>

                                <th class="text-wrap">
                                    Purpose
                                </th>

                                <th class="text-wrap">
                                    Status
                                </th>

                                <th class="text-wrap no-export">
                                    View
                                </th>

                                <th class="text-wrap no-export">
                                    Edit
                                </th>

                                <th class="text-wrap no-export">
                                    Delete
                                </th>

                            </tr>

                        </thead>



                        {{-- ================================================= --}}
                        {{-- TABLE BODY --}}
                        {{-- ================================================= --}}

                        <tbody>

                            @forelse($travelRequests as $key => $travelRequest)

                                <tr>


                                    {{-- Sr No --}}
                                    <td>

                                        {{ $travelRequests->firstItem() + $key }}

                                    </td>



                                    {{-- Request Number --}}
                                    <td>

                                        <strong class="request-code">

                                            {{ $travelRequest->request_no ?? '-' }}

                                        </strong>

                                    </td>



                                    {{-- Request Date --}}
                                    <td>

                                        @if($travelRequest->created_at)

                                            <strong>

                                                {{ $travelRequest->created_at->format('d-m-Y') }}

                                            </strong>

                                            <small class="text-muted d-block">

                                                {{ $travelRequest->created_at->format('h:i A') }}

                                            </small>

                                        @else

                                            -

                                        @endif

                                    </td>



                                    {{-- Company Name --}}
                                    <td>

                                        <strong class="text-dark">

                                            {{ $travelRequest->company_name ?? '-' }}

                                        </strong>

                                    </td>



                                    {{-- Requested By --}}
                                    <td>

                                        @if(!empty($travelRequest->requested_by))

                                            <strong class="text-dark">

                                                {{ $travelRequest->requested_by }}

                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>



                                    {{-- Passenger Name --}}
                                    <td>

                                        <strong class="text-dark">

                                            {{ $travelRequest->passenger_name ?? '-' }}

                                        </strong>

                                    </td>



                                    {{-- Passenger Phone --}}
                                    <td>

                                        {{ $travelRequest->passenger_phone ?? '-' }}

                                    </td>



                                    {{-- Pickup Location --}}
                                    <td>

                                        <div class="location-text">

                                            <i class="fa fa-map-marker text-success mr-1"></i>

                                            {{ $travelRequest->pickup_location ?? '-' }}

                                        </div>

                                    </td>



                                    {{-- Drop Location --}}
                                    <td>

                                        <div class="location-text">

                                            <i class="fa fa-map-marker text-danger mr-1"></i>

                                            {{ $travelRequest->drop_location ?? '-' }}

                                        </div>

                                    </td>



                                    {{-- Travel Date Time --}}
                                    <td>

                                        @if($travelRequest->travel_date_time)

                                            <strong>

                                                {{ $travelRequest->travel_date_time->format('d-m-Y') }}

                                            </strong>

                                            <small class="text-muted d-block">

                                                <i class="fa fa-clock-o mr-1"></i>

                                                {{ $travelRequest->travel_date_time->format('h:i A') }}

                                            </small>

                                        @else

                                            -

                                        @endif

                                    </td>



                                    {{-- Passenger Count --}}
                                    <td>

                                        <span class="badge badge-info">

                                            <i class="fa fa-users mr-1"></i>

                                            {{ $travelRequest->passenger_count ?? 0 }}

                                        </span>

                                    </td>



                                    {{-- Purpose --}}
                                    <td>

                                        @if(!empty($travelRequest->purpose))

                                            <div
                                                class="purpose-text"
                                                title="{{ $travelRequest->purpose }}">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $travelRequest->purpose,
                                                    40
                                                ) }}

                                            </div>

                                        @else

                                            -

                                        @endif

                                    </td>



                                    {{-- ================================================= --}}
                                    {{-- STATUS --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @php

                                            $status = $travelRequest->status;

                                            $statusClass = match ($status) {

                                                'pending' =>
                                                    'badge-warning',

                                                'approved' =>
                                                    'badge-primary',

                                                'rejected' =>
                                                    'badge-danger',

                                                'assigned' =>
                                                    'badge-info',

                                                'completed' =>
                                                    'badge-success',

                                                'cancelled' =>
                                                    'badge-secondary',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match ($status) {

                                                'pending' =>
                                                    'fa-clock-o',

                                                'approved' =>
                                                    'fa-check',

                                                'rejected' =>
                                                    'fa-times',

                                                'assigned' =>
                                                    'fa-car',

                                                'completed' =>
                                                    'fa-check-circle',

                                                'cancelled' =>
                                                    'fa-ban',

                                                default =>
                                                    'fa-info-circle',

                                            };

                                        @endphp


                                        <span
                                            class="badge {{ $statusClass }} badge-pill px-3 py-2">

                                            <i class="fa {{ $statusIcon }} mr-1"></i>

                                            {{ ucfirst($status ?? 'Unknown') }}

                                        </span>

                                    </td>



                                    {{-- ================================================= --}}
                                    {{-- VIEW --}}
                                    {{-- ================================================= --}}

                                    <td class="no-export">

                                        <a
                                            href="{{ route(
                                                'travel-requests.show',
                                                $travelRequest->id
                                            ) }}"
                                            class="btn btn-info btn-sm">

                                            <i class="dw dw-eye mr-1"></i>

                                            View

                                        </a>

                                    </td>



                                    {{-- ================================================= --}}
                                    {{-- EDIT --}}
                                    {{-- ================================================= --}}

                                    <td class="no-export">

                                        <a
                                            href="{{ route(
                                                'travel-requests.edit',
                                                $travelRequest->id
                                            ) }}"
                                            class="btn btn-warning btn-sm">

                                            <i class="dw dw-pencil-1 mr-1"></i>

                                            Edit

                                        </a>

                                    </td>



                                    {{-- ================================================= --}}
                                    {{-- DELETE --}}
                                    {{-- ================================================= --}}

                                    <td class="no-export">

                                        <form
                                            action="{{ route('travel-requests.destroy', $travelRequest->id) }}"
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
                                        colspan="16"
                                        class="text-center py-4">

                                        <i class="fa fa-inbox fa-2x text-muted d-block mb-2"></i>

                                        <strong>
                                            No Travel Requests Found
                                        </strong>

                                        <small class="text-muted d-block mt-1">

                                            Create a new travel request or import
                                            requests from Excel.

                                        </small>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

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



@push('scripts')


{{-- ========================================================= --}}
{{-- SWEET ALERT --}}
{{-- ========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();

            Swal.fire({

                title: 'Are you sure?',

                text: 'This travel request will be moved to trash!',

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

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Excel Import Submit
    |--------------------------------------------------------------------------
    */

    $('#travelRequestImportForm').on('submit', function () {

        const button = $('#travelRequestImportBtn');


        button
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin mr-1"></i> Importing...'
            );

    });


    /*
    |--------------------------------------------------------------------------
    | Re-open Import Modal After Validation Error
    |--------------------------------------------------------------------------
    */

    @if($errors->has('excel_file') || session('import_error'))

        $('#travelRequestImportModal').modal('show');

    @endif


});

</script>


{{-- ========================================================= --}}
{{-- DATATABLE --}}
{{-- ========================================================= --}}

<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>


@endpush