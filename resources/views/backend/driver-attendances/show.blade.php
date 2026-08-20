@extends('backend.layouts.master')

@section('title')
Driver Attendance Details
@endsection

@push('styles')

<style>

    .attendance-detail-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .attendance-detail-value {
        font-size: 15px;
        font-weight: 600;
        color: #212529;
    }

    .attendance-box {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        height: 100%;
        background: #fff;
    }

    .remarks-box {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 15px;
        min-height: 100px;
    }

</style>

@endpush

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ================= PAGE HEADER ================= --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-8 col-sm-12">

                    <div class="title">

                        <h4>

                            @if(auth()->user()->isDriver())
                                My Attendance
                            @else
                                Driver Attendance
                            @endif

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

                                <a href="{{ route('driver-attendances.index') }}">

                                    @if(auth()->user()->isDriver())
                                        My Attendance
                                    @else
                                        Driver Attendance
                                    @endif

                                </a>

                            </li>

                            <li class="breadcrumb-item active">
                                Attendance Details
                            </li>

                        </ol>

                    </nav>

                </div>


                <div class="col-md-4 col-sm-12 text-right">

                    <a
                        href="{{ route(
                            'driver-attendances.edit',
                            $driverAttendance->id
                        ) }}"
                        class="btn btn-warning">

                        <i class="fa fa-pencil"></i>

                        Edit

                    </a>

                    <a
                        href="{{ route('driver-attendances.index') }}"
                        class="btn btn-primary">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>


        {{-- ================= MAIN CARD ================= --}}

        <div class="card-box pd-20 mb-30">


            {{-- ================= HEADER ================= --}}

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="text-blue mb-1">

                        Attendance Details

                    </h4>

                    <p class="text-muted mb-0">

                        Driver attendance record information

                    </p>

                </div>


                {{-- Status --}}

                @php

                    $status = $driverAttendance->status;

                    $statusClass = match ($status) {

                        'present' =>
                            'badge-success',

                        'absent' =>
                            'badge-danger',

                        'half_day' =>
                            'badge-warning',

                        'leave' =>
                            'badge-info',

                        default =>
                            'badge-secondary',

                    };

                    $statusIcon = match ($status) {

                        'present' =>
                            'fa-check-circle',

                        'absent' =>
                            'fa-times-circle',

                        'half_day' =>
                            'fa-adjust',

                        'leave' =>
                            'fa-calendar',

                        default =>
                            'fa-info-circle',

                    };

                @endphp


                <span
                    class="badge {{ $statusClass }} badge-pill px-3 py-2">

                    <i class="fa {{ $statusIcon }}"></i>

                    {{ ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $status ?? 'Unknown'
                        )
                    ) }}

                </span>

            </div>


            <hr>


            {{-- ========================================================= --}}
            {{-- DRIVER INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="mb-4">

                <h5
                    class="text-primary"
                    style="color:#023a85 !important;">

                    <b>Driver Information</b>

                </h5>

                <hr>

            </div>


            <div class="row">


                {{-- Driver Name --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Driver Name
                        </div>

                        <div class="attendance-detail-value">

                            {{ $driverAttendance->driver->name ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Driver Code --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Driver Code
                        </div>

                        <div class="attendance-detail-value">

                            {{ $driverAttendance->driver->driver_code ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Mobile --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Mobile
                        </div>

                        <div class="attendance-detail-value">

                            {{ $driverAttendance->driver->mobile ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- ATTENDANCE INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="mb-4 mt-3">

                <h5
                    class="text-primary"
                    style="color:#023a85 !important;">

                    <b>Attendance Information</b>

                </h5>

                <hr>

            </div>


            <div class="row">


                {{-- Attendance Date --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Attendance Date
                        </div>

                        <div class="attendance-detail-value">

                            @if($driverAttendance->attendance_date)

                                {{ $driverAttendance->attendance_date->format('d-m-Y') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- In Time --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            In Time
                        </div>

                        <div class="attendance-detail-value">

                            {{ $driverAttendance->in_time ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Out Time --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Out Time
                        </div>

                        <div class="attendance-detail-value">

                            {{ $driverAttendance->out_time ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Total Hours --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Total Hours
                        </div>

                        <div class="attendance-detail-value">

                            @if($driverAttendance->total_hours)

                                <span class="badge badge-info px-3 py-2">

                                    <i class="fa fa-clock-o"></i>

                                    {{ $driverAttendance->total_hours }}

                                </span>

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Source --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Source
                        </div>

                        <div class="attendance-detail-value">

                            <span class="badge badge-secondary">

                                {{ ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $driverAttendance->source ?? 'manual'
                                    )
                                ) }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- Working Sheet --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Working Sheet
                        </div>

                        <div class="attendance-detail-value">

                            @if($driverAttendance->workingSheet)

                                {{ $driverAttendance->workingSheet->sheet_no ?? '-' }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- REMARKS --}}
            {{-- ========================================================= --}}

            <div class="mb-4 mt-3">

                <h5
                    class="text-primary"
                    style="color:#023a85 !important;">

                    <b>Remarks</b>

                </h5>

                <hr>

            </div>


            <div class="remarks-box mb-4">

                @if(!empty($driverAttendance->remarks))

                    {{ $driverAttendance->remarks }}

                @else

                    <span class="text-muted">
                        No remarks available.
                    </span>

                @endif

            </div>


            {{-- ========================================================= --}}
            {{-- AUDIT INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="mb-4 mt-3">

                <h5
                    class="text-primary"
                    style="color:#023a85 !important;">

                    <b>Audit Information</b>

                </h5>

                <hr>

            </div>


            <div class="row">


                {{-- Created By --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Created By
                        </div>

                        <div class="attendance-detail-value">

                            {{ $driverAttendance->createdBy->name ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Updated By --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Updated By
                        </div>

                        <div class="attendance-detail-value">

                            {{ $driverAttendance->updatedBy->name ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Created At --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Created At
                        </div>

                        <div class="attendance-detail-value">

                            @if($driverAttendance->created_at)

                                {{ $driverAttendance->created_at->format('d-m-Y h:i A') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Updated At --}}

                <div class="col-md-4 mb-3">

                    <div class="attendance-box">

                        <div class="attendance-detail-label">
                            Updated At
                        </div>

                        <div class="attendance-detail-value">

                            @if($driverAttendance->updated_at)

                                {{ $driverAttendance->updated_at->format('d-m-Y h:i A') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- ACTIONS --}}
            {{-- ========================================================= --}}

            <div class="text-right mt-4">

                <a
                    href="{{ route('driver-attendances.index') }}"
                    class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>

                    Back to List

                </a>

                <a
                    href="{{ route(
                        'driver-attendances.edit',
                        $driverAttendance->id
                    ) }}"
                    class="btn btn-warning">

                    <i class="fa fa-pencil"></i>

                    Edit Attendance

                </a>

            </div>

        </div>

    </div>

    <x-backend.footer />

</div>

@endsection