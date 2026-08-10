@extends('backend.layouts.master')

@section('title')
    Vehicle Management
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>
    .vehicle-icon {
        width: 45px;
        height: 45px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        font-size: 18px;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .vehicle-number {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>

@endpush

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= Page Header ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">
                        Vehicle Management
                    </h4>

                    <p class="mb-0">
                        Manage all company vehicles and their details.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a href="{{ route('vehicle-management.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Vehicle

                    </a>

                </div>

            </div>

        </div>


        {{-- ================= Card ================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Vehicles

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $vehicles->count() }}

                    </span>

                </div>

            </div>


            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Vehicle Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Vehicle No.</th>

                            <th>Category</th>

                            <th>Vehicle Type</th>

                            <th>Registration No.</th>

                            <th>Manufacturer</th>

                            <th>Model</th>

                            <th>Year</th>

                            <th>Capacity</th>

                            <th>Status</th>

                            <th>Created At</th>

                            <th class="no-export">Edit</th>

                            <th class="no-export">Delete</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($vehicles as $key => $vehicle)

                        <tr>

                            {{-- Sr No --}}
                            <td>

                                {{ $key + 1 }}

                            </td>


                            {{-- Vehicle Number --}}
                            <td>

                                <strong class="vehicle-number">

                                    {{ $vehicle->vehicle_number }}

                                </strong>

                            </td>


                            {{-- Vehicle Category --}}
                            <td>

                                @if($vehicle->vehicleCategory)

                                    <span class="badge badge-info">

                                        {{ $vehicle->vehicleCategory->name }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Vehicle Type --}}
                            <td>

                                @if($vehicle->vehicleType)

                                    <strong>

                                        {{ $vehicle->vehicleType->name }}

                                    </strong>

                                    @if($vehicle->vehicleType->code)

                                        <br>

                                        <small class="text-muted">

                                            {{ $vehicle->vehicleType->code }}

                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Registration Number --}}
                            <td>

                                {{ $vehicle->registration_number ?? '-' }}

                            </td>


                            {{-- Manufacturer --}}
                            <td>

                                {{ $vehicle->manufacturer ?? '-' }}

                            </td>


                            {{-- Model --}}
                            <td>

                                {{ $vehicle->model ?? '-' }}

                            </td>


                            {{-- Manufacturing Year --}}
                            <td>

                                {{ $vehicle->manufacturing_year ?? '-' }}

                            </td>


                            {{-- Capacity --}}
                            <td>

                                @if($vehicle->capacity)

                                    {{ $vehicle->capacity }}

                                    @if($vehicle->capacity_unit)

                                        {{ $vehicle->capacity_unit }}

                                    @endif

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @switch($vehicle->status)

                                    @case('active')

                                        <span class="badge badge-success badge-pill px-3 py-2">

                                            <i class="fa fa-check-circle"></i>

                                            Active

                                        </span>

                                        @break


                                    @case('maintenance')

                                        <span class="badge badge-warning badge-pill px-3 py-2">

                                            <i class="fa fa-wrench"></i>

                                            Maintenance

                                        </span>

                                        @break


                                    @case('inactive')

                                        <span class="badge badge-danger badge-pill px-3 py-2">

                                            <i class="fa fa-times-circle"></i>

                                            Inactive

                                        </span>

                                        @break


                                    @default

                                        <span class="badge badge-secondary badge-pill px-3 py-2">

                                            <i class="fa fa-question-circle"></i>

                                            {{ ucfirst($vehicle->status ?? 'Unknown') }}

                                        </span>

                                @endswitch

                            </td>


                            {{-- Created Date --}}
                            <td>

                                {{ optional(
                                    $vehicle->created_at
                                )->format('d-m-Y') }}

                            </td>


                            {{-- Edit --}}
                            <td class="no-export">

                                <a
                                    href="{{ route(
                                        'vehicle-management.edit',
                                        $vehicle->id
                                    ) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="dw dw-pencil-1"></i>

                                    Edit

                                </a>

                            </td>


                            {{-- Delete --}}
                            <td class="no-export">

                                <form
                                    action="{{ route(
                                        'vehicle-management.destroy',
                                        $vehicle->id
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

                        <tr>

                            <td
                                colspan="13"
                                class="text-center">

                                No Vehicles Found

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <x-backend.footer />

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();

            Swal.fire({

                title: 'Are you sure?',

                text: 'This vehicle will be deleted!',

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