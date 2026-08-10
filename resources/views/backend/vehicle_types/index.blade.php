@extends('backend.layouts.master')

@section('title')
    Vehicle Type Management
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

        {{-- ================= Page Header ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">
                        Vehicle Type Management
                    </h4>

                    <p class="mb-0">
                        Manage vehicle types and their categories.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a href="{{ route('vehicle-types.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Vehicle Type

                    </a>

                </div>

            </div>

        </div>


        {{-- ================= Card ================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Vehicle Types

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $vehicleTypes->count() }}

                    </span>

                </div>

            </div>


            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Vehicle Type Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Type Name</th>

                            <th>Code</th>

                            <th>Vehicle Category</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Created By</th>

                            <th>Created At</th>

                            <th class="no-export">Edit</th>

                            <th class="no-export">Delete</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($vehicleTypes as $key => $vehicleType)

                        <tr>

                            {{-- Sr No --}}
                            <td>

                                {{ $key + 1 }}

                            </td>


                            {{-- Type Name --}}
                            <td>

                                <strong>

                                    {{ $vehicleType->name }}

                                </strong>

                            </td>


                            {{-- Type Code --}}
                            <td>

                                <span class="badge badge-info vehicle-type-code">

                                    {{ $vehicleType->code }}

                                </span>

                            </td>


                            {{-- Vehicle Category --}}
                            <td>

                                @if($vehicleType->vehicleCategory)

                                    <strong>

                                        {{ $vehicleType->vehicleCategory->name }}

                                    </strong>

                                    @if($vehicleType->vehicleCategory->code)

                                        <br>

                                        <small class="text-muted">

                                            {{ $vehicleType->vehicleCategory->code }}

                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Description --}}
                            <td>

                                @if($vehicleType->description)

                                    {{ Str::limit(
                                        $vehicleType->description,
                                        80
                                    ) }}

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($vehicleType->status)

                                    <span class="badge badge-success badge-pill px-3 py-2">

                                        <i class="fa fa-check-circle"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge badge-danger badge-pill px-3 py-2">

                                        <i class="fa fa-times-circle"></i>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Created By --}}
                            <td>

                                @if($vehicleType->createdBy)

                                    {{ $vehicleType->createdBy->name }}

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Created Date --}}
                            <td>

                                {{ optional(
                                    $vehicleType->created_at
                                )->format('d-m-Y') }}

                            </td>


                            {{-- Edit --}}
                            <td class="no-export">

                                <a
                                    href="{{ route(
                                        'vehicle-types.edit',
                                        $vehicleType->id
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
                                        'vehicle-types.destroy',
                                        $vehicleType->id
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
                                colspan="10"
                                class="text-center">

                                No Vehicle Types Found

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

                text: 'This vehicle type will be deleted!',

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