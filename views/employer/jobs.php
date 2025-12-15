<?php
// views/employer/jobs.php

$jobs = $jobs ?? [];
$totalJobs = is_array($jobs) ? count($jobs) : 0;

$openJobs = 0;
$closedJobs = 0;
$otherJobs = 0;

if (!empty($jobs)) {
    foreach ($jobs as $j) {
        $st = strtolower(trim($j['status'] ?? ''));
        if ($st === 'open') $openJobs++;
        elseif ($st === 'closed') $closedJobs++;
        else $otherJobs++;
    }
}

function statusBadge(?string $status): array {
    $s = strtolower(trim((string)$status));
    if ($s === 'open')   return ['Open', 'pill pill-open'];
    if ($s === 'closed') return ['Closed', 'pill pill-closed'];
    return [($status ?: 'Unknown'), 'pill pill-neutral'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lowongan Anda — MatchHire</title>

<style>
:root {
    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.35);
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;
    --accent: #2563eb;
    --accent-dark: #1e4fd7;

    --danger: #dc2626;
    --danger-soft: rgba(220,38,38,0.10);

    --radius: 20px;
    --shine: rgba(255,255,255,0.55);
}

/* ===== BASE ===== */
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;

    background: linear-gradient(180deg, #eaf1ff, #f4f8ff 55%, #ffffff);
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
        radial-gradient(40% 35% at 20% 30%, rgba(37,99,235,0.18), transparent 60%),
        radial-gradient(35% 30% at 70% 40%, rgba(96,165,250,0.18), transparent 65%),
        radial-gradient(30% 25% at 50% 70%, rgba(191,219,254,0.25), transparent 70%);
    filter: blur(60px);
    animation: cloudMove 90s linear infinite;
}

body::after {
    background:
        radial-gradient(45% 40% at 30% 60%, rgba(30,109,232,0.12), transparent 65%);
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

/* ===== PAGE ===== */
.page-container {
    max-width: 1180px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ===== HEADER ===== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 18px;
    gap: 16px;
    flex-wrap: wrap;
}

.page-title {
    font-size: 2.35rem;
    letter-spacing: -1.2px;
    margin: 0 0 6px;
    line-height: 1.15;
}

.page-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.65;
    max-width: 720px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* ===== BUTTONS ===== */
.btn-primary-small {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 11px 18px;
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
}

.btn-primary-small:hover {
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
    border: none;
    cursor: pointer;

    transition: transform 0.2s ease, background 0.2s ease;
}

.btn-secondary:hover {
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

.btn-danger-soft {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 10px 14px;
    border-radius: 999px;
    background: var(--danger-soft);
    color: var(--danger);
    border: 1px solid rgba(220,38,38,0.25);
    cursor: pointer;
    font-weight: 800;
    font-size: 0.9rem;

    transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
}

.btn-danger-soft:hover {
    background: var(--danger);
    color: #fff;
    transform: translateY(-1px);
}

/* ===== GLASS SURFACE ===== */
.glass-surface {
    background: var(--glass-bg);
    backdrop-filter: blur(22px) saturate(160%);
    -webkit-backdrop-filter: blur(22px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow: 0 18px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    position: relative;
}

.glass-surface::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 50% at 20% 10%, var(--shine), transparent 60%);
    opacity: 0.28;
    pointer-events: none;
}

/* ===== STATS ===== */
.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin: 18px 0 18px;
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

/* ===== TOOLBAR ===== */
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 16px 16px 14px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    background: rgba(255,255,255,0.35);
    position: relative;
    z-index: 1;
    flex-wrap: wrap;
}

.search {
    flex: 1;
    min-width: 220px;
}

.search input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.12);
    background: rgba(255,255,255,0.75);
    font-size: 0.95rem;
    outline: none;
    transition: border 0.2s ease, box-shadow 0.2s ease;
}

.search input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

.filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.chip {
    padding: 9px 12px;
    border-radius: 999px;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.6);
    color: var(--text-main);
    font-size: 0.85rem;
    font-weight: 800;
    cursor: pointer;
    transition: transform 0.2s ease, background 0.2s ease;
}

.chip:hover { transform: translateY(-1px); }

.chip.active {
    background: rgba(37,99,235,0.12);
    border-color: rgba(37,99,235,0.25);
    color: var(--accent);
}

/* ===== TABLE ===== */
.table-wrap {
    position: relative;
    z-index: 1;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 18px 20px;
}

th {
    background: rgba(255,255,255,0.45);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    border-bottom: 1px solid rgba(0,0,0,0.08);
}

td {
    border-bottom: 1px solid rgba(0,0,0,0.06);
    font-size: 0.95rem;
}

tr:hover td {
    background: rgba(255,255,255,0.55);
}

/* ===== STATUS PILL ===== */
.pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 900;
    border: 1px solid rgba(0,0,0,0.08);
    background: rgba(255,255,255,0.6);
    white-space: nowrap;
}

.pill::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: rgba(0,0,0,0.25);
}

.pill-open {
    border-color: rgba(34,197,94,0.25);
    color: #15803d;
    background: rgba(34,197,94,0.12);
}
.pill-open::before { background: #22c55e; }

.pill-closed {
    border-color: rgba(220,38,38,0.25);
    color: #b91c1c;
    background: rgba(220,38,38,0.12);
}
.pill-closed::before { background: #ef4444; }

.pill-neutral {
    border-color: rgba(245,158,11,0.25);
    color: #b45309;
    background: rgba(245,158,11,0.14);
}
.pill-neutral::before { background: #f59e0b; }

/* ===== ACTIONS ===== */
.action-group {
    display: inline-flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 8px 12px;
    border-radius: 999px;
    text-decoration: none;
    font-weight: 800;
    font-size: 0.86rem;

    background: rgba(0,0,0,0.06);
    color: var(--text-main);

    transition: transform 0.2s ease, background 0.2s ease;
    border: none;
    cursor: pointer;
}

.action-link:hover {
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

.action-link.primary {
    background: rgba(37,99,235,0.12);
    color: var(--accent);
    border: 1px solid rgba(37,99,235,0.18);
}

.action-link.danger {
    background: var(--danger-soft);
    color: var(--danger);
    border: 1px solid rgba(220,38,38,0.18);
}

/* ===== RESULT META / EMPTY ===== */
.result-meta {
    margin: 0;
    padding: 14px 16px;
    font-size: 0.9rem;
    color: var(--text-muted);
    border-top: 1px solid rgba(0,0,0,0.06);
    background: rgba(255,255,255,0.35);
}

.empty-state {
    padding: 70px 22px;
    text-align: center;
    color: var(--text-muted);
}

.empty-state strong {
    color: var(--text-main);
}

/* ===== RESPONSIVE TABLE -> CARDS ===== */
@media (max-width: 860px) {
    thead { display: none; }
    table, tbody, tr, td { display: block; width: 100%; }
    tr {
        border-bottom: 1px solid rgba(0,0,0,0.08);
        padding: 14px 14px;
    }
    td {
        border: none;
        padding: 10px 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }
    td::before {
        content: attr(data-label);
        font-weight: 800;
        color: var(--text-muted);
        font-size: 0.82rem;
    }
}

/* ===== MODAL (LIQUID GLASS) ===== */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(6px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 200;
    padding: 18px;
}

.modal.show { display: flex; }

.modal-box {
    background: var(--glass-bg);
    backdrop-filter: blur(24px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: 22px;
    width: 420px;
    max-width: 100%;
    padding: 34px;
    text-align: center;
    box-shadow:
        0 30px 60px rgba(0,0,0,0.25),
        inset 0 1px 0 rgba(255,255,255,0.45);

    transform: scale(0.92);
    opacity: 0;
    transition: all 0.35s ease;
}

.modal.show .modal-box {
    transform: scale(1);
    opacity: 1;
}

.modal-box h3 {
    font-size: 1.35rem;
    margin: 0 0 10px;
    letter-spacing: -0.4px;
}

.modal-box p {
    margin: 0;
    font-size: 0.95rem;
    color: var(--text-muted);
    line-height: 1.6;
}

.modal-actions {
    margin-top: 22px;
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* ===== FADE ===== */
.fade {
    opacity: 0;
    transform: translateY(22px);
    transition: opacity 0.9s ease, transform 0.9s ease;
    will-change: opacity, transform;
}
.fade.show { opacity: 1; transform: translateY(0); }
</style>
</head>

<body>

<div class="page-container">

    <div class="page-header fade">
        <div>
            <h1 class="page-title">Lowongan Anda</h1>
            <p class="page-subtitle">
                Kelola lowongan secara rapi: cari cepat, filter status, dan akses aksi penting tanpa ribet.
            </p>
        </div>

        <div class="header-actions">
            <a href="<?= base_url('employer/dashboard') ?>" class="btn-secondary">
                ← Dashboard
            </a>
            <a href="<?= base_url('employer/post_job') ?>" class="btn-primary-small">
                + Buat Lowongan
            </a>
        </div>
    </div>

    <div class="stats fade">
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

    <div class="glass-surface fade">

        <div class="toolbar">
            <div class="search">
                <input id="searchInput" type="text" placeholder="Cari judul / lokasi…" autocomplete="off">
            </div>

            <div class="filters" aria-label="Filter status">
                <button type="button" class="chip active" data-filter="all">Semua</button>
                <button type="button" class="chip" data-filter="open">Open</button>
                <button type="button" class="chip" data-filter="closed">Closed</button>
            </div>
        </div>

        <div class="table-wrap">
            <?php if (!empty($jobs)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jobsBody">
                    <?php foreach ($jobs as $job): ?>
                        <?php
                            $jobId = (int)($job['job_id'] ?? 0);
                            $title = (string)($job['title'] ?? '-');
                            $loc   = (string)($job['location'] ?? '-');
                            $stRaw = (string)($job['status'] ?? '');
                            $st    = strtolower(trim($stRaw));
                            [$stLabel, $stClass] = statusBadge($stRaw);

                            $hay = strtolower(trim(($title ?: '') . ' ' . ($loc ?: '') . ' ' . ($st ?: '')));
                            $hay = preg_replace('/\s+/', ' ', $hay);
                        ?>
                        <tr data-status="<?= htmlspecialchars($st ?: 'unknown') ?>"
                            data-search="<?= htmlspecialchars($hay) ?>">
                            <td data-label="Judul"><?= htmlspecialchars($title) ?></td>
                            <td data-label="Lokasi"><?= htmlspecialchars($loc) ?></td>
                            <td data-label="Status">
                                <span class="<?= $stClass ?>"><?= htmlspecialchars($stLabel) ?></span>
                            </td>
                            <td data-label="Aksi">
                                <div class="action-group">
                                    <a class="action-link primary" href="<?= base_url('jobs/show/' . $jobId) ?>">Detail</a>
                                    <a class="action-link" href="<?= base_url('employer/edit/' . $jobId) ?>">Edit</a>
                                    <button type="button"
                                            class="action-link danger"
                                            onclick='openDelete(<?= $jobId ?>, <?= json_encode($title) ?>)'>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="result-meta" id="resultMeta">
                    Menampilkan <strong id="shownCount"><?= (int)$totalJobs ?></strong> dari <strong><?= (int)$totalJobs ?></strong> lowongan
                </p>

                <div class="empty-state" id="noResult" style="display:none;">
                    <p><strong>Tidak ada hasil.</strong><br>Coba ubah kata kunci atau filter status.</p>
                </div>

            <?php else: ?>
                <div class="empty-state">
                    <p><strong>Anda belum membuat lowongan.</strong></p>
                    <p>Mulai cari kandidat terbaik dengan membuat lowongan pertama Anda.</p>
                    <div style="margin-top:16px;">
                        <a href="<?= base_url('employer/post_job') ?>" class="btn-primary-small">
                            Buat Lowongan Pertama
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ===== MODAL DELETE ===== -->
<div class="modal" id="deleteModal" onclick="closeDelete(event)">
    <div class="modal-box">
        <h3>Hapus Lowongan</h3>

        <p style="margin-top:8px;">
            Lowongan <strong id="deleteTitle">""</strong> akan dihapus secara permanen.<br>
            Tindakan ini tidak dapat dibatalkan.
        </p>

        <form method="POST" id="deleteForm">
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeDelete()">
                    Batal
                </button>
                <button type="submit" class="btn-danger-soft">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openDelete(id, title) {
    document.getElementById('deleteForm').action =
        "<?= base_url('employer/delete/') ?>" + id;

    const t = document.getElementById('deleteTitle');
    t.textContent = `"${title}"`;

    document.getElementById('deleteModal').classList.add('show');
}

function closeDelete(e) {
    if (!e || e.target.classList.contains('modal')) {
        document.getElementById('deleteModal').classList.remove('show');
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // ===== Fade-in (IntersectionObserver) =====
    const fades = Array.from(document.querySelectorAll(".fade"));
    fades.forEach((el, i) => el.style.transitionDelay = (i * 70) + "ms");

    if ("IntersectionObserver" in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add("show");
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });
        fades.forEach(el => io.observe(el));
    } else {
        fades.forEach(el => el.classList.add("show"));
    }

    // ===== Search + Filter (client-side) =====
    const searchInput = document.getElementById("searchInput");
    const chips = Array.from(document.querySelectorAll(".chip"));
    const rows = Array.from(document.querySelectorAll("#jobsBody tr"));
    const noResult = document.getElementById("noResult");
    const shownCount = document.getElementById("shownCount");
    const resultMeta = document.getElementById("resultMeta");

    if (!rows.length) return;

    let activeFilter = "all";

    function applyFilter() {
        const q = (searchInput?.value || "").toLowerCase().trim();
        let shown = 0;

        rows.forEach((tr) => {
            const st = (tr.getAttribute("data-status") || "unknown").toLowerCase();
            const hay = (tr.getAttribute("data-search") || "").toLowerCase();

            const okStatus = (activeFilter === "all") ? true : (st === activeFilter);
            const okQuery  = q ? hay.includes(q) : true;

            const visible = okStatus && okQuery;
            tr.style.display = visible ? "" : "none";
            if (visible) shown++;
        });

        if (shownCount) shownCount.textContent = String(shown);

        if (noResult) noResult.style.display = (shown === 0) ? "block" : "none";
        if (resultMeta) resultMeta.style.display = (shown === 0) ? "none" : "block";
    }

    chips.forEach((chip) => {
        chip.addEventListener("click", () => {
            chips.forEach(c => c.classList.remove("active"));
            chip.classList.add("active");
            activeFilter = chip.getAttribute("data-filter") || "all";
            applyFilter();
        });
    });

    if (searchInput) {
        searchInput.addEventListener("input", applyFilter);
    }

    applyFilter();
});
</script>

</body>
</html>
