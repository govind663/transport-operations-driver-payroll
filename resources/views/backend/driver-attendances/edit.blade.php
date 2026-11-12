@extends('backend.layouts.master')

@section('title')
    Edit Driver Attendance
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">
                        <h4>Edit Driver Attendance</h4>
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
                                    {{ $isDriver ? 'My Attendance' : 'Driver Attendance' }}
                                </a>

                            </li>

                            <li class="breadcrumb-item active">
                                Edit Attendance
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= VALIDATION ERRORS ================= --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>Please correct the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('driver-attendances.update', $driverAttendance->id) }}"
            method="POST">

            @csrf
            @method('PUT')


            <div class="card-box pd-20 mb-30">

                {{-- ========================================================= --}}
                {{-- ATTENDANCE INFORMATION --}}
                {{-- ========================================================= --}}

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

                            <label>
                                <b>
                                    Driver
                                    <span class="text-danger">*</span>
                                </b>
                            </label>


                            @if($isDriver)

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $driverAttendance->driver->name ?? '-' }}"
                                    readonly>

                                <input
                                    type="hidden"
                                    name="driver_id"
                                    value="{{ $driverAttendance->driver_id }}">

                            @else

                                <select
                                    name="driver_id"
                                    id="driver_id"
                                    class="form-control custom-select2 @error('driver_id') is-invalid @enderror">

                                    <option value="">
                                        Select Driver
                                    </option>

                                    @foreach($drivers as $driver)

                                        <option
                                            value="{{ $driver->id }}"
                                            {{ old('driver_id', $driverAttendance->driver_id) == $driver->id ? 'selected' : '' }}>

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

                            <label>
                                <b>
                                    Attendance Date
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="date"
                                name="attendance_date"
                                id="attendance_date"
                                class="form-control @error('attendance_date') is-invalid @enderror"
                                value="{{ old(
                                    'attendance_date',
                                    optional($driverAttendance->attendance_date)->format('Y-m-d')
                                ) }}">

                            @error('attendance_date')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Status
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                @foreach($statuses as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ old('status', $driverAttendance->status) == $status ? 'selected' : '' }}>

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


                    {{-- ========================================================= --}}
                    {{-- TIME INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Time Information</b>
                        </h5>

                        <hr>

                    </div>


                    {{-- In Time --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>In Time</b>
                            </label>

                            <input
                                type="time"
                                name="in_time"
                                id="in_time"
                                class="form-control @error('in_time') is-invalid @enderror"
                                value="{{ old('in_time', $driverAttendance->in_time ? substr($driverAttendance->in_time, 0, 5) : '') }}">

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

                            <label>
                                <b>Out Time</b>
                            </label>

                            <input
                                type="time"
                                name="out_time"
                                id="out_time"
                                class="form-control @error('out_time') is-invalid @enderror"
                                value="{{ old('out_time', $driverAttendance->out_time ? substr($driverAttendance->out_time, 0, 5) : '') }}">

                            @error('out_time')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Total Hours --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Total Hours</b>
                            </label>

                            <input
                                type="text"
                                name="total_hours"
                                id="total_hours"
                                class="form-control @error('total_hours') is-invalid @enderror"
                                value="{{ old('total_hours', $driverAttendance->total_hours) }}"
                                placeholder="Example: 08:30"
                                readonly>

                            @error('total_hours')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- ATTENDANCE SOURCE --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Attendance Source</b>
                        </h5>

                        <hr>

                    </div>


                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Source</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ ucwords(str_replace('_', ' ', $driverAttendance->source ?? 'manual')) }}"
                                readonly>

                            <input
                                type="hidden"
                                name="source"
                                value="{{ $driverAttendance->source ?? 'manual' }}">

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- REMARKS --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Remarks</b>
                        </h5>

                        <hr>

                    </div>


                    <div class="col-md-12">

                        <div class="form-group">

                            <label>
                                <b>Remarks</b>
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                maxlength="1000"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter remarks">{{ old('remarks', $driverAttendance->remarks) }}</textarea>

                            @error('remarks')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ========================================================= --}}

                    <div class="col-12">

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('driver-attendances.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>
                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>
                                Update Attendance

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


{{-- ========================================================= --}}
{{-- SCRIPTS --}}
{{-- ========================================================= --}}

@push('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | Calculate Total Hours
    |--------------------------------------------------------------------------
    | Supports overnight shifts.
    | Example: 10:00 PM to 06:00 AM = 08:00
    |--------------------------------------------------------------------------
    */

    function calculateTotalHours()
    {
        const inTime = $('#in_time').val();
        const outTime = $('#out_time').val();

        if (!inTime || !outTime) {
            $('#total_hours').val('');
            return;
        }

        const start = inTime.split(':');
        const end = outTime.split(':');

        const startMinutes =
            parseInt(start[0], 10) * 60 +
            parseInt(start[1], 10);

        let endMinutes =
            parseInt(end[0], 10) * 60 +
            parseInt(end[1], 10);

        if (endMinutes < startMinutes) {
            endMinutes += 24 * 60;
        }

        const totalMinutes = endMinutes - startMinutes;

        const hours = Math.floor(totalMinutes / 60);

        const minutes = totalMinutes % 60;

        $('#total_hours').val(
            String(hours).padStart(2, '0') +
            ':' +
            String(minutes).padStart(2, '0')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Time Change Events
    |--------------------------------------------------------------------------
    */

    $('#in_time, #out_time').on(
        'input change',
        calculateTotalHours
    );


    /*
    |--------------------------------------------------------------------------
    | Calculate Hours On Page Load
    |--------------------------------------------------------------------------
    */

    $(document).ready(function () {
        calculateTotalHours();
    });

</script>

@endpush
