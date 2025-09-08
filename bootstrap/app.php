<?php
require BPJS_BASE_PATH . '/app/helpers/helper.php';
require BPJS_BASE_PATH . '/app/helpers/Rc.php';
use Bpjs\Core\App;

// Inisialisasi instance utama framework
$app = new App();

// Registrasi service / kernel
$app->singleton(Bpjs\Core\Kernel::class, function () use ($app) {
    return new Bpjs\Core\Kernel($app);
});

return $app;
