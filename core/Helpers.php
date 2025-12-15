<?php
// core/Helpers.php

function base_url(string $path = ''): string
{
    $config = require __DIR__ . '/../config/config.php';
    return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
}

function redirect(string $path): void
{
    header("Location: " . base_url($path));
    exit;
}

function dd($var): void
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    exit;
}
