<?php

namespace App\Services\Dashboard;

use App\Models\Client;
use App\Models\Driver;
use App\Models\VehicleManagement;
use App\Models\TravelRequest;
use App\Models\DutyAssignment;
use App\Models\DutySlip;
use App\Models\WorkingSheet;

class DashboardService
{
    /**
     * Get dashboard statistics according to user role.
     *
     * @param object $user
     * @return array
     */
    public function getDashboardData($user): array
    {
        return match ($user->role) {

            'admin' => $this->adminDashboard(),

            'operations' => $this->operationsDashboard(),

            'accountant' => $this->accountantDashboard(),

            'driver' => $this->driverDashboard($user),

            default => [],
        };
    }


    /**
     * =========================================================
     * ADMIN DASHBOARD
     * =========================================================
     */
    protected function adminDashboard(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Masters
            |--------------------------------------------------------------------------
            */

            'total_clients' => Client::Where('status', '1')->count(), 

            'total_drivers' => Driver::Where('status', 'active')->count(),

            'total_vehicles' => VehicleManagement::count(),

            'active_vehicles' => VehicleManagement::where(
                'status',
                'active'
            )->count(),


            /*
            |--------------------------------------------------------------------------
            | Operations
            |--------------------------------------------------------------------------
            */

            'travel_requests' => TravelRequest::count(),

            'duty_assignments' => DutyAssignment::count(),

            'pending_duty_slips' => DutySlip::whereIn(
                'status',
                ['pending', 'open']
            )->count(),

            'working_sheets' => WorkingSheet::count(),


            /*
            |--------------------------------------------------------------------------
            | Payroll
            |--------------------------------------------------------------------------
            */

            'pending_payroll' => $this->getPendingPayroll(),

            'payroll_processed' => $this->getProcessedPayroll(),

        ];
    }


    /**
     * =========================================================
     * OPERATIONS DASHBOARD
     * =========================================================
     */
    protected function operationsDashboard(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Masters
            |--------------------------------------------------------------------------
            */

            'total_clients' => Client::Where('status', '1')->count(),

            'active_drivers' => Driver::where(
                'status',
                'active'
            )->count(),

            'available_vehicles' => VehicleManagement::where(
                'status',
                'active'
            )->count(),


            /*
            |--------------------------------------------------------------------------
            | Operations
            |--------------------------------------------------------------------------
            */
            'travel_requests' => TravelRequest::count(),

            'pending_duty_assignments' => DutyAssignment::where(
                'status',
                'pending'
            )->count(),

            'open_duty_slips' => DutySlip::whereIn(
                'status',
                ['pending', 'started']
            )->count(),

            'working_sheets' => WorkingSheet::count(),

            'completed_duties' => DutyAssignment::where(
                'status',
                'completed'
            )->count(),

        ];
    }


    /**
     * =========================================================
     * ACCOUNTANT DASHBOARD
     * =========================================================
     */
    protected function accountantDashboard(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Payroll
            |--------------------------------------------------------------------------
            */

            'pending_payroll' => $this->getPendingPayroll(),

            'processed_payroll' => $this->getProcessedPayroll(),


            /*
            |--------------------------------------------------------------------------
            | Allowances
            |--------------------------------------------------------------------------
            */

            'allowances' => $this->getAllowanceCount(),


            /*
            |--------------------------------------------------------------------------
            | Expenses
            |--------------------------------------------------------------------------
            */

            'pending_expenses' => $this->getPendingExpenseCount(),


            /*
            |--------------------------------------------------------------------------
            | Salary
            |--------------------------------------------------------------------------
            */

            'salary_slips' => $this->getSalarySlipCount(),

            'payroll_reports' => $this->getPayrollReportCount(),

        ];
    }


    /**
     * =========================================================
     * DRIVER DASHBOARD
     * =========================================================
     */
    protected function driverDashboard($user): array
    {
        /*
        |--------------------------------------------------------------------------
        | Driver Mapping
        |--------------------------------------------------------------------------
        |
        | Driver login ko Driver Management record ke saath map karna
        | project ke actual relationship/column ke according adjust hoga.
        |
        */

        $driverId = $this->getDriverId($user);


        return [

            /*
            |--------------------------------------------------------------------------
            | Duties
            |--------------------------------------------------------------------------
            */

            'assigned_duties' => DutyAssignment::where(
                'driver_id',
                $driverId
            )->count(),

            'today_duties' => DutyAssignment::where(
                'driver_id',
                $driverId
            )
                ->whereDate('assigned_at', today())
                ->count(),

            'open_duty_slips' => DutySlip::where(
                'duty_assignment_id',
                $driverId
            )
                ->whereIn('status', ['pending', 'started'])
                ->count(),

            'completed_duties' => DutyAssignment::where(
                'driver_id',
                $driverId
            )
                ->where('status', 'completed')
                ->count(),

            'working_sheets' => WorkingSheet::where(
                'duty_slip_id',
                $driverId
            )->count(),


            /*
            |--------------------------------------------------------------------------
            | Salary
            |--------------------------------------------------------------------------
            */

            'current_salary' => $this->getDriverSalary($driverId),

            'pending_salary' => $this->getDriverPendingSalary($driverId),

        ];
    }


    /**
     * =========================================================
     * PAYROLL HELPERS
     * =========================================================
     */

    protected function getPendingPayroll(): int
    {
        /*
        |--------------------------------------------------------------------------
        | Payroll model/table available hone ke baad yahan actual query
        | connect karni hai.
        |--------------------------------------------------------------------------
        */

        return 0;
    }


    protected function getProcessedPayroll(): int
    {
        return 0;
    }


    /**
     * =========================================================
     * ALLOWANCE
     * =========================================================
     */

    protected function getAllowanceCount(): int
    {
        return 0;
    }


    /**
     * =========================================================
     * EXPENSE
     * =========================================================
     */

    protected function getPendingExpenseCount(): int
    {
        return 0;
    }


    /**
     * =========================================================
     * SALARY SLIPS
     * =========================================================
     */

    protected function getSalarySlipCount(): int
    {
        return 0;
    }


    /**
     * =========================================================
     * PAYROLL REPORTS
     * =========================================================
     */

    protected function getPayrollReportCount(): int
    {
        return 0;
    }


    /**
     * =========================================================
     * DRIVER ID
     * =========================================================
     */

    protected function getDriverId($user): ?int
    {
        /*
        |--------------------------------------------------------------------------
        | Recommended:
        | users table ke driver user ko driver_management ke user_id se map karein.
        |--------------------------------------------------------------------------
        */

        return Driver::where(
            'user_id',
            $user->id
        )->value('id');
    }


    /**
     * =========================================================
     * DRIVER SALARY
     * =========================================================
     */

    protected function getDriverSalary(?int $driverId): float
    {
        if (!$driverId) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Actual salary column/model ke according query add karni hai.
        |--------------------------------------------------------------------------
        */

        return 0;
    }


    protected function getDriverPendingSalary(?int $driverId): float
    {
        if (!$driverId) {
            return 0;
        }

        return 0;
    }
}