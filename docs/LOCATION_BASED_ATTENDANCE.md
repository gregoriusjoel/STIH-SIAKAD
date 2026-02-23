# Location-Based Student Attendance System - Implementation Guide

## Overview
This document describes the implementation of GPS radius-based attendance tracking for students at STIH Adhyaksa campus. The system validates student locations when they submit attendance for offline meetings.

## Campus Location
- **Latitude**: -6.311252
- **Longitude**: 106.811174
- **Radius**: 100 meters

## Business Rules

### 1. Offline Meetings
When `metode_pengajaran` = `offline`:
- **Within 100m radius**: Status = `Hadir (Offline / On-site)`, no reason required
- **Outside 100m radius**: Status = `Hadir (Online / Remote)`, reason **REQUIRED**

### 2. Online Meetings
When `metode_pengajaran` = `online`:
- Status = `Hadir (Online)`
- Location is saved if available (optional)
- No reason required

### 3. Asynchronous Meetings
When `metode_pengajaran` = `asynchronous`:
- No GPS validation
- Follows standard async attendance rules

### 4. GPS Permission Denied
If student denies GPS permission or GPS is unavailable for offline meetings:
- Treated as **outside radius**
- Reason fields become **REQUIRED**
- UI shows warning message

## Database Changes

### Migration
File: `database/migrations/2026_02_23_100000_add_location_fields_to_presensis_table.php`

New columns added to `presensis` table:
```php
- student_lat (DECIMAL 10,7, nullable)
- student_lng (DECIMAL 10,7, nullable)
- distance_meters (INT, nullable)
- presence_mode (ENUM: 'offline', 'online', nullable)
- reason_category (VARCHAR, nullable)
- reason_detail (TEXT, nullable)
- campus_lat (DECIMAL 10,7, default -6.311252)
- campus_lng (DECIMAL 10,7, default 106.811174)
- radius_meters (INT, default 100)
```

### Model Update
File: `app/Models/Presensi.php`

Added fields to `$fillable`:
```php
'student_lat', 'student_lng', 'distance_meters', 
'presence_mode', 'reason_category', 'reason_detail',
'campus_lat', 'campus_lng', 'radius_meters'
```

## Backend Implementation

### 1. LocationService (Helper)
File: `app/Services/LocationService.php`

**Key Methods:**
- `calculateDistanceMeters($lat1, $lng1, $lat2, $lng2)` - Haversine formula
- `calculateDistanceFromCampus($studentLat, $studentLng)` - Distance to campus
- `isWithinCampusRadius($studentLat, $studentLng)` - Boolean check
- `determinePresenceMode($metodePengajaran, $lat, $lng)` - Main logic

**Constants:**
```php
const CAMPUS_LAT = -6.311252;
const CAMPUS_LNG = 106.811174;
const CAMPUS_RADIUS_METERS = 100;
```

### 2. Controller Updates

#### AttendanceController
File: `app/Http/Controllers/AttendanceController.php`

**Changes:**
- `showForm()`: Passes `$metodePengajaran` to view
- `store()`: 
  - Accepts `lat`, `lng`, `reason_category`, `reason_detail`
  - Validates location using `LocationService::determinePresenceMode()`
  - Validates reason fields if required
  - Saves all location data

#### Absen\LoginController
File: `app/Http/Controllers/Absen/LoginController.php`

**Changes:**
- `showLoginForm()`: Passes `$metodePengajaran` to view
- `login()`: Same validation logic as AttendanceController

## Frontend Implementation

### 1. Attendance Form (QR Scan)
File: `resources/views/absensi/form.blade.php`

**Features:**
- Alpine.js component `attendanceForm()`
- Auto-requests GPS on page load via `navigator.geolocation`
- Shows GPS status banners (success/error)
- Calculates distance client-side for immediate feedback
- Conditionally shows reason fields (`showReasonFields`)
- Hidden inputs for `lat` and `lng`
- Form validation before submit

**GPS States:**
- `locationSuccess`: Green banner, shows if within/outside radius
- `locationError`: Yellow banner, shows error message
- `withinRadius`: Boolean for badge display

### 2. Login-Based Attendance Form
File: `resources/views/absen/login.blade.php`

**Features:**
- Same Alpine.js logic as above
- Dark mode support
- Integrated with existing login flow

### 3. JavaScript Logic
```javascript
// Haversine distance calculation
calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371e3; // Earth radius in meters
    // ... formula implementation
    return distance;
}

// Handle form submission
handleSubmit(event) {
    // Validate reason if required
    if (this.reasonRequired && !this.reasonCategory) {
        event.preventDefault();
        alert('Harap pilih alasan kehadiran online.');
        return false;
    }
    // ...
}
```

### 4. Reason Categories
Pre-defined options:
- Sakit
- Kendala transportasi
- Izin keluarga
- Keperluan penting
- Lainnya (requires `reason_detail`)

## Display Updates

### Student Attendance Table
File: `resources/views/page/dosen/kelas/partials/student_attendance_table.blade.php`

**Shows:**
- `Hadir Offline` badge (green) with distance
- `Hadir Online` badge (blue) with distance + reason
- Legacy `Hadir` badge (for old records without location data)

## Testing & Validation

### Run Migration
```bash
php artisan migrate
```

### Test Scenarios

#### Scenario 1: Offline Meeting - Within Radius
1. Student scans QR or opens attendance link
2. GPS detects location within 100m
3. Green banner: "Lokasi terdeteksi. Anda dalam radius kampus."
4. No reason fields shown
5. Submit → `presence_mode` = `offline`, no reason

#### Scenario 2: Offline Meeting - Outside Radius
1. Student scans QR (but is far from campus)
2. GPS detects location > 100m away
3. Green banner: "Lokasi terdeteksi. Anda di luar radius kampus."
4. Blue info box: "Anda berada di luar radius kampus untuk pertemuan offline. Harap pilih alasan."
5. Reason dropdown + detail textarea (if "Lainnya") shown
6. Validation: Cannot submit without selecting reason
7. Submit → `presence_mode` = `online`, reason saved

#### Scenario 3: GPS Denied - Offline Meeting
1. Student denies GPS permission
2. Yellow banner: "Gagal mendapatkan lokasi. Izin lokasi ditolak."
3. Reason fields automatically shown (same as Scenario 2)
4. Must provide reason to submit
5. Submit → `presence_mode` = `online`, `lat`/`lng` = null

#### Scenario 4: Online Meeting
1. Student accesses attendance form
2. GPS requested (optional)
3. No validation of radius
4. No reason required
5. Submit → `presence_mode` = `online`

### Backend Validation Tests
Check in controller:
```php
// Test with coordinates within radius
POST /absensi/submit/{token}
{
    "lat": -6.311252,
    "lng": 106.811174,
    // ... other fields
}
// Expected: presence_mode = offline

// Test with coordinates outside radius
POST /absensi/submit/{token}
{
    "lat": -6.350000,
    "lng": 106.850000,
    "reason_category": "Sakit",
    // ... other fields
}
// Expected: presence_mode = online, reason_category saved
```

## Security Considerations

### GPS Spoofing
- Students could potentially fake GPS coordinates
- **Mitigation strategies:**
  1. Cross-reference with QR expiry time (5 minutes)
  2. Check IP address consistency
  3. Monitor unusual patterns (e.g., teleportation)
  4. For high-stakes attendance, require additional verification

### Data Privacy
- GPS coordinates are sensitive data
- Stored only for audit trails
- Consider implementing data retention policy
- Ensure compliance with data protection regulations

## Configuration

### Changing Campus Coordinates
To use different campus location, update:
```php
// app/Services/LocationService.php
const CAMPUS_LAT = -6.311252;  // Change this
const CAMPUS_LNG = 106.811174; // Change this
const CAMPUS_RADIUS_METERS = 100; // Change this
```

### Adjusting Radius
To change acceptable radius (e.g., to 200m):
```php
// app/Services/LocationService.php
const CAMPUS_RADIUS_METERS = 200; // Now 200 meters
```

## Troubleshooting

### Issue: GPS not working on mobile
**Solution:** Ensure:
- HTTPS is enabled (required for geolocation API)
- User granted location permission
- Device GPS is enabled

### Issue: Distance calculation seems wrong
**Solution:** 
- Verify coordinates format (latitude, longitude)
- Check Haversine formula implementation
- Test with known coordinates

### Issue: Reason fields not showing
**Solution:**
- Check `metodePengajaran` value in Pertemuan record
- Verify JavaScript console for errors
- Ensure Alpine.js is loaded

### Issue: Old records show no location data
**Solution:** 
- This is expected (migration adds nullable columns)
- Legacy records display standard "Hadir" badge
- New submissions will include location data

## Future Enhancements

1. **Map View**: Show student locations on a map for dosen
2. **Geofencing**: Multiple location zones (e.g., branch campuses)
3. **History Tracking**: GPS path during class time
4. **Analytics**: Distance distribution charts
5. **Push Notifications**: Remind students to check-in when near campus

## File Manifest

### New Files
- `database/migrations/2026_02_23_100000_add_location_fields_to_presensis_table.php`
- `app/Services/LocationService.php`

### Modified Files
- `app/Models/Presensi.php`
- `app/Http/Controllers/AttendanceController.php`
- `app/Http/Controllers/Absen/LoginController.php`
- `resources/views/absensi/form.blade.php`
- `resources/views/absen/login.blade.php`
- `resources/views/page/dosen/kelas/partials/student_attendance_table.blade.php`

## API Reference

### LocationService Methods

```php
// Calculate distance between two coordinates
LocationService::calculateDistanceMeters(
    float $lat1, 
    float $lng1, 
    float $lat2, 
    float $lng2
): float

// Get distance from campus
LocationService::calculateDistanceFromCampus(
    float $studentLat, 
    float $studentLng
): float

// Check if within radius
LocationService::isWithinCampusRadius(
    float $studentLat, 
    float $studentLng
): bool

// Determine presence mode
LocationService::determinePresenceMode(
    string $metodePengajaran,
    ?float $studentLat,
    ?float $studentLng
): array [
    'presence_mode' => 'offline|online',
    'distance_meters' => int|null,
    'requires_reason' => bool
]
```

## Support

For issues or questions regarding this implementation:
1. Check error logs: `storage/logs/laravel.log`
2. Review browser console for JavaScript errors
3. Test GPS functionality: https://www.where-am-i.net/
4. Validate coordinates: https://www.latlong.net/

---

**Last Updated**: February 23, 2026  
**Version**: 1.0  
**Author**: Senior Fullstack Engineer