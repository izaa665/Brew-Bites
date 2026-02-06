<?php
include 'koneksi.php';
echo "--- ORDERS ---\n";
$result = mysqli_query($conn, "DESCRIBE orders");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing orders\n";
}

echo "\n--- ORDER_ITEMS ---\n";
$result = mysqli_query($conn, "DESCRIBE order_items");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing order_items\n";
}
?>