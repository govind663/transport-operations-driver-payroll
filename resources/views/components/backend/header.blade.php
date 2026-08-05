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

    {{-- Header Right Section Start --}}
    <div class="header-right">
        {{-- User Notification --}}
        <div class="user-notification">
            <div class="dropdown">
                <a
                    class="dropdown-toggle no-arrow"
                    href="#"
                    role="button"
                    data-toggle="dropdown"
                >
                    <i class="icon-copy dw dw-notification"></i>
                    <span class="badge notification-active"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="notification-list mx-h-350 customscroll">
                        <ul>

                            @php
                                $histories = DB::table('user_histories')
                                    ->join('users', 'users.id', '=', 'user_histories.user_id')
                                    ->select('user_histories.*', 'users.name as user_name')
                                    ->where('user_histories.user_id', auth()->id())
                                    ->orderBy('user_histories.id', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @forelse($histories as $history)
                                <li>
                                    <a href="#">

                                        <img src="{{ asset('backend/assets/img/logo/favicon.ico') }}" alt="" />

                                        <h3>{{ $history->user_name }}</h3>

                                        <p>

                                            @if($history->activity == 'login')
                                                🟢 Logged in from
                                            @elseif($history->activity == 'logout')
                                                🔴 Logged out from
                                            @else
                                                🔵 {{ ucfirst($history->activity) }} from
                                            @endif

                                            {{ $history->city ?? 'Unknown City' }}
                                            {{ $history->country ?? 'Unknown Country' }}                                            
                                            ({{ $history->ip_address }})

                                        </p>

                                        <small>
                                            {{ \Carbon\Carbon::parse($history->activity_time)->diffForHumans() }}
                                        </small>

                                    </a>
                                </li>
                            @empty
                                <li>
                                    <p class="text-center">No Activity Found</p>
                                </li>
                            @endforelse

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Setting Icon --}}
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>

        {{-- User Info Dropdown --}}
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{ Auth::user()->profile_image 
                            ? asset('/backend/assets/uploads/profile/' . Auth::user()->profile_image) 
                            : asset('/backend/assets/img/logo/favicon.ico') }}" 
                            style="width: 53px !important; height: 50px !important;"
                        alt="Profile">
                    </span>
                    <span class="user-name">
                        Welcome - <b class="font-weight-bold text-capitalize">{{ Auth::user()->name }}</b>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    {{-- Role Badge --}}
                    <span class="dropdown-item">
                        @php
                            $role = Auth::user()->role;

                            $roleBadge = match ($role) {

                                'admin' => [
                                    'class' => 'badge-danger',
                                    'icon'  => 'fa fa-user-shield',
                                ],

                                'operations' => [
                                    'class' => 'badge-primary',
                                    'icon'  => 'fa fa-tasks',
                                ],

                                'accountant' => [
                                    'class' => 'badge-warning',
                                    'icon'  => 'fa fa-calculator',
                                ],

                                'driver' => [
                                    'class' => 'badge-success',
                                    'icon'  => 'fa fa-id-card',
                                ],

                                default => [
                                    'class' => 'badge-secondary',
                                    'icon'  => 'fa fa-user',
                                ],
                            };
                        @endphp

                        <span class="badge {{ $roleBadge['class'] }} badge-pill px-3 py-2">
                            <i class="{{ $roleBadge['icon'] }} mr-1"></i>
                            {{ ucwords(str_replace('_', ' ', $role)) }}
                        </span>
                    </span>

                    <div class="dropdown-divider"></div>
                    
                    <a class="dropdown-item" href="{{ route('change-password') }}">
                        <i class="dw dw-user1"></i> Change Password
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="dw dw-settings"></i> Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="dw dw-logout"></i>
                        Log Out
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
