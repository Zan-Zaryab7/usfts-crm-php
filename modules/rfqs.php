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
                                <td>
                                    <a href="#" onclick="window.location='rfq-pdf.php?id=<?= $r['id'] ?>'" title="Print" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-printer"></i>
                                    </a>
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