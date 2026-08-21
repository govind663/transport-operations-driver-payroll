<ul id="accordion-menu">

    @php

        /*
        |--------------------------------------------------------------------------
        | Current User Role
        |--------------------------------------------------------------------------
        */
        $userRole = auth()->user()->role ?? null;

        $isAdmin = $userRole === 'admin';
        $isOperations = $userRole === 'operations';
        $isAccountant = $userRole === 'accountant';
        $isDriver = $userRole === 'driver';


        /*
        |--------------------------------------------------------------------------
        | Module Access
        |--------------------------------------------------------------------------
        */
        $canAccessMasters =
            $isAdmin ||
            $isOperations ||
            $isAccountant;


        $canAccessOperations =
            $isAdmin ||
            $isOperations ||
            $isAccountant ||
            $isDriver;


        $canAccessPayroll =
            $isAdmin ||
            $isAccountant;


        $canAccessReports =
            $isAdmin ||
            $isOperations ||
            $isAccountant;


        /*
        |--------------------------------------------------------------------------
        | Master Active Routes
        |--------------------------------------------------------------------------
        */
        $masterRoutes = [

            'client-management.*',
            'driver-management.*',
            'vehicle-categories.*',
            'vehicle-types.*',
            'vehicle-management.*',
            'allowances.*',
            'expenses.*',

        ];


        $isMasterActive = collect($masterRoutes)
            ->contains(
                fn ($route) => request()->routeIs($route)
            );


        /*
        |--------------------------------------------------------------------------
        | Operations Active Routes
        |--------------------------------------------------------------------------
        */
        $operationRoutes = [

            'travel-requests.*',
            'duty-assignments.*',
            'duty-slips.*',
            'working-sheets.*',
            'driver-attendances.*',

        ];


        $isOperationActive = collect($operationRoutes)
            ->contains(
                fn ($route) => request()->routeIs($route)
            );


        /*
        |--------------------------------------------------------------------------
        | Payroll Active Routes
        |--------------------------------------------------------------------------
        */
        $payrollRoutes = [

            'salary-processing.*',
            'salary-slips.*',

        ];


        $isPayrollActive = collect($payrollRoutes)
            ->contains(
                fn ($route) => request()->routeIs($route)
            );


        /*
        |--------------------------------------------------------------------------
        | Reports Active Routes
        |--------------------------------------------------------------------------
        */
        $reportRoutes = [

            'driver-reports.*',
            'vehicle-reports.*',
            'duty-reports.*',
            'working-sheet-reports.*',
            'payroll-reports.*',

        ];


        $isReportActive = collect($reportRoutes)
            ->contains(
                fn ($route) => request()->routeIs($route)
            );

    @endphp

    {{-- ================================================================ --}}
    {{-- DASHBOARD --}}
    {{-- ================================================================ --}}
    <li>
        <a href="{{ route('admin.dashboard') }}"
            class="dropdown-toggle no-arrow {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <span class="micon bi bi-house-door"></span>

            <span class="mtext">
                Dashboard
            </span>

        </a>
    </li>

    {{-- ================================================================ --}}
    {{-- USER MANAGEMENT --}}
    {{-- ADMIN ONLY --}}
    {{-- ================================================================ --}}
    @if($isAdmin)
        <li class="dropdown">

            <a href="javascript:;" class="dropdown-toggle">

                <span class="micon bi bi-people"></span>

                <span class="mtext">
                    User Management
                </span>

            </a>


            <ul class="submenu {{ request()->routeIs('users.*') ? 'show' : '' }}">

                <li>

                    <a href="{{ route('users.index') }}"
                        class="{{ request()->routeIs('users.*') ? 'active' : '' }}">

                        Users

                    </a>

                </li>

            </ul>

        </li>
    @endif

    {{-- ================================================================ --}}
    {{-- MASTERS --}}
    {{-- ================================================================ --}}
    @if($canAccessMasters)
        <li class="dropdown">

            <a href="javascript:;" class="dropdown-toggle">

                <span class="micon bi bi-collection"></span>

                <span class="mtext">
                    Masters
                </span>

            </a>


            <ul class="submenu {{ $isMasterActive ? 'show' : '' }}">


                {{-- ================================================== --}}
                {{-- CLIENT MANAGEMENT --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('client-management.index') }}"
                            class="{{ request()->routeIs('client-management.*') ? 'active' : '' }}">

                            Client Management

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- DRIVER MANAGEMENT --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('driver-management.index') }}"
                            class="{{ request()->routeIs('driver-management.*') ? 'active' : '' }}">

                            Driver Management

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- VEHICLE CATEGORIES --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('vehicle-categories.index') }}"
                            class="{{ request()->routeIs('vehicle-categories.*') ? 'active' : '' }}">

                            Vehicle Categories

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- VEHICLE TYPES --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('vehicle-types.index') }}"
                            class="{{ request()->routeIs('vehicle-types.*') ? 'active' : '' }}">

                            Vehicle Types

                        </a>

                    </li>

                @endif

                {{-- ================================================== --}}
                {{-- VEHICLE MANAGEMENT --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('vehicle-management.index') }}"
                            class="{{ request()->routeIs('vehicle-management.*') ? 'active' : '' }}">

                            Vehicle Management

                        </a>

                    </li>

                @endif

                {{-- ================================================== --}}
                {{-- ALLOWANCES --}}
                {{-- ADMIN / ACCOUNTANT --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isAccountant)
                    <li>
                        <a href="{{ route('allowances.index') }}"
                            class="{{ request()->routeIs('allowances.*') ? 'active' : '' }}">

                            Allowances Management
                        </a>
                    </li>
                @endif

                {{-- ================================================== --}}
                {{-- EXPENSES --}}
                {{-- ADMIN / ACCOUNTANT --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isAccountant)
                    <li>

                        <a href="{{ route('expenses.index') }}"
                            class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">

                            Expenses Management

                        </a>

                    </li>
                @endif
            </ul>

        </li>
    @endif

    {{-- ================================================================ --}}
    {{-- OPERATIONS --}}
    {{-- ================================================================ --}}
    @if($canAccessOperations)
        <li class="dropdown">

            <a href="javascript:;" class="dropdown-toggle">

                <span class="micon bi bi-diagram-3"></span>

                <span class="mtext">
                    Operations
                </span>

            </a>


            <ul class="submenu {{ $isOperationActive ? 'show' : '' }}">



                {{-- ================================================== --}}
                {{-- TRAVEL REQUESTS --}}
                {{-- ADMIN / OPERATIONS / ACCOUNTANT --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations || $isAccountant)

                    <li>

                        <a href="{{ route('travel-requests.index') }}"
                            class="{{ request()->routeIs('travel-requests.*') ? 'active' : '' }}">

                            Travel Requests

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- DUTY ASSIGNMENTS --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('duty-assignments.index') }}"
                            class="{{ request()->routeIs('duty-assignments.*') ? 'active' : '' }}">

                            Duty Assignments

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- DUTY SLIPS --}}
                {{-- ADMIN / OPERATIONS / ACCOUNTANT / DRIVER --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isOperations || $isAccountant || $isDriver)

                    <li>

                        <a href="{{ route('duty-slips.index') }}"
                            class="{{ request()->routeIs('duty-slips.*') ? 'active' : '' }}">

                            @if($isDriver)

                                My Duty Slips

                            @else

                                Duty Slips

                            @endif

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- WORKING SHEETS --}}
                {{-- ADMIN / OPERATIONS / ACCOUNTANT / DRIVER --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isOperations || $isAccountant || $isDriver)

                    <li>

                        <a href="{{ route('working-sheets.index') }}"
                            class="{{ request()->routeIs('working-sheets.*') ? 'active' : '' }}">

                            @if($isDriver)

                                My Working Sheets

                            @else

                                Working Sheets

                            @endif

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- DRIVER ATTENDANCE --}}
                {{-- ADMIN / OPERATIONS / ACCOUNTANT / DRIVER --}}
                {{-- ================================================== --}}
                @if($isAdmin || $isOperations || $isAccountant || $isDriver)

                    <li>

                        <a href="{{ route('driver-attendances.index') }}"
                            class="{{ request()->routeIs('driver-attendances.*') ? 'active' : '' }}">

                            @if($isDriver)

                                My Attendance

                            @else

                                Driver Attendance

                            @endif

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- MY DUTIES --}}
                {{-- DRIVER ONLY --}}
                {{-- ================================================== --}}
                @if($isDriver)

                    <li>

                        <a href="{{ route('duty-assignments.index') }}"
                            class="{{ request()->routeIs('duty-assignments.*') ? 'active' : '' }}">

                            My Duties

                        </a>

                    </li>

                @endif


            </ul>

        </li>
    @endif

    {{-- ================================================================ --}}
    {{-- PAYROLL --}}
    {{-- ADMIN / ACCOUNTANT ONLY --}}
    {{-- ================================================================ --}}
    @if($canAccessPayroll)
        <li class="dropdown">

            <a href="javascript:;" class="dropdown-toggle">

                <span class="micon bi bi-cash-stack"></span>

                <span class="mtext">
                    Payroll
                </span>

            </a>


            <ul class="submenu {{ $isPayrollActive ? 'show' : '' }}">

                {{-- Salary Processing --}}
                <li>

                    <a href="{{ route('salary-processing.index') }}"
                        class="{{ request()->routeIs('salary-processing.*') ? 'active' : '' }}">

                        Salary Processing

                    </a>

                </li>

                {{-- Salary Slips --}}
                <li>

                    <a href="{{ route('salary-slips.index') }}"
                        class="{{ request()->routeIs('salary-slips.*') ? 'active' : '' }}">

                        Salary Slips

                    </a>

                </li>

            </ul>

        </li>
    @endif

    {{-- ================================================================ --}}
    {{-- REPORTS --}}
    {{-- ================================================================ --}}
    @if($canAccessReports)
        <li class="dropdown">

            <a href="javascript:;" class="dropdown-toggle">

                <span class="micon bi bi-bar-chart"></span>

                <span class="mtext">
                    Reports
                </span>

            </a>


            <ul class="submenu {{ $isReportActive ? 'show' : '' }}">


                {{-- Driver Reports --}}

                @if($isAdmin || $isOperations)

                    <li>

                        <a href="javascript:void(0);">

                            Driver Reports

                        </a>

                    </li>

                @endif



                {{-- Vehicle Reports --}}

                @if($isAdmin || $isOperations)

                    <li>

                        <a href="javascript:void(0);">

                            Vehicle Reports

                        </a>

                    </li>

                @endif



                {{-- Duty Reports --}}

                @if($isAdmin || $isOperations || $isAccountant)

                    <li>

                        <a href="javascript:void(0);">

                            Duty Reports

                        </a>

                    </li>

                @endif



                {{-- Working Sheet Reports --}}

                @if($isAdmin || $isOperations || $isAccountant)

                    <li>

                        <a href="javascript:void(0);">

                            Working Sheet Reports

                        </a>

                    </li>

                @endif



                {{-- Payroll Reports --}}

                @if($isAdmin || $isAccountant)

                    <li>

                        <a href="javascript:void(0);">

                            Payroll Reports

                        </a>

                    </li>

                @endif


            </ul>

        </li>
    @endif

    {{-- ================================================================ --}}
    {{-- SETTINGS --}}
    {{-- ALL LOGGED-IN USERS --}}
    {{-- ================================================================ --}}
    <li class="dropdown">

        <a href="javascript:;" class="dropdown-toggle">

            <span class="micon bi bi-gear"></span>

            <span class="mtext">
                Settings
            </span>

        </a>


        <ul class="submenu
            {{
                request()->routeIs('admin.change-password') ||
                request()->routeIs('admin.profile')
                    ? 'show'
                    : ''
            }}">


            {{-- Profile --}}

            <li>

                <a href="{{ route('admin.profile') }}"
                    class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">

                    Profile

                </a>

            </li>



            {{-- Change Password --}}

            <li>

                <a href="{{ route('admin.change-password') }}"
                    class="{{ request()->routeIs('admin.change-password') ? 'active' : '' }}">

                    Change Password

                </a>

            </li>


        </ul>

    </li>

</ul>