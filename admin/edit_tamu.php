<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: laporan.php");
    exit;
}

$id = intval($_GET['id']);

/* AMBIL DATA TAMU */
$query = mysqli_query($conn, "SELECT * FROM tamu WHERE id = $id");

if (mysqli_num_rows($query) == 0) {
    echo "Data tamu tidak ditemukan.";
    exit;
}

$data = mysqli_fetch_assoc($query);

/* PROSES UPDATE */
if (isset($_POST['update'])) {

    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp      = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $instansi   = mysqli_real_escape_string($conn, $_POST['instansi']);
    $tujuan     = mysqli_real_escape_string($conn, $_POST['tujuan']);
    $bertemu    = mysqli_real_escape_string($conn, $_POST['bertemu']);
    $waktu      = mysqli_real_escape_string($conn, $_POST['waktu_datang']);

    $update = mysqli_query($conn, "
        UPDATE tamu SET
            nama = '$nama',
            no_hp = '$no_hp',
            instansi = '$instansi',
            tujuan = '$tujuan',
            bertemu = '$bertemu',
            waktu_datang = '$waktu'
        WHERE id = $id
    ");

    if ($update) {
        header("Location: laporan.php?status=edit");
        exit;
    } else {
        $error = "Data gagal diperbarui: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Tamu - BUKU TAMU</title>

    <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .card {
            border-radius: 12px;
        }

        .card-header {
            background: #1e3a8a;
            color: white;
            border-radius: 12px 12px 0 0 !important;
        }

        .btn-primary {
            background-color: #1e3a8a !important;
            border-color: #1e3a8a !important;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit mr-2"></i>
                        Edit Data Tamu
                    </h4>
                </div>

                <div class="card-body">

                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="form-group">
                            <label>Nama Tamu</label>
                            <input 
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama']) ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            <input 
                                type="text"
                                name="no_hp"
                                class="form-control"
                                value="<?= htmlspecialchars($data['no_hp']) ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Instansi</label>
                            <input 
                                type="text"
                                name="instansi"
                                class="form-control"
                                value="<?= htmlspecialchars($data['instansi']) ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Tujuan</label>
                            <textarea
                                name="tujuan"
                                class="form-control"
                                rows="3"
                                required><?= htmlspecialchars($data['tujuan']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Bertemu Dengan</label>
                            <input 
                                type="text"
                                name="bertemu"
                                class="form-control"
                                value="<?= htmlspecialchars($data['bertemu']) ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Waktu Datang</label>
                            <input 
                                type="datetime-local"
                                name="waktu_datang"
                                class="form-control"
                                value="<?= date('Y-m-d\TH:i', strtotime($data['waktu_datang'])) ?>"
                                required>
                        </div>

                        <div class="mt-4">

                            <button 
                                type="submit" 
                                name="update"
                                class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
                            </button>

                            <a 
                                href="laporan.php"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>

</body>
</html>