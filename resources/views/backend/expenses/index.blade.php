@extends('backend.layouts.master')

@section('title')
    Expense Management
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
                        Expense Management
                    </h4>

                    <p class="mb-0">
                        Manage all company expenses and expense categories.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a href="{{ route('expenses.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Expense

                    </a>

                </div>

            </div>

        </div>


        {{-- ================= Card ================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Expenses

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $expenses->count() }}

                    </span>

                </div>

            </div>


            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Expense Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Expense Code</th>

                            <th>Expense Name</th>

                            <th>Description</th>

                            <th>Expense Type</th>

                            <th>Amount</th>

                            <th>Status</th>

                            <th class="no-export">Edit</th>

                            <th class="no-export">Delete</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($expenses as $key => $expense)

                        <tr>

                            {{-- Sr No --}}
                            <td>

                                {{ $key + 1 }}

                            </td>


                            {{-- Expense Code --}}
                            <td>

                                <strong>

                                    {{ $expense->expense_code }}

                                </strong>

                            </td>


                            {{-- Expense Name --}}
                            <td class="text-wrap text-justify">

                                <strong>

                                    {{ $expense->name }}

                                </strong>

                            </td>


                            {{-- Description --}}
                            <td class="text-wrap text-justify">

                                {{ $expense->description ?? '-' }}

                            </td>


                            {{-- Expense Type --}}
                            <td>

                                @switch($expense->expense_type)

                                    @case('fuel')

                                        <span class="badge badge-primary badge-pill px-3 py-2">

                                            <i class="fa fa-tint"></i>

                                            Fuel

                                        </span>

                                        @break

                                    @case('toll')

                                        <span class="badge badge-info badge-pill px-3 py-2">

                                            <i class="fa fa-road"></i>

                                            Toll

                                        </span>

                                        @break

                                    @case('parking')

                                        <span class="badge badge-warning badge-pill px-3 py-2">

                                            <i class="fa fa-car"></i>

                                            Parking

                                        </span>

                                        @break

                                    @case('food')

                                        <span class="badge badge-success badge-pill px-3 py-2">

                                            <i class="fa fa-cutlery"></i>

                                            Food

                                        </span>

                                        @break

                                    @case('maintenance')

                                        <span class="badge badge-secondary badge-pill px-3 py-2">

                                            <i class="fa fa-wrench"></i>

                                            Maintenance

                                        </span>

                                        @break

                                    @case('repair')

                                        <span class="badge badge-danger badge-pill px-3 py-2">

                                            <i class="fa fa-cog"></i>

                                            Repair

                                        </span>

                                        @break

                                    @case('miscellaneous')

                                        <span class="badge badge-dark badge-pill px-3 py-2">

                                            <i class="fa fa-list"></i>

                                            Miscellaneous

                                        </span>

                                        @break

                                    @default

                                        <span class="badge badge-dark badge-pill px-3 py-2">

                                            Unknown

                                        </span>

                                @endswitch

                            </td>


                            {{-- Amount --}}
                            <td>

                                <strong>

                                    ₹ {{ number_format((float) $expense->amount, 2) }}

                                </strong>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($expense->status)

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
                                    href="{{ route('expenses.edit', $expense->id) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="dw dw-pencil-1"></i>

                                    Edit

                                </a>

                            </td>


                            {{-- Delete --}}
                            <td class="no-export">

                                <form
                                    action="{{ route('expenses.destroy', $expense->id) }}"
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

                                No Expenses Found

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

                text: 'This expense will be permanently deleted!',

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