
<div class="header">
    {{-- Header Left Section Start --}}
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
        <div class="header-search">
            {{-- <form>
                <div class="form-group mb-0">
                    <i class="dw dw-search2 search-icon"></i>
                    <input type="text" class="form-control search-input" placeholder="Search Here" />
                    <div class="dropdown">
                        <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                            <i class="ion-arrow-down-c"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">From</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">To</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">Subject</label>
                                <div class="col-sm-12 col-md-10">
                                    <input class="form-control form-control-sm form-control-line" type="text" />
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form> --}}
        </div>
    </div>
    {{-- Header Left Section End --}}

    {{-- Header Right Section Start --}}
    <div class="header-right">

        @php
            $authUser = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | User Histories
            |--------------------------------------------------------------------------
            | Keep query limited and scoped to authenticated user.
            */
            $histories = DB::table('user_histories')
                ->join('users', 'users.id', '=', 'user_histories.user_id')
                ->select(
                    'user_histories.id',
                    'user_histories.activity',
                    'user_histories.city',
                    'user_histories.country',
                    'user_histories.ip_address',
                    'user_histories.activity_time',
                    'users.name as user_name'
                )
                ->where('user_histories.user_id', $authUser->id)
                ->orderByDesc('user_histories.id')
                ->limit(5)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Role Configuration
            |--------------------------------------------------------------------------
            */

            $role = strtolower(trim((string) ($authUser->role ?? 'user')));

            $roleBadge = match ($role) {

                'admin' => [
                    'bg'     => '#fff1f2',
                    'color'  => '#dc3545',
                    'border' => '#fecdd3',
                    'icon'   => 'dw dw-user1',
                    'label'  => 'Administrator',
                ],

                'operations' => [
                    'bg'     => '#eff6ff',
                    'color'  => '#2563eb',
                    'border' => '#bfdbfe',
                    'icon'   => 'dw dw-settings',
                    'label'  => 'Operations',
                ],

                'accountant' => [
                    'bg'     => '#fffbeb',
                    'color'  => '#d97706',
                    'border' => '#fde68a',
                    'icon'   => 'dw dw-money',
                    'label'  => 'Accountant',
                ],

                'driver' => [
                    'bg'     => '#f0fdf4',
                    'color'  => '#16a34a',
                    'border' => '#bbf7d0',
                    'icon'   => 'dw dw-user1',
                    'label'  => 'Driver',
                ],

                'user' => [
                    'bg'     => '#eff6ff',
                    'color'  => '#0284c7',
                    'border' => '#bae6fd',
                    'icon'   => 'dw dw-user1',
                    'label'  => 'User',
                ],

                default => [
                    'bg'     => '#f8fafc',
                    'color'  => '#64748b',
                    'border' => '#e2e8f0',
                    'icon'   => 'dw dw-user1',
                    'label'  => ucwords(str_replace('_', ' ', $role)),
                ],
            };

            /*
            |--------------------------------------------------------------------------
            | Profile Image
            |--------------------------------------------------------------------------
            */
            $profileImage = !empty($authUser->profile_image)
                ? asset('backend/assets/uploads/profile/' . $authUser->profile_image)
                : asset('backend/assets/img/logo/favicon.ico');
        @endphp


        {{-- ============================================================= --}}
        {{-- Notification --}}
        {{-- ============================================================= --}}

        <div class="user-notification">

            <div class="dropdown">

                <a
                    class="dropdown-toggle no-arrow"
                    href="#"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    title="Notifications"
                >
                    <i class="icon-copy dw dw-notification"></i>

                    @if($histories->isNotEmpty())
                        <span class="badge notification-active"></span>
                    @endif
                </a>


                <div class="dropdown-menu dropdown-menu-right">

                    <div class="notification-list mx-h-350 customscroll">

                        <ul class="notification-list-items">

                            @forelse($histories as $history)

                                <li class="notification-item">

                                    <a href="javascript:void(0);" class="notification-link">

                                        {{-- Brand Logo --}}
                                        <div class="notification-avatar">
                                            <img
                                                src="{{ asset('backend/assets/img/logo/mastermind-logo.webp') }}"
                                                alt="Brand Logo"
                                                width="120"
                                                height="42"
                                                loading="lazy"
                                                decoding="async"
                                                data-no-optimize="1"
                                            >
                                        </div>


                                        {{-- Activity Content --}}
                                        <div class="notification-content">

                                            {{-- User --}}
                                            <h3 class="notification-user">
                                                {{ $history->user_name ?? 'User' }}
                                            </h3>


                                            {{-- Activity --}}
                                            <p class="notification-message">

                                                @switch($history->activity)

                                                    @case('login')
                                                        <span class="activity-icon login">🟢</span>
                                                        <span>Logged in from</span>
                                                        @break

                                                    @case('logout')
                                                        <span class="activity-icon logout">🔴</span>
                                                        <span>Logged out from</span>
                                                        @break

                                                    @default
                                                        <span class="activity-icon activity">🔵</span>
                                                        <span>
                                                            {{ ucfirst(str_replace('_', ' ', $history->activity ?? 'Activity')) }}
                                                            from
                                                        </span>

                                                @endswitch

                                                <strong>
                                                    {{ $history->city ?: 'Unknown City' }}
                                                </strong>

                                                @if(!empty($history->country))
                                                    , {{ $history->country }}
                                                @endif

                                            </p>


                                            {{-- IP Address --}}
                                            @if(!empty($history->ip_address))

                                                <small class="notification-ip">
                                                    <i class="fa fa-globe"></i>
                                                    {{ $history->ip_address }}
                                                </small>

                                            @endif


                                            {{-- Activity Time --}}
                                            @if(!empty($history->activity_time))

                                                <small class="notification-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    {{ \Carbon\Carbon::parse($history->activity_time)->diffForHumans() }}
                                                </small>

                                            @endif

                                        </div>

                                    </a>

                                </li>

                            @empty

                                <li class="notification-empty">

                                    <div class="text-center py-3">

                                        <i class="fa fa-bell-slash-o mb-2"
                                        style="font-size:24px; opacity:.5;"></i>

                                        <p class="mb-0">
                                            No recent activity found
                                        </p>

                                    </div>

                                </li>

                            @endforelse

                        </ul>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================= --}}
        {{-- Dashboard Settings --}}
        {{-- ============================================================= --}}

        <div class="dashboard-setting user-notification">

            <div class="dropdown">

                <a
                    class="dropdown-toggle no-arrow"
                    href="javascript:void(0);"
                    data-toggle="right-sidebar"
                    title="Settings"
                >
                    <i class="dw dw-settings2"></i>
                </a>

            </div>

        </div>


        {{-- ============================================================= --}}
        {{-- User Info Dropdown --}}
        {{-- ============================================================= --}}

        <div class="user-info-dropdown">

            <div class="dropdown">

                <a
                    class="dropdown-toggle"
                    href="#"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >

                    {{-- Profile Image --}}
                    <span class="user-profile">

                        <img
                            src="{{ $profileImage }}"
                            alt="{{ $authUser->name ?? 'Profile' }}"
                            loading="eager"
                            decoding="async"
                            width="120"
                            height="50"
                            style="width:120px !important; height:50px !important; object-fit:contain;"
                            data-no-optimize="1"
                        >

                    </span>


                    {{-- User Name --}}
                    <span class="user-name">

                        Welcome -
                        <b class="font-weight-bold text-capitalize">
                            {{ $authUser->name ?? 'User' }}
                        </b>

                    </span>

                </a>


                {{-- ===================================================== --}}
                {{-- User Dropdown --}}
                {{-- ===================================================== --}}

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">


                    {{-- ================================================= --}}
                    {{-- User Role --}}
                    {{-- ================================================= --}}

                    <div
                        style="
                            padding: 10px 15px;
                            display: flex;
                            align-items: center;
                            justify-content: flex-start;
                        "
                    >
                        <span
                            style="
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                gap: 7px;

                                padding: 7px 14px;

                                background-color: {{ $roleBadge['bg'] }};
                                color: {{ $roleBadge['color'] }};

                                border: 1px solid {{ $roleBadge['border'] }};
                                border-radius: 50px;

                                font-size: 12px;
                                font-weight: 600;
                                line-height: 1;

                                letter-spacing: 0.2px;
                                white-space: nowrap;

                                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
                            "
                            title="Current Role"
                        >
                            <i
                                class="{{ $roleBadge['icon'] }}"
                                style="
                                    font-size: 13px;
                                    color: {{ $roleBadge['color'] }};
                                    line-height: 1;
                                    display: inline-block;
                                "
                            ></i>

                            <span>
                                {{ $roleBadge['label'] }}
                            </span>
                        </span>
                    </div>


                    <div class="dropdown-divider"></div>


                    {{-- ================================================= --}}
                    {{-- Change Password --}}
                    {{-- ================================================= --}}

                    <a
                        class="dropdown-item"
                        href="{{ route('admin.change-password') }}"
                    >

                        <i class="dw dw-user1"></i>

                        <span>Change Password</span>

                    </a>


                    {{-- ================================================= --}}
                    {{-- Profile --}}
                    {{-- ================================================= --}}

                    <a
                        class="dropdown-item"
                        href="{{ route('admin.profile') }}"
                    >

                        <i class="dw dw-settings"></i>

                        <span>Profile</span>

                    </a>


                    {{-- ================================================= --}}
                    {{-- Logout --}}
                    {{-- ================================================= --}}

                    <a
                        class="dropdown-item"
                        href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    >

                        <i class="dw dw-logout"></i>

                        <span>Log Out</span>

                    </a>


                    {{-- ================================================= --}}
                    {{-- Logout Form --}}
                    {{-- ================================================= --}}

                    <form
                        id="logout-form"
                        action="{{ route('admin.logout') }}"
                        method="POST"
                        class="d-none"
                    >
                        @csrf
                    </form>


                </div>

            </div>

        </div>

    </div>
    {{-- Header Right Section End --}}
    
</div>
