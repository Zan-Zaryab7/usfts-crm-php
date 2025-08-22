<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

$rfq_id = intval($_GET['id']);

$rfq = mysqli_fetch_assoc(mysqli_query($conn, "SELECT r.*, c.name as customer_name 
    FROM rfqs r 
    JOIN customers c ON r.customer_id = c.id 
    WHERE r.id='$rfq_id'"));

if (isset($_POST['add_line'])) {
    $product = $_POST['product'];
    $qty = $_POST['qty'];
    $unit_price = $_POST['unit_price'];
    $cost_price = $_POST['cost_price'];
    $catalog_file = $_POST['catalog_file'];

    mysqli_query($conn, "INSERT INTO rfq_lines (rfq_id, product, qty, unit_price, cost_price, catalog_file) 
                         VALUES ('$rfq_id','$product','$qty','$unit_price','$cost_price','$catalog_file')");

    header("Location: rfq_edit.php?id=$rfq_id");
    exit;
}

if (isset($_GET['delete_line'])) {
    $line_id = intval($_GET['delete_line']);
    mysqli_query($conn, "DELETE FROM rfq_lines WHERE id='$line_id'");
    header("Location: rfq_edit.php?id=$rfq_id");
    exit;
}

$lines = mysqli_query($conn, "SELECT * FROM rfq_lines WHERE rfq_id='$rfq_id'");
?>

<div class="container mt-4">
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <h5 class="card-title">Edit RFQ #<?= $rfq['id'] ?></h5>
            <p><strong>Customer:</strong> <?= htmlspecialchars($rfq['customer_name']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($rfq['status']) ?></p>
            <p><strong>Total:</strong> $<?= number_format($rfq['total_price'], 2) ?></p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-3">RFQ Line Items</h5>

            <form method="post" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="product" class="form-control" placeholder="Product" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="qty" class="form-control" placeholder="Qty" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="Unit Price"
                        required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="Cost Price">
                </div>
                <div class="col-md-3">
                    <input type="text" name="catalog_file" class="form-control" placeholder="Catalog File URL">
                </div>
                <div class="col-12 mt-2">
                    <button class="btn btn-success" name="add_line">Add Line</button>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Cost Price</th>
                        <th>Catalog File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($line = mysqli_fetch_assoc($lines)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($line['product']) ?></td>
                            <td><?= $line['qty'] ?></td>
                            <td>$<?= number_format($line['unit_price'], 2) ?></td>
                            <td>$<?= number_format($line['cost_price'], 2) ?></td>
                            <td><?= htmlspecialchars($line['catalog_file']) ?></td>
                            <td>
                                <a href="?id=<?= $rfq_id ?>&delete_line=<?= $line['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this line?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../templates/footer.php"); ?>