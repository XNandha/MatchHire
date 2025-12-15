<?php
// controllers/JobseekerController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';

require_once __DIR__ . '/../models/PelamarProfile.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/Matcher.php';

require_once __DIR__ . '/../config/constants.php';

class JobseekerController extends Controller
{
    /**
     * =====================================================
     * DASHBOARD PELAMAR
     * =====================================================
     */
    public function dashboard(): void
    {
        Auth::requireRole(ROLE_PELAMAR);
        $user = Auth::user();

        $profileModel = new PelamarProfile();
        $profile      = $profileModel->getByUserId($user['user_id']);

        $applications  = (new Application())->findByPelamar($user['user_id']);
        $notifications = (new Notification())->findByRecipient($user['user_id']);

        $jobModel = new Job();
        $jobs     = $jobModel->findAllOpen();

        $matcher = new Matcher();

        // ===============================
        // MATCH SCORE UNTUK JOB LIST
        // ===============================
        if (!empty($profile)) {
            foreach ($jobs as &$job) {
                $job['match_score'] = $matcher->calculate($profile, $job);
            }
            unset($job);
        }

        // ===============================
        // REKOMENDASI (AUTO SORTED)
        // ===============================
        $recommendations = [];

        if (!empty($profile)) {
            $recommendations = $matcher->getRecommendations($profile);
        }

        $this->view('jobseeker/dashboard', [
            'profile'         => $profile,
            'applications'    => $applications,
            'jobs'            => $jobs,
            'notifications'   => $notifications,
            'recommendations' => $recommendations,
            'user'            => $user
        ]);
    }

    /**
     * =====================================================
     * DAFTAR LAMARAN
     * =====================================================
     */
    public function applications(): void
    {
        Auth::requireRole(ROLE_PELAMAR);
        $user = Auth::user();

        $applications = (new Application())->findByPelamar($user['user_id']);

        $this->view('jobseeker/applications', compact('applications'));
    }

    /**
     * =====================================================
     * EDIT PROFIL PELAMAR
     * =====================================================
     */
    public function edit_profile(): void
    {
        Auth::requireRole(ROLE_PELAMAR);
        $user = Auth::user();

        $profileModel = new PelamarProfile();
        $profile      = $profileModel->getByUserId($user['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ===============================
            // DATA PROFIL (LENGKAP)
            // ===============================
            $data = [
                'full_name'     => $_POST['full_name']     ?? null,
                'phone'         => $_POST['phone']         ?? null,
                'address'       => $_POST['address']       ?? null,
                'birth_date'    => $_POST['birth_date']    ?? null,
                'industry'      => $_POST['industry']      ?? null,
                'skills'        => $_POST['skills']        ?? null,
                'preferred_job' => $_POST['preferred_job'] ?? null,
                'experience'    => $_POST['experience']    ?? null,
                'about'         => $_POST['about']         ?? null,
            ];

            // ===============================
            // UPLOAD RESUME (OPTIONAL)
            // ===============================
            if (!empty($_FILES['resume']['name']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {

                $ext  = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
                $safe = 'resume_' . $user['user_id'] . '_' . time() . '.' . $ext;

                if (!is_dir(UPLOAD_PATH_RESUME)) {
                    mkdir(UPLOAD_PATH_RESUME, 0777, true);
                }

                $target = UPLOAD_PATH_RESUME . $safe;

                if (move_uploaded_file($_FILES['resume']['tmp_name'], $target)) {
                    $data['resume_path'] = 'uploads/resume/' . $safe;
                }
            }

            // ===============================
            // SIMPAN PROFIL
            // ===============================
            $profileModel->upsert($user['user_id'], $data);

            redirect('jobseeker/dashboard');
        }

        // ===============================
        // FORM EDIT
        // ===============================
        $this->view('jobseeker/edit_profile', [
            'profile' => $profile,
            'user'    => $user
        ]);
    }

    /**
     * =====================================================
     * NOTIFIKASI
     * =====================================================
     */
    public function notifications(): void
    {
        Auth::requireRole(ROLE_PELAMAR);
        $user = Auth::user();

        $notifications = (new Notification())->findByRecipient($user['user_id']);

        $this->view('notifications/index', compact('notifications'));
    }
}
