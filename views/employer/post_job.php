<?php
// views/employer/post_job.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Posting Lowongan Baru — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root{
    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.35);
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;

    --accent: #2563eb;
    --accent-dark: #1e4fd7;

    --radius: 22px;
    --shine: rgba(255,255,255,0.55);

    --shadow-soft: 0 18px 55px rgba(0,0,0,0.08);
}

/* ================= BASE ================= */
*{ box-sizing:border-box; }

body{
    margin:0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;

    background: linear-gradient(180deg, #eaf1ff 0%, #f4f8ff 55%, #ffffff 100%);
    color: var(--text-main);
    overflow-x:hidden;

    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* BACKGROUND CLOUDS */
body::before,
body::after{
    content:"";
    position:fixed;
    inset:-40% -40%;
    pointer-events:none;
    z-index:-2;
}

body::before{
    z-index:-1;
    background:
        radial-gradient(40% 35% at 20% 30%, rgba(37,99,235,0.18), transparent 60%),
        radial-gradient(35% 30% at 70% 40%, rgba(96,165,250,0.18), transparent 65%),
        radial-gradient(30% 25% at 50% 70%, rgba(191,219,254,0.25), transparent 70%);
    filter: blur(60px);
    animation: cloudMove 90s linear infinite;
}

body::after{
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

/* ================= CONTAINER ================= */
.page-container{
    max-width: 1180px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= HEADER ================= */
.page-header{
    display:flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.page-title{
    font-size: 2.35rem;
    letter-spacing: -1.15px;
    margin: 0 0 6px;
    line-height: 1.12;
}

.page-subtitle{
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.65;
    max-width: 720px;
}

.header-actions{
    display:flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* ================= BUTTONS ================= */
.btn-primary{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap: 8px;

    padding: 12px 18px;
    border-radius: 999px;
    border: none;

    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: #fff;

    text-decoration:none;
    font-size: 0.92rem;
    font-weight: 800;
    cursor:pointer;

    box-shadow: 0 16px 34px rgba(30,109,232,0.22);
    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
}

.btn-primary:hover{
    transform: translateY(-2px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.28);
    filter: brightness(1.05);
}

.btn-secondary{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap: 8px;

    padding: 11px 16px;
    border-radius: 999px;
    background: rgba(0,0,0,0.06);
    color: var(--text-main);
    text-decoration:none;
    font-size: 0.9rem;
    font-weight: 700;
    border: none;
    cursor:pointer;

    transition: transform .2s ease, background .2s ease;
}
.btn-secondary:hover{
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

/* ================= LAYOUT GRID ================= */
.layout{
    display:grid;
    grid-template-columns: 1.55fr 0.95fr;
    gap: 18px;
    align-items:start;
}

@media (max-width: 980px){
    .layout{ grid-template-columns: 1fr; }
}

/* ================= GLASS CARD ================= */
.glass-card{
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

.glass-card::before{
    content:"";
    position:absolute;
    inset:0;
    background: radial-gradient(60% 50% at 20% 10%, var(--shine), transparent 60%);
    opacity: 0.30;
    pointer-events:none;
}

.form-card{
    padding: 28px 28px;
}

.preview-card{
    padding: 22px 22px;
}

/* ================= SECTIONS ================= */
.section{
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}

.section:last-child{
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 0;
}

.section-title{
    margin: 0 0 6px;
    font-size: 1.15rem;
    letter-spacing: -0.4px;
}

.section-desc{
    margin: 0 0 14px;
    color: var(--text-muted);
    font-size: 0.92rem;
    line-height: 1.6;
}

/* ================= FORM ================= */
.form-grid{
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px 14px;
}

@media (max-width: 768px){
    .form-grid{ grid-template-columns: 1fr; }
    .form-card{ padding: 22px 18px; }
}

.field{
    display:flex;
    flex-direction:column;
    gap: 6px;
}

label{
    font-size: 0.85rem;
    font-weight: 700;
    color: rgba(29,29,31,0.92);
}

.label-row{
    display:flex;
    justify-content: space-between;
    align-items:center;
    gap: 10px;
}

.hint{
    font-size: 0.78rem;
    color: var(--text-muted);
    font-weight: 600;
}

input, textarea{
    width: 100%;
    padding: 13px 14px;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.14);
    font-size: 0.95rem;
    background: rgba(255,255,255,0.78);
    transition: border .25s ease, box-shadow .25s ease, transform .2s ease;
    outline: none;
}

textarea{
    resize: vertical;
    min-height: 120px;
}

input::placeholder,
textarea::placeholder{
    color: #9ca3af;
}

input:focus,
textarea:focus{
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

.counter{
    margin-top: 6px;
    font-size: 0.78rem;
    color: var(--text-muted);
    display:flex;
    justify-content: space-between;
}

/* ================= ACTION BAR ================= */
.actions{
    margin-top: 18px;
    display:flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

/* ================= PREVIEW ================= */
.preview-title{
    margin: 0 0 10px;
    font-size: 1.1rem;
    letter-spacing: -0.3px;
}

.preview-sub{
    margin: 0 0 14px;
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.55;
}

.preview-box{
    background: rgba(255,255,255,0.66);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 18px;
    padding: 16px 16px;
}

.pv-job-title{
    margin: 0 0 6px;
    font-size: 1.05rem;
    font-weight: 900;
    letter-spacing: -0.2px;
}

.pv-meta{
    display:flex;
    flex-wrap: wrap;
    gap: 8px 10px;
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-bottom: 12px;
}

.pill{
    display:inline-flex;
    align-items:center;
    gap: 6px;
    padding: 7px 10px;
    border-radius: 999px;
    background: rgba(37,99,235,0.12);
    border: 1px solid rgba(37,99,235,0.18);
    color: var(--accent);
    font-weight: 800;
    font-size: 0.78rem;
    white-space: nowrap;
}

.pv-desc{
    margin: 0;
    font-size: 0.92rem;
    line-height: 1.6;
    color: #2c2c2e;
}

/* ================= FADE ================= */
.fade{
    opacity: 0;
    transform: translateY(22px);
    transition: opacity .9s ease, transform .9s ease;
    will-change: opacity, transform;
}
.fade.show{ opacity: 1; transform: translateY(0); }
</style>
</head>

<body>

<div class="page-container">

    <div class="page-header fade">
        <div>
            <h1 class="page-title">Posting Lowongan Baru</h1>
            <p class="page-subtitle">
                Buat lowongan yang jelas dan menarik. Semakin rapi struktur deskripsi, semakin tinggi kualitas pelamar yang masuk.
            </p>
        </div>

        <div class="header-actions">
            <a href="<?= base_url('employer/dashboard') ?>" class="btn-secondary">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="layout">

        <!-- FORM -->
        <form method="POST" class="glass-card form-card fade" id="jobForm">

            <!-- SECTION 1 -->
            <div class="section">
                <h2 class="section-title">Informasi Utama</h2>
                <p class="section-desc">Isi informasi inti lowongan agar mudah ditemukan dan dipahami pelamar.</p>

                <div class="field">
                    <div class="label-row">
                        <label for="title">Judul Pekerjaan</label>
                        <span class="hint">Wajib</span>
                    </div>
                    <input id="title" type="text" name="title" required autocomplete="off"
                           placeholder="Contoh: Software Engineer" autofocus>
                    <div class="counter">
                        <span>Gunakan judul yang spesifik (misal: “Backend Engineer (PHP)”).</span>
                        <span id="titleCount">0</span>
                    </div>
                </div>

                <div class="form-grid" style="margin-top:14px;">
                    <div class="field">
                        <label for="location">Lokasi</label>
                        <input id="location" type="text" name="location" autocomplete="off"
                               placeholder="Jakarta / Remote / Hybrid">
                    </div>

                    <div class="field">
                        <label for="industry">Industri</label>
                        <input id="industry" type="text" name="industry" autocomplete="off"
                               placeholder="Software, Finance, Retail">
                    </div>
                </div>
            </div>

            <!-- SECTION 2 -->
            <div class="section">
                <h2 class="section-title">Detail Pekerjaan</h2>
                <p class="section-desc">Tulis ringkas, terstruktur, dan fokus pada tanggung jawab serta benefit.</p>

                <div class="field">
                    <div class="label-row">
                        <label for="description">Deskripsi Pekerjaan</label>
                        <span class="hint">Wajib</span>
                    </div>
                    <textarea id="description" name="description" rows="6" required
                              placeholder="Jelaskan tanggung jawab utama, budaya kerja, benefit, dan ekspektasi..."></textarea>
                    <div class="counter">
                        <span>Tips: gunakan bullet point untuk tugas harian & benefit.</span>
                        <span id="descCount">0</span>
                    </div>
                </div>

                <div class="field" style="margin-top:14px;">
                    <div class="label-row">
                        <label for="requirements">Persyaratan</label>
                        <span class="hint">Opsional</span>
                    </div>
                    <textarea id="requirements" name="requirements" rows="5"
                              placeholder="Contoh: PHP, MySQL, REST API, komunikasi baik, 1+ tahun pengalaman..."></textarea>
                    <div class="counter">
                        <span>Fokuskan pada must-have. Nice-to-have bisa ditulis belakangan.</span>
                        <span id="reqCount">0</span>
                    </div>
                </div>
            </div>

            <!-- SECTION 3 -->
            <div class="section">
                <h2 class="section-title">Info Tambahan</h2>
                <p class="section-desc">Informasi gaji membantu menarik kandidat yang lebih tepat.</p>

                <div class="field">
                    <label for="salary_range">Rentang Gaji</label>
                    <input id="salary_range" type="text" name="salary_range" autocomplete="off"
                           placeholder="Rp 8.000.000 – Rp 12.000.000">
                    <div class="counter" style="justify-content:flex-start;">
                        <span>Jika belum pasti, Anda bisa isi “Negotiable”.</span>
                    </div>
                </div>
            </div>

            <div class="actions">
                <a href="<?= base_url('employer/dashboard') ?>" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-primary" id="submitBtn">
                    Simpan Lowongan
                </button>
            </div>

        </form>

        <!-- LIVE PREVIEW -->
        <aside class="glass-card preview-card fade">
            <h3 class="preview-title">Live Preview</h3>
            <p class="preview-sub">
                Ini hanya preview tampilan ringkas agar Anda bisa mengecek kualitas isi lowongan.
            </p>

            <div class="preview-box">
                <p class="pv-job-title" id="pvTitle">Judul Pekerjaan</p>

                <div class="pv-meta">
                    <span class="pill" id="pvLocation">Lokasi</span>
                    <span class="pill" id="pvSalary">Gaji</span>
                    <span class="pill" id="pvIndustry">Industri</span>
                </div>

                <p class="pv-desc" id="pvDesc">
                    Deskripsi pekerjaan akan muncul di sini. Tulis ringkas dan jelas agar kandidat cepat paham.
                </p>
            </div>

            <div style="margin-top:14px; color: var(--text-muted); font-size: 0.85rem; line-height:1.6;">
                <b>Checklist cepat:</b><br>
                • Judul spesifik<br>
                • Deskripsi terstruktur<br>
                • Persyaratan tidak berlebihan<br>
                • Rentang gaji (jika memungkinkan)
            </div>
        </aside>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // ===== Fade in (IntersectionObserver) =====
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

    // ===== Live preview + counters + autosize =====
    const title = document.getElementById("title");
    const location = document.getElementById("location");
    const salary = document.getElementById("salary_range");
    const industry = document.getElementById("industry");
    const desc = document.getElementById("description");
    const req = document.getElementById("requirements");

    const pvTitle = document.getElementById("pvTitle");
    const pvLocation = document.getElementById("pvLocation");
    const pvSalary = document.getElementById("pvSalary");
    const pvIndustry = document.getElementById("pvIndustry");
    const pvDesc = document.getElementById("pvDesc");

    const titleCount = document.getElementById("titleCount");
    const descCount  = document.getElementById("descCount");
    const reqCount   = document.getElementById("reqCount");

    function autosize(el){
        if (!el) return;
        el.style.height = "auto";
        el.style.height = (el.scrollHeight + 2) + "px";
    }

    function clip(text, n){
        text = (text || "").trim();
        if (!text) return "";
        return text.length > n ? text.slice(0, n) + "…" : text;
    }

    function update(){
        const t = (title.value || "").trim();
        const l = (location.value || "").trim();
        const s = (salary.value || "").trim();
        const ind = (industry.value || "").trim();
        const d = (desc.value || "").trim();

        pvTitle.textContent = t || "Judul Pekerjaan";
        pvLocation.textContent = l || "Lokasi";
        pvSalary.textContent = s || "Gaji";
        pvIndustry.textContent = ind || "Industri";

        pvDesc.textContent = d ? clip(d, 260) : "Deskripsi pekerjaan akan muncul di sini. Tulis ringkas dan jelas agar kandidat cepat paham.";

        titleCount.textContent = String((t || "").length);
        descCount.textContent  = String((d || "").length);
        reqCount.textContent   = String(((req.value || "").trim()).length);

        autosize(desc);
        autosize(req);
    }

    [title, location, salary, industry, desc, req].forEach((el) => {
        if (!el) return;
        el.addEventListener("input", update);
    });

    // initial
    update();

    // Prevent double submit (UX)
    const form = document.getElementById("jobForm");
    const submitBtn = document.getElementById("submitBtn");
    if (form && submitBtn) {
        form.addEventListener("submit", () => {
            submitBtn.disabled = true;
            submitBtn.style.filter = "grayscale(0.1)";
            submitBtn.textContent = "Menyimpan…";
        });
    }
});
</script>

</body>
</html>
