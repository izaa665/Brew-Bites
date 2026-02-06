<?php
include 'koneksi.php';

$queries = [
    "ALTER TABLE orders ADD COLUMN order_number VARCHAR(20) NOT NULL UNIQUE AFTER id_order",
    "ALTER TABLE orders ADD COLUMN order_type VARCHAR(20) NOT NULL DEFAULT 'dine_in' AFTER status_bayar",
    "ALTER TABLE orders ADD COLUMN payment_method VARCHAR(20) NOT NULL DEFAULT 'cash' AFTER order_type",
    "ALTER TABLE orders ADD COLUMN catatan TEXT AFTER payment_method",
    "ALTER TABLE orders MODIFY COLUMN id_order INT(11) AUTO_INCREMENT PRIMARY KEY"
];

foreach ($queries as $q) {
    if (mysqli_query($conn, $q)) {
        echo "Success: $q\n";
    } else {
        echo "Error/Skipped: " . mysqli_error($conn) . "\n";
    }
}
?>