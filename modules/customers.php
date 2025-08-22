<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $tax_id = $_POST['tax_id'];
    $address = $_POST['address'];

    $q = "INSERT INTO customers (name,company,email,phone,tax_id,address) 
          VALUES ('$name','$company','$email','$phone','$tax_id','$address')";
    mysqli_query($conn, $q);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM customers WHERE id='$id'");
}

$result = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC");
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Customers</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCustomerModal">New</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Tax ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($c = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $c['id'] ?></td>
                                <td><?= htmlspecialchars($c['name']) ?></td>
                                <td><?= htmlspecialchars($c['company']) ?></td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td><?= htmlspecialchars($c['phone']) ?></td>
                                <td><?= htmlspecialchars($c['tax_id']) ?></td>
                                <td>
                                    <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this customer?');">
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

<!-- New Customer Modal -->
<div class="modal fade" id="newCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><input class="form-control" name="name" placeholder="Name" required></div>
                        <div class="col-md-6"><input class="form-control" name="company" placeholder="Company" required>
                        </div>
                        <div class="col-md-6"><input class="form-control" type="email" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="col-md-6"><input class="form-control" name="phone" placeholder="Phone" required>
                        </div>
                        <div class="col-md-6"><input class="form-control" name="tax_id" placeholder="Tax ID" required>
                        </div>
                        <div class="col-md-12"><textarea class="form-control" name="address" placeholder="Address"
                                required></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" name="add_customer">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>