<?php

namespace App\Services\Dashboard;

use App\Models\Allowance;
use App\Models\Client;
use App\Models\Driver;
use App\Models\DriverAllowance;
use App\Models\DriverAttendance;
use App\Models\DriverExpense;
use App\Models\DutyAssignment;
use App\Models\DutySlip;
use App\Models\Expense;
use App\Models\ImageMeta;
use App\Models\ImageOptimizationLog;
use App\Models\SalaryProcessing;
use App\Models\SalarySlip;
use App\Models\TravelRequest;
use App\Models\User;
use App\Models\UserHistory;
use App\Models\VehicleCategory;
use App\Models\VehicleManagement;
use App\Models\VehiclePrice;
use App\Models\VehicleType;
use App\Models\WorkingSheet;
use Illuminate\Support\Carbon;

class DashboardService
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ENTRY POINT
    |--------------------------------------------------------------------------
    |
    | The role names below come from App\Models\User. Keep the legacy statistic
    | keys used by backend.partials.role-overview.php so the current dashboard
    | continues to render while the view is being expanded with new cards.
    |
    */

    public function getDashboardData($user): array
    {
        if (!$user) {
            return [];
        }

        $dashboardData = match ($user->role) {
            User::ROLE_ADMIN => $this->adminDashboard(),
            User::ROLE_OPERATIONS => $this->operationsDashboard(),
            User::ROLE_ACCOUNTANT => $this->accountantDashboard(),
            User::ROLE_DRIVER => $this->driverDashboard($user),
            default => [],
        };

        if ($dashboardData === []) {
            return [];
        }

        // Presentation widgets use the same validated reporting range as the UI.
        // Master inventory counts and live approval queues remain current snapshots;
        // historical charts and audit activity follow the selected date range.
        return array_merge($dashboardData, $this->dashboardPresentationData($user));
    }

    /**
     * Create real, role-aware data for the dashboard's action center, duty board,
     * recent activity and historical charts. Nothing in this method is fabricated.
     */
    protected function dashboardPresentationData($user): array
    {
        $range = $this->resolveDashboardRange();
        $startAt = $range['start']->copy()->startOfDay();
        $endAt = $range['end']->copy()->endOfDay();
        $role = (string) $user->role;

        $data = array_merge($this->buildPeriodMetrics($user, $startAt, $endAt), [
            'dashboard_range' => [
                'period' => $range['period'],
                'start' => $range['start']->toDateString(),
                'end' => $range['end']->toDateString(),
                'label' => $range['label'],
                'last_updated' => now()->toIso8601String(),
            ],
            'trend_series' => $this->buildTrendSeries($startAt, $endAt, $role, $user),
            'today_operations' => [],
            'recent_activity' => [],
        ]);

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS, User::ROLE_DRIVER], true)) {
            $data['overdue_duty_slips'] = $this->overdueDutySlipsCount($role, $user);
        }

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS], true)) {
            $data['unassigned_travel_requests'] = TravelRequest::query()
                ->whereIn('status', [TravelRequest::STATUS_PENDING, TravelRequest::STATUS_APPROVED])
                ->whereDoesntHave('dutyAssignment')
                ->count();
        }

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS, User::ROLE_DRIVER], true)) {
            $todayDuties = DutySlip::query()
                ->with([
                    'driver:id,user_id,driver_code,first_name,last_name',
                    'vehicle:id,vehicle_number,registration_number',
                    'dutyAssignment:id,reporting_time,status',
                ])
                ->whereDate('duty_date', today())
                ->when($role === User::ROLE_DRIVER, function ($query) use ($user) {
                    $driverId = $this->getDriverId($user);
                    if ($driverId) {
                        $query->where('driver_id', $driverId);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                })
                ->orderBy('start_time')
                ->limit(12)
                ->get();

            $data['today_operations'] = $todayDuties->map(function (DutySlip $slip) {
                $driverName = trim((string) (($slip->driver?->first_name ?? '') . ' ' . ($slip->driver?->last_name ?? '')));
                if ($driverName === '') {
                    $driverName = $slip->driver?->driver_code ?: 'Driver not assigned';
                }

                $vehicle = $slip->vehicle?->vehicle_number
                    ?: $slip->vehicle?->registration_number
                    ?: 'Vehicle not allocated';
                $pickup = trim((string) ($slip->pickup_location ?? ''));
                $drop = trim((string) ($slip->drop_location ?? ''));
                $route = trim($pickup . ($pickup !== '' && $drop !== '' ? ' → ' : '') . $drop);

                $startTime = $slip->start_time;
                if ($startTime instanceof \DateTimeInterface) {
                    $displayTime = $startTime->format('h:i A');
                } else {
                    $displayTime = (string) ($startTime ?: ($slip->dutyAssignment?->reporting_time ?: 'Time not set'));
                }

                return [
                    'id' => (int) $slip->id,
                    'slip_no' => (string) ($slip->slip_no ?: ('Duty slip #' . $slip->id)),
                    'time' => $displayTime,
                    'driver' => $driverName,
                    'vehicle' => $vehicle,
                    'route' => $route !== '' ? $route : 'Route not provided',
                    'status' => (string) ($slip->status ?: 'pending'),
                    'duty_date' => optional($slip->duty_date)->format('Y-m-d') ?: today()->toDateString(),
                ];
            })->values()->all();
        }

        // Admins, Operations and Accountants can browse basic user records.
        // Detailed per-user activity is intentionally restricted to Administrators.
        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS, User::ROLE_ACCOUNTANT], true)) {
            $data['user_directory'] = $this->buildUserDirectory();
        }

        // Drivers only receive their own activity. Admins receive the global
        // recent-audit preview; the full user history is shown on the admin detail page.
        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_DRIVER], true)) {
            $historyQuery = UserHistory::query()
                ->with('user:id,name')
                ->whereBetween('activity_time', [$startAt, $endAt]);

            if ($role === User::ROLE_DRIVER) {
                $historyQuery->where('user_id', $user->id);
            }

            $data['recent_activity'] = $historyQuery
                ->orderByDesc('activity_time')
                ->limit($role === User::ROLE_DRIVER ? 20 : 8)
                ->get()
                ->map(function (UserHistory $history) {
                    $latitude = $history->getAttribute('latitude');
                    $longitude = $history->getAttribute('longitude');
                    $hasCoordinates = is_numeric($latitude) && is_numeric($longitude);

                    return [
                        'user_id' => (int) $history->user_id,
                        'actor' => (string) ($history->user?->name ?: 'Unknown user'),
                        'activity' => (string) ($history->activity ?: 'Activity recorded'),
                        'event_type' => (string) ($history->getAttribute('event_type') ?: 'activity'),
                        'time' => $history->activity_time?->format('d M Y, h:i A') ?: 'Time not recorded',
                        'city' => (string) ($history->city ?: ''),
                        'country' => (string) ($history->country ?: ''),
                        'ip_address' => (string) ($history->ip_address ?: ''),
                        'device' => (string) ($history->device ?: ''),
                        'latitude' => $hasCoordinates ? (float) $latitude : null,
                        'longitude' => $hasCoordinates ? (float) $longitude : null,
                        'location_accuracy' => is_numeric($history->getAttribute('location_accuracy')) ? (float) $history->getAttribute('location_accuracy') : null,
                        'location_captured_at' => $history->getAttribute('location_captured_at') ? Carbon::parse($history->getAttribute('location_captured_at'))->format('d M Y, h:i:s A') : null,
                    ];
                })->values()->all();
        }

        return $data;
    }

    /**
     * Build a paginated, searchable user directory for dashboard roles other than Driver.
     * Only basic identity/status information is returned here; activity details are
     * loaded by UserActivityController and only Admin may access those details.
     */
    protected function buildUserDirectory()
    {
        $request = request();
        $query = User::query()
            ->select(['id', 'name', 'email', 'phone', 'role', 'status', 'created_at'])
            ->withCount('histories')
            ->withMax('histories', 'activity_time');

        $search = trim((string) $request->query('user_q', ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $role = (string) $request->query('user_role', '');
        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS, User::ROLE_ACCOUNTANT, User::ROLE_DRIVER], true)) {
            $query->where('role', $role);
        }

        $status = (string) $request->query('user_status', '');
        if ($status === 'active') {
            $query->where('status', true);
        } elseif ($status === 'inactive') {
            $query->where('status', false);
        }

        foreach (['user_registered_from' => '>=', 'user_registered_to' => '<='] as $key => $operator) {
            $value = $request->query($key);
            if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) && strtotime($value) !== false) {
                $query->whereDate('created_at', $operator, $value);
            }
        }

        $activityFilter = $request->query('user_activity_period');
        if (in_array($activityFilter, ['has_activity', 'no_activity'], true)) {
            $query->has('histories', $activityFilter === 'has_activity' ? '>=' : '=', $activityFilter === 'has_activity' ? 1 : 0);
        }

        return $query->orderByDesc('created_at')
            ->paginate(10, ['*'], 'user_page')
            ->withQueryString();
    }

    /** Validate standard/custom reporting dates and cap custom ranges to one year. */
    protected function resolveDashboardRange(): array
    {
        $period = (string) request()->query('dashboard_period', '30d');
        $today = now()->startOfDay();

        if ($period === '7d') {
            $start = $today->copy()->subDays(6);
            $end = $today->copy();
            $label = 'Last 7 days';
        } elseif ($period === 'custom') {
            try {
                $rawStart = request()->query('dashboard_start');
                $rawEnd = request()->query('dashboard_end');
                if (!is_string($rawStart) || trim($rawStart) === '' || !is_string($rawEnd) || trim($rawEnd) === '') {
                    throw new \InvalidArgumentException('Both custom dates are required.');
                }
                $start = Carbon::parse($rawStart)->startOfDay();
                $end = Carbon::parse($rawEnd)->startOfDay();
                if ($start->gt($end) || $start->diffInDays($end) > 365 || $end->gt($today)) {
                    throw new \InvalidArgumentException('Invalid dashboard date range.');
                }
                $label = $start->format('d M Y') . ' – ' . $end->format('d M Y');
            } catch (\Throwable $exception) {
                $period = '30d';
                $start = $today->copy()->subDays(29);
                $end = $today->copy();
                $label = 'Last 30 days';
            }
        } else {
            $period = '30d';
            $start = $today->copy()->subDays(29);
            $end = $today->copy();
            $label = 'Last 30 days';
        }

        return ['period' => $period, 'start' => $start, 'end' => $end, 'label' => $label];
    }

    /**
     * Provide selected-range variants for dated metric cards. Related statuses are
     * grouped in SQL so the reporting filter adds a small number of aggregate
     * queries instead of one query per card.
     */
    protected function buildPeriodMetrics($user, Carbon $startAt, Carbon $endAt): array
    {
        $role = (string) $user->role;
        $startDate = $startAt->toDateString();
        $endDate = $endAt->toDateString();
        $metrics = [];
        $driverId = $role === User::ROLE_DRIVER ? ($this->getDriverId($user) ?: 0) : null;
        $range = [$startAt, $endAt];

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS], true)) {
            $travel = TravelRequest::query()->whereBetween('created_at', $range);
            $travelStatuses = $this->statusAggregates(clone $travel);
            $metrics['travel_requests_period'] = array_sum(array_map(static fn ($row) => (int) $row->aggregate, $travelStatuses));
            foreach ([
                'pending_travel_requests' => TravelRequest::STATUS_PENDING,
                'approved_travel_requests' => TravelRequest::STATUS_APPROVED,
                'rejected_travel_requests' => TravelRequest::STATUS_REJECTED,
                'assigned_travel_requests' => TravelRequest::STATUS_ASSIGNED,
                'completed_travel_requests' => TravelRequest::STATUS_COMPLETED,
                'cancelled_travel_requests' => TravelRequest::STATUS_CANCELLED,
            ] as $key => $status) {
                $metrics[$key . '_period'] = $this->statusCount($travelStatuses, $status);
            }
        }

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS, User::ROLE_DRIVER], true)) {
            $duties = DutyAssignment::query()->whereBetween('created_at', $range);
            if ($role === User::ROLE_DRIVER) {
                $duties->where('driver_id', $driverId);
            }
            $dutyStatuses = $this->statusAggregates(clone $duties);
            $metrics['duty_assignments_period'] = array_sum(array_map(static fn ($row) => (int) $row->aggregate, $dutyStatuses));
            $metrics['assigned_duties_period'] = $this->statusCount($dutyStatuses, DutyAssignment::STATUS_ASSIGNED)
                + $this->statusCount($dutyStatuses, DutyAssignment::STATUS_ACCEPTED)
                + $this->statusCount($dutyStatuses, DutyAssignment::STATUS_STARTED);
            foreach ([
                'pending_duty_assignments' => DutyAssignment::STATUS_PENDING,
                'assigned_duty_assignments' => DutyAssignment::STATUS_ASSIGNED,
                'accepted_duty_assignments' => DutyAssignment::STATUS_ACCEPTED,
                'started_duty_assignments' => DutyAssignment::STATUS_STARTED,
                'rejected_duty_assignments' => DutyAssignment::STATUS_REJECTED,
                'cancelled_duty_assignments' => DutyAssignment::STATUS_CANCELLED,
            ] as $key => $status) {
                $metrics[$key . '_period'] = $this->statusCount($dutyStatuses, $status);
            }
            $completedDuties = DutyAssignment::query()
                ->where('status', DutyAssignment::STATUS_COMPLETED)
                ->whereBetween('updated_at', $range);
            if ($role === User::ROLE_DRIVER) {
                $completedDuties->where('driver_id', $driverId);
            }
            $metrics['completed_duties_period'] = $completedDuties->count();

            $slips = DutySlip::query()->whereBetween('duty_date', [$startDate, $endDate]);
            if ($role === User::ROLE_DRIVER) {
                $slips->where('driver_id', $driverId);
            }
            $slipStatuses = $this->statusAggregates(clone $slips);
            $metrics['pending_duty_slips_period'] = $this->statusCount($slipStatuses, DutySlip::STATUS_OPEN);
            $metrics['open_duty_slips_period'] = $this->statusCount($slipStatuses, DutySlip::STATUS_OPEN);
            $metrics['started_duty_slips_period'] = $this->statusCount($slipStatuses, DutySlip::STATUS_STARTED);
            $metrics['completed_duty_slips_period'] = $this->statusCount($slipStatuses, DutySlip::STATUS_COMPLETED);
            $metrics['cancelled_duty_slips_period'] = $this->statusCount($slipStatuses, DutySlip::STATUS_CANCELLED);

            // working_sheets has no driver_id column. Scope driver records through
            // the related duty slip, which owns the driver_id foreign key.
            $sheets = WorkingSheet::query()->whereBetween('work_date', [$startDate, $endDate]);
            if ($role === User::ROLE_DRIVER) {
                if (!$driverId) {
                    $sheets->whereRaw('1 = 0');
                } else {
                    $sheets->whereHas('dutySlip', function ($query) use ($driverId) {
                        $query->where('driver_id', $driverId);
                    });
                }
            }
            $sheetStatuses = $this->statusAggregates(clone $sheets);
            $metrics['working_sheets_period'] = array_sum(array_map(static fn ($row) => (int) $row->aggregate, $sheetStatuses));
            foreach ([
                'draft_working_sheets' => WorkingSheet::STATUS_DRAFT,
                'submitted_working_sheets' => WorkingSheet::STATUS_SUBMITTED,
                'approved_working_sheets' => WorkingSheet::STATUS_APPROVED,
                'rejected_working_sheets' => WorkingSheet::STATUS_REJECTED,
                'completed_working_sheets' => WorkingSheet::STATUS_COMPLETED,
            ] as $key => $status) {
                $metrics[$key . '_period'] = $this->statusCount($sheetStatuses, $status);
            }
        }

        $attendance = DriverAttendance::query()->whereBetween('attendance_date', [$startDate, $endDate]);
        if ($role === User::ROLE_DRIVER) {
            $attendance->where('driver_id', $driverId);
        }
        $attendanceStatuses = $this->statusAggregates(clone $attendance);
        $metrics['pending_attendance_records_period'] = $this->statusCount($attendanceStatuses, DriverAttendance::STATUS_PENDING);
        $metrics['attendance_pending_period'] = $metrics['pending_attendance_records_period'];
        $metrics['attendance_missing_punch_period'] = $this->statusCount($attendanceStatuses, DriverAttendance::STATUS_MISSING_PUNCH);
        if ($role === User::ROLE_DRIVER) {
            $metrics['present_days_this_month_period'] = $this->statusCount($attendanceStatuses, DriverAttendance::STATUS_PRESENT)
                + $this->statusCount($attendanceStatuses, DriverAttendance::STATUS_ON_DUTY);
            $metrics['late_days_this_month_period'] = $this->statusCount($attendanceStatuses, DriverAttendance::STATUS_LATE);
        }

        $allowances = DriverAllowance::query()->whereBetween('created_at', $range);
        $expenses = DriverExpense::query()->whereBetween('created_at', $range);
        if ($role === User::ROLE_DRIVER) {
            $allowances->where('driver_id', $driverId);
            $expenses->where('driver_id', $driverId);
        }
        $allowanceStatuses = $this->statusAggregates(clone $allowances, 'status', 'amount');
        $metrics['pending_driver_allowances_period'] = $this->statusCount($allowanceStatuses, DriverAllowance::STATUS_PENDING);
        $metrics['approved_driver_allowances_period'] = $this->statusCount($allowanceStatuses, DriverAllowance::STATUS_APPROVED);
        $metrics['rejected_driver_allowances_period'] = $this->statusCount($allowanceStatuses, DriverAllowance::STATUS_REJECTED);
        $metrics['paid_driver_allowances_period'] = $this->statusCount($allowanceStatuses, DriverAllowance::STATUS_PAID);
        $metrics['pending_driver_allowance_amount_period'] = $this->statusSum($allowanceStatuses, DriverAllowance::STATUS_PENDING);
        $metrics['approved_driver_allowance_amount_period'] = $this->statusSum($allowanceStatuses, DriverAllowance::STATUS_APPROVED);
        $metrics['pending_allowances_period'] = $metrics['pending_driver_allowances_period'];
        $metrics['approved_allowances_period'] = $metrics['approved_driver_allowances_period'];
        $metrics['paid_allowances_period'] = $metrics['paid_driver_allowances_period'];
        $metrics['pending_allowance_amount_period'] = $metrics['pending_driver_allowance_amount_period'];
        $metrics['approved_allowance_amount_period'] = $metrics['approved_driver_allowance_amount_period'];

        $expenseStatuses = $this->statusAggregates(clone $expenses, 'status', 'amount');
        $metrics['pending_driver_expenses_period'] = $this->statusCount($expenseStatuses, DriverExpense::STATUS_PENDING);
        $metrics['approved_driver_expenses_period'] = $this->statusCount($expenseStatuses, DriverExpense::STATUS_APPROVED);
        $metrics['pending_expenses_period'] = $metrics['pending_driver_expenses_period'];
        $metrics['approved_expenses_period'] = $metrics['approved_driver_expenses_period'];
        $metrics['paid_expenses_period'] = $this->statusCount($expenseStatuses, DriverExpense::STATUS_PAID);
        $metrics['pending_driver_expense_amount_period'] = $this->statusSum($expenseStatuses, DriverExpense::STATUS_PENDING);
        $metrics['pending_expense_amount_period'] = $metrics['pending_driver_expense_amount_period'];
        $metrics['approved_expense_amount_period'] = $this->statusSum($expenseStatuses, DriverExpense::STATUS_APPROVED);

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_ACCOUNTANT, User::ROLE_DRIVER], true)) {
            $payroll = SalaryProcessing::query()->whereBetween('created_at', $range);
            if ($role === User::ROLE_DRIVER) {
                $payroll->where('driver_id', $driverId)->where('role', SalaryProcessing::ROLE_DRIVER);
            }
            $payrollStatuses = $this->statusAggregates(clone $payroll, 'status', 'net_salary');
            foreach ([
                'pending_payroll' => SalaryProcessing::STATUS_DRAFT,
                'processed_payroll' => SalaryProcessing::STATUS_PROCESSED,
                'approved_payroll' => SalaryProcessing::STATUS_APPROVED,
                'paid_payroll' => SalaryProcessing::STATUS_PAID,
                'cancelled_payroll' => SalaryProcessing::STATUS_CANCELLED,
            ] as $key => $status) {
                $metrics[$key . '_period'] = $this->statusCount($payrollStatuses, $status);
            }
            foreach ([
                'pending_payroll_net_amount' => SalaryProcessing::STATUS_DRAFT,
                'processed_payroll_net_amount' => SalaryProcessing::STATUS_PROCESSED,
                'approved_payroll_net_amount' => SalaryProcessing::STATUS_APPROVED,
                'paid_payroll_net_amount' => SalaryProcessing::STATUS_PAID,
            ] as $key => $status) {
                $metrics[$key . '_period'] = $this->statusSum($payrollStatuses, $status);
            }
            $metrics['payroll_reports_period'] = $metrics['processed_payroll_period']
                + $metrics['approved_payroll_period'] + $metrics['paid_payroll_period'];
        }

        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_ACCOUNTANT, User::ROLE_DRIVER], true)) {
            $salarySlips = SalarySlip::query()
                ->whereBetween('created_at', $range)
                ->whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED]);
            if ($role === User::ROLE_DRIVER) {
                $salarySlips->where('driver_id', $driverId)->where('role', SalarySlip::ROLE_DRIVER);
            }
            $salarySlipStatuses = $this->statusAggregates(clone $salarySlips, 'payment_status', 'net_salary');
            $metrics['salary_slips_period'] = array_sum(array_map(static fn ($row) => (int) $row->aggregate, $salarySlipStatuses));
            $metrics['unpaid_salary_slips_period'] = $this->statusCount($salarySlipStatuses, SalarySlip::PAYMENT_UNPAID);
            $metrics['partially_paid_salary_slips_period'] = $this->statusCount($salarySlipStatuses, SalarySlip::PAYMENT_PARTIAL);
            $metrics['paid_salary_slips_period'] = $this->statusCount($salarySlipStatuses, SalarySlip::PAYMENT_PAID);
            $metrics['unpaid_salary_slip_face_value_period'] = $this->statusSum($salarySlipStatuses, SalarySlip::PAYMENT_UNPAID);
            if ($role === User::ROLE_DRIVER) {
                $metrics['pending_salary_period'] = $metrics['unpaid_salary_slip_face_value_period'];
                $metrics['salary_processing_records_period'] = (clone $payroll)->count();
            }
        }

        if ($role === User::ROLE_ADMIN) {
            $metrics['user_history_records_period'] = UserHistory::query()->whereBetween('activity_time', $range)->count();
            $metrics['user_history_last_7_days_period'] = $metrics['user_history_records_period'];
            $metrics['image_metadata_records_period'] = ImageMeta::query()->whereBetween('created_at', $range)->count();
            $metrics['image_optimization_logs_period'] = ImageOptimizationLog::query()->whereBetween('created_at', $range)->count();
            $metrics['image_optimization_logs_last_7_days_period'] = $metrics['image_optimization_logs_period'];
        }

        return $metrics;
    }

    /** Return status counts and optional amount totals in one aggregate query. */
    protected function statusAggregates($query, string $statusColumn = 'status', ?string $sumColumn = null): array
    {
        $select = $statusColumn . ' as bucket_status, COUNT(*) as aggregate';
        if ($sumColumn !== null) {
            $select .= ', COALESCE(SUM(' . $sumColumn . '), 0) as sum_total';
        }

        return $query->selectRaw($select)->groupBy($statusColumn)->get()->keyBy('bucket_status')->all();
    }

    protected function statusCount(array $aggregates, string $status): int
    {
        return (int) ($aggregates[$status]->aggregate ?? 0);
    }

    protected function statusSum(array $aggregates, string $status): float
    {
        return (float) ($aggregates[$status]->sum_total ?? 0);
    }

    /** Return daily request/completion counts for the selected reporting window. */
    protected function buildTrendSeries(Carbon $startAt, Carbon $endAt, string $role, $user): array
    {
        $startDate = $startAt->toDateString();
        $endDate = $endAt->toDateString();
        $canSeeCompanyOperations = in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS], true);
        $travelByDate = collect();
        if ($canSeeCompanyOperations) {
            $travelByDate = TravelRequest::query()
                ->selectRaw('DATE(created_at) as bucket_date, COUNT(*) as aggregate')
                ->whereBetween('created_at', [$startAt, $endAt])
                ->groupBy('bucket_date')
                ->pluck('aggregate', 'bucket_date');
        }

        $completedDutyQuery = DutyAssignment::query()
            ->where('status', DutyAssignment::STATUS_COMPLETED)
            ->selectRaw('DATE(updated_at) as bucket_date, COUNT(*) as aggregate')
            ->whereBetween('updated_at', [$startAt, $endAt]);
        if ($role === User::ROLE_DRIVER) {
            // A driver may only see their own historical duty trend.
            $driverId = $this->getDriverId($user);
            if ($driverId) {
                $completedDutyQuery->where('driver_id', $driverId);
            } else {
                $completedDutyQuery->whereRaw('1 = 0');
            }
        } elseif ($role !== User::ROLE_ADMIN && $role !== User::ROLE_OPERATIONS) {
            // Accountant and other roles do not receive company operations data.
            $completedDutyQuery->whereRaw('1 = 0');
        }
        $completedByDate = $completedDutyQuery->groupBy('bucket_date')->pluck('aggregate', 'bucket_date');

        $daily = [];
        $cursor = $startAt->copy()->startOfDay();
        $lastDay = $endAt->copy()->startOfDay();
        while ($cursor->lte($lastDay)) {
            $key = $cursor->toDateString();
            $daily[] = [
                'date' => $key,
                'label' => $cursor->format('d M'),
                'travel_requests' => (int) ($travelByDate[$key] ?? 0),
                'completed_duties' => (int) ($completedByDate[$key] ?? 0),
            ];
            $cursor->addDay();
        }

        $fleet = [];
        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_OPERATIONS], true)) {
            $active = VehicleManagement::active()->count();
            $maintenance = VehicleManagement::maintenance()->count();
            $total = VehicleManagement::count();
            $fleet = [
                ['label' => 'Active / operational', 'value' => $active],
                ['label' => 'In maintenance', 'value' => $maintenance],
                ['label' => 'Other / inactive', 'value' => max(0, $total - $active - $maintenance)],
            ];
        }

        $payroll = [];
        if (in_array($role, [User::ROLE_ADMIN, User::ROLE_ACCOUNTANT], true)) {
            $payrollRecords = SalaryProcessing::query()
                ->whereIn('status', [SalaryProcessing::STATUS_PROCESSED, SalaryProcessing::STATUS_APPROVED, SalaryProcessing::STATUS_PAID])
                ->whereBetween('created_at', [$startAt, $endAt])
                ->get(['created_at', 'net_salary']);
            $payrollTotals = [];
            foreach ($payrollRecords as $record) {
                $monthKey = $record->created_at?->format('Y-m') ?: null;
                if ($monthKey !== null) {
                    $payrollTotals[$monthKey] = ($payrollTotals[$monthKey] ?? 0) + (float) $record->net_salary;
                }
            }
            $monthCursor = $startAt->copy()->startOfMonth();
            $lastMonth = $endAt->copy()->startOfMonth();
            while ($monthCursor->lte($lastMonth)) {
                $monthKey = $monthCursor->format('Y-m');
                $payroll[] = [
                    'period' => $monthKey,
                    'label' => $monthCursor->format('M Y'),
                    'net_amount' => round((float) ($payrollTotals[$monthKey] ?? 0), 2),
                ];
                $monthCursor->addMonth();
            }
        }

        return [
            'start' => $startDate,
            'end' => $endDate,
            'daily' => $daily,
            'fleet' => $fleet,
            'payroll' => $payroll,
        ];
    }

    /** A duty slip is overdue only when its scheduled day passed and it remains open/started. */
    protected function overdueDutySlipsCount(string $role, $user): int
    {
        $query = DutySlip::query()
            ->whereDate('duty_date', '<', today())
            ->whereIn('status', [DutySlip::STATUS_OPEN, DutySlip::STATUS_STARTED]);

        if ($role === User::ROLE_DRIVER) {
            $driverId = $this->getDriverId($user);
            if (!$driverId) {
                return 0;
            }
            $query->where('driver_id', $driverId);
        }

        return $query->count();
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    |
    | Admin can see company-wide statistics: master records, fleet, travel,
    | duty execution, attendance, payroll/claims, users, audit and image logs.
    | Soft-deleted models are excluded automatically where the model uses
    | Laravel's SoftDeletes trait.
    |
    */

    protected function adminDashboard(): array
    {
        return [
            // -----------------------------------------------------------------
            // Client master
            // -----------------------------------------------------------------
            'total_clients' => Client::count(),
            'active_clients' => Client::active()->count(),
            'inactive_clients' => Client::inactive()->count(),

            // -----------------------------------------------------------------
            // Driver master and compliance
            // -----------------------------------------------------------------
            'total_drivers' => Driver::count(),
            'active_drivers' => Driver::where('status', Driver::STATUS_ACTIVE)->count(),
            'drivers_on_leave' => Driver::where('status', Driver::STATUS_ON_LEAVE)->count(),
            'drivers_notice_period' => Driver::where('status', Driver::STATUS_NOTICE_PERIOD)->count(),
            'resigned_drivers' => Driver::where('status', Driver::STATUS_RESIGNED)->count(),
            'terminated_drivers' => Driver::where('status', Driver::STATUS_TERMINATED)->count(),
            'inactive_drivers' => Driver::where('status', Driver::STATUS_INACTIVE)->count(),
            'drivers_license_expired' => Driver::licenseExpired()->count(),
            'drivers_license_expiring_30_days' => Driver::licenseExpiringWithin(30)->count(),
            'drivers_documents_pending' => Driver::where('document_status', Driver::DOCUMENT_PENDING)->count(),
            'drivers_documents_rejected' => Driver::where('document_status', Driver::DOCUMENT_REJECTED)->count(),

            // -----------------------------------------------------------------
            // Fleet and vehicle master data
            // -----------------------------------------------------------------
            'total_vehicles' => VehicleManagement::count(),
            'active_vehicles' => VehicleManagement::active()->count(),
            'maintenance_vehicles' => VehicleManagement::maintenance()->count(),
            'vehicle_categories' => VehicleCategory::count(),
            'active_vehicle_categories' => VehicleCategory::active()->count(),
            'inactive_vehicle_categories' => VehicleCategory::inactive()->count(),
            'vehicle_types' => VehicleType::count(),
            'active_vehicle_types' => VehicleType::active()->count(),
            'inactive_vehicle_types' => VehicleType::where('status', false)->count(),
            'vehicle_prices' => VehiclePrice::count(),
            'vehicle_prices_effective_on_or_before_today' => VehiclePrice::whereDate('effective_date', '<=', today())->count(),
            'future_vehicle_prices' => VehiclePrice::whereDate('effective_date', '>', today())->count(),

            // -----------------------------------------------------------------
            // Travel requests
            // -----------------------------------------------------------------
            'travel_requests' => TravelRequest::count(),
            'pending_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_PENDING)->count(),
            'approved_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_APPROVED)->count(),
            'rejected_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_REJECTED)->count(),
            'assigned_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_ASSIGNED)->count(),
            'completed_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_COMPLETED)->count(),
            'cancelled_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_CANCELLED)->count(),

            // -----------------------------------------------------------------
            // Duty assignment lifecycle
            // -----------------------------------------------------------------
            'duty_assignments' => DutyAssignment::count(),
            'pending_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_PENDING)->count(),
            'assigned_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_ASSIGNED)->count(),
            'accepted_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_ACCEPTED)->count(),
            'started_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_STARTED)->count(),
            'rejected_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_REJECTED)->count(),
            'completed_duties' => DutyAssignment::where('status', DutyAssignment::STATUS_COMPLETED)->count(),
            'cancelled_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_CANCELLED)->count(),
            'today_duty_assignments' => DutyAssignment::whereDate('assigned_at', today())->count(),

            // -----------------------------------------------------------------
            // Duty slips
            // -----------------------------------------------------------------
            'pending_duty_slips' => DutySlip::where('status', DutySlip::STATUS_OPEN)->count(),
            'started_duty_slips' => DutySlip::where('status', DutySlip::STATUS_STARTED)->count(),
            'completed_duty_slips' => DutySlip::where('status', DutySlip::STATUS_COMPLETED)->count(),
            'cancelled_duty_slips' => DutySlip::where('status', DutySlip::STATUS_CANCELLED)->count(),

            // -----------------------------------------------------------------
            // Working sheets
            // -----------------------------------------------------------------
            'working_sheets' => WorkingSheet::count(),
            'draft_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_DRAFT)->count(),
            'submitted_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_SUBMITTED)->count(),
            'approved_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_APPROVED)->count(),
            'rejected_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_REJECTED)->count(),
            'completed_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_COMPLETED)->count(),
            'today_working_sheets' => WorkingSheet::whereDate('work_date', today())->count(),

            // -----------------------------------------------------------------
            // Attendance: current day and follow-up queue
            // -----------------------------------------------------------------
            'attendance_today' => DriverAttendance::whereDate('attendance_date', today())->count(),
            'present_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_PRESENT)->count(),
            'absent_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_ABSENT)->count(),
            'half_day_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_HALF_DAY)->count(),
            'on_duty_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_ON_DUTY)->count(),
            'late_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_LATE)->count(),
            'missing_punch_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_MISSING_PUNCH)->count(),
            'pending_attendance_records' => DriverAttendance::where('status', DriverAttendance::STATUS_PENDING)->count(),

            // -----------------------------------------------------------------
            // Allowance master and driver allowance claims
            // -----------------------------------------------------------------
            'allowances' => Allowance::count(),
            'active_allowances' => Allowance::active()->count(),
            'inactive_allowances' => Allowance::inactive()->count(),
            'pending_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_PENDING)->count(),
            'approved_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_APPROVED)->count(),
            'rejected_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_REJECTED)->count(),
            'paid_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_PAID)->count(),
            'cancelled_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_CANCELLED)->count(),
            'pending_driver_allowance_amount' => (float) DriverAllowance::where('status', DriverAllowance::STATUS_PENDING)->sum('amount'),
            'approved_driver_allowance_amount' => (float) DriverAllowance::where('status', DriverAllowance::STATUS_APPROVED)->sum('amount'),

            // -----------------------------------------------------------------
            // Expense master and driver expense claims
            // -----------------------------------------------------------------
            'expense_types' => Expense::count(),
            'active_expense_types' => Expense::active()->count(),
            'inactive_expense_types' => Expense::inactive()->count(),
            'pending_driver_expenses' => DriverExpense::where('status', DriverExpense::STATUS_PENDING)->count(),
            'approved_driver_expenses' => DriverExpense::where('status', DriverExpense::STATUS_APPROVED)->count(),
            'rejected_driver_expenses' => DriverExpense::where('status', DriverExpense::STATUS_REJECTED)->count(),
            'paid_driver_expenses' => DriverExpense::where('status', DriverExpense::STATUS_PAID)->count(),
            'cancelled_driver_expenses' => DriverExpense::where('status', DriverExpense::STATUS_CANCELLED)->count(),
            'pending_driver_expense_amount' => (float) DriverExpense::where('status', DriverExpense::STATUS_PENDING)->sum('amount'),
            'approved_driver_expense_amount' => (float) DriverExpense::where('status', DriverExpense::STATUS_APPROVED)->sum('amount'),

            // -----------------------------------------------------------------
            // Salary processing and salary slips
            // -----------------------------------------------------------------
            'pending_payroll' => $this->getPendingPayroll(),
            'processed_payroll' => $this->getProcessedPayroll(),
            'approved_payroll' => SalaryProcessing::where('status', SalaryProcessing::STATUS_APPROVED)->count(),
            'paid_payroll' => SalaryProcessing::where('status', SalaryProcessing::STATUS_PAID)->count(),
            'cancelled_payroll' => SalaryProcessing::where('status', SalaryProcessing::STATUS_CANCELLED)->count(),
            // No dedicated PayrollReport model was supplied; processed salary
            // batches are the available source for the existing report card.
            'payroll_reports' => SalaryProcessing::whereIn('status', [
                SalaryProcessing::STATUS_PROCESSED,
                SalaryProcessing::STATUS_APPROVED,
                SalaryProcessing::STATUS_PAID,
            ])->count(),
            'processed_payroll_net_amount' => (float) SalaryProcessing::where('status', SalaryProcessing::STATUS_PROCESSED)
                ->sum('net_salary'),
            'approved_payroll_net_amount' => (float) SalaryProcessing::where('status', SalaryProcessing::STATUS_APPROVED)
                ->sum('net_salary'),
            'paid_payroll_net_amount' => (float) SalaryProcessing::where('status', SalaryProcessing::STATUS_PAID)
                ->sum('net_salary'),
            'salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])->count(),
            'unpaid_salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_UNPAID)->count(),
            'partially_paid_salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_PARTIAL)->count(),
            'paid_salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_PAID)->count(),
            'unpaid_salary_slip_face_value' => (float) SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_UNPAID)->sum('net_salary'),

            // -----------------------------------------------------------------
            // User administration and audit trail
            // -----------------------------------------------------------------
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'inactive_users' => User::inactive()->count(),
            'admin_users' => User::where('role', User::ROLE_ADMIN)->count(),
            'operations_users' => User::where('role', User::ROLE_OPERATIONS)->count(),
            'accountant_users' => User::where('role', User::ROLE_ACCOUNTANT)->count(),
            'driver_users' => User::where('role', User::ROLE_DRIVER)->count(),
            'user_history_records' => UserHistory::count(),
            'user_history_last_7_days' => UserHistory::where('activity_time', '>=', now()->subDays(7))->count(),

            // -----------------------------------------------------------------
            // Image metadata and optimization logging (system monitoring)
            // -----------------------------------------------------------------
            'image_metadata_records' => ImageMeta::count(),
            'image_optimization_logs' => ImageOptimizationLog::count(),
            'image_optimization_logs_last_7_days' => ImageOptimizationLog::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | OPERATIONS DASHBOARD
    |--------------------------------------------------------------------------
    |
    | Operations metrics focus on vehicle readiness, request handling, duty
    | execution, working-sheet review and daily driver attendance. Payroll
    | totals and private salary details are intentionally not exposed here.
    |
    */

    protected function operationsDashboard(): array
    {
        return [
            // Client and driver readiness
            'total_clients' => Client::active()->count(),
            'active_drivers' => Driver::where('status', Driver::STATUS_ACTIVE)->count(),
            'drivers_on_leave' => Driver::where('status', Driver::STATUS_ON_LEAVE)->count(),
            'drivers_license_expiring_30_days' => Driver::licenseExpiringWithin(30)->count(),

            // Fleet readiness. VehicleManagement defines active and maintenance
            // scopes; the existing card's "available" label uses the active set.
            'total_vehicles' => VehicleManagement::count(),
            'available_vehicles' => VehicleManagement::active()->count(),
            'maintenance_vehicles' => VehicleManagement::maintenance()->count(),
            'vehicle_categories' => VehicleCategory::active()->count(),
            'vehicle_types' => VehicleType::active()->count(),

            // Travel request pipeline
            'travel_requests' => TravelRequest::count(),
            'pending_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_PENDING)->count(),
            'approved_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_APPROVED)->count(),
            'rejected_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_REJECTED)->count(),
            'assigned_travel_requests' => TravelRequest::where('status', TravelRequest::STATUS_ASSIGNED)->count(),

            // Duty assignment pipeline
            'pending_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_PENDING)->count(),
            'assigned_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_ASSIGNED)->count(),
            'accepted_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_ACCEPTED)->count(),
            'started_duty_assignments' => DutyAssignment::where('status', DutyAssignment::STATUS_STARTED)->count(),
            'completed_duties' => DutyAssignment::where('status', DutyAssignment::STATUS_COMPLETED)->count(),
            'today_duty_assignments' => DutyAssignment::whereDate('assigned_at', today())->count(),

            // Duty slips and working sheets
            'open_duty_slips' => DutySlip::where('status', DutySlip::STATUS_OPEN)->count(),
            'started_duty_slips' => DutySlip::where('status', DutySlip::STATUS_STARTED)->count(),
            'completed_duty_slips' => DutySlip::where('status', DutySlip::STATUS_COMPLETED)->count(),
            'working_sheets' => WorkingSheet::count(),
            'draft_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_DRAFT)->count(),
            'submitted_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_SUBMITTED)->count(),
            'approved_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_APPROVED)->count(),
            'completed_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_COMPLETED)->count(),

            // Daily attendance visibility
            'attendance_today' => DriverAttendance::whereDate('attendance_date', today())->count(),
            'present_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_PRESENT)->count(),
            'absent_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_ABSENT)->count(),
            'late_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_LATE)->count(),
            'missing_punch_today' => DriverAttendance::whereDate('attendance_date', today())
                ->where('status', DriverAttendance::STATUS_MISSING_PUNCH)->count(),
            'pending_attendance_records' => DriverAttendance::where('status', DriverAttendance::STATUS_PENDING)->count(),

            // Items waiting for operations review; no payment totals shown.
            'pending_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_PENDING)->count(),
            'pending_driver_expenses' => DriverExpense::where('status', DriverExpense::STATUS_PENDING)->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ACCOUNTANT DASHBOARD
    |--------------------------------------------------------------------------
    |
    | Uses salary_processing for payroll batches, salary_slips for issued salary
    | documents/payment state, and driver_allowances / driver_expenses for claims.
    | Partial salary slips are counted separately because the supplied model has
    | no paid_amount or balance_due field from which an exact balance can be read.
    |
    */

    protected function accountantDashboard(): array
    {
        return [
            // Payroll batch status
            'pending_payroll' => $this->getPendingPayroll(),
            'processed_payroll' => $this->getProcessedPayroll(),
            'approved_payroll' => SalaryProcessing::where('status', SalaryProcessing::STATUS_APPROVED)->count(),
            'paid_payroll' => SalaryProcessing::where('status', SalaryProcessing::STATUS_PAID)->count(),
            'cancelled_payroll' => SalaryProcessing::where('status', SalaryProcessing::STATUS_CANCELLED)->count(),
            'processed_payroll_net_amount' => (float) SalaryProcessing::where('status', SalaryProcessing::STATUS_PROCESSED)
                ->sum('net_salary'),
            'approved_payroll_net_amount' => (float) SalaryProcessing::where('status', SalaryProcessing::STATUS_APPROVED)
                ->sum('net_salary'),
            'paid_payroll_net_amount' => (float) SalaryProcessing::where('status', SalaryProcessing::STATUS_PAID)
                ->sum('net_salary'),
            'pending_payroll_net_amount' => (float) SalaryProcessing::where('status', SalaryProcessing::STATUS_DRAFT)->sum('net_salary'),

            // Salary slip lifecycle and payments
            'salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])->count(),
            'unpaid_salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_UNPAID)->count(),
            'partially_paid_salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_PARTIAL)->count(),
            'paid_salary_slips' => SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_PAID)->count(),
            'unpaid_salary_slip_face_value' => (float) SalarySlip::whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED])
                ->where('payment_status', SalarySlip::PAYMENT_UNPAID)->sum('net_salary'),

            // Allowance master and driver claims
            'allowances' => Allowance::count(),
            'active_allowances' => Allowance::active()->count(),
            'inactive_allowances' => Allowance::inactive()->count(),
            'pending_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_PENDING)->count(),
            'approved_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_APPROVED)->count(),
            'rejected_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_REJECTED)->count(),
            'paid_driver_allowances' => DriverAllowance::where('status', DriverAllowance::STATUS_PAID)->count(),
            'pending_driver_allowance_amount' => (float) DriverAllowance::where('status', DriverAllowance::STATUS_PENDING)->sum('amount'),
            'approved_driver_allowance_amount' => (float) DriverAllowance::where('status', DriverAllowance::STATUS_APPROVED)->sum('amount'),

            // Expense master and driver claims
            'active_expense_types' => Expense::active()->count(),
            'pending_expenses' => DriverExpense::where('status', DriverExpense::STATUS_PENDING)->count(),
            'approved_expenses' => DriverExpense::where('status', DriverExpense::STATUS_APPROVED)->count(),
            'rejected_expenses' => DriverExpense::where('status', DriverExpense::STATUS_REJECTED)->count(),
            'paid_expenses' => DriverExpense::where('status', DriverExpense::STATUS_PAID)->count(),
            'pending_expense_amount' => (float) DriverExpense::where('status', DriverExpense::STATUS_PENDING)->sum('amount'),
            'approved_expense_amount' => (float) DriverExpense::where('status', DriverExpense::STATUS_APPROVED)->sum('amount'),

            // Payroll preparation: attendance and working-sheet approvals
            'active_drivers_for_payroll' => Driver::where('status', Driver::STATUS_ACTIVE)->count(),
            'attendance_pending' => DriverAttendance::where('status', DriverAttendance::STATUS_PENDING)->count(),
            'attendance_missing_punch' => DriverAttendance::where('status', DriverAttendance::STATUS_MISSING_PUNCH)->count(),
            'submitted_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_SUBMITTED)->count(),
            'approved_working_sheets' => WorkingSheet::where('status', WorkingSheet::STATUS_APPROVED)->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER DASHBOARD
    |--------------------------------------------------------------------------
    |
    | Driver-specific results are filtered by the Driver profile linked to the
    | authenticated user. WorkingSheet records are scoped through dutySlip
    | because working_sheets does not contain a driver_id column.
    |
    */

    protected function driverDashboard($user): array
    {
        $driverId = $this->getDriverId($user);

        // No matching driver profile: return a complete zero-valued structure.
        // This also prevents NULL driver_id filters from accidentally showing
        // another record or producing confusing partial dashboard values.
        if (!$driverId) {
            return $this->emptyDriverDashboard();
        }

        $salarySlipBase = SalarySlip::where('driver_id', $driverId)
            ->where('role', SalarySlip::ROLE_DRIVER)
            ->whereIn('status', [SalarySlip::STATUS_GENERATED, SalarySlip::STATUS_ISSUED]);

        $latestSalary = SalaryProcessing::where('driver_id', $driverId)
            ->where('role', SalaryProcessing::ROLE_DRIVER)
            ->whereIn('status', [
                SalaryProcessing::STATUS_PROCESSED,
                SalaryProcessing::STATUS_APPROVED,
                SalaryProcessing::STATUS_PAID,
            ])
            ->orderByDesc('salary_year')
            ->orderByDesc('salary_month')
            ->first();

        return [
            // Existing Blade card keys
            'assigned_duties' => DutyAssignment::where('driver_id', $driverId)->count(),
            'today_duties' => DutyAssignment::where('driver_id', $driverId)
                ->whereDate('assigned_at', today())->count(),
            'open_duty_slips' => DutySlip::where('driver_id', $driverId)
                ->where('status', DutySlip::STATUS_OPEN)->count(),
            'completed_duties' => DutyAssignment::where('driver_id', $driverId)
                ->where('status', DutyAssignment::STATUS_COMPLETED)->count(),
            'working_sheets' => WorkingSheet::whereHas('dutySlip', function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->count(),
            'current_salary' => round((float) ($latestSalary?->net_salary ?? 0), 2),
            'pending_salary' => round((float) (clone $salarySlipBase)
                ->where('payment_status', SalarySlip::PAYMENT_UNPAID)->sum('net_salary'), 2),

            // Duty and working-sheet workflow
            'pending_duty_assignments' => DutyAssignment::where('driver_id', $driverId)
                ->where('status', DutyAssignment::STATUS_PENDING)->count(),
            'assigned_duty_assignments' => DutyAssignment::where('driver_id', $driverId)
                ->where('status', DutyAssignment::STATUS_ASSIGNED)->count(),
            'accepted_duty_assignments' => DutyAssignment::where('driver_id', $driverId)
                ->where('status', DutyAssignment::STATUS_ACCEPTED)->count(),
            'started_duty_assignments' => DutyAssignment::where('driver_id', $driverId)
                ->where('status', DutyAssignment::STATUS_STARTED)->count(),
            'started_duty_slips' => DutySlip::where('driver_id', $driverId)
                ->where('status', DutySlip::STATUS_STARTED)->count(),
            'completed_duty_slips' => DutySlip::where('driver_id', $driverId)
                ->where('status', DutySlip::STATUS_COMPLETED)->count(),
            'draft_working_sheets' => WorkingSheet::whereHas('dutySlip', function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->where('status', WorkingSheet::STATUS_DRAFT)->count(),
            'submitted_working_sheets' => WorkingSheet::whereHas('dutySlip', function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->where('status', WorkingSheet::STATUS_SUBMITTED)->count(),
            'approved_working_sheets' => WorkingSheet::whereHas('dutySlip', function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->where('status', WorkingSheet::STATUS_APPROVED)->count(),
            'rejected_working_sheets' => WorkingSheet::whereHas('dutySlip', function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->where('status', WorkingSheet::STATUS_REJECTED)->count(),

            // Attendance. A partial day is counted separately; only present and
            // on-duty statuses are included in the full present-day metric.
            'attendance_today' => DriverAttendance::where('driver_id', $driverId)
                ->whereDate('attendance_date', today())->count(),
            'present_today' => DriverAttendance::where('driver_id', $driverId)
                ->whereDate('attendance_date', today())
                ->whereIn('status', [DriverAttendance::STATUS_PRESENT, DriverAttendance::STATUS_ON_DUTY])->count(),
            'present_days_this_month' => DriverAttendance::where('driver_id', $driverId)
                ->whereBetween('attendance_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->whereIn('status', [DriverAttendance::STATUS_PRESENT, DriverAttendance::STATUS_ON_DUTY])->count(),
            'half_days_this_month' => DriverAttendance::where('driver_id', $driverId)
                ->whereBetween('attendance_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->where('status', DriverAttendance::STATUS_HALF_DAY)->count(),
            'late_days_this_month' => DriverAttendance::where('driver_id', $driverId)
                ->whereBetween('attendance_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->where('status', DriverAttendance::STATUS_LATE)->count(),
            'missing_punch_records' => DriverAttendance::where('driver_id', $driverId)
                ->where('status', DriverAttendance::STATUS_MISSING_PUNCH)->count(),

            // Driver-specific allowance claims
            'pending_allowances' => DriverAllowance::where('driver_id', $driverId)
                ->where('status', DriverAllowance::STATUS_PENDING)->count(),
            'approved_allowances' => DriverAllowance::where('driver_id', $driverId)
                ->where('status', DriverAllowance::STATUS_APPROVED)->count(),
            'paid_allowances' => DriverAllowance::where('driver_id', $driverId)
                ->where('status', DriverAllowance::STATUS_PAID)->count(),
            'pending_allowance_amount' => (float) DriverAllowance::where('driver_id', $driverId)
                ->where('status', DriverAllowance::STATUS_PENDING)->sum('amount'),
            'approved_allowance_amount' => (float) DriverAllowance::where('driver_id', $driverId)
                ->where('status', DriverAllowance::STATUS_APPROVED)->sum('amount'),

            // Driver-specific expense claims
            'pending_expenses' => DriverExpense::where('driver_id', $driverId)
                ->where('status', DriverExpense::STATUS_PENDING)->count(),
            'approved_expenses' => DriverExpense::where('driver_id', $driverId)
                ->where('status', DriverExpense::STATUS_APPROVED)->count(),
            'paid_expenses' => DriverExpense::where('driver_id', $driverId)
                ->where('status', DriverExpense::STATUS_PAID)->count(),
            'pending_expense_amount' => (float) DriverExpense::where('driver_id', $driverId)
                ->where('status', DriverExpense::STATUS_PENDING)->sum('amount'),
            'approved_expense_amount' => (float) DriverExpense::where('driver_id', $driverId)
                ->where('status', DriverExpense::STATUS_APPROVED)->sum('amount'),

            // Salary slip payment states. For partially paid slips, only the
            // count and face value are available; no outstanding balance field
            // exists in the supplied SalarySlip model.
            'salary_processing_records' => SalaryProcessing::where('driver_id', $driverId)
                ->where('role', SalaryProcessing::ROLE_DRIVER)->count(),
            'salary_slips' => (clone $salarySlipBase)->count(),
            'unpaid_salary_slips' => (clone $salarySlipBase)
                ->where('payment_status', SalarySlip::PAYMENT_UNPAID)->count(),
            'partially_paid_salary_slips' => (clone $salarySlipBase)
                ->where('payment_status', SalarySlip::PAYMENT_PARTIAL)->count(),
            'paid_salary_slips' => (clone $salarySlipBase)
                ->where('payment_status', SalarySlip::PAYMENT_PAID)->count(),
            'partially_paid_salary_slip_face_value' => (float) (clone $salarySlipBase)
                ->where('payment_status', SalarySlip::PAYMENT_PARTIAL)->sum('net_salary'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY DRIVER DASHBOARD
    |--------------------------------------------------------------------------
    |
    | Always return the same keys for a driver account, even if its profile is
    | not linked yet. This avoids undefined indexes in the Blade view.
    |
    */

    protected function emptyDriverDashboard(): array
    {
        return [
            'assigned_duties' => 0,
            'today_duties' => 0,
            'open_duty_slips' => 0,
            'completed_duties' => 0,
            'working_sheets' => 0,
            'current_salary' => 0.0,
            'pending_salary' => 0.0,
            'pending_duty_assignments' => 0,
            'assigned_duty_assignments' => 0,
            'accepted_duty_assignments' => 0,
            'started_duty_assignments' => 0,
            'started_duty_slips' => 0,
            'completed_duty_slips' => 0,
            'draft_working_sheets' => 0,
            'submitted_working_sheets' => 0,
            'approved_working_sheets' => 0,
            'rejected_working_sheets' => 0,
            'attendance_today' => 0,
            'present_today' => 0,
            'present_days_this_month' => 0,
            'half_days_this_month' => 0,
            'late_days_this_month' => 0,
            'missing_punch_records' => 0,
            'pending_allowances' => 0,
            'approved_allowances' => 0,
            'paid_allowances' => 0,
            'pending_allowance_amount' => 0.0,
            'approved_allowance_amount' => 0.0,
            'pending_expenses' => 0,
            'approved_expenses' => 0,
            'paid_expenses' => 0,
            'pending_expense_amount' => 0.0,
            'approved_expense_amount' => 0.0,
            'salary_processing_records' => 0,
            'salary_slips' => 0,
            'unpaid_salary_slips' => 0,
            'partially_paid_salary_slips' => 0,
            'paid_salary_slips' => 0,
            'partially_paid_salary_slip_face_value' => 0.0,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PAYROLL HELPERS
    |--------------------------------------------------------------------------
    |
    | Draft salary-processing batches are the pending queue. The processed
    | metric includes processed, approved and paid batches, excluding drafts
    | and cancelled batches. Separate keys above expose each stage individually.
    |
    */

    protected function getPendingPayroll(): int
    {
        return SalaryProcessing::where('status', SalaryProcessing::STATUS_DRAFT)->count();
    }

    protected function getProcessedPayroll(): int
    {
        return SalaryProcessing::where(
            'status',
            SalaryProcessing::STATUS_PROCESSED
        )->count();
    }

    /*
    |--------------------------------------------------------------------------
    | DRIVER PROFILE MAPPING
    |--------------------------------------------------------------------------
    |
    | Driver::user() and User::driver() both use drivers.user_id -> users.id.
    | Keep the query consistent with that explicit relationship.
    |
    */

    protected function getDriverId($user): ?int
    {
        $driverId = Driver::where('user_id', $user->id)->value('id');

        return $driverId === null ? null : (int) $driverId;
    }
}
