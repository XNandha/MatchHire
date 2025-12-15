<?php
// models/Application.php

require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../config/constants.php';

class Application extends Model
{
    /**
     * =====================================================
     * APPLY JOB
     * =====================================================
     * Pelamar melamar pekerjaan
     * - Status default: STATUS_TERKIRIM
     * - Timestamp: submitted_at
     */
    public function apply(int $pelamarId, int $jobId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO applications 
                (pelamar_id, job_id, status, submitted_at)
            VALUES 
                (:pelamar_id, :job_id, :status, NOW())
        ");

        $stmt->execute([
            'pelamar_id' => $pelamarId,
            'job_id'     => $jobId,
            'status'     => STATUS_TERKIRIM,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * =====================================================
     * CEK APAKAH PELAMAR SUDAH MELAMAR
     * =====================================================
     */
    public function hasApplied(int $pelamarId, int $jobId): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1 
            FROM applications 
            WHERE pelamar_id = :pelamar_id 
              AND job_id = :job_id
            LIMIT 1
        ");

        $stmt->execute([
            'pelamar_id' => $pelamarId,
            'job_id'     => $jobId,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * =====================================================
     * SEMUA LAMARAN MILIK PELAMAR
     * =====================================================
     */
    public function findByPelamar(int $pelamarId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*, 
                j.title, 
                j.location
            FROM applications a
            JOIN jobs j ON a.job_id = j.job_id
            WHERE a.pelamar_id = :id
            ORDER BY a.submitted_at DESC
        ");

        $stmt->execute(['id' => $pelamarId]);
        return $stmt->fetchAll();
    }

    /**
     * =====================================================
     * SEMUA PELAMAR UNTUK SATU JOB (EMPLOYER)
     * =====================================================
     */
    public function findByJob(int $jobId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*, 
                u.email, 
                p.full_name
            FROM applications a
            JOIN users u ON a.pelamar_id = u.user_id
            LEFT JOIN pelamar_profile p ON p.pelamar_id = u.user_id
            WHERE a.job_id = :id
            ORDER BY a.submitted_at DESC
        ");

        $stmt->execute(['id' => $jobId]);
        return $stmt->fetchAll();
    }

    /**
     * =====================================================
     * DETAIL 1 LAMARAN + PROFIL PELAMAR
     * =====================================================
     */
    public function findOneWithProfile(int $applicationId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*,
                u.email,
                p.full_name,
                p.phone,
                p.address,
                p.birth_date,
                p.resume_path,
                p.about,
                j.title AS job_title
            FROM applications a
            JOIN users u ON a.pelamar_id = u.user_id
            LEFT JOIN pelamar_profile p ON p.pelamar_id = u.user_id
            JOIN jobs j ON a.job_id = j.job_id
            WHERE a.application_id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $applicationId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * =====================================================
     * UPDATE STATUS LAMARAN (EMPLOYER)
     * =====================================================
     */
    public function updateStatus(int $applicationId, string $status): void
    {
        $stmt = $this->db->prepare("
            UPDATE applications
            SET status = :status
            WHERE application_id = :id
        ");

        $stmt->execute([
            'status' => $status,
            'id'     => $applicationId,
        ]);
    }

    /**
     * =====================================================
     * AMBIL 1 LAMARAN (INTERNAL / NOTIFIKASI)
     * =====================================================
     */
    public function findById(int $applicationId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM applications 
            WHERE application_id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $applicationId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * =====================================================
     * WITHDRAW / BATALKAN LAMARAN
     * =====================================================
     */
    public function withdraw(int $pelamarId, int $jobId): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM applications
            WHERE pelamar_id = :pelamar_id
              AND job_id = :job_id
        ");

        $stmt->execute([
            'pelamar_id' => $pelamarId,
            'job_id'     => $jobId,
        ]);
    }
}
