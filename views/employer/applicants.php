<?php
// views/employer/applicants.php
$applications = $applications ?? [];

function statusMeta(string $status): array {
    $s = strtolower(trim($status));
    if ($s === 'terkirim') return ['Terkirim', 'st-terkirim'];
    if ($s === 'direview') return ['Direview', 'st-direview'];
    if ($s === 'ditolak')  return ['Ditolak', 'st-ditolak'];
    if ($s === 'diterima') return ['Diterima', 'st-diterima'];
    return [$status ?: 'Unknown', 'st-neutral'];
}

$totalApps = is_array($applications) ? count($applications) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Pelamar — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.38);

    --text-main: #1d1d1f;
    --text-muted: #6e6e73;

    --accent: #2563eb;
    --accent-dark: #1e4fd7;

    --success: #22c55e;
    --warning: #f59e0b;
    --danger: #ef4444;

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
    background: linear-gradient(180deg, #eef4ff, #ffffff);
    color: var(--text-main);

    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ================= PAGE ================= */
.page-container {
    max-width: 1180px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= TOPBAR ================= */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.page-title {
    font-size: 2.35rem;
    letter-spacing: -1.1px;
    margin: 0 0 6px;
    line-height: 1.15;
}

.page-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.65;
}

.top-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

/* ================= BUTTONS ================= */
.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 16px;
    border-radius: 999px;
    background: rgba(0,0,0,0.06);
    color: var(--text-main);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 700;
    transition: transform 0.2s ease, background 0.2s ease;
    border: none;
    cursor: pointer;
}
.btn-secondary:hover {
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

.btn-primary-small {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 9px 14px;
    border-radius: 999px;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 0.78rem;
    font-weight: 800;
    box-shadow: 0 14px 30px rgba(30,109,232,0.18);
    transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
}
.btn-primary-small:hover {
    transform: translateY(-1px);
    filter: brightness(1.05);
    box-shadow: 0 18px 40px rgba(30,109,232,0.24);
}

/* ================= GLASS CARD ================= */
.glass {
    background: var(--glass-bg);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow:
        0 18px 40px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.45);
    position: relative;
    overflow: hidden;
}
.glass::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 50% at 20% 10%, var(--shine), transparent 60%);
    opacity: 0.28;
    pointer-events: none;
}

/* ================= TOOLBAR (SEARCH/FILTER) ================= */
.toolbar {
    padding: 16px;
    margin-bottom: 16px;
}
.toolbar-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.7fr auto;
    gap: 12px;
    align-items: center;
}
@media (max-width: 860px) {
    .toolbar-grid { grid-template-columns: 1fr; }
}

.search {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.72);
    border: 1px solid rgba(0,0,0,0.12);
    border-radius: 14px;
    padding: 10px 12px;
}
.search input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 0.95rem;
}
.search .icon {
    color: var(--text-muted);
    font-size: 0.95rem;
}

select.filter {
    padding: 11px 12px;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.14);
    background: rgba(255,255,255,0.78);
    font-size: 0.92rem;
    outline: none;
}
select.filter:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

/* ================= TABLE ================= */
.table-wrap {
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 18px 18px;
    text-align: left;
    vertical-align: middle;
}

th {
    background: rgba(255,255,255,0.45);
    font-size: 0.75rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--text-muted);
    border-bottom: 1px solid rgba(0,0,0,0.06);
}

tr {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: background 0.25s ease, transform 0.25s ease;
}

tr:hover td {
    background: rgba(255,255,255,0.62);
}

/* ================= APPLICANT CELL ================= */
.person {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.avatar {
    width: 38px;
    height: 38px;
    border-radius: 14px;
    background: linear-gradient(145deg, rgba(37,99,235,0.14), rgba(96,165,250,0.10));
    border: 1px solid rgba(37,99,235,0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    color: var(--accent);
    flex-shrink: 0;
}

.person-info {
    min-width: 0;
}
.person-name {
    font-weight: 900;
    letter-spacing: -0.2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.person-email {
    margin-top: 4px;
    font-size: 0.85rem;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ================= STATUS BADGE ================= */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 900;
    border: 1px solid rgba(0,0,0,0.08);
    background: rgba(255,255,255,0.6);
}

.st-terkirim {
    background: rgba(37,99,235,0.10);
    border-color: rgba(37,99,235,0.18);
    color: var(--accent);
}
.st-direview {
    background: rgba(245,158,11,0.14);
    border-color: rgba(245,158,11,0.20);
    color: #b45309;
}
.st-ditolak {
    background: rgba(239,68,68,0.14);
    border-color: rgba(239,68,68,0.22);
    color: #b91c1c;
}
.st-diterima {
    background: rgba(34,197,94,0.12);
    border-color: rgba(34,197,94,0.20);
    color: #15803d;
}
.st-neutral {
    background: rgba(0,0,0,0.06);
    border-color: rgba(0,0,0,0.10);
    color: var(--text-muted);
}

/* ================= ACTIONS ================= */
.action-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.inline-form {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.inline-form select {
    padding: 9px 12px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.14);
    background: rgba(255,255,255,0.78);
    font-size: 0.85rem;
    outline: none;
}
.inline-form select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

/* ================= EMPTY ================= */
.empty-state {
    padding: 60px 24px;
    text-align: center;
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.7;
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

/* ================= RESPONSIVE (MOBILE ROW STYLE) ================= */
@media (max-width: 860px) {
    table thead { display: none; }

    table, tbody, tr, td { display: block; width: 100%; }
    tr {
        border-bottom: none;
        margin: 12px 12px 14px;
        border-radius: 18px;
        overflow: hidden;
        background: rgba(255,255,255,0.62);
        border: 1px solid rgba(0,0,0,0.06);
    }
    td { padding: 14px 14px; }
    td[data-label]::before {
        content: attr(data-label);
        display: block;
        font-size: 0.75rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 6px;
        font-weight: 900;
    }
    .action-group {
        justify-content: flex-start;
    }
}
</style>
</head>

<body>

<div class="page-container">

    <div class="topbar fade">
        <div>
            <h1 class="page-title">Daftar Pelamar</h1>
            <p class="page-subtitle">
                Total pelamar: <b><?= (int)$totalApps ?></b> — gunakan pencarian & filter untuk mempercepat review kandidat.
            </p>
        </div>

        <div class="top-actions">
            <a href="<?= base_url('employer/jobs') ?>" class="btn-secondary">← Kembali</a>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="glass toolbar fade">
        <div class="toolbar-grid">
            <div class="search" role="search">
                <span class="icon">⌕</span>
                <input id="searchInput" type="text" placeholder="Cari nama atau email…">
            </div>

            <select id="statusFilter" class="filter" aria-label="Filter status">
                <option value="">Semua Status</option>
                <option value="terkirim">Terkirim</option>
                <option value="direview">Direview</option>
                <option value="ditolak">Ditolak</option>
                <option value="diterima">Diterima</option>
            </select>

            <button class="btn-secondary" type="button" id="clearBtn">Reset</button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="glass table-wrap fade">

        <?php if (!empty($applications)): ?>
        <table>
            <thead>
                <tr>
                    <th>Pelamar</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>

            <tbody id="appsBody">
            <?php foreach ($applications as $app): ?>
                <?php
                    $name = $app['full_name'] ?? '-';
                    $email = $app['email'] ?? '-';
                    [$stLabel, $stClass] = statusMeta($app['status'] ?? '');
                    $initial = strtoupper(mb_substr(trim($name ?: 'A'), 0, 1));
                ?>
                <tr class="app-row"
                    data-name="<?= htmlspecialchars(strtolower($name)) ?>"
                    data-email="<?= htmlspecialchars(strtolower($email)) ?>"
                    data-status="<?= htmlspecialchars(strtolower($app['status'] ?? '')) ?>">

                    <td data-label="Pelamar">
                        <div class="person">
                            <div class="avatar"><?= htmlspecialchars($initial) ?></div>
                            <div class="person-info">
                                <div class="person-name"><?= htmlspecialchars($name) ?></div>
                                <div class="person-email"><?= htmlspecialchars($email) ?></div>
                            </div>
                        </div>
                    </td>

                    <td data-label="Status">
                        <span class="badge <?= $stClass ?>"><?= htmlspecialchars($stLabel) ?></span>
                    </td>

                    <td data-label="Aksi" style="text-align:right;">
                        <div class="action-group">

                            <a href="<?= base_url('employer/applicant_detail/' . $app['application_id']) ?>"
                               class="btn-secondary">
                               Detail
                            </a>

                            <form class="inline-form"
                                  method="POST"
                                  action="<?= base_url('employer/update_application_status/' . $app['application_id']) ?>">
                                <select name="status" aria-label="Ubah status">
                                    <option value="Terkirim" <?= ($app['status']==='Terkirim')?'selected':''; ?>>Terkirim</option>
                                    <option value="Direview" <?= ($app['status']==='Direview')?'selected':''; ?>>Direview</option>
                                    <option value="Ditolak"  <?= ($app['status']==='Ditolak')?'selected':''; ?>>Ditolak</option>
                                    <option value="Diterima" <?= ($app['status']==='Diterima')?'selected':''; ?>>Diterima</option>
                                </select>
                                <button class="btn-primary-small">Update</button>
                            </form>

                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php else: ?>
            <div class="empty-state">
                <b>Belum ada pelamar</b><br>
                Kandidat akan muncul di sini setelah ada yang melamar lowongan Anda.
            </div>
        <?php endif; ?>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Fade-in (IntersectionObserver)
    const els = Array.from(document.querySelectorAll(".fade"));
    els.forEach((el, i) => el.style.transitionDelay = (i * 70) + "ms");

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

    // Client-side search + filter
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const clearBtn = document.getElementById("clearBtn");
    const rows = Array.from(document.querySelectorAll(".app-row"));

    function applyFilter(){
        const q = (searchInput.value || "").trim().toLowerCase();
        const st = (statusFilter.value || "").trim().toLowerCase();

        rows.forEach(r => {
            const name = r.getAttribute("data-name") || "";
            const email = r.getAttribute("data-email") || "";
            const status = r.getAttribute("data-status") || "";

            const matchText = !q || name.includes(q) || email.includes(q);
            const matchStatus = !st || status === st;

            r.style.display = (matchText && matchStatus) ? "" : "none";
        });
    }

    searchInput && searchInput.addEventListener("input", applyFilter);
    statusFilter && statusFilter.addEventListener("change", applyFilter);

    clearBtn && clearBtn.addEventListener("click", () => {
        if (searchInput) searchInput.value = "";
        if (statusFilter) statusFilter.value = "";
        applyFilter();
        searchInput && searchInput.focus();
    });

    // Optional: small UX on submit (prevent double click feeling)
    document.querySelectorAll("form.inline-form").forEach((f) => {
        f.addEventListener("submit", () => {
            const btn = f.querySelector("button");
            if (btn) {
                btn.disabled = true;
                btn.textContent = "Saving…";
            }
        });
    });
});
</script>

</body>
</html>
