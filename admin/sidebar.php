<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Assume auth.php is included by the parent file
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>
<style>
    /* Sidebar Styles */
    aside {
        width: 260px;
        background: var(--white);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        padding: 24px;
        position: fixed;
        height: 100vh;
        left: 0;
        top: 0;
        z-index: 100;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 40px;
        text-decoration: none;
        color: inherit;
    }

    .logo-img {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--primary);
    }

    .logo h1 {
        font-size: 1.1rem;
        font-weight: 800;
    }

    .logo span {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    nav {
        flex: 1;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        text-decoration: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: 12px;
        margin-bottom: 8px;
        transition: 0.2s;
    }

    .nav-item:hover {
        background: var(--primary-light);
        color: var(--primary);
    }

    .nav-item.active {
        background: linear-gradient(135deg, #d35400, #e67e22);
        color: white;
        box-shadow: 0 4px 12px rgba(211, 84, 0, 0.25);
    }

    .nav-item i {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }

    .profile-footer {
        border-top: 1px solid var(--border);
        padding-top: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #cbd5e1;
        overflow: hidden;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .name-job h4 {
        font-size: 0.85rem;
        font-weight: 700;
    }

    .name-job span {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
    }
</style>

<aside>
    <a href="index.php" class="logo">
        <img src="../img/logo.jpg" alt="Logo" class="logo-img"
            onerror="this.src='https://ui-avatars.com/api/?name=B&B&background=d35400&color=fff'">
        <div>
            <h1>Brew & Bites</h1>
            <span>Dashboard Pengelola</span>
        </div>
    </a>

    <nav>
        <a href="index.php"
            class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><i>📋</i> Daftar
            Pesanan</a>
        <a href="#" class="nav-item"><i>🍴</i> Manajemen Menu</a>
        <a href="laporan.php"
            class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : '' ?>"><i>📊</i> Laporan
            Penjualan</a>
        <a href="#" class="nav-item"><i>⚙️</i> Pengaturan</a>
    </nav>

    <div class="profile-footer">
        <div class="profile-info">
            <div class="avatar"><img
                    src="https://ui-avatars.com/api/?name=<?= urlencode($admin_name); ?>&background=dcfce7&color=16a34a"
                    alt="User">
            </div>
            <div class="name-job">
                <h4><?= $admin_name; ?></h4>
                <span>Manager</span>
            </div>
        </div>
        <a href="logout.php" style="text-decoration: none; color: inherit; font-size: 1.2rem;"
            title="Logout"><i>🚪</i></a>
    </div>
</aside>