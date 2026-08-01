<?php
session_start();
include "../config/koneksi.php";

/* CEK LOGIN */
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

/* CEK ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: laporan.php");
    exit;
}

$id = intval($_GET['id']);

/* CEK DATA TERLEBIH DAHULU */
$cek = mysqli_query($conn, "SELECT id FROM tamu WHERE id = $id");

if (!$cek) {
    die("Query pengecekan gagal: " . mysqli_error($conn));
}

if (mysqli_num_rows($cek) == 0) {
    die("Data tamu dengan ID $id tidak ditemukan.");
}

/* HAPUS DATA */
$hapus = mysqli_query($conn, "DELETE FROM tamu WHERE id = $id");

if ($hapus) {

    header("Location: laporan.php?status=hapus");
    exit;

} else {

    die("Data gagal dihapus.<br><br>
        Error MySQL: " . mysqli_error($conn));
}
?>