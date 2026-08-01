<?php
include "../config/koneksi.php";

// Ambil data semua pegawai
$query = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Daftar Pegawai</title>
    <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">
    <style>
        body { background: white; font-family: 'Source Sans Pro', sans-serif; }
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .table thead th { background-color: #f4f6f9 !important; color: black !important; border: 1px solid #dee2e6 !important; }
        @media print {
            .no-print { display: none; }
            @page { margin: 2cm; }
        }
    </style>
</head>
<body>

<div class="container-fluid p-4">
    <div class="no-print mb-4">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Sekarang</button>
        <a href="pegawai.php" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="text-center kop-surat">
        <h3 class="mb-0 font-weight-bold">DINAS KETAHANAN PANGAN DAN PERTANIAN (DKPP)</h3>
        <p class="mb-0">Daftar Seluruh Pegawai / Pejabat</p>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th width="5%">No</th>
                <th width="25%">NIP</th>
                <th>Nama Lengkap</th>
                <th width="30%">Jabatan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($d = mysqli_fetch_array($query)){ 
            ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center"><?= $d['nip']; ?></td>
                <td><?= $d['nama']; ?></td>
                <td><?= $d['jabatan']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-8"></div>
        <div class="col-4 text-center">
            <p>Dicetak pada: <?= date('d F Y'); ?></p>
            <br><br><br>
            <p class="font-weight-bold">( _________________________ )</p>
            <p>Administrator</p>
        </div>
    </div>
</div>

<script>
    // Otomatis buka jendela print saat halaman selesai dimuat
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>