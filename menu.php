<?php
include 'koneksi.php';
$kat = isset($_GET['kat']) ? $_GET['kat'] : 'cold_drinks';
$query = mysqli_query($conn, "SELECT * FROM $kat");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Menu - Brew & Bites</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding-bottom: 120px;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin: 40px 0;
        }

        .filter-btn {
            padding: 10px 22px;
            border-radius: 25px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #636e72;
            font-weight: 600;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background: #fff5ec;
            border-color: #d35400;
            color: #d35400;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(211, 84, 0, 0.15);
        }

        .filter-btn.active {
            background: #d35400;
            color: white;
            border-color: #d35400;
            box-shadow: 0 4px 15px rgba(211, 84, 0, 0.3);
        }

        .grid-menu {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .card {
            border: 1px solid #f0f0f0;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: white;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: #ffe0cc;
        }

        .card-img img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .btn-add {
            background: #d35400;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-add:hover {
            background: #e67e22;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(211, 84, 0, 0.25);
        }

        .btn-counter {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            height: 34px;
            display: none;
            align-items: center;
            padding: 0 8px;
            gap: 10px;
            color: #2d3436;
        }

        .qty-btn {
            cursor: pointer;
            font-weight: 800;
            border: none;
            background: none;
            color: #2d3436;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="filter-container">
        <a href="?kat=hot_drinks" class="filter-btn <?= $kat == 'hot_drinks' ? 'active' : '' ?>">Hot Drinks</a>
        <a href="?kat=cold_drinks" class="filter-btn <?= $kat == 'cold_drinks' ? 'active' : '' ?>">Cold Drinks</a>
        <a href="?kat=heavy_meals" class="filter-btn <?= $kat == 'heavy_meals' ? 'active' : '' ?>">Heavy Meals</a>
        <a href="?kat=desserts" class="filter-btn <?= $kat == 'desserts' ? 'active' : '' ?>">Desserts</a>
    </div>
    <div class="grid-menu">
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="card" data-id="<?= $kat . '_' . $row['id']; ?>" data-nama="<?= $row['nama']; ?>"
                data-harga="<?= $row['harga']; ?>" data-gambar="<?= $row['gambar']; ?>"
                onclick="openProductModal('<?= $kat . '_' . $row['id']; ?>', '<?= addslashes($row['nama']); ?>', '<?= $row['harga']; ?>', '<?= $row['gambar']; ?>', '<?= ucwords(str_replace('_', ' ', $kat)); ?>')">
                <div class="card-img"><img src="img/<?= $row['gambar']; ?>" onerror="this.src='img/default.jpg'"></div>
                <div style="padding:15px;">
                    <h3 style="margin:0; font-size:1.1rem; font-weight:800; color:#2d3436;"><?= $row['nama']; ?></h3>
                    <p
                        style="color:#636e72; font-size:0.75rem; margin:8px 0 12px 0; line-height:1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php
                        if ($kat == 'heavy_meals') {
                            echo 'Hidangan utama yang mengenyangkan, dibuat dengan bumbu rahasia dapur yang otentik.';
                        } elseif ($kat == 'hot_drinks' || $kat == 'cold_drinks') {
                            echo 'Minuman segar pendamping setia santai Anda dengan racikan bahan berkualitas tinggi.';
                        } elseif ($kat == 'desserts') {
                            echo 'Sajian penutup manis sempurna dengan tekstur lembut yang memanjakan lidah Anda.';
                        } else {
                            echo 'Pencuci mulut manis yang memanjakan lidah dengan cita rasa premium yang tak terlupakan.';
                        }
                        ?>
                    </p>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:auto;">
                        <span style="color:#d35400; font-weight:800; font-size:0.95rem;">Rp
                            <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                        <div class="btn-action" onclick="event.stopPropagation()">
                            <div class="btn-add" onclick="upd(this, 1)">+ Tambah</div>
                            <div class="btn-counter"><button class="qty-btn" onclick="upd(this, -1)">−</button><span
                                    class="qty-num"
                                    style="min-width:15px; text-align:center; font-weight:800; font-size:0.85rem;">0</span><button
                                    class="qty-btn" onclick="upd(this, 1)">+</button></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php include 'keranjang.php'; ?>
    <?php include 'detail_modal.php'; ?>
</body>

</html>