<?php

require_once __DIR__ . '/../core/Model.php';

class PelamarProfile extends Model
{
    /**
     * Ambil profil pelamar berdasarkan user_id
     */
    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM pelamar_profile 
            WHERE pelamar_id = :id
        ");
        $stmt->execute(['id' => $userId]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Insert atau Update profile pelamar (LENGKAP)
     */
    public function upsert(int $userId, array $data): void
    {
        $exists = $this->getByUserId($userId);

        if ($exists) {

            // UPDATE
            $stmt = $this->db->prepare("
                UPDATE pelamar_profile SET 
                    full_name      = :full_name,
                    phone          = :phone,
                    address        = :address,
                    birth_date     = :birth_date,
                    industry       = :industry,
                    skills         = :skills,
                    preferred_job  = :preferred_job,
                    experience     = :experience,
                    about          = :about,
                    resume_path    = COALESCE(:resume_path, resume_path)
                WHERE pelamar_id = :id
            ");

        } else {

            // INSERT
            $stmt = $this->db->prepare("
                INSERT INTO pelamar_profile (
                    pelamar_id,
                    full_name,
                    phone,
                    address,
                    birth_date,
                    industry,
                    skills,
                    preferred_job,
                    experience,
                    about,
                    resume_path
                ) VALUES (
                    :id,
                    :full_name,
                    :phone,
                    :address,
                    :birth_date,
                    :industry,
                    :skills,
                    :preferred_job,
                    :experience,
                    :about,
                    :resume_path
                )
            ");
        }

        $stmt->execute([
            'id'             => $userId,
            'full_name'      => $data['full_name']      ?? null,
            'phone'          => $data['phone']          ?? null,
            'address'        => $data['address']        ?? null,
            'birth_date'     => $data['birth_date']     ?? null,
            'industry'       => $data['industry']       ?? null,
            'skills'         => $data['skills']         ?? null,
            'preferred_job'  => $data['preferred_job']  ?? null,
            'experience'     => $data['experience']     ?? null,
            'about'          => $data['about']          ?? null,
            'resume_path'    => $data['resume_path']    ?? null,
        ]);
    }
}
