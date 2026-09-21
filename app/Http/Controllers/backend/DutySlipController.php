<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DutySlip\StoreDutySlipRequest;
use App\Http\Requests\Backend\DutySlip\UpdateDutySlipRequest;
use App\Models\Allowance;
use App\Models\Driver;
use App\Models\DutyAssignment;
use App\Models\DutySlip;
use App\Models\Expense;
use App\Models\VehicleManagement;
use App\Models\VehicleType;
use App\Services\DutySlip\DutySlipService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DutySlipController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Duty Slip Service
    |--------------------------------------------------------------------------
    */

    protected DutySlipService $dutySlipService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        DutySlipService $dutySlipService
    ) {
        $this->dutySlipService = $dutySlipService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of duty slips.
     */
    public function index(): View
    {
        $dutySlips = $this->dutySlipService
            ->getDutySlips();

        return view(
            'backend.duty-slips.index',
            compact('dutySlips')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new duty slip.
     */
    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Active Duty Assignments
        |--------------------------------------------------------------------------
        |
        | Duty Assignment is still required on the Duty Slip, but Driver,
        | Vehicle and Vehicle Type are selected independently by the user.
        |
        */

        $dutyAssignments = DutyAssignment::query()
            ->whereIn('status', [
                DutyAssignment::STATUS_ASSIGNED,
                DutyAssignment::STATUS_ACCEPTED,
                DutyAssignment::STATUS_STARTED,
            ])
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
            ])
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL ALLOWANCES
        |--------------------------------------------------------------------------
        */

        $allowances = Allowance::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL DRIVERS
        |--------------------------------------------------------------------------
        |
        | User selects Driver manually.
        |
        */

        $drivers = Driver::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLES
        |--------------------------------------------------------------------------
        |
        | User selects Vehicle manually.
        |
        */

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLE TYPES
        |--------------------------------------------------------------------------
        |
        | User selects Vehicle Type manually.
        |
        */

        $vehicleTypes = VehicleType::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NEXT DUTY SLIP NUMBER
        |--------------------------------------------------------------------------
        |
        | Service should generate the next server-side sequence.
        |
        */

        $nextSlipNo = $this->dutySlipService
            ->generateNextSlipNo();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.duty-slips.create',
            compact(
                'dutyAssignments',
                'allowances',
                'expenses',
                'drivers',
                'vehicles',
                'vehicleTypes',
                'nextSlipNo'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created duty slip.
     */
    public function store(
        StoreDutySlipRequest $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validated Request Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Normalize Duty Slip Data
        |--------------------------------------------------------------------------
        */

        $data = $this->prepareDutySlipData(
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | Store Duty Slip
        |--------------------------------------------------------------------------
        */

        $this->dutySlipService->store(
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('duty-slips.index')
            ->with(
                'message',
                'Duty slip created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified duty slip.
     */
    public function show(
        DutySlip $dutySlip
    ): View {

        $dutySlip = $this->dutySlipService
            ->findById(
                $dutySlip->id
            );


        return view(
            'backend.duty-slips.show',
            compact('dutySlip')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified duty slip.
     */
    public function edit(
        DutySlip $dutySlip
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Fresh Duty Slip
        |--------------------------------------------------------------------------
        */

        $dutySlip = $this->dutySlipService
            ->findById(
                $dutySlip->id
            );


        /*
        |--------------------------------------------------------------------------
        | ALL DUTY ASSIGNMENTS
        |--------------------------------------------------------------------------
        */

        $dutyAssignments = DutyAssignment::query()
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
            ])
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL ALLOWANCES
        |--------------------------------------------------------------------------
        */

        $allowances = Allowance::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses = Expense::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL DRIVERS
        |--------------------------------------------------------------------------
        */

        $drivers = Driver::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLES
        |--------------------------------------------------------------------------
        */

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ALL VEHICLE TYPES
        |--------------------------------------------------------------------------
        */

        $vehicleTypes = VehicleType::query()
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.duty-slips.edit',
            compact(
                'dutySlip',
                'dutyAssignments',
                'allowances',
                'expenses',
                'drivers',
                'vehicles',
                'vehicleTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified duty slip.
     */
    public function update(
        UpdateDutySlipRequest $request,
        DutySlip $dutySlip
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validated Request Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Normalize Duty Slip Data
        |--------------------------------------------------------------------------
        */

        $data = $this->prepareDutySlipData(
            $data,
            $dutySlip
        );


        /*
        |--------------------------------------------------------------------------
        | Update Duty Slip
        |--------------------------------------------------------------------------
        */

        $this->dutySlipService->update(
            $dutySlip,
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('duty-slips.index')
            ->with(
                'message',
                'Duty slip updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified duty slip.
     */
    public function destroy(
        DutySlip $dutySlip
    ): RedirectResponse {

        $this->dutySlipService->delete(
            $dutySlip
        );


        return redirect()
            ->route('duty-slips.index')
            ->with(
                'message',
                'Duty slip deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE DUTY SLIP DATA
    |--------------------------------------------------------------------------
    |
    | Normalizes form fields before passing them to the service.
    |
    | Driver, Vehicle and Vehicle Type are user-selected independently.
    | Only driver_id is currently stored directly in duty_slips.
    |
    */

    protected function prepareDutySlipData(
        array $data,
        ?DutySlip $dutySlip = null
    ): array {

        /*
        |--------------------------------------------------------------------------
        | DUTY ASSIGNMENT
        |--------------------------------------------------------------------------
        |
        | Duty Assignment remains mandatory because the database requires
        | duty_assignment_id.
        |
        */

        $dutyAssignmentId =
            $data['duty_assignment_id']
            ?? null;


        if (empty($dutyAssignmentId)) {

            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Please select a duty assignment.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY DUTY ASSIGNMENT EXISTS
        |--------------------------------------------------------------------------
        */

        $dutyAssignment = DutyAssignment::query()
            ->select([
                'id',
                'driver_id',
                'vehicle_id',
            ])
            ->find($dutyAssignmentId);


        if (!$dutyAssignment) {

            throw ValidationException::withMessages([
                'duty_assignment_id' =>
                    'Selected duty assignment does not exist.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE DUTY ASSIGNMENT ID
        |--------------------------------------------------------------------------
        */

        $data['duty_assignment_id'] =
            (int) $dutyAssignment->id;


        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        |
        | User selects Driver manually.
        | Driver is stored directly in duty_slips.driver_id.
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


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE DRIVER ID
        |--------------------------------------------------------------------------
        */

        $data['driver_id'] =
            (int) $driverId;


        /*
        |--------------------------------------------------------------------------
        | VERIFY DRIVER EXISTS
        |--------------------------------------------------------------------------
        */

        $driverExists =
            Driver::query()
                ->where(
                    'id',
                    $data['driver_id']
                )
                ->exists();


        if (!$driverExists) {

            throw ValidationException::withMessages([
                'driver_id' =>
                    'Selected driver does not exist.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        |
        | User selects Vehicle manually.
        | Current duty_slips table does not contain vehicle_id,
        | therefore this remains a form-only field.
        |
        */

        if (
            isset($data['vehicle_id']) &&
            $data['vehicle_id'] !== ''
        ) {

            $vehicleId =
                (int) $data['vehicle_id'];


            $vehicleExists =
                VehicleManagement::query()
                    ->where(
                        'id',
                        $vehicleId
                    )
                    ->exists();


            if (!$vehicleExists) {

                throw ValidationException::withMessages([
                    'vehicle_id' =>
                        'Selected vehicle does not exist.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Keep As Form Value
            |--------------------------------------------------------------------------
            */

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
        |
        | User selects Vehicle Type manually.
        | Current duty_slips table has no vehicle_type_id column.
        |
        */

        if (
            isset($data['vehicle_type'])
        ) {

            $data['vehicle_type'] =
                trim(
                    (string) $data['vehicle_type']
                );

            if (
                $data['vehicle_type'] === ''
            ) {

                $data['vehicle_type'] =
                    null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SLIP NUMBER
        |--------------------------------------------------------------------------
        |
        | Current value is normalized.
        | Actual sequence should still be enforced by Service at save time.
        |
        */

        if (
            isset($data['slip_no'])
        ) {

            $data['slip_no'] =
                strtoupper(
                    preg_replace(
                        '/\s+/',
                        '',
                        trim(
                            (string) $data['slip_no']
                        )
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DUTY DATE
        |--------------------------------------------------------------------------
        */

        if (
            empty($data['duty_date'])
        ) {

            throw ValidationException::withMessages([
                'duty_date' =>
                    'Duty date is required.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | OPENING KM
        |--------------------------------------------------------------------------
        */

        $openingKm =
            isset($data['opening_km']) &&
            $data['opening_km'] !== ''
                ? (float) $data['opening_km']
                : null;


        /*
        |--------------------------------------------------------------------------
        | CLOSING KM
        |--------------------------------------------------------------------------
        */

        $closingKm =
            isset($data['closing_km']) &&
            $data['closing_km'] !== ''
                ? (float) $data['closing_km']
                : null;


        /*
        |--------------------------------------------------------------------------
        | KM VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $openingKm !== null &&
            $closingKm !== null &&
            $closingKm < $openingKm
        ) {

            throw ValidationException::withMessages([
                'closing_km' =>
                    'Closing KM must be greater than or equal to Opening KM.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DATABASE METER FIELDS
        |--------------------------------------------------------------------------
        */

        $data['opening_meter'] =
            $openingKm;

        $data['closing_meter'] =
            $closingKm;


        /*
        |--------------------------------------------------------------------------
        | TOTAL KM
        |--------------------------------------------------------------------------
        */

        if (
            $openingKm !== null &&
            $closingKm !== null
        ) {

            $data['total_km'] =
                round(
                    $closingKm - $openingKm,
                    2
                );

        } else {

            $data['total_km'] =
                null;
        }


        /*
        |--------------------------------------------------------------------------
        | START DATE + TIME
        |--------------------------------------------------------------------------
        */

        $data['start_time'] =
            $this->combineDateAndTime(
                $data['start_date'] ?? null,
                $data['start_time'] ?? null,
                $data['duty_date'] ?? null,
                'start_date'
            );


        /*
        |--------------------------------------------------------------------------
        | END DATE + TIME
        |--------------------------------------------------------------------------
        */

        $data['end_time'] =
            $this->combineDateAndTime(
                $data['end_date'] ?? null,
                $data['end_time'] ?? null,
                $data['duty_date'] ?? null,
                'end_date'
            );


        /*
        |--------------------------------------------------------------------------
        | START / END VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $data['start_time'] !== null &&
            $data['end_time'] !== null
        ) {

            try {

                $startDateTime =
                    Carbon::parse(
                        $data['start_time']
                    );

                $endDateTime =
                    Carbon::parse(
                        $data['end_time']
                    );


                if (
                    $endDateTime->lessThan(
                        $startDateTime
                    )
                ) {

                    throw ValidationException::withMessages([
                        'end_time' =>
                            'End date and time cannot be before start date and time.',
                    ]);
                }

            } catch (
                ValidationException $exception
            ) {

                throw $exception;

            } catch (\Throwable $exception) {

                throw ValidationException::withMessages([
                    'start_time' =>
                        'Invalid start date or time.',

                    'end_time' =>
                        'Invalid end date or time.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ALLOWANCES
        |--------------------------------------------------------------------------
        */

        $allowances =
            $data['driver_allowances']
            ?? [];


        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        $expenses =
            $data['driver_expenses']
            ?? [];


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE CHILD DATA
        |--------------------------------------------------------------------------
        */

        $data['allowances'] =
            is_array($allowances)
                ? $allowances
                : [];


        $data['expenses'] =
            is_array($expenses)
                ? $expenses
                : [];


        /*
        |--------------------------------------------------------------------------
        | REMOVE FORM-ONLY FIELDS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | driver_id is NOT removed.
        |
        | vehicle_id and vehicle_type are removed because the current
        | duty_slips table does not contain these columns.
        |
        */

        unset(

            $data['driver_allowances'],

            $data['driver_expenses'],

            $data['vehicle_id'],

            $data['vehicle_type'],

            $data['start_date'],

            $data['end_date'],

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
        | RETURN
        |--------------------------------------------------------------------------
        */

        return $data;
    }


    /*
    |--------------------------------------------------------------------------
    | COMBINE DATE + TIME
    |--------------------------------------------------------------------------
    */

    protected function combineDateAndTime(
        ?string $date,
        ?string $time,
        ?string $fallbackDate = null,
        string $errorField = 'start_date'
    ): ?string {

        /*
        |--------------------------------------------------------------------------
        | Nothing Provided
        |--------------------------------------------------------------------------
        */

        if (
            empty($date) &&
            empty($time)
        ) {

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback Date
        |--------------------------------------------------------------------------
        */

        $date =
            $date
            ?: $fallbackDate;


        /*
        |--------------------------------------------------------------------------
        | Date Required
        |--------------------------------------------------------------------------
        */

        if (
            empty($date)
        ) {

            throw ValidationException::withMessages([
                $errorField =>
                    'A valid date is required when time is provided.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Default Time
        |--------------------------------------------------------------------------
        */

        $time =
            $time
            ?: '00:00';


        /*
        |--------------------------------------------------------------------------
        | Build Datetime
        |--------------------------------------------------------------------------
        */

        try {

            return Carbon::createFromFormat(
                'Y-m-d H:i',
                "{$date} {$time}"
            )->format(
                'Y-m-d H:i:s'
            );

        } catch (\Throwable $exception) {

            throw ValidationException::withMessages([
                $errorField =>
                    'Invalid date or time value.',
            ]);
        }
    }
}