<?php
include 'auth.php';
include '../koneksi.php';

// Filter logic (Sync with laporan.php)
$where = "WHERE 1=1";
$filename = "Laporan_Penjualan_" . date('Y-m-d') . ".csv";

if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
    $tgl = mysqli_real_escape_string($conn, $_GET['tanggal']);
    $where .= " AND DATE(waktu_pesan) = '$tgl'";
    $filename = "Laporan_Penjualan_" . $tgl . ".csv";
}

// Fetch Detailed Transactions
$query_trx = "
    SELECT o.waktu_pesan, o.order_number, o.nama_pelanggan, o.payment_method, o.total_harga,
    GROUP_CONCAT(CONCAT(oi.jumlah, 'x ', oi.nama_produk) SEPARATOR ', ') as items
    FROM orders o
    LEFT JOIN order_items oi ON o.id_order = oi.id_order
    $where
    GROUP BY o.id_order
    ORDER BY o.waktu_pesan DESC
";
$result = mysqli_query($conn, $query_trx);

// Set headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create file pointer connected to output stream
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, ['Waktu Pesan', 'ID Pesanan', 'Pelanggan', 'Item Terjual', 'Metode Pembayaran', 'Total Harga']);

// Fetch data and output as CSV rows
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['waktu_pesan'],
            '#' . $row['order_number'],
            $row['nama_pelanggan'],
            $row['items'],
            $row['payment_method'],
            $row['total_harga']
        ]);
    }
}

fclose($output);
exit;
?>