<?php
include 'auth.php';
include '../koneksi.php';

// Filter logic (Sync with laporan.php)
$where = "WHERE 1=1";
$filter_title = "Laporan Penjualan (Seluruh Periode)";

if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
    $tgl = mysqli_real_escape_string($conn, $_GET['tanggal']);
    $where .= " AND DATE(waktu_pesan) = '$tgl'";
    $filter_title = "Laporan Penjualan Tanggal " . date('d M Y', strtotime($tgl));
}

// Stats
$query_stats = "SELECT 
    COUNT(*) as total_pesanan, 
    SUM(total_harga) as total_pendapatan,
    AVG(total_harga) as rata_rata
    FROM orders $where";
$res_stats = mysqli_query($conn, $query_stats);
$stats = mysqli_fetch_assoc($res_stats);

// Detailed Transactions
$query_trx = "
    SELECT o.*, GROUP_CONCAT(CONCAT(oi.jumlah, 'x ', oi.nama_produk) SEPARATOR ', ') as items
    FROM orders o
    LEFT JOIN order_items oi ON o.id_order = oi.id_order
    $where
    GROUP BY o.id_order
    ORDER BY o.waktu_pesan DESC
";
$transactions = mysqli_query($conn, $query_trx);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Sraddha Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #fff;
            padding: 50px;
            color: #1e293b;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #6F4E37;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }

        .brand h1 {
            color: #6F4E37;
            font-size: 1.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand p {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 4px;
        }

        .doc-info {
            text-align: right;
        }

        .doc-info h2 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .doc-info p {
            font-size: 0.85rem;
            color: #64748b;
        }

        .summary-table-container {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .summary-table {
            width: 350px;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            background: #fdfdfd;
        }

        .summary-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .summary-table tr:last-child td {
            border-bottom: 2px solid #6F4E37;
        }

        .summary-label {
            font-weight: 700;
            color: #64748b;
        }

        .summary-value {
            font-weight: 800;
            text-align: right;
            color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #f8fafc;
            text-align: left;
            padding: 12px 15px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            border: 1px solid #e2e8f0;
        }

        td {
            padding: 12px 15px;
            font-size: 0.85rem;
            border: 1px solid #e2e8f0;
        }

        .tr-even {
            background: #fafafa;
        }

        .footer {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            text-align: center;
        }

        .signature p {
            margin-bottom: 60px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .signature span {
            display: block;
            width: 200px;
            border-top: 1px solid #000;
            padding-top: 10px;
            font-weight: 800;
        }

        @media print {
            body {
                padding: 0;
            }

            .btn-print {
                display: none;
            }
        }

        .btn-print {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #6F4E37;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 30px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(111, 78, 55, 0.4);
            z-index: 1000;
        }
    </style>
</head>

<body>
    <button class="btn-print" onclick="window.print()">Cetak Halaman (Simpan ke PDF)</button>

    <div class="header">
        <div class="brand">
            <h1>Sraddha Coffee</h1>
            <p>Jalan Kopi No. 123, Indonesia</p>
        </div>
        <div class="doc-info">
            <h2>Laporan Penjualan</h2>
            <p><?= $filter_title; ?></p>
            <p>Dicetak pada: <?= date('d M Y, H:i'); ?></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Waktu</th>
                <th width="15%">ID Pesanan</th>
                <th>Item Terjual</th>
                <th width="15%">Metode</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($transactions)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d M Y, H:i', strtotime($row['waktu_pesan'])); ?></td>
                    <td style="font-weight: 600;">#<?= $row['order_number']; ?></td>
                    <td style="font-size: 0.8rem;"><?= $row['items']; ?></td>
                    <td><?= $row['payment_method']; ?></td>
                    <td style="font-weight: 700;">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="summary-table-container" style="margin-top: 30px; margin-bottom: 30px;">
        <table class="summary-table">
            <tr>
                <td class="summary-label">Total Penjualan</td>
                <td class="summary-value">Rp <?= number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td class="summary-label">Total Pesanan</td>
                <td class="summary-value"><?= number_format($stats['total_pesanan'] ?? 0, 0, ',', '.'); ?> Pesanan</td>
            </tr>
            <tr>
                <td class="summary-label">Rata-rata Transaksi</td>
                <td class="summary-value">Rp <?= number_format($stats['rata_rata'] ?? 0, 0, ',', '.'); ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div></div>
        <div class="signature">
            <p>Penanggung Jawab,</p>
            <span>Administrator</span>
        </div>
    </div>
</body>

</html>