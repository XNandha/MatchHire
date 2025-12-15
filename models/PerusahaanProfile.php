<?php
// models/PerusahaanProfile.php

require_once __DIR__ . '/../core/Model.php';

class PerusahaanProfile extends Model
{
    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM perusahaan_profile WHERE perusahaan_id = :id
        ");
        $stmt->execute(['id' => $userId]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function upsert(int $userId, array $data): void
    {
        $exists = $this->getByUserId($userId);

        if ($exists) {
            $stmt = $this->db->prepare("
                UPDATE perusahaan_profile SET
                    company_name = :company_name,
                    industry = :industry,
                    company_address = :company_address,
                    website = :website,
                    description = :description,
                    logo_path = :logo_path
                WHERE perusahaan_id = :id
            ");
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO perusahaan_profile
                    (perusahaan_id, company_name, industry, company_address, website, description, logo_path)
                VALUES
                    (:id, :company_name, :industry, :company_address, :website, :description, :logo_path)
            ");
        }

        $stmt->execute([
            'id'               => $userId,
            'company_name'     => $data['company_name']     ?? null,
            'industry'         => $data['industry']         ?? null,
            'company_address'  => $data['company_address']  ?? null,
            'website'          => $data['website']          ?? null,
            'description'      => $data['description']      ?? null,
            'logo_path'        => $data['logo_path']        ?? null,
        ]);
    }
}
