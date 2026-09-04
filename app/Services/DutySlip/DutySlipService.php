<?php

namespace App\Services\DutySlip;

use App\Models\DutySlip;
use App\Models\DriverAllowance;
use App\Models\DriverExpense;
use App\Services\FileUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DutySlipService
{
    /*
    |--------------------------------------------------------------------------
    | File Upload Service
    |--------------------------------------------------------------------------
    */

    protected FileUploadService $fileUploadService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        FileUploadService $fileUploadService
    ) {
        $this->fileUploadService = $fileUploadService;
    }


    /*
    |--------------------------------------------------------------------------
    | GET DUTY SLIPS
    |--------------------------------------------------------------------------
    */

    public function getDutySlips(
        int $perPage = 20
    ): LengthAwarePaginator {

        return DutySlip::query()
            ->with([
                'dutyAssignment',
                'createdBy',
                'updatedBy',
                'workingSheet',
                'driverAllowances.allowance',
                'driverExpenses.expense',
            ])
            ->latest('id')
            ->paginate($perPage);
    }


    /*
    |--------------------------------------------------------------------------
    | FIND DUTY SLIP
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): DutySlip {

        return DutySlip::query()
            ->with([
                'dutyAssignment',
                'createdBy',
                'updatedBy',
                'workingSheet',
                'driverAllowances.allowance',
                'driverAllowances.driver',
                'driverExpenses.expense',
                'driverExpenses.driver',
            ])
            ->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data
    ): DutySlip {

        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Separate Child Data
            |--------------------------------------------------------------------------
            */

            $allowances =
                $data['allowances'] ?? [];

            $expenses =
                $data['expenses'] ?? [];


            /*
            |--------------------------------------------------------------------------
            | Duty Slip Front / Back Files
            |--------------------------------------------------------------------------
            */

            $frontFile =
                $data['duty_slip_front_file'] ?? null;

            $backFile =
                $data['duty_slip_back_file'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | Remove Child Data
            |--------------------------------------------------------------------------
            */

            unset(
                $data['allowances'],
                $data['expenses']
            );


            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

            $data['created_by'] =
                Auth::id();


            /*
            |--------------------------------------------------------------------------
            | Default Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? DutySlip::STATUS_OPEN;


            /*
            |--------------------------------------------------------------------------
            | Upload Duty Slip Front
            |--------------------------------------------------------------------------
            */

            if (
                $frontFile instanceof UploadedFile
            ) {

                $data['duty_slip_front_file'] =
                    $this->fileUploadService->upload(
                        $frontFile,
                        'duty-slip/front'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Duty Slip Back
            |--------------------------------------------------------------------------
            */

            if (
                $backFile instanceof UploadedFile
            ) {

                $data['duty_slip_back_file'] =
                    $this->fileUploadService->upload(
                        $backFile,
                        'duty-slip/back'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Create Duty Slip
            |--------------------------------------------------------------------------
            */

            $dutySlip =
                DutySlip::create($data);


            /*
            |--------------------------------------------------------------------------
            | Save Allowances
            |--------------------------------------------------------------------------
            */

            $this->syncAllowances(
                $dutySlip,
                $allowances
            );


            /*
            |--------------------------------------------------------------------------
            | Save Expenses
            |--------------------------------------------------------------------------
            */

            $this->syncExpenses(
                $dutySlip,
                $expenses
            );


            /*
            |--------------------------------------------------------------------------
            | Return Fresh Model
            |--------------------------------------------------------------------------
            */

            return $dutySlip->fresh([
                'dutyAssignment',
                'createdBy',
                'updatedBy',
                'workingSheet',
                'driverAllowances.allowance',
                'driverExpenses.expense',
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        DutySlip $dutySlip,
        array $data
    ): DutySlip {

        return DB::transaction(function () use (
            $dutySlip,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | Separate Child Data
            |--------------------------------------------------------------------------
            */

            $allowances =
                $data['allowances'] ?? [];

            $expenses =
                $data['expenses'] ?? [];


            /*
            |--------------------------------------------------------------------------
            | New Duty Slip Front / Back Files
            |--------------------------------------------------------------------------
            */

            $newFrontFile =
                $data['duty_slip_front_file'] ?? null;

            $newBackFile =
                $data['duty_slip_back_file'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | Remove Child Data
            |--------------------------------------------------------------------------
            */

            unset(
                $data['allowances'],
                $data['expenses']
            );


            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $data['updated_by'] =
                Auth::id();


            /*
            |--------------------------------------------------------------------------
            | Upload New Front File
            |--------------------------------------------------------------------------
            */

            if (
                $newFrontFile instanceof UploadedFile
            ) {

                $newFrontFilePath =
                    $this->fileUploadService->upload(
                        $newFrontFile,
                        'duty-slip/front'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Old Front File
                |--------------------------------------------------------------------------
                */

                if (
                    !empty(
                        $dutySlip->duty_slip_front_file
                    )
                ) {

                    $this->fileUploadService->delete(
                        $dutySlip->duty_slip_front_file
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Set New Front File
                |--------------------------------------------------------------------------
                */

                $data['duty_slip_front_file'] =
                    $newFrontFilePath;
            }


            /*
            |--------------------------------------------------------------------------
            | Upload New Back File
            |--------------------------------------------------------------------------
            */

            if (
                $newBackFile instanceof UploadedFile
            ) {

                $newBackFilePath =
                    $this->fileUploadService->upload(
                        $newBackFile,
                        'duty-slip/back'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Old Back File
                |--------------------------------------------------------------------------
                */

                if (
                    !empty(
                        $dutySlip->duty_slip_back_file
                    )
                ) {

                    $this->fileUploadService->delete(
                        $dutySlip->duty_slip_back_file
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Set New Back File
                |--------------------------------------------------------------------------
                */

                $data['duty_slip_back_file'] =
                    $newBackFilePath;
            }


            /*
            |--------------------------------------------------------------------------
            | Update Duty Slip
            |--------------------------------------------------------------------------
            */

            $dutySlip->update($data);


            /*
            |--------------------------------------------------------------------------
            | Sync Allowances
            |--------------------------------------------------------------------------
            */

            $this->syncAllowances(
                $dutySlip,
                $allowances
            );


            /*
            |--------------------------------------------------------------------------
            | Sync Expenses
            |--------------------------------------------------------------------------
            */

            $this->syncExpenses(
                $dutySlip,
                $expenses
            );


            /*
            |--------------------------------------------------------------------------
            | Return Fresh Model
            |--------------------------------------------------------------------------
            */

            return $dutySlip->fresh([
                'dutyAssignment',
                'createdBy',
                'updatedBy',
                'workingSheet',
                'driverAllowances.allowance',
                'driverExpenses.expense',
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | SYNC ALLOWANCES
    |--------------------------------------------------------------------------
    */

    protected function syncAllowances(
        DutySlip $dutySlip,
        array $allowances
    ): void {

        $existingIds = [];


        foreach ($allowances as $allowance) {

            /*
            |--------------------------------------------------------------------------
            | Ignore Empty Rows
            |--------------------------------------------------------------------------
            */

            if (
                empty($allowance['allowance_id'])
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $quantity =
                isset($allowance['quantity'])
                && $allowance['quantity'] !== ''
                    ? (float) $allowance['quantity']
                    : 1;


            /*
            |--------------------------------------------------------------------------
            | Rate
            |--------------------------------------------------------------------------
            */

            $rate =
                isset($allowance['rate'])
                && $allowance['rate'] !== ''
                    ? (float) $allowance['rate']
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $amount =
                round(
                    $quantity * $rate,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            $driverId =
                $allowance['driver_id']
                ?? $this->getDriverId(
                    $dutySlip
                );


            /*
            |--------------------------------------------------------------------------
            | Existing Record
            |--------------------------------------------------------------------------
            */

            $record = null;


            if (
                !empty($allowance['id'])
            ) {

                $record =
                    DriverAllowance::query()
                        ->where(
                            'duty_slip_id',
                            $dutySlip->id
                        )
                        ->where(
                            'id',
                            $allowance['id']
                        )
                        ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | Update Existing
            |--------------------------------------------------------------------------
            */

            if ($record) {

                $record->update([

                    'driver_id' =>
                        $driverId,

                    'allowance_id' =>
                        $allowance['allowance_id'],

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $allowance['remarks'] ?? null,

                    'status' =>
                        $allowance['status']
                        ?? DriverAllowance::STATUS_PENDING,

                    'updated_by' =>
                        Auth::id(),

                ]);


                $existingIds[] =
                    $record->id;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Create New
            |--------------------------------------------------------------------------
            */

            $record =
                DriverAllowance::create([

                    'driver_id' =>
                        $driverId,

                    'duty_slip_id' =>
                        $dutySlip->id,

                    'allowance_id' =>
                        $allowance['allowance_id'],

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $allowance['remarks'] ?? null,

                    'status' =>
                        $allowance['status']
                        ?? DriverAllowance::STATUS_PENDING,

                    'created_by' =>
                        Auth::id(),

                    'updated_by' =>
                        Auth::id(),

                ]);


            $existingIds[] =
                $record->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Removed Rows
        |--------------------------------------------------------------------------
        */

        $query =
            DriverAllowance::query()
                ->where(
                    'duty_slip_id',
                    $dutySlip->id
                );


        if (
            !empty($existingIds)
        ) {

            $query->whereNotIn(
                'id',
                $existingIds
            );

        }


        $query->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | SYNC EXPENSES
    |--------------------------------------------------------------------------
    */

    protected function syncExpenses(
        DutySlip $dutySlip,
        array $expenses
    ): void {

        $existingIds = [];


        foreach ($expenses as $expense) {

            /*
            |--------------------------------------------------------------------------
            | Ignore Empty Rows
            |--------------------------------------------------------------------------
            */

            if (
                empty($expense['expense_id'])
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $quantity =
                isset($expense['quantity'])
                && $expense['quantity'] !== ''
                    ? (float) $expense['quantity']
                    : 1;


            /*
            |--------------------------------------------------------------------------
            | Rate
            |--------------------------------------------------------------------------
            */

            $rate =
                isset($expense['rate'])
                && $expense['rate'] !== ''
                    ? (float) $expense['rate']
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $amount =
                round(
                    $quantity * $rate,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            $driverId =
                $expense['driver_id']
                ?? $this->getDriverId(
                    $dutySlip
                );


            /*
            |--------------------------------------------------------------------------
            | Existing Record
            |--------------------------------------------------------------------------
            */

            $record = null;


            if (
                !empty($expense['id'])
            ) {

                $record =
                    DriverExpense::query()
                        ->where(
                            'duty_slip_id',
                            $dutySlip->id
                        )
                        ->where(
                            'id',
                            $expense['id']
                        )
                        ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | Update Existing
            |--------------------------------------------------------------------------
            */

            if ($record) {

                $record->update([

                    'driver_id' =>
                        $driverId,

                    'expense_id' =>
                        $expense['expense_id'],

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $expense['remarks'] ?? null,

                    'status' =>
                        $expense['status']
                        ?? DriverExpense::STATUS_PENDING,

                    'updated_by' =>
                        Auth::id(),

                ]);


                $existingIds[] =
                    $record->id;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Create New
            |--------------------------------------------------------------------------
            */

            $record =
                DriverExpense::create([

                    'driver_id' =>
                        $driverId,

                    'duty_slip_id' =>
                        $dutySlip->id,

                    'expense_id' =>
                        $expense['expense_id'],

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $expense['remarks'] ?? null,

                    'status' =>
                        $expense['status']
                        ?? DriverExpense::STATUS_PENDING,

                    'created_by' =>
                        Auth::id(),

                    'updated_by' =>
                        Auth::id(),

                ]);


            $existingIds[] =
                $record->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Removed Rows
        |--------------------------------------------------------------------------
        */

        $query =
            DriverExpense::query()
                ->where(
                    'duty_slip_id',
                    $dutySlip->id
                );


        if (
            !empty($existingIds)
        ) {

            $query->whereNotIn(
                'id',
                $existingIds
            );

        }


        $query->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | GET DRIVER ID
    |--------------------------------------------------------------------------
    */

    protected function getDriverId(
        DutySlip $dutySlip
    ): ?int {

        /*
        |--------------------------------------------------------------------------
        | Duty Assignment Driver
        |--------------------------------------------------------------------------
        */

        $dutySlip->loadMissing(
            'dutyAssignment'
        );


        return $dutySlip
            ->dutyAssignment
            ->driver_id
            ?? null;
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        DutySlip $dutySlip
    ): bool {

        return DB::transaction(
            function () use ($dutySlip) {

                /*
                |--------------------------------------------------------------------------
                | Delete Child Records
                |--------------------------------------------------------------------------
                */

                $dutySlip
                    ->driverAllowances()
                    ->delete();


                $dutySlip
                    ->driverExpenses()
                    ->delete();


                /*
                |--------------------------------------------------------------------------
                | Delete Duty Slip Front File
                |--------------------------------------------------------------------------
                */

                if (
                    !empty(
                        $dutySlip->duty_slip_front_file
                    )
                ) {

                    $this->fileUploadService->delete(
                        $dutySlip->duty_slip_front_file
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Delete Duty Slip Back File
                |--------------------------------------------------------------------------
                */

                if (
                    !empty(
                        $dutySlip->duty_slip_back_file
                    )
                ) {

                    $this->fileUploadService->delete(
                        $dutySlip->duty_slip_back_file
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Deleted By
                |--------------------------------------------------------------------------
                */

                if (
                    $dutySlip
                        ->getConnection()
                        ->getSchemaBuilder()
                        ->hasColumn(
                            $dutySlip->getTable(),
                            'deleted_by'
                        )
                ) {

                    $dutySlip->deleted_by =
                        Auth::id();

                    $dutySlip->save();
                }


                /*
                |--------------------------------------------------------------------------
                | Soft Delete Duty Slip
                |--------------------------------------------------------------------------
                */

                return $dutySlip->delete();
            }
        );
    }
}