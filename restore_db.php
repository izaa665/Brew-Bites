<?php
include 'koneksi.php';

$tables = ['order_items', 'orders', 'admins', 'hot_drinks', 'cold_drinks', 'heavy_meals', 'desserts'];

echo "--- Pembersihan Tabel ---\n";
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");
foreach ($tables as $table) {
    if (mysqli_query($conn, "DROP TABLE IF EXISTS $table")) {
        echo "Dropped $table. ";
    }
}
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
echo "\n";

echo "--- Pembuatan Tabel ---\n";
$queries = [
    "admins" => "CREATE TABLE admins (
        id_admin INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nama_lengkap VARCHAR(100) NOT NULL
    )",
    "hot_drinks" => "CREATE TABLE hot_drinks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        stok INT NOT NULL DEFAULT 0,
        gambar VARCHAR(255) DEFAULT 'default.jpg'
    )",
    "cold_drinks" => "CREATE TABLE cold_drinks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        stok INT NOT NULL DEFAULT 0,
        gambar VARCHAR(255) DEFAULT 'default.jpg'
    )",
    "heavy_meals" => "CREATE TABLE heavy_meals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        stok INT NOT NULL DEFAULT 0,
        gambar VARCHAR(255) DEFAULT 'default.jpg'
    )",
    "desserts" => "CREATE TABLE desserts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        harga INT NOT NULL,
        stok INT NOT NULL DEFAULT 0,
        gambar VARCHAR(255) DEFAULT 'default.jpg'
    )",
    "orders" => "CREATE TABLE orders (
        id_order INT AUTO_INCREMENT PRIMARY KEY,
        order_number VARCHAR(20) NOT NULL UNIQUE,
        nama_pelanggan VARCHAR(100) NOT NULL,
        nomor_meja VARCHAR(10) DEFAULT '-',
        total_harga INT NOT NULL,
        status_bayar ENUM('belum', 'sudah') DEFAULT 'belum',
        order_type VARCHAR(20) NOT NULL DEFAULT 'dine_in',
        payment_method VARCHAR(20) NOT NULL DEFAULT 'cash',
        catatan TEXT,
        tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "order_items" => "CREATE TABLE order_items (
        id_item INT AUTO_INCREMENT PRIMARY KEY,
        id_order INT NOT NULL,
        nama_produk VARCHAR(255) NOT NULL,
        harga_satuan INT NOT NULL,
        jumlah INT NOT NULL,
        FOREIGN KEY (id_order) REFERENCES orders(id_order) ON DELETE CASCADE
    )"
];

foreach ($queries as $name => $q) {
    if (mysqli_query($conn, $q)) {
        echo "Success creating $name\n";
    } else {
        echo "Error creating $name: " . mysqli_error($conn) . "\n";
    }
}

echo "\n--- Pengisian Data Bawaan ---\n";
$pass = password_hash('admin', PASSWORD_DEFAULT);
mysqli_query($conn, "INSERT INTO admins (username, password, nama_lengkap) VALUES ('admin', '$pass', 'Administrator Cafe')");

mysqli_query($conn, "INSERT INTO hot_drinks (nama, harga, stok, gambar) VALUES 
('Americano Hot', 18000, 50, 'hot-americano.jpg'),
('Cappuccino Hot', 25000, 40, 'hot-cappuccino.jpg'),
('Caramel Latte Hot', 28000, 30, 'hot-latte.jpg')");

mysqli_query($conn, "INSERT INTO cold_drinks (nama, harga, stok, gambar) VALUES 
('Iced Caffè Latte', 24000, 60, 'iced-latte.jpg'),
('Iced Americano', 20000, 80, 'iced-americano.jpg'),
('Matcha Cold Brew', 32000, 25, 'cold-brew.jpg')");

mysqli_query($conn, "INSERT INTO heavy_meals (nama, harga, stok, gambar) VALUES 
('Nasi Goreng Spesial', 35000, 20, 'nasi-goreng.jpg'),
('Fettuccine Carbonara', 45000, 15, 'pasta.jpg'),
('Beef Burger & Fries', 55000, 10, 'burger.jpg')");

mysqli_query($conn, "INSERT INTO desserts (nama, harga, stok, gambar) VALUES 
('Chocolate Brownies', 22000, 30, 'brownies.jpg'),
('Red Velvet Cake', 38000, 12, 'cake.jpg'),
('Tiramisu Jar', 35000, 18, 'tiramisu.jpg')");

echo "Restorasi database selesai!\n";
?>
