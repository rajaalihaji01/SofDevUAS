<?php
session_start();
if(!isset($_SESSION['login'])){
    header("location:../index.php"); exit;
}
include '../config/koneksi.php';
include '../config/log_helper.php';

$nama = $_POST['nama'];
$instansi = $_POST['instansi'];
$tujuan = $_POST['tujuan'];
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam'];
$ttd = $_POST['ttd'];

// buat barcode unik
$barcode = uniqid('TAMU_');

// simpan ttd sebagai file png
$ttd_file = '../assets/ttd/'. $barcode . '.png';
list($type, $data) = explode(';', $ttd);
list(, $data) = explode(',', $data);
file_put_contents($ttd_file, base64_decode($data));

$stmt = $conn->prepare("INSERT INTO tamu (nama, instansi, tujuan, tanggal, jam, barcode, ttd_file) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssssss",$nama,$instansi,$tujuan,$tanggal,$jam,$barcode,$ttd_file);
$stmt->execute();

catat_log($conn, $_SESSION['user'] ?? $nama, $_SESSION['role'] ?? 'tamu', 'Tambah Data', "Mengisi buku tamu (dengan tanda tangan) untuk bertemu keperluan: $tujuan");

header("location:data_tamu.php");
exit;