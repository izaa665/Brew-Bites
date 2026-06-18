<?php
include 'koneksi.php';
$tables = ['hot_drinks', 'cold_drinks', 'heavy_meals', 'desserts', 'orders', 'order_items'];
foreach ($tables as $table) {
    echo "--- $table ---\n";
    try {
        $result = mysqli_query($conn, "DESCRIBE $table");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['Field'] . " - " . $row['Type'] . "\n";
            }
        } else {
            echo "Error describing $table: " . mysqli_error($conn) . "\n";
        }
    } catch (Exception $e) {
        echo "Exception for $table: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
?>
