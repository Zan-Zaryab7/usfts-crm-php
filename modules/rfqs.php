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
                            <th>Code</th>
                            <th>Creation Date</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($r = mysqli_fetch_assoc($rfqs)) { ?>
                            <tr style="cursor:pointer;" onclick="window.location='rfq_edit.php?id=<?= $r['id'] ?>'">
                                <td><?= htmlspecialchars($r['code']) ?></td>
                                <td><?= date("m/d/Y H:i", strtotime($r['created_at'])) ?></td>
                                <td><?= htmlspecialchars($r['customer']) ?></td>
                                <td><?= number_format($r['total_price'], 2) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= $r['status'] ?></span>
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