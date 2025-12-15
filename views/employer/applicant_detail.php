<?php
// views/employer/applicant_detail.php

$application = $application ?? [];

function statusMeta(string $status): array {
    $s = strtolower(trim($status));
    if ($s === 'terkirim') return ['Terkirim', 'st-terkirim'];
    if ($s === 'direview') return ['Direview', 'st-direview'];
    if ($s === 'ditolak')  return ['Ditolak', 'st-ditolak'];
    if ($s === 'diterima') return ['Diterima', 'st-diterima'];
    return [$status ?: 'Unknown', 'st-neutral'];
}

$name  = $application['full_name'] ?? '-';
$email = $application['email'] ?? '-';
$initial = strtoupper(mb_substr(trim($name ?: 'A'), 0, 1));

[$stLabel, $stClass] = statusMeta((string)($application['status'] ?? ''));
$resumePath = $application['resume_path'] ?? null;

// Jika Anda punya application_id di data ini, akan dipakai untuk update status (opsional)
$appId = $application['application_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pelamar — MatchHire</title>

<style>
:root{
    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.38);
    --text-main:#1d1d1f;
    --text-muted:#6e6e73;
    --accent:#2563eb;
    --accent-dark:#1e4fd7;

    --success:#22c55e;
    --warning:#f59e0b;
    --danger:#ef4444;

    --radius:22px;
    --shine: rgba(255,255,255,0.55);
}

*{ box-sizing:border-box; }

body{
    margin:0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display","SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;
    background: linear-gradient(180deg,#eef4ff,#ffffff);
    color:var(--text-main);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* PAGE */
.page-container{
    max-width: 980px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:14px;
    flex-wrap:wrap;
    margin-bottom: 18px;
}

.page-title{
    font-size: 2.35rem;
    letter-spacing:-1.1px;
    margin:0 0 6px;
    line-height:1.15;
}
.page-subtitle{
    margin:0;
    color:var(--text-muted);
    font-size:1rem;
    line-height:1.65;
}

.top-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}

/* BUTTONS */
.btn-secondary{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 11px 16px;
    border-radius:999px;
    background: rgba(0,0,0,0.06);
    color:var(--text-main);
    text-decoration:none;
    font-size:0.9rem;
    font-weight:700;
    transition: transform .2s ease, background .2s ease;
    border:none;
    cursor:pointer;
}
.btn-secondary:hover{
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

.btn-primary{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding: 11px 16px;
    border-radius:999px;
    border:none;
    background: linear-gradient(145deg,#2b7cff,#1e6de8);
    color:#fff;
    text-decoration:none;
    font-size:0.9rem;
    font-weight:800;
    cursor:pointer;
    box-shadow: 0 16px 34px rgba(30,109,232,0.18);
    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
}
.btn-primary:hover{
    transform: translateY(-2px);
    filter: brightness(1.05);
    box-shadow: 0 22px 48px rgba(30,109,232,0.24);
}
.btn-primary:disabled{
    opacity:.7;
    cursor:not-allowed;
}

/* GLASS */
.glass{
    background: var(--glass-bg);
    backdrop-filter: blur(22px) saturate(160%);
    -webkit-backdrop-filter: blur(22px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow:
        0 18px 40px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.45);
    position: relative;
    overflow:hidden;
}
.glass::before{
    content:"";
    position:absolute;
    inset:0;
    background: radial-gradient(60% 50% at 20% 10%, var(--shine), transparent 60%);
    opacity:0.26;
    pointer-events:none;
}

/* HEADER CARD */
.header-card{
    padding: 22px 22px;
    margin-bottom: 16px;
}

.header-row{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:16px;
    flex-wrap:wrap;
}

.person{
    display:flex;
    gap:14px;
    align-items:center;
    min-width:0;
}

.avatar{
    width:54px;
    height:54px;
    border-radius:18px;
    background: linear-gradient(145deg, rgba(37,99,235,0.16), rgba(96,165,250,0.10));
    border: 1px solid rgba(37,99,235,0.18);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    font-size:1.15rem;
    color: var(--accent);
    flex-shrink:0;
}

.person-info{ min-width:0; }
.person-name{
    font-size:1.35rem;
    font-weight:900;
    letter-spacing:-0.5px;
    margin:0;
    line-height:1.2;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.person-email{
    margin-top:6px;
    color:var(--text-muted);
    font-size:0.95rem;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding: 7px 12px;
    border-radius:999px;
    font-size:0.78rem;
    font-weight:900;
    border:1px solid rgba(0,0,0,0.08);
    background: rgba(255,255,255,0.6);
}
.st-terkirim{
    background: rgba(37,99,235,0.10);
    border-color: rgba(37,99,235,0.18);
    color: var(--accent);
}
.st-direview{
    background: rgba(245,158,11,0.14);
    border-color: rgba(245,158,11,0.20);
    color: #b45309;
}
.st-ditolak{
    background: rgba(239,68,68,0.14);
    border-color: rgba(239,68,68,0.22);
    color: #b91c1c;
}
.st-diterima{
    background: rgba(34,197,94,0.12);
    border-color: rgba(34,197,94,0.20);
    color: #15803d;
}
.st-neutral{
    background: rgba(0,0,0,0.06);
    border-color: rgba(0,0,0,0.10);
    color: var(--text-muted);
}

.header-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    justify-content:flex-end;
}

/* INFO GRID */
.grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    align-items:start;
}
@media (max-width: 900px){
    .grid{ grid-template-columns:1fr; }
}

.card{
    padding: 22px 22px;
}

.card-title{
    margin: 0 0 12px;
    font-size: 1.08rem;
    letter-spacing:-0.3px;
}

.meta-grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
@media (max-width: 640px){
    .meta-grid{ grid-template-columns:1fr; }
}

.meta-item{
    background: rgba(255,255,255,0.62);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px;
    padding: 14px 14px;
}
.meta-item small{
    display:block;
    font-size:0.72rem;
    color: var(--text-muted);
    margin-bottom:6px;
}
.meta-item strong, .meta-item div{
    font-size:0.92rem;
    line-height:1.55;
}

/* TEXT BLOCK */
.text-block{
    background: rgba(255,255,255,0.62);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px;
    padding: 14px 14px;
    color:#2c2c2e;
    line-height:1.7;
    font-size:0.95rem;
}

/* RESUME */
.resume-row{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}

/* STATUS QUICK UPDATE (optional) */
.inline-form{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}
.inline-form select{
    padding: 10px 12px;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.14);
    background: rgba(255,255,255,0.78);
    font-size: 0.9rem;
    outline:none;
}
.inline-form select:focus{
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

/* FADE */
.fade{
    opacity:0;
    transform: translateY(22px);
    transition: opacity .9s ease, transform .9s ease;
    will-change: opacity, transform;
}
.fade.show{
    opacity:1;
    transform: translateY(0);
}
</style>
</head>

<body>

<div class="page-container">

    <div class="topbar fade">
        <div>
            <h1 class="page-title">Detail Pelamar</h1>
            <p class="page-subtitle">
                Review kandidat secara cepat — aksi penting ada di bagian atas, detail ada di bawah.
            </p>
        </div>

        <div class="top-actions">
            <a class="btn-secondary" href="<?= base_url('employer/jobs') ?>">← Kembali</a>
        </div>
    </div>

    <!-- HEADER (PENTING) -->
    <section class="glass header-card fade">
        <div class="header-row">

            <div class="person">
                <div class="avatar"><?= htmlspecialchars($initial) ?></div>
                <div class="person-info">
                    <p class="person-name"><?= htmlspecialchars($name) ?></p>
                    <div class="person-email"><?= htmlspecialchars($email) ?></div>
                    <div style="margin-top:10px;">
                        <span class="badge <?= $stClass ?>">Status: <?= htmlspecialchars($stLabel) ?></span>
                    </div>
                </div>
            </div>

            <div class="header-actions">
                <?php if (!empty($resumePath)): ?>
                    <a class="btn-primary" target="_blank" href="<?= base_url($resumePath) ?>">
                        ⤓ Lihat Resume
                    </a>
                <?php endif; ?>

                <!-- OPTIONAL: Quick status update di halaman detail -->
                <?php if (!empty($appId)): ?>
                <!-- Jika route ini TIDAK ada untuk halaman detail, hapus blok ini -->
                <form class="inline-form"
                      method="POST"
                      action="<?= base_url('employer/update_application_status/' . $appId) ?>">
                    <select name="status" aria-label="Ubah status">
                        <option value="Terkirim" <?= (($application['status'] ?? '')==='Terkirim')?'selected':''; ?>>Terkirim</option>
                        <option value="Direview" <?= (($application['status'] ?? '')==='Direview')?'selected':''; ?>>Direview</option>
                        <option value="Ditolak"  <?= (($application['status'] ?? '')==='Ditolak')?'selected':''; ?>>Ditolak</option>
                        <option value="Diterima" <?= (($application['status'] ?? '')==='Diterima')?'selected':''; ?>>Diterima</option>
                    </select>
                    <button class="btn-primary" type="submit">Update</button>
                </form>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <div class="grid">

        <!-- INFORMASI LAMARAN -->
        <section class="glass card fade">
            <h2 class="card-title">Informasi Lamaran</h2>

            <div class="meta-grid">
                <div class="meta-item">
                    <small>Posisi Dilamar</small>
                    <strong><?= htmlspecialchars($application['job_title'] ?? '-') ?></strong>
                </div>

                <div class="meta-item">
                    <small>Status Lamaran</small>
                    <strong><?= htmlspecialchars($application['status'] ?? '-') ?></strong>
                </div>
            </div>

            <div style="margin-top:12px;" class="resume-row">
                <a class="btn-secondary" href="<?= base_url('jobs/show/' . ($application['job_id'] ?? '')) ?>">
                    Lihat Lowongan
                </a>
                <a class="btn-secondary" href="<?= base_url('employer/applicants/' . ($application['job_id'] ?? '')) ?>">
                    Kembali ke List Pelamar
                </a>
            </div>
        </section>

        <!-- INFORMASI DASAR -->
        <section class="glass card fade">
            <h2 class="card-title">Informasi Dasar</h2>

            <div class="meta-grid">
                <div class="meta-item">
                    <small>Telepon</small>
                    <strong><?= htmlspecialchars($application['phone'] ?? '-') ?></strong>
                </div>

                <div class="meta-item">
                    <small>Tanggal Lahir</small>
                    <strong><?= htmlspecialchars($application['birth_date'] ?? '-') ?></strong>
                </div>

                <div class="meta-item" style="grid-column: 1 / -1;">
                    <small>Alamat</small>
                    <div><?= nl2br(htmlspecialchars($application['address'] ?? '-')) ?></div>
                </div>
            </div>
        </section>

    </div>

    <!-- TENTANG -->
    <section class="glass card fade" style="margin-top:16px;">
        <h2 class="card-title">Tentang Pelamar</h2>

        <div class="text-block">
            <?= nl2br(htmlspecialchars($application['about'] ?? '-')) ?>
        </div>
    </section>

    <!-- RESUME (KURANG PENTING DI BAWAH) -->
    <?php if (!empty($resumePath)): ?>
    <section class="glass card fade" style="margin-top:16px;">
        <h2 class="card-title">Resume / CV</h2>
        <p style="margin:0 0 12px; color: var(--text-muted); line-height:1.7;">
            Buka resume di tab baru untuk melihat detail pengalaman dan skill kandidat.
        </p>
        <div class="resume-row">
            <a class="btn-primary" target="_blank" href="<?= base_url($resumePath) ?>">⤓ Lihat Resume</a>
            <a class="btn-secondary" href="<?= base_url('employer/jobs') ?>">Selesai Review</a>
        </div>
    </section>
    <?php endif; ?>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
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

    // Small UX: disable update button on submit (optional)
    document.querySelectorAll("form.inline-form").forEach((f) => {
        f.addEventListener("submit", () => {
            const btns = f.querySelectorAll("button");
            btns.forEach(b => {
                b.disabled = true;
                b.textContent = "Saving…";
            });
        });
    });
});
</script>

</body>
</html>
