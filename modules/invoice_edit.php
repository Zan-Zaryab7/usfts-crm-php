<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

$id = intval($_GET['id']);
$invoice = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM invoices WHERE id=$id
"));
$orders = mysqli_query($conn, "SELECT o.id,o.code,c.name FROM orders o JOIN customers c ON o.customer_id=c.id");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $total_amount = $_POST['total_amount'];
    $due_date = $_POST['due_date'];
    $paid_date = $_POST['paid_date'];

    mysqli_query($conn, "UPDATE invoices 
        SET order_id='$order_id', status='$status', 
            total_amount='$total_amount', due_date='$due_date', paid_date=" .
        ($paid_date ? "'$paid_date'" : "NULL") . " 
        WHERE id=$id");

    header("Location: invoices.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h5><i class="bi bi-arrow-left-circle" title="Back" onclick="history.go(-1); return false;"></i> Edit Invoice <?= htmlspecialchars($invoice['code']) ?></h5>
            <form method="post">
                <div class="mb-3">
                    <label>Order</label>
                    <select class="form-control" name="order_id" required>
                        <?php while ($o = mysqli_fetch_assoc($orders)) { ?>
                            <option value="<?= $o['id'] ?>" <?= $o['id'] == $invoice['order_id'] ? 'selected' : '' ?>>
                                <?= $o['code'] ?> - <?= htmlspecialchars($o['name']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option <?= $invoice['status'] == 'Draft' ? 'selected' : '' ?>>Draft</option>
                        <option <?= $invoice['status'] == 'Sent' ? 'selected' : '' ?>>Sent</option>
                        <option <?= $invoice['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                        <option <?= $invoice['status'] == 'Overdue' ? 'selected' : '' ?>>Overdue</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Total Amount</label>
                    <input type="number" min="0" step="0.01" name="total_amount" class="form-control"
                        value="<?= $invoice['total_amount'] ?>" required>
                </div>
                <div class="mb-3">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="<?= $invoice['due_date'] ?>">
                </div>
                <div class="mb-3">
                    <label>Paid Date</label>
                    <input type="date" name="paid_date" class="form-control" value="<?= $invoice['paid_date'] ?>">
                </div>
                <button class="btn btn-primary">Save</button>
                <button title="Cancel" onclick="history.go(-1); return false;" class="btn btn-secondary">Cancel</button>
            </form>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>