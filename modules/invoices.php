<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

$invoices = mysqli_query($conn, "
    SELECT i.*, o.code AS order_code, c.name AS customer
    FROM invoices i
    JOIN orders o ON i.order_id=o.id
    JOIN customers c ON o.customer_id=c.id
    ORDER BY i.id DESC
");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM invoices WHERE id='$id'");
}
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Invoices</h4>
        <a href="invoice_add.php" class="btn btn-primary">New Invoice</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Due</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($i = mysqli_fetch_assoc($invoices)) { ?>
                            <tr onclick="window.location='invoice_edit.php?id=<?= $i['id'] ?>'">
                                <td><?= $i['code'] ?></td>
                                <td><?= $i['order_code'] ?></td>
                                <td><?= htmlspecialchars($i['customer']) ?></td>
                                <td><?= number_format($i['total_amount'], 2) ?></td>
                                <td><span class="badge bg-info"><?= $i['status'] ?></span></td>
                                <td><?= $i['due_date'] ?></td>
                                <td>
                                    <a href="view_invoice.php?id=<?= $i['id'] ?>"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="?delete=<?= $i['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Invoice?');">
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

<?php include("../templates/footer.php"); ?>