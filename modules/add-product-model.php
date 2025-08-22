<div class="modal fade" id="newProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="newProductForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><input class="form-control" name="name" placeholder="Product Name"
                                required></div>
                        <div class="col-md-3"><input class="form-control" type="number" min="0" step="0.01"
                                name="sales_price" placeholder="Sales Price"></div>
                        <div class="col-md-3"><input class="form-control" type="number" min="0" step="0.01"
                                name="cost_price" placeholder="Cost Price"></div>
                        <div class="col-md-12">
                            <label class="form-label">Upload Catalog (PDF)</label>
                            <input class="form-control" type="file" name="catalog_file" accept="application/pdf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>