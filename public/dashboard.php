<?php
include("../templates/header.php");
include("../templates/navbar.php");
include("../config/database.php");
include("../includes/auth.php");
check_auth();

$total_customers = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM customers"))[0];
$total_rfqs = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM rfqs"))[0];
$total_orders = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0];
$total_invoices = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM invoices"))[0];
$total_revenue = mysqli_fetch_row(mysqli_query($conn, "SELECT IFNULL(SUM(total_amount),0) FROM invoices WHERE status='Paid'"))[0];

$recent_rfqs = mysqli_query($conn, "
    SELECT r.id, r.code, r.created_at, r.status, c.name AS customer
    FROM rfqs r
    JOIN customers c ON r.customer_id=c.id
    ORDER BY r.id DESC LIMIT 5
");

$recent_invoices = mysqli_query($conn, "
    SELECT i.id, i.code, i.total_amount, i.status, i.created_at, c.name AS customer
    FROM invoices i
    JOIN orders o ON i.order_id=o.id
    JOIN customers c ON o.customer_id=c.id
    ORDER BY i.id DESC LIMIT 5
");
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">
        Dashboard
        <?php if (is_logged_in()) { ?>
            <small class="text-muted">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></small>
        <?php } ?>
    </h2>

    <div class="row g-4 mb-4">
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Customers</h6>
                    <h4><?= $total_customers ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">RFQs</h6>
                    <h4><?= $total_rfqs ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Orders</h6>
                    <h4><?= $total_orders ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Invoices</h6>
                    <h4><?= $total_invoices ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Revenue (Paid Invoices)</h6>
                    <h4>$<?= number_format($total_revenue, 2) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Recent RFQs</div>
                <div class="card-body p-0">
                    <table class="table mb-0 table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($r = mysqli_fetch_assoc($recent_rfqs)) { ?>
                                <tr onclick="window.location='../modules/rfq_edit.php?id=<?= $r['id'] ?>'"
                                    style="cursor:pointer;">
                                    <td><?= $r['code'] ?></td>
                                    <td><?= date("m/d/Y", strtotime($r['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($r['customer']) ?></td>
                                    <td><span class="badge bg-info"><?= $r['status'] ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Recent Invoices</div>
                <div class="card-body p-0">
                    <table class="table mb-0 table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($i = mysqli_fetch_assoc($recent_invoices)) { ?>
                                <tr onclick="window.location='../modules/view_invoice.php?id=<?= $i['id'] ?>'"
                                    style="cursor:pointer;">
                                    <td><?= $i['code'] ?></td>
                                    <td><?= htmlspecialchars($i['customer']) ?></td>
                                    <td>$<?= number_format($i['total_amount'], 2) ?></td>
                                    <td><span class="badge bg-info"><?= $i['status'] ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>