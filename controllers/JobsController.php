<?php
// controllers/JobsController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Job.php';

// ✅ Tambahan agar match_score pakai perhitungan asli (profil pelamar)
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/PelamarProfile.php';
require_once __DIR__ . '/../models/Matcher.php';

class JobsController extends Controller
{
    /**
     * =====================================================
     * LIST & SEARCH JOB (PUBLIC)
     * URL: /jobs/index?q=xxx&loc=yyy
     * =====================================================
     */
    public function index(): void
    {
        $keyword  = isset($_GET['q'])   ? trim($_GET['q'])   : null;
        $location = isset($_GET['loc']) ? trim($_GET['loc']) : null;

        $keyword  = ($keyword === '')  ? null : $keyword;
        $location = ($location === '') ? null : $location;

        $jobModel = new Job();
        $jobs     = $jobModel->findAllOpen($keyword, $location);

        // ===============================
        // MATCH SCORE (REAL untuk PELAMAR)
        // - Kalau login sebagai pelamar & profil ada => pakai Matcher (asli)
        // - Kalau tidak => fallback rule-based (optional)
        // ===============================
        $profile = null;

        if (\Auth::check() && (\Auth::user()['role'] ?? null) === 'pelamar') {
            $profile = (new PelamarProfile())->getByUserId((int) \Auth::user()['user_id']);
        }

        if (!empty($profile)) {
            $matcher = new Matcher();
            foreach ($jobs as &$job) {
                $job['match_score'] = $matcher->calculate($profile, $job);
            }
            unset($job);
        } else {
            // fallback rule-based (tetap ada untuk guest / non-pelamar)
            foreach ($jobs as &$job) {
                $job['match_score'] = $this->calculateMatchScore($job, $keyword, $location);
            }
            unset($job);
        }

        $this->view('jobs/list', [
            'jobs'     => $jobs,
            'keyword'  => $keyword,
            'location' => $location
        ]);
    }

    /**
     * =====================================================
     * DETAIL JOB
     * URL: /jobs/show/{jobId}
     * =====================================================
     */
    public function show(int $jobId): void
    {
        $jobModel = new Job();
        $job      = $jobModel->findById($jobId);

        if (!$job) {
            http_response_code(404);
            echo "<h2>Lowongan tidak ditemukan.</h2>";
            return;
        }

        // ===============================
        // MATCH SCORE (REAL untuk PELAMAR)
        // ===============================
        $profile = null;

        if (\Auth::check() && (\Auth::user()['role'] ?? null) === 'pelamar') {
            $profile = (new PelamarProfile())->getByUserId((int) \Auth::user()['user_id']);
        }

        if (!empty($profile)) {
            $job['match_score'] = (new Matcher())->calculate($profile, $job);
        } else {
            // fallback rule-based (opsional)
            $job['match_score'] = $this->calculateMatchScore($job);
        }

        $this->view('jobs/show', [
            'job' => $job
        ]);
    }

    /**
     * =====================================================
     * MATCH SCORE CALCULATOR (SEMI-CERDAS)
     * =====================================================
     */
    private function calculateMatchScore(array $job, ?string $keyword = null, ?string $location = null): int
    {
        $score = 0;

        // 1. Keyword match (40%)
        if ($keyword) {
            if (
                stripos($job['title'], $keyword) !== false ||
                (!empty($job['company_name']) &&
                 stripos($job['company_name'], $keyword) !== false)
            ) {
                $score += 40;
            }
        }

        // 2. Location match (25%)
        if ($location && !empty($job['location'])) {
            if (stripos($job['location'], $location) !== false) {
                $score += 25;
            }
        }

        // 3. Industry match (25%)
        if (!empty($job['industry']) && !empty($job['company_industry'])) {
            if (strcasecmp($job['industry'], $job['company_industry']) === 0) {
                $score += 25;
            }
        }

        // 4. Freshness bonus (10%)
        if (!empty($job['created_at'])) {
            $days = (time() - strtotime($job['created_at'])) / 86400;
            if ($days <= 7) {
                $score += 10;
            }
        }

        return min(100, max(0, $score));
    }
}
