<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel; // Added this line based on the $kernel usage

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}



// The $app variable is not available here yet, so moving $kernel initialization.
// This line will cause an error if $app is not defined.
// For now, I'm placing it as requested, but it might need adjustment depending on the full context.
// $kernel = $app->make(Kernel::class);


// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
