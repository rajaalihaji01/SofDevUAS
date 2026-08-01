<?php
include "../config/koneksi.php";

$mulai = $_GET['mulai'] ?? '';
$selesai = $_GET['selesai'] ?? '';

if(!$mulai || !$selesai){
    echo "Silakan pilih tanggal dulu!";
    exit;
}

$where = "WHERE DATE(waktu_datang) BETWEEN '$mulai' AND '$selesai'";

$data = mysqli_query($conn,"SELECT * FROM tamu $where ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>PDF Laporan</title>
<style>
body{ font-family: Arial; }
table{
    width:100%;
    border-collapse: collapse;
}
table, th, td{
    border:1px solid black;
}
th, td{
    padding:8px;
    text-align:left;
}
</style>
</head>

<body onload="window.print()">

<h3>Rekapitulasi Pengunjung</h3>

<table>
<tr>
<th>No</th>
<th>Nama</th>
<th>Instansi</th>
<th>Tujuan</th>
<th>Bertemu</th>
<th>Tanggal</th>
</tr>

<?php
$no=1;
while($d = mysqli_fetch_assoc($data)){
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $d['nama'] ?></td>
<td><?= $d['instansi'] ?></td>
<td><?= $d['tujuan'] ?></td>
<td><?= $d['bertemu'] ?></td>
<td><?= date('d-m-Y H:i', strtotime($d['waktu_datang'])) ?></td>
</tr>

<?php } ?>

</table>

</body>
</html>