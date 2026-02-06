<?php
include 'auth.php';
include '../koneksi.php';

// Filter logic
$where = "WHERE 1=1";
$filter_desc = "Analisis performa penjualan cafe Anda.";

if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
    $tgl = mysqli_real_escape_string($conn, $_GET['tanggal']);
    $where .= " AND DATE(waktu_pesan) = '$tgl'";
    $filter_desc = "Laporan Penjualan tanggal " . date('d M Y', strtotime($tgl));
}

// Stats for filtered period
$query_stats = "SELECT 
    COUNT(*) as total_pesanan, 
    SUM(total_harga) as total_pendapatan,
    AVG(total_harga) as rata_rata
    FROM orders $where";
$res_stats = mysqli_query($conn, $query_stats);
$stats = mysqli_fetch_assoc($res_stats);

$total_pendapatan = $stats['total_pendapatan'] ?? 0;
$total_pesanan = $stats['total_pesanan'] ?? 0;
$rata_rata = $stats['rata_rata'] ?? 0;

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Brew & Bites</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #d35400;
            --primary-light: #fff5ec;
            --secondary: #e67e22;
            --bg: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        main {
            flex: 1;
            margin-left: 260px;
            padding: 40px;
            min-width: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
        }

        .header-title h2 {
            font-size: 1.6rem;
            font-weight: 800;
        }

        .header-title p {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .filter-container {
            background: white;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 32px;
            margin-bottom: 32px;
            display: flex;
            justify-content: center;
        }

        .filter-form {
            display: flex;
            align-items: flex-end;
            gap: 20px;
            width: 100%;
            max-width: 600px;
        }

        .filter-group {
            position: relative;
            flex: 1;
        }

        .filter-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .filter-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            outline: none;
            background: #f8fafc;
            cursor: pointer;
            transition: 0.2s;
            height: 52px;
        }

        .filter-group input:focus {
            border-color: var(--primary);
            background: white;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            height: 52px;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(211, 84, 0, 0.2);
        }

        .btn-print:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 28px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
            border-color: var(--primary);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .icon-sales {
            background: #fff7ed;
            color: #ea580c;
        }

        .icon-orders {
            background: #f0fdf4;
            color: #16a34a;
        }

        .icon-avg {
            background: #eff6ff;
            color: #2563eb;
        }

        .stat-trend {
            font-size: 0.75rem;
            font-weight: 700;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 20px;
            color: #16a34a;
        }

        .stat-label {
            display: block;
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-value {
            display: block;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .table-container {
            background: white;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 24px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .table-header h3 {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .table-actions {
            display: flex;
            gap: 12px;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: 0.2s;
        }

        .icon-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 16px;
            color: var(--text-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 800;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 20px 16px;
            font-size: 0.9rem;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .order-id {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .method-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .badge-qris {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-tunai {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-debit {
            background: #f1f5f9;
            color: #475569;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .pag-btns {
            display: flex;
            gap: 8px;
        }

        .pag-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: 0.2s;
            font-size: 1rem;
        }

        .pag-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        @media print {
            aside {
                display: none;
            }

            main {
                margin-left: 0;
                padding: 0;
            }

            .filter-container,
            .icon-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <main>
        <header>
            <div class="header-title">
                <h2>Laporan Penjualan</h2>
                <p>
                    <?= $filter_desc; ?>
                </p>
            </div>
            <button class="icon-btn"
                style="width: 48px; height: 48px; border-radius: 12px; font-size: 1.5rem;">🔔</button>
        </header>

        <div class="filter-container">
            <form action="" method="GET" class="filter-form">
                <div class="filter-group">
                    <label>Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? ''; ?>">
                </div>
                <button type="submit" class="btn-print" style="background: var(--primary);"><i>🔍</i> Cari Data</button>
            </form>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-sales">💰</div>
                    <div class="stat-trend" style="color: #16a34a;">📈 +12%</div>
                </div>
                <span class="stat-label">Total Penjualan</span>
                <span class="stat-value">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></span>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-orders">🛍️</div>
                    <div class="stat-trend" style="color: #16a34a;">📈 +8.4%</div>
                </div>
                <span class="stat-label">Total Pesanan</span>
                <span class="stat-value"><?= number_format($total_pesanan, 0, ',', '.'); ?> Pesanan</span>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-avg">📊</div>
                    <div class="stat-trend" style="color: #ef4444;">📉 -1.2%</div>
                </div>
                <span class="stat-label">Rata-rata Transaksi</span>
                <span class="stat-value">Rp <?= number_format($rata_rata, 0, ',', '.'); ?></span>
            </div>
        </div>


        <div class="table-container">
            <div class="table-header">
                <h3>Detail Transaksi Penjualan</h3>
                <div class="table-actions">
                    <a href="print_laporan.php?tanggal=<?= $_GET['tanggal'] ?? ''; ?>" target="_blank" class="btn-print"
                        style="padding: 0 16px; font-size: 0.85rem; background: #22c55e; text-decoration: none; display: inline-flex; height: 40px; align-items: center;">
                        <i>📥</i> Unduh PDF
                    </a>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>TANGGAL</th>
                        <th>ID PESANAN</th>
                        <th>ITEM TERJUAL</th>
                        <th>METODE PEMBAYARAN</th>
                        <th>TOTAL PENDAPATAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($transactions) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($transactions)): ?>
                            <tr>
                                <td style="font-weight: 500;">
                                    <?= date('d M Y, H:i', strtotime($row['waktu_pesan'])); ?>
                                </td>
                                <td>
                                    <a href="#" class="order-id">#
                                        <?= $row['order_number']; ?>
                                    </a>
                                </td>
                                <td style="max-width: 300px; color: var(--text-muted);">
                                    <?= $row['items']; ?>
                                </td>
                                <td>
                                    <?php
                                    $method = strtolower($row['payment_method']);
                                    $badge_class = "badge-" . $method;
                                    ?>
                                    <span class="method-badge <?= $badge_class; ?>">
                                        <?= $row['payment_method']; ?>
                                    </span>
                                </td>
                                <td style="font-weight: 800;">
                                    Rp
                                    <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination">
                <span>Menampilkan
                    <?= mysqli_num_rows($transactions); ?> pesanan terbaru
                </span>
                <div class="pag-btns">
                    <button class="pag-btn">⟨</button>
                    <button class="pag-btn">⟩</button>
                </div>
            </div>
        </div>

    </main>
</body>

</html>