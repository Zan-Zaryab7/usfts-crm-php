<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

$customers = mysqli_query($conn, "SELECT * FROM customers");

if (isset($_POST['create_rfq'])) {
    $customer_id = $_POST['customer_id'];
    $total = $_POST['total'];

    $q = "INSERT INTO rfqs (customer_id, status, total_price, created_at) 
          VALUES ('$customer_id','Open','$total',NOW())";
    mysqli_query($conn, $q);

    $rfq_id = mysqli_insert_id($conn);

    header("Location: rfq_edit.php?id=$rfq_id");
    exit;
}
?>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-3">Create New RFQ</h5>
            <form method="post">
                <div class="mb-3">
                    <label>Customer</label>
                    <select class="form-control" name="customer_id" required>
                        <option value="">Select Customer</option>
                        <?php while ($c = mysqli_fetch_assoc($customers)) { ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php } ?>
                        <option value="add_new">- Add Customer -</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Total Amount</label>
                    <input type="number" min="0" step="0.01" class="form-control" name="total" required>
                </div>
                <button class="btn btn-primary" name="create_rfq">Save RFQ</button>
                <a href="rfqs.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include("add-customer-model.php"); ?>
<?php include("../templates/footer.php"); ?>