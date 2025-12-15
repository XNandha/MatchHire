<?php
// core/Auth.php

class Auth
{
    public static function startSession(): void
    {
    
    }

    public static function login(array $user): void
    {
        self::startSession();
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    }

    public static function logout(): void
    {
        self::startSession();
        session_destroy();
    }

    public static function user(): ?array
    {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        self::startSession();
        return isset($_SESSION['user']);
    }

    public static function requireRole(string $role): void
    {
        $user = self::user();

        if (!$user || $user['role'] !== $role) {
            header("Location: /auth/login");
            exit;
        }
    }
}
