<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DriverManagement\StoreDriverRequest;
use App\Http\Requests\Backend\DriverManagement\UpdateDriverRequest;
use App\Services\DriverManagement\DriverManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        return view('backend.driver-management.index',
            compact('drivers')
        );
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
    
            Log::error(
                'Driver creation failed.',
                [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
    
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create driver. Please try again.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        string $id
    ): View {

        $driver =
            $this->driverService->findById($id);

        return view('backend.driver-management.show',
            compact('driver')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        string $id
    ): View {

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

            $driver =
                $this->driverService->findById($id);

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

            Log::error(
                'Driver update failed.',
                [
                    'driver_id' => $id,
                    'user_id' => Auth::id(),
                    'exception' => $e,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update driver. Please try again.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        string $id
    ): RedirectResponse {

        try {

            $driver =
                $this->driverService->findById($id);

            $this->driverService->delete(
                $driver
            );

            return redirect()
                ->route('driver-management.index')
                ->with(
                    'message',
                    'Driver deleted successfully.'
                );

        } catch (\Throwable $e) {

            Log::error(
                'Driver deletion failed.',
                [
                    'driver_id' => $id,
                    'user_id' => Auth::id(),
                    'exception' => $e,
                ]
            );

            return back()
                ->with(
                    'error',
                    'Unable to delete driver. Please try again.'
                );
        }
    }
}