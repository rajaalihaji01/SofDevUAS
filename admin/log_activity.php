<?php
session_start();
include "../config/koneksi.php";
$halaman = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* ================= FILTER ================= */
$peran     = isset($_GET['peran'])     ? mysqli_real_escape_string($conn, $_GET['peran'])     : '';
$tgl_awal  = isset($_GET['tgl_awal'])  ? mysqli_real_escape_string($conn, $_GET['tgl_awal'])  : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? mysqli_real_escape_string($conn, $_GET['tgl_akhir']) : '';

$kondisi = [];
if($peran != ""){
    $kondisi[] = "peran = '$peran'";
}
if($tgl_awal != "" && $tgl_akhir != ""){
    $kondisi[] = "DATE(waktu) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
} elseif($tgl_awal != ""){
    $kondisi[] = "DATE(waktu) >= '$tgl_awal'";
} elseif($tgl_akhir != ""){
    $kondisi[] = "DATE(waktu) <= '$tgl_akhir'";
}

$where = count($kondisi) > 0 ? "WHERE " . implode(" AND ", $kondisi) : "";

/* Total & statistik ringkas */
$total_log   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM log_activity"));
$hari_ini    = date('Y-m-d');
$log_hari_ini = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM log_activity WHERE DATE(waktu)='$hari_ini'"));
$log_tamu     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM log_activity WHERE peran='tamu'"));
$log_admin    = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM log_activity WHERE peran IN ('admin','petugas')"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log Activity - BUKU TAMU</title>

<link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

<style>
body{ background:#f4f7fb; }
.content-wrapper{ background-color:#f8fafc !important; }

.small-box{
    border-radius:15px;
    padding:20px;
    color:white;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    display:flex;
    justify-content:space-between;
    align-items:center;
    min-height:100px;
}
.small-box .icon-circle{
    background:rgba(255,255,255,.2);
    width:46px; height:46px;
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
}
.small-box h3{ margin:0; font-size:24px; font-weight:700; }
.small-box p{ margin:0; font-size:13px; opacity:.85; }

.table thead th {
    background-color: #1e3a8a !important;
    color: white;
    border: none;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    padding: 14px !important;
}
.table td { vertical-align: middle !important; }

/* BADGE PERAN */
.badge-peran{ padding:5px 10px; border-radius:6px; font-size:11px; font-weight:600; text-transform:uppercase; }
.badge-peran.admin{ background:#dbeafe; color:#1e3a8a; }
.badge-peran.petugas{ background:#e0f2fe; color:#0369a1; }
.badge-peran.tamu{ background:#f1f5f9; color:#475569; }

/* BADGE AKTIVITAS */
.badge-aksi{ padding:5px 10px; border-radius:6px; font-size:11px; font-weight:600; }
.badge-aksi.login{ background:#dcfce7; color:#15803d; }
.badge-aksi.logout{ background:#fee2e2; color:#b91c1c; }
.badge-aksi.tambah{ background:#dbeafe; color:#1d4ed8; }
.badge-aksi.edit{ background:#fef3c7; color:#b45309; }
.badge-aksi.hapus{ background:#fee2e2; color:#b91c1c; }
.badge-aksi.password{ background:#ede9fe; color:#6d28d9; }
.badge-aksi.default{ background:#f1f5f9; color:#475569; }

.search-box{ position:relative; width:240px; }
.search-box input{ border-radius:50px; padding-left:38px; height:38px; border:1px solid #dee2e6; background:#fff; }
.search-box input:focus{ box-shadow:0 0 0 2px rgba(30,58,138,.15); border-color:#1e3a8a; }
.search-box i{ position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; }

/* ===== SIDEBAR ===== */
.main-sidebar { background: #1e293b !important; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
.brand-link { padding: 20px 15px !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; display: flex; align-items: center; background: rgba(0,0,0,0.1); text-decoration: none !important; }
.brand-image { float: none !important; margin-right: 12px !important; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border: 1.5px solid rgba(255,255,255,0.2); }
.brand-text-container { line-height: 1.2; }
.brand-text-main { display: block; font-size: 16px; font-weight: 800; letter-spacing: 1.5px; color: #ffffff; text-transform: uppercase; }
.brand-text-sub { display: block; font-size: 10px; font-weight: 400; color: #94a3b8; letter-spacing: 0.5px; }
.nav-sidebar .nav-item { margin: 5px 12px !important; padding: 0 !important; display: block !important; width: auto !important; }
.nav-sidebar .nav-link { border-radius: 10px !important; color: #cbd5e1 !important; padding: 10px 15px !important; display: flex !important; align-items: center; width: 100% !important; margin: 0 !important; position: relative; left: 0; }
.nav-sidebar .nav-link.active { background: #3b82f6 !important; color: #ffffff !important; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important; border: none !important; }
.nav-sidebar .nav-link p { margin-left: 10px !important; margin-bottom: 0 !important; white-space: nowrap; }
.nav-icon { width: 25px !important; text-align: center; }
.nav-header { margin: 15px 15px 5px !important; padding: 0 !important; color: #475569 !important; font-size: 11px !important; font-weight: 700 !important; text-transform: uppercase; }
.nav-item.mt-3{ border-top:1px solid rgba(255,255,255,0.1); margin-top:15px; padding-top:10px; }
</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item mr-3 mt-2 font-weight-bold text-primary">
            <span id="clock"></span>
        </li>
        <li class="nav-item">
            <a href="../auth/logout.php" class="nav-link text-danger"><i class="fas fa-power-off"></i></a>
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

                <li class="nav-header">Sistem</li>

                <li class="nav-item">
                    <a href="log_activity.php" class="nav-link <?= ($halaman == 'log_activity.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Log Activity</p>
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
<div class="content-wrapper p-4">

<div class="row mb-3 mt-2">
    <div class="col-sm-12">
        <h1 style="font-weight: 800; color: #1e293b; letter-spacing: -0.5px; font-size:24px;">
            Log Activity
            <span style="display: block; font-size: 14px; font-weight: 400; color: #94a3b8; margin-top: 5px;">
                <i class="fas fa-info-circle mr-1"></i> Rekam jejak seluruh aktivitas admin, petugas, dan tamu.
            </span>
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #1e3a8a, #1e40af);">
            <div><p>Total Log</p><h3><?= $total_log ?></h3></div>
            <div class="icon-circle"><i class="fas fa-list-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #198754, #146c43);">
            <div><p>Aktivitas Hari Ini</p><h3><?= $log_hari_ini ?></h3></div>
            <div class="icon-circle"><i class="fas fa-calendar-day"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
            <div><p>Oleh Admin/Petugas</p><h3><?= $log_admin ?></h3></div>
            <div class="icon-circle"><i class="fas fa-user-shield"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div><p>Oleh Tamu</p><h3><?= $log_tamu ?></h3></div>
            <div class="icon-circle"><i class="fas fa-user-friends"></i></div>
        </div>
    </div>
</div>

<div class="card shadow border-0">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:12px;">
    <h4 class="m-0 font-weight-bold">Riwayat Aktivitas</h4>
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="customSearch" class="form-control" placeholder="Cari pelaku, aktivitas...">
    </div>
</div>

<form method="GET" class="d-flex align-items-center flex-wrap mb-3" style="gap:10px;">
    <select name="peran" class="form-control form-control-sm rounded-pill" style="width:160px;" onchange="this.form.submit()">
        <option value="">-- Semua Peran --</option>
        <option value="admin" <?= $peran=='admin'?'selected':'' ?>>Admin</option>
        <option value="petugas" <?= $peran=='petugas'?'selected':'' ?>>Petugas</option>
        <option value="tamu" <?= $peran=='tamu'?'selected':'' ?>>Tamu</option>
    </select>

    <div class="d-flex align-items-center" style="gap:6px;">
        <label class="m-0 small text-muted">Dari</label>
        <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal) ?>" class="form-control form-control-sm rounded-pill">
    </div>
    <div class="d-flex align-items-center" style="gap:6px;">
        <label class="m-0 small text-muted">Sampai</label>
        <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir) ?>" class="form-control form-control-sm rounded-pill">
    </div>

    <button type="submit" class="btn btn-sm btn-dark rounded-pill px-3">
        <i class="fas fa-filter mr-1"></i> Terapkan
    </button>
    <?php if($peran != "" || $tgl_awal != "" || $tgl_akhir != ""){ ?>
    <a href="log_activity.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="fas fa-times mr-1"></i> Reset
    </a>
    <?php } ?>
</form>

<div class="table-responsive">
<table id="tabelLog" class="table table-hover w-100">
<thead>
<tr>
    <th width="5%">No</th>
    <th>Waktu</th>
    <th>Pelaku</th>
    <th>Peran</th>
    <th>Aktivitas</th>
    <th>Keterangan</th>
    <th>IP Address</th>
</tr>
</thead>
<tbody>
<?php
$no = 1;
$data = mysqli_query($conn, "SELECT * FROM log_activity $where ORDER BY id DESC");
while($d = mysqli_fetch_array($data)){

    $aksi = strtolower($d['aktivitas']);
    $kelas = 'default';
    if(strpos($aksi,'login') !== false) $kelas = 'login';
    elseif(strpos($aksi,'logout') !== false) $kelas = 'logout';
    elseif(strpos($aksi,'tambah') !== false) $kelas = 'tambah';
    elseif(strpos($aksi,'edit') !== false || strpos($aksi,'update') !== false) $kelas = 'edit';
    elseif(strpos($aksi,'hapus') !== false || strpos($aksi,'delete') !== false) $kelas = 'hapus';
    elseif(strpos($aksi,'password') !== false) $kelas = 'password';
?>
<tr>
    <td class="text-center"><?= $no++ ?></td>
    <td><i class="far fa-clock mr-1 text-muted"></i> <?= date('d M Y, H:i', strtotime($d['waktu'])) ?></td>
    <td class="font-weight-bold"><?= htmlspecialchars($d['pelaku']) ?></td>
    <td><span class="badge-peran <?= htmlspecialchars($d['peran']) ?>"><?= htmlspecialchars($d['peran']) ?></span></td>
    <td><span class="badge-aksi <?= $kelas ?>"><?= htmlspecialchars($d['aktivitas']) ?></span></td>
    <td><?= htmlspecialchars($d['keterangan']) ?></td>
    <td class="text-muted small"><?= htmlspecialchars($d['ip_address']) ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
</div>

</div>

<footer class="main-footer">
    <p style="text-align:center; margin:0; color:#64748b;">
        Sistem Informasi Buku Tamu
    </p>
</footer>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
<script>
$(function () {
  $('[data-widget="treeview"]').Treeview('init');
});

function updateClock(){
    const now = new Date();
    document.getElementById("clock").innerHTML = now.toLocaleDateString()+" "+now.toLocaleTimeString();
}
setInterval(updateClock,1000);
updateClock();

var tabelLog = $("#tabelLog").DataTable({
    "responsive": true,
    "autoWidth": false,
    "dom": '<"row"<"col-md-6"l>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
    "order": [[1, 'desc']],
    "language": {
        "lengthMenu": "Tampilkan _MENU_ baris",
        "info": "Menampilkan _START_ ke _END_ dari _TOTAL_ log",
        "paginate": {
            "previous": "<i class='fas fa-angle-left'></i>",
            "next": "<i class='fas fa-angle-right'></i>"
        }
    }
});

$('#customSearch').on('keyup', function () {
    tabelLog.search(this.value).draw();
});
</script>

</body>
</html>