<?php
include 'koneksi.php';
$tables = ['cold_drinks', 'heavy_meals', 'desserts'];
foreach ($tables as $table) {
    echo "--- $table ---\n";
    $result = mysqli_query($conn, "DESCRIBE $table");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . " - " . $row['Key'] . " - " . $row['Default'] . " - " . $row['Extra'] . "\n";
        }
    } else {
        echo "Error describing $table: " . mysqli_error($conn) . "\n";
    }
    echo "\n";
}
?>
