<?php
session_start();
include "../config/koneksi.php";
include "../config/log_helper.php";
$halaman = basename($_SERVER['PHP_SELF']);

/* EDIT */
if(isset($_POST['edit'])){
    $id         = $_POST['id'];
    $nama       = $_POST['nama'];
    $instansi   = $_POST['instansi'];
    $no_hp      = $_POST['no_hp'];
    $tujuan     = $_POST['tujuan'];
    $bertemu    = $_POST['bertemu'];

    mysqli_query($conn,"UPDATE tamu SET
    nama='$nama',
    instansi='$instansi',
    no_hp='$no_hp',
    tujuan='$tujuan',
    bertemu='$bertemu'
    WHERE id='$id'");

    catat_log($conn, $_SESSION['user'] ?? 'admin', $_SESSION['role'] ?? 'admin', 'Edit Data', "Mengubah data tamu: $nama ($instansi)");

    header("Location: data_tamu.php");
}

/* HAPUS */
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM tamu WHERE id='$id'"));
    $nama_hapus = $cek['nama'] ?? '-';

    mysqli_query($conn,"DELETE FROM tamu WHERE id='$id'");

    catat_log($conn, $_SESSION['user'] ?? 'admin', $_SESSION['role'] ?? 'admin', 'Hapus Data', "Menghapus data tamu: $nama_hapus");

    header("Location: data_tamu.php");
}

$pegawai_list = [];
$q_pegawai = mysqli_query($conn, "SELECT * FROM pegawai");
while($p = mysqli_fetch_array($q_pegawai)) {
    $pegawai_list[] = $p;
}

/* ================= FILTER TANGGAL ================= */
$tgl_awal  = isset($_GET['tgl_awal'])  ? mysqli_real_escape_string($conn, $_GET['tgl_awal'])  : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? mysqli_real_escape_string($conn, $_GET['tgl_akhir']) : '';

$where = "";
if($tgl_awal != "" && $tgl_akhir != ""){
    $where = "WHERE DATE(waktu_datang) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
} elseif($tgl_awal != ""){
    $where = "WHERE DATE(waktu_datang) >= '$tgl_awal'";
} elseif($tgl_akhir != ""){
    $where = "WHERE DATE(waktu_datang) <= '$tgl_akhir'";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Data Tamu | DKPP</title>

<link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

<style>
    body { font-size: 0.95rem; }
    .content-wrapper { background: #f4f6f9; padding: 20px; }
    
    /* Tabel Styling */
    .table thead th {
        background-color: #1e3a8a !important;
        color: white;
        border: none;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .table td { vertical-align: middle !important; }
    
    /* Nama Tamu Bold */
    .nama-tamu { font-weight: 700; color: #2c3e50; }
    
    /* Badge Waktu */
    .badge-waktu { 
        background: #eef2ff; 
        color: #4338ca; 
        padding: 5px 10px; 
        border-radius: 6px; 
        font-size: 0.8rem; 
    }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        line-height: 32px;
        border-radius: 8px;
    }

/* ===== SEARCH BOX ===== */
.search-box{
    position:relative;
    width:260px;
}
.search-box input{
    border-radius:50px;
    padding-left:38px;
    height:38px;
    border:1px solid #dee2e6;
    background:#fff;
}
.search-box input:focus{
    box-shadow:0 0 0 2px rgba(30,58,138,.15);
    border-color:#1e3a8a;
}
.search-box i{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#94a3b8;
}

/* ===== SIDEBAR ===== */
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

/* Mengatur teks di samping icon */
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

.nav-item.mt-3{
    border-top:1px solid rgba(255,255,255,0.1);
    margin-top:15px;
    padding-top:10px;
}
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
        <li class="nav-item d-none d-sm-inline-block">
           
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

<div class="content-wrapper">
    <div class="container-fluid pt-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:12px;">
            <h3 class="font-weight-bold m-0">Daftar Kunjungan Tamu</h3>

            <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="customSearch" class="form-control" placeholder="Cari nama, instansi, tujuan...">
                </div>

                <a href="input_tamu.php" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus mr-1"></i> Tambah Tamu
                </a>
            </div>
        </div>

        <form method="GET" class="date-filter d-flex align-items-center flex-wrap mb-3" style="gap:10px;">
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
            <?php if($tgl_awal != "" || $tgl_akhir != ""){ ?>
            <a href="data_tamu.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-times mr-1"></i> Reset
            </a>
            <?php } ?>
        </form>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table id="tabelTamu" class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Tamu</th>
                            <th>Instansi</th>
                            <th>Tujuan</th>
                            <th>Bertemu</th>
                            <th>Waktu Datang</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no=1;
                        $data = mysqli_query($conn,"SELECT * FROM tamu $where ORDER BY id DESC");
                        while($d=mysqli_fetch_array($data)){
                        ?>
                        <tr>
<td class="text-center"><?= $no++ ?></td>
<td>
    <div class="nama-tamu"><?= $d['nama'] ?></div>
    
    <div class="mt-1">
        <span class="badge badge-info" style="font-size: 11px; font-weight: normal; background-color: #e0f2fe; color: #0369a1; border: none;">
            <i class="fas fa-phone-alt mr-1" style="font-size: 9px;"></i><?= $d['no_hp'] ?>
        </span>
    </div>
</td>
<td><?= $d['instansi'] ?></td>
                            <td><?= $d['tujuan'] ?></td>
                            <td><span class="badge badge-light border text-dark"><?= $d['bertemu'] ?></span></td>
                            <td><span class="badge-waktu"><i class="far fa-clock mr-1"></i> <?= date('d M Y, H:i', strtotime($d['waktu_datang'])) ?></span></td>
                            <td class="text-center">
                                <button title="Edit" class="btn btn-warning btn-sm btn-action text-white" data-toggle="modal" data-target="#edit<?= $d['id'] ?>">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <a title="Hapus" href="?hapus=<?= $d['id'] ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm btn-action">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="edit<?= $d['id'] ?>">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow-lg" style="border-radius:15px;">
                                    <form method="POST">
                                        <div class="modal-header bg-warning text-white" style="border-radius:15px 15px 0 0;">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Update Data Tamu</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                            
                                            <div class="form-group">
                                                <label>Nama Lengkap</label>
                                                <input type="text" name="nama" value="<?= $d['nama'] ?>" class="form-control rounded-pill" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Instansi</label>
                                                <input type="text" name="instansi" value="<?= $d['instansi'] ?>" class="form-control rounded-pill" required>
                                            </div>
                                            <div class="form-group">
                                                <label>No HP/WA</label>
                                                <input type="text" name="no_hp" value="<?= $d['no_hp'] ?>" class="form-control rounded-pill" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Tujuan</label>
                                                <input type="text" name="tujuan" value="<?= $d['tujuan'] ?>" class="form-control rounded-pill" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Bertemu Dengan</label>
                                                <select name="bertemu" class="form-control rounded-pill" required>
                                                    <?php foreach($pegawai_list as $p) { ?>
                                                        <option value="<?= $p['nama'] ?>" <?= ($d['bertemu'] == $p['nama']) ? 'selected' : '' ?>>
                                                            <?= $p['nama'] ?> (<?= $p['jabatan'] ?>)
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                                            <button type="submit" name="edit" class="btn btn-warning text-white rounded-pill px-4 font-weight-bold">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer text-center small">
    <strong>DKPP &copy; 2026.</strong> All rights reserved.
</footer>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
<script>
$(function () {
  $('[data-widget="treeview"]').Treeview('init');
});
</script>
<script>
$(document).ready(function() {
    var tabelTamu = $("#tabelTamu").DataTable({
        "responsive": true,
        "lengthChange": true,
        "searching": true,
        "autoWidth": false,
        "dom": '<"row"<"col-md-6"l>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
        "language": {
            "lengthMenu": "Tampilkan _MENU_ baris",
            "info": "Menampilkan _START_ ke _END_ dari _TOTAL_ tamu",
            "paginate": {
                "previous": "<i class='fas fa-angle-left'></i>",
                "next": "<i class='fas fa-angle-right'></i>"
            }
        }
    });

    // Search kustom terhubung ke DataTables
    $('#customSearch').on('keyup', function () {
        tabelTamu.search(this.value).draw();
    });
});
</script>
</body>
</html>