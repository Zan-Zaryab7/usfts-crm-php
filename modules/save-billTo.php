<?php
include("../includes/auth.php");
check_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $q = "INSERT INTO billTo (title, address) VALUES ('$title', '$address')";
    if (mysqli_query($conn, $q)) {
        $id = mysqli_insert_id($conn);
        echo json_encode(["success" => true, "id" => $id, "title" => $title]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>