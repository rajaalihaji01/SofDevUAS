<?php

/* ==========================================================
   SESSION & KONEKSI DATABASE
========================================================== */

session_start();
include "../config/koneksi.php";


/* ==========================================================
   MENU LAPORAN
========================================================== */

$halaman = basename($_SERVER['PHP_SELF']);

$master = in_array($halaman, ['pegawai.php']);
$transaksi = in_array($halaman, ['data_tamu.php', 'input_tamu.php']);
$laporan = in_array($halaman, ['laporan.php']);


/* ==========================================================
   CEK LOGIN
========================================================== */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}


/* ==========================================================
   FILTER TANGGAL
========================================================== */

$mulai = $_GET['mulai'] ?? '';
$selesai = $_GET['selesai'] ?? '';

$where = "";

if ($mulai && $selesai) {

    $mulai_safe = mysqli_real_escape_string($conn, $mulai);
    $selesai_safe = mysqli_real_escape_string($conn, $selesai);

    $where = "WHERE DATE(waktu_datang) BETWEEN '$mulai_safe' AND '$selesai_safe'";
}


/* ==========================================================
   DATA TAMU
========================================================== */

$query = mysqli_query(
    $conn,
    "SELECT * FROM tamu $where ORDER BY id DESC"
);

if (!$query) {
    die("Query gagal: " . mysqli_error($conn));
}


/* ==========================================================
   TOTAL KUNJUNGAN TERDATA
========================================================== */

$total = mysqli_num_rows($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="utf-8">


<!-- ==========================================================
     JUDUL HALAMAN
========================================================== -->

<title>Laporan - BUKU TAMU</title>


<!-- ==========================================================
     CSS ADMINLTE
========================================================== -->

<link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">

<link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">


<style>

/* ==========================================================
   CARD
========================================================== */

.card-gradient {
    background: linear-gradient(45deg, #4e73df, #1cc88a);
    color: white;
}


/* ==========================================================
   SIDEBAR PREMIUM
========================================================== */

.main-sidebar {
    background: #1e293b !important;
    box-shadow: 4px 0 10px rgba(0,0,0,0.1);
}


/* ==========================================================
   BRAND / LOGO
========================================================== */

.brand-link {
    padding: 20px 15px !important;
    border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    display: flex;
    align-items: center;
    background: rgba(0,0,0,0.1);
    text-decoration: none !important;
}

.brand-image {
    float: none !important;
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


/* ==========================================================
   MENU SIDEBAR
========================================================== */

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
    box-shadow: 0 4px 12px rgba(59,130,246,0.4) !important;
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

.nav-header {
    margin: 15px 15px 5px !important;
    padding: 0 !important;
    color: #475569 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
}

.nav-item.mt-3 {
    border-top: 1px solid rgba(255,255,255,0.1);
    margin-top: 15px;
    padding-top: 10px;
}


/* ==========================================================
   WARNA
========================================================== */

.bg-primary {
    background-color: #1e3a8a !important;
}

.text-primary {
    color: #1e3a8a !important;
}

.badge-primary {
    background-color: #1e3a8a !important;
}

.table thead.bg-primary {
    background-color: #1e3a8a !important;
}

.form-control:focus {
    border-color: #1e3a8a;
    box-shadow: none;
}

.btn-primary {
    background-color: #1e3a8a !important;
    border-color: #1e3a8a !important;
}


/* ==========================================================
   TOMBOL AKSI
========================================================== */

.btn-action {
    width: 34px;
    height: 34px;
    padding: 6px 0;
    border-radius: 7px;
    margin: 2px;
}

.table td {
    vertical-align: middle !important;
}

</style>

</head>


<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">


<!-- ==========================================================
     NAVBAR
========================================================== -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">

        <li class="nav-item">

            <a
                class="nav-link"
                data-widget="pushmenu"
                href="#"
            >

                <i class="fas fa-bars"></i>

            </a>

        </li>

    </ul>


    <ul class="navbar-nav ml-auto">

        <li class="nav-item mr-3 mt-2 font-weight-bold text-primary">

            <span id="clock"></span>

        </li>

    </ul>

</nav>



<!-- ==========================================================
     SIDEBAR
========================================================== -->

<aside class="main-sidebar sidebar-dark-primary">


    <!-- ======================================================
         BRAND / LOGO
    ====================================================== -->

    <a href="dashboard.php" class="brand-link">

        <img
            src="../assets/adminlte/logo_1.png"
            alt="Logo"
            class="brand-image"
            style="max-height:40px; width:auto;"
        >

        <div class="brand-text-container">

            <span class="brand-text-main">
                BUKU TAMU
            </span>

            <span class="brand-text-sub">
                DKPP DIGITAL SYSTEM
            </span>

        </div>

    </a>


    <div class="sidebar">

        <nav class="mt-3">

            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >


                <!-- ==================================================
                     MENU DASHBOARD
                ================================================== -->

                <li class="nav-item">

                    <a
                        href="dashboard.php"
                        class="nav-link <?= ($halaman == 'dashboard.php') ? 'active' : '' ?>"
                    >

                        <i class="nav-icon fas fa-th-large"></i>

                        <p>Dashboard</p>

                    </a>

                </li>


                <!-- ==================================================
                     HEADER MENU
                ================================================== -->

                <li class="nav-header">
                    Manajemen Data
                </li>


                <!-- ==================================================
                     MENU DATA PEGAWAI
                ================================================== -->

                <li class="nav-item">

                    <a
                        href="pegawai.php"
                        class="nav-link <?= ($halaman == 'pegawai.php') ? 'active' : '' ?>"
                    >

                        <i class="nav-icon fas fa-user-tie"></i>

                        <p>Data Pegawai</p>

                    </a>

                </li>


                <!-- ==================================================
                     MENU INPUT DATA TAMU
                ================================================== -->

                <li class="nav-item">

                    <a
                        href="input_tamu.php"
                        class="nav-link <?= ($halaman == 'input_tamu.php') ? 'active' : '' ?>"
                    >

                        <i class="nav-icon fas fa-user-edit"></i>

                        <p>Input Data Tamu</p>

                    </a>

                </li>


                <!-- ==================================================
                     MENU DAFTAR TAMU
                ================================================== -->

                <li class="nav-item">

                    <a
                        href="data_tamu.php"
                        class="nav-link <?= ($halaman == 'data_tamu.php') ? 'active' : '' ?>"
                    >

                        <i class="nav-icon fas fa-address-book"></i>

                        <p>Daftar Tamu</p>

                    </a>

                </li>


                <!-- ==================================================
                     MENU LAPORAN
                ================================================== -->
                <li class="nav-item">
                    <a href="laporan.php" class="nav-link <?= ($halaman == 'laporan.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <!-- ==================================================
                     MENU LOGOUT
                ================================================== -->

                <li
                    class="nav-item mt-4 pt-2 border-top"
                    style="border-color:rgba(255,255,255,0.05) !important;"
                >

                    <a
                        href="../auth/logout.php"
                        class="nav-link text-danger"
                    >

                        <i class="nav-icon fas fa-power-off"></i>

                        <p>Keluar Aplikasi</p>

                    </a>

                </li>

            </ul>

        </nav>

    </div>

</aside>



<!-- ==========================================================
     CONTENT
========================================================== -->

<div class="content-wrapper">


    <!-- ==========================================================
         REKAPITULASI KUNJUNGAN
    ========================================================== -->

    <section class="content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">


                <!-- ==================================================
                     JUDUL REKAPITULASI
                ================================================== -->


                <!-- ==================================================
                     PERIODE
                ================================================== -->

            </div>

        </div>

    </section>



    <section class="content">

        <div class="container-fluid">


            <!-- ==================================================
                 NOTIFIKASI HAPUS
            ================================================== -->

            <?php if (isset($_GET['status']) && $_GET['status'] == 'hapus'): ?>

                <div class="alert alert-success alert-dismissible fade show">

                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                    >
                        &times;
                    </button>

                    <i class="fas fa-check-circle mr-2"></i>

                    Data tamu berhasil dihapus.

                </div>

            <?php endif; ?>


            <!-- ==================================================
                 NOTIFIKASI EDIT
            ================================================== -->

            <?php if (isset($_GET['status']) && $_GET['status'] == 'edit'): ?>

                <div class="alert alert-success alert-dismissible fade show">

                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                    >
                        &times;
                    </button>

                    <i class="fas fa-check-circle mr-2"></i>

                    Data tamu berhasil diperbarui.

                </div>

            <?php endif; ?>



            <!-- ==========================================================
                 TOTAL KUNJUNGAN TERDATA
            ========================================================== -->
            <div class="row">

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-primary shadow">

                        <div class="inner">

                            <h3>
                                <?= $total ?>
                            </h3>

                            <p>
                                Total Kunjungan Terdata
                            </p>

                        </div>


                        <div class="icon">

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

            </div>
            <!-- ==========================================================
                 ATUR PERIODE LAPORAN
            ========================================================== -->
            <div class="card card-primary card-outline shadow-sm mb-4">


                <div class="card-header bg-light">

                    <h3 class="card-title text-primary font-weight-bold">

                        <i class="fas fa-filter mr-1"></i>

                        Atur Periode Laporan

                    </h3>

                </div>


                <div class="card-body">

                    <form method="GET">

                        <div class="row align-items-end">


                            <div class="col-md-3">

                                <label class="small font-weight-bold">

                                    Dari Tanggal

                                </label>

                                <input
                                    type="date"
                                    name="mulai"
                                    value="<?= htmlspecialchars($mulai) ?>"
                                    class="form-control shadow-sm"
                                >

                            </div>


                            <div class="col-md-3">

                                <label class="small font-weight-bold">

                                    Sampai Tanggal

                                </label>

                                <input
                                    type="date"
                                    name="selesai"
                                    value="<?= htmlspecialchars($selesai) ?>"
                                    class="form-control shadow-sm"
                                >

                            </div>


                            <div class="col-md-6 mt-3 mt-md-0">


                                <button
                                    type="submit"
                                    class="btn btn-primary px-4 shadow-sm"
                                >

                                    <i class="fas fa-search mr-1"></i>

                                    Tampilkan

                                </button>
                                <a
                                    href="laporan.php"
                                    class="btn btn-secondary px-3"
                                >

                                    Reset

                                </a>
                                <div class="float-right">

                                    <div class="btn-group shadow-sm">


                                        <a
                                            href="export_excel_laporan.php?mulai=<?= urlencode($mulai) ?>&selesai=<?= urlencode($selesai) ?>"
                                            class="btn btn-success <?= (!$mulai || !$selesai) ? 'disabled' : '' ?>"
                                        >

                                            <i class="fas fa-file-excel"></i>

                                            Excel

                                        </a>
                                        <a
                                            href="export_pdf_laporan.php?mulai=<?= urlencode($mulai) ?>&selesai=<?= urlencode($selesai) ?>"
                                            class="btn btn-danger <?= (!$mulai || !$selesai) ? 'disabled' : '' ?>"
                                            target="_blank"
                                        >

                                            <i class="fas fa-file-pdf"></i>

                                            PDF

                                        </a>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            <!-- ==========================================================
                 TABEL REKAPITULASI KUNJUNGAN
            ========================================================== -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="bg-primary text-white">

                                <tr>

                                    <tr>

                                    <th class="text-center" width="5%">
                                        No
                                    </th>

                                    <th>
                                        Nama Tamu & No. HP
                                    </th>

                                    <th>
                                        Instansi
                                    </th>

                                    <th>
                                        Tujuan
                                    </th>

                                    <th>
                                        Bertemu Dengan
                                    </th>

                                    <th class="text-center">
                                        Waktu Datang
                                    </th>

                                    <th class="text-center" width="12%">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>



                            <!-- ==================================================
                                 DATA TABEL
                            ================================================== -->

                            <tbody>

                            <?php

                            if (mysqli_num_rows($query) > 0) {

                                $no = 1;

                                while ($d = mysqli_fetch_assoc($query)) {

                            ?>

                                <tr>


                                    <!-- ==================================================
                                         NOMOR
                                    ================================================== -->


                                    <!-- ==================================================
                                         NAMA TAMU & NO HP
                                    ================================================== -->


                                    <!-- ==================================================
                                         INSTANSI
                                    ================================================== -->


                                    <!-- ==================================================
                                         TUJUAN
                                    ================================================== -->


                                    <!-- ==================================================
                                         BERTEMU DENGAN
                                    ================================================== -->


                                    <!-- ==================================================
                                         WAKTU DATANG
                                    ================================================== -->


                                    <!-- ==================================================
                                         AKSI
                                    ================================================== -->

                                </tr>

                            <?php

                                }

                            } else {

                            ?>


                                <!-- ==================================================
                                     JIKA DATA KOSONG
                                ================================================== -->


                            <?php

                            }

                            ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </div>

    </section>

</div>



<!-- ==========================================================
     JAVASCRIPT
========================================================== -->

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>

<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>


<script>

$(function () {

    $('[data-widget="treeview"]').Treeview('init');

});

</script>


</body>

</html>