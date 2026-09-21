<?php

namespace App\Services\DutySlip;

use App\Models\Allowance;
use App\Models\Driver;
use App\Models\DriverAllowance;
use App\Models\DriverExpense;
use App\Models\DutyAssignment;
use App\Models\DutySlip;
use App\Models\Expense;
use App\Models\VehicleManagement;
use App\Models\VehicleType;
use App\Services\FileUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

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
    | GET ALL DUTY SLIPS
    |--------------------------------------------------------------------------
    */

    public function getDutySlips(): Collection
    {
        return DutySlip::query()
            ->with([
                /*
                |--------------------------------------------------------------------------
                | Direct Driver
                |--------------------------------------------------------------------------
                */
                'driver',

                /*
                |--------------------------------------------------------------------------
                | Direct Vehicle
                |--------------------------------------------------------------------------
                */
                'vehicle',
                'vehicleType',

                /*
                |--------------------------------------------------------------------------
                | Duty Assignment
                |--------------------------------------------------------------------------
                */
                'dutyAssignment',
                'dutyAssignment.driver',
                'dutyAssignment.vehicle',

                /*
                |--------------------------------------------------------------------------
                | Audit Relations
                |--------------------------------------------------------------------------
                */
                'createdBy',
                'updatedBy',

                /*
                |--------------------------------------------------------------------------
                | Working Sheet
                |--------------------------------------------------------------------------
                */
                'workingSheet',

                /*
                |--------------------------------------------------------------------------
                | Driver Allowances
                |--------------------------------------------------------------------------
                */
                'driverAllowances',
                'driverAllowances.allowance',
                'driverAllowances.driver',

                /*
                |--------------------------------------------------------------------------
                | Driver Expenses
                |--------------------------------------------------------------------------
                */
                'driverExpenses',
                'driverExpenses.expense',
                'driverExpenses.driver',
            ])
            ->latest('id')
            ->get();
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
                /*
                |--------------------------------------------------------------------------
                | Direct Driver
                |--------------------------------------------------------------------------
                */
                'driver',

                /*
                |--------------------------------------------------------------------------
                | Direct Vehicle
                |--------------------------------------------------------------------------
                */
                'vehicle',
                'vehicleType',

                /*
                |--------------------------------------------------------------------------
                | Duty Assignment
                |--------------------------------------------------------------------------
                */
                'dutyAssignment',
                'dutyAssignment.driver',
                'dutyAssignment.vehicle',

                /*
                |--------------------------------------------------------------------------
                | Audit Relations
                |--------------------------------------------------------------------------
                */
                'createdBy',
                'updatedBy',

                /*
                |--------------------------------------------------------------------------
                | Working Sheet
                |--------------------------------------------------------------------------
                */
                'workingSheet',

                /*
                |--------------------------------------------------------------------------
                | Driver Allowances
                |--------------------------------------------------------------------------
                */
                'driverAllowances',
                'driverAllowances.allowance',
                'driverAllowances.driver',

                /*
                |--------------------------------------------------------------------------
                | Driver Expenses
                |--------------------------------------------------------------------------
                */
                'driverExpenses',
                'driverExpenses.expense',
                'driverExpenses.driver',
            ])
            ->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE NEXT DUTY SLIP NUMBER
    |--------------------------------------------------------------------------
    |
    | Display:
    | DS000001
    | DS000002
    | DS000003
    |
    | With an active DB transaction, the latest sequence row is locked
    | before calculating the next number.
    |
    */

    public function generateNextSlipNo(): string
    {
        $query = DutySlip::withTrashed()
            ->where(
                'slip_no',
                'REGEXP',
                '^DS[0-9]+$'
            )
            ->orderByRaw(
                'CAST(SUBSTRING(slip_no, 3) AS UNSIGNED) DESC'
            );


        /*
        |--------------------------------------------------------------------------
        | Lock During Transaction
        |--------------------------------------------------------------------------
        */

        if (
            DB::transactionLevel() > 0
        ) {

            $query->lockForUpdate();
        }


        $lastSlipNo =
            $query->value('slip_no');


        /*
        |--------------------------------------------------------------------------
        | First Slip
        |--------------------------------------------------------------------------
        */

        if (
            empty($lastSlipNo)
        ) {

            return 'DS000001';
        }


        /*
        |--------------------------------------------------------------------------
        | Get Numeric Sequence
        |--------------------------------------------------------------------------
        */

        $lastNumber =
            (int) substr(
                $lastSlipNo,
                2
            );


        $nextNumber =
            $lastNumber + 1;


        /*
        |--------------------------------------------------------------------------
        | Generate Fixed Six-Digit Number
        |--------------------------------------------------------------------------
        */

        return 'DS' .
            str_pad(
                (string) $nextNumber,
                6,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data
    ): DutySlip {

        $uploadedFiles = [];


        try {

            return DB::transaction(
                function () use (
                    $data,
                    &$uploadedFiles
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Child Data
                    |--------------------------------------------------------------------------
                    */

                    $allowances =
                        $data['allowances']
                        ?? [];

                    $expenses =
                        $data['expenses']
                        ?? [];


                    /*
                    |--------------------------------------------------------------------------
                    | Uploaded Files
                    |--------------------------------------------------------------------------
                    */

                    $frontFile =
                        $data['duty_slip_front_file']
                        ?? null;

                    $backFile =
                        $data['duty_slip_back_file']
                        ?? null;


                    /*
                    |--------------------------------------------------------------------------
                    | Remove Child Payload
                    |--------------------------------------------------------------------------
                    */

                    unset(
                        $data['allowances'],
                        $data['expenses'],
                        $data['driver_allowances'],
                        $data['driver_expenses']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Remove Form-Only Fields
                    |--------------------------------------------------------------------------
                    */

                    unset(
                        $data['vehicle_type'],
                        $data['start_date'],
                        $data['end_date'],
                        $data['opening_km'],
                        $data['closing_km'],
                        $data['pickup_location'],
                        $data['drop_location'],
                        $data['passenger_name'],
                        $data['passenger_mobile'],
                        $data['number_of_passengers'],
                        $data['fuel_quantity'],
                        $data['fuel_amount'],
                        $data['total_allowance'],
                        $data['total_expense'],
                        $data['grand_total']
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
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    $data['status'] =
                        $data['status']
                        ?? DutySlip::STATUS_OPEN;


                    /*
                    |--------------------------------------------------------------------------
                    | Duty Assignment
                    |--------------------------------------------------------------------------
                    */

                    $dutyAssignment =
                        $this->getDutyAssignment(
                            $data['duty_assignment_id']
                            ?? null
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Assignment Driver Is Required
                    |--------------------------------------------------------------------------
                    |
                    | Duty Assignment must have a driver assigned because the
                    | Duty Slip itself stores a direct driver_id.
                    |
                    */

                    if (
                        empty(
                            $dutyAssignment->driver_id
                        )
                    ) {

                        throw ValidationException::withMessages([
                            'duty_assignment_id' =>
                                'Selected duty assignment does not have a driver assigned.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Normalize IDs
                    |--------------------------------------------------------------------------
                    */

                    $data['duty_assignment_id'] =
                        (int) $dutyAssignment->id;


                    /*
                    |--------------------------------------------------------------------------
                    | DRIVER
                    |--------------------------------------------------------------------------
                    |
                    | User-selected Driver is authoritative for Duty Slip.
                    | No forced assignment match.
                    |
                    */

                    $driverId =
                        $data['driver_id']
                        ?? null;


                    if (
                        $driverId === null ||
                        $driverId === ''
                    ) {

                        throw ValidationException::withMessages([
                            'driver_id' =>
                                'Please select a driver.',
                        ]);
                    }


                    $driverId =
                        (int) $driverId;


                    if (
                        !Driver::query()
                            ->where(
                                'id',
                                $driverId
                            )
                            ->exists()
                    ) {

                        throw ValidationException::withMessages([
                            'driver_id' =>
                                'Selected driver does not exist.',
                        ]);
                    }


                    $data['driver_id'] =
                        $driverId;


                    /*
                    |--------------------------------------------------------------------------
                    | VEHICLE
                    |--------------------------------------------------------------------------
                    |
                    | User-selected Vehicle is independent from Duty Assignment.
                    |
                    */

                    $vehicleId =
                        $data['vehicle_id']
                        ?? null;


                    if (
                        $vehicleId !== null &&
                        $vehicleId !== ''
                    ) {

                        $vehicleId =
                            (int) $vehicleId;


                        if (
                            !VehicleManagement::query()
                                ->where(
                                    'id',
                                    $vehicleId
                                )
                                ->exists()
                        ) {

                            throw ValidationException::withMessages([
                                'vehicle_id' =>
                                    'Selected vehicle does not exist.',
                            ]);
                        }


                        $data['vehicle_id'] =
                            $vehicleId;

                    } else {

                        $data['vehicle_id'] =
                            null;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VEHICLE TYPE ID
                    |--------------------------------------------------------------------------
                    |
                    | Preferred field:
                    | vehicle_type_id
                    |
                    | For compatibility, if vehicle_type is sent as a numeric
                    | value, it can also be treated as the Vehicle Type ID.
                    |
                    */

                    $vehicleTypeId =
                        $data['vehicle_type_id']
                        ?? null;


                    /*
                    |--------------------------------------------------------------------------
                    | Backward Compatibility With vehicle_type
                    |--------------------------------------------------------------------------
                    */

                    if (
                        (
                            $vehicleTypeId === null ||
                            $vehicleTypeId === ''
                        ) &&
                        isset($data['vehicle_type'])
                    ) {

                        $vehicleTypeValue =
                            trim(
                                (string) $data['vehicle_type']
                            );


                        if (
                            $vehicleTypeValue !== ''
                        ) {

                            if (
                                ctype_digit(
                                    $vehicleTypeValue
                                )
                            ) {

                                $vehicleTypeId =
                                    (int) $vehicleTypeValue;

                            } else {

                                /*
                                |--------------------------------------------------------------------------
                                | Try Vehicle Type Name
                                |--------------------------------------------------------------------------
                                */

                                $vehicleTypeId =
                                    VehicleType::query()
                                        ->where(
                                            'name',
                                            $vehicleTypeValue
                                        )
                                        ->value('id');


                                /*
                                |--------------------------------------------------------------------------
                                | Try Vehicle Type Code
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    !$vehicleTypeId
                                ) {

                                    $vehicleTypeId =
                                        VehicleType::query()
                                            ->where(
                                                'code',
                                                $vehicleTypeValue
                                            )
                                            ->value('id');
                                }

                            }
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Validate Vehicle Type
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $vehicleTypeId !== null &&
                        $vehicleTypeId !== ''
                    ) {

                        $vehicleTypeId =
                            (int) $vehicleTypeId;


                        if (
                            !VehicleType::query()
                                ->where(
                                    'id',
                                    $vehicleTypeId
                                )
                                ->exists()
                        ) {

                            throw ValidationException::withMessages([
                                'vehicle_type_id' =>
                                    'Selected vehicle type does not exist.',
                            ]);
                        }


                        $data['vehicle_type_id'] =
                            $vehicleTypeId;

                    } else {

                        $data['vehicle_type_id'] =
                            null;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Remove Legacy Vehicle Type Text
                    |--------------------------------------------------------------------------
                    */

                    unset(
                        $data['vehicle_type']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FORCE SERVER-SIDE SLIP NUMBER
                    |--------------------------------------------------------------------------
                    |
                    | Never trust the readonly input value.
                    |
                    */

                    $data['slip_no'] =
                        $this->generateNextSlipNo();


                    /*
                    |--------------------------------------------------------------------------
                    | Normalize Meter Values
                    |--------------------------------------------------------------------------
                    */

                    $data =
                        $this->normalizeMeterValues(
                            $data
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Upload Front File
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $frontFile instanceof UploadedFile
                    ) {

                        $frontPath =
                            $this->fileUploadService->upload(
                                $frontFile,
                                'duty-slip/front'
                            );


                        $uploadedFiles[] =
                            $frontPath;


                        $data['duty_slip_front_file'] =
                            $frontPath;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Upload Back File
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $backFile instanceof UploadedFile
                    ) {

                        $backPath =
                            $this->fileUploadService->upload(
                                $backFile,
                                'duty-slip/back'
                            );


                        $uploadedFiles[] =
                            $backPath;


                        $data['duty_slip_back_file'] =
                            $backPath;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create Duty Slip
                    |--------------------------------------------------------------------------
                    */

                    $dutySlip =
                        DutySlip::create(
                            $data
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Sync Allowances
                    |--------------------------------------------------------------------------
                    */

                    $this->syncAllowances(
                        $dutySlip,
                        is_array($allowances)
                            ? $allowances
                            : []
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Sync Expenses
                    |--------------------------------------------------------------------------
                    */

                    $this->syncExpenses(
                        $dutySlip,
                        is_array($expenses)
                            ? $expenses
                            : []
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Return Fresh
                    |--------------------------------------------------------------------------
                    */

                    return $dutySlip->fresh([
                        'driver',
                        'vehicle',
                        'vehicleType',

                        'dutyAssignment',
                        'dutyAssignment.driver',
                        'dutyAssignment.vehicle',

                        'createdBy',
                        'updatedBy',

                        'workingSheet',

                        'driverAllowances',
                        'driverAllowances.allowance',
                        'driverAllowances.driver',

                        'driverExpenses',
                        'driverExpenses.expense',
                        'driverExpenses.driver',
                    ]);
                }
            );

        } catch (\Throwable $exception) {

            $this->cleanupUploadedFiles(
                $uploadedFiles
            );


            throw $exception;
        }
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

        /*
        |--------------------------------------------------------------------------
        | Old Files
        |--------------------------------------------------------------------------
        */

        $oldFrontFile =
            $dutySlip->duty_slip_front_file;

        $oldBackFile =
            $dutySlip->duty_slip_back_file;


        /*
        |--------------------------------------------------------------------------
        | New Uploaded Files
        |--------------------------------------------------------------------------
        */

        $uploadedFiles = [];


        try {

            $updatedDutySlip =
                DB::transaction(
                    function () use (
                        $dutySlip,
                        $data,
                        &$uploadedFiles
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Child Data
                        |--------------------------------------------------------------------------
                        */

                        $allowances =
                            $data['allowances']
                            ?? [];

                        $expenses =
                            $data['expenses']
                            ?? [];


                        /*
                        |--------------------------------------------------------------------------
                        | Files
                        |--------------------------------------------------------------------------
                        */

                        $newFrontFile =
                            $data['duty_slip_front_file']
                            ?? null;

                        $newBackFile =
                            $data['duty_slip_back_file']
                            ?? null;


                        /*
                        |--------------------------------------------------------------------------
                        | Remove Child Payload
                        |--------------------------------------------------------------------------
                        */

                        unset(
                            $data['allowances'],
                            $data['expenses'],
                            $data['driver_allowances'],
                            $data['driver_expenses']
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Remove Form-Only Fields
                        |--------------------------------------------------------------------------
                        */

                        unset(
                            $data['vehicle_type'],
                            $data['start_date'],
                            $data['end_date'],
                            $data['opening_km'],
                            $data['closing_km'],
                            $data['pickup_location'],
                            $data['drop_location'],
                            $data['passenger_name'],
                            $data['passenger_mobile'],
                            $data['number_of_passengers'],
                            $data['fuel_quantity'],
                            $data['fuel_amount'],
                            $data['total_allowance'],
                            $data['total_expense'],
                            $data['grand_total']
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
                        | Duty Assignment
                        |--------------------------------------------------------------------------
                        */

                        $dutyAssignment =
                            $this->getDutyAssignment(
                                $data['duty_assignment_id']
                                ?? $dutySlip->duty_assignment_id
                                ?? null
                            );


                        if (
                            empty(
                                $dutyAssignment->driver_id
                            )
                        ) {

                            throw ValidationException::withMessages([
                                'duty_assignment_id' =>
                                    'Selected duty assignment does not have a driver assigned.',
                            ]);
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Duty Assignment ID
                        |--------------------------------------------------------------------------
                        */

                        $data['duty_assignment_id'] =
                            (int) $dutyAssignment->id;


                        /*
                        |--------------------------------------------------------------------------
                        | DRIVER
                        |--------------------------------------------------------------------------
                        */

                        $driverId =
                            $data['driver_id']
                            ?? $dutySlip->driver_id
                            ?? null;


                        if (
                            $driverId === null ||
                            $driverId === ''
                        ) {

                            throw ValidationException::withMessages([
                                'driver_id' =>
                                    'Please select a driver.',
                            ]);
                        }


                        $driverId =
                            (int) $driverId;


                        if (
                            !Driver::query()
                                ->where(
                                    'id',
                                    $driverId
                                )
                                ->exists()
                        ) {

                            throw ValidationException::withMessages([
                                'driver_id' =>
                                    'Selected driver does not exist.',
                            ]);
                        }


                        $data['driver_id'] =
                            $driverId;


                        /*
                        |--------------------------------------------------------------------------
                        | VEHICLE
                        |--------------------------------------------------------------------------
                        */

                        $vehicleId =
                            $data['vehicle_id']
                            ?? $dutySlip->vehicle_id
                            ?? null;


                        if (
                            $vehicleId !== null &&
                            $vehicleId !== ''
                        ) {

                            $vehicleId =
                                (int) $vehicleId;


                            if (
                                !VehicleManagement::query()
                                    ->where(
                                        'id',
                                        $vehicleId
                                    )
                                    ->exists()
                            ) {

                                throw ValidationException::withMessages([
                                    'vehicle_id' =>
                                        'Selected vehicle does not exist.',
                                ]);
                            }


                            $data['vehicle_id'] =
                                $vehicleId;

                        } else {

                            $data['vehicle_id'] =
                                null;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | VEHICLE TYPE
                        |--------------------------------------------------------------------------
                        */

                        $vehicleTypeId =
                            $data['vehicle_type_id']
                            ?? $dutySlip->vehicle_type_id
                            ?? null;


                        /*
                        |--------------------------------------------------------------------------
                        | Backward Compatibility With vehicle_type
                        |--------------------------------------------------------------------------
                        */

                        if (
                            (
                                $vehicleTypeId === null ||
                                $vehicleTypeId === ''
                            ) &&
                            isset($data['vehicle_type'])
                        ) {

                            $vehicleTypeValue =
                                trim(
                                    (string) $data['vehicle_type']
                                );


                            if (
                                $vehicleTypeValue !== ''
                            ) {

                                if (
                                    ctype_digit(
                                        $vehicleTypeValue
                                    )
                                ) {

                                    $vehicleTypeId =
                                        (int) $vehicleTypeValue;

                                } else {

                                    $vehicleTypeId =
                                        VehicleType::query()
                                            ->where(
                                                'name',
                                                $vehicleTypeValue
                                            )
                                            ->value('id');


                                    if (
                                        !$vehicleTypeId
                                    ) {

                                        $vehicleTypeId =
                                            VehicleType::query()
                                                ->where(
                                                    'code',
                                                    $vehicleTypeValue
                                                )
                                                ->value('id');
                                    }
                                }
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Validate Vehicle Type
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $vehicleTypeId !== null &&
                            $vehicleTypeId !== ''
                        ) {

                            $vehicleTypeId =
                                (int) $vehicleTypeId;


                            if (
                                !VehicleType::query()
                                    ->where(
                                        'id',
                                        $vehicleTypeId
                                    )
                                    ->exists()
                            ) {

                                throw ValidationException::withMessages([
                                    'vehicle_type_id' =>
                                        'Selected vehicle type does not exist.',
                                ]);
                            }


                            $data['vehicle_type_id'] =
                                $vehicleTypeId;

                        } else {

                            $data['vehicle_type_id'] =
                                null;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Remove Legacy Vehicle Type
                        |--------------------------------------------------------------------------
                        */

                        unset(
                            $data['vehicle_type']
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Preserve Existing Slip Number
                        |--------------------------------------------------------------------------
                        |
                        | Updating a Duty Slip should NOT create a new number.
                        |
                        */

                        $data['slip_no'] =
                            $dutySlip->slip_no;


                        /*
                        |--------------------------------------------------------------------------
                        | Normalize Meters
                        |--------------------------------------------------------------------------
                        */

                        $data =
                            $this->normalizeMeterValues(
                                $data
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | New Front File
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $newFrontFile instanceof UploadedFile
                        ) {

                            $newFrontPath =
                                $this->fileUploadService->upload(
                                    $newFrontFile,
                                    'duty-slip/front'
                                );


                            $uploadedFiles[] =
                                $newFrontPath;


                            $data['duty_slip_front_file'] =
                                $newFrontPath;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | New Back File
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $newBackFile instanceof UploadedFile
                        ) {

                            $newBackPath =
                                $this->fileUploadService->upload(
                                    $newBackFile,
                                    'duty-slip/back'
                                );


                            $uploadedFiles[] =
                                $newBackPath;


                            $data['duty_slip_back_file'] =
                                $newBackPath;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Update Parent
                        |--------------------------------------------------------------------------
                        */

                        $dutySlip->update(
                            $data
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Sync Allowances
                        |--------------------------------------------------------------------------
                        */

                        $this->syncAllowances(
                            $dutySlip,
                            is_array($allowances)
                                ? $allowances
                                : []
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Sync Expenses
                        |--------------------------------------------------------------------------
                        */

                        $this->syncExpenses(
                            $dutySlip,
                            is_array($expenses)
                                ? $expenses
                                : []
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Fresh Model
                        |--------------------------------------------------------------------------
                        */

                        return $dutySlip->fresh([
                            'driver',
                            'vehicle',
                            'vehicleType',

                            'dutyAssignment',
                            'dutyAssignment.driver',
                            'dutyAssignment.vehicle',

                            'createdBy',
                            'updatedBy',

                            'workingSheet',

                            'driverAllowances',
                            'driverAllowances.allowance',
                            'driverAllowances.driver',

                            'driverExpenses',
                            'driverExpenses.expense',
                            'driverExpenses.driver',
                        ]);
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | Delete Old Front File
            |--------------------------------------------------------------------------
            */

            if (
                !empty($oldFrontFile) &&
                !empty($updatedDutySlip->duty_slip_front_file) &&
                $oldFrontFile !==
                    $updatedDutySlip->duty_slip_front_file
            ) {

                $this->deleteFileSafely(
                    $oldFrontFile
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Old Back File
            |--------------------------------------------------------------------------
            */

            if (
                !empty($oldBackFile) &&
                !empty($updatedDutySlip->duty_slip_back_file) &&
                $oldBackFile !==
                    $updatedDutySlip->duty_slip_back_file
            ) {

                $this->deleteFileSafely(
                    $oldBackFile
                );
            }


            return $updatedDutySlip;

        } catch (\Throwable $exception) {

            $this->cleanupUploadedFiles(
                $uploadedFiles
            );


            throw $exception;
        }
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


        /*
        |--------------------------------------------------------------------------
        | Master IDs
        |--------------------------------------------------------------------------
        */

        $allowanceIds =
            collect($allowances)
                ->pluck('allowance_id')
                ->filter()
                ->map(
                    fn ($id) => (int) $id
                )
                ->unique()
                ->values();


        /*
        |--------------------------------------------------------------------------
        | Load Masters
        |--------------------------------------------------------------------------
        */

        $allowanceMasters =
            $allowanceIds->isNotEmpty()
                ? Allowance::query()
                    ->whereIn(
                        'id',
                        $allowanceIds
                    )
                    ->get()
                    ->keyBy('id')
                : collect();


        /*
        |--------------------------------------------------------------------------
        | Direct Duty Driver
        |--------------------------------------------------------------------------
        */

        $driverId =
            $this->getDriverId(
                $dutySlip
            );


        if (
            empty($driverId)
        ) {

            throw ValidationException::withMessages([
                'driver_allowances' =>
                    'Unable to determine the driver for this duty slip.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Save Rows
        |--------------------------------------------------------------------------
        */

        foreach ($allowances as $allowance) {

            if (
                empty(
                    $allowance['allowance_id']
                )
            ) {
                continue;
            }


            $allowanceId =
                (int) $allowance['allowance_id'];


            $master =
                $allowanceMasters->get(
                    $allowanceId
                );


            if (!$master) {

                throw ValidationException::withMessages([
                    'driver_allowances' =>
                        'One of the selected allowances no longer exists.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $quantity =
                isset($allowance['quantity']) &&
                $allowance['quantity'] !== ''
                    ? (float) $allowance['quantity']
                    : 1;


            /*
            |--------------------------------------------------------------------------
            | Per KM
            |--------------------------------------------------------------------------
            */

            if (
                ($master->calculation_type ?? null)
                === 'per_km'
            ) {

                $quantity =
                    (float) (
                        $dutySlip->total_km
                        ?? 0
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Master Rate
            |--------------------------------------------------------------------------
            */

            $rate =
                isset($master->amount)
                    ? (float) $master->amount
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Server Calculated Amount
            |--------------------------------------------------------------------------
            */

            $amount =
                round(
                    $quantity * $rate,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Existing Record
            |--------------------------------------------------------------------------
            */

            $record = null;


            if (
                !empty(
                    $allowance['id']
                )
            ) {

                $record =
                    DriverAllowance::query()
                        ->where(
                            'duty_slip_id',
                            $dutySlip->id
                        )
                        ->where(
                            'id',
                            (int) $allowance['id']
                        )
                        ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            if ($record) {

                $record->update([

                    'driver_id' =>
                        $driverId,

                    'allowance_id' =>
                        $allowanceId,

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $allowance['remarks']
                        ?? null,

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
            | Create
            |--------------------------------------------------------------------------
            */

            $record =
                DriverAllowance::create([

                    'driver_id' =>
                        $driverId,

                    'duty_slip_id' =>
                        $dutySlip->id,

                    'allowance_id' =>
                        $allowanceId,

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $allowance['remarks']
                        ?? null,

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


        /*
        |--------------------------------------------------------------------------
        | Master IDs
        |--------------------------------------------------------------------------
        */

        $expenseIds =
            collect($expenses)
                ->pluck('expense_id')
                ->filter()
                ->map(
                    fn ($id) => (int) $id
                )
                ->unique()
                ->values();


        /*
        |--------------------------------------------------------------------------
        | Load Masters
        |--------------------------------------------------------------------------
        */

        $expenseMasters =
            $expenseIds->isNotEmpty()
                ? Expense::query()
                    ->whereIn(
                        'id',
                        $expenseIds
                    )
                    ->get()
                    ->keyBy('id')
                : collect();


        /*
        |--------------------------------------------------------------------------
        | Direct Duty Driver
        |--------------------------------------------------------------------------
        */

        $driverId =
            $this->getDriverId(
                $dutySlip
            );


        if (
            empty($driverId)
        ) {

            throw ValidationException::withMessages([
                'driver_expenses' =>
                    'Unable to determine the driver for this duty slip.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Save Rows
        |--------------------------------------------------------------------------
        */

        foreach ($expenses as $expense) {

            if (
                empty(
                    $expense['expense_id']
                )
            ) {
                continue;
            }


            $expenseId =
                (int) $expense['expense_id'];


            $master =
                $expenseMasters->get(
                    $expenseId
                );


            if (!$master) {

                throw ValidationException::withMessages([
                    'driver_expenses' =>
                        'One of the selected expenses no longer exists.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $quantity =
                isset($expense['quantity']) &&
                $expense['quantity'] !== ''
                    ? (float) $expense['quantity']
                    : 1;


            /*
            |--------------------------------------------------------------------------
            | Master Rate
            |--------------------------------------------------------------------------
            */

            $rate =
                isset($master->amount)
                    ? (float) $master->amount
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Server Calculated Amount
            |--------------------------------------------------------------------------
            */

            $amount =
                round(
                    $quantity * $rate,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Existing Record
            |--------------------------------------------------------------------------
            */

            $record = null;


            if (
                !empty(
                    $expense['id']
                )
            ) {

                $record =
                    DriverExpense::query()
                        ->where(
                            'duty_slip_id',
                            $dutySlip->id
                        )
                        ->where(
                            'id',
                            (int) $expense['id']
                        )
                        ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            if ($record) {

                $record->update([

                    'driver_id' =>
                        $driverId,

                    'expense_id' =>
                        $expenseId,

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $expense['remarks']
                        ?? null,

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
            | Create
            |--------------------------------------------------------------------------
            */

            $record =
                DriverExpense::create([

                    'driver_id' =>
                        $driverId,

                    'duty_slip_id' =>
                        $dutySlip->id,

                    'expense_id' =>
                        $expenseId,

                    'quantity' =>
                        $quantity,

                    'rate' =>
                        $rate,

                    'amount' =>
                        $amount,

                    'remarks' =>
                        $expense['remarks']
                        ?? null,

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
    | GET DUTY ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected function getDutyAssignment(
        string|int|null $dutyAssignmentId
    ): DutyAssignment {

        if (
            empty($dutyAssignmentId)
        ) {

            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Please select a duty assignment.',
            ]);
        }


        return DutyAssignment::query()
            ->select([
                'id',
                'driver_id',
                'vehicle_id',
            ])
            ->findOrFail(
                $dutyAssignmentId
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GET DRIVER ID
    |--------------------------------------------------------------------------
    |
    | Direct DutySlip driver_id is now primary.
    | Assignment is only fallback for legacy records.
    |
    */

    protected function getDriverId(
        DutySlip $dutySlip
    ): ?int {

        if (
            !empty($dutySlip->driver_id)
        ) {

            return (int) $dutySlip->driver_id;
        }


        $dutySlip->loadMissing(
            'dutyAssignment'
        );


        $assignmentDriverId =
            optional(
                $dutySlip->dutyAssignment
            )->driver_id;


        return $assignmentDriverId !== null
            ? (int) $assignmentDriverId
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE METER VALUES
    |--------------------------------------------------------------------------
    */

    protected function normalizeMeterValues(
        array $data
    ): array {

        $openingMeter =
            isset($data['opening_meter']) &&
            $data['opening_meter'] !== ''
                ? (float) $data['opening_meter']
                : null;


        $closingMeter =
            isset($data['closing_meter']) &&
            $data['closing_meter'] !== ''
                ? (float) $data['closing_meter']
                : null;


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if (
            $openingMeter !== null &&
            $closingMeter !== null &&
            $closingMeter < $openingMeter
        ) {

            throw ValidationException::withMessages([
                'closing_km' =>
                    'Closing KM must be greater than or equal to Opening KM.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Store Values
        |--------------------------------------------------------------------------
        */

        $data['opening_meter'] =
            $openingMeter;

        $data['closing_meter'] =
            $closingMeter;


        /*
        |--------------------------------------------------------------------------
        | Total KM
        |--------------------------------------------------------------------------
        */

        if (
            $openingMeter !== null &&
            $closingMeter !== null
        ) {

            $data['total_km'] =
                round(
                    $closingMeter - $openingMeter,
                    2
                );

        } else {

            $data['total_km'] =
                null;
        }


        return $data;
    }


    /*
    |--------------------------------------------------------------------------
    | CLEANUP UPLOADED FILES
    |--------------------------------------------------------------------------
    */

    protected function cleanupUploadedFiles(
        array $files
    ): void {

        foreach ($files as $file) {

            if (
                empty($file)
            ) {
                continue;
            }


            try {

                $this->fileUploadService->delete(
                    $file
                );

            } catch (\Throwable $exception) {

                Log::error(
                    'Failed to cleanup uploaded Duty Slip file.',
                    [
                        'file' =>
                            $file,

                        'error' =>
                            $exception->getMessage(),
                    ]
                );
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SAFE FILE DELETE
    |--------------------------------------------------------------------------
    */

    protected function deleteFileSafely(
        ?string $file
    ): void {

        if (
            empty($file)
        ) {
            return;
        }


        try {

            $this->fileUploadService->delete(
                $file
            );

        } catch (\Throwable $exception) {

            Log::warning(
                'Duty Slip file could not be deleted.',
                [
                    'file' =>
                        $file,

                    'error' =>
                        $exception->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        DutySlip $dutySlip
    ): bool {

        $frontFile =
            $dutySlip->duty_slip_front_file;

        $backFile =
            $dutySlip->duty_slip_back_file;


        /*
        |--------------------------------------------------------------------------
        | Delete Database Records
        |--------------------------------------------------------------------------
        */

        $deleted =
            DB::transaction(
                function () use (
                    $dutySlip
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Child Allowances
                    |--------------------------------------------------------------------------
                    */

                    $dutySlip
                        ->driverAllowances()
                        ->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | Child Expenses
                    |--------------------------------------------------------------------------
                    */

                    $dutySlip
                        ->driverExpenses()
                        ->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | Deleted By
                    |--------------------------------------------------------------------------
                    */

                    if (
                        Schema::hasColumn(
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
                    | Soft Delete
                    |--------------------------------------------------------------------------
                    */

                    return $dutySlip->delete();
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Delete Files After Successful Transaction
        |--------------------------------------------------------------------------
        */

        if (
            $deleted
        ) {

            $this->deleteFileSafely(
                $frontFile
            );

            $this->deleteFileSafely(
                $backFile
            );
        }


        return $deleted;
    }
}