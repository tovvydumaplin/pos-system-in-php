<?php
/**
 * Analytics Print View
 * Print-friendly page that can be saved as PDF using browser
 */

require __DIR__ . '/../../config/function.php';
require __DIR__ . '/../authentication.php';

// Check if user is staff - redirect if true
if($_SESSION['loggedInUser']['user_type'] == 'staff') {
    redirect('../index.php', 'Access denied for staff accounts.');
}

include('analytics-backend.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Report - Print</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            @page { margin: 1cm; }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #333;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #000;
        }
        
        .header-info {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            padding: 8px 10px;
            background: #f0f0f0;
            border-left: 4px solid #333;
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table th {
            background: #555;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        
        table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #000;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print / Save as PDF
    </button>
    
    <div class="header">
        <h1>Analytics Report</h1>
        <div class="header-info">
            <strong>Generated:</strong> <?= date('F d, Y g:i A'); ?><br>
            <strong>Date Range:</strong> <?= date('M d, Y', strtotime($startDate)); ?> - <?= date('M d, Y', strtotime($endDate)); ?><br>
            <?php if($isSuperAdmin && !empty($selectedBranch)): 
                $branchQuery = mysqli_query($conn, "SELECT branch_name FROM branches WHERE id='$selectedBranch'");
                if($branchQuery && mysqli_num_rows($branchQuery) > 0):
                    $branchName = mysqli_fetch_assoc($branchQuery)['branch_name'];
            ?>
            <strong>Branch:</strong> <?= $branchName; ?>
            <?php endif; ?>
            <?php elseif($isSuperAdmin): ?>
            <strong>Branch:</strong> All Branches
            <?php elseif($isAdmin && !empty($userBranchId)): 
                $branchQuery = mysqli_query($conn, "SELECT branch_name FROM branches WHERE id='$userBranchId'");
                if($branchQuery && mysqli_num_rows($branchQuery) > 0):
                    $branchName = mysqli_fetch_assoc($branchQuery)['branch_name'];
            ?>
            <strong>Branch:</strong> <?= $branchName; ?>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- OVERVIEW STATISTICS -->
    <div class="section">
        <div class="section-title">Overview Statistics</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Sales</div>
                <div class="stat-value">₱<?= number_format($totalSales, 2); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?= number_format($totalOrders); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Average Order Value</div>
                <div class="stat-value">₱<?= number_format($averageOrderValue, 2); ?></div>
            </div>
        </div>
    </div>
    
    <!-- PAYMENT METHOD BREAKDOWN -->
    <div class="section">
        <div class="section-title">Payment Method Breakdown</div>
        <table>
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th class="text-center">Order Count</th>
                    <th class="text-right">Total Sales</th>
                </tr>
            </thead>
            <tbody>
                <?php if($paymentMethodResult && mysqli_num_rows($paymentMethodResult) > 0): ?>
                    <?php mysqli_data_seek($paymentMethodResult, 0); ?>
                    <?php while($payment = mysqli_fetch_assoc($paymentMethodResult)): ?>
                    <tr>
                        <td><?= $payment['payment_mode']; ?></td>
                        <td class="text-center"><?= $payment['order_count']; ?></td>
                        <td class="text-right">₱<?= number_format($payment['total_sales'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">No payment data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- DAILY SALES TREND -->
    <div class="section">
        <div class="section-title">Daily Sales Trend</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-center">Orders</th>
                    <th class="text-right">Total Sales</th>
                </tr>
            </thead>
            <tbody>
                <?php if($dailySalesResult && mysqli_num_rows($dailySalesResult) > 0): ?>
                    <?php mysqli_data_seek($dailySalesResult, 0); ?>
                    <?php while($day = mysqli_fetch_assoc($dailySalesResult)): ?>
                    <tr>
                        <td><?= date('M d, Y', strtotime($day['order_date'])); ?></td>
                        <td class="text-center"><?= $day['order_count']; ?></td>
                        <td class="text-right">₱<?= number_format($day['total_sales'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">No sales data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- TOP SERVICES -->
    <div class="section">
        <div class="section-title">Top Services</div>
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th class="text-center">Times Ordered</th>
                    <th class="text-center">Total Quantity</th>
                    <th class="text-right">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php if($topServicesResult && mysqli_num_rows($topServicesResult) > 0): ?>
                    <?php mysqli_data_seek($topServicesResult, 0); ?>
                    <?php while($service = mysqli_fetch_assoc($topServicesResult)): ?>
                    <tr>
                        <td><?= $service['name']; ?></td>
                        <td class="text-center"><?= $service['times_ordered']; ?></td>
                        <td class="text-center"><?= $service['total_quantity']; ?></td>
                        <td class="text-right">₱<?= number_format($service['total_revenue'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No service data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- TOP CUSTOMERS -->
    <div class="section">
        <div class="section-title">Top Customers</div>
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th class="text-center">Total Orders</th>
                    <th class="text-right">Total Spent</th>
                </tr>
            </thead>
            <tbody>
                <?php if($topCustomersResult && mysqli_num_rows($topCustomersResult) > 0): ?>
                    <?php mysqli_data_seek($topCustomersResult, 0); ?>
                    <?php while($customer = mysqli_fetch_assoc($topCustomersResult)): ?>
                    <tr>
                        <td><?= $customer['name']; ?></td>
                        <td><?= $customer['phone']; ?></td>
                        <td class="text-center"><?= $customer['order_count']; ?></td>
                        <td class="text-right">₱<?= number_format($customer['total_spent'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No customer data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- BRANCH PERFORMANCE (Super Admin Only) -->
    <?php if($isSuperAdmin && $branchPerformance && mysqli_num_rows($branchPerformance) > 0): ?>
    <div class="section">
        <div class="section-title">Branch Performance</div>
        <table>
            <thead>
                <tr>
                    <th>Branch Name</th>
                    <th class="text-center">Total Orders</th>
                    <th class="text-right">Total Sales</th>
                    <th class="text-right">Avg Order Value</th>
                </tr>
            </thead>
            <tbody>
                <?php mysqli_data_seek($branchPerformance, 0); ?>
                <?php while($branch = mysqli_fetch_assoc($branchPerformance)): 
                    $avgValue = $branch['order_count'] > 0 ? $branch['total_sales'] / $branch['order_count'] : 0;
                ?>
                <tr>
                    <td><?= $branch['branch_name']; ?></td>
                    <td class="text-center"><?= $branch['order_count']; ?></td>
                    <td class="text-right">₱<?= number_format($branch['total_sales'], 2); ?></td>
                    <td class="text-right">₱<?= number_format($avgValue, 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
</body>
</html>