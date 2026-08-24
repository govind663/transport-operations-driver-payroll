<?php

namespace App\Services\DriverManagement;

use App\Models\Driver;
use App\Services\FileUploadService;
use App\Services\User\UserService;
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
     * User Service
     */
    protected UserService $userService;


    /**
     * Constructor
     */
    public function __construct(
        FileUploadService $fileUploadService,
        UserService $userService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->userService = $userService;
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
            | PF & DOCUMENT STATUS DEFAULTS
            |--------------------------------------------------------------------------
            */

            $data['pf_status'] =
                $data['pf_status']
                ?? Driver::PF_NO;

            $data['document_status'] =
                $data['document_status']
                ?? Driver::DOCUMENT_PENDING;


            /*
            |--------------------------------------------------------------------------
            | Normalize Basic Values
            |--------------------------------------------------------------------------
            */

            $this->normalizeDriverData($data);


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
            | QUALIFICATIONS
            |--------------------------------------------------------------------------
            */

            $qualificationDocuments =
                $data['qualification_documents'] ?? [];

            $qualifications =
                $data['qualifications'] ?? [];

            $data['driver_qualifications'] =
                $this->processQualifications(
                    $qualifications,
                    $qualificationDocuments
                );


            /*
            |--------------------------------------------------------------------------
            | NOMINEES
            |--------------------------------------------------------------------------
            */

            $nominees =
                $data['nominees'] ?? [];

            $nomineeProfileImages =
                $data['nominee_profile_images'] ?? [];

            $data['driver_nominees'] =
                $this->processNominees(
                    $nominees,
                    $nomineeProfileImages
                );


            /*
            |--------------------------------------------------------------------------
            | BANK DETAILS
            |--------------------------------------------------------------------------
            */

            $data['driver_bank_details'] =
                $this->processBankDetails(
                    $data['bank_details'] ?? []
                );


            /*
            |--------------------------------------------------------------------------
            | REMOVE FORM-ONLY FIELDS
            |--------------------------------------------------------------------------
            */

            unset(
                $data['qualifications'],
                $data['qualification_documents'],
                $data['nominees'],
                $data['nominee_profile_images'],
                $data['bank_details']
            );


            /*
            |--------------------------------------------------------------------------
            | CREATE DRIVER
            |--------------------------------------------------------------------------
            */

            $driver = Driver::create($data);


            /*
            |--------------------------------------------------------------------------
            | CREATE DRIVER LOGIN CREDENTIAL
            |--------------------------------------------------------------------------
            */

            $this->userService->createDriverCredential(
                $driver
            );


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
            | Normalize Basic Values
            |--------------------------------------------------------------------------
            */

            $this->normalizeDriverData($data);


            /*
            |--------------------------------------------------------------------------
            | DRIVER PHOTO
            |--------------------------------------------------------------------------
            */

            $newDriverPhoto = null;

            if (
                isset($data['driver_photo']) &&
                $data['driver_photo']
            ) {

                $newDriverPhoto =
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
                    $newDriverPhoto;
            }


            /*
            |--------------------------------------------------------------------------
            | DRIVING LICENCE DOCUMENT
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['driving_license_document']) &&
                $data['driving_license_document']
            ) {

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
            | QUALIFICATIONS
            |--------------------------------------------------------------------------
            */

            $oldQualifications =
                is_array($driver->driver_qualifications)
                    ? $driver->driver_qualifications
                    : [];

            $newQualifications =
                $data['qualifications'] ?? [];

            $qualificationDocuments =
                $data['qualification_documents'] ?? [];


            $data['driver_qualifications'] =
                $this->processQualifications(
                    $newQualifications,
                    $qualificationDocuments,
                    $oldQualifications
                );


            /*
            |--------------------------------------------------------------------------
            | NOMINEES
            |--------------------------------------------------------------------------
            */

            $oldNominees =
                is_array($driver->driver_nominees)
                    ? $driver->driver_nominees
                    : [];

            $newNominees =
                $data['nominees'] ?? [];

            $nomineeProfileImages =
                $data['nominee_profile_images'] ?? [];


            $data['driver_nominees'] =
                $this->processNominees(
                    $newNominees,
                    $nomineeProfileImages,
                    $oldNominees
                );


            /*
            |--------------------------------------------------------------------------
            | BANK DETAILS
            |--------------------------------------------------------------------------
            */

            $data['driver_bank_details'] =
                $this->processBankDetails(
                    $data['bank_details'] ?? []
                );


            /*
            |--------------------------------------------------------------------------
            | REMOVE FORM-ONLY FIELDS
            |--------------------------------------------------------------------------
            */

            unset(
                $data['qualifications'],
                $data['qualification_documents'],
                $data['nominees'],
                $data['nominee_profile_images'],
                $data['bank_details']
            );


            /*
            |--------------------------------------------------------------------------
            | UPDATE DRIVER
            |--------------------------------------------------------------------------
            */

            $driver->update($data);


            /*
            |--------------------------------------------------------------------------
            | UPDATE USER PROFILE IMAGE
            |--------------------------------------------------------------------------
            |
            | If driver already has login credentials and
            | driver photo was changed, synchronize it
            | with users.profile_image.
            |
            |--------------------------------------------------------------------------
            */

            if (
                $newDriverPhoto &&
                !empty($driver->user_id)
            ) {

                $user = $driver->user;

                if ($user) {

                    $user->update([
                        'profile_image' =>
                            $newDriverPhoto,
                        'updated_by' =>
                            Auth::id(),
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | RETURN FRESH DRIVER
            |--------------------------------------------------------------------------
            */

            return $driver->fresh([
                'user',
                'createdBy',
                'updatedBy',
            ]);
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


    /*
    |--------------------------------------------------------------------------
    | PROCESS QUALIFICATIONS
    |--------------------------------------------------------------------------
    */
    protected function processQualifications(
        array $qualifications = [],
        array $qualificationDocuments = [],
        array $oldQualifications = []
    ): array {

        $processedQualifications = [];


        /*
        |--------------------------------------------------------------------------
        | Normalize Existing Qualification Keys
        |--------------------------------------------------------------------------
        */

        $oldQualifications =
            array_values($oldQualifications);


        /*
        |--------------------------------------------------------------------------
        | Process Submitted Qualifications
        |--------------------------------------------------------------------------
        */

        foreach (
            $qualifications as $index => $qualification
        ) {

            if (
                !is_array($qualification)
            ) {
                continue;
            }


            $qualificationName =
                trim(
                    $qualification['qualification'] ?? ''
                );

            $institute =
                trim(
                    $qualification['institute'] ?? ''
                );

            $passingYear =
                $qualification['passing_year'] ?? null;

            $grade =
                trim(
                    $qualification['grade'] ?? ''
                );


            /*
            |--------------------------------------------------------------------------
            | Skip Completely Empty Row
            |--------------------------------------------------------------------------
            */

            if (
                $qualificationName === '' &&
                $institute === '' &&
                empty($passingYear) &&
                $grade === '' &&
                empty($qualificationDocuments[$index])
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Existing Document
            |--------------------------------------------------------------------------
            */

            $existingDocument = null;


            if (
                array_key_exists(
                    $index,
                    $oldQualifications
                )
            ) {

                $existingDocument =
                    $oldQualifications[$index]['document']
                    ?? null;
            }


            /*
            |--------------------------------------------------------------------------
            | New Qualification Document
            |--------------------------------------------------------------------------
            */

            $newDocument = null;


            if (
                isset($qualificationDocuments[$index]) &&
                $qualificationDocuments[$index]
            ) {

                $newDocument =
                    $this->fileUploadService->upload(
                        $qualificationDocuments[$index],
                        'driver/qualification'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Existing Qualification Document
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($existingDocument)
                ) {

                    $this->fileUploadService->delete(
                        $existingDocument
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Preserve Existing Document
            |--------------------------------------------------------------------------
            */

            $document =
                $newDocument
                ?? $existingDocument;


            /*
            |--------------------------------------------------------------------------
            | Build Qualification Record
            |--------------------------------------------------------------------------
            */

            $processedQualifications[] = [

                'qualification' =>
                    $qualificationName !== ''
                        ? $qualificationName
                        : null,

                'institute' =>
                    $institute !== ''
                        ? $institute
                        : null,

                'passing_year' =>
                    !empty($passingYear)
                        ? (int) $passingYear
                        : null,

                'grade' =>
                    $grade !== ''
                        ? $grade
                        : null,

                'document' =>
                    $document,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Documents of Removed Qualifications
        |--------------------------------------------------------------------------
        */

        $submittedQualificationIndexes =
            array_keys($qualifications);


        foreach (
            $oldQualifications as $oldIndex => $oldQualification
        ) {

            if (
                !in_array(
                    $oldIndex,
                    $submittedQualificationIndexes,
                    true
                )
            ) {

                $oldDocument =
                    $oldQualification['document']
                    ?? null;

                if (
                    !empty($oldDocument)
                ) {

                    $this->fileUploadService->delete(
                        $oldDocument
                    );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return Sequential Array
        |--------------------------------------------------------------------------
        */

        return array_values(
            $processedQualifications
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESS NOMINEES
    |--------------------------------------------------------------------------
    |
    | Handles:
    |
    | - Multiple nominees
    | - Nominee profile images
    | - Existing profile image preservation
    | - Profile image replacement
    | - Removal of old nominee images
    |
    |--------------------------------------------------------------------------
    */
    protected function processNominees(
        array $nominees = [],
        array $nomineeProfileImages = [],
        array $oldNominees = []
    ): array {

        $processedNominees = [];


        /*
        |--------------------------------------------------------------------------
        | Normalize Existing Nominees
        |--------------------------------------------------------------------------
        */

        $oldNominees =
            array_values($oldNominees);


        /*
        |--------------------------------------------------------------------------
        | Process Submitted Nominees
        |--------------------------------------------------------------------------
        */

        foreach (
            $nominees as $index => $nominee
        ) {

            /*
            |--------------------------------------------------------------------------
            | Skip Invalid Row
            |--------------------------------------------------------------------------
            */

            if (
                !is_array($nominee)
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize Values
            |--------------------------------------------------------------------------
            */

            $name =
                trim(
                    $nominee['name'] ?? ''
                );

            $relationship =
                trim(
                    $nominee['relationship'] ?? ''
                );

            $dateOfBirth =
                $nominee['date_of_birth'] ?? null;

            $mobile =
                preg_replace(
                    '/[^0-9]/',
                    '',
                    $nominee['mobile'] ?? ''
                );

            $address =
                trim(
                    $nominee['address'] ?? ''
                );

            $percentage =
                $nominee['percentage'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | Skip Completely Empty Row
            |--------------------------------------------------------------------------
            */

            if (
                $name === '' &&
                $relationship === '' &&
                empty($dateOfBirth) &&
                $mobile === '' &&
                $address === '' &&
                empty($percentage) &&
                empty($nomineeProfileImages[$index])
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Existing Profile Image
            |--------------------------------------------------------------------------
            */

            $existingProfileImage = null;


            if (
                array_key_exists(
                    $index,
                    $oldNominees
                )
            ) {

                $existingProfileImage =
                    $oldNominees[$index]['profile_image']
                    ?? null;
            }


            /*
            |--------------------------------------------------------------------------
            | New Profile Image
            |--------------------------------------------------------------------------
            */

            $newProfileImage = null;


            if (
                isset($nomineeProfileImages[$index]) &&
                $nomineeProfileImages[$index]
            ) {

                $newProfileImage =
                    $this->fileUploadService->upload(
                        $nomineeProfileImages[$index],
                        'driver/nominee'
                    );


                /*
                |--------------------------------------------------------------------------
                | Delete Existing Profile Image
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($existingProfileImage)
                ) {

                    $this->fileUploadService->delete(
                        $existingProfileImage
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Preserve Existing Profile Image
            |--------------------------------------------------------------------------
            */

            $profileImage =
                $newProfileImage
                ?? $existingProfileImage;


            /*
            |--------------------------------------------------------------------------
            | Build Nominee Record
            |--------------------------------------------------------------------------
            */

            $processedNominees[] = [

                'name' =>
                    $name !== ''
                        ? $name
                        : null,

                'relationship' =>
                    $relationship !== ''
                        ? $relationship
                        : null,

                'date_of_birth' =>
                    !empty($dateOfBirth)
                        ? $dateOfBirth
                        : null,

                'mobile' =>
                    $mobile !== ''
                        ? $mobile
                        : null,

                'percentage' =>
                    $percentage !== null &&
                    $percentage !== ''
                        ? (float) $percentage
                        : null,

                'address' =>
                    $address !== ''
                        ? $address
                        : null,

                'profile_image' =>
                    $profileImage,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Profile Images of Removed Nominees
        |--------------------------------------------------------------------------
        */

        $submittedNomineeIndexes =
            array_keys($nominees);


        foreach (
            $oldNominees as $oldIndex => $oldNominee
        ) {

            if (
                !in_array(
                    $oldIndex,
                    $submittedNomineeIndexes,
                    true
                )
            ) {

                $oldProfileImage =
                    $oldNominee['profile_image']
                    ?? null;


                if (
                    !empty($oldProfileImage)
                ) {

                    $this->fileUploadService->delete(
                        $oldProfileImage
                    );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return Sequential Array
        |--------------------------------------------------------------------------
        */

        return array_values(
            $processedNominees
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESS BANK DETAILS
    |--------------------------------------------------------------------------
    */
    protected function processBankDetails(
        array $bankDetails = []
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Normalize Values
        |--------------------------------------------------------------------------
        */

        $accountHolderName =
            trim(
                $bankDetails['account_holder_name']
                ?? ''
            );

        $bankName =
            trim(
                $bankDetails['bank_name']
                ?? ''
            );

        $accountNumber =
            trim(
                $bankDetails['account_number']
                ?? ''
            );

        $ifscCode =
            strtoupper(
                trim(
                    $bankDetails['ifsc_code']
                    ?? ''
                )
            );

        $branchName =
            trim(
                $bankDetails['branch_name']
                ?? ''
            );

        $accountType =
            strtolower(
                trim(
                    $bankDetails['account_type']
                    ?? ''
                )
            );

        $upiId =
            trim(
                $bankDetails['upi_id']
                ?? ''
            );


        /*
        |--------------------------------------------------------------------------
        | No Bank Details
        |--------------------------------------------------------------------------
        */

        if (
            $accountHolderName === '' &&
            $bankName === '' &&
            $accountNumber === '' &&
            $ifscCode === '' &&
            $branchName === '' &&
            $accountType === '' &&
            $upiId === ''
        ) {

            return [];
        }


        /*
        |--------------------------------------------------------------------------
        | Return Bank Details
        |--------------------------------------------------------------------------
        */

        return [

            'account_holder_name' =>
                $accountHolderName !== ''
                    ? $accountHolderName
                    : null,

            'bank_name' =>
                $bankName !== ''
                    ? $bankName
                    : null,

            'account_number' =>
                $accountNumber !== ''
                    ? $accountNumber
                    : null,

            'ifsc_code' =>
                $ifscCode !== ''
                    ? $ifscCode
                    : null,

            'branch_name' =>
                $branchName !== ''
                    ? $branchName
                    : null,

            'account_type' =>
                in_array(
                    $accountType,
                    [
                        'savings',
                        'current',
                    ],
                    true
                )
                    ? $accountType
                    : null,

            'upi_id' =>
                $upiId !== ''
                    ? $upiId
                    : null,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DRIVER DATA
    |--------------------------------------------------------------------------
    */
    protected function normalizeDriverData(
        array &$data
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Driver Code
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['driver_code'])
        ) {

            $data['driver_code'] =
                strtoupper(
                    preg_replace(
                        '/\s+/',
                        '',
                        trim(
                            $data['driver_code']
                        )
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Names
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'first_name',
                'last_name',
                'father_name',
            ] as $field
        ) {

            if (
                isset($data[$field])
            ) {

                $data[$field] =
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim(
                            $data[$field]
                        )
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'mobile',
                'alternate_mobile',
            ] as $field
        ) {

            if (
                isset($data[$field])
            ) {

                $data[$field] =
                    preg_replace(
                        '/[^0-9]/',
                        '',
                        $data[$field]
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Email
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['email'])
        ) {

            $data['email'] =
                strtolower(
                    trim(
                        $data['email']
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | License Number
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['license_number'])
        ) {

            $data['license_number'] =
                strtoupper(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim(
                            $data['license_number']
                        )
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | License Authority
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['license_issuing_authority'])
        ) {

            $data['license_issuing_authority'] =
                preg_replace(
                    '/\s+/',
                    ' ',
                    trim(
                        $data['license_issuing_authority']
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Aadhaar
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['aadhar_number'])
        ) {

            $data['aadhar_number'] =
                preg_replace(
                    '/[^0-9]/',
                    '',
                    $data['aadhar_number']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PAN
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['pan_number'])
        ) {

            $data['pan_number'] =
                strtoupper(
                    preg_replace(
                        '/[^A-Z0-9]/i',
                        '',
                        $data['pan_number']
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Address Fields
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'country',
                'state',
                'city',
                'address',
            ] as $field
        ) {

            if (
                isset($data[$field])
            ) {

                $data[$field] =
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim(
                            $data[$field]
                        )
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Pincode
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['pincode'])
        ) {

            $data['pincode'] =
                preg_replace(
                    '/[^0-9]/',
                    '',
                    $data['pincode']
                );
        }
    }
}