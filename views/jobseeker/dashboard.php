<?php
// views/jobseeker/dashboard.php

// === MATCH HELPER (DITAMBAHKAN) ===
if (!function_exists('matchMeta')) {
    function matchMeta(int $score): array {
        if ($score >= 65) {
            return ['Tinggi', '#22c55e'];
        } elseif ($score >= 35) {
            return ['Sedang', '#f59e0b'];
        }
        return ['Rendah', '#ef4444'];
    }
}

// === UX HELPER (DITAMBAHKAN): Kelengkapan profil ===
$profileFields = ['full_name','phone','address','birth_date','industry','skills','preferred_job','experience','about','resume_path'];
$filled = 0;

if (!empty($profile) && is_array($profile)) {
    foreach ($profileFields as $f) {
        if (isset($profile[$f]) && trim((string)$profile[$f]) !== '') {
            $filled++;
        }
    }
}

$profileCompletion = (int) round(($filled / max(count($profileFields), 1)) * 100);

// counts (aman kalau variabel tidak ada)
$jobsCount = (isset($jobs) && is_array($jobs)) ? count($jobs) : null;
$recCount  = (isset($recommendations) && is_array($recommendations)) ? count($recommendations) : null;
$appCount  = (isset($applications) && is_array($applications)) ? count($applications) : null;
$notifCount = (isset($notifications) && is_array($notifications)) ? count($notifications) : null;

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Jobseeker — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255, 255, 255, 0.65);
    --glass-border: rgba(255, 255, 255, 0.35);
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;
    --accent: #2563eb;
    --radius: 18px;
}

/* ================= BASE ================= */
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
}

body::before {
    content: "";
    position: fixed;
    inset: -40% -40%;
    z-index: -1;

    background:
        radial-gradient(
            40% 35% at 20% 30%,
            rgba(37, 99, 235, 0.18),
            transparent 60%
        ),
        radial-gradient(
            35% 30% at 70% 40%,
            rgba(96, 165, 250, 0.18),
            transparent 65%
        ),
        radial-gradient(
            30% 25% at 50% 70%,
            rgba(191, 219, 254, 0.25),
            transparent 70%
        );

    filter: blur(60px);
    animation: cloudMove 90s linear infinite;
}

body::after {
    content: "";
    position: fixed;
    inset: -50% -50%;
    z-index: -2;

    background:
        radial-gradient(
            45% 40% at 30% 60%,
            rgba(30, 109, 232, 0.12),
            transparent 65%
        );

    filter: blur(90px);
    animation: cloudMoveReverse 140s linear infinite;
}

@keyframes cloudMoveReverse {
    0% { transform: translate(0, 0); }
    50% { transform: translate(-8%, 10%); }
    100% { transform: translate(0, 0); }
}

@keyframes cloudMove {
    0% { transform: translate(0, 0); }
    50% { transform: translate(10%, -8%); }
    100% { transform: translate(0, 0); }
}

/* ================= CONTAINER ================= */
.dashboard-container {
    max-width: 1180px;
    margin: 70px auto 100px;
    padding: 0 22px;
}

/* ================= HEADER (NEW UX) ================= */
.dash-header{
    display:flex;
    align-items:flex-end;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 26px;
}

.page-title {
    font-size: 2.4rem;
    letter-spacing: -1px;
    margin: 0;
}

.page-subtitle{
    margin-top: 10px;
    color: var(--text-muted);
    line-height: 1.6;
    max-width: 720px;
    font-size: 1.02rem;
}

.header-actions{
    display:flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.action-pill{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 10px 16px;
    border-radius: 999px;
    text-decoration:none;
    font-size: .88rem;
    font-weight: 600;
    color: var(--text-main);
    background: rgba(255,255,255,0.65);
    border: 1px solid rgba(0,0,0,0.08);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
}
.action-pill:hover{
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.10);
}
.action-pill.primary{
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: #fff;
    border: none;
    box-shadow: 0 14px 30px rgba(30,109,232,0.28);
}
.action-pill.primary:hover{
    box-shadow: 0 20px 44px rgba(30,109,232,0.35);
}

/* ================= GLASS CARD ================= */
.card,
.match-card {
    background: var(--glass-bg);
    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 32px;
    margin-bottom: 55px;
    box-shadow:
        0 10px 30px rgba(0,0,0,0.06),
        inset 0 1px 0 rgba(255,255,255,0.4);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    position: relative;
    overflow: hidden;
}

/* subtle specular highlight */
.card::before,
.match-card::before {
    content:"";
    position:absolute;
    inset:-2px;
    pointer-events:none;
    background:
        radial-gradient(60% 45% at 20% 0%, rgba(255,255,255,0.65), transparent 55%),
        radial-gradient(45% 35% at 90% 10%, rgba(255,255,255,0.28), transparent 60%);
    opacity:.55;
}

.card:hover,
.match-card:hover {
    transform: translateY(-6px);
    box-shadow:
        0 18px 45px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.45);
}

/* ================= SECTION TOP (NEW UX) ================= */
.section-top{
    display:flex;
    align-items:center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
    position: relative;
    z-index: 1;
}

.section-title {
    font-size: 1.55rem;
    letter-spacing: -0.5px;
    margin: 0;
}

.section-link{
    font-size: .88rem;
    color: var(--accent);
    font-weight: 600;
    text-decoration:none;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,0.55);
    border: 1px solid rgba(0,0,0,0.06);
    transition: transform .25s ease, box-shadow .25s ease;
}
.section-link:hover{
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.10);
}

/* ================= TEXT ================= */
.card p {
    margin-bottom: 14px;
    line-height: 1.7;
    position: relative;
    z-index: 1;
}

.text-muted {
    color: var(--text-muted);
}

/* ================= BUTTONS ================= */
.btn-secondary {
    display: inline-block;
    padding: 9px 18px;
    border-radius: 999px;
    background: rgba(255,255,255,0.6);
    border: 1px solid rgba(0,0,0,0.08);
    color: var(--text-main);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
}
.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.75);
}

.btn-primary-small {
    display: inline-block;
    padding: 9px 18px;
    border-radius: 999px;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: #fff;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 700;
    box-shadow: 0 14px 30px rgba(30,109,232,0.25);
    transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
}
.btn-primary-small:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 44px rgba(30,109,232,0.34);
    filter: brightness(1.05);
}

/* ================= OVERVIEW (NEW UX) ================= */
.stats-grid{
    display:grid;
    grid-template-columns: repeat(4, minmax(0,1fr));
    gap: 16px;
    position: relative;
    z-index: 1;
}

.stat{
    background: rgba(255,255,255,0.62);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px;
    padding: 16px 16px 14px;
}

.stat-label{
    font-size: .78rem;
    color: var(--text-muted);
    font-weight: 700;
    letter-spacing: .2px;
    margin-bottom: 8px;
}

.stat-value{
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.progress{
    margin-top: 10px;
    height: 10px;
    border-radius: 999px;
    background: rgba(0,0,0,0.10);
    overflow:hidden;
}
.progress span{
    display:block;
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #2563eb, #60a5fa);
    border-radius: 999px;
    transition: width .6s ease;
}

.callout{
    margin-top: 18px;
    padding: 14px 16px;
    border-radius: 16px;
    background: rgba(255,255,255,0.62);
    border: 1px solid rgba(0,0,0,0.06);
    position: relative;
    z-index: 1;
    color: rgba(29,29,31,.92);
}
.callout a{
    margin-left: 8px;
    color: var(--accent);
    font-weight: 700;
    text-decoration:none;
}
.callout a:hover{ text-decoration: underline; }

/* ================= JOB LIST ================= */
.job-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
    position: relative;
    z-index: 1;
}

.job-card {
    background: rgba(255,255,255,0.70);
    backdrop-filter: blur(14px) saturate(160%);
    -webkit-backdrop-filter: blur(14px) saturate(160%);
    border-radius: 18px;
    padding: 22px;
    border: 1px solid rgba(255,255,255,0.45);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.job-card::before{
    content:"";
    position:absolute;
    inset:-2px;
    pointer-events:none;
    background: radial-gradient(55% 45% at 20% 0%, rgba(255,255,255,0.55), transparent 55%);
    opacity:.55;
}

.job-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 36px rgba(0,0,0,0.10);
}

.job-card h3 {
    margin: 0 0 8px;
    position: relative;
    z-index: 1;
}

.job-company,
.job-location,
.job-preview{
    position: relative;
    z-index: 1;
}

.job-company {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin: 0 0 2px;
}

.job-location {
    font-size: 0.85rem;
    margin: 0 0 12px;
    color: var(--text-muted);
}

.job-preview {
    font-size: 0.9rem;
    color: #444;
    margin: 0;
    line-height: 1.6;

    /* clamp supaya card konsisten */
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ================= MATCH METER (UPGRADE) ================= */
.match-meter{
    margin-top: 14px;
    position: relative;
    z-index: 1;
}

.match-row{
    display:flex;
    align-items:center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 8px;
}

.match-badge{
    display:inline-flex;
    align-items:center;
    gap: 8px;
    font-size: .78rem;
    font-weight: 800;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,0.60);
    border: 1px solid rgba(0,0,0,0.06);
    color: rgba(29,29,31,.9);
}
.match-badge::before{
    content:"";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--badge, #2563eb);
}

.match-value{
    font-size: .9rem;
    font-weight: 900;
}

.match-hint{
    margin-top: 12px;
    font-size: .85rem;
    color: var(--text-muted);
}

/* bar memakai CSS variable */
.match-bar {
    background: rgba(0,0,0,0.10);
    height: 10px;
    border-radius: 999px;
    overflow: hidden;
}
.match-bar span {
    display: block;
    height: 100%;
    width: 0%;
    border-radius: 999px;
    background: linear-gradient(90deg, var(--bar, #2563eb), rgba(96,165,250,0.95));
    transition: width 0.6s ease;
}

/* ================= FADE (UPGRADE) ================= */
.fade {
    opacity: 0;
    transform: translateY(22px);
    transition: opacity 0.85s ease, transform 0.85s ease;
    transition-delay: var(--d, 0ms);
}
.fade.show {
    opacity: 1;
    transform: translateY(0);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 980px){
    .dash-header{ flex-direction: column; align-items:flex-start; }
    .header-actions{ justify-content:flex-start; }
    .stats-grid{ grid-template-columns: repeat(2, minmax(0,1fr)); }
}
@media (max-width: 560px){
    .stats-grid{ grid-template-columns: 1fr; }
}
</style>
</head>

<body>

<div class="dashboard-container">

    <!-- ================= HEADER (UPGRADE) ================= -->
    <div class="dash-header fade">
        <div>
            <h1 class="page-title">
                Halo, <?= htmlspecialchars($profile['full_name'] ?? explode('@', $user['email'])[0]) ?>
            </h1>
            <div class="page-subtitle">
                Ringkasan aktivitas Anda, lowongan terbaru, serta rekomendasi berdasarkan profil dan preferensi.
            </div>
        </div>

        <div class="header-actions">
            <a class="action-pill" href="<?= base_url('jobseeker/edit_profile') ?>">Perbarui Profil</a>
            <a class="action-pill primary" href="<?= base_url('jobs/index') ?>">Cari Lowongan</a>
        </div>
    </div>

    <!-- ================= OVERVIEW (NEW) ================= -->
    <section class="card fade">
        <div class="section-top">
            <h2 class="section-title">Ringkasan</h2>
            <a class="section-link" href="<?= base_url('jobseeker/edit_profile') ?>">Tingkatkan Profil</a>
        </div>

        <div class="stats-grid">
            <div class="stat">
                <div class="stat-label">Kelengkapan Profil</div>
                <div class="stat-value"><?= (int)$profileCompletion ?>%</div>
                <div class="progress">
                    <span style="width: <?= (int)$profileCompletion ?>%"></span>
                </div>
            </div>

            <div class="stat">
                <div class="stat-label">Lamaran</div>
                <div class="stat-value"><?= $appCount !== null ? (int)$appCount : '—' ?></div>
            </div>

            <div class="stat">
                <div class="stat-label">Notifikasi</div>
                <div class="stat-value"><?= $notifCount !== null ? (int)$notifCount : '—' ?></div>
            </div>

            <div class="stat">
                <div class="stat-label">Rekomendasi</div>
                <div class="stat-value"><?= $recCount !== null ? (int)$recCount : '—' ?></div>
            </div>
        </div>

        <?php if ($profileCompletion < 70): ?>
            <div class="callout">
                <strong>Saran cepat:</strong> Lengkapi profil untuk meningkatkan kualitas rekomendasi dan akurasi tingkat kecocokan.
                <a href="<?= base_url('jobseeker/edit_profile') ?>">Lengkapi sekarang</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- ================= LOWONGAN ================= -->
    <section class="card fade">
        <div class="section-top">
            <h2 class="section-title">Lowongan Terbaru</h2>
            <a class="section-link" href="<?= base_url('jobs/index') ?>">Lihat semua</a>
        </div>

        <?php if (!empty($jobs)): ?>
            <div class="job-list">
                <?php foreach ($jobs as $job): ?>
                    <?php
                        $score = isset($job['match_score']) ? (int)$job['match_score'] : null;
                        if ($score !== null) {
                            [$label, $color] = matchMeta($score);
                        }
                    ?>
                    <div class="job-card fade">
                        <h3><?= htmlspecialchars($job['title']) ?></h3>
                        <p class="job-company"><?= htmlspecialchars($job['company_name']) ?></p>
                        <p class="job-location"><?= htmlspecialchars($job['location'] ?? '-') ?></p>

                        <?php if (!empty($job['description'])): ?>
                            <p class="job-preview">
                                <?= htmlspecialchars(substr($job['description'], 0, 180)) ?>…
                            </p>
                        <?php endif; ?>

                        <!-- MATCH METER (NEW DI LOWONGAN TERBARU) -->
                        <?php if ($score !== null): ?>
                            <div class="match-meter" style="--bar: <?= htmlspecialchars($color) ?>; --badge: <?= htmlspecialchars($color) ?>;">
                                <div class="match-row">
                                    <span class="match-badge"><?= htmlspecialchars($label) ?></span>
                                    <span class="match-value"><?= (int)$score ?>%</span>
                                </div>
                                <div class="match-bar">
                                    <span style="width: <?= (int)$score ?>%"></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="match-hint">Lengkapi profil untuk melihat tingkat kecocokan.</p>
                        <?php endif; ?>

                        <div style="margin-top:16px; position:relative; z-index:1;">
                            <a href="<?= base_url('jobs/show/' . $job['job_id']) ?>" class="btn-secondary">
                                Lihat Detail
                            </a>
                            <a href="<?= base_url('application/apply/' . $job['job_id']) ?>" class="btn-primary-small">
                                Lamar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Belum ada lowongan.</p>
        <?php endif; ?>
    </section>

    <!-- ================= REKOMENDASI ================= -->
    <section class="card fade">
        <div class="section-top">
            <h2 class="section-title">Rekomendasi Pekerjaan</h2>
            <a class="section-link" href="<?= base_url('jobseeker/edit_profile') ?>">Optimalkan rekomendasi</a>
        </div>

        <?php if (!empty($recommendations)): ?>
            <?php foreach ($recommendations as $job): ?>
                <?php
                    $score = (int)($job['match_score'] ?? 0);
                    [$label, $color] = matchMeta($score);
                ?>
                <div class="match-card fade">
                    <h3 style="position:relative;z-index:1;margin:0 0 8px;">
                        <?= htmlspecialchars($job['title']) ?>
                    </h3>
                    <p class="job-company"><?= htmlspecialchars($job['company_name']) ?></p>
                    <p class="job-location"><?= htmlspecialchars($job['location'] ?? '-') ?></p>

                    <!-- MATCH METER (UPGRADED) -->
                    <div class="match-meter" style="--bar: <?= htmlspecialchars($color) ?>; --badge: <?= htmlspecialchars($color) ?>;">
                        <div class="match-row">
                            <span class="match-badge"><?= htmlspecialchars($label) ?></span>
                            <span class="match-value"><?= (int)$score ?>%</span>
                        </div>
                        <div class="match-bar">
                            <span style="width: <?= (int)$score ?>%"></span>
                        </div>
                    </div>

                    <div style="margin-top:14px; position:relative; z-index:1;">
                        <a href="<?= base_url('jobs/show/' . $job['job_id']) ?>" class="btn-secondary">
                            Detail
                        </a>
                        <a href="<?= base_url('application/apply/' . $job['job_id']) ?>" class="btn-primary-small">
                            Lamar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Belum ada rekomendasi.</p>
        <?php endif; ?>
    </section>

    <!-- ================= PROFIL ================= -->
    <section class="card fade">
        <div class="section-top">
            <h2 class="section-title">Profil Anda</h2>
            <a class="section-link" href="<?= base_url('jobseeker/edit_profile') ?>">Edit</a>
        </div>

        <?php if (!empty($profile)): ?>
            <p><strong>Nama</strong><br><?= htmlspecialchars($profile['full_name'] ?? '-') ?></p>
            <p><strong>Telepon</strong><br><?= htmlspecialchars($profile['phone'] ?? '-') ?></p>
            <p><strong>Alamat</strong><br><?= nl2br(htmlspecialchars($profile['address'] ?? '-')) ?></p>
            <p><strong>Tentang Saya</strong><br><?= nl2br(htmlspecialchars($profile['about'] ?? '-')) ?></p>
        <?php else: ?>
            <p class="text-muted">Profil belum diisi.</p>
        <?php endif; ?>

        <div style="margin-top:22px; position:relative; z-index:1;">
            <a href="<?= base_url('jobseeker/edit_profile') ?>" class="btn-secondary">
                Edit Profil
            </a>
        </div>
    </section>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const els = document.querySelectorAll('.fade');

    // Stagger per list supaya animasi tidak “panjang banget”
    document.querySelectorAll('.job-list').forEach(list => {
        list.querySelectorAll('.job-card.fade').forEach((el, i) => {
            el.style.setProperty('--d', (i * 70) + 'ms');
        });
    });

    // default small delays for top blocks
    document.querySelectorAll('.dash-header.fade, .card.fade, .match-card.fade').forEach((el, i) => {
        if (!el.style.getPropertyValue('--d')) {
            el.style.setProperty('--d', (i * 60) + 'ms');
        }
    });

    if (reduceMotion) {
        els.forEach(el => el.classList.add('show'));
        return;
    }

    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: "0px 0px -10% 0px" });

    els.forEach(el => io.observe(el));
});
</script>

</body>
</html>
