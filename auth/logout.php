<?php
session_start();
include "../config/koneksi.php";
include "../config/log_helper.php";

if(isset($_SESSION['user'])){
    catat_log($conn, $_SESSION['user'], $_SESSION['role'] ?? 'admin', 'Logout', 'Keluar dari sistem');
}

session_unset();
session_destroy();
header("Location: ../index.php");
exit;