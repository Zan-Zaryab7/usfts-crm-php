<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

if (!isset($_GET['id'])) {
    header("Location: invoices.php");
    exit;
}

$id = intval($_GET['id']);
$invoice = mysqli_query($conn, "
    SELECT i.*, o.code AS order_code, o.created_at AS order_date,
           c.name AS customer, c.company, c.email, c.phone, c.address
    FROM invoices i
    JOIN orders o ON i.order_id=o.id
    JOIN customers c ON o.customer_id=c.id
    WHERE i.id=$id
");
$invoice = mysqli_fetch_assoc($invoice);

if (!$invoice) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Invoice not found.</div></div>";
    include("../templates/footer.php");
    exit;
}

$order_lines = mysqli_query(
    $conn,
    "
    SELECT * FROM order_lines WHERE order_id=" . intval($invoice['order_id'])
);
?>

<div class="container mt-4" id="invoiceArea">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold">Invoice <?= $invoice['code'] ?></h4>
                    <p class="mb-1">Order #: <?= $invoice['order_code'] ?></p>
                    <p class="mb-1">Date: <?= date("m/d/Y", strtotime($invoice['created_at'])) ?></p>
                    <span class="badge bg-info"><?= $invoice['status'] ?></span>
                </div>
                <div class="text-end">
                    <h5 class="fw-bold">Your Company</h5>
                    <p class="mb-1">123 Business St</p>
                    <p class="mb-1">City, Country</p>
                    <p>Email: info@company.com</p>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold">Bill To:</h6>
                <p class="mb-1"><?= htmlspecialchars($invoice['customer']) ?>
                    (<?= htmlspecialchars($invoice['company']) ?>)</p>
                <p class="mb-1"><?= htmlspecialchars($invoice['email']) ?> | <?= htmlspecialchars($invoice['phone']) ?>
                </p>
                <p><?= nl2br(htmlspecialchars($invoice['address'])) ?></p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($line = mysqli_fetch_assoc($order_lines)) { ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($line['product']) ?></td>
                                <td><?= $line['qty'] ?></td>
                                <td><?= number_format($line['unit_price'], 2) ?></td>
                                <td><?= number_format($line['qty'] * $line['unit_price'], 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <h5>Total: $<?= number_format($invoice['total_amount'], 2) ?></h5>
                <p class="mb-1"><strong>Due Date:</strong> <?= $invoice['due_date'] ?></p>
                <?php if ($invoice['paid_date']) { ?>
                    <p class="text-success"><strong>Paid Date:</strong> <?= $invoice['paid_date'] ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <button onclick="window.print()" class="btn btn-outline-secondary">Print Invoice</button>
    <a href="invoices.php" class="btn btn-secondary">Back</a>
</div>

<?php include("../templates/footer.php"); ?>