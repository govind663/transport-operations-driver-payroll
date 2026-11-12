@extends('backend.layouts.master')

@section('title')
    Create Driver Attendance
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>{{ $isDriver ? 'My Attendance' : 'Driver Attendance' }}</h4>
                    </div>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('driver-attendances.index') }}">
                                    {{ $isDriver ? 'My Attendance' : 'Driver Attendance' }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Create Attendance
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- ================= VALIDATION SUMMARY ================= --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Please correct the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= CREATE ATTENDANCE FORM ================= --}}
        <form
            id="driverAttendanceForm"
            action="{{ route('driver-attendances.store') }}"
            method="POST"
            novalidate>

            @csrf

            <div class="card-box pd-20 mb-30">

                {{-- ================= ATTENDANCE INFORMATION ================= --}}
                <div class="mb-4">
                    <h5 class="text-primary" style="color:#023a85 !important;">
                        <b>Attendance Information</b>
                    </h5>
                    <hr>
                </div>

                <div class="row">

                    {{-- Driver --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="driver_id">
                                <b>Driver <span class="text-danger">*</span></b>
                            </label>

                            @if($isDriver)
                                @php
                                    // The controller supplies only the authenticated driver's record.
                                    $loggedInDriver = $drivers->first();
                                @endphp

                                <input
                                    type="text"
                                    id="driver_display_name"
                                    class="form-control"
                                    value="{{ $loggedInDriver->name ?? 'Driver Not Mapped' }}"
                                    readonly>

                                @if($loggedInDriver)
                                    <input
                                        type="hidden"
                                        name="driver_id"
                                        id="driver_id"
                                        value="{{ $loggedInDriver->id }}">
                                @endif
                            @else
                                <select
                                    name="driver_id"
                                    id="driver_id"
                                    class="form-control custom-select2 @error('driver_id') is-invalid @enderror"
                                    required>

                                    <option value="">Select Driver</option>

                                    @foreach($drivers as $driver)
                                        <option
                                            value="{{ $driver->id }}"
                                            {{ (string) old('driver_id') === (string) $driver->id ? 'selected' : '' }}>
                                            {{ $driver->first_name }} {{ $driver->last_name }}
                                            @if(!empty($driver->driver_code))
                                                ({{ $driver->driver_code }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            @error('driver_id')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Attendance Date --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="attendance_date">
                                <b>Attendance Date <span class="text-danger">*</span></b>
                            </label>

                            <input
                                type="date"
                                name="attendance_date"
                                id="attendance_date"
                                class="form-control @error('attendance_date') is-invalid @enderror"
                                value="{{ old('attendance_date', now()->toDateString()) }}"
                                required>

                            @error('attendance_date')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Attendance Status --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">
                                <b>Status <span class="text-danger">*</span></b>
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror"
                                required>

                                <option value="">Select Status</option>

                                {{-- STATUSES is a list of actual database enum values, not keyed labels. --}}
                                @foreach($statuses as $status)
                                    <option
                                        value="{{ $status }}"
                                        {{ old('status') === $status ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>

                            @error('status')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- ================= TIME INFORMATION ================= --}}
                    <div class="col-12 mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Time Information</b>
                        </h5>
                        <hr>
                    </div>

                    {{-- In Time --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="in_time"><b>In Time</b></label>
                            <input
                                type="time"
                                name="in_time"
                                id="in_time"
                                class="form-control @error('in_time') is-invalid @enderror"
                                value="{{ old('in_time') }}"
                                step="60">

                            @error('in_time')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Out Time --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="out_time"><b>Out Time</b></label>
                            <input
                                type="time"
                                name="out_time"
                                id="out_time"
                                class="form-control @error('out_time') is-invalid @enderror"
                                value="{{ old('out_time') }}"
                                step="60">

                            @error('out_time')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                When Out Time is earlier than In Time, it is treated as the next day.
                            </small>
                        </div>
                    </div>

                    {{-- Total Hours --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_hours"><b>Total Hours</b></label>
                            <input
                                type="text"
                                name="total_hours"
                                id="total_hours"
                                class="form-control @error('total_hours') is-invalid @enderror"
                                value="{{ old('total_hours') }}"
                                placeholder="HH:MM"
                                inputmode="numeric"
                                autocomplete="off"
                                readonly>

                            @error('total_hours')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                Automatically calculated from In Time and Out Time. The server validates and recalculates the duration.
                            </small>
                        </div>
                    </div>

                    {{-- ================= ATTENDANCE SOURCE ================= --}}
                    <div class="col-12 mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Attendance Source</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="attendance_source"><b>Source</b></label>
                            <input
                                type="text"
                                id="attendance_source"
                                class="form-control"
                                value="Manual"
                                readonly>
                            <input type="hidden" name="source" value="manual">
                        </div>
                    </div>

                    {{-- ================= REMARKS ================= --}}
                    <div class="col-12 mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Remarks</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="remarks"><b>Remarks</b></label>
                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                maxlength="1000"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter remarks">{{ old('remarks') }}</textarea>

                            @error('remarks')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- ================= FORM ACTIONS ================= --}}
                    <div class="col-12">
                        <div class="text-right mt-4">
                            <a
                                href="{{ route('driver-attendances.index') }}"
                                class="btn btn-danger">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                Cancel
                            </a>

                            <button
                                type="submit"
                                id="saveAttendanceButton"
                                class="btn btn-success">
                                <i class="fa fa-save" aria-hidden="true"></i>
                                Save Attendance
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
(function ($) {
    'use strict';

    /**
     * Convert a 24-hour HH:MM value into minutes after midnight.
     * Returns null if the input is empty or malformed.
     */
    function timeToMinutes(value) {
        if (!value || !/^([01]\d|2[0-3]):([0-5]\d)$/.test(value)) {
            return null;
        }

        var parts = value.split(':');
        return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10);
    }

    /**
     * Calculate elapsed time. If Out Time is earlier than In Time,
     * treat Out Time as belonging to the following calendar day.
     *
     * The resulting HH:MM string is normalized by the FormRequest
     * into a decimal number before it reaches the service/database.
     */
    function calculateTotalHours() {
        var startMinutes = timeToMinutes($('#in_time').val());
        var endMinutes = timeToMinutes($('#out_time').val());
        var $totalHours = $('#total_hours');

        if (startMinutes === null || endMinutes === null) {
            $totalHours.val('');
            return;
        }

        if (endMinutes < startMinutes) {
            endMinutes += 24 * 60;
        }

        var durationMinutes = endMinutes - startMinutes;
        var hours = Math.floor(durationMinutes / 60);
        var minutes = durationMinutes % 60;

        $totalHours.val(
            String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0')
        );
    }

    $(function () {
        $('#in_time, #out_time').on('input change', calculateTotalHours);

        // Recalculate after a validation redirect when old time values are present.
        calculateTotalHours();
    });
})(jQuery);
</script>
@endpush

