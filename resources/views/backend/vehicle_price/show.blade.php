@extends('backend.layouts.master')

@section('title')
    Vehicle Price Details
@endsection

@push('styles')

<style>
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 15px;
        font-weight: 500;
        color: #212529;
    }

    .price-value {
        font-size: 24px;
        font-weight: 700;
    }

    .vehicle-number {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
</style>

@endpush

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ================================================================ --}}
        {{-- PAGE HEADER --}}
        {{-- ================================================================ --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>
                            Vehicle Price Details
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

                                <a href="{{ route('vehicle-management.index') }}">
                                    Vehicle Management
                                </a>

                            </li>


                            <li class="breadcrumb-item">

                                <a href="{{ route('vehicle-price.index') }}">
                                    Vehicle Price Management
                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                Price Details

                            </li>

                        </ol>

                    </nav>

                </div>


                <div class="col-md-6 col-sm-12 text-right">

                    <a
                        href="{{ route(
                            'vehicle-price.edit',
                            $vehiclePrice->id
                        ) }}"
                        class="btn btn-warning">

                        <i class="dw dw-pencil-1"></i>

                        Edit Price

                    </a>


                    <a
                        href="{{ route('vehicle-price.index') }}"
                        class="btn btn-danger">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>


        {{-- ================================================================ --}}
        {{-- MAIN CARD --}}
        {{-- ================================================================ --}}

        <div class="card-box pd-20 mb-30">


            {{-- ============================================================ --}}
            {{-- VEHICLE INFORMATION --}}
            {{-- ============================================================ --}}

            <div class="mb-4">

                <h5
                    class="text-primary"
                    style="color:#023a85 !important;">

                    <b>
                        Vehicle Information
                    </b>

                </h5>

                <hr>

            </div>


            <div class="row">


                {{-- Vehicle Number --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Vehicle Number
                        </div>

                        <div class="detail-value vehicle-number">

                            {{ $vehiclePrice->vehicle->vehicle_number ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Registration Number --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Registration Number
                        </div>

                        <div class="detail-value">

                            {{ $vehiclePrice->vehicle->registration_number ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Category --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Vehicle Category
                        </div>

                        <div class="detail-value">

                            @if(
                                $vehiclePrice->vehicle &&
                                $vehiclePrice->vehicle->vehicleCategory
                            )

                                <span class="badge badge-info">

                                    {{
                                        $vehiclePrice
                                            ->vehicle
                                            ->vehicleCategory
                                            ->name
                                    }}

                                </span>

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Vehicle Type --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Vehicle Type
                        </div>

                        <div class="detail-value">

                            @if(
                                $vehiclePrice->vehicle &&
                                $vehiclePrice->vehicle->vehicleType
                            )

                                <strong>

                                    {{
                                        $vehiclePrice
                                            ->vehicle
                                            ->vehicleType
                                            ->name
                                    }}

                                </strong>

                                @if(
                                    $vehiclePrice
                                        ->vehicle
                                        ->vehicleType
                                        ->code
                                )

                                    <small class="text-muted">

                                        (
                                        {{
                                            $vehiclePrice
                                                ->vehicle
                                                ->vehicleType
                                                ->code
                                        }}
                                        )

                                    </small>

                                @endif

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Manufacturer --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Manufacturer
                        </div>

                        <div class="detail-value">

                            {{
                                $vehiclePrice->vehicle->manufacturer
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                {{-- Model --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Model
                        </div>

                        <div class="detail-value">

                            {{
                                $vehiclePrice->vehicle->model
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


            </div>


            {{-- ============================================================ --}}
            {{-- PRICING INFORMATION --}}
            {{-- ============================================================ --}}

            <div class="mt-3 mb-4">

                <h5
                    class="text-primary"
                    style="color:#023a85 !important;">

                    <b>
                        Pricing Information
                    </b>

                </h5>

                <hr>

            </div>


            <div class="row">


                {{-- Price --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Vehicle Price
                        </div>

                        <div class="detail-value price-value text-success">

                            ₹
                            {{
                                number_format(
                                    (float) $vehiclePrice->price,
                                    2
                                )
                            }}

                        </div>

                    </div>

                </div>


                {{-- Effective Date --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Effective Date
                        </div>

                        <div class="detail-value">

                            @if($vehiclePrice->effective_date)

                                {{
                                    $vehiclePrice
                                        ->effective_date
                                        ->format('d-m-Y')
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Price Record ID --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Price Record ID
                        </div>

                        <div class="detail-value">

                            #{{ $vehiclePrice->id }}

                        </div>

                    </div>

                </div>


                {{-- Remarks --}}
                <div class="col-md-12">

                    <div class="form-group">

                        <div class="detail-label">
                            Remarks
                        </div>

                        <div class="detail-value">

                            @if($vehiclePrice->remarks)

                                {!! nl2br(e($vehiclePrice->remarks)) !!}

                            @else

                                <span class="text-muted">
                                    No remarks available.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ============================================================ --}}
            {{-- AUDIT INFORMATION --}}
            {{-- ============================================================ --}}

            <div class="mt-3 mb-4">

                <h5
                    class="text-primary"
                    style="color:#023a85 !important;">

                    <b>
                        Audit Information
                    </b>

                </h5>

                <hr>

            </div>


            <div class="row">


                {{-- Created By --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Created By
                        </div>

                        <div class="detail-value">

                            {{ optional($vehiclePrice->createdBy)->name ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Created At --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Created At
                        </div>

                        <div class="detail-value">

                            @if($vehiclePrice->created_at)

                                {{
                                    $vehiclePrice
                                        ->created_at
                                        ->format('d-m-Y h:i A')
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Updated By --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Updated By
                        </div>

                        <div class="detail-value">

                            {{ optional($vehiclePrice->updatedBy)->name ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Updated At --}}
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">

                        <div class="detail-label">
                            Updated At
                        </div>

                        <div class="detail-value">

                            @if($vehiclePrice->updated_at)

                                {{
                                    $vehiclePrice
                                        ->updated_at
                                        ->format('d-m-Y h:i A')
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


            </div>


            {{-- ============================================================ --}}
            {{-- ACTION BUTTONS --}}
            {{-- ============================================================ --}}

            <div class="text-right mt-4">


                <a
                    href="{{ route('vehicle-price.index') }}"
                    class="btn btn-danger">

                    <i class="fa fa-arrow-left"></i>

                    Cancel

                </a>

            </div>


        </div>

    </div>


    <x-backend.footer />

</div>

@endsection