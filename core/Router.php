<?php
/**
 * Router Final Version — MatchHire
 * ----------------------------------------------
 * ✔ Mendukung project di dalam subfolder (XAMPP)
 * ✔ Bersih, modular, dan aman
 * ✔ Controller/Method/Params mapping otomatis
 * ✔ Tidak mengganggu landing page index.php
 * ✔ Error handling lebih cantik
 * ----------------------------------------------
 */

class Router
{
    public function dispatch(): void
    {
        // URL tanpa query string
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Nama folder project (misal: /MatchHire)
        $baseFolder = '/' . basename(dirname(__DIR__));

        // Jika di-root domain (production), baseFolder akan kosong → aman
        if ($baseFolder !== '/' && strpos($uri, $baseFolder) === 0) {
            $uri = substr($uri, strlen($baseFolder));
        }

        // Bersihkan slash depan/belakang
        $uri = trim($uri, '/');

        // ---------------------------
        // Landing Page Handling
        // ---------------------------
        // Jika user akses: "/", "/MatchHire", "/MatchHire/", "/index.php"
        if ($uri === '' || $uri === 'index.php') {
            // Jangan jalankan router
            // index.php di root project yang akan menampilkan landing page
            return;
        }

        // ---------------------------
        // Parsing Segmen URL
        // ---------------------------
        $segments = explode('/', $uri);

        // Controller = segmen pertama
        $controllerName = ucfirst($segments[0]) . 'Controller';

        // Method = segmen kedua (default: index)
        $methodName = $segments[1] ?? 'index';

        // Parameter = sisanya
        $params = array_slice($segments, 2);

        // Path ke file controller
        $file = __DIR__ . '/../controllers/' . $controllerName . '.php';

        // ---------------------------
        // Validation
        // ---------------------------

        if (!file_exists($file)) {
            $this->error404("Controller <b>{$controllerName}</b> tidak ditemukan.");
        }

        require_once $file;

        if (!class_exists($controllerName)) {
            $this->error500("Class controller <b>$controllerName</b> tidak valid.");
        }

        $controller = new $controllerName;

        if (!method_exists($controller, $methodName)) {
            $this->error404("Method <b>{$methodName}</b> tidak ditemukan pada controller <b>{$controllerName}</b>.");
        }

        // ---------------------------
        // Jalankan Controller
        // ---------------------------
        try {
            call_user_func_array([$controller, $methodName], $params);
        } catch (Throwable $e) {
            $this->error500("Internal error:<br>" . $e->getMessage());
        }
    }

    // ---------------------------
    // Error Page Handling
    // ---------------------------

    private function error404(string $msg): void
    {
        http_response_code(404);
        echo "<h1 style='font-family:Arial;margin-top:40px'>404 Not Found</h1>";
        echo "<p style='font-family:Arial'>{$msg}</p>";
        exit;
    }

    private function error500(string $msg): void
    {
        http_response_code(500);
        echo "<h1 style='font-family:Arial;margin-top:40px'>500 Internal Server Error</h1>";
        echo "<p style='font-family:Arial'>{$msg}</p>";
        exit;
    }
}
