<?php
// models/Matcher.php

require_once __DIR__ . '/Job.php';

class Matcher
{
    /**
     * =====================================================
     * HITUNG MATCH SCORE ANTARA PELAMAR & JOB
     * =====================================================
     * Return: integer (0 - 100)
     */
    public function calculate(array $profile, array $job): int
    {
        $score = 0;

        /* ===============================
           1. LOCATION MATCH (15%)
        =============================== */
        if (!empty($profile['address']) && !empty($job['location'])) {
            if (stripos($job['location'], $profile['address']) !== false) {
                $score += 15;
            }
        }

        /* ===============================
           2. INDUSTRY MATCH (15%)
        =============================== */
        if (!empty($profile['industry']) && !empty($job['industry'])) {
            if (strcasecmp($profile['industry'], $job['industry']) === 0) {
                $score += 15;
            }
        }

        /* ===============================
           3. KEYWORD MATCH (TITLE + DESC) – 40%
        =============================== */
        $keywordsProfile = $this->tokenize(
            ($profile['about'] ?? '') . ' ' . ($profile['skills'] ?? '')
        );

        $keywordsJob = $this->tokenize(
            ($job['title'] ?? '') . ' ' . ($job['description'] ?? '')
        );

        if (!empty($keywordsProfile) && !empty($keywordsJob)) {
            $matches = array_intersect($keywordsProfile, $keywordsJob);
            $ratio   = count($matches) / max(count($keywordsProfile), 1);
            $score  += min(40, (int) round($ratio * 40));
        }

        /* ===============================
           4. EXPERIENCE (OPTIONAL – 30%)
        =============================== */
        if (!empty($profile['experience_years']) && !empty($job['experience_min'])) {
            if ($profile['experience_years'] >= $job['experience_min']) {
                $score += 30;
            }
        }

        return min(100, $score);
    }

    /**
     * =====================================================
     * REKOMENDASI JOB BERDASARKAN MATCH
     * =====================================================
     */
    public function getRecommendations(array $profile): array
    {
        $jobModel = new Job();
        $jobs     = $jobModel->findAllOpen();

        foreach ($jobs as &$job) {
            $job['match_score'] = $this->calculate($profile, $job);
        }

        // Sort descending by match_score
        usort($jobs, fn($a, $b) => $b['match_score'] <=> $a['match_score']);

        return $jobs;
    }

    /**
     * =====================================================
     * HELPER: TOKENIZE STRING
     * =====================================================
     */
    private function tokenize(string $text): array
    {
        $text = strtolower(strip_tags($text));
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);

        $words = array_filter(explode(' ', $text));

        // Stopwords sederhana
        $stopwords = ['dan','atau','yang','di','ke','dari','untuk','dengan'];

        return array_diff(array_unique($words), $stopwords);
    }
}
