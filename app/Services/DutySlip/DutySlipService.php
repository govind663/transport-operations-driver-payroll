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
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
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
    | GET DUTY SLIPS
    |--------------------------------------------------------------------------
    |
    | Access:
    |
    | admin       -> ALL
    | operations  -> ALL
    | accountant  -> ALL
    | driver      -> ONLY own driver's slips
    |
    */

    public function getDutySlips(): Collection
    {
        $query = DutySlip::query()
            ->with([
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
            ])
            ->latest('id');


        /*
        |--------------------------------------------------------------------------
        | APPLY ROLE BASED ACCESS
        |--------------------------------------------------------------------------
        */

        $this->applyDutySlipAccessScope(
            $query
        );


        return $query->get();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND DUTY SLIP
    |--------------------------------------------------------------------------
    |
    | Access control is also applied here.
    |
    | This prevents a driver from opening another driver's
    | duty slip through a direct URL.
    |
    */

    public function findById(
        string|int $id
    ): DutySlip {

        $query = DutySlip::query()
            ->with([
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


        /*
        |--------------------------------------------------------------------------
        | APPLY ROLE BASED ACCESS
        |--------------------------------------------------------------------------
        */

        $this->applyDutySlipAccessScope(
            $query
        );


        return $query->findOrFail(
            $id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE NEXT DUTY SLIP NUMBER
    |--------------------------------------------------------------------------
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


        if (DB::transactionLevel() > 0) {

            $query->lockForUpdate();

        }


        $lastSlipNo =
            $query->value('slip_no');


        if (empty($lastSlipNo)) {

            return 'DS000001';

        }


        $lastNumber =
            (int) substr(
                $lastSlipNo,
                2
            );


        return 'DS' .
            str_pad(
                (string) ($lastNumber + 1),
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
                    | CHILD PAYLOAD
                    |--------------------------------------------------------------------------
                    */

                    $allowances =
                        array_key_exists(
                            'driver_allowances',
                            $data
                        )
                            ? $data['driver_allowances']
                            : (
                                $data['allowances']
                                ?? []
                            );


                    $expenses =
                        array_key_exists(
                            'driver_expenses',
                            $data
                        )
                            ? $data['driver_expenses']
                            : (
                                $data['expenses']
                                ?? []
                            );


                    if (!is_array($allowances)) {

                        $allowances = [];

                    }


                    if (!is_array($expenses)) {

                        $expenses = [];

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | FILES
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
                    | NORMALIZE DATE / TIME
                    |--------------------------------------------------------------------------
                    */

                    $data =
                        $this->normalizeDateTimeValues(
                            $data
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | NORMALIZE METERS
                    |--------------------------------------------------------------------------
                    */

                    $data =
                        $this->normalizeMeterValues(
                            $data
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE CHILD DATA
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
                    | REMOVE FORM-ONLY DATA
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
                    | CREATED BY
                    |--------------------------------------------------------------------------
                    */

                    $data['created_by'] =
                        Auth::id();


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    $data['status'] =
                        $data['status']
                        ?? DutySlip::STATUS_OPEN;


                    /*
                    |--------------------------------------------------------------------------
                    | DUTY ASSIGNMENT
                    |--------------------------------------------------------------------------
                    */

                    $dutyAssignment =
                        $this->getDutyAssignment(
                            $data['duty_assignment_id']
                            ?? null
                        );


                    $data['duty_assignment_id'] =
                        (int) $dutyAssignment->id;


                    /*
                    |--------------------------------------------------------------------------
                    | DRIVER
                    |--------------------------------------------------------------------------
                    */

                    $driverId =
                        $this->normalizeRequiredId(
                            $data['driver_id']
                            ?? null
                        );


                    if (!$driverId) {

                        throw ValidationException::withMessages([
                            'driver_id' =>
                                'Please select a driver.',
                        ]);

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DRIVER EXISTS
                    |--------------------------------------------------------------------------
                    */

                    $driverQuery =
                        Driver::query()
                            ->whereKey(
                                $driverId
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | DRIVER ROLE OWNERSHIP
                    |--------------------------------------------------------------------------
                    |
                    | A logged-in driver can create only a Duty Slip
                    | for their own Driver record.
                    |
                    */

                    $this->applyDriverOwnershipToDriverQuery(
                        $driverQuery
                    );


                    if (!$driverQuery->exists()) {

                        throw ValidationException::withMessages([
                            'driver_id' =>
                                'Selected driver is not available for your account.',
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
                        $this->normalizeRequiredId(
                            $data['vehicle_id']
                            ?? null
                        );


                    if (!$vehicleId) {

                        throw ValidationException::withMessages([
                            'vehicle_id' =>
                                'Please select a vehicle.',
                        ]);

                    }


                    if (
                        !VehicleManagement::query()
                            ->whereKey(
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


                    /*
                    |--------------------------------------------------------------------------
                    | VEHICLE TYPE
                    |--------------------------------------------------------------------------
                    */

                    $vehicleTypeId =
                        $this->normalizeRequiredId(
                            $data['vehicle_type_id']
                            ?? null
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | LEGACY FALLBACK
                    |--------------------------------------------------------------------------
                    */

                    if (!$vehicleTypeId) {

                        $vehicleTypeId =
                            $this->resolveLegacyVehicleType(
                                $data['vehicle_type']
                                ?? null
                            );

                    }


                    if (!$vehicleTypeId) {

                        throw ValidationException::withMessages([
                            'vehicle_type_id' =>
                                'Please select a vehicle type.',
                        ]);

                    }


                    if (
                        !VehicleType::query()
                            ->whereKey(
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


                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE LEGACY VALUE
                    |--------------------------------------------------------------------------
                    */

                    unset(
                        $data['vehicle_type']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | SERVER-SIDE SLIP NUMBER
                    |--------------------------------------------------------------------------
                    */

                    $data['slip_no'] =
                        $this->generateNextSlipNo();


                    /*
                    |--------------------------------------------------------------------------
                    | FRONT FILE
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
                    | BACK FILE
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
                    | CREATE DUTY SLIP
                    |--------------------------------------------------------------------------
                    */

                    $dutySlip =
                        DutySlip::create(
                            $data
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | SYNC ALLOWANCES
                    |--------------------------------------------------------------------------
                    */

                    $this->syncAllowances(
                        $dutySlip,
                        $allowances
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | SYNC EXPENSES
                    |--------------------------------------------------------------------------
                    */

                    $this->syncExpenses(
                        $dutySlip,
                        $expenses
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FRESH
                    |--------------------------------------------------------------------------
                    */

                    return $this->freshDutySlip(
                        $dutySlip
                    );
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
        | SECURITY
        |--------------------------------------------------------------------------
        */

        $this->ensureDutySlipAccess(
            $dutySlip
        );


        $oldFrontFile =
            $dutySlip->duty_slip_front_file;


        $oldBackFile =
            $dutySlip->duty_slip_back_file;


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
                        | CHILD PAYLOAD
                        |--------------------------------------------------------------------------
                        */

                        $allowances =
                            array_key_exists(
                                'driver_allowances',
                                $data
                            )
                                ? $data['driver_allowances']
                                : (
                                    $data['allowances']
                                    ?? []
                                );


                        $expenses =
                            array_key_exists(
                                'driver_expenses',
                                $data
                            )
                                ? $data['driver_expenses']
                                : (
                                    $data['expenses']
                                    ?? []
                                );


                        if (!is_array($allowances)) {

                            $allowances = [];

                        }


                        if (!is_array($expenses)) {

                            $expenses = [];

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | FILES
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
                        | NORMALIZE DATE / TIME
                        |--------------------------------------------------------------------------
                        */

                        $data =
                            $this->normalizeDateTimeValues(
                                $data
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | NORMALIZE METERS
                        |--------------------------------------------------------------------------
                        */

                        $data =
                            $this->normalizeMeterValues(
                                $data
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | REMOVE CHILD DATA
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
                        | REMOVE FORM-ONLY DATA
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
                        | UPDATED BY
                        |--------------------------------------------------------------------------
                        */

                        $data['updated_by'] =
                            Auth::id();


                        /*
                        |--------------------------------------------------------------------------
                        | DUTY ASSIGNMENT
                        |--------------------------------------------------------------------------
                        */

                        $dutyAssignment =
                            $this->getDutyAssignment(
                                $data['duty_assignment_id']
                                ?? $dutySlip->duty_assignment_id
                                ?? null
                            );


                        $data['duty_assignment_id'] =
                            (int) $dutyAssignment->id;


                        /*
                        |--------------------------------------------------------------------------
                        | DRIVER
                        |--------------------------------------------------------------------------
                        */

                        $driverId =
                            $this->normalizeRequiredId(
                                $data['driver_id']
                                ?? $dutySlip->driver_id
                                ?? null
                            );


                        if (!$driverId) {

                            throw ValidationException::withMessages([
                                'driver_id' =>
                                    'Please select a driver.',
                            ]);

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | DRIVER EXISTS + OWNERSHIP
                        |--------------------------------------------------------------------------
                        */

                        $driverQuery =
                            Driver::query()
                                ->whereKey(
                                    $driverId
                                );


                        $this->applyDriverOwnershipToDriverQuery(
                            $driverQuery
                        );


                        if (!$driverQuery->exists()) {

                            throw ValidationException::withMessages([
                                'driver_id' =>
                                    'Selected driver is not available for your account.',
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
                            $this->normalizeRequiredId(
                                $data['vehicle_id']
                                ?? $dutySlip->vehicle_id
                                ?? null
                            );


                        if (!$vehicleId) {

                            throw ValidationException::withMessages([
                                'vehicle_id' =>
                                    'Please select a vehicle.',
                            ]);

                        }


                        if (
                            !VehicleManagement::query()
                                ->whereKey(
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


                        /*
                        |--------------------------------------------------------------------------
                        | VEHICLE TYPE
                        |--------------------------------------------------------------------------
                        */

                        $vehicleTypeId =
                            $this->normalizeRequiredId(
                                $data['vehicle_type_id']
                                ?? $dutySlip->vehicle_type_id
                                ?? null
                            );


                        if (!$vehicleTypeId) {

                            $vehicleTypeId =
                                $this->resolveLegacyVehicleType(
                                    $data['vehicle_type']
                                    ?? null
                                );

                        }


                        if (!$vehicleTypeId) {

                            throw ValidationException::withMessages([
                                'vehicle_type_id' =>
                                    'Please select a vehicle type.',
                            ]);

                        }


                        if (
                            !VehicleType::query()
                                ->whereKey(
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


                        /*
                        |--------------------------------------------------------------------------
                        | REMOVE LEGACY VALUE
                        |--------------------------------------------------------------------------
                        */

                        unset(
                            $data['vehicle_type']
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | PRESERVE SLIP NUMBER
                        |--------------------------------------------------------------------------
                        */

                        $data['slip_no'] =
                            $dutySlip->slip_no;


                        /*
                        |--------------------------------------------------------------------------
                        | FRONT FILE
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
                        | BACK FILE
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
                        | UPDATE PARENT
                        |--------------------------------------------------------------------------
                        */

                        $dutySlip->update(
                            $data
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | SYNC ALLOWANCES
                        |--------------------------------------------------------------------------
                        */

                        $this->syncAllowances(
                            $dutySlip,
                            $allowances
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | SYNC EXPENSES
                        |--------------------------------------------------------------------------
                        */

                        $this->syncExpenses(
                            $dutySlip,
                            $expenses
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | FRESH
                        |--------------------------------------------------------------------------
                        */

                        return $this->freshDutySlip(
                            $dutySlip
                        );
                    }
                );


            /*
            |--------------------------------------------------------------------------
            | DELETE OLD FRONT
            |--------------------------------------------------------------------------
            */

            if (
                !empty($oldFrontFile) &&
                !empty(
                    $updatedDutySlip->duty_slip_front_file
                ) &&
                $oldFrontFile !==
                    $updatedDutySlip->duty_slip_front_file
            ) {

                $this->deleteFileSafely(
                    $oldFrontFile
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DELETE OLD BACK
            |--------------------------------------------------------------------------
            */

            if (
                !empty($oldBackFile) &&
                !empty(
                    $updatedDutySlip->duty_slip_back_file
                ) &&
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
    | APPLY DUTY SLIP ACCESS SCOPE
    |--------------------------------------------------------------------------
    |
    | admin       -> ALL
    | operations  -> ALL
    | accountant  -> ALL
    | driver      -> ONLY drivers.user_id = Auth::id()
    |
    */

    protected function applyDutySlipAccessScope(
        Builder $query
    ): Builder {

        $user =
            $this->authenticatedUser();


        /*
        |--------------------------------------------------------------------------
        | ADMIN / OPERATIONS / ACCOUNTANT
        |--------------------------------------------------------------------------
        */

        if (
            $this->isFullDutySlipAccessRole(
                $user->role
            )
        ) {

            return $query;
        }


        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'driver'
        ) {

            return $query->whereHas(
                'driver',
                function (Builder $driverQuery) use ($user) {

                    $driverQuery->where(
                        'user_id',
                        $user->id
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | UNAUTHORIZED ROLE
        |--------------------------------------------------------------------------
        */

        throw new AuthorizationException(
            'You are not authorized to access duty slips.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ENSURE DUTY SLIP ACCESS
    |--------------------------------------------------------------------------
    |
    | Used for update / delete.
    |
    */

    protected function ensureDutySlipAccess(
        DutySlip $dutySlip
    ): void {

        $user =
            $this->authenticatedUser();


        /*
        |--------------------------------------------------------------------------
        | ADMIN / OPERATIONS / ACCOUNTANT
        |--------------------------------------------------------------------------
        */

        if (
            $this->isFullDutySlipAccessRole(
                $user->role
            )
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'driver'
        ) {

            $ownsDutySlip =
                Driver::query()
                    ->whereKey(
                        $dutySlip->driver_id
                    )
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->exists();


            if ($ownsDutySlip) {

                return;
            }


            throw new AuthorizationException(
                'You are not authorized to access this duty slip.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | UNAUTHORIZED ROLE
        |--------------------------------------------------------------------------
        */

        throw new AuthorizationException(
            'You are not authorized to access this duty slip.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPLY DRIVER OWNERSHIP
    |--------------------------------------------------------------------------
    |
    | For driver users, restrict Driver query to their own
    | drivers.user_id = Auth::id().
    |
    | Admin / Operations / Accountant:
    | No additional restriction.
    |
    */

    protected function applyDriverOwnershipToDriverQuery(
        Builder $query
    ): Builder {

        $user =
            $this->authenticatedUser();


        if (
            $user->role === 'driver'
        ) {

            $query->where(
                'user_id',
                $user->id
            );

        }


        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | FULL DUTY SLIP ACCESS ROLE
    |--------------------------------------------------------------------------
    */

    protected function isFullDutySlipAccessRole(
        ?string $role
    ): bool {

        return in_array(
            $role,
            [
                'admin',
                'operations',
                'accountant',
            ],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED USER
    |--------------------------------------------------------------------------
    */

    protected function authenticatedUser()
    {
        $user =
            Auth::user();


        if (!$user) {

            throw new AuthorizationException(
                'Authentication is required.'
            );
        }


        return $user;
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


        $allowanceIds =
            collect($allowances)
                ->pluck('allowance_id')
                ->filter(
                    fn ($id) =>
                        $id !== null &&
                        $id !== ''
                )
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->unique()
                ->values();


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


        $driverId =
            $this->getDriverId(
                $dutySlip
            );


        if (!$driverId) {

            throw ValidationException::withMessages([
                'driver_allowances' =>
                    'Unable to determine the driver for this duty slip.',
            ]);
        }


        foreach ($allowances as $allowance) {

            if (!is_array($allowance)) {

                continue;
            }


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
                        'One of the selected allowances does not exist.',
                ]);
            }


            $quantity =
                isset($allowance['quantity']) &&
                $allowance['quantity'] !== ''
                    ? (float) $allowance['quantity']
                    : 1;


            if ($quantity <= 0) {

                $quantity = 1;

            }


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


            $rate =
                (float) (
                    $master->amount
                    ?? 0
                );


            $amount =
                round(
                    $quantity * $rate,
                    2
                );


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
                        $this->nullableString(
                            $allowance['remarks']
                            ?? null
                        ),

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
                        $this->nullableString(
                            $allowance['remarks']
                            ?? null
                        ),

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


        $query =
            DriverAllowance::query()
                ->where(
                    'duty_slip_id',
                    $dutySlip->id
                );


        if (!empty($existingIds)) {

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


        $expenseIds =
            collect($expenses)
                ->pluck('expense_id')
                ->filter(
                    fn ($id) =>
                        $id !== null &&
                        $id !== ''
                )
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->unique()
                ->values();


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


        $driverId =
            $this->getDriverId(
                $dutySlip
            );


        if (!$driverId) {

            throw ValidationException::withMessages([
                'driver_expenses' =>
                    'Unable to determine the driver for this duty slip.',
            ]);
        }


        foreach ($expenses as $expense) {

            if (!is_array($expense)) {

                continue;
            }


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
                        'One of the selected expenses does not exist.',
                ]);
            }


            $quantity =
                isset($expense['quantity']) &&
                $expense['quantity'] !== ''
                    ? (float) $expense['quantity']
                    : 1;


            if ($quantity <= 0) {

                $quantity = 1;

            }


            $rate =
                (float) (
                    $master->amount
                    ?? 0
                );


            $amount =
                round(
                    $quantity * $rate,
                    2
                );


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
                        $this->nullableString(
                            $expense['remarks']
                            ?? null
                        ),

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
                        $this->nullableString(
                            $expense['remarks']
                            ?? null
                        ),

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


        $query =
            DriverExpense::query()
                ->where(
                    'duty_slip_id',
                    $dutySlip->id
                );


        if (!empty($existingIds)) {

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
            $dutyAssignmentId === null ||
            $dutyAssignmentId === ''
        ) {

            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Please select a duty assignment.',
            ]);
        }


        $dutyAssignment =
            DutyAssignment::query()
                ->select([
                    'id',
                    'driver_id',
                    'vehicle_id',
                ])
                ->find(
                    $dutyAssignmentId
                );


        if (!$dutyAssignment) {

            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Selected duty assignment does not exist.',
            ]);
        }


        return $dutyAssignment;
    }


    /*
    |--------------------------------------------------------------------------
    | GET DRIVER ID
    |--------------------------------------------------------------------------
    */

    protected function getDriverId(
        DutySlip $dutySlip
    ): ?int {

        if (
            !empty(
                $dutySlip->driver_id
            )
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
    | NORMALIZE DATE / TIME
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | The Controller may already combine:
    |
    | start_date + start_time
    |
    | into:
    |
    | start_time = 2026-09-21 14:30
    |
    | Therefore this method detects an already-combined datetime
    | before attempting to combine again.
    |
    */

    protected function normalizeDateTimeValues(
        array $data
    ): array {

        $startDate =
            $data['start_date']
            ?? null;


        $startTime =
            $data['start_time']
            ?? null;


        $endDate =
            $data['end_date']
            ?? null;


        $endTime =
            $data['end_time']
            ?? null;


        $startDate =
            $startDate !== null
                ? trim((string) $startDate)
                : null;


        $startTime =
            $startTime !== null
                ? trim((string) $startTime)
                : null;


        $endDate =
            $endDate !== null
                ? trim((string) $endDate)
                : null;


        $endTime =
            $endTime !== null
                ? trim((string) $endTime)
                : null;


        $normalizedStart =
            $this->buildDateTimeValue(
                $startDate,
                $startTime,
                'start_time',
                $data['duty_date'] ?? null
            );


        $normalizedEnd =
            $this->buildDateTimeValue(
                $endDate,
                $endTime,
                'end_time',
                $data['duty_date'] ?? null
            );


        $data['start_time'] =
            $normalizedStart;


        $data['end_time'] =
            $normalizedEnd;


        if (
            $normalizedStart !== null &&
            $normalizedEnd !== null
        ) {

            try {

                $start =
                    Carbon::parse(
                        $normalizedStart
                    );


                $end =
                    Carbon::parse(
                        $normalizedEnd
                    );


                if (
                    $end->lessThan($start)
                ) {

                    throw ValidationException::withMessages([
                        'end_time' =>
                            'End date and time cannot be before start date and time.',
                    ]);
                }

            } catch (ValidationException $exception) {

                throw $exception;

            } catch (\Throwable $exception) {

                throw ValidationException::withMessages([
                    'end_time' =>
                        'End date and time must be valid.',
                ]);
            }
        }


        return $data;
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD DATETIME VALUE
    |--------------------------------------------------------------------------
    */

    protected function buildDateTimeValue(
        ?string $date,
        ?string $time,
        string $field,
        mixed $fallbackDate = null
    ): ?string {

        if (
            empty($date) &&
            empty($time)
        ) {

            return null;

        }


        if (
            !empty($time) &&
            $this->looksLikeDateTime($time)
        ) {

            try {

                $dateTime =
                    $this->parseDateTimeSafely(
                        $time
                    );


                return $dateTime->format(
                    'Y-m-d H:i:s'
                );

            } catch (\Throwable $exception) {

                throw ValidationException::withMessages([
                    $field =>
                        ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $field
                            )
                        ) .
                        ' must be valid.',
                ]);
            }
        }


        if (
            empty($date) &&
            !empty($time)
        ) {

            $date =
                $fallbackDate
                ?? null;


            if (
                empty($date)
            ) {

                throw ValidationException::withMessages([
                    $field =>
                        ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $field
                            )
                        ) .
                        ' date must be provided.',
                ]);
            }
        }


        if (
            !empty($date) &&
            empty($time)
        ) {

            return null;

        }


        $normalizedTime =
            $this->normalizeTimeValue(
                $time
            );


        $normalizedDate =
            $this->normalizeDateValue(
                $date,
                $field
            );


        try {

            $dateTime =
                Carbon::createFromFormat(
                    'Y-m-d H:i',
                    "{$normalizedDate} {$normalizedTime}"
                );


            return $dateTime->format(
                'Y-m-d H:i:s'
            );

        } catch (\Throwable $exception) {

            throw ValidationException::withMessages([
                $field =>
                    ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $field
                        )
                    ) .
                    ' must be valid.',
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DETECT DATETIME
    |--------------------------------------------------------------------------
    */

    protected function looksLikeDateTime(
        string $value
    ): bool {

        $value =
            trim($value);


        return (bool) preg_match(
            '/^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}(?::\d{2})?$/',
            $value
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PARSE DATETIME SAFELY
    |--------------------------------------------------------------------------
    */

    protected function parseDateTimeSafely(
        string $value
    ): Carbon {

        $value =
            trim($value);


        try {

            return Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $value
            );

        } catch (\Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Continue with minute format.
            |--------------------------------------------------------------------------
            */
        }


        try {

            return Carbon::createFromFormat(
                'Y-m-d H:i',
                $value
            );

        } catch (\Throwable $exception) {

            return Carbon::parse(
                $value
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE TIME VALUE
    |--------------------------------------------------------------------------
    */

    protected function normalizeTimeValue(
        ?string $time
    ): string {

        if (
            empty($time)
        ) {

            throw ValidationException::withMessages([
                'start_time' =>
                    'Time must be provided.',
            ]);
        }


        $time =
            trim($time);


        if (
            preg_match(
                '/^\d{2}:\d{2}$/',
                $time
            )
        ) {

            return $time;
        }


        if (
            preg_match(
                '/^\d{2}:\d{2}:\d{2}$/',
                $time
            )
        ) {

            return substr(
                $time,
                0,
                5
            );
        }


        try {

            return Carbon::parse(
                $time
            )->format('H:i');

        } catch (\Throwable $exception) {

            throw ValidationException::withMessages([
                'start_time' =>
                    'Time must be valid.',
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DATE VALUE
    |--------------------------------------------------------------------------
    */

    protected function normalizeDateValue(
        ?string $date,
        string $field
    ): string {

        if (
            empty($date)
        ) {

            throw ValidationException::withMessages([
                $field =>
                    ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $field
                        )
                    ) .
                    ' date must be provided.',
            ]);
        }


        $date =
            trim($date);


        try {

            return Carbon::createFromFormat(
                'Y-m-d',
                $date
            )->format('Y-m-d');

        } catch (\Throwable $exception) {

            throw ValidationException::withMessages([
                $field =>
                    ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $field
                        )
                    ) .
                    ' date must be valid.',
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE METER VALUES
    |--------------------------------------------------------------------------
    */

    protected function normalizeMeterValues(
        array $data
    ): array {

        $openingRaw =
            array_key_exists(
                'opening_km',
                $data
            )
                ? $data['opening_km']
                : (
                    $data['opening_meter']
                    ?? null
                );


        $closingRaw =
            array_key_exists(
                'closing_km',
                $data
            )
                ? $data['closing_km']
                : (
                    $data['closing_meter']
                    ?? null
                );


        $openingMeter =
            (
                $openingRaw !== null &&
                $openingRaw !== ''
            )
                ? (float) $openingRaw
                : null;


        $closingMeter =
            (
                $closingRaw !== null &&
                $closingRaw !== ''
            )
                ? (float) $closingRaw
                : null;


        if (
            $openingMeter !== null &&
            $openingMeter < 0
        ) {

            throw ValidationException::withMessages([
                'opening_km' =>
                    'Opening KM cannot be negative.',
            ]);
        }


        if (
            $closingMeter !== null &&
            $closingMeter < 0
        ) {

            throw ValidationException::withMessages([
                'closing_km' =>
                    'Closing KM cannot be negative.',
            ]);
        }


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


        $data['opening_meter'] =
            $openingMeter;


        $data['closing_meter'] =
            $closingMeter;


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
    | NORMALIZE REQUIRED ID
    |--------------------------------------------------------------------------
    */

    protected function normalizeRequiredId(
        mixed $value
    ): ?int {

        if (
            $value === null ||
            $value === ''
        ) {

            return null;
        }


        if (
            !is_numeric($value)
        ) {

            return null;
        }


        $id =
            (int) $value;


        return $id > 0
            ? $id
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE LEGACY VEHICLE TYPE
    |--------------------------------------------------------------------------
    */

    protected function resolveLegacyVehicleType(
        mixed $value
    ): ?int {

        if (
            $value === null ||
            $value === ''
        ) {

            return null;
        }


        $value =
            trim(
                (string) $value
            );


        if ($value === '') {

            return null;
        }


        if (
            ctype_digit($value)
        ) {

            $id =
                (int) $value;


            return $id > 0
                ? $id
                : null;
        }


        $id =
            VehicleType::query()
                ->where(
                    'name',
                    $value
                )
                ->value('id');


        if ($id) {

            return (int) $id;
        }


        $id =
            VehicleType::query()
                ->where(
                    'code',
                    $value
                )
                ->value('id');


        return $id
            ? (int) $id
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | NULLABLE STRING
    |--------------------------------------------------------------------------
    */

    protected function nullableString(
        mixed $value
    ): ?string {

        if (
            $value === null
        ) {

            return null;
        }


        $value =
            trim(
                (string) $value
            );


        return $value !== ''
            ? $value
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | FRESH DUTY SLIP
    |--------------------------------------------------------------------------
    */

    protected function freshDutySlip(
        DutySlip $dutySlip
    ): DutySlip {

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
    | DELETE DUTY SLIP
    |--------------------------------------------------------------------------
    */

    public function delete(
        DutySlip $dutySlip
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        $this->ensureDutySlipAccess(
            $dutySlip
        );


        $frontFile =
            $dutySlip->duty_slip_front_file;


        $backFile =
            $dutySlip->duty_slip_back_file;


        $deleted =
            DB::transaction(
                function () use (
                    $dutySlip
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Delete Child Allowances
                    |--------------------------------------------------------------------------
                    */

                    $dutySlip
                        ->driverAllowances()
                        ->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | Delete Child Expenses
                    |--------------------------------------------------------------------------
                    */

                    $dutySlip
                        ->driverExpenses()
                        ->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | deleted_by
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


        if ($deleted) {

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