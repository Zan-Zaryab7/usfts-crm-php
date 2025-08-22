<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$id = (int) $_GET['id'];
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id=$id"));
if (!$order) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    include("../templates/footer.php");
    exit;
}

$customers = mysqli_query($conn, "SELECT id,name FROM customers");
$rfqs = mysqli_query($conn, "SELECT id FROM rfqs");
$lines = mysqli_query($conn, "SELECT * FROM order_lines WHERE order_id=$id");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = $_POST['customer_id'];
    $rfq_id = $_POST['rfq_id'] ?: "NULL";
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE orders SET customer_id='$customer_id', rfq_id=$rfq_id, status='$status' WHERE id=$id");

    mysqli_query($conn, "DELETE FROM order_lines WHERE order_id=$id");

    $products = $_POST['product'];
    $qtys = $_POST['qty'];
    $unit_prices = $_POST['unit_price'];
    $cost_prices = $_POST['cost_price'];

    $total_cost = 0;
    $total_price = 0;

    foreach ($products as $i => $product) {
        if (trim($product) == "")
            continue;

        $qty = (int) $qtys[$i];
        if ($i == 0 && $qty < 1)
            $qty = 1;
        $unit = (float) $unit_prices[$i];
        $cost = (float) $cost_prices[$i];

        mysqli_query($conn, "INSERT INTO order_lines (order_id, product, qty, unit_price, cost_price) 
                    VALUES ('$id','$product','$qty','$unit','$cost')");

        $total_cost += $qty * $cost;
        $total_price += $qty * $unit;
    }

    $profit = $total_price - $total_cost;
    $margin = $total_price > 0 ? ($profit / $total_price) * 100 : 0;
    mysqli_query($conn, "UPDATE orders 
                SET total_cost='$total_cost', total_price='$total_price', profit='$profit', margin='$margin' 
                WHERE id=$id");

    header("Location: orders.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h5>Edit Order #<?= $order['code'] ?></h5>
            <form method="post">
                <div class="mb-3">
                    <label>Customer</label>
                    <select class="form-control" name="customer_id" required>
                        <?php while ($c = mysqli_fetch_assoc($customers)) { ?>
                            <option value="<?= $c['id'] ?>" <?= $c['id'] == $order['customer_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Linked RFQ (optional)</label>
                    <select class="form-control" name="rfq_id">
                        <option value="">None</option>
                        <?php
                        mysqli_data_seek($rfqs, 0);
                        while ($r = mysqli_fetch_assoc($rfqs)) { ?>
                            <option value="<?= $r['id'] ?>" <?= $order['rfq_id'] == $r['id'] ? 'selected' : '' ?>>
                                RFQ #<?= $r['id'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                        <option <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>

                <h6>Order Lines</h6>
                <div id="lines">
                    <?php
                    $i = 0;
                    while ($line = mysqli_fetch_assoc($lines)) { ?>
                        <div class="row g-2 mb-2 line">
                            <div class="col">
                                <input class="form-control" name="product[]" placeholder="Product"
                                    value="<?= htmlspecialchars($line['product']) ?>" required>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="qty[]" value="<?= $line['qty'] ?>" required
                                    <?= $i == 0 ? '' : ' ' ?>>
                            </div>
                            <div class="col">
                                <input type="number" step="0.01" class="form-control" name="unit_price[]"
                                    value="<?= $line['unit_price'] ?>" required>
                            </div>
                            <div class="col">
                                <input type="number" step="0.01" class="form-control" name="cost_price[]"
                                    value="<?= $line['cost_price'] ?>" required>
                            </div>
                            <?php if ($i > 0) { ?>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="this.parentElement.parentElement.remove()">x</button>
                                </div>
                            <?php } ?>
                        </div>
                        <?php $i++;
                    } ?>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addLine()">+ Add Line</button>

                <br><br>
                <button class="btn btn-primary">Update Order</button>
                <a href="orders.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
    function addLine() {
        const div = document.createElement("div");
        div.className = "row g-2 mb-2 line";
        div.innerHTML = `
        <div class="col"><input class="form-control" name="product[]" placeholder="Product" required></div>
        <div class="col"><input type="number" class="form-control" name="qty[]" value="1" required></div>
        <div class="col"><input type="number" step="0.01" class="form-control" name="unit_price[]" placeholder="Unit Price" required></div>
        <div class="col"><input type="number" step="0.01" class="form-control" name="cost_price[]" placeholder="Cost Price" required></div>
        <div class="col-auto"><button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()">x</button></div>
    `;
        document.getElementById("lines").appendChild(div);
    }
</script>

<?php include("../templates/footer.php"); ?>