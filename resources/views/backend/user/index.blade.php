@extends('backend.layouts.master')

@section('title')
    User Management
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>
    .user-profile {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #dee2e6;
    }

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
                        User Management
                    </h4>

                    <p class="mb-0">
                        Manage all system users.
                    </p>

                </div>

                <div class="col-md-6 col-sm-12 text-right">

                    <a href="{{ route('users.create') }}"
                        class="btn btn-primary">

                        <i class="fa fa-plus"></i>

                        Add New User

                    </a>

                </div>

            </div>

        </div>

        {{-- ================= Card ================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Users

                    </h4>

                    <span class="badge badge-primary">

                        Total :
                        {{ $users->count() }}

                    </span>

                </div>

            </div>

            <div class="pb-20">

                <table class="table hover multiple-select-row data-table-export1 nowrap p-3"
                    data-title="User Management">

                    <thead>

                        <tr>

                            <th>Sr. No.</th>

                            <th>Profile</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Created At</th>

                            <th class="no-export">Edit</th>

                            <th class="no-export">Delete</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($users as $key => $user)

                            <tr>

                                {{-- Sr --}}
                                <td>
                                    {{ $key + 1 }}
                                </td>

                                {{-- Profile --}}
                                <td>
                                    @php
                                        $profileImage = $user->profile_image;

                                        if ($profileImage) {
                                            if (str_starts_with($profileImage, 'users/')) {
                                                $profileUrl = asset('storage/' . $profileImage);
                                            } else {
                                                $profileUrl = asset('backend/assets/uploads/profile/' . $profileImage);
                                            }
                                        } else {
                                            $profileUrl = asset('backend/assets/img/logo/favicon.ico');
                                        }
                                    @endphp

                                    <img
                                        src="{{ $profileUrl }}"
                                        alt="{{ $user->name }}"
                                        class="user-profile"
                                        loading="lazy"
                                        decoding="async"
                                        data-no-optimize="1"
                                        onerror="this.onerror=null; this.src='{{ asset('backend/assets/img/logo/favicon.ico') }}';">
                                </td>

                                {{-- Name --}}
                                <td>

                                    <strong>

                                        {{ $user->name }}

                                    </strong>

                                </td>

                                {{-- Email --}}
                                <td>

                                    {{ $user->email }}

                                </td>

                                {{-- Phone --}}
                                <td>

                                    {{ $user->phone ?? '-' }}

                                </td>

                                {{-- Role --}}
                                <td>

                                    @php

                                        $roleBadge = match ($user->role) {

                                            'admin' => [
                                                'class' => 'badge-danger',
                                                'icon'  => 'bi bi-shield-lock-fill'
                                            ],

                                            'operations' => [
                                                'class' => 'badge-primary',
                                                'icon'  => 'bi bi-gear-fill'
                                            ],

                                            'accountant' => [
                                                'class' => 'badge-warning',
                                                'icon'  => 'bi bi-calculator-fill'
                                            ],

                                            'driver' => [
                                                'class' => 'badge-success',
                                                'icon'  => 'bi bi-truck'
                                            ],

                                            default => [
                                                'class' => 'badge-secondary',
                                                'icon'  => 'bi bi-person-fill'
                                            ],

                                        };

                                    @endphp

                                    <span class="badge {{ $roleBadge['class'] }} badge-pill px-3 py-2">

                                        <i class="{{ $roleBadge['icon'] }}"></i>

                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}

                                    </span>

                                </td>

                                {{-- Status --}}
                                <td>

                                    @if($user->status)

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

                                {{-- Created --}}
                                <td>

                                    {{ $user->created_at?->format('d-m-Y') }}

                                </td>

                                {{-- Edit --}}
                                <td class="no-export">

                                    <a href="{{ route('users.edit',$user->id) }}"
                                        class="btn btn-warning btn-sm">

                                        <i class="dw dw-pencil-1"></i>

                                        Edit

                                    </a>

                                </td>

                                {{-- Delete --}}
                                <td class="no-export">

                                    <form
                                        action="{{ route('users.destroy', $user->id) }}"
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

                                <td colspan="11" class="text-center">

                                    No Users Found

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
                text: 'You will not be able to recover this record!',
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