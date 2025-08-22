<?php
include("../config/database.php");
include("../templates/header.php");
include("../templates/navbar.php");
include("../includes/auth.php");
check_auth();

$customers = mysqli_query($conn, "SELECT id,name FROM customers");
$rfqs = mysqli_query($conn, "SELECT id FROM rfqs");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = $_POST['customer_id'];
    $rfq_id = $_POST['rfq_id'] ?: "NULL";
    $status = $_POST['status'];

    $q = "INSERT INTO orders (customer_id, rfq_id, status) 
          VALUES ('$customer_id', $rfq_id, '$status')";
    mysqli_query($conn, $q);
    $order_id = mysqli_insert_id($conn);

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
        $unit = (float) $unit_prices[$i];
        $cost = (float) $cost_prices[$i];

        mysqli_query($conn, "INSERT INTO order_lines (order_id, product, qty, unit_price, cost_price) 
                    VALUES ('$order_id','$product','$qty','$unit','$cost')");

        $total_cost += $qty * $cost;
        $total_price += $qty * $unit;
    }

    $profit = $total_price - $total_cost;
    $margin = $total_price > 0 ? ($profit / $total_price) * 100 : 0;
    mysqli_query($conn, "UPDATE orders 
                SET total_cost='$total_cost', total_price='$total_price', profit='$profit', margin='$margin' 
                WHERE id=$order_id");

    header("Location: orders.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h5>Create New Order</h5>
            <form method="post">
                <div class="mb-3">
                    <label>Customer</label>
                    <select class="form-control" name="customer_id" required>
                        <option value="">Select Customer</option>
                        <?php while ($c = mysqli_fetch_assoc($customers)) { ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Linked RFQ (optional)</label>
                    <select class="form-control" name="rfq_id">
                        <option value="">None</option>
                        <?php while ($r = mysqli_fetch_assoc($rfqs)) { ?>
                            <option value="<?= $r['id'] ?>">RFQ #<?= $r['id'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option>Pending</option>
                        <option>Delivered</option>
                        <option>Completed</option>
                    </select>
                </div>

                <h6>Order Lines</h6>
                <div id="lines">
                    <div class="row g-2 mb-2 line">
                        <div class="col"><input class="form-control" name="product[]" placeholder="Product" required>
                        </div>
                        <div class="col"><input type="number" class="form-control" name="qty[]" value="1" required>
                        </div>
                        <div class="col"><input type="number" step="0.01" class="form-control" name="unit_price[]"
                                placeholder="Unit Price" required></div>
                        <div class="col"><input type="number" step="0.01" class="form-control" name="cost_price[]"
                                placeholder="Cost Price" required></div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addLine()">+ Add Line</button>

                <br><br>
                <button class="btn btn-primary">Save Order</button>
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