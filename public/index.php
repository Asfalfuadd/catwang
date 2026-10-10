<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance Mode Check
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Autoloader
require __DIR__ . '/../vendor/autoload.php';

// Run Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);