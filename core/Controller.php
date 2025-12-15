<?php
// core/Controller.php

class Controller
{
    protected string $layout;
    protected array $data = [];

    public function __construct()
    {
        $config = require __DIR__ . '/../config/config.php';
        $this->layout = $config['default_layout'] ?? 'layouts/main';
    }

    /**
     * Render view dengan layout
     */
    public function view(string $view, array $data = []): void
    {
        $this->data = $data;

        $viewFile = __DIR__ . "/../views/{$view}.php";
        $layoutFile = __DIR__ . "/../views/{$this->layout}.php";

        if (!file_exists($viewFile)) {
            echo "View tidak ditemukan: {$viewFile}";
            return;
        }

        $content = function () use ($viewFile) {
            extract($this->data);
            require $viewFile;
        };

        if (file_exists($layoutFile)) {
            extract($this->data);
            require $layoutFile;
        } else {
            $content();
        }
    }
}
