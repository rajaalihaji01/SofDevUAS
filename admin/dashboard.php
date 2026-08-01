<?php
session_start();
include "../config/koneksi.php";

$halaman = basename($_SERVER['PHP_SELF']);

// ✅ FIX
$master = in_array($halaman, ['pegawai.php']);
$transaksi = in_array($halaman, ['data_tamu.php','input_tamu.php']);
$laporan = in_array($halaman, ['laporan.php']);

// CEK LOGIN
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: ../login.php");
    exit;
}

// CEK USER
if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
    exit;
}


// ======================
// DATA STATISTIK
// ======================
$total_tamu = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tamu"));

$hari_ini = date('Y-m-d');

$tamu_hari_ini = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM tamu 
WHERE DATE(waktu_datang)='$hari_ini'
"));

$tamu_bulan_ini = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM tamu 
WHERE MONTH(waktu_datang)=MONTH(CURDATE()) 
AND YEAR(waktu_datang)=YEAR(CURDATE())
"));

$tamu_tahun_ini = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM tamu 
WHERE YEAR(waktu_datang)=YEAR(CURDATE())
"));

// ======================
// GRAFIK
// ======================
$grafik = mysqli_query($conn,"
SELECT MONTH(waktu_datang) as bulan, COUNT(*) as total
FROM tamu
WHERE YEAR(waktu_datang)=YEAR(CURDATE())
GROUP BY MONTH(waktu_datang)
");

$data_bulan = array_fill(1,12,0);

while($g = mysqli_fetch_assoc($grafik)){
    $data_bulan[$g['bulan']] = $g['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard - BUKU TAMU</title>

<link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

<style>
/* GLOBAL */
body{
    background:#f4f7fb;
   font-family: Arial, sans-serif;
}

/* NAVBAR */
.main-header{
    background:white;
    border-bottom:1px solid #eee;
}

/* SIDEBAR JADI SOFT (GAK ITEM LAGI) */
.main-sidebar{
    background:#343a40; /* warna default AdminLTE */
}

/* BRAND */
.brand-link{
    background:transparent;
    color:white !important;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

/* HOVER */
.nav-sidebar .nav-link:hover{
    background:rgba(255,255,255,0.08);
    color:white;
}

/* ACTIVE */
.nav-sidebar .nav-link.active{
    background:#007bff;
    box-shadow:0 6px 15px rgba(0,123,255,0.3);
}
/* ICON */
.nav-icon{
    color:#cbd5f1 !important;
}
/* CONTENT */
.content-wrapper {
    background-color: #f8fafc !important; /* Background abu-abu sangat muda agar card putih menonjol */
}

/* CARD STAT */
.small-box{
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    transition:0.3s;
}

.brand-link{
    padding:15px 10px;
    text-align:center;
}
.brand-link{
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
/* WARNA STAT MODERN */
.bg-info{background:#0d6efd !important;}
.bg-success{background:#198754 !important;}
.bg-warning{background:#ffc107 !important;}
.bg-danger{background:#dc3545 !important;}
/* ICON */
.small-box{
    border-radius:20px;
    padding:20px;
    color:white;
box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    border: none;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.small-box .icon{
    background:rgba(255,255,255,0.2);
    padding:12px;
    border-radius:50%;
    font-size:20px;
}
.small-box h3{
    font-size:28px;
    font-weight:700;
}
.small-box p{
    margin:0;
    font-size:14px;
    opacity:0.9;
}
.small-box:hover {
    transform: translateY(-5px);
}
/* CARD UMUM */
.card{
    border-radius:10px; /* lebih kotak */
    border:1px solid #e5e7eb; /* kasih garis biar kepisah */
    box-shadow:0 4px 15px rgba(0,0,0,0.06);
    background:#fff;
}
/* HEADER CARD */
.card-header{
    border-bottom:1px solid #e5e7eb;
    font-weight:500;
}
/* GRAFIK CARD */
.card .card-header.bg-primary{
    background: linear-gradient(135deg,#3b82f6,#2563eb)!important;
}
.card:hover{
    transform:translateY(-3px);
    transition:0.3s;
}
/* TABLE */
.table{
    margin:0;
}

.table thead{
    background:#f1f5f9;
}

.table td, .table th{
    vertical-align:middle;
}

/* TOAST LEBIH HALUS */
#welcomeToast{
    border-radius:12px;
    font-size:14px;
}

/* RESPONSIVE FIX */
@media(max-width:768px){

    .content-header h1{
        font-size:18px;
    }

    .small-box h3{
        font-size:20px;
    }

    .small-box p{
        font-size:12px;
    }

}
/* SIDEBAR */
.main-sidebar {
    background: #1e293b !important; /* Warna Slate (Biru Gelap Professional) */
    box-shadow: 4px 0 10px rgba(0,0,0,0.1);
}

/* Bagian Logo/Brand */
.brand-link {
    padding: 20px 15px !important;
    border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    display: flex;
    align-items: center;
    background: rgba(0,0,0,0.1); /* Sedikit gelap di area logo */
    text-decoration: none !important;
}

.brand-image {
    float: none !important; /* Reset AdminLTE default */
    margin-right: 12px !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    border: 1.5px solid rgba(255,255,255,0.2);
}

.brand-text-container {
    line-height: 1.2;
}

.brand-text-main {
    display: block;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 1.5px;
    color: #ffffff;
    text-transform: uppercase;
}

.brand-text-sub {
    display: block;
    font-size: 10px;
    font-weight: 400;
    color: #94a3b8;
    letter-spacing: 0.5px;
}


/* Gaya Menu Navigasi  */
.nav-sidebar .nav-item {
    margin: 5px 12px !important; 
    padding: 0 !important;
    display: block !important;
    width: auto !important;
}

.nav-sidebar .nav-link {
    border-radius: 10px !important;
    color: #cbd5e1 !important;
    padding: 10px 15px !important;
    display: flex !important;
    align-items: center;
    width: 100% !important; 
    margin: 0 !important; 
    position: relative;
    left: 0;
}

.nav-sidebar .nav-link.active {
    background: #3b82f6 !important; 
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
    border: none !important;
}

.nav-sidebar .nav-link p {
    margin-left: 10px !important;
    margin-bottom: 0 !important;
    white-space: nowrap;
}

.nav-icon {
    width: 25px !important;
    text-align: center;
}

/* Header menu (Manajemen Data) */
.nav-header {
    margin: 15px 15px 5px !important;
    padding: 0 !important;
    color: #475569 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
}
/* garis pemisah bawah logout */
.nav-item.mt-3{
    border-top:1px solid rgba(255,255,255,0.1);
    margin-top:15px;
    padding-top:10px;
}

/* icon */
.nav-icon{
    color:#cbd5f1 !important;
}
.main-footer{
    display:flex;
    justify-content:center;
    align-items:center;
    background:#fff;
    border-top:1px solid #e5e7eb;
    padding:12px;
}
</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<!-- NAVBAR -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" data-widget="pushmenu" href="#">
<i class="fas fa-bars"></i>
</a>
</li>
</ul>

<ul class="navbar-nav ml-auto">
    <?php
$notif = mysqli_query($conn,"
SELECT * FROM tamu 
ORDER BY id DESC 
LIMIT 5
");

$total_notif = mysqli_num_rows($notif);
?>

<li class="nav-item dropdown">
<a class="nav-link" data-toggle="dropdown" href="#">
<i class="far fa-bell"></i>
<span class="badge badge-danger"><?= $total_notif ?></span>
</a>

<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

<span class="dropdown-header"><?= $total_notif ?> Tamu Baru</span>

<div class="dropdown-divider"></div>

<?php while($n = mysqli_fetch_assoc($notif)){ ?>
<a href="#" class="dropdown-item">
<i class="fas fa-user mr-2"></i> <?= $n['nama'] ?>
<span class="float-right text-muted text-sm">
<?= date('H:i', strtotime($n['waktu_datang'])) ?>
</span>
</a>
<div class="dropdown-divider"></div>
<?php } ?>

<a href="data_tamu.php" class="dropdown-item dropdown-footer">
Lihat Semua Tamu
</a>

</div>
</li>
<li class="nav-item mr-3 mt-2 font-weight-bold text-primary">
<span id="clock"></span>
</li>

<li class="nav-item dropdown">
<a class="nav-link" href="profil.php">
<span class="font-weight-bold text-primary">
<i class="fas fa-user-shield"></i> <?= $_SESSION['user']; ?>
</span>
</a>

<div class="dropdown-menu dropdown-menu-right text-center p-3">
<i class="fas fa-user-shield fa-2x text-primary"></i>
<p class="mt-2 mb-0 font-weight-bold"><?= $_SESSION['user']; ?></p>
<small class="text-muted">Admin</small>
</div>
</li>
</ul>
</nav>

<!-- SIDEBAR -->
<aside class="main-sidebar sidebar-dark-primary">
    
    <a href="dashboard.php" class="brand-link">
        <img src="../assets/adminlte/logo_1.png" alt="Logo" class="brand-image" style="max-height: 40px; width: auto;">
        <div class="brand-text-container">
            <span class="brand-text-main">BUKU TAMU</span>
            <span class="brand-text-sub">DKPP DIGITAL SYSTEM</span>
        </div>
    </a>

    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?= ($halaman == 'dashboard.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">Manajemen Data</li>

                <li class="nav-item">
                    <a href="pegawai.php" class="nav-link <?= ($halaman == 'pegawai.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Data Pegawai</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="input_tamu.php" class="nav-link <?= ($halaman == 'input_tamu.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Input Data Tamu</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_tamu.php" class="nav-link <?= ($halaman == 'data_tamu.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>Daftar Tamu</p>
                    </a>
                </li>

                <li class="nav-item mt-4 pt-2 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                    <a href="../auth/logout.php" class="nav-link text-danger">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Keluar Aplikasi</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- CONTENT -->
<div class="content-wrapper">
    <div class="container-fluid">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 mt-2">
            <div class="col-sm-12">
                <h1 style="font-weight: 800; color: #1e293b; letter-spacing: -0.5px;">
                    Ringkasan Data
                    <span style="display: block; font-size: 14px; font-weight: 400; color: #94a3b8; margin-top: 5px;">
                        <i class="fas fa-info-circle mr-1"></i> Pantau aktivitas kunjungan tamu DKPP secara real-time.
                    </span>
                </h1>
            </div>
        </div>
    </div>
</section>
       <div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #0d6efd, #0a58ca); min-height: 120px; display: flex; align-items: center; justify-content: space-between; padding: 20px; border-radius: 15px;">
            <div class="inner" style="color: white;">
                <p style="margin: 0; font-size: 14px; font-weight: 500; opacity: 0.8;">Total Semua Tamu</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;"><?= $total_tamu ?></h3>
            </div>
            <div class="icon-circle" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-users" style="color: white; font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #198754, #146c43); min-height: 120px; display: flex; align-items: center; justify-content: space-between; padding: 20px; border-radius: 15px;">
            <div class="inner" style="color: white;">
                <p style="margin: 0; font-size: 14px; font-weight: 500; opacity: 0.8;">Tamu Hari Ini</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;"><?= $tamu_hari_ini ?></h3>
            </div>
            <div class="icon-circle" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-check" style="color: white; font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #f59e0b, #d97706); min-height: 120px; display: flex; align-items: center; justify-content: space-between; padding: 20px; border-radius: 15px;">
            <div class="inner" style="color: white;">
                <p style="margin: 0; font-size: 14px; font-weight: 500; opacity: 0.8;">Tamu Bulan Ini</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;"><?= $tamu_bulan_ini ?></h3>
            </div>
            <div class="icon-circle" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chart-line" style="color: white; font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #dc3545, #b02a37); min-height: 120px; display: flex; align-items: center; justify-content: space-between; padding: 20px; border-radius: 15px;">
            <div class="inner" style="color: white;">
                <p style="margin: 0; font-size: 14px; font-weight: 500; opacity: 0.8;">Tamu Tahun Ini</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;"><?= $tamu_tahun_ini ?></h3>
            </div>
            <div class="icon-circle" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-calendar-alt" style="color: white; font-size: 20px;"></i>
            </div>
        </div>
    </div>
</div>
        </div> <div class="row mt-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-chart-area mr-1 text-primary"></i> Tren Kunjungan <?= date('Y') ?></h3>
                    </div>
                    <div class="card-body">
                        <div style="height:320px;">
                            <canvas id="grafikTamu"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-1 text-primary"></i> Tamu Terbaru</h3>
                        <div class="card-tools">
                            <a href="data_tamu.php" class="btn btn-tool btn-sm">Lihat Semua</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-valign-middle table-hover">
                                <thead class="bg-light">
                                    <tr style="font-size: 13px;">
                                        <th>Nama</th>
                                        <th>Tujuan</th>
                                        <th class="text-right">Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($conn,"SELECT * FROM tamu ORDER BY id DESC LIMIT 5");
                                    if(mysqli_num_rows($query) > 0){
                                        while($row = mysqli_fetch_assoc($query)){
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold" style="color: #1e293b;"><?= $row['nama'] ?></span>
                                            <br><small class="text-muted"><?= $row['instansi'] ?></small>
                                        </td>
                                        <td><small><?= $row['tujuan'] ?></small></td>
                                        <td class="text-right text-primary font-weight-bold">
                                            <?= date('H:i', strtotime($row['waktu_datang'])) ?>
                                        </td>
                                    </tr>
                                    <?php }} else { ?>
                                    <tr><td colspan="3" class="text-center p-4">Belum ada tamu hari ini</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> </div> </div> ```

<footer class="main-footer">
    <p style="text-align:center; margin:0; color:#64748b;">
        Sistem Informasi Buku Tamu
    </p>
</footer>
</div>

<!-- TOAST -->
<div id="welcomeToast" style="
position: fixed;
top: 20px;
right: -300px;
opacity: 0;
background: linear-gradient(45deg,#28a745,#20c997);
color: white;
padding: 15px 20px;
border-radius: 10px;
box-shadow: 0 0 15px rgba(40,167,69,0.7);
transition: all 0.5s ease;
z-index: 9999;
">
👋 Selamat datang, <b><?= $_SESSION['user']; ?></b>
</div>

<!-- SCRIPT -->
<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>

<script>
// JAM REALTIME
function updateClock(){
const now = new Date();
document.getElementById("clock").innerHTML =
now.toLocaleDateString()+" "+now.toLocaleTimeString();
}
setInterval(updateClock,1000);
updateClock();

// TOAST
window.onload = function(){
var toast = document.getElementById("welcomeToast");

setTimeout(() => {
toast.style.right = "20px";
toast.style.opacity = "1";
}, 500);

setTimeout(() => {
toast.style.right = "-300px";
toast.style.opacity = "0";
}, 4000);
}

// GRAFIK
new Chart(document.getElementById('grafikTamu'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
            label: 'Jumlah Tamu',
            data: [<?= implode(',', $data_bulan) ?>],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.1)',
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            fill: true,
            tension: 0.4,      // Kelengkungan garis
            borderWidth: 3,    // Ketebalan garis (Premium Look)
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: '#f1f5f9' }, // Garis horizontal tipis
            ticks: { color: '#94a3b8' }
        },
        x: {
            grid: { display: false }, // Hilangkan garis vertikal
            ticks: { color: '#94a3b8' }
        }
    }
}
});
</script>
<script>
$(function () {
  $('[data-widget="treeview"]').Treeview('init');
});
</script>
</body>
</html>