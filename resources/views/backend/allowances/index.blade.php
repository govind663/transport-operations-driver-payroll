@extends('backend.layouts.master')

@section('title')
    Allowance Management
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
                        Allowance Management
                    </h4>

                    <p class="mb-0">
                        Manage all employee allowances and calculation types.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a href="{{ route('allowances.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Allowance

                    </a>

                </div>

            </div>

        </div>


        {{-- ================= Card ================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Allowances

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $allowances->count() }}

                    </span>

                </div>

            </div>


            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Allowance Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Allowance Code</th>

                            <th>Allowance Name</th>

                            <th>Description</th>

                            <th>Amount</th>

                            <th>Calculation Type</th>

                            <th>Status</th>

                            <th class="no-export">Edit</th>

                            <th class="no-export">Delete</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($allowances as $key => $allowance)

                        <tr>

                            {{-- Sr No --}}
                            <td>

                                {{ $key + 1 }}

                            </td>


                            {{-- Allowance Code --}}
                            <td>

                                <strong>

                                    {{ $allowance->allowance_code }}

                                </strong>

                            </td>


                            {{-- Allowance Name --}}
                            <td class="text-wrap text-justify">

                                <strong>

                                    {{ $allowance->name }}

                                </strong>

                            </td>


                            {{-- Description --}}
                            <td class="text-wrap text-justify">

                                {{ $allowance->description ?? '-' }}

                            </td>


                            {{-- Amount --}}
                            <td>

                                <strong>

                                    ₹ {{ number_format((float) $allowance->amount, 2) }}

                                </strong>

                            </td>


                            {{-- Calculation Type --}}
                            <td>

                                @switch($allowance->calculation_type)

                                    @case('fixed')

                                        <span class="badge badge-primary badge-pill px-3 py-2">

                                            <i class="fa fa-money"></i>

                                            Fixed Amount

                                        </span>

                                        @break

                                    @case('per_day')

                                        <span class="badge badge-info badge-pill px-3 py-2">

                                            <i class="fa fa-calendar"></i>

                                            Per Day

                                        </span>

                                        @break

                                    @case('per_km')

                                        <span class="badge badge-warning badge-pill px-3 py-2">

                                            <i class="fa fa-road"></i>

                                            Per KM

                                        </span>

                                        @break

                                    @case('per_hour')

                                        <span class="badge badge-secondary badge-pill px-3 py-2">

                                            <i class="fa fa-clock-o"></i>

                                            Per Hour

                                        </span>

                                        @break

                                    @default

                                        <span class="badge badge-dark badge-pill px-3 py-2">

                                            Unknown

                                        </span>

                                @endswitch

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($allowance->status)

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


                            {{-- Edit --}}
                            <td class="no-export">

                                <a
                                    href="{{ route('allowances.edit', $allowance->id) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="dw dw-pencil-1"></i>

                                    Edit

                                </a>

                            </td>


                            {{-- Delete --}}
                            <td class="no-export">

                                <form
                                    action="{{ route('allowances.destroy', $allowance->id) }}"
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

                            <td colspan="9" class="text-center">

                                No Allowances Found

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

                text: 'This allowance will be permanently deleted!',

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