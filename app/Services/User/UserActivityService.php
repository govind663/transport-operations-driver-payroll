<?php

namespace App\Services\User;

use App\Models\UserHistory;
use Illuminate\Support\Facades\Auth;

class UserActivityService
{
    /*
    |--------------------------------------------------------------------------
    | LOG ACTIVITY
    |--------------------------------------------------------------------------
    |
    | User activity ke saath IP, device aur optional browser location
    | information save karne ke liye.
    |
    */

    public function log(
        string $activity,
        ?int $userId = null,
        array $location = []
    ): ?UserHistory {
        try {
            $request = request();

            $userId = $userId ?? Auth::id();
            $userAgent = $request->userAgent();

            $data = [
                'user_id'       => $userId,
                'activity'      => $activity,
                'ip_address'    => $request->ip(),
                'city'          => null,
                'country'       => null,
                'device'        => $this->detectDevice($userAgent),
                'user_agent'    => $userAgent,
                'activity_time' => now(),
            ];

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL LOCATION DATA
            |--------------------------------------------------------------------------
            |
            | Coordinates tabhi save honge jab caller location provide kare.
            | Browser permission aur validation controller mein handle honi chahiye.
            |
            */

            if ($this->hasValidCoordinates($location)) {
                $data['latitude'] = (float) $location['latitude'];
                $data['longitude'] = (float) $location['longitude'];

                if (
                    isset($location['accuracy'])
                    && is_numeric($location['accuracy'])
                    && (float) $location['accuracy'] >= 0
                ) {
                    $data['location_accuracy'] =
                        (float) $location['accuracy'];
                }

                $data['location_captured_at'] = now();
            }

            return UserHistory::create($data);
        } catch (\Throwable $e) {
            /*
            |--------------------------------------------------------------------------
            | LOGGING FAILURE MUST NOT BREAK MAIN OPERATION
            |--------------------------------------------------------------------------
            */

            report($e);

            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE LOCATION COORDINATES
    |--------------------------------------------------------------------------
    */

    protected function hasValidCoordinates(array $location): bool
    {
        if (
            !isset($location['latitude'], $location['longitude'])
            || !is_numeric($location['latitude'])
            || !is_numeric($location['longitude'])
        ) {
            return false;
        }

        $latitude = (float) $location['latitude'];
        $longitude = (float) $location['longitude'];

        return $latitude >= -90
            && $latitude <= 90
            && $longitude >= -180
            && $longitude <= 180;
    }

    /*
    |--------------------------------------------------------------------------
    | DEVICE DETECTION
    |--------------------------------------------------------------------------
    */

    protected function detectDevice(?string $userAgent): string
    {
        if (empty($userAgent)) {
            return 'Unknown';
        }

        $userAgent = strtolower($userAgent);

        if (
            str_contains($userAgent, 'ipad')
            || str_contains($userAgent, 'tablet')
        ) {
            return 'Tablet';
        }

        if (
            str_contains($userAgent, 'mobile')
            || str_contains($userAgent, 'android')
        ) {
            return 'Mobile';
        }

        return 'Desktop';
    }
}
