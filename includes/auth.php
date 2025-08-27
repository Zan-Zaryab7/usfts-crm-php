<?php
include(__DIR__ . "/../config/database.php");
include(__DIR__ . "/../config/config.php");

function login($name_email, $password)
{
    global $conn;
    $name_email = mysqli_real_escape_string($conn, $name_email);
    $password = mysqli_real_escape_string($conn, $password);

    $q = "SELECT * FROM users 
      WHERE (username='$name_email' OR email='$name_email') 
      AND password=MD5('$password')";

    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['useremail'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        return true;
    }
    return false;
}

function check_auth()
{
    global $base_url;
    if (!isset($_SESSION['user_id'])) {
        header("Location: {$base_url}login.php");
        exit();
    }
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function logout()
{
    global $base_url;
    session_unset();
    session_destroy();
    header("Location: {$base_url}login.php");
    exit();
}
