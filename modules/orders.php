<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

$orders = mysqli_query($conn, "
    SELECT o.*, c.name AS customer 
    FROM orders o
    JOIN customers c ON o.customer_id=c.id
    ORDER BY o.id DESC
");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id='$id'");
}
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Orders</h4>
        <!-- <a href="order_add.php" class="btn btn-primary">New Order</a> -->
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Created</th>
                            <th>Customer</th>
                            <th>Total Price</th>
                            <th>Profit</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($o = mysqli_fetch_assoc($orders)) { ?>
                            <tr onclick="window.location='order_edit.php?id=<?= $o['id'] ?>'">
                                <td><?= htmlspecialchars($o['code']) ?></td>
                                <td><?= date("m/d/Y", strtotime($o['created_at'])) ?></td>
                                <td><?= htmlspecialchars($o['customer']) ?></td>
                                <td><?= number_format($o['total_price'], 2) ?></td>
                                <td><?= number_format($o['profit'], 2) ?></td>
                                <td><span class="badge bg-info"><?= $o['status'] ?></span></td>
                                <td>
                                    <a href="?delete=<?= $o['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Order?');">
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