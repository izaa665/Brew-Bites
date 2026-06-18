<?php
include 'auth.php';
include '../koneksi.php';

// Categories
$categories = [
    'hot_drinks' => 'Hot Drinks',
    'cold_drinks' => 'Cold Drinks',
    'heavy_meals' => 'Heavy Meals',
    'desserts' => 'Desserts'
];

// Combined Categories
$all_categories = array_merge(['all' => 'Semua Kategori'], $categories);
$current_kat = $_GET['kat'] ?? 'all';

// Combine all menus
$all_menus = [];
$total_menu = 0;
$tersedia = 0;
$hampir_habis = 0;
$stok_kosong = 0;

foreach ($categories as $table => $label) {
    $res = mysqli_query($conn, "SELECT *, '$table' as category_table FROM $table");
    while ($row = mysqli_fetch_assoc($res)) {
        $total_menu++;

        if ($row['stok'] > 5) {
            $tersedia++;
        } elseif ($row['stok'] > 0) {
            $hampir_habis++;
        } else {
            $stok_kosong++;
        }

        // Filter by category if selected
        if ($current_kat == 'all' || $current_kat == $table) {
            $all_menus[] = $row;
        }
    }
}

// Search functionality
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $all_menus = array_filter($all_menus, function ($m) use ($search) {
        return stripos($m['nama'], $search) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu - Cafe Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #6F4E37;
            --primary-light: #e1f5fe;
            --blue: #6F4E37;
            --bg: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 16px;
            --success: #16a34a;
            --warning: #ea580c;
            --danger: #ef4444;
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

        .btn-add {
            background: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.2);
        }

        .btn-add:hover {
            background: #4B3621;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(111, 78, 55, 0.3);
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-info h3 {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .stat-info .value {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .icon-total {
            background: #eff6ff;
            color: #3b82f6;
        }

        .icon-tersedia {
            background: #f0fdf4;
            color: #22c55e;
        }

        .icon-habis {
            background: #fff7ed;
            color: #f97316;
        }

        .icon-kosong {
            background: #fef2f2;
            color: #ef4444;
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

        .search-box {
            position: relative;
            width: 350px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #f8fafc;
            font-size: 0.9rem;
            outline: none;
            transition: 0.2s;
        }

        .search-box input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 16px;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 800;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 16px;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .menu-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
        }

        .menu-details h4 {
            font-weight: 700;
            font-size: 0.95rem;
        }

        .menu-details span {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .badge-kat {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #f1f5f9;
            color: #475569;
        }

        .stok-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .stok-tersedia {
            background: #dcfce7;
            color: #16a34a;
        }

        .stok-habis {
            background: #fef3c7;
            color: #d97706;
        }

        .stok-kosong {
            background: #fee2e2;
            color: #ef4444;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            background: white;
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-icon:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-icon.delete:hover {
            background: #fef2f2;
            color: var(--danger);
            border-color: var(--danger);
        }
        .category-filter {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .cat-tab {
            padding: 10px 20px;
            border-radius: 12px;
            background: white;
            border: 1px solid var(--border);
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            white-space: nowrap;
            transition: 0.2s;
        }

        .cat-tab:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .cat-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.2);
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <main>
        <header>
            <div class="header-title">
                <h2>Manajemen Menu & Stok</h2>
                <p>Kelola katalog produk, harga, dan ketersediaan stok harian Anda.</p>
            </div>
            <a href="tambah_menu.php" class="btn-add">
                <span>⊕</span> Tambah Menu Baru
            </a>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Menu</h3>
                    <div class="value">
                        <?= $total_menu; ?>
                    </div>
                </div>
                <div class="stat-icon icon-total">📋</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Tersedia</h3>
                    <div class="value">
                        <?= $tersedia; ?>
                    </div>
                </div>
                <div class="stat-icon icon-tersedia">✅</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Hampir Habis</h3>
                    <div class="value">
                        <?= $hampir_habis; ?>
                    </div>
                </div>
                <div class="stat-icon icon-habis">⚠️</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Stok Kosong</h3>
                    <div class="value">
                        <?= $stok_kosong; ?>
                    </div>
                </div>
                <div class="stat-icon icon-kosong">🚫</div>
            </div>
        </div>

        <div class="category-filter">
            <?php foreach ($all_categories as $val => $label): ?>
                <a href="?kat=<?= $val; ?>&search=<?= urlencode($search); ?>" 
                   class="cat-tab <?= $current_kat == $val ? 'active' : ''; ?>">
                    <?= $label; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="table-container">
            <div class="table-header">
                <form action="" method="GET" class="search-box">
                    <span style="position: absolute; left: 16px; top: 12px;">🔍</span>
                    <input type="text" name="search" placeholder="Cari menu, kategori, atau harga..."
                        value="<?= htmlspecialchars($search); ?>">
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok Hari Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($all_menus)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Tidak ada menu ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_menus as $m): ?>
                            <tr>
                                <td>
                                    <div class="menu-info">
                                        <img src="../img/<?= $m['gambar']; ?>" onerror="this.src='../img/default.jpg'"
                                            class="menu-img">
                                        <div class="menu-details">
                                            <h4>
                                                <?= $m['nama']; ?>
                                            </h4>
                                            <span>SKU:
                                                <?= strtoupper(substr($m['category_table'], 0, 2)) . '-' . str_pad($m['id'], 3, '0', STR_PAD_LEFT); ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-kat">
                                        <?= $categories[$m['category_table']]; ?>
                                    </span>
                                </td>
                                <td style="font-weight: 700;">Rp
                                    <?= number_format($m['harga'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?php if ($m['stok'] > 5): ?>
                                        <div class="stok-badge stok-tersedia">● Tersedia (
                                            <?= $m['stok']; ?>)
                                        </div>
                                    <?php elseif ($m['stok'] > 0): ?>
                                        <div class="stok-badge stok-habis">● Sisa
                                            <?= $m['stok']; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="stok-badge stok-kosong">● Habis</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="edit_menu.php?id=<?= $m['id']; ?>&kat=<?= $m['category_table']; ?>"
                                            class="btn-icon" title="Edit">
                                            <span>✏️</span>
                                        </a>
                                        <a href="proses_menu.php?action=delete&id=<?= $m['id']; ?>&kat=<?= $m['category_table']; ?>"
                                            class="btn-icon delete" title="Hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                            <span>🗑️</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Check for status messages in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('status')) {
            const status = urlParams.get('status');
            const msg = urlParams.get('msg');

            const toast = document.createElement('div');
            toast.style.position = 'fixed';
            toast.style.bottom = '40px';
            toast.style.right = '40px';
            toast.style.padding = '16px 24px';
            toast.style.borderRadius = '12px';
            toast.style.background = status === 'success' ? '#16a34a' : '#ef4444';
            toast.style.color = 'white';
            toast.style.fontWeight = '700';
            toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
            toast.style.zIndex = '1000';
            toast.style.display = 'flex';
            toast.style.alignItems = 'center';
            toast.style.gap = '12px';
            toast.innerHTML = `<span>${status === 'success' ? '✅' : '❌'}</span> ${msg}`;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s';
                setTimeout(() => toast.remove(), 500);
            }, 3000);

            // Clean URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>

</html>