<?php
// include("../config/database.php");
include("../includes/auth.php");
include("../templates/header.php");
include("../templates/navbar.php");
check_auth();

if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id=$delete_id");
    header("Location: products.php");
    exit;
}

$products = mysqli_query($conn, "
    SELECT * 
    FROM products 
    ORDER BY id DESC
");
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h4>Products</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProductModal">New Product</button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Sales Price</th>
                            <th>Cost Price</th>
                            <th>Catalog</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = mysqli_fetch_assoc($products)) { ?>
                            <tr onclick="window.location='product_edit.php?id=<?= $p['id'] ?>'">
                                <td><?= htmlspecialchars($p['code']) ?></td>
                                <td><?= htmlspecialchars($p['name']) ?></td>
                                <td><?= number_format($p['sales_price'], 2) ?></td>
                                <td><?= number_format($p['cost_price'], 2) ?></td>
                                <td>
                                    <?php if (!empty($p['catalog_file'])) { ?>
                                        <a href="../<?= htmlspecialchars($p['catalog_file']) ?>" target="_blank"
                                            class="btn btn-sm btn-outline-secondary">
                                            View Catalog
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-muted">No File</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="products.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("add-product-model.php"); ?>

<script>
    document.getElementById("newProductForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch("save-product.php", { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const lastInput = document.querySelector(".productSearch:last-of-type");
                    if (lastInput) lastInput.value = data.name;
                    bootstrap.Modal.getInstance(document.getElementById("newProductModal")).hide();
                    this.reset();
                } else {
                    alert("Error saving product");
                }
            });
    });
</script>

<?php include("../templates/footer.php"); ?>