<?php
// include("../config/database.php");
include("../includes/auth.php");
check_auth();

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM sales WHERE id='$id'");
header("Location: sales.php");
exit;
