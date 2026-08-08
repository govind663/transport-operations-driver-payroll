<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DriverManagement\StoreDriverRequest;
use App\Http\Requests\Backend\DriverManagement\UpdateDriverRequest;
use App\Services\DriverManagement\DriverManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DriverManagementController extends Controller
{
    /**
     * Driver Management Service
     */
    protected DriverManagementService $driverService;

    /**
     * Constructor
     */
    public function __construct(
        DriverManagementService $driverService
    ) {
        $this->driverService = $driverService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(): View
    {
        $drivers = $this->driverService->getDrivers();

        return view('backend.driver-management.index', compact('drivers'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(): View
    {
        return view('backend.driver-management.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(
        StoreDriverRequest $request
    ): RedirectResponse {

        try {

            $this->driverService->store(
                $request->validated()
            );

            return redirect()
                ->route('driver-management.index')
                ->with(
                    'message',
                    'Driver created successfully.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(string $id): View
    {
        $driver = $this->driverService->findById($id);

        return view('backend.driver-management.show', compact('driver'));

    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(string $id): View
    {
        $driver = $this->driverService->findById($id);

        return view('backend.driver-management.edit', compact('driver'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(
        UpdateDriverRequest $request,
        string $id
    ): RedirectResponse {

        try {

            $driver = $this->driverService->findById($id);

            $this->driverService->update(
                $driver,
                $request->validated()
            );

            return redirect()
                ->route('driver-management.index')
                ->with(
                    'message',
                    'Driver updated successfully.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(string $id): RedirectResponse
    {
        try {

            $driver = $this->driverService->findById($id);

            $this->driverService->delete($driver);

            return redirect()
                ->route('driver-management.index')
                ->with(
                    'message',
                    'Driver deleted successfully.'
                );

        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}