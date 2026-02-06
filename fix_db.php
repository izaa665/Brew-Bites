<?php
include 'koneksi.php';

$query = "ALTER TABLE orders ADD COLUMN status_pesanan ENUM('menunggu', 'proses', 'selesai') DEFAULT 'menunggu' AFTER status_bayar";

if (mysqli_query($conn, $query)) {
    echo "Successfully added status_pesanan column.\n";
} else {
    echo "Error adding column: " . mysqli_error($conn) . "\n";
}
?>