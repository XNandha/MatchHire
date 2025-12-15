<?php
$config  = require __DIR__ . '/../../config/config.php';
$baseUrl = $config['base_url'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $config['app_name'] ?></title>

<style>
/* ================= ROOT ================= */
:root {
    --accent: #1e6de8;
    --danger: #ff3b30;

    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.45);

    --text-main: #1c1c1e;
    --text-muted: #6e6e73;

    --radius: 20px;
}

/* ================= RESET ================= */
* { margin: 0; padding: 0; box-sizing: border-box; }

html {
    scroll-behavior: smooth;
}

body {
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;
    background: linear-gradient(180deg, #eaf1ff, #ffffff);
    color: var(--text-main);
    -webkit-font-smoothing: antialiased;
}

/* ================= GLASS ================= */
.glass {
    background: var(--glass-bg);
    backdrop-filter: blur(24px) saturate(180%);
    -webkit-backdrop-filter: blur(24px) saturate(180%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow:
        0 24px 50px rgba(0,0,0,0.12),
        inset 0 1px 0 rgba(255,255,255,0.6);
}

/* ================= HEADER ================= */
.navbar {
    position: sticky;
    top: 18px;
    z-index: 100;
    margin: 0 auto;
    width: calc(100% - 40px);
    max-width: 1180px;

    padding: 16px 28px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    transition:
        box-shadow 0.4s ease,
        transform 0.4s ease;
}

.navbar:hover {
    box-shadow:
        0 34px 80px rgba(0,0,0,0.16),
        inset 0 1px 0 rgba(255,255,255,0.7);
}

/* ================= BRAND ================= */
.brand img {
    height: 28px;
    transition: transform 0.35s ease, opacity 0.35s ease;
}
.brand:hover img {
    transform: scale(1.06);
    opacity: 0.95;
}

/* ================= NAV MENU ================= */
.nav-menu a {
    position: relative;
    margin-left: 24px;
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    color: var(--text-muted);
    transition: color 0.25s ease;
}

.nav-menu a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -7px;
    width: 100%;
    height: 2px;
    background: var(--accent);
    border-radius: 2px;
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.35s ease;
}

.nav-menu a:hover {
    color: var(--text-main);
}
.nav-menu a:hover::after {
    transform: scaleX(1);
    transform-origin: left;
}

/* ================= BUTTONS ================= */
.btn-primary-small {
    padding: 9px 22px;
    border-radius: 999px;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white !important;
    font-weight: 600;
    text-decoration: none;

    box-shadow: 0 14px 32px rgba(30,109,232,0.35);
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        background 0.25s ease;
}

.btn-primary-small:hover {
    transform: translateY(-3px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.45);
    background: linear-gradient(145deg, #3b8cff, #2563eb);
}

.btn-primary-small:active {
    transform: translateY(-1px) scale(0.98);
}

.btn-logout {
    padding: 9px 22px;
    border-radius: 999px;
    background: linear-gradient(145deg, #ff5a4f, #ff3b30);
    color: white !important;
    font-weight: 600;
    text-decoration: none;

    box-shadow: 0 14px 32px rgba(255,59,48,0.35);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.btn-logout:hover {
    transform: translateY(-3px);
    box-shadow: 0 22px 48px rgba(255,59,48,0.45);
}

/* ================= PAGE ================= */
.page-container {
    max-width: 1180px;
    margin: 100px auto;
    padding: 0 22px;
}

/* ================= FOOTER ================= */
.footer {
    background: linear-gradient(160deg, #1f4c8f, #2f5fa7);
    color: #eaf1ff;
    padding: 100px 20px 60px;
    margin-top: 140px;
}

.footer-inner {
    max-width: 1180px;
    margin: auto;
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1fr;
    gap: 50px;
}

.footer-brand img {
    height: 38px;
    margin-bottom: 18px;
    opacity: 0.95;
}

.footer-brand p {
    font-size: 0.9rem;
    opacity: 0.9;
    line-height: 1.7;
}

.footer-col h4 {
    font-size: 1rem;
    margin-bottom: 16px;
    font-weight: 600;
}

.footer-col a {
    display: inline-block;
    color: #eaf1ff;
    opacity: 0.85;
    font-size: 0.9rem;
    margin-bottom: 10px;
    text-decoration: none;
    transition: opacity 0.25s ease, transform 0.25s ease;
}

.footer-col a:hover {
    opacity: 1;
    transform: translateX(6px);
}

.footer-bottom {
    text-align: center;
    margin-top: 50px;
    font-size: 0.85rem;
    opacity: 0.85;
}

/* ================= FADE ================= */
.fade {
    opacity: 0;
    transform: translateY(26px);
    transition: opacity 0.9s ease, transform 0.9s ease;
}
.fade.show {
    opacity: 1;
    transform: translateY(0);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 900px) {
    .footer-inner {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 600px) {
    .footer-inner {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="navbar glass fade">
    <a href="<?= $baseUrl ?>" class="brand">
        <img src="<?= base_url('assets/images/logomh.png') ?>" alt="MatchHire">
    </a>

    <nav class="nav-menu">
        <?php if (\Auth::check()): ?>
            <?php $u = \Auth::user(); ?>

            <?php if ($u['role'] === 'pelamar'): ?>
                <a href="<?= $baseUrl ?>/jobseeker/dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>/jobs/index">Cari Kerja</a>
                <a href="<?= $baseUrl ?>/jobseeker/applications">Lamaran Saya</a>

            <?php elseif ($u['role'] === 'perusahaan'): ?>
                <a href="<?= $baseUrl ?>/employer/dashboard">Dashboard</a>
                <a href="<?= $baseUrl ?>/employer/jobs">Lowongan Saya</a>
                <a href="<?= $baseUrl ?>/employer/post_job">Pasang Lowongan</a>
            <?php endif; ?>

            <a href="<?= $baseUrl ?>/auth/logout" class="btn-logout">Logout</a>

        <?php else: ?>
            <a href="<?= $baseUrl ?>/auth/login">Masuk</a>
            <a href="<?= $baseUrl ?>/auth/register" class="btn-primary-small">Daftar</a>
        <?php endif; ?>
    </nav>
</header>

<!-- ================= CONTENT ================= -->
<main class="page-container fade">
    <?php $content(); ?>
</main>

<!-- ================= FOOTER ================= -->
<footer class="footer fade">
    <div class="footer-inner">

        <div class="footer-brand">
            <img src="<?= base_url('assets/images/logomhputih.png') ?>" alt="MatchHire">
            <p>
                Platform pencari kerja modern yang menghubungkan pelamar dan perusahaan
                secara cepat, transparan, dan manusiawi.
            </p>
        </div>

        <div class="footer-col">
            <h4>Untuk Jobseeker</h4>
            <a href="#">Cari Lowongan</a>
            <a href="#">Status Lamaran</a>
            <a href="#">Rekomendasi Pekerjaan</a>
        </div>

        <div class="footer-col">
            <h4>Untuk Perusahaan</h4>
            <a href="#">Pasang Lowongan</a>
            <a href="#">Kelola Pelamar</a>
            <a href="#">FAQ Perusahaan</a>
        </div>

        <div class="footer-col">
            <h4>Bantuan</h4>
            <a href="#">Pusat Bantuan</a>
            <a href="#">Syarat & Ketentuan</a>
            <a href="#">Kebijakan Privasi</a>
        </div>

    </div>

    <div class="footer-bottom">
        © <?= date("Y") ?> MatchHire | Dari Para Pekerja, untuk Para Pekerja
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.fade').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), i * 120);
    });
});
</script>

</body>
</html>
