@extends('backend.layouts.master')

@section('title')
    Vehicle Category Management
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>
    .table td,
    .table th {
        vertical-align: middle;
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
                        Vehicle Category Management
                    </h4>

                    <p class="mb-0">
                        Manage all vehicle categories.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a href="{{ route('vehicle-categories.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Vehicle Category

                    </a>

                </div>

            </div>

        </div>

        {{-- ================= Card ================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Vehicle Categories

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $vehicleCategories->count() }}

                    </span>

                </div>

            </div>

            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Vehicle Category Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Category Name</th>

                            <th>Code</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Created By</th>

                            <th>Created At</th>

                            <th class="no-export">Edit</th>

                            <th class="no-export">Delete</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($vehicleCategories as $key => $vehicleCategory)

                        <tr>

                            {{-- Sr No --}}
                            <td>
                                {{ $key + 1 }}
                            </td>


                            {{-- Category Name --}}
                            <td>

                                <strong>
                                    {{ $vehicleCategory->name }}
                                </strong>

                            </td>


                            {{-- Category Code --}}
                            <td>

                                <span class="badge badge-info">

                                    {{ $vehicleCategory->code }}

                                </span>

                            </td>


                            {{-- Description --}}
                            <td>

                                @if($vehicleCategory->description)

                                    {{ Str::limit(
                                        $vehicleCategory->description,
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

                                @if($vehicleCategory->status)

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

                                @if($vehicleCategory->createdBy)

                                    {{ $vehicleCategory->createdBy->name }}

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Created Date --}}
                            <td>

                                {{ optional(
                                    $vehicleCategory->created_at
                                )->format('d-m-Y') }}

                            </td>


                            {{-- Edit --}}
                            <td class="no-export">

                                <a
                                    href="{{ route(
                                        'vehicle-categories.edit',
                                        $vehicleCategory->id
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
                                        'vehicle-categories.destroy',
                                        $vehicleCategory->id
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
                                colspan="9"
                                class="text-center">

                                No Vehicle Categories Found

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

                text: 'This vehicle category will be deleted!',

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