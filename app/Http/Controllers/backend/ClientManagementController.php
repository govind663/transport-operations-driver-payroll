<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ClientManagement\StoreClientRequest;
use App\Http\Requests\Backend\ClientManagement\UpdateClientRequest;
use App\Services\ClientManagement\ClientManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientManagementController extends Controller
{
    /**
     * Client Management Service
     */
    protected ClientManagementService $clientService;

    /**
     * Constructor
     */
    public function __construct(ClientManagementService $clientService)
    {
        $this->clientService = $clientService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $clients = $this->clientService->getClients();

        return view('backend.client-management.index', compact('clients'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view('backend.client-management.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(StoreClientRequest $request): RedirectResponse
    {
        try {

            $this->clientService->store($request->validated());

            return redirect()
                ->route('client-management.index')
                ->with('message', 'Client created successfully.');

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(string $id): View
    {
        $client = $this->clientService->findById($id);

        return view('backend.client-management.show', compact('client'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(string $id): View
    {
        $client = $this->clientService->findById($id);

        return view('backend.client-management.edit', compact('client'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(UpdateClientRequest $request, string $id): RedirectResponse
    {
        try {

            $client = $this->clientService->findById($id);

            $this->clientService->update(
                $client,
                $request->validated()
            );

            return redirect()
                ->route('client-management.index')
                ->with('message', 'Client updated successfully.');

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
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

            $client = $this->clientService->findById($id);

            $this->clientService->delete($client);

            return redirect()
                ->route('client-management.index')
                ->with('message', 'Client deleted successfully.');

        } catch (\Throwable $e) {

            return back()
                ->with('error', $e->getMessage());
        }
    }
}