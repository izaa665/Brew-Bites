<?php
include 'koneksi.php';
$query_rekomendasi = mysqli_query($conn, "
    (SELECT id, nama, harga, gambar, 'hot_drinks' as kat FROM hot_drinks) UNION 
    (SELECT id, nama, harga, gambar, 'cold_drinks' as kat FROM cold_drinks) UNION
    (SELECT id, nama, harga, gambar, 'heavy_meals' as kat FROM heavy_meals) UNION
    (SELECT id, nama, harga, gambar, 'desserts' as kat FROM desserts) 
    ORDER BY RAND() LIMIT 3
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Brew & Bites - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding-bottom: 100px;
        }

        .hero {
            padding: 140px 20px;
            text-align: center;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            border-radius: 0 0 50px 50px;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.05;
            pointer-events: none;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -1px;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .card {
            border-radius: 20px;
            border: 1px solid #f0f0f0;
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
            height: 220px;
            object-fit: cover;
        }

        .btn-add {
            color: #d35400;
            cursor: pointer;
            font-weight: 700;
            border: 1.5px solid #d35400;
            padding: 5px 15px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #d35400;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(211, 84, 0, 0.25);
        }

        .btn-counter {
            display: none;
            align-items: center;
            gap: 12px;
            background: #d35400;
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
        }

        .qty-btn {
            cursor: pointer;
            border: none;
            background: none;
            color: white;
            font-weight: bold;
        }

        .btn-3d {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 5px 15px rgba(211, 84, 0, 0.3);
        }

        .btn-3d:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 30px rgba(211, 84, 0, 0.4);
            background: #e67e22 !important;
        }

        .btn-3d:active {
            transform: translateY(1px) scale(0.98);
            box-shadow: 0 5px 10px rgba(211, 84, 0, 0.2);
        }

        /* Card 3D Buttons */
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
        }

        .qty-btn {
            color: #2d3436 !important;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <section class="hero">
        <h1>Awali Harimu dengan Kopi Terbaik</h1>
        <p>Nikmati rasa otentik dari biji kopi pilihan yang diseduh dengan hati. Pesan sekarang dan rasakan bedanya.</p>
        <a href="menu.php" class="btn-3d"
            style="background:#d35400; color:white; padding:18px 50px; border-radius:50px; text-decoration:none; font-weight:800; display:inline-block; font-size:1.1rem;">Pesan
            Sekarang</a>
    </section>

    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 2.5rem; color: #2d3436; margin-bottom: 15px;">Menu Rekomendasi</h2>
        <div style="height: 4px; width: 60px; background: #d35400; margin: 0 auto; border-radius: 2px;"></div>
    </div>

    <div class="grid">
        <?php while ($row = mysqli_fetch_assoc($query_rekomendasi)): ?>
            <div class="card" data-id="<?= $row['kat'] . '_' . $row['id']; ?>" data-nama="<?= $row['nama']; ?>"
                data-harga="<?= $row['harga']; ?>" data-gambar="<?= $row['gambar']; ?>"
                onclick="openProductModal('<?= $row['kat'] . '_' . $row['id']; ?>', '<?= addslashes($row['nama']); ?>', '<?= $row['harga']; ?>', '<?= $row['gambar']; ?>', 'Menu Rekomendasi')">
                <div class="card-img"><img src="img/<?= $row['gambar']; ?>" onerror="this.src='img/default.jpg'"></div>
                <div style="padding:15px;">
                    <h3 style="margin:0; font-size:1.1rem; font-weight:800; color:#2d3436;"><?= $row['nama']; ?></h3>
                    <p
                        style="color:#636e72; font-size:0.75rem; margin:8px 0 12px 0; line-height:1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php
                        $kat = $row['kat'];
                        if (strpos($kat, 'heavy_meals') !== false) {
                            echo 'Hidangan utama yang mengenyangkan, dibuat dengan bumbu rahasia dapur yang otentik.';
                        } elseif (strpos($kat, 'drinks') !== false) {
                            echo 'Minuman segar pendamping setia santai Anda dengan racikan bahan berkualitas tinggi.';
                        } elseif (strpos($kat, 'desserts') !== false) {
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