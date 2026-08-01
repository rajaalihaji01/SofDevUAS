<?php
/**
 * Mencatat satu baris aktivitas ke tabel log_activity.
 *
 * @param mysqli $conn       koneksi database yang sudah ada (dari koneksi.php)
 * @param string $pelaku     username admin/petugas ATAU nama tamu
 * @param string $peran      'admin' | 'petugas' | 'tamu'
 * @param string $aktivitas  contoh: 'Login', 'Logout', 'Tambah Data', 'Edit Data', 'Hapus Data', 'Ganti Password'
 * @param string $keterangan detail tambahan, contoh: 'Menambahkan tamu: Budi Santoso'
 */
function catat_log($conn, $pelaku, $peran, $aktivitas, $keterangan = ''){
    $pelaku     = mysqli_real_escape_string($conn, $pelaku);
    $peran      = mysqli_real_escape_string($conn, $peran);
    $aktivitas  = mysqli_real_escape_string($conn, $aktivitas);
    $keterangan = mysqli_real_escape_string($conn, $keterangan);
    $ip         = mysqli_real_escape_string($conn, $_SERVER['REMOTE_ADDR'] ?? '-');

    mysqli_query($conn, "INSERT INTO log_activity 
        (pelaku, peran, aktivitas, keterangan, ip_address) 
        VALUES ('$pelaku','$peran','$aktivitas','$keterangan','$ip')");
}