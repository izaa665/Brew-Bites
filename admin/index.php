<?php
include 'auth.php';
include '../koneksi.php';

// Stats logic
$today = date('Y-m-d');
$res_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE DATE(waktu_pesan) = '$today'");
$total_today = mysqli_fetch_assoc($res_count)['total'];

$res_revenue = mysqli_query($conn, "SELECT SUM(total_harga) as revenue FROM orders WHERE DATE(waktu_pesan) = '$today'");
$revenue_today = mysqli_fetch_assoc($res_revenue)['revenue'] ?? 0;

$res_pending = mysqli_query($conn, "SELECT COUNT(*) as pending FROM orders WHERE status_pesanan = 'menunggu'");
$total_pending = mysqli_fetch_assoc($res_pending)['pending'];

// For "Pengunjung Aktif", since we don't have session tracking yet, let's use a dynamic placeholder or count tables
$total_guests = 42; // Placeholder based on UI

// Fetch Orders with their items
$query_orders = "
    SELECT o.*, GROUP_CONCAT(CONCAT(oi.jumlah, 'x ', oi.nama_produk) SEPARATOR ', ') as items
    FROM orders o
    LEFT JOIN order_items oi ON o.id_order = oi.id_order
    WHERE DATE(o.waktu_pesan) = CURDATE()
    GROUP BY o.id_order
    ORDER BY o.waktu_pesan DESC
    LIMIT 6
";
$orders = mysqli_query($conn, $query_orders);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Admin - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #d35400;
            --primary-light: #fff5ec;
            --blue: #d35400;
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

        /* Main Content */
        main {
            flex: 1;
            margin-left: 260px;
            padding: 40px;
            min-width: 0;
            /* Prevent content from pushing sidebar */
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            font-size: 0.9rem;
            outline: none;
        }

        .notify-btn {
            width: 48px;
            height: 48px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
        }

        .notify-btn::after {
            content: '';
            position: absolute;
            top: 12px;
            right: 12px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border: 2px solid white;
            border-radius: 50%;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            position: relative;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 16px;
        }

        .icon-blue {
            background: #e0f2fe;
            color: var(--blue);
        }

        .icon-green {
            background: #dcfce7;
            color: #16a34a;
        }

        .icon-orange {
            background: #ffedd5;
            color: #ea580c;
        }

        .icon-purple {
            background: #f3e8ff;
            color: #9333ea;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            display: block;
        }

        .trend {
            position: absolute;
            top: 24px;
            right: 24px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .trend.up {
            color: #16a34a;
        }

        /* Table Section */
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

        .table-header h3 i {
            color: #ef4444;
            font-size: 0.6rem;
            vertical-align: middle;
            margin-left: 8px;
        }

        .view-all {
            color: var(--blue);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
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
            color: var(--blue);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .cust-name {
            font-weight: 700;
        }

        .cust-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: block;
            margin-top: 4px;
        }

        .table-badge {
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* Status Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .badge-pending {
            background: #fef3c7;
            color: #d97706;
        }

        /* Menunggu */
        .badge-process {
            background: #e0f2fe;
            color: var(--blue);
        }

        /* Diproses */
        .badge-done {
            background: #dcfce7;
            color: #16a34a;
        }

        /* Selesai */

        /* Buttons */
        .btn {
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-blue {
            background: var(--blue);
            color: white;
        }

        .btn-green {
            background: #16a34a;
            color: white;
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            font-size: 1.2rem;
            padding: 0;
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
            width: 32px;
            height: 32px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <main>
        <header>
            <div class="header-title">
                <h2>Kelola Pesanan</h2>
                <p>Selamat datang kembali. Cek pesanan masuk terbaru.</p>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <span style="position: absolute; left: 16px; top: 12px;">🔍</span>
                    <input type="text" placeholder="Cari ID Pesanan...">
                </div>
                <button class="notify-btn">🔔</button>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-blue">🛍️</div>
                <span class="stat-label">Total Pesanan Hari Ini</span>
                <span class="stat-value"><?= $total_today; ?> Pesanan</span>
                <div class="trend up">📈 +12%</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-green">💵</div>
                <span class="stat-label">Pendapatan Hari Ini</span>
                <span class="stat-value">Rp <?= number_format($revenue_today, 0, ',', '.'); ?></span>
                <div class="trend up">📈 +8.4%</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-orange">⏱️</div>
                <span class="stat-label">Pesanan Menunggu</span>
                <span class="stat-value"><?= $total_pending; ?> Pesanan</span>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-purple">👤</div>
                <span class="stat-label">Pengunjung Aktif</span>
                <span class="stat-value"><?= $total_guests; ?> Tamu</span>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Daftar Pesanan Masuk <i style="color:red; font-style: normal;">●</i></h3>
                <a href="#" class="view-all">Lihat Semua</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID PESANAN</th>
                        <th>TANGGAL</th>
                        <th>NAMA PELANGGAN</th>
                        <th>MEJA</th>
                        <th>ITEM PESANAN</th>
                        <th>TOTAL HARGA</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td class="order-id">#<?= $row['order_number']; ?></td>
                            <td>
                                <span style="font-weight: 600; font-size: 0.85rem; color: var(--text-main);">
                                    <?= date('d M Y', strtotime($row['waktu_pesan'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="cust-name"><?= $row['nama_pelanggan']; ?></span>
                                <span class="cust-time"><?= date('H:i A', strtotime($row['waktu_pesan'])); ?></span>
                            </td>
                            <td>
                                <div class="table-badge"><?= $row['nomor_meja']; ?></div>
                            </td>
                            <td style="max-width: 250px; color: var(--text-muted); font-size: 0.85rem;">
                                <?= $row['items']; ?>
                            </td>
                            <td style="font-weight: 700;">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php
                                $status = $row['status_pesanan'];
                                if ($status == 'menunggu')
                                    echo '<span class="badge badge-pending">Menunggu</span>';
                                elseif ($status == 'proses')
                                    echo '<span class="badge badge-process">Diproses</span>';
                                elseif ($status == 'selesai')
                                    echo '<span class="badge badge-done">Selesai</span>';
                                ?>
                            </td>
                            <td>
                                <?php if ($status == 'menunggu'): ?>
                                    <button class="btn btn-blue"
                                        onclick="updateStatus(<?= $row['id_order']; ?>, 'proses')">Proses</button>
                                <?php elseif ($status == 'proses'): ?>
                                    <button class="btn btn-green"
                                        onclick="updateStatus(<?= $row['id_order']; ?>, 'selesai')">Selesai</button>
                                <?php else: ?>
                                    <span
                                        style="color: var(--text-muted); font-weight: 700; font-size: 0.85rem; padding-left: 10px;">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="pagination">
                <span>Menampilkan 6 pesanan terbaru</span>
                <div class="pag-btns">
                    <button class="pag-btn">⟨</button>
                    <button class="pag-btn">⟩</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateStatus(id, newStatus) {
            if (confirm('Ubah status pesanan ini?')) {
                fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id_order=${id}&status=${newStatus}`
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert('Gagal update: ' + data.message);
                        }
                    })
                    .catch(err => alert('Error: ' + err));
            }
        }
    </script>
</body>

</html>