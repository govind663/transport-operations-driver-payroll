@extends('backend.layouts.master')

@section('title')
Home
@endsection

@push('styles')
@endpush

@section('content')

<div class="xs-pd-20-10 pd-ltr-20">

    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">

                <div class="title">
                    <h4>Dashboard</h4>
                </div>

                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Home
                            </a>
                        </li>

                        <li class="breadcrumb-item active"
                            aria-current="page">
                            Dashboard
                        </li>

                    </ol>
                </nav>

            </div>
        </div>
    </div>

    {{-- Role Wise Dashboard Cards --}}
    @include('backend.partials.role-overview')

    <!-- Footer Start -->
    <x-backend.footer />
    <!-- Footer End -->

</div>

@endsection

@push('scripts')
@endpush