<?php
include("../includes/auth.php");
check_auth();
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id'])) {
    die("RFQ ID is required.");
}
$rfq_id = intval($_GET['id']);

$sql = "SELECT r.*, 
               c.name AS customer_name, c.address AS customer_address,
               sp.name AS salesperson_name, sp.title AS salesperson_title, sp.email AS salesperson_email, sp.phone AS salesperson_phone, sp.signature AS salesperson_signature,
               bt.title AS billto_title, bt.address AS billto_address,
               b.name AS buyer_name, b.email AS buyer_email, b.phone AS buyer_phone, b.address AS buyer_address,
               st.name AS shipto_name, st.company AS shipto_company, st.email AS shipto_email, st.phone AS shipto_phone, st.address AS shipto_address
        FROM rfqs r
        JOIN customers c ON r.customer_id = c.id
        JOIN salesperson sp ON r.salesPerson_id = sp.id
        JOIN billto bt ON r.billTo_id = bt.id
        JOIN buyer b ON r.buyer_id = b.id
        JOIN shipto st ON r.shipTo_id = st.id
        WHERE r.id = $rfq_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("RFQ not found.");
}
$rfq = $result->fetch_assoc();

$sql_lines = "SELECT * FROM rfq_lines WHERE rfq_id = $rfq_id";
$result_lines = $conn->query($sql_lines);

$subtotal = 0;
$lines = [];
while ($row = $result_lines->fetch_assoc()) {
    $row['total_price'] = $row['qty'] * $row['unit_price'];
    $subtotal += $row['total_price'];
    $lines[] = $row;
}

$signature_path = !empty($rfq['salesperson_signature']) ? realpath("../" . $rfq['salesperson_signature']) : null;

ob_start();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 13px;
    }

    .quote-container {
        background: #fff;
        padding: 20px;
        font-size: 13px;
        border: 1px solid #ccc;
    }

    .quote-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .quote-header img {
        height: 70px;
    }

    .company-info {
        max-width: 300px;
    }

    .support-info {
        text-align: right;
        font-size: 12px;
    }

    .quote-title {
        text-align: center;
        margin: 15px 0;
        font-size: 20px;
        font-weight: bold;
    }

    .details-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }

    .details-box {
        border: 1px solid #000;
        padding: 8px;
        min-height: 70px;
    }

    .quote-info {
        border: 1px solid #000;
        padding: 8px;
        font-size: 12px;
        margin-top: 10px;
    }

    .quote-info p {
        margin: 2px 0;
    }

    table.quote-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .quote-table th,
    .quote-table td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 12px;
    }

    .quote-table th {
        background: #f5f5f5;
        text-align: center;
    }

    .subtotal {
        text-align: right;
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
    }

    .signature {
        margin-top: 20px;
    }

    .signature img {
        height: 60px;
    }
</style>

<div class="quote-container">

    <div class="quote-header">
        <div class="company-info">
            <img src="https://novaascenddynamics.com/crm/assets/USFTS.png" alt="Logo">
            <p>U.S. Forces Tactical Supply<br>
                15870 Camino San Bernardo<br>
                San Diego, CA 92127, USA<br>
                UEI: KQM1HHYLHR5<br>
                CAGE: 9EAx7</p>
        </div>
        <div class="support-info">
            <a href="https://usfts.us/" target="_blank">www.usfts.us</a><br>
            Global Customer Support<br>
            1-619-918-5013<br>
            bids@usfts.us
        </div>
    </div>

    <div class="quote-title">Request For Quotation</div>

    <div class="details-row">
        <div class="details-box">
            <strong>Bill To:</strong><br>
            <?= htmlspecialchars($rfq['billto_title']) ?><br>
            <?= nl2br(htmlspecialchars($rfq['billto_address'])) ?>
        </div>
        <div class="details-box">
            <strong>Ship To:</strong><br>
            <?= htmlspecialchars($rfq['shipto_name']) ?><br>
            <?= htmlspecialchars($rfq['shipto_company']) ?><br>
            <?= htmlspecialchars($rfq['shipto_email']) ?><br>
            <?= htmlspecialchars($rfq['shipto_phone']) ?><br>
            <?= nl2br(htmlspecialchars($rfq['shipto_address'])) ?>
        </div>
        <div class="details-box">
            <strong>Buyer:</strong><br>
            <?= htmlspecialchars($rfq['buyer_name']) ?><br>
            <?= htmlspecialchars($rfq['buyer_email']) ?><br>
            <?= htmlspecialchars($rfq['buyer_phone']) ?><br>
            <?= nl2br(htmlspecialchars($rfq['buyer_address'])) ?>
        </div>
    </div>

    <div class="quote-info">
        <p><strong>Quote Number:</strong> <?= htmlspecialchars($rfq['code']) ?></p>
        <p>RFQ Number: <?= htmlspecialchars($rfq['rfq_number']) ?></p>
        <p>RFQ Title: <?= htmlspecialchars($rfq['rfq_title']) ?></p>
        <p>Quote Date: <?= date("F d, Y", strtotime($rfq['quote_date'])) ?></p>
        <p>Quote Validity: <?php
        $quoteDate = new DateTime($rfq['quote_date']);

        $validityStr = strtolower(trim($rfq['validity']));
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
        ?></p>
        <p>Lead Time: <?= htmlspecialchars($rfq['lead_time']) ?></p>
        <p>Sales Person: <?= htmlspecialchars($rfq['salesperson_name']) ?></p>
        <p>Sales Person Title: <?= htmlspecialchars($rfq['salesperson_title']) ?></p>
        <p>Sales Person Email: <?= htmlspecialchars($rfq['salesperson_email']) ?></p>
        <p>Sales Person Phone: <?= htmlspecialchars($rfq['salesperson_phone']) ?></p>
        <div class="signature">
            <p>Sales Person Signature:</p>
            <?php if ($signature_path): ?>
                <img src="file://<?= $signature_path ?>" alt="Signature">
            <?php endif; ?>
        </div>
    </div>

    <table class="quote-table mt-5">
        <thead>
            <tr>
                <th>Line Item</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Description</th>
                <th>Unit Price Euro</th>
                <th>Total Price Euro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lines as $i => $line): ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td class="text-center"><?= htmlspecialchars($line['qty']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($line['unit']) ?></td>
                    <td>
                        PART: <?= htmlspecialchars($line['part']) ?><br>
                        CUST: <?= htmlspecialchars($line['cust']) ?><br>
                        MFG: <?= htmlspecialchars($line['mfg']) ?><br>
                        COO: <?= htmlspecialchars($line['coo']) ?><br>
                        ECCN: <?= htmlspecialchars($line['eccn']) ?><br>
                        HTSUS: <?= htmlspecialchars($line['htsus']) ?><br>
                        DESC: <?= htmlspecialchars($line['description']) ?>
                    </td>
                    <td class="text-center" style="text-align:right;"><?= number_format($line['unit_price'], 2) ?></td>
                    <td class="text-center" style="text-align:right;"><?= number_format($line['total_price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="subtotal">
        Sub Total: €<?= number_format($subtotal, 2) ?>
    </div>
</div>

<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("RFQ_" . $rfq_id . ".pdf", ["Attachment" => true]);
