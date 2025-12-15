<?php
// models/Job.php

require_once __DIR__ . '/../core/Model.php';

class Job extends Model
{
    /**
     * =====================================================
     * CREATE JOB (EMPLOYER)
     * =====================================================
     */
    public function create(int $perusahaanId, array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO jobs (
                perusahaan_id,
                title,
                description,
                requirements,
                location,
                salary_range,
                industry,
                status,
                created_at
            ) VALUES (
                :perusahaan_id,
                :title,
                :description,
                :requirements,
                :location,
                :salary_range,
                :industry,
                'open',
                NOW()
            )
        ");

        $stmt->execute([
            'perusahaan_id' => $perusahaanId,
            'title'         => trim($data['title']),
            'description'   => trim($data['description']),
            'requirements'  => $data['requirements'] ?? null,
            'location'      => $data['location'] ?? null,
            'salary_range'  => $data['salary_range'] ?? null,
            'industry'      => $data['industry'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * =====================================================
     * LIST SEMUA JOB OPEN (PUBLIC SEARCH)
     * Data siap untuk MATCH ENGINE
     * =====================================================
     */
    public function findAllOpen(?string $keyword = null, ?string $location = null): array
    {
        $sql = "
            SELECT 
                j.*,
                p.company_name,
                p.industry AS company_industry
            FROM jobs j
            LEFT JOIN perusahaan_profile p 
                ON j.perusahaan_id = p.perusahaan_id
            WHERE j.status = 'open'
        ";

        $params = [];

        if ($keyword !== null && $keyword !== '') {
            $sql .= " AND (
                j.title LIKE :kw_title 
                OR p.company_name LIKE :kw_company
            )";
            $params['kw_title']   = '%' . $keyword . '%';
            $params['kw_company'] = '%' . $keyword . '%';
        }

        if ($location !== null && $location !== '') {
            $sql .= " AND j.location LIKE :loc";
            $params['loc'] = '%' . $location . '%';
        }

        $sql .= " ORDER BY j.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * =====================================================
     * LIST JOB MILIK PERUSAHAAN
     * =====================================================
     */
    public function findByPerusahaan(int $perusahaanId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM jobs
            WHERE perusahaan_id = :id
            ORDER BY created_at DESC
        ");

        $stmt->execute(['id' => $perusahaanId]);
        return $stmt->fetchAll();
    }

    /**
     * =====================================================
     * DETAIL 1 JOB (JOB DETAIL PAGE)
     * Data lengkap untuk MATCH METER
     * =====================================================
     */
    public function findById(int $jobId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                j.*,
                p.company_name,
                p.company_address,
                p.website,
                p.industry AS company_industry,
                p.description AS company_description
            FROM jobs j
            LEFT JOIN perusahaan_profile p
                ON j.perusahaan_id = p.perusahaan_id
            WHERE j.job_id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $jobId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * =====================================================
     * UPDATE JOB
     * =====================================================
     */
    public function update(int $jobId, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE jobs SET
                title        = :title,
                description  = :description,
                requirements = :requirements,
                location     = :location,
                salary_range = :salary_range,
                industry     = :industry
            WHERE job_id = :id
        ");

        $stmt->execute([
            'id'           => $jobId,
            'title'        => trim($data['title']),
            'description'  => trim($data['description']),
            'requirements' => $data['requirements'] ?? null,
            'location'     => $data['location'] ?? null,
            'salary_range' => $data['salary_range'] ?? null,
            'industry'     => $data['industry'] ?? null,
        ]);
    }

    /**
     * =====================================================
     * DELETE JOB
     * =====================================================
     */
    public function delete(int $jobId): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM jobs WHERE job_id = :id
        ");

        $stmt->execute(['id' => $jobId]);
    }

    /**
     * =====================================================
     * CEK KEPEMILIKAN JOB (SECURITY)
     * =====================================================
     */
    public function isOwnedBy(int $jobId, int $perusahaanId): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM jobs
            WHERE job_id = :job_id
              AND perusahaan_id = :perusahaan_id
            LIMIT 1
        ");

        $stmt->execute([
            'job_id'        => $jobId,
            'perusahaan_id' => $perusahaanId,
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
