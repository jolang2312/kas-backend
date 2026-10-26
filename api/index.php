<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowedOrigins = [
    'https://kas-frontend-react.vercel.app',
    'http://localhost:5173',
];

if (in_array($origin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}

header('Vary: Origin');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: text/plain; charset=UTF-8');

try {

    require __DIR__ . '/../vendor/autoload.php';

    $app = require __DIR__ . '/../bootstrap/app.php';

    echo "1. Application dibuat: OK\n";
    echo "2. view sebelum HTTP bootstrap: ";
    echo $app->bound('view') ? "TRUE\n" : "FALSE\n";

    $kernel = $app->make(Kernel::class);

    echo "3. HTTP Kernel dibuat: OK\n";

    $kernel->bootstrap();

    echo "4. HTTP bootstrap selesai: OK\n";
    echo "5. view setelah HTTP bootstrap: ";
    echo $app->bound('view') ? "TRUE\n" : "FALSE\n";

    echo "6. Provider View terdaftar: ";

    $provider = $app->getProvider(
        Illuminate\View\ViewServiceProvider::class
    );

    echo $provider ? "TRUE\n" : "FALSE\n";

    echo "7. Selesai\n";

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