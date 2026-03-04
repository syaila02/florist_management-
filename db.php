<?php
$host = "localhost";
$user = "florist_user";
$pass = "florist123";
$db   = "florist_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>
