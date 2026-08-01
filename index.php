<?php 
session_start();
include "config/koneksi.php";

if(isset($_POST['login'])){
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$user'");

    if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_assoc($query);

        // Login menggunakan password biasa (plaintext)
        if($pass == $data['password']){
            $_SESSION['login'] = true;
            $_SESSION['user'] = $data['username'];
            $_SESSION['role'] = $data['role'];

            // Redirect berdasarkan role
            if($data['role'] == 'tamu'){
                header("Location: input_tamu_umum.php");
            } else {
                // admin & petugas tetap ke dashboard
                header("Location: admin/dashboard.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - SI TAMU</title>

<link rel="stylesheet" href="assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="assets/adminlte/dist/css/adminlte.min.css">

<style>
body {
    margin: 0;
    height: 100vh;
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;

    /* Background animasi gradasi biru */
    background: linear-gradient(-45deg, #dbeafe, #eff6ff, #bfdbfe, #e0e7ff);
    background-size: 400% 400%;
    animation: gradientBG 12s ease infinite;
}

@keyframes gradientBG {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.login-container {
    width: 900px;
    height: 520px;
    display: flex;
    border-radius: 20px;
    overflow: hidden;
    background: white;
    box-shadow: 0 25px 50px rgba(0,0,0,0.1);
}

.left {
    width: 45%;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 40px;
    position: relative;

    /* Foto Tugu Kota Dumai sebagai background */
    background-image:
        linear-gradient(135deg, rgba(30,58,138,.85), rgba(59,130,246,.75)),
        url('assets/img/kotadumai.webp');
    background-size: cover;
    background-position: center;
}

.left::before {
    content: "";
    position: absolute;
    top: -50px;
    left: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,.1);
    border-radius: 50%;
}

.left .konten {
    position: relative;
    z-index: 2;
}

.left h2{
    font-weight:700;
    font-size:28px;
    margin-top:20px;
    text-shadow: 0 2px 8px rgba(0,0,0,.35);
}

.left p{
    text-shadow: 0 1px 4px rgba(0,0,0,.35);
}

.logo-kiri{
    width:130px;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,.35));
}

.right{
    width:55%;
    background:white;
    padding:50px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    position: relative;
    overflow: hidden;
}

.right .bubble{
    position:absolute;
    border-radius:50%;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    opacity:.5;
    z-index:0;
    animation: floatBubble 8s ease-in-out infinite;
}

.right .bubble.b1{ width:120px; height:120px; top:-40px; right:-30px; animation-delay:0s; }
.right .bubble.b2{ width:70px;  height:70px;  bottom:20px; right:40px; animation-delay:1.5s; }
.right .bubble.b3{ width:45px;  height:45px;  bottom:80px; left:-15px; animation-delay:.7s; }

@keyframes floatBubble {
    0%, 100% { transform: translateY(0) scale(1); }
    50%      { transform: translateY(-18px) scale(1.06); }
}

.right > *{
    position: relative;
    z-index: 1;
}

.right h5{
    color:#1e3a8a;
    font-weight:700;
}

.input-group-text{
    background:#f8f9fa;
    border-right:none;
    color:#1e3a8a;
    border-radius:12px 0 0 12px;
}

.form-control{
    border-radius:0 12px 12px 0;
    border-left:none;
    height:50px;
    background:#f8f9fa;
}

.form-control:focus{
    background:#fff;
    box-shadow:none;
    border-color:#3b82f6;
}

.input-group-append .input-group-text{
    border-radius:0 12px 12px 0;
    border-left:none;
}

.btn-login{
    background:#1e3a8a;
    color:white;
    border-radius:12px;
    padding:14px;
    border:none;
    font-weight:600;
    transition:.3s;
    margin-top:10px;
}

.btn-login:hover{
    background:#1e40af;
    transform:translateY(-2px);
    box-shadow:0 5px 15px rgba(30,58,138,.3);
}

.footer-text{
    margin-top:30px;
    font-size:12px;
    color:#adb5bd;
    text-align:center;
}
</style>

</head>

<body>

<div class="login-container">

<div class="left">
    <div class="konten text-center">
        <img src="assets/adminlte/logo_1.png" class="logo-kiri">
        <h2 class="mt-3">Buku Tamu Digital</h2>
        <p style="font-size:14px;opacity:.9;">
            Dinas Ketahanan Pangan dan Pertanian
        </p>
    </div>
</div>

<div class="right">

<div class="bubble b1"></div>
<div class="bubble b2"></div>
<div class="bubble b3"></div>

<div class="d-flex align-items-center mb-4">
    <img src="assets/adminlte/logo_1.png" style="width:55px;margin-right:12px;">

    <div>
        <h5 style="margin:0;font-weight:600;color:#007bff;">
            Selamat Datang 👋
        </h5>
        <small style="color:#666;">
            Silakan login ke sistem buku tamu
        </small>
    </div>
</div>

<?php if(isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>

<form method="POST">

<div class="input-group mb-3">
    <div class="input-group-prepend">
        <span class="input-group-text">
            <i class="fas fa-user"></i>
        </span>
    </div>
    <input type="text" name="username" class="form-control" placeholder="Username" required>
</div>

<div class="input-group mb-3">
    <div class="input-group-prepend">
        <span class="input-group-text">
            <i class="fas fa-lock"></i>
        </span>
    </div>

    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>

    <div class="input-group-append">
        <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
            <i class="fas fa-eye" id="eyeIcon"></i>
        </span>
    </div>
</div>

<button type="submit" name="login" class="btn btn-login btn-block">
    <i class="fas fa-sign-in-alt"></i> Login
</button>

<div class="text-center mt-4" style="font-size:13px;color:#888;">
    ©️ 2026 - DKPP
</div>

</form>

</div>

</div>

<script>
function togglePassword(){
    const pass=document.getElementById("password");
    const icon=document.getElementById("eyeIcon");

    if(pass.type==="password"){
        pass.type="text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }else{
        pass.type="password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>