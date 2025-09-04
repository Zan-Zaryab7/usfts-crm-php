<?php
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_rfq'])) {
    $rfq_number = mysqli_real_escape_string($conn, $_POST['rfq_number']);
    $rfq_title = mysqli_real_escape_string($conn, $_POST['rfq_title']);
    $quote_date = mysqli_real_escape_string($conn, $_POST['quote_date']);
    $validity = mysqli_real_escape_string($conn, $_POST['validity']);
    $lead_time = (int) $_POST['lead_time'];
    $customer_id = (int) $_POST['customer_id'];
    $salesPerson_id = (int) $_POST['salesPerson_id'];
    $billTo_id = (int) $_POST['billTo_id'];
    $buyer_id = (int) $_POST['buyer_id'];
    $shipTo_id = (int) $_POST['shipTo_id'];

    $q = "INSERT INTO rfqs 
            (rfq_number, rfq_title, quote_date, validity, lead_time, 
             customer_id, salesPerson_id, billTo_id, buyer_id, shipTo_id, 
             status, created_at)
          VALUES 
            ('$rfq_number', '$rfq_title', '$quote_date', '$validity', '$lead_time',
             '$customer_id', '$salesPerson_id', '$billTo_id', '$buyer_id', '$shipTo_id',
             'Open', NOW())";

    mysqli_query($conn, $q) or die("RFQ Insert Error: " . mysqli_error($conn));
    $rfq_id = mysqli_insert_id($conn);

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

    header("Location: rfqs.php");
    exit;
}
?>

<div class="container mt-4">
    <h4 class="card-title mb-3"><i class="bi bi-arrow-left-circle" title="Back"
            onclick="history.go(-1); return false;"></i>
        Create New RFQ</h4>
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

        <h4 class="text-center mb-4"><span class="text-[#397099]">Request</span> For Quotation</h4>

        <form method="post">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="rfq-card mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">RFQ Number</label>
                                <input type="text" placeholder="Number" name="rfq_number" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">RFQ Title</label>
                                <input type="text" placeholder="Title" name="rfq_title" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Quote Date</label>
                                <input type="date" name="quote_date" id="quote_date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Quote Validity</label>
                                <input type="text" name="validity" class="form-control" value="5 Months">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Lead Time (Days)</label>
                                <input type="number" name="lead_time" class="form-control" min="0" value="5">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="rfq-card">
                        <div class="row g-2">

                            <div class="col-6">
                                <label class="form-label">Customer</label>
                                <?php include("add-customer-model.php"); ?>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Sales Person</label>
                                <?php include("add-salesPerson-model.php"); ?>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Bill To</label>
                                <?php include("add-billTo-model.php"); ?>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Buyer</label>
                                <?php include("add-buyer-model.php"); ?>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ship To</label>
                                <?php include("add-shipTo-model.php"); ?>
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
                        <tr>
                            <td class="row-index">1</td>
                            <td><input type="number" name="lines[0][qty]" min="0" class="form-control" value="1"></td>
                            <td><input type="text" name="lines[0][unit]" class="form-control" value="Each"></td>
                            <td>
                                <div class="desc-field">
                                    <div class="row g-1">
                                        <div class="col-6 d-flex">PART: <input type="text" name="lines[0][part]"
                                                class="border-0 ms-1"></div>
                                        <div class="col-6 d-flex">MFG: <input type="text" name="lines[0][mfg]"
                                                class="border-0 ms-1"></div>
                                        <div class="col-6 d-flex">COO: <input type="text" name="lines[0][coo]"
                                                class="border-0 ms-1"></div>
                                        <div class="col-6 d-flex">ECCN: <input type="text" name="lines[0][eccn]"
                                                class="border-0 ms-1"></div>
                                        <div class="col-6 d-flex">CUST: <input type="text" name="lines[0][cust]"
                                                class="border-0 ms-1"></div>
                                        <div class="col-6 d-flex">HTSUS: <input type="text" name="lines[0][htsus]"
                                                class="border-0 ms-1"></div>
                                        <div class="col-12 d-flex">Desc: <input type="text" name="lines[0][desc]"
                                                class="border-0 ms-1"></div>
                                    </div>
                                </div>
                            </td>
                            <td><input type="number" step="0.01" min="0" name="lines[0][unit_price]"
                                    class="form-control"></td>
                            <td><input type="number" step="0.01" min="0" name="lines[0][total]" class="form-control">
                            </td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger removeRow">X</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-outline-success mt-2" id="addRow">+ Add Line</button>
            </div>

            <div class="text-end mt-3">
                <button type="submit" name="create_rfq" class="btn btn-outline-primary">Save RFQ</button>
                <button title="Cancel" onclick="history.go(-1); return false;"
                    class="btn btn-outline-secondary">Cancel</button>
            </div>

        </form>
    </div>
</div>

<script>
    const quote_datePickerId = document.getElementById('quote_date');
    quote_datePickerId.min = new Date().toISOString().split("T")[0];
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


<?php include("../templates/footer.php"); ?>