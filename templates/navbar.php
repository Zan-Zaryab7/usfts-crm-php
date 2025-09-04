<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/crm/public/dashboard.php">
            <img src="/crm/assets/USFTS.png" alt="Logo" width="35" height="35"
                class="d-inline-block align-text-top me-2">
            US Forces Tactical Supply
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/crm/public/dashboard.php">Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="addDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Add
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="addDropdown">
                        <li><a class="dropdown-item" href="/crm/modules/customers.php">Customers</a></li>
                        <li><a class="dropdown-item" href="/crm/modules/salesPerson.php">Sales Person</a></li>
                        <li><a class="dropdown-item" href="/crm/modules/billTo.php">Bill To</a></li>
                        <li><a class="dropdown-item" href="/crm/modules/buyer.php">Buyer</a></li>
                        <li><a class="dropdown-item" href="/crm/modules/shipTo.php">Ship To</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="/crm/modules/sales.php">Sales</a></li>
                <li class="nav-item"><a class="nav-link" href="/crm/modules/products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="/crm/modules/rfqs.php">RFQs</a></li>
                <li class="nav-item"><a class="nav-link" href="/crm/modules/orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="/crm/modules/invoices.php">Invoices</a></li>
                <li class="nav-item"><a class="nav-link" href="/crm/public/logout.php">Logout</a></li>
                </li>
            </ul>
        </div>
    </div>
</nav>