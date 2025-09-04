<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

if (isset($_POST['add_billTo'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $q = "INSERT INTO billTo (title, address) VALUES ('$title','$address')";
    mysqli_query($conn, $q);
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM billTo WHERE id='$id'");
}

$result = mysqli_query($conn, "SELECT * FROM billTo ORDER BY id DESC");
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Bill To</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newBillToModal">New</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Bill To #</th>
                            <th>Title</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($b = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($b['code']) ?></td>
                                <td><?= htmlspecialchars($b['title']) ?></td>
                                <td><?= nl2br(htmlspecialchars($b['address'])) ?></td>
                                <td>
                                    <a href="?delete=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Bill To?');">
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

<div class="modal fade" id="newBillToModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Bill To</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <input class="form-control" name="title" placeholder="Title" required>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" name="address" placeholder="Address" rows="3"
                                required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" name="add_billTo">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>