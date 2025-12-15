<?php
// controllers/EmployerController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';

require_once __DIR__ . '/../models/PerusahaanProfile.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Notification.php';

require_once __DIR__ . '/../config/constants.php';

class EmployerController extends Controller
{
    /* =====================================================
     * DASHBOARD PERUSAHAAN
     * ===================================================== */
    public function dashboard(): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        $profile      = (new PerusahaanProfile())->getByUserId($user['user_id']);
        $jobs          = (new Job())->findByPerusahaan($user['user_id']);
        $notifications = (new Notification())->findByRecipient($user['user_id']);

        $this->view('employer/dashboard', compact(
            'profile',
            'jobs',
            'notifications',
            'user'
        ));
    }

    /* =====================================================
     * LIST LOWONGAN MILIK PERUSAHAAN
     * ===================================================== */
    public function jobs(): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        $jobs = (new Job())->findByPerusahaan($user['user_id']);
        $this->view('employer/jobs', compact('jobs'));
    }

    /* =====================================================
     * POST LOWONGAN BARU
     * ===================================================== */
    public function post_job(): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'title'        => $_POST['title']        ?? '',
                'description'  => $_POST['description']  ?? '',
                'requirements' => $_POST['requirements'] ?? null,
                'location'     => $_POST['location']     ?? null,
                'salary_range' => $_POST['salary_range'] ?? null,
                'industry'     => $_POST['industry']     ?? null,
            ];

            (new Job())->create($user['user_id'], $data);
            redirect('employer/jobs');
        }

        $this->view('employer/post_job');
    }

    /* =====================================================
     * EDIT LOWONGAN (FORM)
     * URL: /employer/edit/{jobId}
     * ===================================================== */
    public function edit(int $jobId): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        $jobModel = new Job();
        $job = $jobModel->findById($jobId);

        if (!$job || $job['perusahaan_id'] != $user['user_id']) {
            http_response_code(404);
            echo "Lowongan tidak ditemukan.";
            return;
        }

        $this->view('employer/job_edit', compact('job'));
    }

    /* =====================================================
     * UPDATE LOWONGAN (SUBMIT)
     * ===================================================== */
    public function update(int $jobId): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        $jobModel = new Job();
        $job = $jobModel->findById($jobId);

        if (!$job || $job['perusahaan_id'] != $user['user_id']) {
            http_response_code(403);
            echo "Akses ditolak.";
            return;
        }

        $data = [
            'title'        => $_POST['title']        ?? '',
            'description'  => $_POST['description']  ?? '',
            'requirements' => $_POST['requirements'] ?? null,
            'location'     => $_POST['location']     ?? null,
            'salary_range' => $_POST['salary_range'] ?? null,
            'industry'     => $_POST['industry']     ?? null,
        ];

        $jobModel->update($jobId, $data);
        redirect('employer/jobs');
    }

    /* =====================================================
     * DELETE LOWONGAN
     * ===================================================== */
    public function delete(int $jobId): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        $jobModel = new Job();
        $job = $jobModel->findById($jobId);

        if (!$job || $job['perusahaan_id'] != $user['user_id']) {
            http_response_code(403);
            echo "Akses ditolak.";
            return;
        }

        $jobModel->delete($jobId);
        redirect('employer/jobs');
    }

    /* =====================================================
     * ALIAS METHOD (BIAR URL LAMA TIDAK 404)
     * ===================================================== */
    public function edit_job(int $jobId): void
    {
        $this->edit($jobId);
    }

    public function update_job(int $jobId): void
    {
        $this->update($jobId);
    }

    public function delete_job(int $jobId): void
    {
        $this->delete($jobId);
    }

    /* =====================================================
     * DAFTAR PELAMAR PER JOB
     * ===================================================== */
    public function applicants(int $jobId): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);

        $applications = (new Application())->findByJob($jobId);
        $this->view('employer/applicants', compact('applications', 'jobId'));
    }

    /* =====================================================
     * DETAIL PELAMAR
     * ===================================================== */
    public function applicant_detail(int $applicationId): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);

        $application = (new Application())->findOneWithProfile($applicationId);

        if (!$application) {
            http_response_code(404);
            echo "Data pelamar tidak ditemukan.";
            return;
        }

        $this->view('employer/applicant_detail', compact('application'));
    }

    /* =====================================================
     * UPDATE STATUS LAMARAN
     * ===================================================== */
    public function update_application_status(int $applicationId): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $newStatus = $_POST['status'] ?? STATUS_TERKIRIM;

            $appModel   = new Application();
            $notifModel = new Notification();

            $appModel->updateStatus($applicationId, $newStatus);

            $appRow = $appModel->findById($applicationId);
            if ($appRow) {
                $notifModel->create(
                    (int) $appRow['pelamar_id'],
                    (int) $user['user_id'],
                    'status_update',
                    "Status lamaran Anda diperbarui menjadi: {$newStatus}"
                );
            }
        }

        redirect('employer/dashboard');
    }

    /* =====================================================
     * EDIT PROFIL PERUSAHAAN
     * ===================================================== */
    public function edit_profile(): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        $profileModel = new PerusahaanProfile();
        $profile = $profileModel->getByUserId($user['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'company_name'    => $_POST['company_name']    ?? null,
                'industry'        => $_POST['industry']        ?? null,
                'company_address' => $_POST['company_address'] ?? null,
                'website'         => $_POST['website']         ?? null,
                'description'     => $_POST['description']     ?? null,
            ];

            $profileModel->upsert($user['user_id'], $data);
            redirect('employer/dashboard');
        }

        $this->view('employer/edit_profile', compact('profile', 'user'));
    }

    /* =====================================================
     * NOTIFIKASI
     * ===================================================== */
    public function notifications(): void
    {
        Auth::requireRole(ROLE_PERUSAHAAN);
        $user = Auth::user();

        $notifications = (new Notification())->findByRecipient($user['user_id']);
        $this->view('notifications/index', compact('notifications'));
    }
}
