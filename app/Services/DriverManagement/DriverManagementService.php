<?php

namespace App\Services\DriverManagement;

use App\Models\Driver;
use App\Services\FileUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverManagementService
{
    /**
     * File Upload Service
     */
    protected FileUploadService $fileUploadService;


    /**
     * Constructor
     */
    public function __construct(
        FileUploadService $fileUploadService
    ) {
        $this->fileUploadService = $fileUploadService;
    }


    /*
    |--------------------------------------------------------------------------
    | GET DRIVERS
    |--------------------------------------------------------------------------
    */

    public function getDrivers(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Driver::query()
            ->with([
                'createdBy',
                'updatedBy',
            ])
            ->latest('id')
            ->paginate($perPage);
    }


    /*
    |--------------------------------------------------------------------------
    | FIND DRIVER
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): Driver {

        return Driver::query()
            ->with([
                'createdBy',
                'updatedBy',
                'deletedBy',
            ])
            ->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE DRIVER
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data
    ): Driver {

        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

            $data['created_by'] = Auth::id();


            /*
            |--------------------------------------------------------------------------
            | DRIVER PHOTO
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['driver_photo']) &&
                $data['driver_photo']
            ) {

                $data['driver_photo'] =
                    $this->fileUploadService->upload(
                        $data['driver_photo'],
                        'driver'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | DRIVING LICENCE DOCUMENT
            |--------------------------------------------------------------------------
            |
            | Database field:
            | driving_license_document
            |
            | Upload folder:
            | driver/license
            |
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['driving_license_document']) &&
                $data['driving_license_document']
            ) {

                $data['driving_license_document'] =
                    $this->fileUploadService->upload(
                        $data['driving_license_document'],
                        'driver/license'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | AADHAAR DOCUMENT
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['aadhar_document']) &&
                $data['aadhar_document']
            ) {

                $data['aadhar_document'] =
                    $this->fileUploadService->upload(
                        $data['aadhar_document'],
                        'driver/aadhar'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | PAN DOCUMENT
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['pan_document']) &&
                $data['pan_document']
            ) {

                $data['pan_document'] =
                    $this->fileUploadService->upload(
                        $data['pan_document'],
                        'driver/pan'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE DRIVER
            |--------------------------------------------------------------------------
            */

            return Driver::create($data);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DRIVER
    |--------------------------------------------------------------------------
    */

    public function update(
        Driver $driver,
        array $data
    ): Driver {

        return DB::transaction(function () use (
            $driver,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $data['updated_by'] = Auth::id();


            /*
            |--------------------------------------------------------------------------
            | DRIVER PHOTO
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['driver_photo']) &&
                $data['driver_photo']
            ) {

                $newPhoto =
                    $this->fileUploadService->upload(
                        $data['driver_photo'],
                        'driver'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Old Photo
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($driver->driver_photo)
                ) {

                    $this->fileUploadService->delete(
                        $driver->driver_photo
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Set New Photo
                |--------------------------------------------------------------------------
                */

                $data['driver_photo'] =
                    $newPhoto;
            }


            /*
            |--------------------------------------------------------------------------
            | DRIVING LICENCE DOCUMENT
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | Field name MUST be:
            | driving_license_document
            |
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['driving_license_document']) &&
                $data['driving_license_document']
            ) {

                /*
                |--------------------------------------------------------------------------
                | Upload New Licence
                |--------------------------------------------------------------------------
                */

                $newDocument =
                    $this->fileUploadService->upload(
                        $data['driving_license_document'],
                        'driver/license'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Old Licence
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($driver->driving_license_document)
                ) {

                    $this->fileUploadService->delete(
                        $driver->driving_license_document
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Set New Licence
                |--------------------------------------------------------------------------
                */

                $data['driving_license_document'] =
                    $newDocument;
            }


            /*
            |--------------------------------------------------------------------------
            | AADHAAR DOCUMENT
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['aadhar_document']) &&
                $data['aadhar_document']
            ) {

                /*
                |--------------------------------------------------------------------------
                | Upload New Aadhaar
                |--------------------------------------------------------------------------
                */

                $newDocument =
                    $this->fileUploadService->upload(
                        $data['aadhar_document'],
                        'driver/aadhar'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Old Aadhaar
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($driver->aadhar_document)
                ) {

                    $this->fileUploadService->delete(
                        $driver->aadhar_document
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Set New Aadhaar
                |--------------------------------------------------------------------------
                */

                $data['aadhar_document'] =
                    $newDocument;
            }


            /*
            |--------------------------------------------------------------------------
            | PAN DOCUMENT
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['pan_document']) &&
                $data['pan_document']
            ) {

                /*
                |--------------------------------------------------------------------------
                | Upload New PAN
                |--------------------------------------------------------------------------
                */

                $newDocument =
                    $this->fileUploadService->upload(
                        $data['pan_document'],
                        'driver/pan'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Old PAN
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($driver->pan_document)
                ) {

                    $this->fileUploadService->delete(
                        $driver->pan_document
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Set New PAN
                |--------------------------------------------------------------------------
                */

                $data['pan_document'] =
                    $newDocument;
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE DRIVER
            |--------------------------------------------------------------------------
            */

            $driver->update($data);


            /*
            |--------------------------------------------------------------------------
            | RETURN FRESH DRIVER
            |--------------------------------------------------------------------------
            */

            return $driver->fresh();
        });
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE DRIVER
    |--------------------------------------------------------------------------
    */

    public function delete(
        Driver $driver
    ): bool {

        return DB::transaction(function () use ($driver) {

            /*
            |--------------------------------------------------------------------------
            | Deleted By
            |--------------------------------------------------------------------------
            */

            $driver->deleted_by =
                Auth::id();

            $driver->save();


            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            return $driver->delete();
        });
    }
}