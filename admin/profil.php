<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profil Admin - DKPP</title>

<link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
* { box-sizing: border-box; }

body {
    background: #f0f4f8;
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 15px;
}

.profile-wrapper {
    width: 100%;
    max-width: 520px;
}

/* ── CARD ── */
.profile-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.10);
}

/* ── HEADER BANNER ── */
.profile-banner {
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%);
    padding: 40px 30px 70px;
    position: relative;
    text-align: center;
}

/* Subtle pattern overlay */
.profile-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.06) 0%, transparent 50%),
                      radial-gradient(circle at 80% 70%, rgba(255,255,255,0.04) 0%, transparent 50%);
}

.profile-banner .badge-role {
    position: relative;
    display: inline-block;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: white;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 20px;
}

/* ── AVATAR ── */
.avatar-wrap {
    position: absolute;
    bottom: -45px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}

.avatar-ring {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    overflow: hidden;
    background: #dbeafe;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-ring img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-online {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 16px;
    height: 16px;
    background: #22c55e;
    border: 3px solid white;
    border-radius: 50%;
}

/* ── BODY ── */
.profile-body {
    padding: 60px 30px 30px;
    text-align: center;
}

.profile-name {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
    letter-spacing: -0.3px;
}

.profile-sub {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 28px;
}

/* ── DIVIDER ── */
.divider {
    height: 1px;
    background: #f1f5f9;
    margin: 0 -30px 24px;
}

/* ── INFO ROWS ── */
.info-list {
    list-style: none;
    padding: 0;
    margin: 0 0 28px;
    text-align: left;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.info-icon.blue   { background: #dbeafe; color: #2563eb; }
.info-icon.green  { background: #dcfce7; color: #16a34a; }
.info-icon.orange { background: #fef3c7; color: #d97706; }
.info-icon.purple { background: #ede9fe; color: #7c3aed; }

.info-content {
    flex: 1;
}

.info-label {
    font-size: 11px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
    margin-bottom: 2px;
}

.info-value {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

/* ── STATUS BADGE ── */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #dcfce7;
    color: #15803d;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
}

.status-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    background: #22c55e;
    border-radius: 50%;
}

/* ── ACTIONS ── */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-back {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    text-decoration: none;
    padding: 13px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s;
    box-shadow: 0 4px 14px rgba(37,99,235,0.35);
}

.btn-back:hover {
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37,99,235,0.45);
}

.btn-logout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: white;
    color: #ef4444;
    text-decoration: none;
    padding: 12px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    border: 1.5px solid #fee2e2;
    transition: all 0.2s;
}

.btn-logout:hover {
    background: #fff1f1;
    color: #dc2626;
    text-decoration: none;
    border-color: #fca5a5;
}

/* ── FOOTER ── */
.profile-footer {
    background: #f8fafc;
    padding: 14px 30px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}

.profile-footer span {
    font-size: 12px;
    color: #94a3b8;
}

.profile-footer .dot {
    width: 3px;
    height: 3px;
    background: #cbd5e1;
    border-radius: 50%;
}
</style>
</head>

<body>

<div class="profile-wrapper">
<div class="profile-card">

    <!-- BANNER -->
    <div class="profile-banner">
        <div class="badge-role">
            <i class="fas fa-shield-alt mr-1"></i> Sistem Buku Tamu DKPP
        </div>

        <!-- AVATAR -->
        <div class="avatar-wrap">
            <div class="avatar-ring">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']); ?>&background=2563eb&color=ffffff&bold=true&size=90" alt="Avatar">
            </div>
            <div class="avatar-online" title="Sedang Online"></div>
        </div>
    </div>

    <!-- BODY -->
    <div class="profile-body">

        <h2 class="profile-name"><?= htmlspecialchars($_SESSION['user']); ?></h2>
        <p class="profile-sub">Dinas Ketahanan Pangan &amp; Pertanian</p>

        <div class="divider"></div>

        <ul class="info-list">

            <li class="info-item">
                <div class="info-icon blue">
                    <i class="fas fa-user"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Username</div>
                    <div class="info-value"><?= htmlspecialchars($_SESSION['user']); ?></div>
                </div>
            </li>

            <li class="info-item">
                <div class="info-icon green">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Hak Akses</div>
                    <div class="info-value">
                        <span class="status-badge">Administrator</span>
                    </div>
                </div>
            </li>

            <li class="info-item">
                <div class="info-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Waktu Login</div>
                    <div class="info-value"><?= date('d M Y, H:i') ?> WIB</div>
                </div>
            </li>

            <li class="info-item">
                <div class="info-icon purple">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Hari Ini</div>
                    <div class="info-value"><?= strftime('%A, %d %B %Y') ?? date('l, d F Y') ?></div>
                </div>
            </li>

        </ul>

        <!-- ACTIONS -->
        <div class="action-buttons">
            <a href="ganti_password.php" class="btn-back">
                <i class="fas fa-key"></i> Ganti Password
            </a>
            <a href="dashboard.php" class="btn-back" style="background: linear-gradient(135deg, #64748b, #475569); box-shadow: 0 4px 14px rgba(100,116,139,0.3);">
                <i class="fas fa-th-large"></i> Kembali ke Dashboard
            </a>
            <a href="../auth/logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Keluar dari Sistem
            </a>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="profile-footer">
        <span>DKPP Digital System</span>
        <div class="dot"></div>
        <span>v1.0</span>
        <div class="dot"></div>
        <span>&copy; <?= date('Y') ?></span>
    </div>

</div>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>