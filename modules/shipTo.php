<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

if (isset($_POST['add_shipTo'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $q = "INSERT INTO shipto (name, company, email, phone, address) 
          VALUES ('$name','$company','$email','$phone','$address')";
    mysqli_query($conn, $q);
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM shipto WHERE id='$id'");
}

$result = mysqli_query($conn, "SELECT * FROM shipto ORDER BY id DESC");
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Ship To</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newShipToModal">New</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ship To #</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($st = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($st['code']) ?></td>
                                <td><?= htmlspecialchars($st['name']) ?></td>
                                <td><?= htmlspecialchars($st['company']) ?></td>
                                <td><?= htmlspecialchars($st['email']) ?></td>
                                <td><?= htmlspecialchars($st['phone']) ?></td>
                                <td><?= nl2br(htmlspecialchars($st['address'])) ?></td>
                                <td>
                                    <a href="?delete=<?= $st['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Ship To?');">
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

<div class="modal fade" id="newShipToModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Ship To</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input class="form-control" name="name" placeholder="Name" required>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" name="company" placeholder="Company">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="email" name="email" placeholder="Email">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" name="phone" placeholder="Phone">
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" name="address" placeholder="Address" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" name="add_shipTo">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>