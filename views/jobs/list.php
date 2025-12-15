<?php
// views/jobs/list.php

$isPelamar = (\Auth::check() && (\Auth::user()['role'] ?? '') === 'pelamar');
$totalJobs = is_array($jobs ?? null) ? count($jobs) : 0;

function safeDateLabel(?string $dt): string {
    if (empty($dt)) return '-';
    $ts = strtotime($dt);
    if ($ts === false) return htmlspecialchars($dt);
    return date('d M Y', $ts);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cari Lowongan — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255,255,255,0.65);
    --glass-border: rgba(255,255,255,0.35);
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;
    --accent: #2563eb;
    --radius: 18px;

    /* premium helpers */
    --glass-shine: rgba(255,255,255,0.55);
    --shadow-soft: 0 18px 55px rgba(0,0,0,0.08);
}

/* ================= BASE ================= */
body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;
    background: linear-gradient(180deg, #eef4ff, #ffffff);
    color: var(--text-main);
    overflow-x: hidden;
}

/* ================= CONTAINER ================= */
.page-container {
    max-width: 1180px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= HEADER ================= */
.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.page-title {
    font-size: 2.4rem;
    letter-spacing: -1px;
    margin: 0;
}

.page-subtitle {
    margin: 10px 0 0;
    color: var(--text-muted);
    line-height: 1.65;
    max-width: 640px;
    font-size: 1.02rem;
}

/* ================= GLASS ================= */
.glass {
    background: var(--glass-bg);
    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow:
        0 12px 34px rgba(0,0,0,0.06),
        inset 0 1px 0 rgba(255,255,255,0.4);
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
        0 18px 45px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.45);
}

/* ================= SEARCH ================= */
.search-card {
    padding: 26px 28px;
    margin-bottom: 18px;
}

.search-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 18px;
    align-items: end;
}

label {
    display: block;
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--text-muted);
    letter-spacing: 0.02em;
}

.field-wrap {
    position: relative;
}

.field-ico {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.75;
    pointer-events: none;
    font-size: 0.95rem;
}

input {
    width: 100%;
    padding: 12px 14px 12px 38px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.15);
    background: rgba(255,255,255,0.85);
    font-size: 0.95rem;
    transition: border 0.2s ease, box-shadow 0.2s ease;
}

input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

/* ✅ tombol cari lebih “premium” */
.btn-primary {
    padding: 13px 28px;
    border-radius: 999px;
    border: none;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 16px 34px rgba(30,109,232,0.22);
    transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
    height: 44px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.28);
    filter: brightness(1.05);
}

.btn-primary:active {
    transform: translateY(-1px) scale(0.99);
}

/* ================= TOOLBAR (NEW UX) ================= */
.results-toolbar {
    padding: 14px 16px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
}

.toolbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,0.70);
    border: 1px solid rgba(0,0,0,0.08);
    font-size: 0.86rem;
    color: var(--text-muted);
}

.meta-pill strong {
    color: var(--text-main);
}

.quick-search {
    position: relative;
    min-width: 240px;
    max-width: 360px;
    flex: 1;
}

.quick-search .qs-ico {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.75;
    pointer-events: none;
}

.quick-search input {
    padding-left: 36px;
    height: 42px;
    border-radius: 12px;
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.select {
    height: 42px;
    padding: 0 12px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.12);
    background: rgba(255,255,255,0.75);
    font-size: 0.9rem;
}

.select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

.btn-soft {
    height: 42px;
    padding: 0 14px;
    border-radius: 999px;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.55);
    cursor: pointer;
    font-weight: 700;
    color: var(--text-main);
    transition: transform .2s ease, background .2s ease;
}

.btn-soft:hover {
    transform: translateY(-1px);
    background: rgba(255,255,255,0.75);
}

/* ================= JOB LIST ================= */
.job-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 26px;
}

/* ✅ lebih “liquid glass card” */
.job-card {
    padding: 22px 22px 22px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.job-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 50% at 18% 10%, rgba(255,255,255,0.55), transparent 60%);
    opacity: 0.55;
    pointer-events: none;
}

.job-top {
    position: relative;
}

.job-card h3 {
    margin: 0 0 8px;
    font-size: 1.08rem;
    letter-spacing: -0.2px;
    line-height: 1.25;
}

.job-company {
    font-size: 0.92rem;
    color: var(--text-muted);
    margin: 0 0 6px;
}

.job-location {
    font-size: 0.86rem;
    color: #8a8a8f;
    margin: 0 0 14px;
}

.badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,0.70);
    border: 1px solid rgba(0,0,0,0.08);
    font-size: 0.82rem;
    color: var(--text-muted);
}

.badge strong { color: var(--text-main); }

.job-preview {
    font-size: 0.92rem;
    color: #3a3a3c;
    line-height: 1.6;
    margin: 0;

    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ================= ACTION ================= */
.job-actions {
    margin-top: 18px;
    display: flex;
    gap: 10px;
    align-items: center;
}

/* konsisten (dipakai di HTML) */
.btn-secondary {
    padding: 10px 18px;
    border-radius: 999px;
    background: rgba(0,0,0,0.06);
    text-decoration: none;
    font-size: 0.86rem;
    font-weight: 700;
    color: var(--text-main);
    transition: transform 0.2s ease, background 0.2s ease;
}

.btn-secondary:hover {
    background: rgba(0,0,0,0.11);
    transform: translateY(-1px);
}

.btn-primary-small {
    padding: 10px 18px;
    border-radius: 999px;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;
    text-decoration: none;
    font-size: 0.86rem;
    font-weight: 700;
    box-shadow: 0 14px 30px rgba(30,109,232,0.22);
    transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
}

.btn-primary-small:hover {
    transform: translateY(-1px);
    filter: brightness(1.06);
    box-shadow: 0 18px 40px rgba(30,109,232,0.28);
}

/* ================= MATCH METER ================= */
.match-meter {
    margin-top: 14px;
}

.match-label {
    font-size: 0.76rem;
    color: var(--text-muted);
    margin-bottom: 7px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.match-label strong {
    color: var(--text-main);
    font-weight: 800;
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
    transition: width 0.6s ease;
}

.match-low  { background: linear-gradient(90deg, #ef4444, #fca5a5); }
.match-mid  { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.match-high { background: linear-gradient(90deg, #22c55e, #86efac); }

/* ================= EMPTY ================= */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: var(--text-muted);
}

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
    .glass, .btn-primary, .btn-secondary, .btn-primary-small { transition: none; }
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .search-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<div class="page-container">

    <!-- HEADER -->
    <div class="page-header fade">
        <div>
            <h1 class="page-title">Cari Lowongan</h1>
            <p class="page-subtitle">
                Temukan peluang yang relevan dengan cepat. Gunakan kata kunci & lokasi,
                lalu urutkan hasil untuk fokus pada posisi terbaik.
            </p>
        </div>
    </div>

    <!-- SEARCH -->
    <form method="GET" class="glass search-card fade">
        <div class="search-grid">
            <div>
                <label>Kata Kunci</label>
                <div class="field-wrap">
                    <span class="field-ico">🔎</span>
                    <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Contoh: UI/UX, Admin, Marketing">
                </div>
            </div>

            <div>
                <label>Lokasi</label>
                <div class="field-wrap">
                    <span class="field-ico">📍</span>
                    <input type="text" name="loc" value="<?= htmlspecialchars($location ?? '') ?>" placeholder="Contoh: Jakarta, Bandung, Remote">
                </div>
            </div>

            <div>
                <button class="btn-primary" type="submit">Cari</button>
            </div>
        </div>
    </form>

    <!-- TOOLBAR -->
    <div class="glass results-toolbar fade">
        <div class="toolbar-left">
            <div class="meta-pill" id="resultPill">
                Menampilkan <strong><?= (int)$totalJobs ?></strong> lowongan
            </div>

            <?php if (!$isPelamar): ?>
                <div class="meta-pill">
                    💡 <span>Masuk sebagai <strong>pelamar</strong> untuk melihat kecocokan berbasis profil</span>
                </div>
            <?php endif; ?>

            <div class="quick-search">
                <span class="qs-ico">⌕</span>
                <input id="quickSearch" type="text" placeholder="Filter cepat (posisi/perusahaan/lokasi)…">
            </div>
        </div>

        <div class="toolbar-right">
            <select id="sortSelect" class="select">
                <option value="newest">Urutkan: Terbaru</option>
                <option value="match">Urutkan: Match Tertinggi</option>
                <option value="title">Urutkan: Judul A–Z</option>
            </select>

            <button class="btn-soft" id="clearQuick" type="button">Reset</button>
        </div>
    </div>

    <!-- RESULTS -->
    <?php if (!empty($jobs)): ?>
        <div class="job-list fade" id="jobList">

            <?php foreach ($jobs as $job): ?>
                <?php
                    $title   = (string)($job['title'] ?? '-');
                    $company = (string)($job['company_name'] ?? '-');
                    $loc     = (string)($job['location'] ?? '-');

                    $industry = $job['industry'] ?? ($job['company_industry'] ?? null);
                    $industry = !empty($industry) ? (string)$industry : '-';

                    $salary = !empty($job['salary_range']) ? (string)$job['salary_range'] : '-';

                    $createdAt = $job['created_at'] ?? null;
                    $createdTs = 0;
                    if (!empty($createdAt)) {
                        $tmp = strtotime($createdAt);
                        $createdTs = ($tmp !== false) ? $tmp : 0;
                    }

                    $desc = (string)($job['description'] ?? '');
                    $preview = trim(mb_substr($desc, 0, 140));

                    $matchScore = isset($job['match_score']) ? (int)$job['match_score'] : null;
                    if ($matchScore !== null) {
                        if ($matchScore < 0) $matchScore = 0;
                        if ($matchScore > 100) $matchScore = 100;
                    }

                    $href = base_url('jobs/show/' . $job['job_id']);

                    $searchKey = strtolower($title . ' ' . $company . ' ' . $loc . ' ' . $industry);
                ?>

                <div
                    class="glass job-card"
                    data-href="<?= htmlspecialchars($href) ?>"
                    data-title="<?= htmlspecialchars(strtolower($title)) ?>"
                    data-company="<?= htmlspecialchars(strtolower($company)) ?>"
                    data-location="<?= htmlspecialchars(strtolower($loc)) ?>"
                    data-industry="<?= htmlspecialchars(strtolower($industry)) ?>"
                    data-search="<?= htmlspecialchars($searchKey) ?>"
                    data-date="<?= (int)$createdTs ?>"
                    data-match="<?= (int)($matchScore ?? 0) ?>"
                >

                    <div class="job-top">
                        <h3><?= htmlspecialchars($title) ?></h3>
                        <p class="job-company"><?= htmlspecialchars($company) ?></p>
                        <p class="job-location"><?= htmlspecialchars($loc) ?></p>

                        <div class="badges">
                            <span class="badge">🏷️ <strong><?= htmlspecialchars($industry) ?></strong></span>
                            <span class="badge">💰 <strong><?= htmlspecialchars($salary) ?></strong></span>
                            <span class="badge">🗓️ <strong><?= safeDateLabel($createdAt) ?></strong></span>
                        </div>

                        <p class="job-preview">
                            <?= htmlspecialchars($preview ?: 'Deskripsi belum tersedia.') ?>
                        </p>

                        <!-- ✅ MATCH METER (lebih “benar” secara UX: hanya untuk pelamar) -->
                        <?php if ($isPelamar && $matchScore !== null): ?>
                            <?php
                                $class = 'match-low';
                                $label = 'Kurang Cocok';

                                if ($matchScore >= 70) { $class = 'match-high'; $label = 'Sangat Cocok'; }
                                elseif ($matchScore >= 40) { $class = 'match-mid'; $label = 'Cukup Cocok'; }
                            ?>
                            <div class="match-meter">
                                <div class="match-label">
                                    <span>Kecocokan</span>
                                    <strong><?= htmlspecialchars($label) ?> • <?= (int)$matchScore ?>%</strong>
                                </div>
                                <div class="match-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= (int)$matchScore ?>">
                                    <div class="match-fill <?= $class ?>" style="width: <?= (int)$matchScore ?>%"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- /MATCH METER -->
                    </div>

                    <div class="job-actions">
                        <a href="<?= $href ?>" class="btn-secondary">
                            Detail
                        </a>

                        <?php if ($isPelamar): ?>
                            <a href="<?= base_url('application/apply/' . $job['job_id']) ?>"
                               class="btn-primary-small">
                                Lamar
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <div class="glass empty-state fade">
            <p>Tidak ada lowongan yang sesuai dengan pencarian Anda.</p>
        </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Fade in
    document.querySelectorAll('.fade').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), i * 120);
    });

    const jobList = document.getElementById('jobList');
    if (!jobList) return;

    const cards = Array.from(jobList.querySelectorAll('.job-card'));
    const quickSearch = document.getElementById('quickSearch');
    const sortSelect = document.getElementById('sortSelect');
    const clearBtn = document.getElementById('clearQuick');
    const resultPill = document.getElementById('resultPill');

    // Card click => detail (tombol tetap aman)
    cards.forEach(card => {
        const href = card.dataset.href;
        if (!href) return;

        card.addEventListener('click', (e) => {
            if (e.target.closest('a, button, input, select, textarea')) return;
            window.location.href = href;
        });
    });

    function applyFilterAndSort() {
        const q = (quickSearch?.value || '').trim().toLowerCase();
        let visible = 0;

        // Filter
        cards.forEach(card => {
            const hay = (card.dataset.search || '');
            const show = q ? hay.includes(q) : true;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Sort (only visible cards moved)
        const visibleCards = cards.filter(c => c.style.display !== 'none');

        const mode = sortSelect?.value || 'newest';

        visibleCards.sort((a, b) => {
            if (mode === 'match') {
                const ma = parseInt(a.dataset.match || '0', 10);
                const mb = parseInt(b.dataset.match || '0', 10);
                return mb - ma;
            }
            if (mode === 'title') {
                const ta = (a.dataset.title || '');
                const tb = (b.dataset.title || '');
                return ta.localeCompare(tb);
            }
            // newest default
            const da = parseInt(a.dataset.date || '0', 10);
            const db = parseInt(b.dataset.date || '0', 10);
            return db - da;
        });

        visibleCards.forEach(c => jobList.appendChild(c));

        if (resultPill) {
            resultPill.innerHTML = `Menampilkan <strong>${visible}</strong> lowongan`;
        }
    }

    quickSearch?.addEventListener('input', applyFilterAndSort);
    sortSelect?.addEventListener('change', applyFilterAndSort);

    clearBtn?.addEventListener('click', () => {
        if (quickSearch) quickSearch.value = '';
        if (sortSelect) sortSelect.value = 'newest';
        applyFilterAndSort();
    });

    applyFilterAndSort();
});
</script>

</body>
</html>
