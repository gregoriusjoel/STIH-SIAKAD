<?php

// Quick test script for LocationService
// Run with: php artisan tinker

use App\Services\LocationService;

echo "=== LOCATION SERVICE TEST ===\n\n";

// Test 1: Within radius (exact campus location)
echo "Test 1: Exact campus location\n";
$result1 = LocationService::determinePresenceMode('offline', -6.311252, 106.811174);
echo "Distance: " . $result1['distance_meters'] . " meters\n";
echo "Mode: " . $result1['presence_mode'] . "\n";
echo "Requires reason: " . ($result1['requires_reason'] ? 'YES' : 'NO') . "\n\n";

// Test 2: Just within radius (90m away)
echo "Test 2: Within radius (~90m from campus)\n";
$result2 = LocationService::determinePresenceMode('offline', -6.312052, 106.811174);
echo "Distance: " . $result2['distance_meters'] . " meters\n";
echo "Mode: " . $result2['presence_mode'] . "\n";
echo "Requires reason: " . ($result2['requires_reason'] ? 'YES' : 'NO') . "\n\n";

// Test 3: Outside radius (500m away)
echo "Test 3: Outside radius (~500m from campus)\n";
$result3 = LocationService::determinePresenceMode('offline', -6.315252, 106.811174);
echo "Distance: " . $result3['distance_meters'] . " meters\n";
echo "Mode: " . $result3['presence_mode'] . "\n";
echo "Requires reason: " . ($result3['requires_reason'] ? 'YES' : 'NO') . "\n\n";

// Test 4: Online meeting (location doesn't matter)
echo "Test 4: Online meeting (location doesn't matter)\n";
$result4 = LocationService::determinePresenceMode('online', -6.350000, 106.850000);
echo "Distance: " . ($result4['distance_meters'] ?? 'N/A') . " meters\n";
echo "Mode: " . $result4['presence_mode'] . "\n";
echo "Requires reason: " . ($result4['requires_reason'] ? 'YES' : 'NO') . "\n\n";

// Test 5: No GPS data for offline meeting
echo "Test 5: No GPS data for offline meeting\n";
$result5 = LocationService::determinePresenceMode('offline', null, null);
echo "Distance: " . ($result5['distance_meters'] ?? 'NULL') . "\n";
echo "Mode: " . $result5['presence_mode'] . "\n";
echo "Requires reason: " . ($result5['requires_reason'] ? 'YES' : 'NO') . "\n\n";

// Test 6: Calculate specific distance
echo "Test 6: Distance between Jakarta and Bogor\n";
$distance = LocationService::calculateDistanceMeters(-6.2088, 106.8456, -6.5971, 106.8060);
echo "Distance: " . round($distance/1000, 2) . " km\n";

echo "\n=== ALL TESTS COMPLETED ===\n";
