<?php

define('BPJS_START', microtime(true));
define('BPJS_VERSION','0.1.0');
// Detect root base dir (flexible path)
$baseDir = realpath(__DIR__.'/');
define('BPJS_BASE_PATH',$baseDir);

// Autoload Composer
require $baseDir . '/vendor/autoload.php';

// Load app
$app = require $baseDir . '/bootstrap/app.php';

// Menjalankan Kernel
$kernel = $app->make(\Bpjs\Core\Kernel::class);

$response = $kernel->handle(
    \Bpjs\Core\Request::capture()
);

$response->send();

$kernel->terminate();
