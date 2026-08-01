<?php
include "../config/koneksi.php";

$mulai = $_GET['mulai'] ?? '';
$selesai = $_GET['selesai'] ?? '';

if(!$mulai || !$selesai){
    echo "Silakan pilih tanggal dulu!";
    exit;
}

$where = "WHERE DATE(waktu_datang) BETWEEN '$mulai' AND '$selesai'";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_$mulai-$selesai.xls");
?>

<h3>Rekapitulasi Pengunjung</h3>

<table border="1">
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
$data = mysqli_query($conn,"SELECT * FROM tamu $where ORDER BY id DESC");

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