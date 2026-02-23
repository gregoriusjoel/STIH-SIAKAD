<?php

namespace App\Services;

class LocationService
{
    /**
     * Campus location coordinates
     */
    const CAMPUS_LAT = -6.311252;
    const CAMPUS_LNG = 106.811174;
    const CAMPUS_RADIUS_METERS = 100;

    /**
     * Calculate distance between two GPS coordinates using Haversine formula
     * 
     * @param float $lat1 Latitude of first point
     * @param float $lng1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lng2 Longitude of second point
     * @return float Distance in meters
     */
    public static function calculateDistanceMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusKm = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distanceKm = $earthRadiusKm * $c;
        $distanceMeters = $distanceKm * 1000;

        return round($distanceMeters, 2);
    }

    /**
     * Calculate distance from student location to campus
     * 
     * @param float $studentLat Student's latitude
     * @param float $studentLng Student's longitude
     * @return float Distance in meters
     */
    public static function calculateDistanceFromCampus(float $studentLat, float $studentLng): float
    {
        return self::calculateDistanceMeters(
            $studentLat,
            $studentLng,
            self::CAMPUS_LAT,
            self::CAMPUS_LNG
        );
    }

    /**
     * Check if student is within campus radius
     * 
     * @param float $studentLat Student's latitude
     * @param float $studentLng Student's longitude
     * @return bool True if within radius, false otherwise
     */
    public static function isWithinCampusRadius(float $studentLat, float $studentLng): bool
    {
        $distance = self::calculateDistanceFromCampus($studentLat, $studentLng);
        return $distance <= self::CAMPUS_RADIUS_METERS;
    }

    /**
     * Determine presence mode based on meeting method and student location
     * 
     * @param string $metodePengajaran Meeting method: offline, online, asynchronous
     * @param float|null $studentLat Student's latitude (nullable)
     * @param float|null $studentLng Student's longitude (nullable)
     * @return array [presence_mode, distance_meters, requires_reason]
     */
    public static function determinePresenceMode(
        string $metodePengajaran,
        ?float $studentLat,
        ?float $studentLng
    ): array {
        // For online or asynchronous meetings, always online mode
        if (in_array($metodePengajaran, ['online', 'asynchronous'])) {
            $distance = ($studentLat && $studentLng) 
                ? self::calculateDistanceFromCampus($studentLat, $studentLng) 
                : null;
                
            return [
                'presence_mode' => 'online',
                'distance_meters' => $distance,
                'requires_reason' => false,
            ];
        }

        // For offline meetings
        if ($metodePengajaran === 'offline') {
            // If no GPS data, treat as outside radius (requires reason)
            if (!$studentLat || !$studentLng) {
                return [
                    'presence_mode' => 'online',
                    'distance_meters' => null,
                    'requires_reason' => true,
                ];
            }

            $distance = self::calculateDistanceFromCampus($studentLat, $studentLng);
            $withinRadius = $distance <= self::CAMPUS_RADIUS_METERS;

            return [
                'presence_mode' => $withinRadius ? 'offline' : 'online',
                'distance_meters' => $distance,
                'requires_reason' => !$withinRadius,
            ];
        }

        // Default fallback
        return [
            'presence_mode' => 'online',
            'distance_meters' => null,
            'requires_reason' => false,
        ];
    }
}
