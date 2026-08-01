<?php 
include "../config/koneksi.php";

// PROSES SIMPAN DATA (Jika Tombol Simpan diklik)
if(isset($_POST['tambah'])){
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $instansi   = mysqli_real_escape_string($conn, $_POST['instansi']);
    $no_hp      = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $tujuan     = mysqli_real_escape_string($conn, $_POST['tujuan']);
    $bertemu    = mysqli_real_escape_string($conn, $_POST['bertemu']);

    $query = mysqli_query($conn,"INSERT INTO tamu 
        (nama,instansi,no_hp,tujuan,bertemu,waktu_datang) 
        VALUES('$nama','$instansi','$no_hp','$tujuan','$bertemu',NOW())");

    if($query){
        echo "<script>alert('Terima kasih! Data sudah masuk.'); window.location='tamu_scan.php';</script>";
    }
}

// AMBIL DATA PEGAWAI UNTUK DROPDOWN
$pegawai = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Tamu Digital - DKPP</title>

    <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            padding: 15px;
        }
        .form-box {
            max-width: 500px;
            margin: 20px auto;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: none;
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            text-align: center;
            padding: 25px;
        }
        .form-control {
            border-radius: 12px;
            height: 45px;
        }
        .btn-primary {
            border-radius: 12px;
            height: 50px;
            font-weight: bold;
            font-size: 16px;
            background: #007bff;
        }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f8f9fa;
        }
        label {
            font-weight: 600;
            color: #495057;
            margin-left: 5px;
        }
        /* Penyesuaian Select2 agar bulat */
        .select2-container--default .select2-selection--single {
            border-radius: 12px;
            height: 45px;
            border: 1px solid #ced4da;
            padding-top: 8px;
        }
    </style>
</head>
<body>

<div class="form-box">
    <div class="card shadow">
        <div class="card-header">
            <h3 class="card-title" style="float: none; font-weight: 700;">
                <i class="fas fa-qrcode mr-2"></i> BUKU TAMU DIGITAL
            </h3>
            <p class="mb-0" style="opacity: 0.8;">Dinas Ketahanan Pangan dan Pertanian</p>
        </div>

        <div class="card-body p-4">
            <form method="POST">
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
                        </div>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Instansi / Alamat</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-building text-primary"></i></span>
                        </div>
                        <input type="text" name="instansi" class="form-control" placeholder="Contoh: Universitas Riau / Alamat" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>No HP (Aktif)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone text-primary"></i></span>
                        </div>
                        <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tujuan Kunjungan</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-info-circle text-primary"></i></span>
                        </div>
                        <input type="text" name="tujuan" class="form-control" placeholder="Keperluan kunjungan" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Bertemu Dengan</label>
                    <select name="bertemu" class="form-control select2" required>
                        <option value=""></option>
                        <?php while($p = mysqli_fetch_array($pegawai)){ ?>
                            <option value="<?= $p['nama'] ?>">
                                <?= $p['nama'] ?> (<?= $p['jabatan'] ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" name="tambah" class="btn btn-primary btn-block mt-4 shadow">
                    <i class="fas fa-paper-plane mr-2"></i> KIRIM DATA KUNJUNGAN
                </button>

            </form>
        </div>
        <div class="card-footer text-center bg-white border-0 pb-4">
            <small class="text-muted">© 2026 DKPP - Semua Data Tersimpan Secara Digital</small>
        </div>
    </div>
</div>

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "-- Pilih Pegawai --",
        allowClear: true,
        width: '100%'
    });
});
</script>

</body>
</html>