<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

$rfq_id = intval($_GET['id']);
$rfq = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rfqs WHERE id='$rfq_id'"));

if (!$rfq) {
    die("RFQ not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_rfq'])) {
    $rfq_number = mysqli_real_escape_string($conn, $_POST['rfq_number']);
    $rfq_title = mysqli_real_escape_string($conn, $_POST['rfq_title']);
    $quote_date = mysqli_real_escape_string($conn, $_POST['quote_date']);
    $validity = mysqli_real_escape_string($conn, $_POST['validity']);
    $lead_time = (int) $_POST['lead_time'];
    $shipping = (float) $_POST['shipping'];
    $customer_id = (int) $_POST['customer_id'];
    $salesPerson_id = (int) $_POST['salesPerson_id'];
    $billTo_id = (int) $_POST['billTo_id'];
    $buyer_id = (int) $_POST['buyer_id'];
    $shipTo_id = (int) $_POST['shipTo_id'];

    $q = "UPDATE rfqs SET 
            rfq_number='$rfq_number',
            rfq_title='$rfq_title',
            quote_date='$quote_date',
            validity='$validity',
            lead_time='$lead_time',
            shipping='$shipping',
            customer_id='$customer_id',
            salesPerson_id='$salesPerson_id',
            billTo_id='$billTo_id',
            buyer_id='$buyer_id',
            shipTo_id='$shipTo_id'
          WHERE id='$rfq_id'";

    mysqli_query($conn, $q) or die("RFQ Update Error: " . mysqli_error($conn));

    mysqli_query($conn, "DELETE FROM rfq_lines WHERE rfq_id='$rfq_id'");

    if (!empty($_POST['lines'])) {
        foreach ($_POST['lines'] as $line) {
            $qty = (int) $line['qty'];
            $unit = mysqli_real_escape_string($conn, $line['unit']);
            $part = mysqli_real_escape_string($conn, $line['part']);
            $mfg = mysqli_real_escape_string($conn, $line['mfg']);
            $coo = mysqli_real_escape_string($conn, $line['coo']);
            $eccn = mysqli_real_escape_string($conn, $line['eccn']);
            $cust = mysqli_real_escape_string($conn, $line['cust']);
            $htsus = mysqli_real_escape_string($conn, $line['htsus']);
            $desc = mysqli_real_escape_string($conn, $line['desc']);
            $unit_price = (float) $line['unit_price'];
            $total = (float) $line['total'];

            $line_q = "INSERT INTO rfq_lines 
                        (rfq_id, qty, unit, part, mfg, coo, eccn, cust, htsus, description, unit_price, total_price)
                       VALUES
                        ('$rfq_id','$qty','$unit','$part','$mfg','$coo','$eccn','$cust','$htsus','$desc','$unit_price','$total')";
            mysqli_query($conn, $line_q) or die("RFQ Line Insert Error: " . mysqli_error($conn));
        }
    }

    echo "<script>window.location.href = 'rfqs.php';</script>";
    exit;
}

$customers = mysqli_query($conn, "SELECT * FROM customers");
$shipTos = mysqli_query($conn, "SELECT * FROM shipto");
$buyers = mysqli_query($conn, "SELECT * FROM buyer");
$billToList = mysqli_query($conn, "SELECT * FROM billto");
$salesPersons = mysqli_query($conn, "SELECT * FROM salesperson");

$lines = mysqli_query($conn, "SELECT * FROM rfq_lines WHERE rfq_id='$rfq_id'");
?>

<div class="container mt-4">
    <h4 class="card-title mb-3"><i class="bi bi-arrow-left-circle" title="Back"
            onclick="history.go(-1); return false;"></i>
        Edit RFQ #<?= $rfq['id'] ?></h4>
    <div class="container my-4 bg-white p-4 shadow-sm">

        <div class="d-flex justify-content-between align-items-start rfq-header">
            <div class="d-md-flex">
                <img src="../assets/USFTS.png" alt="Logo" height="80" class="me-3">
                <div>
                    <h5 class="mb-1">US Forces Tactical Supply LLC</h5>
                    <p class="mb-0">15870 Camino San Bernardo Apt 207<br>
                        San Diego, CA 92127, USA</p>
                </div>
            </div>
            <div class="text-end">
                <a href="https://usfts.us/" class="mb-1" target="_blank">www.usfts.us</a>
                <p class="mb-0">Global Customer Support<br>
                    1-619-918-5013<br>
                    bids@usfts.us</p>
            </div>
        </div>

        <h4 class="text-center mb-4"><span class="text-[#397099]">Edit</span> Request For Quotation</h4>

        <form method="post">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="rfq-card mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">RFQ Number</label>
                                <input type="text" name="rfq_number" class="form-control"
                                    value="<?= htmlspecialchars($rfq['rfq_number']) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">RFQ Title</label>
                                <input type="text" name="rfq_title" class="form-control"
                                    value="<?= htmlspecialchars($rfq['rfq_title']) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Quote Date</label>
                                <input type="date" name="quote_date" id="quote_date" class="form-control"
                                    value="<?= htmlspecialchars($rfq['quote_date']) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Quote Validity</label>
                                <input type="text" name="validity" class="form-control"
                                    value="<?= htmlspecialchars($rfq['validity']) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Lead Time (Days)</label>
                                <input type="number" name="lead_time" class="form-control" min="0"
                                    value="<?= htmlspecialchars($rfq['lead_time']) ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Shipping (Optional)</label>
                                <input type="number" placeholder="Shipping" value="<?= htmlspecialchars($rfq['shipping']) ?>" step="0.01" min="0" name="shipping" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="rfq-card">
                        <div class="row g-2">

                            <div class="col-6">
                                <label class="form-label">Customer</label>
                                <select class="form-control" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    <?php while ($c = mysqli_fetch_assoc($customers)) { ?>
                                            <option value="<?= $c['id'] ?>" <?= $rfq['customer_id'] == $c['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['code']) ?> - <?= htmlspecialchars($c['name']) ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Sales Person</label>
                                <select class="form-control" name="salesPerson_id" required>
                                    <option value="">Select Sales Person</option>
                                    <?php while ($s = mysqli_fetch_assoc($salesPersons)) { ?>
                                            <option value="<?= $s['id'] ?>" <?= $rfq['salesPerson_id'] == $s['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($s['code']) ?> - <?= htmlspecialchars($s['name']) ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Bill To</label>
                                <select class="form-control" name="billTo_id" required>
                                    <option value="">Select Bill To</option>
                                    <?php while ($b = mysqli_fetch_assoc($billToList)) { ?>
                                            <option value="<?= $b['id'] ?>" <?= $rfq['billTo_id'] == $b['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($b['code']) ?> - <?= htmlspecialchars($b['title']) ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Buyer</label>
                                <select class="form-control" name="buyer_id" required>
                                    <option value="">Select Buyer</option>
                                    <?php while ($b = mysqli_fetch_assoc($buyers)) { ?>
                                            <option value="<?= $b['id'] ?>" <?= $rfq['buyer_id'] == $b['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($b['code']) ?> - <?= htmlspecialchars($b['name']) ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ship To</label>
                                <select class="form-control" name="shipTo_id" required>
                                    <option value="">Select Ship To</option>
                                    <?php while ($st = mysqli_fetch_assoc($shipTos)) { ?>
                                            <option value="<?= $st['id'] ?>" <?= $rfq['shipTo_id'] == $st['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($st['code']) ?> - <?= htmlspecialchars($st['name']) ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-2">RFQ Line Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered rfq-table" id="lineItemsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Description</th>
                            <th>Unit Price (€)</th>
                            <th>Total Price (€)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        while ($line = mysqli_fetch_assoc($lines)) { ?>
                                <tr>
                                    <td class="row-index"><?= $i + 1 ?></td>
                                    <td><input type="number" name="lines[<?= $i ?>][qty]" value="<?= $line['qty'] ?>" class="form-control" required></td>
                                    <td><input type="text" name="lines[<?= $i ?>][unit]" value="<?= htmlspecialchars($line['unit']) ?>" class="form-control" required></td>
                                    <td>
                                        <div class="desc-field">
                                            <div class="row g-1">
                                                <div class="col-6 d-flex">PART: <input type="text" name="lines[<?= $i ?>][part]" value="<?= htmlspecialchars($line['part']) ?>" class="border-0 ms-1" required></div>
                                                <div class="col-6 d-flex">MFG: <input type="text" name="lines[<?= $i ?>][mfg]" value="<?= htmlspecialchars($line['mfg']) ?>" class="border-0 ms-1" required></div>
                                                <div class="col-6 d-flex">COO: <input type="text" name="lines[<?= $i ?>][coo]" value="<?= htmlspecialchars($line['coo']) ?>" class="border-0 ms-1" required></div>
                                                <div class="col-6 d-flex">ECCN: <input type="text" name="lines[<?= $i ?>][eccn]" value="<?= htmlspecialchars($line['eccn']) ?>" class="border-0 ms-1" required></div>
                                                <div class="col-6 d-flex">CUST: <input type="text" name="lines[<?= $i ?>][cust]" value="<?= htmlspecialchars($line['cust']) ?>" class="border-0 ms-1" required></div>
                                                <div class="col-6 d-flex">HTSUS: <input type="text" name="lines[<?= $i ?>][htsus]" value="<?= htmlspecialchars($line['htsus']) ?>" class="border-0 ms-1" required></div>
                                                <div class="col-12 d-flex">Desc: <input type="text" name="lines[<?= $i ?>][desc]" value="<?= htmlspecialchars($line['description']) ?>" class="border-0 ms-1" required></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><input type="number" step="0.01" name="lines[<?= $i ?>][unit_price]" value="<?= $line['unit_price'] ?>" class="form-control" required></td>
                                    <td><input type="number" step="0.01" name="lines[<?= $i ?>][total]" value="<?= $line['total_price'] ?>" class="form-control" required></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger removeRow">X</button></td>
                                </tr>
                            <?php $i++;
                        } ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-outline-success mt-2" id="addRow">+ Add Line</button>
            </div>

            <div class="text-end mt-3">
                <button type="submit" name="update_rfq" class="btn btn-outline-primary">Update RFQ</button>
                <button title="Cancel" onclick="history.go(-1); return false;" class="btn btn-outline-secondary">Cancel</button>
            </div>

        </form>
    </div>
</div>


<script>
    const quote_datePickerId = document.getElementById('quote_date');
    const today = new Date().toISOString().split("T")[0];
    quote_datePickerId.value = today;
    quote_datePickerId.min = today;
    quote_datePickerId.max = today;
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("lineItemsTable").getElementsByTagName("tbody")[0];
        const addRowBtn = document.getElementById("addRow");

        addRowBtn.addEventListener("click", function () {
            const rowCount = table.rows.length;
            const newRow = table.rows[0].cloneNode(true);

            Array.from(newRow.querySelectorAll("input")).forEach(input => {
                input.value = "";
                input.name = input.name.replace(/\d+/, rowCount);
            });

            newRow.querySelector(".row-index").innerText = rowCount + 1;
            table.appendChild(newRow);
        });

        table.addEventListener("click", function (e) {
            if (e.target.classList.contains("removeRow")) {
                if (table.rows.length > 1) {
                    e.target.closest("tr").remove();

                    Array.from(table.rows).forEach((row, idx) => {
                        row.querySelector(".row-index").innerText = idx + 1;
                        Array.from(row.querySelectorAll("input")).forEach(input => {
                            input.name = input.name.replace(/\d+/, idx);
                        });
                    });
                } else {
                    alert("At least one line item is required!");
                }
            }
        });
    });
</script>

<?php include("add-customer-model.php"); ?>
<?php include("add-salesPerson-model.php"); ?>
<?php include("add-billTo-model.php"); ?>
<?php include("add-buyer-model.php"); ?>
<?php include("add-shipTo-model.php"); ?>
<?php include("../templates/footer.php"); ?>
