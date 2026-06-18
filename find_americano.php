<?php
require_once 'koneksi.php';

// Cari menu Americano Hot
echo "=== Mencari Americano Hot ===\n";
$query = "SELECT * FROM hot_drinks WHERE nama LIKE '%americano%' OR nama LIKE '%Americano%'";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id'] . "\n";
        echo "Nama: " . $row['nama'] . "\n";
        echo "Gambar saat ini: " . ($row['gambar'] ?? 'NULL') . "\n";
        echo "Harga: " . ($row['harga'] ?? 'N/A') . "\n";
        echo "---\n";
    }
} else {
    echo "Tidak ditemukan. Menampilkan semua hot_drinks:\n\n";
    $query2 = "SELECT * FROM hot_drinks ORDER BY id";
    $result2 = mysqli_query($conn, $query2);
    if (!$result2) {
        die("Query failed: " . mysqli_error($conn));
    }
    while ($row = mysqli_fetch_assoc($result2)) {
        echo "ID: " . $row['id'] . " | Nama: " . $row['nama'] . " | Gambar: " . ($row['gambar'] ?? 'NULL') . "\n";
    }
}

mysqli_close($conn);
?>