<?php include('../includes/header.php'); ?>
<?php
if ($_SESSION['loggedInUser']['user_type'] == 'staff') {
    redirect('../index.php', 'Access denied for staff accounts.');
}
include('analytics-backend.php');
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
    .page-hero h2 {
        color: #2d3250;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
    }
    .page-hero .hero-sub {
        color: #9ba3b8;
        font-size: 0.85rem;
        margin: 0.2rem 0 0.4rem;
    }
    .hero-badge-branch {
        background: #eef2ff;
        color: #4361ee;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.22rem 0.6rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .hero-badge-admin {
        background: #fdecea;
        color: #d63031;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.22rem 0.6rem;
        border-radius: 6px;
    }
    .btn-export-excel {
        background: #e6f9f0;
        color: #1a9e5f;
        border: none;
        border-radius: 9px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.55rem 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-export-excel:hover { background: #c8f5e0; color: #1a9e5f; }
    .btn-export-pdf {
        background: #fdecea;
        color: #d63031;
        border: none;
        border-radius: 9px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.55rem 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-export-pdf:hover { background: #fbc8c8; color: #d63031; }

    .filters-panel {
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        padding: 1rem 1.5rem;
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
        cursor: pointer;
    }
    .btn-filter:hover { background: #424870; color: #fff; }
    .btn-reset {
        background: #fff;
        color: #6b7189;
        border: 1px solid #dde1ec;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: background 0.15s;
    }
    .btn-reset:hover { background: #f0f2f8; color: #2d3250; }

    /* Stat cards */
    .stat-card {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        padding: 1.4rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        height: 100%;
    }
    .stat-card .s-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .s-icon.green  { background: #e6f9f0; color: #1a9e5f; }
    .s-icon.blue   { background: #eef2ff; color: #4361ee; }
    .s-icon.purple { background: #f4eeff; color: #8b5cf6; }
    .stat-card .s-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9ba3b8;
        margin: 0;
    }
    .stat-card .s-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: #2d3250;
        line-height: 1.1;
        margin: 0;
    }
    .stat-card .s-note {
        font-size: 0.78rem;
        color: #9ba3b8;
    }
    .stat-card .s-note .text-danger { color: #d63031 !important; }

    /* Panels */
    .data-panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
    }
    .data-panel-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid #f0f2f8;
        background: #fafbfc;
        font-weight: 700;
        font-size: 0.88rem;
        color: #2d3250;
    }
    .data-panel-header i { color: #9ba3b8; font-size: 0.85rem; }
    .data-panel-body { padding: 1.1rem 1.25rem; }

    /* Analytics tables */
    .analytics-table {
        margin: 0;
        font-size: 0.855rem;
    }
    .analytics-table thead tr { background: #f4f6fb; border-bottom: 2px solid #e6e9f0; }
    .analytics-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.73rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.75rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .analytics-table tbody tr { border-bottom: 1px solid #f0f2f8; transition: background 0.1s; }
    .analytics-table tbody tr:last-child { border-bottom: none; }
    .analytics-table tbody tr:hover { background: #f8f9fc; }
    .analytics-table td { padding: 0.75rem 1rem; vertical-align: middle; border: none; color: #2d3250; }
    .analytics-table .text-end  { text-align: right; }
    .analytics-table .text-center { text-align: center; }

    .branch-pill {
        background: #eef2ff;
        color: #4361ee;
        border-radius: 6px;
        font-size: 0.75rem;
        padding: 0.22rem 0.6rem;
        font-weight: 600;
    }

    .payment-pill-cash   { background: #e6f9f0; color: #1a9e5f; border-radius: 6px; font-size: 0.72rem; padding: 0.18rem 0.5rem; font-weight: 700; }
    .payment-pill-online { background: #eef2ff; color: #4361ee; border-radius: 6px; font-size: 0.72rem; padding: 0.18rem 0.5rem; font-weight: 700; }

    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.65rem 0;
        border-bottom: 1px solid #f0f2f8;
        font-size: 0.875rem;
    }
    .summary-row:last-child { border-bottom: none; padding-bottom: 0; }
    .summary-row .s-left { display: flex; align-items: center; gap: 0.5rem; color: #4a5072; }
    .summary-row .s-right { font-weight: 700; color: #2d3250; font-size: 0.95rem; }

    .customer-name  { font-weight: 600; color: #2d3250; }
    .customer-phone { font-size: 0.78rem; color: #9ba3b8; }

    .revenue-value { font-weight: 700; color: #2d3250; }

    .empty-cell { text-align: center; color: #9ba3b8; font-style: italic; font-size: 0.82rem; padding: 2rem !important; }
</style>

<div class="container-fluid px-4 mt-4">

    <?php alertMessage(); ?>

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-chart-line me-2" style="opacity:0.75;"></i>Analytics &amp; Reports</h2>
            <p class="hero-sub">
                <?= date('M d', strtotime($startDate)) ?> &ndash; <?= date('M d, Y', strtotime($endDate)) ?>
            </p>
            <?php if (!$isSuperAdmin && !empty($_SESSION['loggedInUser']['branch_name'])): ?>
                <span class="hero-badge-branch">
                    <i class="fas fa-code-branch"></i>
                    <?= htmlspecialchars($_SESSION['loggedInUser']['branch_name']) ?>
                </span>
            <?php elseif ($isSuperAdmin): ?>
                <span class="hero-badge-admin">Super Admin View</span>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <?php
            $exportParams = '';
            if (isset($_GET['branch_id']))  $exportParams .= '&branch_id='  . htmlspecialchars($_GET['branch_id']);
            if (isset($_GET['start_date'])) $exportParams .= '&start_date=' . htmlspecialchars($_GET['start_date']);
            if (isset($_GET['end_date']))   $exportParams .= '&end_date='   . htmlspecialchars($_GET['end_date']);
            ?>
            <a href="analytics-export.php?type=excel<?= $exportParams ?>" class="btn-export-excel">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="analytics-export.php?type=pdf<?= $exportParams ?>" class="btn-export-pdf" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="filters-panel">
        <form action="" method="GET">
            <div class="row g-2 align-items-end">

                <?php if ($isSuperAdmin): ?>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        <?php
                        $branches = getAll('branches');
                        if ($branches && mysqli_num_rows($branches) > 0) {
                            foreach ($branches as $branch) {
                                $selected = ($selectedBranch == $branch['id']) ? 'selected' : '';
                                echo '<option value="' . $branch['id'] . '" ' . $selected . '>' . htmlspecialchars($branch['branch_name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md">
                    <label class="form-label text-muted small fw-semibold mb-1">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
                </div>

                <div class="col-md">
                    <label class="form-label text-muted small fw-semibold mb-1">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
                </div>

                <div class="col-md-auto">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="analytics.php" class="btn-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-card">
                <div class="s-icon green"><i class="fas fa-peso-sign"></i></div>
                <p class="s-label">Total Sales</p>
                <p class="s-value">₱<?= number_format($totalSales, 2) ?></p>
                <span class="s-note"><?= date('M d', strtotime($startDate)) ?> &ndash; <?= date('M d, Y', strtotime($endDate)) ?></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="s-icon blue"><i class="fas fa-receipt"></i></div>
                <p class="s-label">Total Orders</p>
                <p class="s-value"><?= number_format($totalOrders) ?></p>
                <span class="s-note">
                    <?= date('M d', strtotime($startDate)) ?> &ndash; <?= date('M d, Y', strtotime($endDate)) ?>
                    <?php if ($cancelledOrders > 0): ?>
                        &bull; <span class="text-danger"><?= $cancelledOrders ?> cancelled</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="s-icon purple"><i class="fas fa-chart-line"></i></div>
                <p class="s-label">Average Order Value</p>
                <p class="s-value">₱<?= number_format($averageOrderValue, 2) ?></p>
                <span class="s-note">Per transaction</span>
            </div>
        </div>

    </div>

    <!-- DAILY SALES CHART + PAYMENT METHODS -->
    <div class="row g-3 mb-4">

        <div class="col-xl-8">
            <div class="data-panel">
                <div class="data-panel-header">
                    <i class="fas fa-chart-area"></i> Daily Sales Trend
                </div>
                <div class="data-panel-body">
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="data-panel">
                <div class="data-panel-header">
                    <i class="fas fa-money-bill-wave"></i> Payment Methods
                </div>
                <div class="data-panel-body">
                    <?php if ($paymentMethodResult && mysqli_num_rows($paymentMethodResult) > 0): ?>
                        <?php foreach ($paymentMethodResult as $payment): ?>
                        <div class="summary-row">
                            <div class="s-left">
                                <?php if ($payment['payment_mode'] == 'Cash Payment'): ?>
                                    <span class="payment-pill-cash"><?= $payment['order_count'] ?></span>
                                <?php else: ?>
                                    <span class="payment-pill-online"><?= $payment['order_count'] ?></span>
                                <?php endif; ?>
                                <?= htmlspecialchars($payment['payment_mode']) ?>
                            </div>
                            <span class="s-right">₱<?= number_format($payment['total_sales'], 2) ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-cell">No payment data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- BRANCH PERFORMANCE (Super Admin Only) -->
    <?php if ($isSuperAdmin && $branchPerformance): ?>
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="data-panel">
                <div class="data-panel-header">
                    <i class="fas fa-code-branch"></i> Branch Performance
                </div>
                <div class="table-responsive">
                    <table class="table analytics-table">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th class="text-center">Orders</th>
                                <th class="text-end">Total Sales</th>
                                <th class="text-end">Avg Order Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($branchPerformance) > 0): ?>
                                <?php foreach ($branchPerformance as $branch):
                                    $avgValue = $branch['order_count'] > 0 ? $branch['total_sales'] / $branch['order_count'] : 0;
                                ?>
                                <tr>
                                    <td><span class="branch-pill"><?= htmlspecialchars($branch['branch_name']) ?></span></td>
                                    <td class="text-center"><?= number_format($branch['order_count']) ?></td>
                                    <td class="text-end revenue-value">₱<?= number_format($branch['total_sales'], 2) ?></td>
                                    <td class="text-end" style="color:#6b7189;">₱<?= number_format($avgValue, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="empty-cell">No branch data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- TOP SERVICES + TOP CUSTOMERS -->
    <div class="row g-3 mb-4">

        <div class="col-xl-6">
            <div class="data-panel">
                <div class="data-panel-header">
                    <i class="fas fa-star"></i> Top Services
                </div>
                <div class="table-responsive">
                    <table class="table analytics-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th class="text-center">Orders</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($topServicesResult && mysqli_num_rows($topServicesResult) > 0): ?>
                                <?php foreach ($topServicesResult as $service): ?>
                                <tr>
                                    <td style="font-weight:600;"><?= htmlspecialchars($service['name']) ?></td>
                                    <td class="text-center"><?= $service['times_ordered'] ?></td>
                                    <td class="text-center"><?= $service['total_quantity'] ?></td>
                                    <td class="text-end revenue-value">₱<?= number_format($service['total_revenue'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="empty-cell">No service data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="data-panel">
                <div class="data-panel-header">
                    <i class="fas fa-users"></i> Top Customers
                </div>
                <div class="table-responsive">
                    <table class="table analytics-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="text-center">Orders</th>
                                <th class="text-end">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($topCustomersResult && mysqli_num_rows($topCustomersResult) > 0): ?>
                                <?php foreach ($topCustomersResult as $customer): ?>
                                <tr>
                                    <td>
                                        <span class="customer-name"><?= htmlspecialchars($customer['name']) ?></span>
                                        <span class="customer-phone d-block"><?= htmlspecialchars($customer['phone']) ?></span>
                                    </td>
                                    <td class="text-center"><?= $customer['order_count'] ?></td>
                                    <td class="text-end revenue-value">₱<?= number_format($customer['total_spent'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="empty-cell">No customer data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
        while ($row = mysqli_fetch_assoc($dailySalesResult)) {
            echo "'" . date('M d', strtotime($row['order_date'])) . "',";
        }
        ?>
    ],
    datasets: [{
        label: 'Daily Sales (₱)',
        data: [
            <?php
            mysqli_data_seek($dailySalesResult, 0);
            while ($row = mysqli_fetch_assoc($dailySalesResult)) {
                echo $row['total_sales'] . ',';
            }
            ?>
        ],
        borderColor: '#4361ee',
        backgroundColor: 'rgba(67,97,238,0.08)',
        borderWidth: 2,
        pointBackgroundColor: '#4361ee',
        pointRadius: 4,
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
                backgroundColor: '#2d3250',
                titleColor: '#fff',
                bodyColor: 'rgba(255,255,255,0.8)',
                padding: 10,
                callbacks: {
                    label: function (context) {
                        return ' ₱' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { color: '#f0f2f8' },
                ticks: { color: '#9ba3b8', font: { size: 11 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#f0f2f8' },
                ticks: {
                    color: '#9ba3b8',
                    font: { size: 11 },
                    callback: function (value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

<?php include('../includes/footer.php'); ?>
