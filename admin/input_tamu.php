<?php 
include "../config/koneksi.php";
$halaman = basename($_SERVER['PHP_SELF']);

if(isset($_POST['tambah'])){
    $nama       = $_POST['nama'];
    $instansi   = $_POST['instansi'];
    $no_hp      = $_POST['no_hp'];
    $tujuan     = $_POST['tujuan'];
    $bertemu    = $_POST['bertemu'];

    mysqli_query($conn,"INSERT INTO tamu 
    (nama,instansi,no_hp,tujuan,bertemu,waktu_datang) 
    VALUES('$nama','$instansi','$no_hp','$tujuan','$bertemu',NOW())");

    header("Location: data_tamu.php");
}

$pegawai = mysqli_query($conn, "SELECT * FROM pegawai");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Buku Tamu | DKPP</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    .content-wrapper { background: #f4f6f9; }
    .card-primary.card-outline { 
    border-top: 3px solid #1e3a8a; 
    }
    label { font-weight: 600; color: #495057; }
    .form-control:focus { 
    border-color: #1e3a8a; 
    box-shadow: none; 
}
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
    }
    .btn-save { padding: 10px 25px; font-weight: bold; }
    .nav-sidebar .nav-item{
    margin-bottom:5px;
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
.card-primary:not(.card-outline) > .card-header {
    background-color: #1e3a8a;
}
.card-title {
    font-weight: 600;
}
.btn-primary {
    background-color: #1e3a8a !important;
    border-color: #1e3a8a !important;
}

.btn-primary:hover {
    background-color: #162c66 !important; /* Warna sedikit lebih gelap saat di-hover */
}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
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

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold">Registrasi Tamu</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            
            <div class="card card-primary shadow">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Kunjungan Baru</h3>
              </div>
              
              <form method="POST">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Nama Lengkap Tamu</label>
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                          <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Nomor WhatsApp/HP</label>
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                          <input type="number" name="no_hp" class="form-control" placeholder="0812xxxx" required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Instansi</label>
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-building"></i></span></div>
                          <input type="text" name="instansi" class="form-control" placeholder="Contoh: PT. Maju Jaya" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Tujuan Kunjungan</label>
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-info-circle"></i></span></div>
                          <input type="text" name="tujuan" class="form-control" placeholder="Maksud kedatangan..." required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Pejabat/Pegawai yang Ditemui</label>
                        <select name="bertemu" class="form-control select2" style="width: 100%;" required>
                          <option value=""></option>
                          <?php 
                          mysqli_data_seek($pegawai, 0);
                          while($p = mysqli_fetch_array($pegawai)){ ?>
                            <option value="<?= $p['nama'] ?>"><?= $p['nama'] ?> (<?= $p['jabatan'] ?>)</option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer bg-white">
                  <div class="d-flex justify-content-between">
                    <a href="data_tamu.php" class="btn btn-default"><i class="fas fa-times"></i> Batal</a>
                    <button type="submit" name="tambah" class="btn btn-primary btn-save shadow-sm">
                      <i class="fas fa-save mr-1"></i> Simpan Data Kunjungan
                    </button>
                  </div>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2026 DKPP.</strong>
  </footer>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
  $('[data-widget="treeview"]').Treeview('init');
});
</script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "-- Pilih Pegawai --",
        allowClear: true
    });
});
</script>
</body>
</html>