<!-- <?php
// require_once '../vendor/autoload.php';
// use Dompdf\Dompdf;
// include("../config/database.php");
// include("../includes/auth.php");
// check_auth();

// if (!isset($_GET['id'])) {
//     header("Location: invoices.php");
//     exit;
// }

// $id = intval($_GET['id']);
// $invoice = mysqli_query($conn, "
//     SELECT i.*, o.code AS order_code, o.created_at AS order_date,
//            c.name AS customer, c.company, c.email, c.phone, c.address
//     FROM invoices i
//     JOIN orders o ON i.order_id=o.id
//     JOIN customers c ON o.customer_id=c.id
//     WHERE i.id=$id
// ");
// $invoice = mysqli_fetch_assoc($invoice);

// if (!$invoice) {
//     echo "<div class='container mt-5'><div class='alert alert-danger'>Invoice not found.</div></div>";
//     include("../templates/footer.php");
//     exit;
// }

// $order_lines = mysqli_query(
//     $conn,
//     "
//     SELECT * FROM order_lines WHERE order_id=" . intval($invoice['order_id'])
// );

// ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - USFTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="/crm/assets/style.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'DancingScript';
            src: url('../assets/fonts/static/DancingScript-SemiBold.ttf') format('truetype');
        }

        .pdf-head-name {
            font-family: 'DancingScript', sans-serif;
            font-size: 6em;
            font-weight: 600;
            letter-spacing: 2px;
            color: #0d3483;
        }

        .span-blue {
            color: #1a6493;
        }

        .span-red {
            color: #c6265c;
        }

        .pdf-header-w {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 900;
            letter-spacing: -3px;
        }

        .pdf-head-title {
            font-size: 5em;
        }
    </style>
</head>

<body class="bg-light"></body>

<section class="bg-white">
    <div class="w-100">
        <img src="../assets/pdf-head-img.jpg" class="img-fluid w-100" alt="PDF Head">
    </div>

    <div class="bg-secondary pb-2">
        <div class="d-flex justify-between">
            <div class="w-100 ms-5">
                <h1 class="pdf-head-name mt-5">Emma Willam</h1>
                <h3 class="text-white ms-3">Signed and Authorized By</h3>
                <h5 class="text-white ms-3">Sign Id # 847093072321</h5>
                <h1 class="pdf-header-w"><span class="span-blue">Women</span> <span class="span-red">Owned</span> Small
                    <span class="span-red">Business</span>
                </h1>
            </div>
            <img src="../assets/USFTS.png" class="img-fluid w-50" alt="PDF Logo">
        </div>

        <h1 class="pdf-head-title text-white ms-4">US Forces</br>Tactical Supply</h1>

        <div class="bg-white pb-2 mx-4 mb-2">
            <div class="d-flex border mt-4 p-3 align-items-center">
                <div class="w-50">
                    <div class="mb-2 d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-danger me-2 border border-danger rounded-3 p-2"></i>
                        <span>+1 619-918-5013</span>
                    </div>
                    <div class="mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope-fill text-danger me-2 border border-danger rounded-3 p-2"></i>
                        <span>bids@usfts.us</span>
                    </div>
                    <div class="mb-2 d-flex align-items-center">
                        <i class="bi bi-globe2 text-danger me-2 border border-danger rounded-3 p-2"></i>
                        <span>www.usfts.us</span>
                    </div>
                    <div class="mb-2 d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-danger me-2 border border-danger rounded-3 p-2"></i>
                        <span>15870 Camino San Bernardo Apt 207, San Diego CA 92127 USA</span>
                    </div>
                </div>

                <div class="text-center w-25">
                    <img src="../assets/emma-wiliam.png" alt="Business POC" class="img-fluid rounded shadow-sm">
                </div>

                <div class="w-50 ps-3">
                    <h5 class="fw-bold">Business POC</h5>
                    <p class="mb-1"><strong>Name:</strong> Emma William</p>
                    <p class="mb-1"><strong>Title:</strong> Procurement Manager</p>
                    <p class="mb-1"><strong>Email:</strong> bids@usfts.us</p>
                    <p class="mb-0"><strong>Cell:</strong> +1 (858) 504-0588</p>
                </div>

            </div>
            <div class="mt-3 text-center ">
                <strong class="span-red me-3"><span class="span-blue">UEI:</span> KQM1HHYHLHR5</strong>
                <strong class="span-red me-3"><span class="span-blue">NCAGE:</span> 9EAX7</strong>
                <strong class="span-red me-3"><span class="span-blue">DUNS:</span> 12-346-8707</strong>
                <strong class="span-red me-3"><span class="span-blue">JCCS:</span> 133245</strong>
                <strong class="span-red me-3"><span class="span-blue">CA License #:</span> 202253016523</strong>
                <strong class="span-red"><span class="span-blue">EIN:</span> 92-2840560</strong>
            </div>
        </div>
    </div>


    <div class="d-flex m-3 mt-5">
        <img src="../assets/USFTS.png" class="img-fluid" width="120px" style="height:100px;" alt="PDF Logo">
        <div>
            <p class="mt-1">US Forces Tactical Supply LLC</p>
            <p>15870 Camino San Bernardo Apt 207</p>
            <p>San Diego Ca 92127</p>
            <p>United States</p>
        </div>
    </div>

    <div class="d-flex justify-content-end m-3 mb-4">
        <div>
            <p class="mt-1">Pascaru, Ms. Anamaria</p>
            <p>Brussels, BE (NCIA HQ - Ship-To) New NATO HQ</p>
            <p>-Industrial Infrastructure Building - Reception</p>
            <p>Service Rue Arthur Maes 1, 1130 BRUSSELS,</p>
            <p>Belgium BRUSSELS 1130 Belgium</p>
        </div>
    </div>

    <div class="m-3 mt-5" id="invoiceArea">
        <div class=" ">
            <div class=" ">
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
                    <p class="mb-1"><?= htmlspecialchars($invoice['email']) ?> |
                        <?= htmlspecialchars($invoice['phone']) ?>
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

    <div class="container mt-3 mb-4">
        <button onclick="window.print()" class="btn btn-outline-secondary">Print Invoice</button>
    </div>

    <div class="d-flex m-3 mt-5">
        <img src="../assets/USFTS.png" class="img-fluid" width="120px" style="height:100px;" alt="PDF Logo">
        <div>
            <p class="mt-1">US Forces Tactical Supply LLC</p>
            <p>15870 Camino San Bernardo Apt 207</p>
            <p>San Diego Ca 92127</p>
            <p>United States</p>
        </div>
    </div>

    <div class="m-3 mt-5 p-2 bg-light">
        <div>
            <p>US Forces Tactical Supply LLC</p>
            <p>15870 Camino San Bernardo Apt 207</p>
            <p>San Diego Ca 92127</p>
            <p>United States</p>
        </div>
    </div>

    <hr />

    <div class="m-3 d-flex justify-content-between align-items-center">
        <h2>INTRODUCTION</h2>
        <img src="../assets/USFTS.png" class="img-fluid" width="120px" style="height:100px;" alt="PDF Logo">
    </div>

    <hr />

    <div class="m-3">
        <h1 class="span-blue" style="font-size:50px">
            USFTS Specializes in Providing tactical and logistical solutions across a broad range of industries.
        </h1>
    </div>
</section>

<?php

// $html = ob_get_clean();

// $dompdf = new Dompdf();
// $dompdf->loadHtml($html);
// $dompdf->setPaper('A4', 'portrait');
// $dompdf->render();
// $dompdf->stream("invoice");
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> -->