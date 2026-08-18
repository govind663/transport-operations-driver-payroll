<style>
    /* =========================================================
       PREMIUM TRANSPORT OPERATIONS DASHBOARD
    ========================================================= */

    .dashboard-header {
        margin-bottom: 5px;
    }

    .dashboard-header h2 {
        color: #172033;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .dashboard-header p {
        font-size: 13px;
        color: #94a3b8;
    }


    /* =========================================================
       PREMIUM STAT CARD
    ========================================================= */

    .premium-stat-card {
        position: relative;
        overflow: hidden;

        min-height: 185px;

        border: 1px solid #e9edf5;
        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 6px 24px rgba(15, 23, 42, 0.06);

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease,
            border-color 0.3s ease;
    }


    .premium-stat-card:hover {
        transform: translateY(-5px);

        border-color: #dfe5ef;

        box-shadow:
            0 16px 40px rgba(15, 23, 42, 0.12);
    }


    /* Left Accent */

    .premium-stat-card::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 5px;
        height: 100%;

        background: var(--card-accent);

        border-radius: 16px 0 0 16px;
    }


    /* Decorative Circle */

    .premium-stat-card::after {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        right: -65px;
        top: -65px;

        border-radius: 50%;

        background: var(--card-soft);

        opacity: 0.75;

        transition:
            transform 0.4s ease,
            opacity 0.4s ease;
    }


    .premium-stat-card:hover::after {
        transform: scale(1.15);
        opacity: 1;
    }


    /* =========================================================
       CARD BODY
    ========================================================= */

    .premium-stat-body {
        position: relative;

        z-index: 2;

        padding: 22px;
    }


    .premium-stat-top {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 15px;
    }


    .premium-stat-info {
        min-width: 0;

        flex: 1;
    }


    /* =========================================================
       LABEL
    ========================================================= */

    .premium-stat-label {
        margin-bottom: 8px;

        color: #64748b;

        font-size: 13px;

        font-weight: 600;

        letter-spacing: 0.2px;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }


    /* =========================================================
       VALUE
    ========================================================= */

    .premium-stat-value {
        margin: 0;

        color: #172033;

        font-size: 30px;

        line-height: 1;

        font-weight: 800;

        letter-spacing: -0.7px;
    }


    /* =========================================================
       SUBTITLE
    ========================================================= */

    .premium-stat-subtitle {
        margin-top: 9px;

        color: #94a3b8;

        font-size: 12px;

        font-weight: 500;

        line-height: 1.4;
    }


    /* =========================================================
       ICON
    ========================================================= */

    .premium-stat-icon {
        position: relative;

        z-index: 3;

        display: flex;

        align-items: center;

        justify-content: center;

        width: 52px;
        height: 52px;

        flex: 0 0 52px;

        border-radius: 14px;

        background: var(--card-soft);

        color: var(--card-accent);

        font-size: 22px;

        box-shadow:
            0 5px 15px rgba(15, 23, 42, 0.06);

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }


    .premium-stat-card:hover .premium-stat-icon {
        transform: scale(1.08) rotate(-3deg);

        box-shadow:
            0 8px 20px rgba(15, 23, 42, 0.10);
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .premium-stat-footer {
        position: relative;

        z-index: 3;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 10px;

        margin-top: 20px;

        padding-top: 13px;

        border-top: 1px solid #f1f3f7;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .premium-stat-status {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        color: var(--card-accent);

        font-size: 11px;

        font-weight: 700;
    }


    .premium-stat-status .status-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: currentColor;

        box-shadow:
            0 0 0 3px var(--card-soft);
    }


    /* =========================================================
       LINK
    ========================================================= */

    .premium-stat-link {
        color: #94a3b8;

        font-size: 12px;

        font-weight: 600;

        text-decoration: none;

        white-space: nowrap;

        transition: all 0.2s ease;
    }


    .premium-stat-link:hover {
        color: var(--card-accent);

        text-decoration: none;
    }


    /* =========================================================
       ROLE BADGE
    ========================================================= */

    .dashboard-role-badge {
        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 8px 14px;

        border-radius: 30px;

        background: #f8fafc;

        border: 1px solid #e5e7eb;

        color: #475569;

        font-size: 12px;

        font-weight: 700;

        text-transform: capitalize;

        box-shadow:
            0 3px 10px rgba(15, 23, 42, 0.04);
    }


    .role-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #16a34a;

        box-shadow:
            0 0 0 4px #dcfce7;
    }


    /* =========================================================
       CARD COLORS
    ========================================================= */

    .stat-blue {
        --card-accent: #2563eb;
        --card-soft: #eff6ff;
    }

    .stat-green {
        --card-accent: #16a34a;
        --card-soft: #f0fdf4;
    }

    .stat-orange {
        --card-accent: #ea580c;
        --card-soft: #fff7ed;
    }

    .stat-purple {
        --card-accent: #7c3aed;
        --card-soft: #f5f3ff;
    }

    .stat-cyan {
        --card-accent: #0891b2;
        --card-soft: #ecfeff;
    }

    .stat-red {
        --card-accent: #dc2626;
        --card-soft: #fef2f2;
    }

    .stat-indigo {
        --card-accent: #4f46e5;
        --card-soft: #eef2ff;
    }

    .stat-teal {
        --card-accent: #0f766e;
        --card-soft: #f0fdfa;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .premium-stat-body {
            padding: 20px;
        }

        .premium-stat-value {
            font-size: 28px;
        }
    }


    @media (max-width: 767px) {

        .dashboard-role-badge {
            margin-top: 12px;
        }

        .premium-stat-body {
            padding: 18px;
        }

        .premium-stat-value {
            font-size: 26px;
        }

        .premium-stat-icon {
            width: 46px;
            height: 46px;

            flex-basis: 46px;

            font-size: 19px;
        }

        .premium-stat-footer {
            margin-top: 16px;
        }
    }


    @media (max-width: 480px) {

        .premium-stat-top {
            gap: 10px;
        }

        .premium-stat-label {
            font-size: 12px;
        }

        .premium-stat-value {
            font-size: 24px;
        }

        .premium-stat-subtitle {
            font-size: 11px;
        }

        .premium-stat-footer {
            align-items: flex-start;

            flex-direction: column;
        }
    }
</style>


@php

    /*
    |--------------------------------------------------------------------------
    | AUTH USER
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    $role = strtolower($user?->role ?? '');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DATA
    |
    | Future:
    | These values will come from DashboardService.
    |--------------------------------------------------------------------------
    */

    $dashboardCards = [];


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    if ($role === 'admin') {

        $dashboardCards = [

            [
                'title'    => 'Total Clients',
                'value'    => $dashboardStats['total_clients'] ?? 0,
                'subtitle' => 'Registered transport clients',
                'icon'     => 'dw dw-user-2',
                'color'    => 'stat-blue',
                'status'   => 'Client Management',
                'link'     => 'View Clients',
                'url'      => route('client-management.index'),
            ],

            [
                'title'    => 'Total Drivers',
                'value'    => $dashboardStats['total_drivers'] ?? 0,
                'subtitle' => 'Registered drivers',
                'icon'     => 'dw dw-user-13',
                'color'    => 'stat-green',
                'status'   => 'Driver Management',
                'link'     => 'View Drivers',
                'url'      => route('driver-management.index'),
            ],

            [
                'title'    => 'Total Vehicles',
                'value'    => $dashboardStats['total_vehicles'] ?? 0,
                'subtitle' => 'Registered fleet vehicles',
                'icon'     => 'dw dw-car',
                'color'    => 'stat-purple',
                'status'   => 'Fleet Management',
                'link'     => 'View Vehicles',
                'url'      => route('vehicle-management.index'),
            ],

            [
                'title'    => 'Active Vehicles',
                'value'    => $dashboardStats['active_vehicles'] ?? 0,
                'subtitle' => 'Currently operational',
                'icon'     => 'dw dw-car',
                'color'    => 'stat-cyan',
                'status'   => 'Operational',
                'link'     => 'View Fleet',
                'url'      => route('vehicle-management.index'),
            ],

            [
                'title'    => 'Travel Requests',
                'value'    => $dashboardStats['travel_requests'] ?? 0,
                'subtitle' => 'Total transport requests',
                'icon'     => 'dw dw-file',
                'color'    => 'stat-orange',
                'status'   => 'Operations',
                'link'     => 'View Requests',
                'url'      => route('travel-requests.index'),
            ],

            [
                'title'    => 'Duty Assignments',
                'value'    => $dashboardStats['duty_assignments'] ?? 0,
                'subtitle' => 'Driver and vehicle assignments',
                'icon'     => 'dw dw-briefcase',
                'color'    => 'stat-indigo',
                'status'   => 'Operations',
                'link'     => 'View Duties',
                'url'      => route('duty-assignments.index'),
            ],

            [
                'title'    => 'Pending Duty Slips',
                'value'    => $dashboardStats['pending_duty_slips'] ?? 0,
                'subtitle' => 'Awaiting completion',
                'icon'     => 'dw dw-file-1',
                'color'    => 'stat-red',
                'status'   => 'Pending Action',
                'link'     => 'View Slips',
                'url'      => route('duty-slips.index'),
            ],

            [
                'title'    => 'Working Sheets',
                'value'    => $dashboardStats['working_sheets'] ?? 0,
                'subtitle' => 'Operational working sheets',
                'icon'     => 'dw dw-file',
                'color'    => 'stat-teal',
                'status'   => 'Operations',
                'link'     => 'View Sheets',
                'url'      => route('working-sheets.index'),
            ],

            [
                'title'    => 'Pending Payroll',
                'value'    => $dashboardStats['pending_payroll'] ?? 0,
                'subtitle' => 'Payroll awaiting processing',
                'icon'     => 'dw dw-money',
                'color'    => 'stat-orange',
                'status'   => 'Finance',
                'link'     => 'Process Payroll',
                'url'      => '#',
            ],

            [
                'title'    => 'Payroll Processed',
                'value'    => $dashboardStats['processed_payroll'] ?? 0,
                'subtitle' => 'Successfully processed payroll',
                'icon'     => 'dw dw-money-2',
                'color'    => 'stat-green',
                'status'   => 'Completed',
                'link'     => 'View Payroll',
                'url'      => '#',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | OPERATIONS
    |--------------------------------------------------------------------------
    */
    elseif ($role === 'operations') {

        $dashboardCards = [

            [
                'title'    => 'Total Clients',
                'value'    => $dashboardStats['total_clients'] ?? 0,
                'subtitle' => 'Clients available',
                'icon'     => 'dw dw-user-2',
                'color'    => 'stat-blue',
                'status'   => 'Client Data',
                'link'     => 'View Clients',
                'url'      => route('client-management.index'),
            ],

            [
                'title'    => 'Active Drivers',
                'value'    => $dashboardStats['active_drivers'] ?? 0,
                'subtitle' => 'Drivers available for duty',
                'icon'     => 'dw dw-user-13',
                'color'    => 'stat-green',
                'status'   => 'Available',
                'link'     => 'View Drivers',
                'url'      => route('driver-management.index'),
            ],

            [
                'title'    => 'Available Vehicles',
                'value'    => $dashboardStats['available_vehicles'] ?? 0,
                'subtitle' => 'Vehicles ready for duty',
                'icon'     => 'dw dw-car',
                'color'    => 'stat-cyan',
                'status'   => 'Available',
                'link'     => 'View Fleet',
                'url'      => route('vehicle-management.index'),
            ],

            [
                'title'    => 'Travel Requests',
                'value'    => $dashboardStats['travel_requests'] ?? 0,
                'subtitle' => 'Incoming transport requests',
                'icon'     => 'dw dw-file',
                'color'    => 'stat-orange',
                'status'   => 'Operations',
                'link'     => 'Manage Requests',
                'url'      => route('travel-requests.index'),
            ],

            [
                'title'    => 'Pending Duty Assignments',
                'value'    => $dashboardStats['pending_duty_assignments'] ?? 0,
                'subtitle' => 'Assignments awaiting action',
                'icon'     => 'dw dw-briefcase',
                'color'    => 'stat-indigo',
                'status'   => 'Pending',
                'link'     => 'Assign Duties',
                'url'      => route('duty-assignments.index'),
            ],

            [
                'title'    => 'Open Duty Slips',
                'value'    => $dashboardStats['open_duty_slips'] ?? 0,
                'subtitle' => 'Active duty slips',
                'icon'     => 'dw dw-file-1',
                'color'    => 'stat-purple',
                'status'   => 'Active',
                'link'     => 'View Slips',
                'url'      => route('duty-slips.index'),
            ],

            [
                'title'    => 'Working Sheets',
                'value'    => $dashboardStats['working_sheets'] ?? 0,
                'subtitle' => 'Operational worksheets',
                'icon'     => 'dw dw-file',
                'color'    => 'stat-teal',
                'status'   => 'Operations',
                'link'     => 'View Sheets',
                'url'      => route('working-sheets.index'),
            ],

            [
                'title'    => 'Completed Duties',
                'value'    => $dashboardStats['completed_duties'] ?? 0,
                'subtitle' => 'Successfully completed duties',
                'icon'     => 'dw dw-check',
                'color'    => 'stat-green',
                'status'   => 'Completed',
                'link'     => 'View Duties',
                'url'      => route('duty-assignments.index'),
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNTANT
    |--------------------------------------------------------------------------
    */

    elseif ($role === 'accountant') {

        $dashboardCards = [

            [
                'title'    => 'Pending Payroll',
                'value'    => $dashboardStats['pending_payroll'] ?? 0,
                'subtitle' => 'Payroll awaiting processing',
                'icon'     => 'dw dw-money',
                'color'    => 'stat-orange',
                'status'   => 'Pending',
                'link'     => 'Process Payroll',
                'url'      => '#',
            ],

            [
                'title'    => 'Processed Payroll',
                'value'    => $dashboardStats['processed_payroll'] ?? 0,
                'subtitle' => 'Completed payroll records',
                'icon'     => 'dw dw-money-2',
                'color'    => 'stat-green',
                'status'   => 'Completed',
                'link'     => 'View Payroll',
                'url'      => '#',
            ],

            [
                'title'    => 'Allowances',
                'value'    => $dashboardStats['allowances'] ?? 0,
                'subtitle' => 'Driver allowance records',
                'icon'     => 'dw dw-list',
                'color'    => 'stat-blue',
                'status'   => 'Finance',
                'link'     => 'View Allowances',
                'url'      => '#',
            ],

            [
                'title'    => 'Pending Expenses',
                'value'    => $dashboardStats['pending_expenses'] ?? 0,
                'subtitle' => 'Expenses awaiting approval',
                'icon'     => 'dw dw-wallet',
                'color'    => 'stat-red',
                'status'   => 'Pending',
                'link'     => 'View Expenses',
                'url'      => '#',
            ],

            [
                'title'    => 'Salary Slips',
                'value'    => $dashboardStats['salary_slips'] ?? 0,
                'subtitle' => 'Generated salary slips',
                'icon'     => 'dw dw-file',
                'color'    => 'stat-purple',
                'status'   => 'Payroll',
                'link'     => 'View Salary Slips',
                'url'      => '#',
            ],

            [
                'title'    => 'Payroll Reports',
                'value'    => $dashboardStats['payroll_reports'] ?? 0,
                'subtitle' => 'Financial payroll reports',
                'icon'     => 'dw dw-bar-chart',
                'color'    => 'stat-indigo',
                'status'   => 'Reports',
                'link'     => 'View Reports',
                'url'      => '#',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DRIVER
    |--------------------------------------------------------------------------
    */

    elseif ($role === 'driver') {

        $dashboardCards = [

            [
                'title'    => 'Assigned Duties',
                'value'    => $dashboardStats['assigned_duties'] ?? 0,
                'subtitle' => 'Your assigned duties',
                'icon'     => 'dw dw-briefcase',
                'color'    => 'stat-blue',
                'status'   => 'Assigned',
                'link'     => 'View Duties',
                'url'      => '#',
            ],

            [
                'title'    => "Today's Duties",
                'value'    => $dashboardStats['today_duties'] ?? 0,
                'subtitle' => 'Duties scheduled today',
                'icon'     => 'dw dw-calendar',
                'color'    => 'stat-orange',
                'status'   => 'Today',
                'link'     => 'View Schedule',
                'url'      => '#',
            ],

            [
                'title'    => 'Open Duty Slips',
                'value'    => $dashboardStats['open_duty_slips'] ?? 0,
                'subtitle' => 'Duty slips to complete',
                'icon'     => 'dw dw-file-1',
                'color'    => 'stat-purple',
                'status'   => 'Open',
                'link'     => 'View Slips',
                'url'      => '#',
            ],

            [
                'title'    => 'Completed Duties',
                'value'    => $dashboardStats['completed_duties'] ?? 0,
                'subtitle' => 'Successfully completed',
                'icon'     => 'dw dw-check',
                'color'    => 'stat-green',
                'status'   => 'Completed',
                'link'     => 'Duty History',
                'url'      => '#',
            ],

            [
                'title'    => 'Working Sheets',
                'value'    => $dashboardStats['working_sheets'] ?? 0,
                'subtitle' => 'Your working sheets',
                'icon'     => 'dw dw-file',
                'color'    => 'stat-teal',
                'status'   => 'Records',
                'link'     => 'View Sheets',
                'url'      => '#',
            ],

            [
                'title'    => 'Current Salary',
                'value'    => '₹ ' . number_format($dashboardStats['current_salary'] ?? 0, 2),
                'subtitle' => 'Current salary information',
                'icon'     => 'dw dw-money',
                'color'    => 'stat-cyan',
                'status'   => 'Payroll',
                'link'     => 'View Salary',
                'url'      => '#',
            ],

            [
                'title'    => 'Pending Salary',
                'value'    => '₹ ' . number_format($dashboardStats['pending_salary'] ?? 0, 2),
                'subtitle' => 'Salary awaiting processing',
                'icon'     => 'dw dw-money-2',
                'color'    => 'stat-red',
                'status'   => 'Pending',
                'link'     => 'Salary Details',
                'url'      => '#',
            ],
        ];
    }

@endphp


{{-- =========================================================
    DASHBOARD HEADER
========================================================= --}}

<div class="title pb-20 dashboard-header">

    <div class="d-flex align-items-center justify-content-between flex-wrap">

        <div>

            <h2 class="h3 mb-5">
                Transport Operations Dashboard
            </h2>

            <p class="text-muted mb-0">
                Welcome back,
                <strong>{{ $user?->name ?? 'User' }}</strong>
            </p>

        </div>


        <div class="dashboard-role-badge">

            <span class="role-dot"></span>

            {{ ucfirst($role ?: 'User') }}

        </div>

    </div>

</div>


{{-- =========================================================
    PREMIUM STAT CARDS
========================================================= --}}

<div class="row pb-10">

    @forelse($dashboardCards as $card)

        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">

            <div class="premium-stat-card {{ $card['color'] }}">

                <div class="premium-stat-body">

                    {{-- TOP --}}
                    <div class="premium-stat-top">

                        <div class="premium-stat-info">

                            <div class="premium-stat-label">

                                {{ $card['title'] }}

                            </div>


                            <div class="premium-stat-value">

                                {{ $card['value'] }}

                            </div>


                            <div class="premium-stat-subtitle">

                                {{ $card['subtitle'] }}

                            </div>

                        </div>


                        {{-- ICON --}}
                        <div class="premium-stat-icon">

                            <i class="icon-copy {{ $card['icon'] }}"></i>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="premium-stat-footer">

                        <div class="premium-stat-status">

                            <span class="status-dot"></span>

                            {{ $card['status'] }}

                        </div>


                        @if(!empty($card['url']) && $card['url'] !== '#')

                            <a
                                href="{{ $card['url'] }}"
                                class="premium-stat-link"
                            >

                                {{ $card['link'] }}

                                <i class="fa fa-angle-right ml-1"></i>

                            </a>

                        @else

                            <span class="premium-stat-link">

                                {{ $card['link'] }}

                                <i class="fa fa-angle-right ml-1"></i>

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-warning">

                Dashboard access is not configured for this user role.

            </div>

        </div>

    @endforelse

</div>