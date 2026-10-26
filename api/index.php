<?php

use Illuminate\Contracts\Http\Kernel;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: text/plain; charset=UTF-8');

try {

    // Buat bootstrap writable di Vercel
    if (!is_dir('/tmp/bootstrap/cache')) {
        mkdir('/tmp/bootstrap/cache', 0755, true);
    }

    // Laravel 12 membutuhkan bootstrap/providers.php
    copy(
        __DIR__ . '/../bootstrap/providers.php',
        '/tmp/bootstrap/providers.php'
    );

    $app = require __DIR__ . '/../bootstrap/app.php';

    echo "1. Application dibuat: OK\n";
    echo "2. Bootstrap asli: " . $app->bootstrapPath() . "\n";

    // Pindahkan bootstrap/cache ke filesystem writable
    $app->useBootstrapPath('/tmp/bootstrap');

    echo "3. Bootstrap baru: " . $app->bootstrapPath() . "\n";
    echo "4. Cache path: " . $app->bootstrapPath('cache') . "\n";

    $kernel = $app->make(Kernel::class);

    echo "5. HTTP Kernel dibuat: OK\n";

    $kernel->bootstrap();

    echo "6. HTTP bootstrap selesai: OK\n";

    echo "7. view: ";
    echo $app->bound('view') ? "TRUE\n" : "FALSE\n";

    echo "8. ViewServiceProvider: ";

    $provider = $app->getProvider(
        Illuminate\View\ViewServiceProvider::class
    );

    echo $provider ? "TRUE\n" : "FALSE\n";

} catch (Throwable $e) {

    http_response_code(500);

    echo "\n=== ERROR ===\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n";

    if ($e->getPrevious()) {
        echo "\n=== PREVIOUS ===\n";
        echo $e->getPrevious()->getMessage() . "\n";
    }
}