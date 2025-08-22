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
    $product = trim($_POST['product']);
    $qty = $_POST['qty'];
    $unit_price = $_POST['unit_price'];
    $cost_price = $_POST['cost_price'];

    $catalog_file = null;

    if (!empty($_FILES['catalog_file']['name'])) {
        $upload_dir = "../uploads/catalogs/";
        if (!is_dir($upload_dir))
            mkdir($upload_dir, 0777, true);

        $file_name = time() . "_" . basename($_FILES['catalog_file']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['catalog_file']['tmp_name'], $target_file)) {
            $catalog_file = "uploads/catalogs/" . $file_name;
        }
    } else {
        if (!empty($_POST['existing_catalog'])) {
            $catalog_file = $_POST['existing_catalog'];
        }
    }


    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE name = '$product' LIMIT 1"));

    if (!$check) {
        echo "<script>alert('Invalid product selected. Please choose a product from the list.'); window.location.href='rfq_edit.php?id=$rfq_id';</script>";
        exit;
    }

    if ($cost_price === '' || $cost_price === null) {
        $cost_price = $unit_price;
    }

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

            <form method="post" enctype="multipart/form-data" class="row g-2 mb-3">
                <div class="col-md-3 position-relative">
                    <input type="text" name="product" id="productSearch" class="form-control" placeholder="Product"
                        autocomplete="off" required>
                    <div id="productDropdown" class="list-group position-absolute w-100"
                        style="z-index:1000; display:none;"></div>
                </div>
                <div class="col-md-2">
                    <input type="number" min="0" value="1" name="qty" class="form-control" placeholder="Qty" required>
                </div>
                <div class="col-md-2">
                    <input type="number" min="0" step="0.01" name="unit_price" class="form-control"
                        placeholder="Unit Price" required>
                </div>
                <div class="col-md-2">
                    <input type="number" min="0" step="0.01" name="cost_price" class="form-control"
                        placeholder="Cost Price">
                </div>
                <div class="col-md-3">
                    <input type="file" name="catalog_file" class="form-control mb-1" accept="application/pdf">

                    <input type="hidden" name="existing_catalog" id="existingCatalog">

                    <a href="#" target="_blank" id="catalogLink" class="btn btn-sm btn-outline-secondary w-100"
                        style="display:none;">
                        View Catalog
                    </a>
                </div>

                <div class="col-12 mt-2">
                    <button class="btn btn-outline-success" name="add_line">Add Line</button>
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
                            <td>
                                <?php if ($line['catalog_file']) { ?>
                                    <a href="../<?= htmlspecialchars($line['catalog_file']) ?>" target="_blank"
                                        class="btn btn-sm btn-outline-secondary">View PDF</a>
                                <?php } else { ?>
                                    <span class="text-muted">No File</span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="?id=<?= $rfq_id ?>&delete_line=<?= $line['id'] ?>" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Delete this line?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const productInput = document.getElementById("productSearch");
        const dropdown = document.getElementById("productDropdown");
        const modal = document.getElementById("newProductModal");

        let timeout = null;

        productInput.addEventListener("input", function () {
            clearTimeout(timeout);
            let term = this.value;
            if (term.length < 2) {
                dropdown.style.display = "none";
                return;
            }
            timeout = setTimeout(() => {
                fetch("search-products.php?term=" + encodeURIComponent(term))
                    .then(res => res.json())
                    .then(data => {
                        dropdown.innerHTML = "";
                        if (data.length > 0) {
                            data.forEach(p => {
                                let option = document.createElement("a");
                                option.href = "#";
                                option.className = "list-group-item list-group-item-action";
                                option.textContent = p.name;
                                option.addEventListener("click", function (e) {
                                    e.preventDefault();
                                    productInput.value = p.name;
                                    document.querySelector("input[name='unit_price']").value = p.sales_price || "";
                                    document.querySelector("input[name='cost_price']").value = p.cost_price || "";
                                    const existingCatalogInput = document.getElementById("existingCatalog");
                                    const catalogLink = document.getElementById("catalogLink");

                                    if (p.catalog_file) {
                                        existingCatalogInput.value = p.catalog_file;
                                        catalogLink.href = "../" + p.catalog_file;
                                        catalogLink.style.display = "block";
                                    } else {
                                        existingCatalogInput.value = "";
                                        catalogLink.style.display = "none";
                                    }

                                    dropdown.style.display = "none";
                                });
                                dropdown.appendChild(option);
                            });
                        } else {
                            let addOption = document.createElement("a");
                            addOption.href = "#";
                            addOption.className = "list-group-item list-group-item-action text-primary";
                            addOption.textContent = "- Add Product -";
                            addOption.addEventListener("click", function (e) {
                                e.preventDefault();
                                dropdown.style.display = "none";
                                new bootstrap.Modal(modal).show();
                            });
                            dropdown.appendChild(addOption);
                        }
                        dropdown.style.display = "block";
                    });
            }, 300);
        });

        document.getElementById("newProductForm").addEventListener("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch("save-product.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        productInput.value = data.name;
                        bootstrap.Modal.getInstance(modal).hide();
                        this.reset();
                    } else {
                        alert("Error saving product");
                    }
                });
        });
    });
</script>


<?php include("add-product-model.php"); ?>
<?php include("../templates/footer.php"); ?>