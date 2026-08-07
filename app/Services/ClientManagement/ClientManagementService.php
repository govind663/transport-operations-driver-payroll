<?php

namespace App\Services\ClientManagement;

use App\Models\Client;
use App\Services\FileUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ClientManagementService
{
    /**
     * File Upload Service
     */
    protected FileUploadService $fileUploadService;

    /**
     * Constructor
     */
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Clients
    |--------------------------------------------------------------------------
    */

    public function getClients(): Collection
    {
        return Client::latest()->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Find Client
    |--------------------------------------------------------------------------
    */

    public function findById(int|string $id): Client
    {
        return Client::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Client
    |--------------------------------------------------------------------------
    */

    public function store(array $data): Client
    {
        /*
        |--------------------------------------------------------------------------
        | Company Logo Upload
        |--------------------------------------------------------------------------
        */

        if (isset($data['company_logo'])) {

            $data['company_logo'] = $this->fileUploadService->upload(
                $data['company_logo'],
                'client/company-logo'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['created_by'] = Auth::id();

        return Client::create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Client
    |--------------------------------------------------------------------------
    */

    public function update(Client $client, array $data): Client
    {
        /*
        |--------------------------------------------------------------------------
        | Replace Company Logo
        |--------------------------------------------------------------------------
        */

        if (isset($data['company_logo'])) {

            $data['company_logo'] = $this->fileUploadService->replace(
                $data['company_logo'],
                $client->company_logo,
                'client/company-logo'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['updated_by'] = Auth::id();

        $client->update($data);

        return $client->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Client
    |--------------------------------------------------------------------------
    */

    public function delete(Client $client): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $client->update([
            'deleted_by' => Auth::id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Delete Logo
        |--------------------------------------------------------------------------
        */

        if ($client->company_logo) {

            $this->fileUploadService->delete(
                $client->company_logo
            );
        }

        return (bool) $client->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Active Clients
    |--------------------------------------------------------------------------
    */

    public function getActiveClients(): Collection
    {
        return Client::active()
            ->orderBy('company_name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Search Clients
    |--------------------------------------------------------------------------
    */

    public function search(?string $keyword): Collection
    {
        return Client::query()

            ->when($keyword, function ($query) use ($keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->where('company_name', 'like', "%{$keyword}%")
                        ->orWhere('client_code', 'like', "%{$keyword}%")
                        ->orWhere('contact_person', 'like', "%{$keyword}%")
                        ->orWhere('mobile', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");

                });

            })

            ->latest()

            ->get();
    }
}