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

    .vehicle-type-code {
        font-weight: 600;
        letter-spacing: 0.5px;
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


                {{-- Add Travel Request --}}
                <div class="col-md-6 col-sm-12 text-right">

                    <a
                        href="{{ route('travel-requests.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Travel Request

                    </a>

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

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Travel Requests">


                    {{-- ================================================= --}}
                    {{-- TABLE HEADER --}}
                    {{-- ================================================= --}}
                    <thead>
                        <tr>
                            <th class="text-wrap">Sr. No.</th>
                            <th class="text-wrap">Request No.</th>
                            <th class="text-wrap">Client</th>
                            <th class="text-wrap">Requested By</th>
                            <th class="text-wrap">Passenger</th>
                            <th class="text-wrap">Passenger Phone</th>
                            <th class="text-wrap">Pickup Location</th>
                            <th class="text-wrap">Drop Location</th>
                            <th class="text-wrap">Travel Date &amp; Time</th>
                            <th class="text-wrap">Passengers</th>
                            <th class="text-wrap">Purpose</th>
                            <th class="text-wrap">Status</th>
                            <th class="text-wrap no-export">Edit</th>
                            <th class="text-wrap no-export">Delete</th>
                        </tr>
                    </thead>

                    {{-- ================================================= --}}
                    {{-- TABLE BODY --}}
                    {{-- ================================================= --}}
                    <tbody>

                        @forelse($travelRequests as $key => $travelRequest)

                            <tr>


                                {{-- ========================================= --}}
                                {{-- Sr No --}}
                                {{-- ========================================= --}}

                                <td>

                                    {{ $key + 1 }}

                                </td>



                                {{-- ========================================= --}}
                                {{-- Request Number --}}
                                {{-- ========================================= --}}

                                <td>

                                    <strong class="request-code">

                                        {{ $travelRequest->request_no ?? '-' }}

                                    </strong>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Client --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($travelRequest->client)

                                        <strong class="text-dark">

                                            {{ $travelRequest->client->name
                                                ?? $travelRequest->client->client_name
                                                ?? '-' }}

                                        </strong>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Requested By --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($travelRequest->requestedBy)

                                        <strong class="text-dark">

                                            {{ $travelRequest->requestedBy->name ?? '-' }}

                                        </strong>

                                        @if(!empty($travelRequest->requestedBy->email))

                                            <small class="text-muted d-block">

                                                {{ $travelRequest->requestedBy->email }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Passenger Name --}}
                                {{-- ========================================= --}}

                                <td>

                                    <strong class="text-dark">

                                        {{ $travelRequest->passenger_name ?? '-' }}

                                    </strong>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Passenger Phone --}}
                                {{-- ========================================= --}}

                                <td>

                                    {{ $travelRequest->passenger_phone ?? '-' }}

                                </td>



                                {{-- ========================================= --}}
                                {{-- Pickup Location --}}
                                {{-- ========================================= --}}

                                <td>

                                    <div class="location-text">

                                        <i class="fa fa-map-marker text-success mr-1"></i>

                                        {{ $travelRequest->pickup_location ?? '-' }}

                                    </div>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Drop Location --}}
                                {{-- ========================================= --}}

                                <td>

                                    <div class="location-text">

                                        <i class="fa fa-map-marker text-danger mr-1"></i>

                                        {{ $travelRequest->drop_location ?? '-' }}

                                    </div>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Travel Date Time --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if($travelRequest->travel_date_time)

                                        <strong>

                                            {{ $travelRequest->travel_date_time->format('d-m-Y') }}

                                        </strong>

                                        <small class="text-muted d-block">

                                            <i class="fa fa-clock-o"></i>

                                            {{ $travelRequest->travel_date_time->format('h:i A') }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Passenger Count --}}
                                {{-- ========================================= --}}

                                <td>

                                    <span class="badge badge-info">

                                        <i class="fa fa-users mr-1"></i>

                                        {{ $travelRequest->passenger_count ?? 0 }}

                                    </span>

                                </td>



                                {{-- ========================================= --}}
                                {{-- Purpose --}}
                                {{-- ========================================= --}}

                                <td>

                                    @if(!empty($travelRequest->purpose))

                                        <span
                                            title="{{ $travelRequest->purpose }}">

                                            {{ \Illuminate\Support\Str::limit(
                                                $travelRequest->purpose,
                                                40
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- ========================================= --}}
                                {{-- Status --}}
                                {{-- ========================================= --}}

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

                                        <i class="fa {{ $statusIcon }}"></i>

                                        {{ ucfirst($status ?? 'Unknown') }}

                                    </span>

                                </td>

                                {{-- ========================================= --}}
                                {{-- Edit --}}
                                {{-- ========================================= --}}

                                <td class="no-export">

                                    <a
                                        href="{{ route(
                                            'travel-requests.edit',
                                            $travelRequest->id
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
                                            'travel-requests.destroy',
                                            $travelRequest->id
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
                                    colspan="15"
                                    class="text-center">

                                    No Travel Requests Found

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
    | Travel Request Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();


            Swal.fire({

                title: 'Are you sure?',

                text: 'This travel request will be moved to trash.',

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