<?php
include 'auth.php';
include '../koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_order = mysqli_real_escape_string($conn, $_POST['id_order']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate status
    $allowed_status = ['menunggu', 'proses', 'selesai'];
    if (!in_array($status, $allowed_status)) {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid']);
        exit;
    }

    $query = "UPDATE orders SET status_pesanan = '$status' WHERE id_order = '$id_order'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>