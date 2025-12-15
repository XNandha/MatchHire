<?php
// controllers/ApplicationController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../config/constants.php';

class ApplicationController extends Controller
{
    public function apply($jobId)
    {
        Auth::requireRole(ROLE_PELAMAR);

        $app = new Application();
        $user = Auth::user();

        if ($app->hasApplied($user['user_id'], $jobId)) {
            echo "Anda sudah melamar lowongan ini.";
            return;
        }

        $app->apply($user['user_id'], $jobId);
        redirect('jobseeker/applications');
    }

    public function withdraw($jobId)
    {
        Auth::requireRole(ROLE_PELAMAR);

        $app = new Application();
        $app->withdraw(Auth::user()['user_id'], $jobId);

        redirect('jobseeker/applications');
    }
}

