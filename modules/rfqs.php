<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

$rfqs = mysqli_query($conn, "SELECT r.*, c.name AS customer, sp.name AS salesPerson, bt.title AS billTo, b.name AS buyer, st.name AS shipTo
                             FROM rfqs AS r 
                             JOIN customers c ON r.customer_id=c.id
                             JOIN salesperson sp ON r.salesPerson_id=sp.id
                             JOIN billto bt ON r.billTo_id=bt.id
                             JOIN buyer b ON r.buyer_id=b.id
                             JOIN shipto st ON r.shipTo_id=st.id
                             ORDER BY r.id DESC");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM rfqs WHERE id='$id'");

    echo "<script>window.location.href = 'rfqs.php';</script>";
    exit;
}


if (isset($_POST['convertToOrder']) && !empty($_POST['rfq_id'])) {
    $rfq_id = (int) $_POST['rfq_id'];

    $check_order = mysqli_query($conn, "SELECT id FROM orders WHERE rfq_id = $rfq_id");
    if (mysqli_num_rows($check_order) == 0) {
        $rfq_res = mysqli_query($conn, "SELECT * FROM rfqs WHERE id = $rfq_id");
        if (mysqli_num_rows($rfq_res) > 0) {
            $rfq = mysqli_fetch_assoc($rfq_res);

            $conv_order_query = "INSERT INTO orders 
                (rfq_id, customer_id, status, created_at, 
                 rfq_title, rfq_number, quote_date, validity, lead_time, shipping,
                 salesPerson_id, billTo_id, buyer_id, shipTo_id, rfq_status, rfq_created_at) 
                VALUES (
                    '{$rfq['id']}',
                    '{$rfq['customer_id']}',
                    '{$rfq['status']}',
                    NOW(),
                    '" . mysqli_real_escape_string($conn, $rfq['rfq_title']) . "',
                    '{$rfq['rfq_number']}',
                    '{$rfq['quote_date']}',
                    '{$rfq['validity']}',
                    '{$rfq['lead_time']}',
                    '{$rfq['shipping']}',
                    '{$rfq['salesPerson_id']}',
                    '{$rfq['billTo_id']}',
                    '{$rfq['buyer_id']}',
                    '{$rfq['shipTo_id']}',
                    '{$rfq['status']}',
                    '{$rfq['created_at']}'
                )";
            mysqli_query($conn, $conv_order_query);
            $order_id = mysqli_insert_id($conn);

            $rfq_lines_res = mysqli_query($conn, "SELECT * FROM rfq_lines WHERE rfq_id = $rfq_id");
            while ($line = mysqli_fetch_assoc($rfq_lines_res)) {
                $conv_line_query = "INSERT INTO order_lines 
                    (order_id, qty, unit_price, unit, part, mfg, coo, eccn, cust, htsus, description, total_price) 
                    VALUES (
                        '$order_id',
                        '{$line['qty']}',
                        '{$line['unit_price']}',
                        '{$line['unit']}',
                        '" . mysqli_real_escape_string($conn, $line['part']) . "',
                        '" . mysqli_real_escape_string($conn, $line['mfg']) . "',
                        '{$line['coo']}',
                        '{$line['eccn']}',
                        '{$line['cust']}',
                        '{$line['htsus']}',
                        '" . mysqli_real_escape_string($conn, $line['description']) . "',
                        '{$line['total_price']}'
                    )";
                mysqli_query($conn, $conv_line_query);
            }

            echo "<script>alert('RFQ #{$rfq['rfq_number']} converted to Order #$order_id successfully!'); window.location.href='rfqs.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('This RFQ is already converted to an Order!'); window.location.href='rfqs.php';</script>";
        exit;
    }
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
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>RFQs #</th>
                            <th>Title</th>
                            <th>Customer</th>
                            <th>Sales Person</th>
                            <th>Bill To</th>
                            <th>Buyer</th>
                            <th>Ship To</th>
                            <th>Validity</th>
                            <th>Creation Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($r = mysqli_fetch_assoc($rfqs)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($r['rfq_number']) ?></td>
                                <td><?= htmlspecialchars($r['rfq_title']) ?></td>
                                <td><?= htmlspecialchars($r['customer']) ?></td>
                                <td><?= htmlspecialchars($r['salesPerson']) ?></td>
                                <td><?= htmlspecialchars($r['billTo']) ?></td>
                                <td><?= htmlspecialchars($r['buyer']) ?></td>
                                <td><?= htmlspecialchars($r['shipTo']) ?></td>
                                <td>
                                    <?php
                                    $quoteDate = new DateTime($r['quote_date']);

                                    $validityStr = strtolower(trim($r['validity']));
                                    $validityInterval = null;

                                    if (strpos($validityStr, 'month') !== false) {
                                        $months = (int) filter_var($validityStr, FILTER_SANITIZE_NUMBER_INT);
                                        $validityInterval = new DateInterval("P{$months}M");
                                    } elseif (strpos($validityStr, 'day') !== false) {
                                        $days = (int) filter_var($validityStr, FILTER_SANITIZE_NUMBER_INT);
                                        $validityInterval = new DateInterval("P{$days}D");
                                    }

                                    if ($validityInterval) {
                                        $expiryDate = (clone $quoteDate)->add($validityInterval);
                                        $today = new DateTime();
                                        $daysLeft = $today->diff($expiryDate)->days;

                                        if ($today > $expiryDate) {
                                            echo "<span class='text-danger'>Expired</span>";
                                        } else {
                                            echo $daysLeft . " days left";
                                        }
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td><?= date("m/d/Y H:i", strtotime($r['created_at'])) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= $r['status'] ?></span>
                                </td>
                                <td style="display: flex; gap: 4px;">
                                    <button onclick="window.location='rfq-pdf.php?id=<?= $r['id'] ?>'" title="Print"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                    <button onclick="window.location='rfq_edit.php?id=<?= $r['id'] ?>'" title="Edit"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="?delete=<?= $r['id'] ?>" title="Delete" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this Quotation?');">
                                        <i class="bi bi-trash2"></i>
                                    </a>

                                    <?php
                                    $already_order = mysqli_query($conn, "SELECT id FROM orders WHERE rfq_id = {$r['id']}");
                                    if (mysqli_num_rows($already_order) == 0) {
                                        ?>
                                        <form method="post"
                                            onsubmit="return confirm('Are you sure you want to convert this RFQ to an Order?');">
                                            <input type="hidden" name="rfq_id" value="<?= $r['id'] ?>">
                                            <button type="submit" name="convertToOrder" title="Convert to Order"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <button class="btn btn-sm btn-secondary" aria-readonly="" title="Already Converted">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    <?php } ?>
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