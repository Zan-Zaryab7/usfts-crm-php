<?php
include("../includes/auth.php");
check_auth();
header("Content-Type: application/json");

$response = ["success" => false];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = trim($_POST['name']);
    $company = trim($_POST['company']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (!empty($name)) {
        $q = "INSERT INTO shipTo (name, company, email, phone, address) 
              VALUES ('$name', '$company', '$email', '$phone', '$address')";

        if (mysqli_query($conn, $q)) {
            $id = mysqli_insert_id($conn);
            $response = [
                "success" => true,
                "id" => $id,
                "name" => $name
            ];
        }
    }
}

echo json_encode($response);
?>