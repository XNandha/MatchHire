<?php
// index.php (Front Controller + Landing Page)

if (session_status() === PHP_SESSION_NONE) {
            session_start();
}

// Bootstrapping core functionality
require_once __DIR__ . '/core/Helpers.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Router.php';

// Ambil URL tanpa query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalisasi base folder (misal: /MatchHire)
$baseFolder = '/' . basename(__DIR__);

// Jika request ke root project → tampilkan landing page
if (
    $uri === '/' ||
    $uri === $baseFolder ||
    $uri === $baseFolder . '/' ||
    $uri === '/index.php'
) {
    $config = require __DIR__ . '/config/config.php';

    $layoutFile = __DIR__ . '/views/layouts/main.php';
    $viewFile   = __DIR__ . '/views/home/index.php';

    if (!file_exists($layoutFile) || !file_exists($viewFile)) {
        die('Layout atau view landing page tidak ditemukan.');
    }

    // Simulasikan mekanisme Controller::view()
    $content = function () use ($viewFile) {
        require $viewFile;
    };

    // Render layout utama
    require $layoutFile;
    exit;
}

// Selain root → lanjutkan ke router
$router = new Router();
$router->dispatch();
