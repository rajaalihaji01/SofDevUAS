<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

$error = "";
$success = "";

if(isset($_POST['ganti_password'])){
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi    = $_POST['konfirmasi_password'];
    $username      = $_SESSION['user'];

    // Ambil data user saat ini
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='".mysqli_real_escape_string($conn, $username)."'");
    $data  = mysqli_fetch_assoc($query);

    if(!$data){
        $error = "Data pengguna tidak ditemukan.";
    } elseif(!password_verify($password_lama, $data['password'])){
        $error = "Password lama yang Anda masukkan salah.";
    } elseif(strlen($password_baru) < 8){
        $error = "Password baru minimal 8 karakter.";
    } elseif($password_baru !== $konfirmasi){
        $error = "Konfirmasi password baru tidak cocok.";
    } elseif(password_verify($password_baru, $data['password'])){
        $error = "Password baru tidak boleh sama dengan password lama.";
    } else {
        $hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE user SET password=? WHERE username=?");
        mysqli_stmt_bind_param($stmt, "ss", $hash, $username);

        if(mysqli_stmt_execute($stmt)){
            $success = "Password berhasil diperbarui. Gunakan password baru Anda saat login berikutnya.";
        } else {
            $error = "Terjadi kesalahan saat menyimpan password baru.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ganti Password - DKPP</title>

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

.pw-wrapper {
    width: 100%;
    max-width: 460px;
}

.pw-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.10);
}

.pw-banner {
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%);
    padding: 34px 30px;
    text-align: center;
    color: white;
}

.pw-banner .icon-lock {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 22px;
}

.pw-banner h4 {
    margin: 0;
    font-weight: 700;
}

.pw-banner p {
    margin: 4px 0 0;
    font-size: 13px;
    opacity: 0.9;
}

.pw-body {
    padding: 30px 30px 25px;
}

.form-group label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 6px;
}

.input-group-text {
    background-color: #f8fafc;
    border-right: none;
    color: #2563eb;
    border-radius: 12px 0 0 12px;
}

.form-control {
    border-radius: 0 12px 12px 0 !important;
    border-left: none;
    height: 48px;
    background-color: #f8fafc;
}

.form-control:focus {
    background-color: #fff;
    box-shadow: none;
    border-color: #3b82f6;
}

.input-group-append .input-group-text {
    border-radius: 0 12px 12px 0;
    border-left: none;
    cursor: pointer;
}

.btn-simpan {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 13px;
    font-weight: 600;
    width: 100%;
    box-shadow: 0 4px 14px rgba(37,99,235,0.35);
    transition: all 0.2s;
}

.btn-simpan:hover {
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37,99,235,0.45);
}

.btn-cancel {
    display: block;
    text-align: center;
    margin-top: 12px;
    font-size: 13px;
    color: #64748b;
    text-decoration: none;
}

.btn-cancel:hover {
    color: #2563eb;
    text-decoration: none;
}

.alert {
    border-radius: 10px;
    font-size: 13px;
}

.pw-hint {
    font-size: 12px;
    color: #94a3b8;
    margin: -10px 0 20px;
}
</style>
</head>

<body>

<div class="pw-wrapper">
<div class="pw-card">

    <div class="pw-banner">
        <div class="icon-lock"><i class="fas fa-lock"></i></div>
        <h4>Ganti Password</h4>
        <p>Perbarui kata sandi akun Anda</p>
    </div>

    <div class="pw-body">

        <?php if($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-1"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i> <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" id="formGanti">

            <div class="form-group">
                <label>Password Lama</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="password_lama" class="form-control pw-toggle" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-btn"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" name="password_baru" id="password_baru" class="form-control pw-toggle" minlength="8" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-btn"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
            </div>
            <p class="pw-hint">Minimal 8 karakter.</p>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control pw-toggle" minlength="8" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-btn"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
            </div>

            <button type="submit" name="ganti_password" class="btn-simpan">
                <i class="fas fa-save mr-1"></i> Simpan Password Baru
            </button>

            <a href="profil.php" class="btn-cancel"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Profil</a>

        </form>

    </div>
</div>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.toggle-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        var input = btn.closest('.input-group').querySelector('.pw-toggle');
        var icon = btn.querySelector('i');
        if(input.type === 'password'){
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

document.getElementById('formGanti').addEventListener('submit', function(e){
    var baru = document.getElementById('password_baru').value;
    var konfirmasi = document.getElementById('konfirmasi_password').value;
    if(baru !== konfirmasi){
        e.preventDefault();
        alert('Konfirmasi password baru tidak cocok.');
    }
});
</script>

</body>
</html>