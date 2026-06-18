<?php
include 'koneksi.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$order_number = mysqli_real_escape_string($conn, $data['id']);
$order_type = mysqli_real_escape_string($conn, $data['orderType']);
$payment_method = mysqli_real_escape_string($conn, $data['paymentMethod']);
$subtotal = $data['subtotal'];
$total = $data['total'];
$table_no = isset($data['tableNo']) ? mysqli_real_escape_string($conn, $data['tableNo']) : '-';
$cust_name = isset($data['customerName']) ? mysqli_real_escape_string($conn, $data['customerName']) : 'Guest';
$notes = isset($data['catatan']) ? mysqli_real_escape_string($conn, $data['catatan']) : '';

try {
    // Insert Order
    $query = "INSERT INTO orders (order_number, nama_pelanggan, nomor_meja, total_harga, status_bayar, status_pesanan, order_type, payment_method, catatan) 
              VALUES ('$order_number', '$cust_name', '$table_no', $total, 'belum', 'menunggu', '$order_type', '$payment_method', '$notes')";

    if (mysqli_query($conn, $query)) {
        $order_id = mysqli_insert_id($conn);

        // Insert Items
        foreach ($data['items'] as $item) {
            $nama = mysqli_real_escape_string($conn, $item['nama']);

            // Append Level to Name if exists for DB record
            if (isset($item['level']) && !empty($item['level'])) {
                $lvl_info = " (" . $item['levelLabel'] . ": " . $item['level'] . ")";
                $nama .= mysqli_real_escape_string($conn, $lvl_info);
            }

            $harga = $item['harga'];
            $qty = $item['qty'];

            // We assume product ID handling is loose here as we are saving names handling. 
            mysqli_query($conn, "INSERT INTO order_items (id_order, nama_produk, harga_satuan, jumlah) 
                                 VALUES ($order_id, '$nama', $harga, $qty)");

            // Reduce Stock
            if (isset($item['pid']) && !empty($item['pid'])) {
                $pid_parts = explode('_', $item['pid']);
                $p_id = array_pop($pid_parts);
                $p_table = implode('_', $pid_parts);

                // Ensure table is one of the allowed product tables
                $allowed_tables = ['hot_drinks', 'cold_drinks', 'heavy_meals', 'desserts'];
                if (in_array($p_table, $allowed_tables)) {
                    mysqli_query($conn, "UPDATE $p_table SET stok = stok - $qty WHERE id = $p_id");
                }
            }
        }

        echo json_encode(['status' => 'success', 'order_id' => $order_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} catch (mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>