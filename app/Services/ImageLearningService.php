<?php

namespace App\Services;

use App\Models\ImageOptimizationLog;
use Throwable;

class ImageLearningService
{
    /**
     * Maximum historical records used for adaptive learning.
     */
    protected int $historyLimit = 120;

    /**
     * Maximum positive/negative adaptive boost.
     */
    protected int $maxBoost = 15;

    /**
     * Maximum adaptive confidence.
     */
    protected int $maxConfidence = 25;

    /**
     * Minimum history required before adaptive learning starts.
     */
    protected int $minimumHistory = 5;

    /**
     * Get adaptive optimization boost based on route history.
     */
    public function getAdaptiveBoost(
        string $routePath,
        string $context,
        int $position
    ): array {

        try {

            /*
            |--------------------------------------------------------------------------
            | Normalize Input
            |--------------------------------------------------------------------------
            */

            $routePath = $this->normalizeRoute($routePath);

            $context = $this->normalizeContext($context);

            $position = max(1, $position);

            /*
            |--------------------------------------------------------------------------
            | Historical Data
            |--------------------------------------------------------------------------
            */

            $logs = ImageOptimizationLog::query()
                ->where('route_path', $routePath)
                ->whereNull('deleted_at')
                ->latest('id')
                ->limit($this->historyLimit)
                ->get([
                    'mode',
                    'score',
                    'image_position',
                    'confidence',
                    'image_role',
                ]);

            /*
            |--------------------------------------------------------------------------
            | Not Enough History
            |--------------------------------------------------------------------------
            */

            if ($logs->count() < $this->minimumHistory) {
                return $this->emptyResult();
            }

            /*
            |--------------------------------------------------------------------------
            | Counters
            |--------------------------------------------------------------------------
            */

            $criticalCount = $logs->where('mode', 'critical')->count();

            $importantCount = $logs->where('mode', 'important')->count();

            $deferredCount = $logs->where('mode', 'deferred')->count();

            $highScoreCount = $logs
                ->filter(
                    fn ($log) => (int) $log->score >= 80
                )
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Position Pattern
            |--------------------------------------------------------------------------
            */

            $nearPositionCount = $logs
                ->filter(function ($log) use ($position) {

                    $historicalPosition = (int) $log->image_position;

                    if ($historicalPosition <= 0) {
                        return false;
                    }

                    return abs($historicalPosition - $position) <= 1;
                })
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Calculate Ratios
            |--------------------------------------------------------------------------
            */

            $totalLogs = max(1, $logs->count());

            $criticalRatio = $criticalCount / $totalLogs;

            $importantRatio = $importantCount / $totalLogs;

            $deferredRatio = $deferredCount / $totalLogs;

            $highScoreRatio = $highScoreCount / $totalLogs;

            $positionRatio = $nearPositionCount / $totalLogs;

            /*
            |--------------------------------------------------------------------------
            | Initial Values
            |--------------------------------------------------------------------------
            */

            $boost = 0;

            $confidence = 0;

            $reasons = [];

            /*
            |--------------------------------------------------------------------------
            | Critical Pattern
            |--------------------------------------------------------------------------
            */

            if (
                $criticalCount >= 15 &&
                $criticalRatio >= 0.15 &&
                $position <= 2
            ) {

                $boost += 6;

                $confidence += 7;

                $reasons[] =
                    'Historical critical-image pattern detected near the top of the route.';
            }

            /*
            |--------------------------------------------------------------------------
            | Important Pattern
            |--------------------------------------------------------------------------
            */

            if (
                $importantCount >= 20 &&
                $importantRatio >= 0.20 &&
                $position <= 3
            ) {

                $boost += 4;

                $confidence += 5;

                $reasons[] =
                    'Historical important-image pattern detected near the top of the route.';
            }

            /*
            |--------------------------------------------------------------------------
            | Deferred Pattern
            |--------------------------------------------------------------------------
            */

            if (
                $deferredCount >= 25 &&
                $deferredRatio >= 0.35 &&
                $position >= 5
            ) {

                $boost -= 5;

                $confidence += 5;

                $reasons[] =
                    'Historical pattern indicates lower-position images are commonly deferred.';
            }

            /*
            |--------------------------------------------------------------------------
            | Position Pattern
            |--------------------------------------------------------------------------
            */

            if (
                $nearPositionCount >= 10 &&
                $positionRatio >= 0.10
            ) {

                /*
                | Position is useful, but should not overpower
                | critical/important/deferred decisions.
                */

                $boost += 2;

                $confidence += 3;

                $reasons[] =
                    'Similar image-position pattern detected from route history.';
            }

            /*
            |--------------------------------------------------------------------------
            | High Score Pattern
            |--------------------------------------------------------------------------
            */

            if (
                $highScoreCount >= 15 &&
                $highScoreRatio >= 0.15 &&
                $position <= 3
            ) {

                $boost += 2;

                $confidence += 3;

                $reasons[] =
                    'Historical high-priority image score pattern detected.';
            }

            /*
            |--------------------------------------------------------------------------
            | Role-Based Learning
            |--------------------------------------------------------------------------
            |
            | Current ImageOptimizationService provides image context.
            | We can safely detect common roles from that context without
            | adding an expensive DB query.
            |
            */

            $currentRole = $this->detectImageRole($context);

            if ($currentRole !== null) {

                $roleCount = $logs
                    ->where('image_role', $currentRole)
                    ->count();

                $roleRatio = $roleCount / $totalLogs;

                if (
                    $roleCount >= 5 &&
                    $roleRatio >= 0.08
                ) {

                    if ($currentRole === 'hero' && $position <= 3) {

                        $boost += 2;

                        $confidence += 2;

                        $reasons[] =
                            'Historical hero-image pattern matched.';
                    }

                    if ($currentRole === 'icon' && $position >= 5) {

                        $boost -= 1;

                        $confidence += 1;

                        $reasons[] =
                            'Historical icon-image pattern matched.';
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Confidence From History Size
            |--------------------------------------------------------------------------
            */

            if ($logs->count() >= 50) {

                $confidence += 2;

            } elseif ($logs->count() >= 20) {

                $confidence += 1;
            }

            /*
            |--------------------------------------------------------------------------
            | Clamp Boost
            |--------------------------------------------------------------------------
            */

            $boost = max(
                -$this->maxBoost,
                min($this->maxBoost, $boost)
            );

            /*
            |--------------------------------------------------------------------------
            | Clamp Confidence
            |--------------------------------------------------------------------------
            */

            $confidence = max(
                0,
                min($this->maxConfidence, $confidence)
            );

            /*
            |--------------------------------------------------------------------------
            | Remove Duplicate Reasons
            |--------------------------------------------------------------------------
            */

            $reasons = array_values(
                array_unique(
                    array_filter($reasons)
                )
            );

            /*
            |--------------------------------------------------------------------------
            | Return
            |--------------------------------------------------------------------------
            */

            return [
                'boost' => $boost,
                'confidence' => $confidence,
                'reasons' => $reasons,
            ];

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Learning must NEVER break the actual image optimization.
            |--------------------------------------------------------------------------
            */

            report($e);

            return $this->emptyResult();
        }
    }

    /**
     * Normalize route path.
     */
    protected function normalizeRoute(string $routePath): string
    {
        $routePath = trim($routePath);

        if ($routePath === '') {
            return '/';
        }

        return '/' . trim($routePath, '/');
    }

    /**
     * Normalize HTML context.
     */
    protected function normalizeContext(string $context): string
    {
        $context = strtolower(trim($context));

        /*
        | Prevent unnecessarily large strings from entering
        | the learning logic.
        */

        if (strlen($context) > 2000) {
            $context = substr($context, 0, 2000);
        }

        return $context;
    }

    /**
     * Detect common image role from HTML context.
     */
    protected function detectImageRole(string $context): ?string
    {
        if ($context === '') {
            return null;
        }

        if (
            str_contains($context, 'hero') ||
            str_contains($context, 'banner')
        ) {
            return 'hero';
        }

        if (
            str_contains($context, 'logo') ||
            str_contains($context, 'brand')
        ) {
            return 'logo';
        }

        if (
            str_contains($context, 'product') ||
            str_contains($context, 'item')
        ) {
            return 'product';
        }

        if (
            str_contains($context, 'icon') ||
            str_contains($context, 'avatar')
        ) {
            return 'icon';
        }

        return 'content';
    }

    /**
     * Empty adaptive learning result.
     */
    protected function emptyResult(): array
    {
        return [
            'boost' => 0,
            'confidence' => 0,
            'reasons' => [],
        ];
    }
}