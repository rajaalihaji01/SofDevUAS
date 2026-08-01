<?php 
session_start();
include "config/koneksi.php";

$sukses = false;

if(isset($_POST['tambah'])){
    $nama       = $_POST['nama'];
    $instansi   = $_POST['instansi'];
    $no_hp      = $_POST['no_hp'];
    $tujuan     = $_POST['tujuan'];
    $bertemu    = $_POST['bertemu'];

    mysqli_query($conn,"INSERT INTO tamu 
    (nama,instansi,no_hp,tujuan,bertemu,waktu_datang) 
    VALUES('$nama','$instansi','$no_hp','$tujuan','$bertemu',NOW())");

    $sukses = true;
}
$pegawai = mysqli_query($conn, "SELECT * FROM pegawai");
?>

<!DOCTYPE html>
<html>
<head>
<title>Input Tamu</title>

<link rel="stylesheet" href="assets/adminlte/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="assets/adminlte/dist/css/adminlte.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
body{
    background:#f4f6f9;
    font-family:'Poppins', sans-serif;
}

/* CARD */
.card{
    width:500px;
    margin:60px auto;
    border-radius:15px;
    border:none;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* HEADER */
.card-header{
    background:#007bff;
    color:white;
    font-weight:600;
    text-align:center;
    border-radius:15px 15px 0 0;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.btn-logout{
    position:absolute;
    top:0;
    right:0;
    height:100%;
    display:flex;
    align-items:center;
    background:rgba(0,0,0,.12);
    color:white;
    border:none;
    border-left:1px solid rgba(255,255,255,.3);
    border-radius:0 15px 0 0;
    padding:0 20px;
    font-size:13px;
    font-weight:500;
    text-decoration:none;
    transition:.2s;
}

.btn-logout:hover{
    background:rgba(255,255,255,.3);
    color:white;
}

/* INPUT */
.form-control{
    border-radius:10px;
    padding:12px;
}

.form-control:focus{
    box-shadow:0 0 0 2px rgba(0,123,255,0.2);
}

/* BUTTON */
.btn-custom{
    background:#007bff;
    color:white;
    border:none;
    border-radius:10px;
    padding:12px;
    font-weight:500;
    transition:0.3s;
}

.btn-custom:hover{
    background:#0056b3;
}

/* ALERT BIRU */
.alert-custom{
    background:#e7f1ff;
    color:#0d6efd;
    border:none;
    border-radius:10px;
}

/* SELECT2 */
.select2-container .select2-selection--single{
    height:45px !important;
    border-radius:10px !important;
}

.select2-selection__rendered{
    line-height:45px !important;
}
.form-group label{
    font-weight:500;
    margin-bottom:5px;
}

.card{
    max-width:700px;
    margin:40px auto;
}
</style>

</head>

<body>

<div class="card">

<div class="card-header">
    <span><i class="fas fa-book"></i> Input Buku Tamu</span>
    <a href="auth/logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?');">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<div class="card-body">

<h4 class="text-center mb-2 font-weight-bold">
    Form Input Data Tamu
</h4>

<?php if($sukses){ ?>
<div class="alert alert-custom text-center">
    ✅ Data tamu berhasil ditambahkan!
</div>

<div class="text-center mb-5">
    <a href="auth/logout.php" class="btn btn-primary">
        ⬅ Back to Login Form
    </a>
</div>
<?php } ?>

<form method="POST">

<div class="form-group">
<label>Nama Tamu</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="form-group">
<label> Instansi </label>
<input type="text" name="instansi" class="form-control" required>
</div>

<div class="form-group">
<label>Tujuan</label>
<input type="text" name="tujuan" class="form-control" required>
</div>

<div class="form-group">
<label>No HP</label>
<input type="text" name="no_hp" class="form-control" required>
</div>

<div class="form-group">
<label>Bertemu Dengan</label>
<select name="bertemu" class="form-control select2" required>
<option value="">-- Pilih Pegawai --</option>
<?php while($p = mysqli_fetch_array($pegawai)){ ?>
<option value="<?= $p['nama'] ?>">
<?= $p['nama'] ?> - <?= $p['jabatan'] ?>
</option>
<?php } ?>
</select>
</div>

<button type="submit" name="tambah" class="btn btn-custom w-100 mt-3">
<i class="fas fa-paper-plane"></i> Submit
</button>

</form>

</div>
</div>

<script src="assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){
    $('.select2').select2({
        placeholder:"Cari Pegawai...",
        allowClear:true
    });
});
</script>

</body>
</html>