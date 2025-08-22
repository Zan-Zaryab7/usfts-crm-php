<?php
include("../includes/auth.php");

$error = "";

// If already logged in, redirect to dashboard
if (is_logged_in()) {
    header("Location: {$base_url}dashboard.php");
    exit();
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password)) {
        header("Location: {$base_url}dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - CRM</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="card p-4 shadow-sm border-0">
                    <h3 class="mb-3 text-center">CRM Login</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="post" novalidate>
                        <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
                        <input type="password" name="password" class="form-control mb-2" placeholder="Password"
                            required>
                        <button class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>