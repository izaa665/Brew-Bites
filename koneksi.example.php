<?php
// Salin file ini menjadi koneksi.php dan sesuaikan konfigurasinya
$conn = mysqli_connect("localhost", "root", "", "db_cafe");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>