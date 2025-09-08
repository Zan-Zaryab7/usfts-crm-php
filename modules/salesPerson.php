<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

if (isset($_POST['add_salesPerson'])) {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $signaturePath = null;

    if (!empty($_FILES['signature']['name'])) {
        $uploadDir = "../uploads/signatures/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['signature']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['signature']['tmp_name'], $targetFile)) {
            $signaturePath = "uploads/signatures/" . $fileName;
        }
    }

    $q = "INSERT INTO salesperson (name, title, email, phone, signature) 
          VALUES ('$name','$title','$email','$phone','$signaturePath')";

    mysqli_query($conn, $q);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sig = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM salesperson WHERE id='$id'"));
    if ($sig && !empty($sig['signature']) && file_exists("../" . $sig['signature'])) {
        unlink("../" . $sig['signature']);
    }

    mysqli_query($conn, "DELETE FROM salesperson WHERE id='$id'");
}

$result = mysqli_query($conn, "SELECT * FROM salesperson ORDER BY id DESC");
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Sales Person</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newSalesPersonModal">New</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sales Person #</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Signature</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($sp = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $sp['code'] ?></td>
                                <td><?= htmlspecialchars($sp['name']) ?></td>
                                <td><?= htmlspecialchars($sp['title']) ?></td>
                                <td><?= htmlspecialchars($sp['email']) ?></td>
                                <td><?= htmlspecialchars($sp['phone']) ?></td>
                                <td>
                                    <?php if (!empty($sp['signature'])) { ?>
                                        <img src="../<?= htmlspecialchars($sp['signature']) ?>" width="100px" alt="Signature">
                                    <?php } else { ?>
                                        <span class="text-muted">No Signature</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="?delete=<?= $sp['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Sales Person?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newSalesPersonModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Sales Person</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><input class="form-control" name="name" placeholder="Name" required></div>
                        <div class="col-md-6"><input class="form-control" name="title" placeholder="Title" required>
                        </div>
                        <div class="col-md-6"><input class="form-control" type="email" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="col-md-6"><input class="form-control" name="phone" placeholder="Phone" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Signature</label>
                            <input type="file" class="form-control" name="signature" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" name="add_salesPerson">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>