<?php
// views/employer/edit_profile.php
$profile = $profile ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profil Perusahaan — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.35);

    --text-main: #1d1d1f;
    --text-muted: #6e6e73;

    --accent: #2563eb;
    --accent-dark: #1e4fd7;

    --radius: 22px;
    --shine: rgba(255,255,255,0.55);
}

/* ================= BASE ================= */
* { box-sizing: border-box; }

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

    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* BACKGROUND CLOUDS */
body::before,
body::after {
    content: "";
    position: fixed;
    inset: -40% -40%;
    pointer-events: none;
    z-index: -2;
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

/* ================= CONTAINER ================= */
.page-container {
    max-width: 1180px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= HEADER ================= */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.page-title {
    font-size: 2.35rem;
    letter-spacing: -1.15px;
    margin: 0 0 6px;
    line-height: 1.12;
}

.page-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.65;
    max-width: 760px;
}

.top-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

/* ================= BUTTONS ================= */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 12px 18px;
    border-radius: 999px;
    border: none;

    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;

    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 800;
    cursor: pointer;

    box-shadow: 0 16px 34px rgba(30,109,232,0.22);
    transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.28);
    filter: brightness(1.05);
}

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

    border: none;
    cursor: pointer;

    transition: transform 0.2s ease, background 0.2s ease;
}

.btn-secondary:hover {
    background: rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

.btn-primary:disabled {
    opacity: 0.75;
    cursor: not-allowed;
}

/* ================= LAYOUT ================= */
.layout {
    display: grid;
    grid-template-columns: 1.55fr 0.95fr;
    gap: 18px;
    align-items: start;
}

@media (max-width: 980px) {
    .layout { grid-template-columns: 1fr; }
}

/* ================= GLASS CARD ================= */
.glass-card {
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

.glass-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 50% at 20% 10%, var(--shine), transparent 60%);
    opacity: 0.30;
    pointer-events: none;
}

.form-card { padding: 28px 28px; }
.preview-card { padding: 22px 22px; }

/* ================= SECTIONS ================= */
.section {
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-title {
    margin: 0 0 6px;
    font-size: 1.15rem;
    letter-spacing: -0.4px;
}

.section-desc {
    margin: 0 0 14px;
    color: var(--text-muted);
    font-size: 0.92rem;
    line-height: 1.6;
}

/* ================= FORM ================= */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-card { padding: 22px 18px; }
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

label {
    font-size: 0.85rem;
    font-weight: 800;
    color: rgba(29,29,31,0.92);
}

.hint {
    font-size: 0.78rem;
    color: var(--text-muted);
    font-weight: 700;
}

input, textarea {
    width: 100%;
    padding: 13px 14px;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.14);
    font-size: 0.95rem;
    background: rgba(255,255,255,0.78);
    transition: border 0.25s ease, box-shadow 0.25s ease;
    outline: none;
}

textarea { resize: vertical; min-height: 110px; }

input::placeholder, textarea::placeholder { color: #9ca3af; }

input:focus, textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

.counter {
    margin-top: 6px;
    font-size: 0.78rem;
    color: var(--text-muted);
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

/* ================= BOTTOM ACTIONS ================= */
.form-actions {
    margin-top: 18px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

/* ================= PREVIEW ================= */
.preview-title {
    margin: 0 0 10px;
    font-size: 1.1rem;
    letter-spacing: -0.3px;
}

.preview-sub {
    margin: 0 0 14px;
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.55;
}

.preview-box {
    background: rgba(255,255,255,0.66);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 18px;
    padding: 16px 16px;
}

.pv-name {
    margin: 0 0 6px;
    font-size: 1.05rem;
    font-weight: 900;
    letter-spacing: -0.2px;
}

.pv-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 10px;
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-bottom: 12px;
}

.pill {
    display: inline-flex;
    align-items: center;
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

.pv-desc {
    margin: 0;
    font-size: 0.92rem;
    line-height: 1.6;
    color: #2c2c2e;
}

.pv-address {
    margin-top: 12px;
    color: var(--text-muted);
    font-size: 0.88rem;
    line-height: 1.55;
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
</style>
</head>

<body>

<div class="page-container">

    <div class="topbar fade">
        <div>
            <h1 class="page-title">Edit Profil Perusahaan</h1>
            <p class="page-subtitle">
                Lengkapi profil agar terlihat profesional di mata pelamar. Informasi yang jelas meningkatkan kepercayaan dan kualitas kandidat.
            </p>
        </div>

        <div class="top-actions">
            <a href="<?= base_url('employer/dashboard') ?>" class="btn-secondary">← Kembali</a>
            <button type="submit" form="companyProfileForm" class="btn-primary" id="topSaveBtn">
                Simpan
            </button>
        </div>
    </div>

    <div class="layout">

        <!-- FORM -->
        <form method="POST" id="companyProfileForm" class="glass-card form-card fade">

            <div class="section">
                <h2 class="section-title">Identitas Perusahaan</h2>
                <p class="section-desc">Nama dan industri biasanya muncul di halaman lowongan dan detail perusahaan.</p>

                <div class="form-grid">
                    <div class="field">
                        <div class="label-row">
                            <label for="company_name">Nama Perusahaan</label>
                            <span class="hint">Disarankan</span>
                        </div>
                        <input id="company_name" type="text" name="company_name"
                               value="<?= htmlspecialchars($profile['company_name'] ?? '') ?>"
                               placeholder="Contoh: PT MatchHire Indonesia">
                        <div class="counter">
                            <span>Gunakan nama resmi/branding utama.</span>
                            <span id="nameCount">0</span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="label-row">
                            <label for="industry">Industri</label>
                            <span class="hint">Disarankan</span>
                        </div>
                        <input id="industry" type="text" name="industry"
                               value="<?= htmlspecialchars($profile['industry'] ?? '') ?>"
                               placeholder="Software, Finance, Retail, dll">
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Kontak & Web</h2>
                <p class="section-desc">Website memudahkan pelamar melakukan riset tentang perusahaan Anda.</p>

                <div class="form-grid">
                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label-row">
                            <label for="website">Website</label>
                            <span class="hint">Opsional</span>
                        </div>
                        <input id="website" type="text" name="website"
                               value="<?= htmlspecialchars($profile['website'] ?? '') ?>"
                               placeholder="https://perusahaananda.com">
                    </div>

                    <div class="field" style="grid-column: 1 / -1;">
                        <div class="label-row">
                            <label for="company_address">Alamat Perusahaan</label>
                            <span class="hint">Opsional</span>
                        </div>
                        <textarea id="company_address" name="company_address" rows="3"
                                  placeholder="Alamat lengkap perusahaan..."><?= htmlspecialchars($profile['company_address'] ?? '') ?></textarea>
                        <div class="counter">
                            <span>Alamat membantu transparansi lokasi.</span>
                            <span id="addrCount">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Deskripsi Singkat</h2>
                <p class="section-desc">Ringkas, jelas, dan menggambarkan budaya atau visi perusahaan.</p>

                <div class="field">
                    <div class="label-row">
                        <label for="description">Deskripsi</label>
                        <span class="hint">Opsional</span>
                    </div>
                    <textarea id="description" name="description" rows="5"
                              placeholder="Ceritakan tentang perusahaan Anda, budaya, dan apa yang membuatnya menarik..."><?= htmlspecialchars($profile['description'] ?? '') ?></textarea>
                    <div class="counter">
                        <span>Tips: 2–5 kalimat sudah cukup kuat.</span>
                        <span id="descCount">0</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= base_url('employer/dashboard') ?>" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary" id="bottomSaveBtn">Simpan Perubahan</button>
            </div>

        </form>

        <!-- LIVE PREVIEW -->
        <aside class="glass-card preview-card fade">
            <h3 class="preview-title">Live Preview</h3>
            <p class="preview-sub">Preview ringkas agar Anda tahu bagaimana profil terlihat bagi pelamar.</p>

            <div class="preview-box">
                <p class="pv-name" id="pvName">Nama Perusahaan</p>

                <div class="pv-meta">
                    <span class="pill" id="pvIndustry">Industri</span>
                    <span class="pill" id="pvWebsite">Website</span>
                </div>

                <p class="pv-desc" id="pvDesc">
                    Deskripsi singkat perusahaan akan muncul di sini.
                </p>

                <div class="pv-address" id="pvAddress">
                    Alamat perusahaan akan muncul di sini.
                </div>
            </div>

            <div style="margin-top:14px; color: var(--text-muted); font-size: 0.85rem; line-height:1.6;">
                <b>Checklist cepat:</b><br>
                • Nama & industri jelas<br>
                • Website valid (jika ada)<br>
                • Deskripsi tidak terlalu panjang<br>
                • Alamat ditulis rapi
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

    // ===== Live preview + counters + autosize + dirty state =====
    const form = document.getElementById("companyProfileForm");
    const topSaveBtn = document.getElementById("topSaveBtn");
    const bottomSaveBtn = document.getElementById("bottomSaveBtn");

    const companyName = document.getElementById("company_name");
    const industry = document.getElementById("industry");
    const website = document.getElementById("website");
    const address = document.getElementById("company_address");
    const desc = document.getElementById("description");

    const pvName = document.getElementById("pvName");
    const pvIndustry = document.getElementById("pvIndustry");
    const pvWebsite = document.getElementById("pvWebsite");
    const pvDesc = document.getElementById("pvDesc");
    const pvAddress = document.getElementById("pvAddress");

    const nameCount = document.getElementById("nameCount");
    const addrCount = document.getElementById("addrCount");
    const descCount = document.getElementById("descCount");

    let isDirty = false;
    let submitted = false;

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
        const n = (companyName.value || "").trim();
        const ind = (industry.value || "").trim();
        const w = (website.value || "").trim();
        const a = (address.value || "").trim();
        const d = (desc.value || "").trim();

        pvName.textContent = n || "Nama Perusahaan";
        pvIndustry.textContent = ind || "Industri";
        pvWebsite.textContent = w || "Website";

        pvDesc.textContent = d ? clip(d, 260) : "Deskripsi singkat perusahaan akan muncul di sini.";
        pvAddress.textContent = a ? clip(a, 220) : "Alamat perusahaan akan muncul di sini.";

        nameCount.textContent = String(n.length);
        addrCount.textContent = String(a.length);
        descCount.textContent = String(d.length);

        autosize(address);
        autosize(desc);
    }

    function markDirty(){
        if (!isDirty) isDirty = true;
    }

    [companyName, industry, website, address, desc].forEach((el) => {
        if (!el) return;
        el.addEventListener("input", () => {
            markDirty();
            update();
        });
    });

    // initial
    update();

    // warn if leave with unsaved changes
    window.addEventListener("beforeunload", (e) => {
        if (isDirty && !submitted) {
            e.preventDefault();
            e.returnValue = "";
        }
    });

    // prevent double submit + better UX
    if (form) {
        form.addEventListener("submit", () => {
            submitted = true;

            if (topSaveBtn) {
                topSaveBtn.disabled = true;
                topSaveBtn.textContent = "Menyimpan…";
            }
            if (bottomSaveBtn) {
                bottomSaveBtn.disabled = true;
                bottomSaveBtn.textContent = "Menyimpan…";
            }
        });
    }
});
</script>

</body>
</html>
