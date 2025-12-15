<?php
// views/jobs/show.php

$isPelamar = (\Auth::check() && (\Auth::user()['role'] ?? '') === 'pelamar');

$title   = (string)($job['title'] ?? '-');
$company = (string)($job['company_name'] ?? '-');
$loc     = (string)($job['location'] ?? '-');
$industry= (string)($job['industry'] ?? '-');
$salary  = (string)($job['salary_range'] ?? '-');
$created = $job['created_at'] ?? null;

$companyAddr = $job['company_address'] ?? null;
$companyWeb  = $job['website'] ?? null;
$companyDesc = $job['company_description'] ?? null;

$matchScore = isset($job['match_score']) ? (int)$job['match_score'] : null;
if ($matchScore !== null) {
    if ($matchScore < 0) $matchScore = 0;
    if ($matchScore > 100) $matchScore = 100;
}

function safeDateLabel(?string $dt): string {
    if (empty($dt)) return '-';
    $ts = strtotime($dt);
    if ($ts === false) return htmlspecialchars($dt);
    return date('d M Y', $ts);
}

function matchLabelAndClass(int $score): array {
    $class = 'match-low';
    $label = 'Kurang Cocok';

    if ($score >= 70) { $class = 'match-high'; $label = 'Sangat Cocok'; }
    elseif ($score >= 40) { $class = 'match-mid'; $label = 'Cukup Cocok'; }

    return [$label, $class];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($title) ?> — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255,255,255,0.68);
    --glass-border: rgba(255,255,255,0.35);
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;
    --accent: #2563eb;
    --radius: 20px;

    --glass-shine: rgba(255,255,255,0.55);
}

/* ================= BASE (APPLE-LIKE) ================= */
body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;

    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;

    background: linear-gradient(180deg, #eef4ff, #ffffff);
    color: var(--text-main);
    line-height: 1.6;
    overflow-x: hidden;
}

/* ================= CONTAINER ================= */
.page-container {
    max-width: 1100px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= GLASS ================= */
.glass {
    background: var(--glass-bg);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow:
        0 14px 36px rgba(0,0,0,0.07),
        inset 0 1px 0 rgba(255,255,255,0.45);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    position: relative;
    overflow: hidden;
}

.glass::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 50% at 20% 10%, rgba(255,255,255,0.55), transparent 60%);
    opacity: 0.35;
    pointer-events: none;
}

.glass:hover {
    transform: translateY(-4px);
    box-shadow:
        0 22px 50px rgba(0,0,0,0.09),
        inset 0 1px 0 rgba(255,255,255,0.5);
}

/* ================= HEADER HERO ================= */
.hero {
    padding: 28px 28px 24px;
    margin-bottom: 22px;
}

.hero-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    flex-wrap: wrap;
}

.job-title {
    margin: 0 0 10px;
    font-size: 2.55rem;
    letter-spacing: -1.2px;
    line-height: 1.12;
}

.job-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
}

.hero-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
    align-items: center;
    margin-top: 6px;
}

/* ================= BUTTONS (TOP PRIORITY) ================= */
.btn-primary {
    height: 44px;
    padding: 0 16px;
    border-radius: 999px;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 800;
    border: none;
    cursor: pointer;
    box-shadow: 0 16px 34px rgba(30,109,232,0.22);
    transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.28);
    filter: brightness(1.05);
}

.btn-secondary {
    height: 44px;
    padding: 0 14px;
    border-radius: 999px;
    background: rgba(0,0,0,0.06);
    color: var(--text-main);
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 800;
    transition: transform 0.2s ease, background 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-secondary:hover {
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

.btn-soft {
    height: 44px;
    padding: 0 14px;
    border-radius: 999px;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.55);
    cursor: pointer;
    font-size: 0.92rem;
    font-weight: 800;
    color: var(--text-main);
    transition: transform .2s ease, background .2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-soft:hover {
    transform: translateY(-1px);
    background: rgba(255,255,255,0.75);
}

/* ================= META CHIPS (IMPORTANT SUMMARY) ================= */
.meta-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
}

.chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,0.70);
    border: 1px solid rgba(0,0,0,0.08);
    font-size: 0.88rem;
    color: var(--text-muted);
}

.chip strong { color: var(--text-main); }

/* ================= MATCH METER (HIGH PRIORITY FOR PELAMAR) ================= */
.match-meter {
    margin-top: 18px;
    max-width: 560px;
}

.match-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-bottom: 7px;
}

.match-label strong {
    color: var(--text-main);
    font-weight: 900;
}

.match-bar {
    height: 10px;
    background: rgba(0,0,0,0.08);
    border-radius: 999px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.55);
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.08);
}

.match-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.7s ease;
}

.match-low  { background: linear-gradient(90deg, #ef4444, #fca5a5); }
.match-mid  { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.match-high { background: linear-gradient(90deg, #22c55e, #86efac); }

.match-hint {
    margin-top: 10px;
    font-size: 0.9rem;
    color: var(--text-muted);
}

/* ================= CONTENT (LESS IMPORTANT DOWNWARDS) ================= */
.section {
    padding: 26px 26px;
    margin-bottom: 18px;
}

.section h3 {
    font-size: 1.22rem;
    margin: 0 0 14px;
    letter-spacing: -0.4px;
}

.prose {
    font-size: 0.96rem;
    color: #2c2c2e;
    line-height: 1.75;
}

.prose p { margin: 0; }

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
    gap: 14px;
    margin-top: 6px;
}

.info-item {
    background: rgba(255,255,255,0.60);
    border-radius: 14px;
    padding: 14px 14px;
    border: 1px solid rgba(0,0,0,0.06);
}

.info-item small {
    display: block;
    font-size: 0.72rem;
    color: var(--text-muted);
    margin-bottom: 4px;
}

.info-item strong {
    font-size: 0.92rem;
}

.website-link {
    color: var(--accent);
    text-decoration: none;
    font-weight: 900;
}
.website-link:hover { text-decoration: underline; }

/* ================= FADE ================= */
.fade {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.9s ease, transform 0.9s ease;
}
.fade.show {
    opacity: 1;
    transform: translateY(0);
}

/* ================= REDUCED MOTION ================= */
@media (prefers-reduced-motion: reduce) {
    .fade { transition: none; transform: none; }
    .glass, .btn-primary, .btn-secondary, .btn-soft { transition: none; }
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .job-title { font-size: 2.1rem; }
}
</style>
</head>

<body>

<div class="page-container">

    <!-- HERO: semua yang paling penting DI ATAS -->
    <section class="glass hero fade">

        <div class="hero-top">
            <div>
                <h1 class="job-title"><?= htmlspecialchars($title) ?></h1>
                <p class="job-subtitle"><?= htmlspecialchars($company) ?></p>
            </div>

            <div class="hero-actions">
                <?php if ($isPelamar): ?>
                    <a href="<?= base_url('application/apply/' . $job['job_id']) ?>" class="btn-primary">
                        Lamar
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="btn-primary">
                        Masuk untuk melamar
                    </a>
                <?php endif; ?>

                <a href="<?= base_url('jobs/index') ?>" class="btn-secondary">
                    Kembali
                </a>
            </div>
        </div>

        <div class="meta-chips">
            <span class="chip">📍 <strong><?= htmlspecialchars($loc) ?></strong></span>
            <span class="chip">🏷️ <strong><?= htmlspecialchars($industry) ?></strong></span>
            <span class="chip">💰 <strong><?= htmlspecialchars($salary) ?></strong></span>
            <span class="chip">🗓️ <strong><?= safeDateLabel($created) ?></strong></span>
        </div>

        <!-- MATCH SCORE: masih tinggi prioritas untuk pelamar -->
        <?php if ($isPelamar && $matchScore !== null): ?>
            <?php [$mLabel, $mClass] = matchLabelAndClass($matchScore); ?>
            <div class="match-meter">
                <div class="match-label">
                    <span>Kecocokan dengan profil Anda</span>
                    <strong><?= htmlspecialchars($mLabel) ?> • <?= (int)$matchScore ?>%</strong>
                </div>
                <div class="match-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= (int)$matchScore ?>">
                    <div class="match-fill <?= $mClass ?>" style="width: <?= (int)$matchScore ?>%"></div>
                </div>
            </div>
        <?php elseif (!$isPelamar): ?>
            <div class="match-hint">
                Login sebagai <strong>pelamar</strong> untuk melihat tingkat kecocokan berbasis profil.
            </div>
        <?php endif; ?>

    </section>

    <!-- RINGKASAN: penting tapi di bawah hero -->
    <section class="glass section fade">
        <h3>Ringkasan</h3>
        <div class="info-grid">
            <div class="info-item">
                <small>Perusahaan</small>
                <strong><?= htmlspecialchars($company) ?></strong>
            </div>
            <div class="info-item">
                <small>Lokasi</small>
                <strong><?= htmlspecialchars($loc) ?></strong>
            </div>
            <div class="info-item">
                <small>Industri</small>
                <strong><?= htmlspecialchars($industry) ?></strong>
            </div>
            <div class="info-item">
                <small>Gaji</small>
                <strong><?= htmlspecialchars($salary) ?></strong>
            </div>
        </div>
    </section>

    <!-- DETAIL PANJANG: makin bawah makin detail -->
    <section class="glass section fade">
        <h3>Deskripsi Pekerjaan</h3>
        <div class="prose">
            <p><?= nl2br(htmlspecialchars($job['description'] ?? 'Deskripsi belum tersedia.')) ?></p>
        </div>
    </section>

    <section class="glass section fade">
        <h3>Persyaratan</h3>
        <div class="prose">
            <p><?= nl2br(htmlspecialchars($job['requirements'] ?? 'Persyaratan belum tersedia.')) ?></p>
        </div>
    </section>

    <?php if (!empty($companyAddr) || !empty($companyWeb) || !empty($companyDesc)): ?>
        <section class="glass section fade">
            <h3>Tentang Perusahaan</h3>

            <div class="info-grid" style="margin-bottom:14px;">
                <?php if (!empty($companyAddr)): ?>
                    <div class="info-item">
                        <small>Alamat</small>
                        <strong><?= nl2br(htmlspecialchars($companyAddr)) ?></strong>
                    </div>
                <?php endif; ?>

                <?php if (!empty($companyWeb)): ?>
                    <div class="info-item">
                        <small>Website</small>
                        <strong>
                            <a class="website-link" href="<?= htmlspecialchars($companyWeb) ?>" target="_blank" rel="noopener noreferrer">
                                <?= htmlspecialchars($companyWeb) ?>
                            </a>
                        </strong>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($companyDesc)): ?>
                <div class="prose">
                    <p><?= nl2br(htmlspecialchars($companyDesc)) ?></p>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Fade in bertahap
    document.querySelectorAll('.fade').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), i * 120);
    });

    // Copy link UX
    const btn = document.getElementById('copyLinkBtn');
    if (btn) {
        btn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href);
                const old = btn.textContent;
                btn.textContent = 'Tersalin ✓';
                setTimeout(() => btn.textContent = old, 1200);
            } catch (e) {
                const old = btn.textContent;
                btn.textContent = 'Gagal menyalin';
                setTimeout(() => btn.textContent = old, 1200);
            }
        });
    }
});
</script>

</body>
</html>
