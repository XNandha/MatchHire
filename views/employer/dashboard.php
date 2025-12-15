<?php
// views/employer/dashboard.php

// ===============================
// SMALL HELPERS (AMAN, VIEW-ONLY)
// ===============================
$jobs    = $jobs ?? [];
$profile = $profile ?? [];

$totalJobs  = is_array($jobs) ? count($jobs) : 0;
$openJobs   = 0;
$closedJobs = 0;
$otherJobs  = 0;

if (!empty($jobs)) {
    foreach ($jobs as $j) {
        $st = strtolower(trim($j['status'] ?? ''));
        if ($st === 'open') $openJobs++;
        elseif ($st === 'closed') $closedJobs++;
        else $otherJobs++;
    }
}

function statusMeta(?string $status): array
{
    $s = strtolower(trim((string)$status));

    if ($s === 'open')   return ['Open', 'badge-open'];
    if ($s === 'closed') return ['Closed', 'badge-closed'];

    return [($status ?: 'Unknown'), 'badge-neutral'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Perusahaan — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255,255,255,0.68);
    --glass-border: rgba(255,255,255,0.35);

    --text-main: #1d1d1f;
    --text-muted: #6e6e73;

    --accent: #2563eb;
    --accent-dark: #1e4fd7;

    --radius: 20px;

    --shine: rgba(255,255,255,0.55);
}

/* ================= BASE ================= */
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;

    background: linear-gradient(
        180deg,
        #eaf1ff 0%,
        #f4f8ff 50%,
        #ffffff 100%
    );

    color: var(--text-main);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* BACKGROUND CLOUDS */
body::before,
body::after {
    content: "";
    position: fixed;
    inset: -40% -40%;
    z-index: -2;
    pointer-events: none;
}

body::before {
    z-index: -1;
    background:
        radial-gradient(40% 35% at 20% 30%, rgba(37, 99, 235, 0.18), transparent 60%),
        radial-gradient(35% 30% at 70% 40%, rgba(96, 165, 250, 0.18), transparent 65%),
        radial-gradient(30% 25% at 50% 70%, rgba(191, 219, 254, 0.25), transparent 70%);
    filter: blur(60px);
    animation: cloudMove 90s linear infinite;
}

body::after {
    background:
        radial-gradient(45% 40% at 30% 60%, rgba(30, 109, 232, 0.12), transparent 65%);
    filter: blur(90px);
    animation: cloudMoveReverse 140s linear infinite;
}

@keyframes cloudMove {
    0% { transform: translate(0,0); }
    50% { transform: translate(10%,-8%); }
    100% { transform: translate(0,0); }
}
@keyframes cloudMoveReverse {
    0% { transform: translate(0,0); }
    50% { transform: translate(-8%,10%); }
    100% { transform: translate(0,0); }
}

/* ================= CONTAINER ================= */
.page-container {
    max-width: 1180px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= TOP BAR ================= */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.page-title {
    font-size: 2.45rem;
    letter-spacing: -1.2px;
    margin: 0 0 10px;
    line-height: 1.15;
}

.page-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.65;
    max-width: 760px;
}

.top-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* ================= BUTTONS ================= */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 11px 18px;
    border-radius: 999px;
    border: none;

    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;

    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 800;

    box-shadow: 0 16px 34px rgba(30,109,232,0.22);
    transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.28);
    filter: brightness(1.05);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 10px 16px;
    border-radius: 999px;

    background: rgba(0,0,0,0.06);
    color: var(--text-main);

    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 700;

    transition: transform 0.2s ease, background 0.2s ease;
}

.btn-secondary:hover {
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

/* ================= GRID ================= */
.grid {
    display: grid;
    grid-template-columns: 1.65fr 1fr;
    gap: 22px;
    align-items: start;
}

@media (max-width: 900px) {
    .grid { grid-template-columns: 1fr; }
}

/* ================= GLASS CARD ================= */
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 28px 28px;

    box-shadow:
        0 14px 36px rgba(0,0,0,0.07),
        inset 0 1px 0 rgba(255,255,255,0.45);

    transition: transform 0.35s ease, box-shadow 0.35s ease;
    position: relative;
    overflow: hidden;
}

.glass-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 50% at 20% 10%, var(--shine), transparent 60%);
    opacity: 0.33;
    pointer-events: none;
}

.glass-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 22px 50px rgba(0,0,0,0.09),
        inset 0 1px 0 rgba(255,255,255,0.5);
}

.section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.section-title {
    font-size: 1.35rem;
    margin: 0;
    letter-spacing: -0.4px;
}

.section-hint {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.92rem;
    line-height: 1.6;
}

/* ================= STATS ================= */
.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 10px;
}

.stat {
    background: rgba(255,255,255,0.62);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px;
    padding: 14px 14px;
}

.stat small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.stat strong {
    font-size: 1.25rem;
    letter-spacing: -0.4px;
}

@media (max-width: 900px) {
    .stats { grid-template-columns: 1fr; }
}

/* ================= JOB LIST (PREVIEW) ================= */
.job-list {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.job-item {
    background: rgba(255,255,255,0.72);
    border-radius: 16px;
    padding: 18px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid rgba(0,0,0,0.06);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    gap: 14px;
}

.job-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 26px rgba(0,0,0,0.08);
}

.job-left {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.job-title {
    font-size: 1rem;
    font-weight: 900;
    letter-spacing: -0.2px;
    margin: 0;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 10px;
    align-items: center;
    color: var(--text-muted);
    font-size: 0.85rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 900;
    border: 1px solid rgba(0,0,0,0.08);
    background: rgba(255,255,255,0.6);
}

.badge-open {
    border-color: rgba(34,197,94,0.25);
    color: #15803d;
    background: rgba(34,197,94,0.12);
}

.badge-closed {
    border-color: rgba(239,68,68,0.25);
    color: #b91c1c;
    background: rgba(239,68,68,0.12);
}

.badge-neutral {
    border-color: rgba(245,158,11,0.25);
    color: #b45309;
    background: rgba(245,158,11,0.14);
}

.job-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .job-item {
        flex-direction: column;
        align-items: flex-start;
    }
    .job-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

/* ================= PROFILE META ================= */
.meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 8px;
}

.meta-item {
    background: rgba(255,255,255,0.62);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px;
    padding: 14px 14px;
}

.meta-item small {
    display: block;
    color: var(--text-muted);
    font-size: 0.72rem;
    margin-bottom: 6px;
}

.meta-item strong, .meta-item div {
    font-size: 0.92rem;
    line-height: 1.55;
}

.meta-item.full {
    grid-column: 1 / -1;
}

@media (max-width: 900px) {
    .meta-grid { grid-template-columns: 1fr; }
}

/* ================= EMPTY ================= */
.empty {
    margin: 12px 0 0;
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    background: rgba(255,255,255,0.55);
    border: 1px dashed rgba(0,0,0,0.14);
    border-radius: 16px;
    padding: 16px 16px;
}

/* ================= FADE ================= */
.fade {
    opacity: 0;
    transform: translateY(22px);
    transition: opacity 0.9s ease, transform 0.9s ease;
    will-change: opacity, transform;
}

.fade.show {
    opacity: 1;
    transform: translateY(0);
}
</style>
</head>

<body>

<div class="page-container">

    <!-- TOP BAR -->
    <div class="topbar fade">
        <div>
            <h1 class="page-title">Dashboard Perusahaan</h1>
            <p class="page-subtitle">
                Ringkasan cepat untuk pengelolaan lowongan & profil. Untuk manajemen lengkap, gunakan menu <b>Kelola Lowongan</b>.
            </p>
        </div>

        <div class="top-actions">
            <!-- sesuai views/employer/post_job.php -->
            <a href="<?= base_url('employer/post_job') ?>" class="btn-primary">
                + Posting Lowongan
            </a>

            <!-- sesuai views/employer/jobs.php -->
            <a href="<?= base_url('employer/jobs') ?>" class="btn-secondary">
                Kelola Lowongan
            </a>

            <!-- sesuai views/employer/edit_profile.php -->
            <a href="<?= base_url('employer/edit_profile') ?>" class="btn-secondary">
                Edit Profil
            </a>
        </div>
    </div>

    <div class="grid">

        <!-- LEFT: JOBS PREVIEW -->
        <section class="glass-card fade">
            <div class="section-head">
                <div>
                    <h2 class="section-title">Ringkasan Lowongan</h2>
                    <p class="section-hint">Preview lowongan Anda. Untuk edit detail/kelola semuanya, buka <b>Kelola Lowongan</b>.</p>
                </div>

                <a href="<?= base_url('employer/jobs') ?>" class="btn-secondary">
                    Lowongan Saya
                </a>
            </div>

            <!-- quick stats -->
            <div class="stats">
                <div class="stat">
                    <small>Total Lowongan</small>
                    <strong><?= (int)$totalJobs ?></strong>
                </div>
                <div class="stat">
                    <small>Open</small>
                    <strong><?= (int)$openJobs ?></strong>
                </div>
                <div class="stat">
                    <small>Closed / Lainnya</small>
                    <strong><?= (int)($closedJobs + $otherJobs) ?></strong>
                </div>
            </div>

            <?php if (!empty($jobs)): ?>
                <div class="job-list">
                    <?php foreach ($jobs as $job): ?>
                        <?php
                            [$stLabel, $stClass] = statusMeta($job['status'] ?? '');
                            $createdAt   = $job['created_at'] ?? null;
                            $createdText = $createdAt ? date('d M Y', strtotime($createdAt)) : null;
                            $loc         = $job['location'] ?? null;
                        ?>
                        <div class="job-item">
                            <div class="job-left">
                                <p class="job-title">
                                    <?= htmlspecialchars($job['title'] ?? '-') ?>
                                </p>

                                <div class="job-meta">
                                    <span class="badge <?= $stClass ?>">
                                        <?= htmlspecialchars($stLabel) ?>
                                    </span>

                                    <?php if (!empty($loc)): ?>
                                        <span><?= htmlspecialchars($loc) ?></span>
                                    <?php endif; ?>

                                    <?php if (!empty($createdText)): ?>
                                        <span>Diposting: <?= htmlspecialchars($createdText) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="job-actions">
                                <!-- sesuai views/employer/job_edit.php -->
                                <a href="<?= base_url('employer/edit/' . $job['job_id']) ?>"
                                   class="btn-secondary">
                                    Edit
                                </a>

                                <!-- sesuai views/employer/applicants.php -->
                                <a href="<?= base_url('employer/applicants/' . $job['job_id']) ?>"
                                   class="btn-secondary">
                                    Pelamar
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:14px;">
                    <a href="<?= base_url('employer/jobs') ?>" class="btn-secondary">
                        Lihat Semua Lowongan →
                    </a>
                </div>

            <?php else: ?>
                <div class="empty">
                    <strong>Belum ada lowongan.</strong><br>
                    Klik <b>Posting Lowongan</b> untuk mulai mencari kandidat terbaik.
                </div>
            <?php endif; ?>
        </section>

        <!-- RIGHT: PROFILE -->
        <section class="glass-card fade">
            <div class="section-head">
                <div>
                    <h2 class="section-title">Profil Perusahaan</h2>
                    <p class="section-hint">Tingkatkan kepercayaan pelamar dengan profil yang lengkap.</p>
                </div>

                <a href="<?= base_url('employer/edit_profile') ?>" class="btn-secondary">
                    Edit
                </a>
            </div>

            <?php if (!empty($profile)): ?>
                <div class="meta-grid">
                    <div class="meta-item">
                        <small>Nama Perusahaan</small>
                        <strong><?= htmlspecialchars($profile['company_name'] ?? '-') ?></strong>
                    </div>

                    <div class="meta-item">
                        <small>Industri</small>
                        <strong><?= htmlspecialchars($profile['industry'] ?? '-') ?></strong>
                    </div>

                    <div class="meta-item full">
                        <small>Alamat</small>
                        <div><?= nl2br(htmlspecialchars($profile['company_address'] ?? '-')) ?></div>
                    </div>

                    <div class="meta-item full">
                        <small>Website</small>
                        <strong><?= htmlspecialchars($profile['website'] ?? '-') ?></strong>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty">
                    <strong>Profil perusahaan belum diisi.</strong><br>
                    Lengkapi profil agar lowongan Anda terlihat lebih meyakinkan.
                </div>
            <?php endif; ?>
        </section>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const els = Array.from(document.querySelectorAll(".fade"));
    els.forEach((el, i) => {
        el.style.transitionDelay = (i * 70) + "ms";
    });

    if ("IntersectionObserver" in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add("show");
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });

        els.forEach(el => io.observe(el));
    } else {
        els.forEach(el => el.classList.add("show"));
    }
});
</script>

</body>
</html>
