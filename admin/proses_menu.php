<?php
include 'auth.php';
include '../koneksi.php';

$action = $_GET['action'] ?? '';

if ($action == 'add') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kat = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $gambar = 'default.jpg';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../img/' . $gambar);
    }

    $query = "INSERT INTO $kat (nama, harga, stok, gambar) VALUES ('$nama', $harga, $stok, '$gambar')";
    if (mysqli_query($conn, $query)) {
        header("Location: manajemen_menu.php?status=success&msg=Menu berhasil ditambahkan");
    } else {
        header("Location: manajemen_menu.php?status=error&msg=Gagal menambahkan menu");
    }

} elseif ($action == 'edit') {
    $id = (int) $_POST['id'];
    $old_kat = mysqli_real_escape_string($conn, $_POST['old_kat']);
    $new_kat = mysqli_real_escape_string($conn, $_POST['kategori']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $old_gambar = $_POST['old_gambar'];
    $gambar = $old_gambar;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], '../img/' . $gambar)) {
            if ($old_gambar != 'default.jpg' && file_exists('../img/' . $old_gambar)) {
                unlink('../img/' . $old_gambar);
            }
        }
    }

    if ($old_kat == $new_kat) {
        $query = "UPDATE $old_kat SET nama = '$nama', harga = $harga, stok = $stok, gambar = '$gambar' WHERE id = $id";
        mysqli_query($conn, $query);
    } else {
        // Move to another table
        $query_insert = "INSERT INTO $new_kat (nama, harga, stok, gambar) VALUES ('$nama', $harga, $stok, '$gambar')";
        if (mysqli_query($conn, $query_insert)) {
            mysqli_query($conn, "DELETE FROM $old_kat WHERE id = $id");
        }
    }
    header("Location: manajemen_menu.php?status=success&msg=Perubahan berhasil disimpan");

} elseif ($action == 'delete') {
    $id = (int) $_GET['id'];
    $kat = mysqli_real_escape_string($conn, $_GET['kat']);

    // Get image first
    $res = mysqli_query($conn, "SELECT gambar FROM $kat WHERE id = $id");
    $data = mysqli_fetch_assoc($res);
    if ($data) {
        $gambar = $data['gambar'];
        if ($gambar != 'default.jpg' && file_exists('../img/' . $gambar)) {
            unlink('../img/' . $gambar);
        }
        mysqli_query($conn, "DELETE FROM $kat WHERE id = $id");
    }
    header("Location: manajemen_menu.php?status=success&msg=Menu berhasil dihapus");
} else {
    header("Location: manajemen_menu.php");
}
?>