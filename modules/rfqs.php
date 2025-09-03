<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

$rfqs = mysqli_query($conn, "SELECT r.id, r.code, r.created_at, r.status, r.total_price, c.name AS customer 
                             FROM rfqs AS r 
                             JOIN customers c ON r.customer_id=c.id 
                             ORDER BY r.id DESC");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM rfqs WHERE id='$id'");
}
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>RFQs</h4>
        <div>
            <a href="add-rfq.php" class="btn btn-primary">New RFQ</a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>RFQs #</th>
                            <th>Creation Date</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($r = mysqli_fetch_assoc($rfqs)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($r['code']) ?></td>
                                <td><?= date("m/d/Y H:i", strtotime($r['created_at'])) ?></td>
                                <td><?= htmlspecialchars($r['customer']) ?></td>
                                <td><?= number_format($r['total_price'], 2) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= $r['status'] ?></span>
                                </td>
                                <td>
                                    <!-- <a href="#" onclick="window.print()" title="Print" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-printer"></i>
                                    </a> -->
                                    <a href="#" onclick="window.location='rfq_edit.php?id=<?= $r['id'] ?>'" title="Edit"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?delete=<?= $r['id'] ?>" title="Delete" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Qoutation?');">
                                        <i class="bi bi-trash2"></i>
                                    </a>
                                    <!-- <a href="#" title="Order" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </a> -->
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