<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

$orders = mysqli_query($conn, "SELECT o.id,o.code,c.name FROM orders o JOIN customers c ON o.customer_id=c.id");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $total_amount = $_POST['total_amount'];
    $due_date = $_POST['due_date'];

    mysqli_query($conn, "INSERT INTO invoices (order_id,status,total_amount,due_date) 
                VALUES ('$order_id','$status','$total_amount','$due_date')");
    header("Location: invoices.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h5><i class="bi bi-arrow-left-circle" title="Back" onclick="history.go(-1); return false;"></i> Create
                Invoice</h5>
            <form method="post">
                <div class="mb-3">
                    <label>Order</label>
                    <select class="form-control" name="order_id" required>
                        <option value="">Select Order</option>
                        <?php while ($o = mysqli_fetch_assoc($orders)) { ?>
                            <option value="<?= $o['id'] ?>"><?= $o['code'] ?> - <?= htmlspecialchars($o['name']) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option>Draft</option>
                        <option>Sent</option>
                        <option>Paid</option>
                        <option>Overdue</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Total Amount</label>
                    <input type="number" min="0" step="0.01" name="total_amount" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>
                <button class="btn btn-primary">Save</button>
                <button title="Cancel" onclick="history.go(-1); return false;" class="btn btn-secondary">Cancel</button>
            </form>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>