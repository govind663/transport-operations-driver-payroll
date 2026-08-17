@extends('backend.layouts.master')

@section('title')
    Client Management
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>
    .client-logo{
        width:150px;
        height:60ch;
        object-fit:cover;
        /* border-radius:50%; */
        border:2px solid #0e0b9d;
    }

    .table td,
    .table th{
        vertical-align:middle;
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
                        Client Management
                    </h4>

                    <p class="mb-0">
                        Manage all transport clients & companies.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a href="{{ route('client-management.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New Client

                    </a>

                </div>

            </div>

        </div>

        {{-- ================= Card ================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Clients

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $clients->count() }}

                    </span>

                </div>

            </div>

            <div class="pb-20">

                <table
                    class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="Client Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Logo</th>

                            <th>Client Code</th>

                            <th>Company Name</th>

                            <th>Category</th>

                            <th>Contact Person</th>

                            <th>Mobile</th>

                            <th>Email</th>

                            <th>GST No.</th>

                            <th>City</th>

                            <th>Status</th>

                            <th class="no-export">Edit</th>

                            <th class="no-export">Delete</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($clients as $key => $client)

                        <tr>

                            {{-- Sr No --}}
                            <td>{{ $key + 1 }}</td>

                            {{-- Company Logo --}}
                            <td class="text-wrap text-justify">

                                <img
                                    src="{{ $client->company_logo_url }}"
                                    alt="{{ $client->company_name }}"
                                    class="img-fluid client-logo"
                                    loading="lazy"
                                    decoding="async"
                                    data-no-optimize="1"
                                    style="
                                        width:150px;
                                        height:70px;
                                        object-fit:cover;
                                        border-radius:8px;
                                        border:1px solid #dee2e6;
                                        background:#f8f9fa;
                                    "
                                    onerror="this.onerror=null; this.src='{{ asset('backend/assets/img/logo/company.png') }}';">

                            </td>

                            {{-- Client Code --}}
                            <td>

                                <strong>

                                    {{ $client->client_code }}

                                </strong>

                            </td>

                            {{-- Company Name --}}
                            <td class="text-wrap text-justify">

                                <strong>

                                    {{ $client->company_name }}

                                </strong>

                                @if($client->website)

                                    <br>

                                    <small>

                                        <a href="{{ Str::startsWith($client->website,['http://','https://']) ? $client->website : 'https://'.$client->website }}"
                                            target="_blank"
                                            rel="noopener noreferrer">

                                            <b class="text-primary">
                                                <i class="fa fa-link"></i>
                                                {{ $client->website }}
                                            </b>

                                        </a>

                                    </small>

                                @endif

                            </td>

                            {{-- Category --}}
                            <td>

                                {{ $client->category ?? '-' }}

                            </td>

                            {{-- Contact Person --}}
                            <td>

                                {{ $client->contact_person ?? '-' }}

                            </td>

                            {{-- Mobile --}}
                            <td>

                                {{ $client->mobile }}

                                @if($client->alternate_mobile)

                                    <br>

                                    <small class="text-muted">

                                        {{ $client->alternate_mobile }}

                                    </small>

                                @endif

                            </td>

                            {{-- Email --}}
                            <td>

                                {{ $client->email ?? '-' }}

                            </td>

                            {{-- GST Number --}}
                            <td>

                                {{ $client->gst_number ?? '-' }}

                            </td>

                            {{-- City --}}
                            <td>

                                {{ $client->city ?? '-' }}

                            </td>

                            {{-- Status --}}
                            <td>

                                @if($client->status)

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
                                    href="{{ route('client-management.edit', $client->id) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="dw dw-pencil-1"></i>

                                    Edit

                                </a>

                            </td>

                            {{-- Delete --}}
                            <td class="no-export">

                                <form
                                    action="{{ route('client-management.destroy', $client->id) }}"
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

                            <td colspan="13" class="text-center">

                                No Clients Found

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

                text: 'This client will be permanently deleted!',

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