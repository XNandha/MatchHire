<?php
// views/jobseeker/edit_profile.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil — MatchHire</title>

<style>
/* ================= ROOT ================= */
:root {
    --glass-bg: rgba(255,255,255,0.65);
    --glass-border: rgba(255,255,255,0.35);
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;
    --accent: #2563eb;
    --accent-dark: #1e4fd7;
    --radius: 18px;

    --field-bg: rgba(255,255,255,0.82);
    --field-border: rgba(0,0,0,0.14);
}

/* ================= BASE ================= */
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;

    background: linear-gradient(180deg, #eef4ff, #ffffff);
    color: var(--text-main);
    overflow-x: hidden;
}

/* subtle background clouds (konsisten dengan halaman lain) */
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
        radial-gradient(40% 35% at 20% 30%, rgba(37,99,235,0.16), transparent 60%),
        radial-gradient(35% 30% at 70% 40%, rgba(96,165,250,0.16), transparent 65%),
        radial-gradient(30% 25% at 50% 70%, rgba(191,219,254,0.22), transparent 70%);
    filter: blur(60px);
    animation: cloudMove 90s linear infinite;
}
body::after {
    background:
        radial-gradient(45% 40% at 30% 60%, rgba(30,109,232,0.10), transparent 65%);
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
.profile-container {
    max-width: 960px;
    margin: 70px auto 120px;
    padding: 0 22px;
}

/* ================= HEADER ================= */
.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 26px;
}

.page-title {
    font-size: 2.35rem;
    letter-spacing: -1px;
    margin: 0 0 10px;
    line-height: 1.15;
}

.page-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.6;
    max-width: 640px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

/* ================= GLASS CARD ================= */
.form-card {
    background: var(--glass-bg);
    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 26px 26px;
    box-shadow:
        0 12px 34px rgba(0,0,0,0.06),
        inset 0 1px 0 rgba(255,255,255,0.4);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    position: relative;
    overflow: hidden;
}

.form-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(60% 50% at 20% 10%, rgba(255,255,255,0.55), transparent 60%);
    opacity: 0.35;
    pointer-events: none;
}

.form-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 18px 45px rgba(0,0,0,0.08),
        inset 0 1px 0 rgba(255,255,255,0.45);
}

/* ================= SECTION ================= */
.section {
    padding: 18px 14px 8px;
    border-radius: 16px;
}

.section + .section {
    margin-top: 14px;
    border-top: 1px solid rgba(0,0,0,0.07);
    padding-top: 22px;
}

.section-title {
    margin: 0 0 6px;
    font-size: 1.15rem;
    letter-spacing: -0.3px;
}

.section-desc {
    margin: 0 0 18px;
    color: var(--text-muted);
    font-size: 0.92rem;
    line-height: 1.6;
}

/* ================= FORM GRID ================= */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px 18px;
}

.form-full {
    grid-column: 1 / -1;
}

/* ================= FIELD ================= */
.field label {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    margin-bottom: 7px;
    color: #000;
}

.helper {
    margin-top: 8px;
    font-size: 0.82rem;
    color: var(--text-muted);
    line-height: 1.45;
}

input,
textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid var(--field-border);
    font-size: 0.95rem;
    background: var(--field-bg);
    transition: border 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

textarea { resize: vertical; }

input:focus,
textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.16);
    background: rgba(255,255,255,0.92);
}

/* ================= COUNTER ================= */
.counter-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 8px;
    font-size: 0.82rem;
    color: var(--text-muted);
}

.counter-row strong { color: var(--text-main); }

/* ================= FILE ================= */
.file-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    padding: 14px 14px;
    border-radius: 14px;
    border: 1px dashed rgba(0,0,0,0.18);
    background: rgba(255,255,255,0.55);
}

.file-left {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.resume-info {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin: 0;
}

.resume-info a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 800;
}
.resume-info a:hover { text-decoration: underline; }

.file-name {
    font-size: 0.86rem;
    color: var(--text-main);
    font-weight: 700;
    opacity: 0.9;
}

input[type="file"] {
    padding: 0;
    background: transparent;
    border: none;
}

/* ================= BUTTONS ================= */
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

.btn-primary {
    height: 44px;
    padding: 0 14px;
    border-radius: 999px;
    border: none;
    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;
    font-size: 0.92rem;
    font-weight: 800;
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

.btn-primary:active {
    transform: translateY(-1px) scale(0.99);
}

.btn-primary:disabled {
    opacity: 0.75;
    cursor: not-allowed;
    transform: none;
    filter: none;
}

/* sticky action bar (UX: tombol selalu mudah dijangkau) */
.actions-sticky {
    position: sticky;
    bottom: 18px;
    margin-top: 22px;
    padding: 12px 12px;
    border-radius: 16px;
    background: rgba(255,255,255,0.65);
    backdrop-filter: blur(14px) saturate(150%);
    -webkit-backdrop-filter: blur(14px) saturate(150%);
    border: 1px solid rgba(0,0,0,0.08);
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    box-shadow: 0 18px 45px rgba(0,0,0,0.08);
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

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .actions-sticky { justify-content: stretch; }
    .btn-primary, .btn-secondary { width: 100%; }
}
</style>
</head>

<body>

<div class="profile-container">

    <div class="page-header fade">
        <div>
            <h1 class="page-title">Edit Profil Pelamar</h1>
            <p class="page-subtitle">
                Lengkapi profil Anda agar sistem matching lebih akurat dan perusahaan lebih mudah memahami kemampuan Anda.
            </p>
        </div>

        <div class="header-actions">
            <a href="<?= base_url('jobseeker/dashboard') ?>" class="btn-secondary">
                Kembali
            </a>
            <button type="button" class="btn-primary" id="saveTopBtn">
                Simpan
            </button>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data" class="form-card fade" id="profileForm">

        <!-- ================= IDENTITAS ================= -->
        <div class="section">
            <h2 class="section-title">Identitas</h2>
            <p class="section-desc">Informasi dasar untuk identifikasi dan komunikasi.</p>

            <div class="form-grid">
                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="full_name" autocomplete="name"
                           value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>">
                    <div class="helper">Gunakan nama asli agar terlihat profesional.</div>
                </div>

                <div class="field">
                    <label>No. Telepon</label>
                    <input type="tel" name="phone" inputmode="tel" autocomplete="tel"
                           value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
                    <div class="helper">Contoh: 08xxxxxxxxxx</div>
                </div>

                <div class="field form-full">
                    <label>Alamat</label>
                    <textarea name="address" rows="3" autocomplete="street-address"><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
                </div>

                <div class="field">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="birth_date" autocomplete="bday"
                        value="<?=
                            !empty($profile['birth_date'])
                            ? date('Y-m-d', strtotime($profile['birth_date']))
                            : ''
                        ?>">
                </div>

                <div class="field">
                    <label>Industri Saat Ini</label>
                    <input type="text" name="industry" autocomplete="organization"
                           value="<?= htmlspecialchars($profile['industry'] ?? '') ?>">
                    <div class="helper">Misal: Teknologi, Keuangan, Ritel, Pendidikan.</div>
                </div>
            </div>
        </div>

        <!-- ================= PROFESIONAL ================= -->
        <div class="section">
            <h2 class="section-title">Profil Profesional</h2>
            <p class="section-desc">Bantu sistem memahami kemampuan dan preferensi kerja Anda.</p>

            <div class="form-grid">
                <div class="field form-full">
                    <label>Tentang Saya</label>
                    <textarea name="about" rows="4" id="aboutField"><?= htmlspecialchars($profile['about'] ?? '') ?></textarea>
                    <div class="counter-row">
                        <span>Ringkas, fokus ke keahlian & nilai tambah.</span>
                        <span><strong id="aboutCount">0</strong> karakter</span>
                    </div>
                </div>

                <div class="field">
                    <label>Skills (pisahkan dengan koma)</label>
                    <input type="text" name="skills" autocomplete="off"
                           placeholder="contoh: PHP, MySQL, UI/UX"
                           value="<?= htmlspecialchars($profile['skills'] ?? '') ?>">
                    <div class="helper">Semakin spesifik, matching semakin bagus.</div>
                </div>

                <div class="field">
                    <label>Pekerjaan yang Diinginkan</label>
                    <input type="text" name="preferred_job" autocomplete="organization-title"
                           placeholder="contoh: Backend Developer"
                           value="<?= htmlspecialchars($profile['preferred_job'] ?? '') ?>">
                </div>

                <div class="field form-full">
                    <label>Pengalaman Kerja</label>
                    <textarea name="experience" rows="3" id="expField"><?= htmlspecialchars($profile['experience'] ?? '') ?></textarea>
                    <div class="counter-row">
                        <span>Isi ringkas: perusahaan, peran, hasil yang dicapai.</span>
                        <span><strong id="expCount">0</strong> karakter</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= DOKUMEN ================= -->
        <div class="section">
            <h2 class="section-title">Dokumen</h2>
            <p class="section-desc">Unggah CV agar perekrut bisa meninjau profil Anda lebih cepat.</p>

            <div class="form-grid">
                <div class="field form-full">
                    <label>Resume / CV (PDF / DOC)</label>

                    <div class="file-wrap">
                        <div class="file-left">
                            <?php if (!empty($profile['resume_path'])): ?>
                                <p class="resume-info">
                                    Resume saat ini:
                                    <a href="<?= base_url($profile['resume_path']) ?>" target="_blank" rel="noopener noreferrer">
                                        Lihat Resume
                                    </a>
                                </p>
                            <?php else: ?>
                                <p class="resume-info">Belum ada resume yang diunggah.</p>
                            <?php endif; ?>

                            <div class="file-name" id="fileName">Tidak ada file dipilih</div>
                        </div>

                        <div>
                            <input type="file" name="resume" id="resumeFile" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="helper">Disarankan PDF. Maksimalkan peluang interview dengan CV yang rapi.</div>
                </div>
            </div>
        </div>

        <!-- sticky actions -->
        <div class="actions-sticky">
            <a href="<?= base_url('jobseeker/dashboard') ?>" class="btn-secondary">
                Batal
            </a>
            <button class="btn-primary" id="saveBtn">
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Fade in bertahap
    document.querySelectorAll('.fade').forEach((el, i) => {
        setTimeout(() => el.classList.add('show'), i * 120);
    });

    // Counter (About + Experience)
    const aboutField = document.getElementById('aboutField');
    const expField   = document.getElementById('expField');
    const aboutCount = document.getElementById('aboutCount');
    const expCount   = document.getElementById('expCount');

    function updateCounts() {
        if (aboutField && aboutCount) aboutCount.textContent = (aboutField.value || '').length;
        if (expField && expCount) expCount.textContent = (expField.value || '').length;
    }
    updateCounts();
    if (aboutField) aboutField.addEventListener('input', updateCounts);
    if (expField) expField.addEventListener('input', updateCounts);

    // File name preview
    const resumeFile = document.getElementById('resumeFile');
    const fileName   = document.getElementById('fileName');
    if (resumeFile && fileName) {
        resumeFile.addEventListener('change', () => {
            const f = resumeFile.files && resumeFile.files[0];
            fileName.textContent = f ? f.name : 'Tidak ada file dipilih';
        });
    }

    // Top save button triggers form submit (UX)
    const form = document.getElementById('profileForm');
    const saveTopBtn = document.getElementById('saveTopBtn');
    if (saveTopBtn && form) {
        saveTopBtn.addEventListener('click', () => form.requestSubmit());
    }

    // Prevent double submit
    const saveBtn = document.getElementById('saveBtn');
    if (form && saveBtn) {
        form.addEventListener('submit', () => {
            saveBtn.disabled = true;
            saveBtn.textContent = 'Menyimpan...';
            if (saveTopBtn) {
                saveTopBtn.disabled = true;
                saveTopBtn.textContent = 'Menyimpan...';
            }
        });
    }
});
</script>

</body>
</html>
