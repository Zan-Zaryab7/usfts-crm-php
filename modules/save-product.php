<?php
include("../config/database.php");

$response = ["success" => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $sales_price = $_POST['sales_price'] ?? 0;
      $cost_price = $_POST['cost_price'] ?? 0;
      $catalog_file = null;

      if (isset($_FILES['catalog_file']) && $_FILES['catalog_file']['error'] == 0) {
            $uploadDir = "../uploads/catalogs/";
            if (!is_dir($uploadDir))
                  mkdir($uploadDir, 0777, true);

            $fileName = time() . "_" . basename($_FILES['catalog_file']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['catalog_file']['tmp_name'], $filePath)) {
                  $catalog_file = "uploads/catalogs/" . $fileName;
            }
      }

      $query = "INSERT INTO products (name, sales_price, cost_price, catalog_file) 
              VALUES ('$name', '$sales_price', '$cost_price', " . ($catalog_file ? "'$catalog_file'" : "NULL") . ")";

      if (mysqli_query($conn, $query)) {
            $id = mysqli_insert_id($conn);
            $response = [
                  "success" => true,
                  "id" => $id,
                  "name" => $name,
                  "sales_price" => $sales_price,
                  "cost_price" => $cost_price,
                  "catalog_file" => $catalog_file
            ];
      }
}

header("Content-Type: application/json");
echo json_encode($response);