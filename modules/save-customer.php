<?php
include("../config/database.php");
include("../includes/auth.php");
check_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $tax_id = $_POST['tax_id'];
    $address = $_POST['address'];

    $q = "INSERT INTO customers (name, company, email, phone, tax_id, address) 
          VALUES ('$name','$company','$email','$phone','$tax_id','$address')";
    if (mysqli_query($conn, $q)) {
        $id = mysqli_insert_id($conn);
        echo json_encode(["success" => true, "id" => $id, "name" => $name]);
    } else {
        echo json_encode(["success" => false]);
    }
}
