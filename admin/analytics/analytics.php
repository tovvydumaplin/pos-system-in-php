<?php include('../includes/header.php'); ?>
<?php
if($_SESSION['loggedInUser']['user_type'] == 'staff') {
    redirect('../index.php', 'Access denied for staff accounts.');
}

include('analytics-backend.php');
?>

<div class="container-fluid px-4">

    <!-- PAGE HEADER -->
    <div class="dashboard-header mt-4 mb-4">
        <div>
            <p class="dashboard-kicker mb-1">Reports</p>
            <h1 class="mb-1">Analytics & Reports</h1>
            <p class="text-muted mb-0">
                <?= date('M d', strtotime($startDate)); ?> &ndash; <?= date('M d, Y', strtotime($endDate)); ?>
            </p>
            <?php if (!$isSuperAdmin && !empty($_SESSION['loggedInUser']['branch_name'])): ?>
                <span class="badge bg-primary mt-1">
                    <i class="fas fa-building me-1"></i>
                    <?= htmlspecialchars($_SESSION['loggedInUser']['branch_name']); ?>
                </span>
            <?php elseif ($isSuperAdmin): ?>
                <span class="badge bg-danger mt-1">Super Admin View</span>
            <?php endif; ?>
        </div>
        <div class="dashboard-actions">
            <a href="analytics-export.php?type=excel<?= isset($_GET['branch_id']) ? '&branch_id='.htmlspecialchars($_GET['branch_id']) : ''; ?><?= isset($_GET['start_date']) ? '&start_date='.htmlspecialchars($_GET['start_date']) : ''; ?><?= isset($_GET['end_date']) ? '&end_date='.htmlspecialchars($_GET['end_date']) : ''; ?>"
               class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="analytics-export.php?type=pdf<?= isset($_GET['branch_id']) ? '&branch_id='.htmlspecialchars($_GET['branch_id']) : ''; ?><?= isset($_GET['start_date']) ? '&start_date='.htmlspecialchars($_GET['start_date']) : ''; ?><?= isset($_GET['end_date']) ? '&end_date='.htmlspecialchars($_GET['end_date']) : ''; ?>"
               class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <?php alertMessage(); ?>

    <!-- FILTERS -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="" method="GET">
                <div class="row g-2 align-items-end">

                    <?php if($isSuperAdmin): ?>
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">All Branches</option>
                            <?php
                            $branches = getAll('branches');
                            if($branches && mysqli_num_rows($branches) > 0){
                                foreach($branches as $branch){
                                    $selected = ($selectedBranch == $branch['id']) ? 'selected' : '';
                                    echo '<option value="'.$branch['id'].'" '.$selected.'>'.$branch['branch_name'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="col-md">
                        <label class="form-label small mb-1">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $startDate; ?>" />
                    </div>

                    <div class="col-md">
                        <label class="form-label small mb-1">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $endDate; ?>" />
                    </div>

                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="analytics.php" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- OVERVIEW STATS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card dashboard-stat">
                <div class="card-body">
                    <div class="stat-icon stat-icon-green">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    <p class="stat-label">Total Sales</p>
                    <h2>₱<?= number_format($totalSales, 2); ?></h2>
                    <span class="stat-note"><?= date('M d', strtotime($startDate)); ?> &ndash; <?= date('M d, Y', strtotime($endDate)); ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-stat">
                <div class="card-body">
                    <div class="stat-icon stat-icon-blue">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <p class="stat-label">Total Orders</p>
                    <h2><?= number_format($totalOrders); ?></h2>
                    <span class="stat-note"><?= date('M d', strtotime($startDate)); ?> &ndash; <?= date('M d, Y', strtotime($endDate)); ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-stat">
                <div class="card-body">
                    <div class="stat-icon stat-icon-purple">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <p class="stat-label">Average Order Value</p>
                    <h2>₱<?= number_format($averageOrderValue, 2); ?></h2>
                    <span class="stat-note">Per transaction</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DAILY SALES CHART + PAYMENT METHODS -->
    <div class="row g-3 mb-4">

        <div class="col-xl-8">
            <div class="card dashboard-panel h-100">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i> Daily Sales Trend
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dashboard-panel h-100">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-1"></i> Payment Methods
                </div>
                <div class="card-body">
                    <?php if($paymentMethodResult && mysqli_num_rows($paymentMethodResult) > 0): ?>
                        <div class="summary-list">
                            <?php
                            $paymentColors = [
                                'Cash Payment'   => 'success',
                                'Online Payment' => 'info'
                            ];
                            foreach($paymentMethodResult as $payment):
                            ?>
                            <div class="summary-item">
                                <span>
                                    <span class="badge bg-<?= $paymentColors[$payment['payment_mode']] ?? 'secondary' ?> me-2">
                                        <?= $payment['order_count']; ?>
                                    </span>
                                    <?= $payment['payment_mode']; ?>
                                </span>
                                <strong>₱<?= number_format($payment['total_sales'], 2); ?></strong>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No payment data</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- BRANCH PERFORMANCE (Super Admin Only) -->
    <?php if($isSuperAdmin && $branchPerformance): ?>
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card dashboard-panel">
                <div class="card-header">
                    <i class="fas fa-building me-1"></i> Branch Performance
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dashboard-table mb-0">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Total Sales</th>
                                    <th class="text-end">Avg Order Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($branchPerformance) > 0): ?>
                                    <?php foreach($branchPerformance as $branch):
                                        $avgValue = $branch['order_count'] > 0 ? $branch['total_sales'] / $branch['order_count'] : 0;
                                    ?>
                                        <tr>
                                            <td class="fw-semibold"><?= $branch['branch_name']; ?></td>
                                            <td class="text-center"><?= number_format($branch['order_count']); ?></td>
                                            <td class="text-end">₱<?= number_format($branch['total_sales'], 2); ?></td>
                                            <td class="text-end">₱<?= number_format($avgValue, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No branch data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- TOP SERVICES + TOP CUSTOMERS -->
    <div class="row g-3 mb-4">

        <div class="col-xl-6">
            <div class="card dashboard-panel">
                <div class="card-header">
                    <i class="fas fa-star me-1"></i> Top Services
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dashboard-table mb-0">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($topServicesResult && mysqli_num_rows($topServicesResult) > 0): ?>
                                    <?php foreach($topServicesResult as $service): ?>
                                        <tr>
                                            <td><?= $service['name']; ?></td>
                                            <td class="text-center"><?= $service['times_ordered']; ?></td>
                                            <td class="text-center"><?= $service['total_quantity']; ?></td>
                                            <td class="text-end fw-semibold">₱<?= number_format($service['total_revenue'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No service data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card dashboard-panel">
                <div class="card-header">
                    <i class="fas fa-users me-1"></i> Top Customers
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dashboard-table mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($topCustomersResult && mysqli_num_rows($topCustomersResult) > 0): ?>
                                    <?php foreach($topCustomersResult as $customer): ?>
                                        <tr>
                                            <td>
                                                <?= $customer['name']; ?>
                                                <small class="d-block text-muted"><?= $customer['phone']; ?></small>
                                            </td>
                                            <td class="text-center"><?= $customer['order_count']; ?></td>
                                            <td class="text-end fw-semibold">₱<?= number_format($customer['total_spent'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No customer data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart');
const salesData = {
    labels: [
        <?php
        mysqli_data_seek($dailySalesResult, 0);
        while($row = mysqli_fetch_assoc($dailySalesResult)) {
            echo "'".date('M d', strtotime($row['order_date']))."',";
        }
        ?>
    ],
    datasets: [{
        label: 'Daily Sales (₱)',
        data: [
            <?php
            mysqli_data_seek($dailySalesResult, 0);
            while($row = mysqli_fetch_assoc($dailySalesResult)) {
                echo $row['total_sales'].",";
            }
            ?>
        ],
        borderColor: 'rgb(37, 99, 235)',
        backgroundColor: 'rgba(37, 99, 235, 0.1)',
        tension: 0.4,
        fill: true
    }]
};

new Chart(ctx, {
    type: 'line',
    data: salesData,
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return '₱' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

<?php include('../includes/footer.php'); ?>
