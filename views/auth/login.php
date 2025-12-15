<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Masuk — MatchHire</title>

<style>
/* =====================================================
   ROOT
===================================================== */
:root {
    --accent: #2563eb;
    --accent-dark: #1e4fd7;
    --text-main: #1d1d1f;
    --text-muted: #6e6e73;
    --glass-bg: rgba(255,255,255,0.72);
    --glass-border: rgba(255,255,255,0.45);
}

/* =====================================================
   GLOBAL
===================================================== */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont,
                 "SF Pro Display", "SF Pro Text",
                 "Helvetica Neue", Helvetica, Arial, sans-serif;
    color: var(--text-main);
    background: linear-gradient(
        180deg,
        #eaf1ff 0%,
        #f4f8ff 55%,
        #ffffff 100%
    );
    overflow-x: hidden;
}

/* BACKGROUND CLOUDS */
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
        radial-gradient(35% 30% at 70% 40%, rgba(96,165,250,0.18), transparent 65%);
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

/* =====================================================
   LAYOUT
===================================================== */
.auth-wrapper {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
}

/* =====================================================
   LEFT CONTENT
===================================================== */
.auth-left {
    padding: 90px 80px;
}

.auth-left h1 {
    font-size: 2.6rem;
    letter-spacing: -1.2px;
    margin-bottom: 18px;
}

.auth-left p {
    color: var(--text-muted);
    max-width: 460px;
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 56px;
}

.features {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 32px;
}

.feature {
    display: flex;
    gap: 16px;
}

.feature-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: linear-gradient(145deg, #e6efff, #ffffff);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.feature h4 {
    margin: 0;
    font-size: 1rem;
}

.feature p {
    margin: 4px 0 0;
    font-size: 0.88rem;
    color: var(--text-muted);
}

/* =====================================================
   RIGHT CARD (LIQUID GLASS)
===================================================== */
.auth-right {
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-card {
    width: 390px;
    padding: 42px 38px;

    background: var(--glass-bg);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);

    border: 1px solid var(--glass-border);
    border-radius: 22px;

    box-shadow:
        0 24px 50px rgba(0,0,0,0.12),
        inset 0 1px 0 rgba(255,255,255,0.5);
}

.auth-card h2 {
    margin: 0 0 6px;
    font-size: 1.8rem;
}

.auth-card small {
    color: var(--text-muted);
    font-size: 0.95rem;
}

/* =====================================================
   ALERT
===================================================== */
.alert {
    margin-top: 18px;
    background: rgba(254,226,226,0.8);
    color: #b91c1c;
    padding: 12px 14px;
    border-radius: 12px;
    font-size: 0.85rem;
}

/* =====================================================
   FORM
===================================================== */
label {
    display: block;
    margin-top: 22px;
    font-weight: 600;
    font-size: 0.9rem;
}

input {
    width: 100%;
    padding: 13px 14px;
    margin-top: 6px;
    border-radius: 12px;
    border: 1px solid #d1d5db;
    font-size: 0.95rem;
    background: rgba(255,255,255,0.85);
}

input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}

/* =====================================================
   BUTTON
===================================================== */
button {
    width: 100%;
    margin-top: 30px;
    padding: 15px;
    border-radius: 999px;
    border: none;

    background: linear-gradient(145deg, #2b7cff, #1e6de8);
    color: white;

    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;

    box-shadow: 0 16px 34px rgba(30,109,232,0.3);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        filter 0.25s ease;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 22px 48px rgba(30,109,232,0.4);
    filter: brightness(1.07);
}

button:active {
    transform: translateY(-1px) scale(0.98);
}

/* =====================================================
   FOOTER
===================================================== */
.auth-footer {
    margin-top: 22px;
    text-align: center;
    font-size: 0.9rem;
    color: var(--text-muted);
}

.auth-footer a {
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
}

.auth-footer a:hover {
    text-decoration: underline;
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 900px) {
    .auth-wrapper {
        grid-template-columns: 1fr;
    }
    .auth-left {
        display: none;
    }
}

/* =====================================================
   PASSWORD TOGGLE (LOGIN ONLY)
===================================================== */
.password-wrap {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.85rem;
    cursor: pointer;
    color: var(--text-muted);
    user-select: none;
}

.password-toggle:hover {
    color: var(--accent);
}

</style>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</head>

<body>

<div class="auth-wrapper">

    <!-- LEFT -->
    <div class="auth-left">
        <h1>Masuk ke MatchHire</h1>
        <p>
            Kelola karier profesional Anda melalui platform rekrutmen modern
            yang dirancang untuk efisiensi dan transparansi.
        </p>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">📄</div>
                <div>
                    <h4>Profil Profesional</h4>
                    <p>Kelola data diri dan CV terintegrasi.</p>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">📊</div>
                <div>
                    <h4>Status Lamaran</h4>
                    <p>Pantau proses seleksi secara real-time.</p>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">🔔</div>
                <div>
                    <h4>Notifikasi Lowongan</h4>
                    <p>Update peluang sesuai preferensi Anda.</p>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">🧠</div>
                <div>
                    <h4>Smart Matching</h4>
                    <p>Rekomendasi berbasis kecocokan profil.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-card">
            <h2>Masuk Akun</h2>
            <small>Silakan masuk untuk melanjutkan</small>

            <?php if (!empty($error)): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <div class="password-wrap">
                    <input type="password" name="password" id="password" required>
                    <span class="password-toggle" onclick="togglePassword()">👁</span>
                </div>

                <button>Masuk</button>
            </form>

            <div class="auth-footer">
                Belum punya akun?
                <a href="<?= base_url('auth/register') ?>">Daftar sekarang</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>
