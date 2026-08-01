<?php
$ip_address = "192.168.100.179"; 
$url_form = "http://192.168.1.10/si-tamu-dkpp/admin/tamu_scan.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak QR Code Tamu</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .qr-card { background: white; padding: 40px; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); text-align: center; border: 2px solid #007bff; }
        #qrcode { margin: 20px auto; display: flex; justify-content: center; }
        #qrcode img { border: 1px solid #eee; padding: 10px; border-radius: 10px; }
        .qr-card h2 { margin: 0; color: #1a1a1a; }
        .brand { font-weight: bold; color: #007bff; margin-top: 15px; }
        .btn-print { margin-top: 25px; padding: 12px 25px; background: #007bff; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; width: 100%; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

<div class="qr-card">
    <h2>SCAN DISINI</h2>
    <p>Silahkan scan untuk mengisi Buku Tamu</p>
    
    <div id="qrcode"></div>
    
    <div class="brand">
        DKPP<br>
        <span style="font-weight: normal; color: #888; font-size: 13px;">Dinas Ketahanan Pangan dan Pertanian</span>
    </div>
    
    <button class="btn-print" onclick="window.print()">Cetak QR Code</button>
</div>

<script>
    // Generate QR Code otomatis saat halaman dibuka
    new QRCode(document.getElementById("qrcode"), {
        text: "<?= $url_form ?>",
        width: 250,
        height: 250,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
</script>

</body>
</html>