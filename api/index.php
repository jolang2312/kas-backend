<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));


// ========================================
// CORS
// ========================================

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
header('Access-Control-Max-Age: 86400');


// ========================================
// PREFLIGHT
// ========================================

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}


// ========================================
// LARAVEL
// ========================================

try {

    require __DIR__ . '/../vendor/autoload.php';

    $app = require __DIR__ . '/../bootstrap/app.php';

    $app->handleRequest(
        Request::capture()
    );

} } catch (Throwable $e) {

    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');

    echo "=== ERROR ===" . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;

    echo PHP_EOL . "=== FILE ===" . PHP_EOL;
    echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;

    echo PHP_EOL . "=== PREVIOUS ERROR ===" . PHP_EOL;

    $previous = $e->getPrevious();

    if ($previous) {
        echo $previous->getMessage() . PHP_EOL;
        echo $previous->getFile() . ':' . $previous->getLine() . PHP_EOL;
    } else {
        echo "Tidak ada previous exception." . PHP_EOL;
    }

    echo PHP_EOL . "=== TRACE ===" . PHP_EOL;
    echo $e->getTraceAsString();

}