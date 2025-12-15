<section class="hero-wrap">
    <div class="hero-left">

        <h1 class="fade">
            Temukan Karier Impianmu<br>
            <span>bersama MatchHire</span>
        </h1>

        <p class="hero-desc fade">
            Platform rekrutmen modern yang menghubungkan pelamar dan perusahaan
            secara cepat, transparan, dan profesional — dengan pengalaman pengguna
            berstandar premium.
        </p>

        <form action="<?= base_url('jobs/index') ?>" method="GET" class="hero-search glass fade">
            <div class="field">
                <span class="field-ico" aria-hidden="true">
                    <!-- search icon -->
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M16.2 16.2 21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <input type="text" name="q" placeholder="Posisi atau perusahaan" aria-label="Posisi atau perusahaan">
            </div>

            <div class="field">
                <span class="field-ico" aria-hidden="true">
                    <!-- location icon -->
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M12 22s7-4.6 7-11a7 7 0 1 0-14 0c0 6.4 7 11 7 11Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 13.2a2.2 2.2 0 1 0 0-4.4 2.2 2.2 0 0 0 0 4.4Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </span>
                <input type="text" name="loc" placeholder="Lokasi" aria-label="Lokasi">
            </div>

            <button type="submit" class="btn-search">
                Cari
            </button>
        </form>

        <div class="hero-actions fade">
            <a href="<?= base_url('auth/register') ?>" class="btn btn-primary">
                Daftar Gratis
            </a>
            <a href="<?= base_url('auth/login') ?>" class="btn btn-outline">
                Masuk
            </a>
        </div>

        <div class="hero-footnote fade">
            Untuk para pejuang karir • Sistem kecocokan otomatis • Cepat dan dukungan AI
        </div>
    </div>

    <div class="hero-right fade" aria-hidden="true">
        <div class="hero-blob"></div>
        <div class="hero-circle"></div>
        <div class="hero-ring"></div>
    </div>
</section>

<!-- ================= WHY MATCHHIRE ================= -->
<section class="why-section">
    <div class="why-divider fade"></div>

    <div class="why-header fade">
        <h2>Mengapa Memilih MatchHire</h2>
        <p>
            MatchHire dikembangkan sebagai platform rekrutmen modern
            yang mengutamakan efisiensi, transparansi, serta pengalaman pengguna
            berstandar profesional.
        </p>
    </div>

    <div class="why-grid">

        <div class="why-card glass fade">
            <div class="why-icon">🔍</div>
            <h3>Pencarian Lowongan Terstruktur</h3>
            <p>
                Temukan peluang kerja berdasarkan posisi, lokasi, dan perusahaan
                melalui sistem pencarian yang cepat dan akurat.
            </p>
        </div>

        <div class="why-card glass fade">
            <div class="why-icon">📄</div>
            <h3>Profil & Resume Terintegrasi</h3>
            <p>
                Kelola profil profesional dan dokumen CV Anda dalam satu sistem
                untuk mempercepat proses rekrutmen.
            </p>
        </div>

        <div class="why-card glass fade">
            <div class="why-icon">🤝</div>
            <h3>Status Lamaran Transparan</h3>
            <p>
                Pantau perkembangan lamaran secara real-time,
                dari tahap awal hingga keputusan akhir perusahaan.
            </p>
        </div>

        <div class="why-card glass fade">
            <div class="why-icon">🧠</div>
            <h3>Rekomendasi Berbasis Kecocokan</h3>
            <p>
                Sistem job matching merekomendasikan lowongan relevan
                berdasarkan profil dan preferensi Anda.
            </p>
        </div>

    </div>
</section>

<style>
/* =====================================================
   ROOT (AMAN: jika sudah ada, ini tetap kompatibel)
===================================================== */
:root{
    --text-main:#1d1d1f;
    --text-muted:#6e6e73;
    --accent:#2563eb;
    --accent-2:#60a5fa;

    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.45);
}

/* =====================================================
   GLOBAL BACKGROUND (ANIMATED CLOUD – FIXED)
===================================================== */
body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;

    background: linear-gradient(
        180deg,
        #eaf1ff 0%,
        #f4f8ff 55%,
        #ffffff 100%
    );

    color: var(--text-main);
    overflow-x: hidden;
}

/* CLOUD LAYERS (NON INTERACTIVE) */
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

/* =====================================================
   LIQUID GLASS (APPLE-LIKE)
===================================================== */
.glass{
    position: relative;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);

    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);

    box-shadow:
        0 18px 45px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.55);

    overflow: hidden;
}

/* specular highlight */
.glass::before{
    content:"";
    position:absolute;
    inset:-2px;
    pointer-events:none;

    background:
        radial-gradient(60% 40% at 20% 10%, rgba(255,255,255,0.75), transparent 55%),
        radial-gradient(45% 35% at 80% 0%, rgba(255,255,255,0.35), transparent 60%);

    opacity: .55;
}

/* subtle grain (data-uri noise) */
.glass::after{
    content:"";
    position:absolute;
    inset:0;
    pointer-events:none;
    opacity: .12;
    mix-blend-mode: overlay;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='140' height='140' filter='url(%23n)' opacity='.45'/%3E%3C/svg%3E");
}

.glass > *{
    position: relative; /* keep content above overlays */
    z-index: 1;
}

/* focus ring premium */
:focus-visible{
    outline: none;
    box-shadow: 0 0 0 4px rgba(37,99,235,0.18);
    border-radius: 14px;
}

/* =====================================================
   FADE IN (PER BAGIAN)
===================================================== */
.fade{
    opacity: 0;
    transform: translateY(18px) scale(0.985);
    filter: blur(6px);
    transition:
        opacity .85s cubic-bezier(.2,.8,.2,1),
        transform .85s cubic-bezier(.2,.8,.2,1),
        filter .85s cubic-bezier(.2,.8,.2,1);
    transition-delay: var(--d, 0ms);
    will-change: opacity, transform, filter;
}

.fade.show{
    opacity: 1;
    transform: translateY(0) scale(1);
    filter: blur(0);
}

/* =====================================================
   HERO
===================================================== */
.hero-wrap {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 70px;
    align-items: center;
    min-height: 72vh;
}

.hero-left h1 {
    font-size: 3.35rem;
    line-height: 1.12;
    letter-spacing: -1.7px;
    margin: 12px 0 0;
}

.hero-left h1 span {
    color: var(--accent);
}

.hero-eyebrow{
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 10px;
}

.pill{
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: .82rem;
    font-weight: 600;
    color: rgba(29,29,31,.85);
    background: rgba(255,255,255,.75);
    border: 1px solid rgba(0,0,0,.08);
    backdrop-filter: blur(12px);
}

.pill-soft{
    color: rgba(29,29,31,.70);
    background: rgba(255,255,255,.62);
}

.hero-desc {
    margin-top: 18px;
    font-size: 1.15rem;
    color: var(--text-muted);
    max-width: 560px;
    line-height: 1.7;
}

/* SEARCH */
.hero-search {
    margin-top: 34px;
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 12px;
    padding: 14px;
    border-radius: 20px;
}

.field{
    position: relative;
}

.field-ico{
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(29,29,31,.55);
    pointer-events: none;
    display: inline-flex;
}

.hero-search input {
    width: 100%;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.85);

    padding: 14px 14px 14px 42px;
    border-radius: 16px;
    font-size: 0.95rem;
    outline: none;

    transition: box-shadow .25s ease, border-color .25s ease, transform .25s ease;
}

.hero-search input::placeholder{
    color: rgba(29,29,31,.45);
}

.hero-search input:focus{
    border-color: rgba(37,99,235,0.55);
    box-shadow: 0 0 0 4px rgba(37,99,235,0.14);
}

.btn-search{
    padding: 14px 30px;
    border-radius: 16px;
    border: none;
    font-weight: 700;
    cursor: pointer;

    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;

    box-shadow: 0 14px 30px rgba(30,109,232,0.30);

    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 44px rgba(30,109,232,0.38);
    filter: brightness(1.06);
}
.btn-search:active{
    transform: translateY(-1px) scale(.99);
}

/* PREMIUM BUTTONS */
.hero-actions {
    margin-top: 26px;
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}

.btn {
    min-width: 170px;
    height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    font-size: 1rem;
    font-weight: 700;
    text-decoration: none;

    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
    position: relative;
    overflow: hidden;
}

.btn::before{
    content:"";
    position:absolute;
    inset: -40% -20%;
    background: radial-gradient(40% 60% at 20% 20%, rgba(255,255,255,.65), transparent 60%);
    opacity: .0;
    transition: opacity .25s ease;
    pointer-events:none;
}

.btn:hover::before{ opacity: .55; }

.btn-primary {
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: #fff;
    box-shadow: 0 14px 30px rgba(30,109,232,0.28);
}
.btn-primary:hover {
    transform: translateY(-4px);
    box-shadow: 0 22px 45px rgba(30,109,232,0.38);
    filter: brightness(1.03);
}

.btn-outline {
    background: rgba(255,255,255,0.70);
    border: 1px solid rgba(0,0,0,0.12);
    color: var(--text-main);
    backdrop-filter: blur(12px);
}
.btn-outline:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 38px rgba(0,0,0,0.12);
}

.hero-footnote{
    margin-top: 14px;
    font-size: .92rem;
    color: rgba(110,110,115,.92);
}

/* HERO DECOR */
.hero-right { position: relative; height: 420px; }
.hero-blob, .hero-circle, .hero-ring { pointer-events: none; }

.hero-circle {
    width: 320px;
    height: 320px;
    background: radial-gradient(circle at top left, #4a90ff, #1e6de8);
    border-radius: 50%;
    position: absolute;
    right: 30px;
    top: 40px;
    filter: saturate(1.1);
    animation: float1 9s ease-in-out infinite;
}

.hero-blob {
    position: absolute;
    width: 420px;
    height: 420px;
    background: linear-gradient(135deg, #e8f0ff, #f7faff);
    border-radius: 58% 42% 45% 55%;
    top: 0;
    right: 0;
    animation: float2 12s ease-in-out infinite;
}

.hero-ring{
    position:absolute;
    width: 420px;
    height: 420px;
    right: -10px;
    top: -6px;
    border-radius: 50%;
    border: 1px solid rgba(37,99,235,0.14);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.35);
    animation: float3 14s ease-in-out infinite;
}

@keyframes float1{
    0%,100%{ transform: translateY(0); }
    50%{ transform: translateY(-10px); }
}
@keyframes float2{
    0%,100%{ transform: translateY(0) rotate(0deg); }
    50%{ transform: translateY(10px) rotate(2deg); }
}
@keyframes float3{
    0%,100%{ transform: translateY(0) scale(1); }
    50%{ transform: translateY(-8px) scale(1.02); }
}

/* =====================================================
   WHY SECTION
===================================================== */
.why-section {
    margin-top: 140px;
    padding-bottom: 60px;
}

.why-divider {
    width: 140px;
    height: 6px;
    margin: 0 auto 80px;
    border-radius: 999px;
    background: linear-gradient(
        90deg,
        rgba(37,99,235,0.18),
        rgba(96,165,250,0.65),
        rgba(37,99,235,0.18)
    );
    position: relative;
    overflow: hidden;
}

.why-divider::after{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.75), transparent);
    transform: translateX(-120%);
    animation: shimmer 2.6s ease-in-out infinite;
    opacity:.65;
}
@keyframes shimmer{
    0%{ transform: translateX(-120%); }
    60%{ transform: translateX(120%); }
    100%{ transform: translateX(120%); }
}

.why-header {
    text-align: center;
    max-width: 760px;
    margin: 0 auto 80px;
}

.why-header h2 {
    font-size: 2.55rem;
    letter-spacing: -1px;
    margin: 0 0 18px;
}

.why-header p {
    font-size: 1.06rem;
    color: var(--text-muted);
    line-height: 1.75;
    margin: 0;
}

.why-grid {
    max-width: 1100px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 30px;
}

.why-card {
    border-radius: 22px;
    padding: 38px 32px;
    text-align: center;
    transition: transform .35s ease, box-shadow .35s ease, filter .35s ease;
}

.why-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 26px 60px rgba(0,0,0,0.12);
    filter: saturate(1.02);
}

.why-icon {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;

    background: rgba(255,255,255,0.75);
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 16px 34px rgba(0,0,0,0.08);
    font-size: 1.55rem;
}

.why-card h3{
    margin: 0 0 10px;
    letter-spacing: -0.3px;
}

.why-card p{
    margin: 0;
    color: rgba(58,58,60,.92);
    line-height: 1.7;
    font-size: .98rem;
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 900px) {
    .hero-wrap { grid-template-columns: 1fr; }
    .hero-right { display: none; }
    .hero-left h1{ font-size: 2.75rem; }
    .hero-search{ grid-template-columns: 1fr; }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * { animation: none !important; transition: none !important; }
}
</style>

<script>
/**
 * Fade-in per bagian (viewport-aware) + stagger per section.
 * - Elemen .fade akan muncul ketika masuk layar.
 * - Delay di-reset per section supaya tidak “makin lama” di bawah.
 */
document.addEventListener("DOMContentLoaded", () => {
    // set stagger delay per parent section / block
    const blocks = document.querySelectorAll('.hero-wrap, .why-section');
    blocks.forEach(block => {
        let i = 0;
        block.querySelectorAll('.fade').forEach(el => {
            el.style.setProperty('--d', (i * 300) + 'ms');
            i++;
        });
    });

    const els = document.querySelectorAll('.fade');
    const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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
