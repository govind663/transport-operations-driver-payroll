<ul id="accordion-menu">

    @php

        /*
        |--------------------------------------------------------------------------
        | Current User Role
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        $userRole = $user->role ?? null;

        $isAdmin = $userRole === 'admin';
        $isOperations = $userRole === 'operations';
        $isAccountant = $userRole === 'accountant';
        $isDriver = $userRole === 'driver';


        /*
        |--------------------------------------------------------------------------
        | MODULE PERMISSIONS
        |--------------------------------------------------------------------------
        */

        // Masters
        $canAccessMasters =
            $isAdmin ||
            $isOperations ||
            $isAccountant ||
            $isDriver;


        // Driver Management
        // Driver can access ONLY own driver profile.
        $canAccessDriverManagement =
            $isAdmin ||
            $isOperations ||
            $isDriver;


        // Operations
        $canAccessOperations =
            $isAdmin ||
            $isOperations ||
            $isAccountant ||
            $isDriver;


        // Payroll
        $canAccessPayroll =
            $isAdmin ||
            $isAccountant ||
            $isDriver;


        // Reports
        $canAccessReports =
            $isAdmin ||
            $isOperations ||
            $isAccountant;


        /*
        |--------------------------------------------------------------------------
        | MASTER ACTIVE ROUTES
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
        | OPERATIONS ACTIVE ROUTES
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
        | PAYROLL ACTIVE ROUTES
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
        | REPORTS ACTIVE ROUTES
        |--------------------------------------------------------------------------
        */

        $reportRoutes = [

            'reports.drivers.*',
            'reports.vehicles.*',
            'reports.duties.*',
            'reports.working-sheets.*',
            'reports.payroll.*',

        ];

        $isReportActive = collect($reportRoutes)
            ->contains(
                fn ($route) => request()->routeIs($route)
            );


        /*
        |--------------------------------------------------------------------------
        | SETTINGS ACTIVE
        |--------------------------------------------------------------------------
        */

        $isSettingsActive =
            (!$isDriver && request()->routeIs('admin.profile')) ||
            request()->routeIs('admin.change-password');

    @endphp


    {{-- ================================================================ --}}
    {{-- DASHBOARD --}}
    {{-- ================================================================ --}}

    <li>

        <a href="{{ route('admin.dashboard') }}"
           class="dropdown-toggle no-arrow
           {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

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


            <ul class="submenu
                {{ request()->routeIs('users.*') ? 'show' : '' }}">

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
                {{-- ADMIN / OPERATIONS / DRIVER --}}
                {{-- DRIVER = OWN RECORD ONLY --}}
                {{-- ================================================== --}}

                @if($canAccessDriverManagement)

                    <li>

                        <a href="{{ route('driver-management.index') }}"
                           class="{{ request()->routeIs('driver-management.*') ? 'active' : '' }}">

                            @if($isDriver)

                                My Profile

                            @else

                                Driver Management

                            @endif

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
                {{-- VEHICLE PRICE MANAGEMENT --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('vehicle-price.index') }}"
                        class="{{ request()->routeIs('vehicle-price.*') ? 'active' : '' }}">

                            Vehicle Price Management

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

                            @if($isDriver)

                                My Duties

                            @else

                                Duty Assignments

                            @endif

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- DUTY SLIPS --}}
                {{-- ALL ROLES --}}
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
                {{-- ALL ROLES --}}
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
                {{-- ALL ROLES --}}
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
    {{-- ADMIN / ACCOUNTANT / DRIVER --}}
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


                {{-- ================================================== --}}
                {{-- SALARY PROCESSING --}}
                {{-- ADMIN / ACCOUNTANT ONLY --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isAccountant)

                    <li>

                        <a href="{{ route('salary-processing.index') }}"
                           class="{{ request()->routeIs('salary-processing.*') ? 'active' : '' }}">

                            Salary Processing

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- SALARY SLIPS --}}
                {{-- ADMIN / ACCOUNTANT / DRIVER --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isAccountant || $isDriver)

                    <li>

                        <a href="{{ route('salary-slips.index') }}"
                           class="{{ request()->routeIs('salary-slips.*') ? 'active' : '' }}">

                            @if($isDriver)

                                My Salary Slips

                            @else

                                Salary Slips

                            @endif

                        </a>

                    </li>

                @endif

            </ul>

        </li>

    @endif



    {{-- ================================================================ --}}
    {{-- REPORTS --}}
    {{-- ADMIN / OPERATIONS / ACCOUNTANT --}}
    {{-- DRIVER NOT ALLOWED --}}
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


                {{-- ================================================== --}}
                {{-- DRIVER REPORTS --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('reports.drivers.index') }}"
                           class="{{ request()->routeIs('reports.drivers.*') ? 'active' : '' }}">

                            Driver Reports

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- VEHICLE REPORTS --}}
                {{-- ADMIN / OPERATIONS --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations)

                    <li>

                        <a href="{{ route('reports.vehicles.index') }}"
                           class="{{ request()->routeIs('reports.vehicles.*') ? 'active' : '' }}">

                            Vehicle Reports

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- DUTY REPORTS --}}
                {{-- ADMIN / OPERATIONS / ACCOUNTANT --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations || $isAccountant)

                    <li>

                        <a href="{{ route('reports.duties.index') }}"
                           class="{{ request()->routeIs('reports.duties.*') ? 'active' : '' }}">

                            Duty Reports

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- WORKING SHEET REPORTS --}}
                {{-- ADMIN / OPERATIONS / ACCOUNTANT --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isOperations || $isAccountant)

                    <li>

                        <a href="{{ route('reports.working-sheets.index') }}"
                           class="{{ request()->routeIs('reports.working-sheets.*') ? 'active' : '' }}">

                            Working Sheet Reports

                        </a>

                    </li>

                @endif



                {{-- ================================================== --}}
                {{-- PAYROLL REPORTS --}}
                {{-- ADMIN / ACCOUNTANT --}}
                {{-- ================================================== --}}

                @if($isAdmin || $isAccountant)

                    <li>

                        <a href="{{ route('reports.payroll.index') }}"
                           class="{{ request()->routeIs('reports.payroll.*') ? 'active' : '' }}">

                            Payroll Reports

                        </a>

                    </li>

                @endif

            </ul>

        </li>

    @endif



    {{-- ================================================================ --}}
    {{-- SETTINGS --}}
    {{-- ================================================================ --}}

    <li class="dropdown">

        <a href="javascript:;" class="dropdown-toggle">

            <span class="micon bi bi-gear"></span>

            <span class="mtext">
                Settings
            </span>

        </a>


        <ul class="submenu {{ $isSettingsActive ? 'show' : '' }}">


            {{-- ================================================== --}}
            {{-- USER PROFILE --}}
            {{-- ADMIN / OPERATIONS / ACCOUNTANT --}}
            {{-- DRIVER NOT ALLOWED --}}
            {{-- ================================================== --}}

            @if(!$isDriver)

                <li>

                    <a href="{{ route('admin.profile') }}"
                       class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">

                        Profile

                    </a>

                </li>

            @endif



            {{-- ================================================== --}}
            {{-- CHANGE PASSWORD --}}
            {{-- ALL ROLES --}}
            {{-- ================================================== --}}

            <li>

                <a href="{{ route('admin.change-password') }}"
                   class="{{ request()->routeIs('admin.change-password') ? 'active' : '' }}">

                    Change Password

                </a>

            </li>

        </ul>

    </li>

</ul>