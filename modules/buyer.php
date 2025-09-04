<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

if (isset($_POST['add_buyer'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $q = "INSERT INTO buyer (name, email, phone, address) VALUES ('$name','$email','$phone','$address')";
    mysqli_query($conn, $q);
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM buyer WHERE id='$id'");
}

$result = mysqli_query($conn, "SELECT * FROM buyer ORDER BY id DESC");
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Buyers</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newBuyerModal">New</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Buyer #</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($b = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($b['code']) ?></td>
                                <td><?= htmlspecialchars($b['name']) ?></td>
                                <td><?= htmlspecialchars($b['email']) ?></td>
                                <td><?= htmlspecialchars($b['phone']) ?></td>
                                <td><?= nl2br(htmlspecialchars($b['address'])) ?></td>
                                <td>
                                    <a href="?delete=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Buyer?');">
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

<div class="modal fade" id="newBuyerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Buyer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input class="form-control" name="name" placeholder="Name" required>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="email" name="email" placeholder="Email">
                        </div>
                        <div class="col-md-12">
                            <input class="form-control" name="phone" placeholder="Phone">
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" name="address" placeholder="Address" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" name="add_buyer">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>