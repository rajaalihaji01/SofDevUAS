<?php
session_start();
include "../config/koneksi.php";
$halaman = basename($_SERVER['PHP_SELF']);

$master = in_array($halaman, ['pegawai.php']);
$transaksi = in_array($halaman, ['data_tamu.php','input_tamu.php']);
$laporan = in_array($halaman, ['laporan.php']);

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

/* ================= TAMBAH ================= */
if(isset($_POST['tambah'])){
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    mysqli_query($conn,"INSERT INTO pegawai(nip,nama,jabatan) 
    VALUES('$nip','$nama','$jabatan')");

    header("Location: pegawai.php");
}

/* ================= EDIT ================= */
if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    mysqli_query($conn,"UPDATE pegawai SET
        nip='$nip',
        nama='$nama',
        jabatan='$jabatan'
        WHERE id='$id'
    ");

    header("Location: pegawai.php");
}

/* ================= HAPUS ================= */
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn,"DELETE FROM pegawai WHERE id='$id'");
    header("Location: pegawai.php");
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
.card-gradient {
    background: linear-gradient(45deg,#4e73df,#1cc88a);
    color:white;
}

div.dataTables_wrapper div.dataTables_paginate {
    float: right;
    text-align: right;
}
/* biar tombol sejajar */
.aksi-btn{
    display:flex;
    justify-content:center;
    gap:8px;
}

/* tombol kotak */
.aksi-btn .btn{
    width:40px;
    height:40px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:8px;
    padding:0;
}

/* warna */
.btn-warning{
    background:#fbbf24;
    border:none;
}
.btn-danger{
    background:#ef4444;
    borderA:none;
}
table td, table th{
    vertical-align: middle !important;
    text-align: center;
}

/* khusus kolom teks */
td:nth-child(3), td:nth-child(4){
    text-align: left;
}

/* Styling Tabel Konsisten */
.table thead th {
    background-color: #1e3a8a !important; /* Biru Tua Navy */
    color: white;
    border: none;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 15px !important; /* Beri ruang agar tidak sesak */
}

.table td { 
    vertical-align: middle !important; 
    border-bottom: 1px solid #f1f5f9; /* Garis bawah tipis saja */
}
.table-hover tbody tr:hover {
    background-color: rgba(30, 58, 138, 0.04) !important; /* Biru sangat muda saat kursor di atasnya */
}
/* ===== SIDEBAR PREMIUM RE-DESIGN ===== */
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

/* Gaya Menu Navigasi - FIX TOTAL BOX OVERFLOW */
.nav-sidebar .nav-item {
    margin: 5px 12px !important; 
    padding: 0 !important;
    display: block !important;
    width: auto !important; /* Memaksa box tetap di dalam lebar sidebar minus margin */
}

.nav-sidebar .nav-link {
    border-radius: 10px !important;
    color: #cbd5e1 !important;
    padding: 10px 15px !important;
    display: flex !important;
    align-items: center;
    width: 100% !important; /* Mengikuti lebar nav-item yang sudah dibatasi margin */
    margin: 0 !important; /* Hapus margin bawaan link agar tidak geser */
    position: relative;
    left: 0;
}

.nav-sidebar .nav-link.active {
    background: #3b82f6 !important; 
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
    /* Hilangkan gaya default AdminLTE yang suka narik ke kiri/kanan */
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- NAVBAR -->
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
                <li class="nav-item">
                    <a href="laporan.php" class="nav-link <?= ($halaman == 'laporan.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Laporan</p>
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
<div class="row mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pegawai</span>
                <span class="info-box-number">
                    <?php 
                    $total = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pegawai"));
                    echo $total;
                    ?>
                    <small>Orang</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-tie"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Jabatan Berbeda</span>
                <span class="info-box-number">
                    <?php 
                    $jabatan_count = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT jabatan FROM pegawai"));
                    echo $jabatan_count;
                    ?>
                    <small>Kategori</small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box shadow-sm bg-light">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-print"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Laporan Pegawai</span>
                <a href="laporan_pegawai.php" class="btn btn-xs btn-outline-info mt-1">Cetak Daftar</a>
            </div>
        </div>
    </div>
</div>
<div class="card shadow">
<div class="card-header d-flex align-items-center">

<h4 class="m-0">Data Pegawai</h4>

<button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#tambah">
<i class="fas fa-plus"></i> Data Baru
</button>

</div>
<div class="card-body">
<table id="tabel" class="table table-bordered table-hover">
<thead class="bg-primary text-white">
<tr>
<th>No</th>
<th>NIP</th>
<th>Nama</th>
<th>Jabatan</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>
<?php
$no=1;
$data = mysqli_query($conn,"SELECT * FROM pegawai ORDER BY id DESC");
while($d=mysqli_fetch_array($data)){
?>
<tr>
<td><?= $no++ ?></td>
<td><?= $d['nip'] ?></td>
<td><?= $d['nama'] ?></td>
<td><?= $d['jabatan'] ?></td>
<td class="text-center">
    <div class="aksi-btn">
        <button class="btn btn-warning btn-sm"
            data-toggle="modal"
            data-target="#edit<?= $d['id'] ?>">
            <i class="fas fa-edit"></i>
        </button>

<a href="#" onclick="hapusData(<?= $d['id'] ?>)" class="btn btn-danger btn-sm">
    <i class="fas fa-trash"></i>
</a>
    </div>
</td>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $d['id'] ?>">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST">
<div class="modal-header">
<h5>Edit Pegawai</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <input type="hidden" name="id" value="<?= $d['id'] ?>">
    <div class="form-group">
        <label>NIP Pegawai</label>
        <input type="text" name="nip" value="<?= $d['nip'] ?>" class="form-control" placeholder="Masukkan NIP" required>
    </div>
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= $d['nama'] ?>" class="form-control" placeholder="Masukkan Nama" required>
    </div>
    <div class="form-group">
        <label>Jabatan</label>
        <input type="text" name="jabatan" value="<?= $d['jabatan'] ?>" class="form-control" placeholder="Masukkan Jabatan" required>
    </div>
</div>
<div class="modal-footer">
<button type="submit" name="edit" class="btn btn-success">Update</button>
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambah">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST">
<div class="modal-header">
<h5>Tambah Pegawai</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
<input type="text" name="nip" placeholder="NIP" class="form-control mb-2" required>
<input type="text" name="nama" placeholder="Nama" class="form-control mb-2" required>
<input type="text" name="jabatan" placeholder="Jabatan" class="form-control mb-2" required>
</div>

<div class="modal-footer">
<button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
</div>

</form>

</div>
</div>
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
</script>
<script>
$(function () {
    $("#tabel").DataTable({
        "responsive": true,
        "autoWidth": false,
        "dom": '<"row"<"col-md-6"l>>rt<"row"<"col-md-6"i><"col-md-6 text-right"p>>'
    });
});
function hapusData(id) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data pegawai akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "pegawai.php?hapus=" + id;
        }
    })
}
</script>

</body>
</html>