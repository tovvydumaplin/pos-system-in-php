<?php include('../includes/header.php'); ?>
<?php
$branchId = $_SESSION['loggedInUser']['branch_id'];
$userType = $_SESSION['loggedInUser']['user_type'];
$isSuperAdmin = ($userType == 'super_admin');
?>

<style>
    .orders-hero {
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .orders-hero h2 {
        color: #2d3250;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
    }
    .orders-hero .hero-sub {
        color: #9ba3b8;
        font-size: 0.85rem;
        margin: 0.2rem 0 0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .orders-hero .badge-admin {
        background: #fdecea;
        color: #d63031;
        font-size: 0.72rem;
        padding: 0.22rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .filters-panel {
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        padding: 1.1rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .filters-panel .form-control,
    .filters-panel .form-select {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.85rem;
        background: #fff;
    }
    .filters-panel .form-control:focus,
    .filters-panel .form-select:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }
    .btn-filter {
        background: #2d3250;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        transition: background 0.2s;
    }
    .btn-filter:hover {
        background: #424870;
        color: #fff;
    }
    .btn-reset {
        background: #fff;
        color: #6b7189;
        border: 1px solid #dde1ec;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .btn-reset:hover {
        background: #f0f2f8;
        color: #2d3250;
    }

    .orders-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .orders-table {
        margin: 0;
        font-size: 0.875rem;
    }
    .orders-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .orders-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.85rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .orders-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.12s;
    }
    .orders-table tbody tr:last-child {
        border-bottom: none;
    }
    .orders-table tbody tr:hover {
        background: #f8f9fc;
    }
    .orders-table td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border: none;
        color: #2d3250;
    }

    .tracking-no {
        font-weight: 700;
        font-family: monospace;
        font-size: 0.85rem;
        color: #2d3250;
        letter-spacing: 0.03em;
    }

    .customer-name {
        font-weight: 600;
        color: #2d3250;
    }
    .customer-phone {
        font-size: 0.8rem;
        color: #9ba3b8;
    }

    .branch-pill {
        background: #eef2ff;
        color: #4361ee;
        border-radius: 6px;
        font-size: 0.75rem;
        padding: 0.22rem 0.6rem;
        font-weight: 600;
    }

    .order-date {
        color: #6b7189;
        font-size: 0.83rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.28rem 0.75rem;
    }
    .status-cancelled  { background: #fdecea; color: #d63031; }
    .status-booked     { background: #e8f4fd; color: #2980b9; }
    .status-released   { background: #e6f9f0; color: #1a9e5f; }
    .status-default    { background: #f0f2f8; color: #6b7189; }

    .payment-badge {
        font-size: 0.78rem;
        color: #6b7189;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .payment-badge i {
        font-size: 0.7rem;
        opacity: 0.7;
    }

    .btn-view {
        background: #eef2ff;
        color: #4361ee;
        border: none;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        text-decoration: none;
        transition: background 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .btn-view:hover {
        background: #dce4ff;
        color: #4361ee;
    }
    .btn-print {
        background: #f4f6ff;
        color: #2d3250;
        border: 1px solid #dde1ec;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        text-decoration: none;
        transition: background 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .btn-print:hover {
        background: #e8eaf8;
        color: #2d3250;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ba3b8;
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.35;
        display: block;
    }
    .empty-state h5 {
        font-weight: 600;
        color: #6b7189;
        margin-bottom: 0.3rem;
    }
    .empty-state p {
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid px-4 mt-4">

    <!-- HERO -->
    <div class="orders-hero">
        <div>
            <h2><i class="fas fa-receipt me-2" style="opacity:0.75;"></i>Orders</h2>
            <div class="hero-sub">
                <?php if ($isSuperAdmin): ?>
                    <i class="fas fa-layer-group"></i> All Branches
                    <span class="badge-admin">Super Admin View</span>
                <?php else: ?>
                    <i class="fas fa-code-branch"></i>
                    <?= htmlspecialchars($_SESSION['loggedInUser']['branch_name'] ?? 'Unassigned Branch') ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="filters-panel">
        <form action="" method="GET">
            <div class="row g-2 align-items-end">

                <?php if ($isSuperAdmin): ?>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">Branch</label>
                    <select name="branch_filter" class="form-select">
                        <option value="">All Branches</option>
                        <?php
                        $branches = getAll('branches');
                        if ($branches && mysqli_num_rows($branches) > 0) {
                            foreach ($branches as $branch) {
                                $selected = (isset($_GET['branch_filter']) && $_GET['branch_filter'] == $branch['id']) ? 'selected' : '';
                                echo '<option value="' . $branch['id'] . '" ' . $selected . '>' . htmlspecialchars($branch['branch_name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md">
                    <label class="form-label text-muted small fw-semibold mb-1">Date</label>
                    <input type="date" name="date" class="form-control"
                           value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>">
                </div>

                <div class="col-md">
                    <label class="form-label text-muted small fw-semibold mb-1">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All Payment Types</option>
                        <option value="Cash Payment" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'Cash Payment') ? 'selected' : '' ?>>Cash Payment</option>
                        <option value="Online Payment" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'Online Payment') ? 'selected' : '' ?>>Online Payment</option>
                    </select>
                </div>

                <div class="col-md">
                    <label class="form-label text-muted small fw-semibold mb-1">Tracking No.</label>
                    <input type="text" name="tracking_no" class="form-control"
                           placeholder="Search tracking no..."
                           value="<?= isset($_GET['tracking_no']) ? htmlspecialchars($_GET['tracking_no']) : '' ?>">
                </div>

                <div class="col-md-auto">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="orders.php" class="btn-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- TABLE -->
    <?php
    $query = "SELECT o.*, c.*
              FROM orders o
              JOIN customers c ON c.id = o.customer_id
              WHERE 1=1";

    if ($isSuperAdmin) {
        if (isset($_GET['branch_filter']) && $_GET['branch_filter'] != '') {
            $selectedBranch = validate($_GET['branch_filter']);
            $query .= " AND o.branch_id='$selectedBranch'";
        }
    } else {
        $query .= " AND o.branch_id='$branchId'";
    }

    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = validate($_GET['date']);
        $query .= " AND o.order_date='$date'";
    }

    if (isset($_GET['payment_status']) && $_GET['payment_status'] != '') {
        $payment = validate($_GET['payment_status']);
        $query .= " AND o.payment_mode='$payment'";
    }

    if (isset($_GET['tracking_no']) && $_GET['tracking_no'] != '') {
        $track = validate($_GET['tracking_no']);
        $query .= " AND o.tracking_no LIKE '%$track%'";
    }

    $query .= " ORDER BY o.id DESC";
    $orders = mysqli_query($conn, $query);
    ?>

    <?php if ($orders && mysqli_num_rows($orders) > 0): ?>
    <div class="orders-table-wrap">
        <div class="table-responsive">
            <table class="table orders-table">
                <thead>
                    <tr>
                        <th>Tracking No.</th>
                        <th>Customer</th>
                        <?php if ($isSuperAdmin): ?><th>Branch</th><?php endif; ?>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $orderItem):
                        $branchName = '-';
                        if ($isSuperAdmin && !empty($orderItem['branch_id'])) {
                            $branchData = getById('branches', $orderItem['branch_id']);
                            if ($branchData && $branchData['status'] == 200) {
                                $branchName = $branchData['data']['branch_name'];
                            }
                        }

                        $status = strtolower($orderItem['order_status']);
                        if ($status == 'cancelled')     $statusClass = 'status-cancelled';
                        elseif ($status == 'booked')    $statusClass = 'status-booked';
                        elseif ($status == 'released')  $statusClass = 'status-released';
                        else                            $statusClass = 'status-default';

                        $paymentIcon = ($orderItem['payment_mode'] == 'Online Payment') ? 'fa-wifi' : 'fa-money-bill-wave';
                    ?>
                    <tr>
                        <td><span class="tracking-no"><?= htmlspecialchars($orderItem['tracking_no']) ?></span></td>
                        <td>
                            <div class="customer-name"><?= htmlspecialchars($orderItem['name']) ?></div>
                            <div class="customer-phone"><?= htmlspecialchars($orderItem['phone']) ?></div>
                        </td>
                        <?php if ($isSuperAdmin): ?>
                        <td><span class="branch-pill"><?= htmlspecialchars($branchName) ?></span></td>
                        <?php endif; ?>
                        <td><span class="order-date"><?= date('d M, Y', strtotime($orderItem['order_date'])) ?></span></td>
                        <td>
                            <span class="status-badge <?= $statusClass ?>">
                                <?= ucfirst($orderItem['order_status']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="payment-badge">
                                <i class="fas <?= $paymentIcon ?>"></i>
                                <?= htmlspecialchars($orderItem['payment_mode']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="orders-view.php?track=<?= $orderItem['tracking_no'] ?>" class="btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="orders-view-print.php?track=<?= $orderItem['tracking_no'] ?>" class="btn-print">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php else: ?>
    <div class="orders-table-wrap">
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <h5>No Orders Found</h5>
            <p>Try adjusting your filters or check back later.</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>
