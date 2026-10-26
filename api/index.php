<?php

use Illuminate\Http\Request;

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

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
header(
    'Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS'
);
header(
    'Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN'
);
header('Access-Control-Max-Age: 86400');


// ========================================
// PREFLIGHT CORS
// ========================================

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}


// ========================================
// STORAGE VERCEL
// ========================================

$storagePath = '/tmp/storage';

$directories = [
    $storagePath,
    $storagePath . '/app',
    $storagePath . '/app/public',
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}


// Beri tahu Laravel untuk menggunakan /tmp/storage
putenv('LARAVEL_STORAGE_PATH=' . $storagePath);

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;


// ========================================
// BOOT LARAVEL
// ========================================

try {

    require __DIR__ . '/../vendor/autoload.php';

    $app = require __DIR__ . '/../bootstrap/app.php';

    if (!is_object($app)) {
        throw new RuntimeException(
            'bootstrap/app.php tidak menghasilkan Application. Type: '
            . gettype($app)
        );
    }

    $app->handleRequest(
        Request::capture()
    );

} catch (Throwable $e) {

    http_response_code(500);

    header(
        'Content-Type: text/plain; charset=UTF-8'
    );

    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo 'FILE: ' . $e->getFile() . PHP_EOL;
    echo 'LINE: ' . $e->getLine() . PHP_EOL;
}