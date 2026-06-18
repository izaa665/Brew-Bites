<?php
include 'auth.php';
include '../koneksi.php';

$id = $_GET['id'] ?? 0;
$kat = $_GET['kat'] ?? '';

$categories = [
    'hot_drinks' => 'Hot Drinks',
    'cold_drinks' => 'Cold Drinks',
    'heavy_meals' => 'Heavy Meals',
    'desserts' => 'Desserts'
];

if (!isset($categories[$kat])) {
    header("Location: manajemen_menu.php");
    exit();
}

$query = "SELECT * FROM $kat WHERE id = $id";
$res = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($res);

if (!$data) {
    header("Location: manajemen_menu.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Cafe Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #6F4E37;
            --primary-light: #e1f5fe;
            --bg: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 16px;
            --danger: #ef4444;
            --danger-light: #fef2f2;
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

        .breadcrumb {
            display: flex;
            gap: 8px;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .breadcrumb a {
            text-decoration: none;
            color: var(--text-muted);
            transition: 0.2s;
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .form-container {
            background: white;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 40px;
            max-width: 1000px;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h2 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .form-header p {
            color: var(--text-muted);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 40px;
        }

        .upload-section h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
            overflow: hidden;
            background: #f8fafc;
        }

        .upload-area:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .upload-area i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            color: var(--primary);
        }

        .upload-area p {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-align: center;
            padding: 0 20px;
        }

        #image-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            /* Show existing image by default */
        }

        .input-group {
            margin-bottom: 24px;
        }

        .input-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-main);
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            outline: none;
            background: #f8fafc;
            transition: 0.2s;
        }

        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        .price-input {
            position: relative;
        }

        .price-input span {
            position: absolute;
            left: 16px;
            top: 12px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .price-input input {
            padding-left: 45px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            margin-top: 40px;
            padding-top: 32px;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: 0.2s;
            border: none;
        }

        .btn-cancel {
            background: white;
            border: 1px solid var(--border);
            color: var(--text-muted);
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        .btn-save {
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .delete-section {
            background: #fff5f5;
            border: 1px solid #fee2e2;
            border-radius: var(--radius);
            padding: 24px;
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-info h4 {
            color: #b91c1c;
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .delete-info p {
            color: #ef4444;
            font-size: 0.85rem;
        }

        .btn-delete {
            background: white;
            border: 1px solid #fee2e2;
            color: #ef4444;
            padding: 10px 20px;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <main>
        <div class="breadcrumb">
            <a href="index.php">Dashboard</a>
            <span>›</span>
            <a href="manajemen_menu.php">Manajemen Menu</a>
            <span>›</span>
            <span>Edit Menu</span>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h2>Form Edit Menu</h2>
                <p>Ubah informasi detail menu makanan dan minuman Anda dengan presisi.</p>
            </div>

            <form action="proses_menu.php?action=edit" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">
                <input type="hidden" name="old_kat" value="<?= $kat; ?>">
                <input type="hidden" name="old_gambar" value="<?= $data['gambar']; ?>">

                <div class="form-grid">
                    <div class="upload-section">
                        <h3>Upload Gambar Produk</h3>
                        <div class="upload-area" onclick="document.getElementById('gambar').click()">
                            <i>📸</i>
                            <p>Klik atau tarik gambar ke sini<br><small>Maks. 2MB (JPG, PNG)</small></p>
                            <img id="image-preview" src="../img/<?= $data['gambar']; ?>"
                                onerror="this.src='../img/default.jpg'">
                            <input type="file" name="gambar" id="gambar" style="display: none;" accept="image/*"
                                onchange="previewImage(this)">
                        </div>
                    </div>

                    <div class="inputs-section">
                        <div class="input-group">
                            <label for="nama">Nama Menu</label>
                            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($data['nama']); ?>"
                                required>
                        </div>

                        <div class="input-group">
                            <label for="kategori">Kategori</label>
                            <select name="kategori" id="kategori" required>
                                <?php foreach ($categories as $val => $label): ?>
                                    <option value="<?= $val; ?>" <?= $kat == $val ? 'selected' : ''; ?>>
                                        <?= $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($kat != ''): ?>
                                <small style="color: var(--warning); font-size: 0.75rem; margin-top: 4px; display: block;">
                                    * Mengubah kategori akan memindahkan data antar tabel.
                                </small>
                            <?php endif; ?>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="input-group">
                                <label for="harga">Harga</label>
                                <div class="price-input">
                                    <span>Rp</span>
                                    <input type="number" name="harga" id="harga" value="<?= $data['harga']; ?>"
                                        required>
                                </div>
                            </div>
                            <div class="input-group">
                                <label for="stok">Stok Tersedia</label>
                                <input type="number" name="stok" id="stok" value="<?= $data['stok']; ?>" required>
                            </div>
                        </div>

                        <div class="input-group" style="opacity: 0.6; pointer-events: none;">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi"
                                rows="4">Deskripsi diatur di database namun kolom belum ada di schema tabel default.</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="manajemen_menu.php" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save">
                        <span>💾</span> Simpan Perubahan
                    </button>
                </div>
            </form>

            <div class="delete-section">
                <div class="delete-info">
                    <h4>Hapus Menu</h4>
                    <p>Menu yang sudah dihapus tidak dapat dipulihkan kembali.</p>
                </div>
                <a href="proses_menu.php?action=delete&id=<?= $data['id']; ?>&kat=<?= $kat; ?>" class="btn btn-delete"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini secara permanen?')">
                    Hapus Permanen
                </a>
            </div>
        </div>
    </main>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>