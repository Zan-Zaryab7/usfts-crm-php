<?php
include("../config/database.php");

$term = mysqli_real_escape_string($conn, $_GET['term']);

$q = mysqli_query($conn, "SELECT id, name, sales_price, cost_price, catalog_file 
                          FROM products 
                          WHERE name LIKE '%$term%' LIMIT 10");

$results = [];
while ($row = mysqli_fetch_assoc($q)) {
    $results[] = $row;
}

echo json_encode($results);
