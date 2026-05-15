<?php include('includes/header.php'); ?>

<?php
    $todayDate = date('Y-m-d');

    function dashboardCountWhere($tableName, $whereClause)
    {
        global $conn;

        $table = validate($tableName);
        $query = "SELECT COUNT(*) AS total FROM $table WHERE $whereClause";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        }

        return 0;
    }

    function dashboardOrderTotal($whereClause = '1=1')
    {
        global $conn;

        $query = "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE $whereClause";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        }

        return 0;
    }

    $adminCount = dashboardCountWhere('users', "user_type='admin'");
    $staffCount = dashboardCountWhere('users', "user_type='staff'");
    $todayOrders = dashboardCountWhere('orders', "order_date='$todayDate'");
    $todaySales = dashboardOrderTotal("order_date='$todayDate'");
    $totalSales = dashboardOrderTotal();
    $recentOrders = mysqli_query($conn, "SELECT o.*, c.name, c.phone FROM orders o LEFT JOIN customers c ON c.id = o.customer_id ORDER BY o.id DESC LIMIT 5");
?>

<div class="container-fluid px-4 dashboard-page">

    <div class="dashboard-header mt-4 mb-4">
        <div>
            <p class="dashboard-kicker mb-1">POS Overview</p>
            <h1 class="mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Today is <?= date('F d, Y'); ?></p>
        </div>

        <div class="dashboard-actions">
            <a href="pos/order-create.php" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Order
            </a>
            <a href="pos/orders.php" class="btn btn-outline-primary">
                <i class="fas fa-list me-1"></i> View Orders
            </a>
        </div>
    </div>

    <?php alertMessage(); ?>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-stat">
                <div class="card-body">
                    <div class="stat-icon stat-icon-blue">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <p class="stat-label">Today Orders</p>
                    <h2><?= $todayOrders; ?></h2>
                    <span class="stat-note">Orders booked today</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-stat">
                <div class="card-body">
                    <div class="stat-icon stat-icon-green">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <p class="stat-label">Total Orders</p>
                    <h2><?= getCount('orders'); ?></h2>
                    <span class="stat-note">All recorded orders</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-stat">
                <div class="card-body">
                    <div class="stat-icon stat-icon-cyan">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    <p class="stat-label">Today Sales</p>
                    <h2>₱<?= number_format($todaySales, 2); ?></h2>
                    <span class="stat-note">From today orders</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-stat">
                <div class="card-body">
                    <div class="stat-icon stat-icon-purple">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <p class="stat-label">Total Sales</p>
                    <h2>₱<?= number_format($totalSales, 2); ?></h2>
                    <span class="stat-note">Lifetime sales total</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card dashboard-panel">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Recent Orders</span>
                    <a href="pos/orders.php" class="btn btn-sm btn-outline-primary">See All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dashboard-table mb-0">
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
                                <?php if($recentOrders && mysqli_num_rows($recentOrders) > 0): ?>
                                    <?php foreach($recentOrders as $order): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= $order['tracking_no']; ?></td>
                                            <td>
                                                <?= $order['name'] ?? 'Walk-in Customer'; ?>
                                                <small class="d-block text-muted"><?= $order['phone'] ?? ''; ?></small>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($order['order_date'])); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= ucfirst($order['order_status']); ?></span>
                                            </td>
                                            <td class="text-end fw-semibold">₱<?= number_format($order['total_amount'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No recent orders found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dashboard-panel mb-3">
                <div class="card-header">Business Records</div>
                <div class="card-body">
                    <div class="summary-list">
                        <div class="summary-item">
                            <span><i class="fas fa-tags"></i> Categories</span>
                            <strong><?= getCount('categories'); ?></strong>
                        </div>
                        <div class="summary-item">
                            <span><i class="fas fa-shirt"></i> Services</span>
                            <strong><?= getCount('services'); ?></strong>
                        </div>
                        <div class="summary-item">
                            <span><i class="fas fa-users"></i> Customers</span>
                            <strong><?= getCount('customers'); ?></strong>
                        </div>
                        <div class="summary-item">
                            <span><i class="fas fa-building"></i> Branches</span>
                            <strong><?= getCount('branches'); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card dashboard-panel">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <div class="summary-list">
                        <div class="summary-item">
                            <span><i class="fas fa-user-shield"></i> Admins</span>
                            <strong><?= $adminCount; ?></strong>
                        </div>
                        <div class="summary-item">
                            <span><i class="fas fa-user-tie"></i> Staff</span>
                            <strong><?= $staffCount; ?></strong>
                        </div>
                        <div class="summary-item">
                            <span><i class="fas fa-user-group"></i> Total Users</span>
                            <strong><?= getCount('users'); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>
