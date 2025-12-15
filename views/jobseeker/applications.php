<?php
// views/jobseeker/applications.php

$apps = is_array($applications ?? null) ? $applications : [];
$total = count($apps);

$norm = function ($s): string {
    $s = strtolower(trim((string)$s));
    $s = preg_replace('/\s+/', '', $s);
    return $s;
};

$counts = [
    'terkirim' => 0,
    'direview' => 0,
    'diterima' => 0,
    'ditolak'  => 0,
];

foreach ($apps as $a) {
    $st = $norm($a['status'] ?? '');
    if (isset($counts[$st])) $counts[$st]++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lamaran Saya — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255,255,255,0.65);
    --glass-border: rgba(255,255,255,0.35);
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;
    --accent: #2563eb;
    --radius: 18px;

    --ok: #22c55e;
    --warn: #f59e0b;
    --bad: #ef4444;
}

/* ================= BASE ================= */
body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;
    background: linear-gradient(180deg, #eaf1ff 0%, #f4f8ff 55%, #ffffff 100%);
    color: var(--text-main);
    overflow-x: hidden;
}

/* BACKGROUND CLOUDS (CONSISTENT) */
body::before,
body::after {
    content: "";
    position: fixed;
    inset: -40%;
    z-index: -2;
    pointer-events: none;
}
body::before {
    background:
        radial-gradient(40% 35% at 20% 30%, rgba(37,99,235,0.18), transparent 60%),
        radial-gradient(35% 30% at 70% 40%, rgba(96,165,250,0.18), transparent 65%),
        radial-gradient(30% 25% at 50% 70%, rgba(191,219,254,0.25), transparent 70%);
    filter: blur(70px);
    animation: cloudMove 90s linear infinite;
}
body::after {
    background:
        radial-gradient(45% 40% at 30% 60%, rgba(30,109,232,0.12), transparent 65%);
    filter: blur(100px);
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
    max-width: 1060px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= HEADER ================= */
.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 26px;
    flex-wrap: wrap;
}

.page-title {
    font-size: 2.35rem;
    letter-spacing: -1px;
    margin: 0;
}

.page-subtitle {
    margin: 10px 0 0;
    color: var(--text-muted);
    line-height: 1.6;
    max-width: 520px;
    font-size: 0.98rem;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;

    height: 42px;
    padding: 0 16px;
    border-radius: 999px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;

    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease, background .25s ease;
    user-select: none;
}

.btn-ghost {
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(14px) saturate(160%);
    -webkit-backdrop-filter: blur(14px) saturate(160%);
    border: 1px solid rgba(255,255,255,0.45);
    color: var(--text-main);
    box-shadow: 0 10px 26px rgba(0,0,0,0.06);
}
.btn-ghost:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 36px rgba(0,0,0,0.08);
}

.btn-primary {
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: #fff;
    box-shadow: 0 14px 30px rgba(30,109,232,0.25);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 45px rgba(30,109,232,0.35);
    filter: brightness(1.05);
}

/* ================= GLASS CARD ================= */
.glass-card {
    position: relative;
    background: var(--glass-bg);
    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 22px 22px;
    box-shadow:
        0 12px 34px rgba(0,0,0,0.06),
        inset 0 1px 0 rgba(255,255,255,0.4);
    overflow: hidden;
    transition: transform 0.35s ease, box-shadow 0.35s ease;
}

.glass-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 45% at 20% 10%, rgba(255,255,255,0.55), transparent 60%);
    opacity: 0.35;
    pointer-events: none;
}

.glass-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 18px 45px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.45);
}

/* ================= STATS ================= */
.stats-grid {
    display: grid;
    grid-template-columns: 1.2fr repeat(4, 1fr);
    gap: 12px;
    margin: 18px 0 18px;
}

.stat {
    padding: 18px 18px;
    border-radius: 16px;
    background: rgba(255,255,255,0.55);
    border: 1px solid rgba(255,255,255,0.45);
    backdrop-filter: blur(14px) saturate(160%);
    -webkit-backdrop-filter: blur(14px) saturate(160%);
}

.stat .k {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-bottom: 10px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.stat .v {
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.stat small {
    display: inline-block;
    margin-top: 8px;
    color: var(--text-muted);
    font-size: 0.85rem;
}

@media (max-width: 920px) {
    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }
}

/* ================= TOOLBAR ================= */
.toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.chip {
    appearance: none;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 0.85rem;
    cursor: pointer;
    color: var(--text-main);
    transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
}
.chip:hover {
    transform: translateY(-1px);
    background: rgba(255,255,255,0.75);
    box-shadow: 0 10px 24px rgba(0,0,0,0.06);
}
.chip.active {
    border-color: rgba(37,99,235,0.35);
    background: rgba(37,99,235,0.12);
    color: #1746b6;
}

.search-wrap {
    position: relative;
    min-width: 260px;
    flex: 1;
    max-width: 360px;
}

.search-wrap input {
    width: 100%;
    padding: 11px 12px 11px 40px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.12);
    background: rgba(255,255,255,0.80);
    font-size: 0.93rem;
}
.search-wrap input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}
.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.95rem;
    pointer-events: none;
}

.result-count {
    font-size: 0.88rem;
    color: var(--text-muted);
}

/* ================= TABLE ================= */
.table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 14px;
}

.table th {
    text-align: left;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 14px 16px;
    background: rgba(255,255,255,0.75);
    color: var(--text-muted);
    border-bottom: 1px solid rgba(0,0,0,0.08);
}

.table td {
    padding: 16px;
    font-size: 0.95rem;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    vertical-align: middle;
}

.table tr {
    transition: background 0.25s ease, transform 0.25s ease;
}

.table tr[data-href] {
    cursor: pointer;
}

.table tr:hover {
    background: rgba(255,255,255,0.85);
}

/* Job link */
.job-link {
    color: var(--text-main);
    text-decoration: none;
    font-weight: 700;
}
.job-link:hover {
    text-decoration: underline;
}

/* ================= STATUS PILL ================= */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 800;
    letter-spacing: 0.01em;
    border: 1px solid rgba(0,0,0,0.08);
    background: rgba(255,255,255,0.75);
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 99px;
    display: inline-block;
}

.status-terkirim .dot { background: var(--accent); }
.status-direview .dot { background: var(--warn); }
.status-diterima .dot { background: var(--ok); }
.status-ditolak  .dot { background: var(--bad); }

/* ================= ROW ACTION ================= */
.row-action a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    border-radius: 999px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 700;
    background: rgba(0,0,0,0.06);
    color: var(--text-main);
    transition: background .2s ease, transform .2s ease;
}
.row-action a:hover {
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

/* ================= EMPTY ================= */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}
.empty-state p {
    margin: 0 0 18px;
    line-height: 1.7;
}
.empty-cta {
    display: inline-flex;
    padding: 12px 18px;
    border-radius: 999px;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;
    text-decoration: none;
    font-weight: 700;
    box-shadow: 0 16px 34px rgba(30,109,232,0.28);
    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
}
.empty-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.38);
    filter: brightness(1.06);
}

/* ================= FADE ================= */
.fade {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.9s ease, transform 0.9s ease;
}
.fade.show {
    opacity: 1;
    transform: translateY(0);
}

/* ================= RESPONSIVE TABLE ================= */
@media (max-width: 768px) {
    .table thead {
        display: none;
    }
    .table tr {
        display: block;
        margin-bottom: 14px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.06);
        background: rgba(255,255,255,0.6);
    }
    .table td {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 14px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .table td:last-child {
        border-bottom: none;
    }
    .table td::before {
        content: attr(data-label);
        font-weight: 800;
        color: var(--text-muted);
        font-size: 0.82rem;
        min-width: 110px;
    }
}
</style>
</head>

<body>

<div class="page-container">

    <div class="page-header fade">
        <div>
            <h1 class="page-title">Lamaran Saya</h1>
            <p class="page-subtitle">
                Pantau status lamaran Anda dengan cepat dan rapi — dari terkirim hingga keputusan akhir.
            </p>
        </div>

        <div class="header-actions">
            <a class="btn btn-ghost" href="<?= base_url('jobseeker/dashboard') ?>">← Dashboard</a>
            <a class="btn btn-primary" href="<?= base_url('jobs/index') ?>">Cari Lowongan</a>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats-grid fade">
        <div class="stat">
            <div class="k">Total Lamaran</div>
            <div class="v"><?= (int)$total ?></div>
            <small>Semua riwayat lamaran Anda</small>
        </div>

        <div class="stat">
            <div class="k">Terkirim</div>
            <div class="v"><?= (int)$counts['terkirim'] ?></div>
            <small>Menunggu diproses</small>
        </div>

        <div class="stat">
            <div class="k">Direview</div>
            <div class="v"><?= (int)$counts['direview'] ?></div>
            <small>Dalam peninjauan</small>
        </div>

        <div class="stat">
            <div class="k">Diterima</div>
            <div class="v"><?= (int)$counts['diterima'] ?></div>
            <small>Hasil positif</small>
        </div>

        <div class="stat">
            <div class="k">Ditolak</div>
            <div class="v"><?= (int)$counts['ditolak'] ?></div>
            <small>Perlu peluang lain</small>
        </div>
    </div>

    <?php if (!empty($apps)): ?>

        <div class="glass-card fade">

            <!-- TOOLBAR -->
            <div class="toolbar">
                <div class="chips" role="tablist" aria-label="Filter status lamaran">
                    <button type="button" class="chip active" data-filter="all">Semua</button>
                    <button type="button" class="chip" data-filter="terkirim">Terkirim</button>
                    <button type="button" class="chip" data-filter="direview">Direview</button>
                    <button type="button" class="chip" data-filter="diterima">Diterima</button>
                    <button type="button" class="chip" data-filter="ditolak">Ditolak</button>
                </div>

                <div class="search-wrap">
                    <span class="search-icon">🔎</span>
                    <input id="searchInput" type="text" placeholder="Cari posisi / lokasi / status...">
                </div>

                <div class="result-count" id="resultCount"></div>
            </div>

            <table class="table" id="appsTable">
                <thead>
                    <tr>
                        <th>Posisi</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($apps as $app): ?>
                    <?php
                        $title = (string)($app['title'] ?? '-');
                        $loc   = (string)($app['location'] ?? '-');
                        $stRaw = (string)($app['status'] ?? '-');
                        $st    = $norm($stRaw);

                        $jobId = $app['job_id'] ?? null; // jika ada dari query JOIN
                        $href  = (!empty($jobId)) ? base_url('jobs/show/' . $jobId) : '';

                        $dateRaw = $app['submitted_at'] ?? null;
                        $dateText = '-';
                        if (!empty($dateRaw)) {
                            $ts = strtotime($dateRaw);
                            if ($ts !== false) $dateText = date('d M Y, H:i', $ts);
                            else $dateText = (string)$dateRaw;
                        }

                        $searchKey = strtolower($title . ' ' . $loc . ' ' . $stRaw);
                    ?>
                    <tr
                        data-status="<?= htmlspecialchars($st) ?>"
                        data-search="<?= htmlspecialchars($searchKey) ?>"
                        <?= $href ? 'data-href="' . htmlspecialchars($href) . '"' : '' ?>
                    >
                        <td data-label="Posisi">
                            <?php if ($href): ?>
                                <a class="job-link" href="<?= $href ?>">
                                    <?= htmlspecialchars($title) ?>
                                </a>
                            <?php else: ?>
                                <strong><?= htmlspecialchars($title) ?></strong>
                            <?php endif; ?>
                        </td>

                        <td data-label="Lokasi">
                            <?= htmlspecialchars($loc) ?>
                        </td>

                        <td data-label="Status">
                            <span class="status-pill status-<?= htmlspecialchars($st) ?>">
                                <span class="dot"></span>
                                <?= htmlspecialchars($stRaw) ?>
                            </span>
                        </td>

                        <td data-label="Tanggal">
                            <?= htmlspecialchars($dateText) ?>
                        </td>

                        <td data-label="Aksi" class="row-action" style="text-align:right;">
                            <?php if ($href): ?>
                                <a href="<?= $href ?>">Detail</a>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size:0.9rem;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    <?php else: ?>

        <div class="glass-card empty-state fade">
            <p>Anda belum mengirimkan lamaran pekerjaan.</p>
            <a class="empty-cta" href="<?= base_url('jobs/index') ?>">Mulai Cari Lowongan</a>
        </div>

    <?php endif; ?>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Fade in
    document.querySelectorAll('.fade').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), i * 120);
    });

    // Filter + Search (client-side)
    const chips = Array.from(document.querySelectorAll('.chip'));
    const searchInput = document.getElementById('searchInput');
    const rows = Array.from(document.querySelectorAll('#appsTable tbody tr'));
    const resultCount = document.getElementById('resultCount');

    let activeFilter = 'all';

    function applyFilters() {
        const q = (searchInput?.value || '').trim().toLowerCase();
        let visible = 0;

        rows.forEach(row => {
            const st = (row.dataset.status || '');
            const hay = (row.dataset.search || '');

            const okStatus = (activeFilter === 'all') ? true : (st === activeFilter);
            const okQuery  = q ? hay.includes(q) : true;

            const show = okStatus && okQuery;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (resultCount) {
            resultCount.textContent = `Menampilkan ${visible} dari ${rows.length}`;
        }
    }

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            activeFilter = chip.dataset.filter || 'all';
            applyFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    applyFilters();

    // Row click (if has data-href)
    rows.forEach(row => {
        const href = row.dataset.href;
        if (!href) return;

        row.addEventListener('click', (e) => {
            // jangan ganggu klik link/button
            if (e.target.closest('a, button, input, select, textarea')) return;
            window.location.href = href;
        });
    });
});
</script>

</body>
</html>
