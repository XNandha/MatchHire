<?php

class JobMatcher
{
    public static function calculate(array $job, array $profile): int
    {
        $score = 0;

        // 1. Lokasi
        if (!empty($profile['address']) && !empty($job['location'])) {
            if (stripos($profile['address'], $job['location']) !== false) {
                $score += 30;
            }
        }

        // 2. Judul pekerjaan
        if (!empty($profile['about']) && !empty($job['title'])) {
            if (stripos($profile['about'], $job['title']) !== false) {
                $score += 30;
            }
        }

        // 3. Industri
        if (!empty($profile['industry']) && !empty($job['industry'])) {
            if ($profile['industry'] === $job['industry']) {
                $score += 20;
            }
        }

        // 4. Profil lengkap (bonus)
        if (
            !empty($profile['full_name']) &&
            !empty($profile['phone']) &&
            !empty($profile['about'])
        ) {
            $score += 20;
        }

        return min($score, 100);
    }
}
