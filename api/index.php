<?php

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

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}


// Vercel hanya mengizinkan penulisan sementara di /tmp
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}


try {

    require __DIR__ . '/../public/index.php';

} catch (Throwable $e) {

    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');

    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo 'FILE: ' . $e->getFile() . PHP_EOL;
    echo 'LINE: ' . $e->getLine() . PHP_EOL;

    if ($e->getPrevious()) {
        echo 'PREVIOUS: ' . $e->getPrevious()->getMessage() . PHP_EOL;
    }
}