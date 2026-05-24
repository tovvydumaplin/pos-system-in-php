<?php include('includes/header.php'); ?>

<?php
$todayDate    = date('Y-m-d');
$userType     = $_SESSION['loggedInUser']['user_type'];
$userBranchId = $_SESSION['loggedInUser']['branch_id'] ?? null;
$isSuperAdmin = ($userType === 'super_admin');

$orderBranchWhere = $isSuperAdmin ? '1=1' : "branch_id='" . (int)$userBranchId . "'";
$userBranchWhere  = $isSuperAdmin ? '' : " AND branch_id='" . (int)$userBranchId . "'";

function dashboardCountWhere($tableName, $whereClause)
{
    global $conn;
    $table  = validate($tableName);
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $table WHERE $whereClause");
    return $result ? mysqli_fetch_assoc($result)['total'] : 0;
}

function dashboardOrderTotal($whereClause = '1=1')
{
    global $conn;
    $result = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE $whereClause");
    return $result ? mysqli_fetch_assoc($result)['total'] : 0;
}

$adminCount      = dashboardCountWhere('users', "user_type='admin'" . $userBranchWhere);
$staffCount      = dashboardCountWhere('users', "user_type='staff'" . $userBranchWhere);
$totalUsers      = dashboardCountWhere('users', "1=1" . $userBranchWhere);

$notCancelled    = "order_status != 'cancelled'";
$todayOrders     = dashboardCountWhere('orders', "order_date='$todayDate' AND $notCancelled AND $orderBranchWhere");
$totalOrders     = dashboardCountWhere('orders', "$notCancelled AND $orderBranchWhere");
$cancelledOrders = dashboardCountWhere('orders', "order_status='cancelled' AND $orderBranchWhere");
$todaySales      = dashboardOrderTotal("order_date='$todayDate' AND $notCancelled AND $orderBranchWhere");
$totalSales      = dashboardOrderTotal("$notCancelled AND $orderBranchWhere");

$recentOrders = mysqli_query($conn, "
    SELECT o.*, c.name, c.phone
    FROM orders o
    LEFT JOIN customers c ON c.id = o.customer_id
    WHERE $notCancelled AND " . ($isSuperAdmin ? '1=1' : "o.branch_id='" . (int)$userBranchId . "'") . "
    ORDER BY o.id DESC
    LIMIT 5
");
?>

<style>
    .page-hero {
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
    .page-hero h2 { color: #2d3250; font-size: 1.4rem; font-weight: 700; margin: 0; }
    .page-hero .hero-date { color: #9ba3b8; font-size: 0.85rem; margin: 0.2rem 0 0; }
    .hero-badge-branch {
        background: #eef2ff; color: #4361ee;
        font-size: 0.72rem; font-weight: 600;
        padding: 0.22rem 0.6rem; border-radius: 6px;
        display: inline-flex; align-items: center; gap: 0.3rem;
        margin-top: 0.4rem;
    }
    .btn-new-order {
        background: #2d3250; color: #fff; border: none;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.55rem 1.25rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.2s; white-space: nowrap;
    }
    .btn-new-order:hover { background: #424870; color: #fff; }
    .btn-view-orders {
        background: #fff; color: #2d3250; border: 1px solid #dde1ec;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.55rem 1.25rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.15s; white-space: nowrap;
    }
    .btn-view-orders:hover { background: #f0f2f8; color: #2d3250; }

    /* Stat cards */
    .stat-card {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        padding: 1.4rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        height: 100%;
    }
    .stat-card .s-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0;
    }
    .s-icon.blue   { background: #eef2ff; color: #4361ee; }
    .s-icon.green  { background: #e6f9f0; color: #1a9e5f; }
    .s-icon.cyan   { background: #e8f7fd; color: #0a9ec9; }
    .s-icon.purple { background: #f4eeff; color: #8b5cf6; }
    .stat-card .s-label {
        font-size: 0.73rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.05em; color: #9ba3b8; margin: 0;
    }
    .stat-card .s-value {
        font-size: 1.75rem; font-weight: 800; color: #2d3250;
        line-height: 1.1; margin: 0;
    }
    .stat-card .s-note { font-size: 0.78rem; color: #9ba3b8; }
    .stat-card .s-note .text-danger { color: #d63031 !important; }

    /* Data panels */
    .data-panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .data-panel-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid #f0f2f8;
        background: #fafbfc;
        font-weight: 700; font-size: 0.88rem; color: #2d3250;
    }
    .data-panel-header i { color: #9ba3b8; font-size: 0.82rem; }
    .btn-see-all {
        background: #f4f6fb; color: #6b7189; border: none;
        border-radius: 7px; font-size: 0.78rem; font-weight: 600;
        padding: 0.3rem 0.8rem; text-decoration: none;
        transition: background 0.15s;
    }
    .btn-see-all:hover { background: #eef2ff; color: #4361ee; }

    /* Recent orders table */
    .dash-table { margin: 0; font-size: 0.855rem; }
    .dash-table thead tr { background: #f4f6fb; border-bottom: 2px solid #e6e9f0; }
    .dash-table thead th {
        color: #6b7189; font-weight: 600; font-size: 0.73rem;
        text-transform: uppercase; letter-spacing: 0.04em;
        padding: 0.75rem 1rem; border: none; white-space: nowrap;
    }
    .dash-table tbody tr { border-bottom: 1px solid #f0f2f8; transition: background 0.1s; }
    .dash-table tbody tr:last-child { border-bottom: none; }
    .dash-table tbody tr:hover { background: #f8f9fc; }
    .dash-table td { padding: 0.75rem 1rem; vertical-align: middle; border: none; color: #2d3250; }

    .tracking-no   { font-weight: 700; font-family: monospace; font-size: 0.82rem; }
    .customer-name { font-weight: 600; }
    .customer-phone{ font-size: 0.78rem; color: #9ba3b8; }
    .order-date    { font-size: 0.82rem; color: #6b7189; }
    .amount-val    { font-weight: 700; color: #2d3250; }

    .status-badge {
        display: inline-flex; align-items: center;
        border-radius: 20px; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.7rem;
    }
    .status-booked   { background: #e8f4fd; color: #2980b9; }
    .status-released { background: #e6f9f0; color: #1a9e5f; }
    .status-cancelled{ background: #fdecea; color: #d63031; }
    .status-default  { background: #f0f2f8; color: #6b7189; }

    /* Summary panels */
    .summary-panel-body { padding: 0.25rem 0; }
    .summary-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.7rem 1.25rem;
        border-bottom: 1px solid #f0f2f8;
        font-size: 0.875rem;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-row .s-left {
        display: flex; align-items: center; gap: 0.6rem; color: #4a5072;
    }
    .summary-row .s-left .row-icon {
        width: 28px; height: 28px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem; flex-shrink: 0;
    }
    .row-icon.indigo { background: #eef2ff; color: #4361ee; }
    .row-icon.green  { background: #e6f9f0; color: #1a9e5f; }
    .row-icon.amber  { background: #fff8ec; color: #e67e22; }
    .row-icon.slate  { background: #f4f6fb; color: #6b7189; }
    .row-icon.purple { background: #f4eeff; color: #8b5cf6; }
    .summary-row .row-label { font-weight: 600; font-size: 0.85rem; }
    .summary-row .row-value { font-weight: 800; color: #2d3250; font-size: 1rem; }

    .empty-row td { text-align: center; color: #9ba3b8; font-style: italic; font-size: 0.82rem; padding: 2.5rem !important; }
</style>

<div class="container-fluid px-4 mt-4">

    <?php alertMessage(); ?>

    <!-- HERO -->
    <div class="page-hero mb-4">
        <div>
            <h2><i class="fas fa-gauge-high me-2" style="opacity:0.75;"></i>Dashboard</h2>
            <p class="hero-date">Today is <?= date('F d, Y') ?></p>
            <?php if (!$isSuperAdmin && !empty($_SESSION['loggedInUser']['branch_name'])): ?>
                <span class="hero-badge-branch">
                    <i class="fas fa-code-branch"></i>
                    <?= htmlspecialchars($_SESSION['loggedInUser']['branch_name']) ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="pos/order-create.php" class="btn-new-order">
                <i class="fas fa-plus"></i> New Order
            </a>
            <a href="pos/orders.php" class="btn-view-orders">
                <i class="fas fa-list"></i> View Orders
            </a>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="s-icon blue"><i class="fas fa-calendar-day"></i></div>
                <p class="s-label">Today's Orders</p>
                <p class="s-value"><?= $todayOrders ?></p>
                <span class="s-note">Orders booked today</span>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="s-icon green"><i class="fas fa-receipt"></i></div>
                <p class="s-label">Total Orders</p>
                <p class="s-value"><?= $totalOrders ?></p>
                <span class="s-note">
                    <?php if ($cancelledOrders > 0): ?>
                        <span class="text-danger"><?= $cancelledOrders ?> cancelled</span>
                    <?php else: ?>
                        All recorded orders
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="s-icon cyan"><i class="fas fa-peso-sign"></i></div>
                <p class="s-label">Today's Sales</p>
                <p class="s-value">₱<?= number_format($todaySales, 2) ?></p>
                <span class="s-note">Revenue from today</span>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="s-icon purple"><i class="fas fa-chart-line"></i></div>
                <p class="s-label">Total Sales</p>
                <p class="s-value">₱<?= number_format($totalSales, 2) ?></p>
                <span class="s-note">Lifetime sales total</span>
            </div>
        </div>

    </div>

    <!-- RECENT ORDERS + SIDE PANELS -->
    <div class="row g-3">

        <!-- RECENT ORDERS -->
        <div class="col-xl-8">
            <div class="data-panel">
                <div class="data-panel-header">
                    <span><i class="fas fa-clock me-2"></i>Recent Orders</span>
                    <a href="pos/orders.php" class="btn-see-all">See All</a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table">
                        <thead>
                            <tr>
                                <th>Tracking No.</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recentOrders && mysqli_num_rows($recentOrders) > 0): ?>
                                <?php foreach ($recentOrders as $order):
                                    $status = strtolower($order['order_status']);
                                    if ($status == 'booked')        $sBadge = 'status-booked';
                                    elseif ($status == 'released')  $sBadge = 'status-released';
                                    elseif ($status == 'cancelled') $sBadge = 'status-cancelled';
                                    else                            $sBadge = 'status-default';
                                ?>
                                <tr>
                                    <td><span class="tracking-no"><?= htmlspecialchars($order['tracking_no']) ?></span></td>
                                    <td>
                                        <span class="customer-name"><?= htmlspecialchars($order['name'] ?? 'Walk-in') ?></span>
                                        <span class="customer-phone d-block"><?= htmlspecialchars($order['phone'] ?? '') ?></span>
                                    </td>
                                    <td><span class="order-date"><?= date('M d, Y', strtotime($order['order_date'])) ?></span></td>
                                    <td><span class="status-badge <?= $sBadge ?>"><?= ucfirst($order['order_status']) ?></span></td>
                                    <td class="text-end"><span class="amount-val">₱<?= number_format($order['total_amount'], 2) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="empty-row">No recent orders found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SIDE PANELS -->
        <div class="col-xl-4 d-flex flex-column gap-3">

            <!-- Business Records -->
            <div class="data-panel">
                <div class="data-panel-header">
                    <span><i class="fas fa-folder-open me-2"></i>Business Records</span>
                </div>
                <div class="summary-panel-body">
                    <div class="summary-row">
                        <div class="s-left">
                            <span class="row-icon indigo"><i class="fas fa-concierge-bell"></i></span>
                            <span class="row-label">Services</span>
                        </div>
                        <span class="row-value"><?= dashboardCountWhere('services', $orderBranchWhere) ?></span>
                    </div>
                    <div class="summary-row">
                        <div class="s-left">
                            <span class="row-icon green"><i class="fas fa-user-friends"></i></span>
                            <span class="row-label">Customers</span>
                        </div>
                        <span class="row-value"><?= dashboardCountWhere('customers', $orderBranchWhere) ?></span>
                    </div>
                    <?php if ($isSuperAdmin): ?>
                    <div class="summary-row">
                        <div class="s-left">
                            <span class="row-icon amber"><i class="fas fa-code-branch"></i></span>
                            <span class="row-label">Branches</span>
                        </div>
                        <span class="row-value"><?= getCount('branches') ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Users -->
            <div class="data-panel">
                <div class="data-panel-header">
                    <span><i class="fas fa-users me-2"></i>Users</span>
                </div>
                <div class="summary-panel-body">
                    <div class="summary-row">
                        <div class="s-left">
                            <span class="row-icon purple"><i class="fas fa-user-shield"></i></span>
                            <span class="row-label">Admins</span>
                        </div>
                        <span class="row-value"><?= $adminCount ?></span>
                    </div>
                    <div class="summary-row">
                        <div class="s-left">
                            <span class="row-icon slate"><i class="fas fa-user-tie"></i></span>
                            <span class="row-label">Staff</span>
                        </div>
                        <span class="row-value"><?= $staffCount ?></span>
                    </div>
                    <div class="summary-row">
                        <div class="s-left">
                            <span class="row-icon indigo"><i class="fas fa-user-group"></i></span>
                            <span class="row-label">Total Users</span>
                        </div>
                        <span class="row-value"><?= $totalUsers ?></span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?php include('includes/footer.php'); ?>
