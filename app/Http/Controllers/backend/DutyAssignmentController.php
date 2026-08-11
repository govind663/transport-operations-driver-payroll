<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DutyAssignment\StoreDutyAssignmentRequest;
use App\Http\Requests\Backend\DutyAssignment\UpdateDutyAssignmentRequest;
use App\Models\Driver;
use App\Models\DutyAssignment;
use App\Models\TravelRequest;
use App\Models\VehicleManagement;
use App\Services\DutyAssignment\DutyAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DutyAssignmentController extends Controller
{
    /**
     * Duty Assignment Service
     */
    protected DutyAssignmentService $dutyAssignmentService;

    /**
     * Constructor
     */
    public function __construct(
        DutyAssignmentService $dutyAssignmentService
    ) {
        $this->dutyAssignmentService = $dutyAssignmentService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of duty assignments.
     */
    public function index(): View
    {
        $dutyAssignments =
            $this->dutyAssignmentService->getDutyAssignments();

        return view('backend.duty-assignments.index',
            compact('dutyAssignments')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new duty assignment.
     */
    public function create(): View
    {
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

        return view('backend.duty-assignments.create',
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
     */
    public function store(
        StoreDutyAssignmentRequest $request
    ): RedirectResponse {

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
     */
    public function show(
        DutyAssignment $dutyAssignment
    ): View {

        $dutyAssignment =
            $this->dutyAssignmentService->findById(
                $dutyAssignment->id
            );

        return view('backend.duty-assignments.show',
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
     */
    public function edit(
        DutyAssignment $dutyAssignment
    ): View {

        $dutyAssignment =
            $this->dutyAssignmentService->findById(
                $dutyAssignment->id
            );

        $travelRequests = TravelRequest::query()
            ->latest('id')
            ->get();

        $drivers = Driver::query()
            ->latest('id')
            ->get();

        $vehicles = VehicleManagement::query()
            ->latest('id')
            ->get();

        return view('backend.duty-assignments.edit',
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
     */
    public function update(
        UpdateDutyAssignmentRequest $request,
        DutyAssignment $dutyAssignment
    ): RedirectResponse {

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
     */
    public function destroy(
        DutyAssignment $dutyAssignment
    ): RedirectResponse {

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