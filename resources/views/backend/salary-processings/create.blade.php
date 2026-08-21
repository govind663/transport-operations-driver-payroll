@extends('backend.layouts.master')

@section('title')
Create Salary Processing
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>
                            Salary Processing
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

                                <a href="{{ route('salary-processing.index') }}">
                                    Salary Processing
                                </a>

                            </li>

                            <li class="breadcrumb-item active">
                                Create Salary Processing
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('salary-processing.store') }}"
            method="POST">

            @csrf

            <div class="card-box pd-20 mb-30">

                {{-- ================= BASIC INFORMATION ================= --}}
                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>Salary Processing Information</b>

                    </h5>

                    <hr>

                </div>


                <div class="row">

                    {{-- Processing Month --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Salary Month
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="month"
                                name="salary_month"
                                id="salary_month"
                                class="form-control @error('salary_month') is-invalid @enderror"
                                value="{{ old('salary_month', now()->format('Y-m')) }}">

                            @error('salary_month')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Period From --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Period From
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="date"
                                name="period_from"
                                id="period_from"
                                class="form-control @error('period_from') is-invalid @enderror"
                                value="{{ old('period_from', now()->startOfMonth()->format('Y-m-d')) }}">

                            @error('period_from')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Period To --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Period To
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="date"
                                name="period_to"
                                id="period_to"
                                class="form-control @error('period_to') is-invalid @enderror"
                                value="{{ old('period_to', now()->endOfMonth()->format('Y-m-d')) }}">

                            @error('period_to')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ================= PROCESSING DETAILS ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Processing Details</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Driver --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Driver</b>
                            </label>

                            <select
                                name="driver_id"
                                id="driver_id"
                                class="form-control custom-select2 @error('driver_id') is-invalid @enderror">

                                <option value="">
                                    All Drivers
                                </option>

                                @foreach($drivers ?? [] as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ old('driver_id') == $driver->id ? 'selected' : '' }}>

                                        {{ $driver->name }}

                                        @if(!empty($driver->driver_code))
                                            ({{ $driver->driver_code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('driver_id')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Working Days --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Total Working Days
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="number"
                                name="total_working_days"
                                id="total_working_days"
                                class="form-control @error('total_working_days') is-invalid @enderror"
                                value="{{ old('total_working_days') }}"
                                min="0"
                                step="0.01"
                                placeholder="Example: 26">

                            @error('total_working_days')

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

                                <option value="">
                                    Select Status
                                </option>

                                @foreach($statuses ?? [] as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ old('status') == $status ? 'selected' : '' }}>

                                        {{ ucfirst(str_replace('_', ' ', $status)) }}

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


                    {{-- ================= REMARKS ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

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
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter remarks">{{ old('remarks') }}</textarea>

                            @error('remarks')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ================= ACTION BUTTONS ================= --}}

                    <div class="col-12">

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('salary-processing.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-calculator"></i>

                                Start Salary Processing

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