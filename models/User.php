<?php
// models/User.php

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    /**
     * Cari user berdasarkan email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE email = :email LIMIT 1
        ");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Buat user baru (register)
     */
    public function create(string $role, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
            INSERT INTO users (role, email, password)
            VALUES (:role, :email, :password)
        ");

        $stmt->execute([
            'role' => $role,
            'email' => $email,
            'password' => $hash
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Cari user berdasarkan ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE user_id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch();
        return $user ?: null;
    }
}
