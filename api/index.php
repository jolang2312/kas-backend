<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
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
// STORAGE UNTUK VERCEL
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


// ========================================
// COMPOSER
// ========================================

require __DIR__ . '/../vendor/autoload.php';


// ========================================
// LARAVEL 12
// ========================================

$app = require_once __DIR__ . '/../bootstrap/app.php';


// Gunakan storage writable milik Vercel
$app->useStoragePath($storagePath);


// ========================================
// HANDLE REQUEST
// ========================================

$app->handleRequest(
    Request::capture()
);