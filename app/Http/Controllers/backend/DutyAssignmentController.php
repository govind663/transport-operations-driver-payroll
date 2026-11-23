<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DutyAssignment\StoreDutyAssignmentRequest;
use App\Http\Requests\Backend\DutyAssignment\UpdateDutyAssignmentRequest;
use App\Models\Driver;
use App\Models\DutyAssignment;
use App\Models\TravelRequest;
use App\Models\User;
use App\Models\VehicleManagement;
use App\Services\DutyAssignment\DutyAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DutyAssignmentController extends Controller
{
    /**
     * Duty Assignment Service.
     */
    protected DutyAssignmentService $dutyAssignmentService;

    /**
     * Constructor.
     */
    public function __construct(
        DutyAssignmentService $dutyAssignmentService
    ) {
        $this->dutyAssignmentService = $dutyAssignmentService;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    /**
     * Allow only Admin, Operations and Accountant.
     */
    protected function authorizeManagementAccess(): User
    {
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthenticated access.'
        );

        abort_unless(
            $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant(),
            403,
            'Unauthorized access.'
        );

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display accessible duty assignments.
     *
     * Admin / Operations / Accountant:
     * - Can view all assignments.
     * - Can filter by driver and vehicle.
     *
     * Driver:
     * - Can view own assignments only.
     * - Can use applicable filters.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE FILTERS
        |--------------------------------------------------------------------------
        */

        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],

            'driver_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'vehicle_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                'in:pending,assigned,accepted,rejected,started,completed,cancelled',
            ],

            'date_from' => [
                'nullable',
                'date',
            ],

            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET FILTERED DUTY ASSIGNMENTS
        |--------------------------------------------------------------------------
        */

        $dutyAssignments = $this->dutyAssignmentService
            ->getDutyAssignments($filters);

        /*
        |--------------------------------------------------------------------------
        | GET FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $filterOptions = $this->dutyAssignmentService
            ->getFilterOptions();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.duty-assignments.index',
            compact(
                'dutyAssignments',
                'filters',
                'filterOptions'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new duty assignment.
     *
     * Only management roles can create assignments.
     */
    public function create(): View
    {
        $this->authorizeManagementAccess();

        $travelRequests = TravelRequest::query()
            ->whereIn('status', [
                TravelRequest::STATUS_PENDING,
                TravelRequest::STATUS_APPROVED,
                TravelRequest::STATUS_ASSIGNED,
            ])
            ->latest('id')
            ->get();

        $drivers = Driver::query()
            ->latest('id')
            ->get();

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();

        return view(
            'backend.duty-assignments.create',
            compact(
                'travelRequests',
                'drivers',
                'vehicles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created duty assignment.
     *
     * Only management roles can store assignments.
     */
    public function store(
        StoreDutyAssignmentRequest $request
    ): RedirectResponse {

        $this->authorizeManagementAccess();

        $this->dutyAssignmentService->store(
            $request->validated()
        );

        return redirect()
            ->route('duty-assignments.index')
            ->with(
                'success',
                'Duty assignment created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified duty assignment.
     *
     * Driver can access only their own assignment.
     * Management roles can access all assignments.
     */
    public function show(
        DutyAssignment $dutyAssignment
    ): View {

        $dutyAssignment = $this->dutyAssignmentService
            ->findById($dutyAssignment->id);

        return view(
            'backend.duty-assignments.show',
            compact('dutyAssignment')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing the specified duty assignment.
     *
     * Only management roles can edit assignments.
     */
    public function edit(
        DutyAssignment $dutyAssignment
    ): View {

        $this->authorizeManagementAccess();

        $dutyAssignment = $this->dutyAssignmentService
            ->findById($dutyAssignment->id);

        $travelRequests = TravelRequest::query()
            ->latest('id')
            ->get();

        $drivers = Driver::query()
            ->latest('id')
            ->get();

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();

        return view(
            'backend.duty-assignments.edit',
            compact(
                'dutyAssignment',
                'travelRequests',
                'drivers',
                'vehicles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update the specified duty assignment.
     *
     * Only management roles can update assignments.
     */
    public function update(
        UpdateDutyAssignmentRequest $request,
        DutyAssignment $dutyAssignment
    ): RedirectResponse {

        $this->authorizeManagementAccess();

        $this->dutyAssignmentService->update(
            $dutyAssignment,
            $request->validated()
        );

        return redirect()
            ->route('duty-assignments.index')
            ->with(
                'success',
                'Duty assignment updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the specified duty assignment.
     *
     * Only management roles can delete assignments.
     */
    public function destroy(
        DutyAssignment $dutyAssignment
    ): RedirectResponse {

        $this->authorizeManagementAccess();

        $this->dutyAssignmentService->delete(
            $dutyAssignment
        );

        return redirect()
            ->route('duty-assignments.index')
            ->with(
                'success',
                'Duty assignment deleted successfully.'
            );
    }
}
