<?php
/**
 * Analytics Export Handler
 * Handles Excel (CSV) and PDF exports
 */

require __DIR__ . '/../../config/function.php';
require __DIR__ . '/../authentication.php';

// Check if user is staff - redirect if true
if($_SESSION['loggedInUser']['user_type'] == 'staff') {
    redirect('../index.php', 'Access denied for staff accounts.');
}

// Get export type
$exportType = isset($_GET['type']) ? validate($_GET['type']) : '';

if(!in_array($exportType, ['excel', 'pdf'])) {
    redirect('analytics.php', 'Invalid export type.');
}

// Get the same backend logic
include('analytics-backend.php');

// ============================================
// EXCEL EXPORT (CSV FORMAT)
// ============================================
if($exportType == 'excel') {
    
    $filename = 'analytics_report_' . date('Y-m-d_His') . '.csv';
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Add BOM for Excel UTF-8 support
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Report Header
    fputcsv($output, ['Analytics Report']);
    fputcsv($output, ['Generated: ' . date('F d, Y g:i A')]);
    fputcsv($output, ['Date Range: ' . date('M d, Y', strtotime($startDate)) . ' - ' . date('M d, Y', strtotime($endDate))]);
    
    if($isSuperAdmin && !empty($selectedBranch)) {
        $branchQuery = mysqli_query($conn, "SELECT branch_name FROM branches WHERE id='$selectedBranch'");
        if($branchQuery && mysqli_num_rows($branchQuery) > 0) {
            $branchName = mysqli_fetch_assoc($branchQuery)['branch_name'];
            fputcsv($output, ['Branch: ' . $branchName]);
        }
    } elseif($isSuperAdmin) {
        fputcsv($output, ['Branch: All Branches']);
    } elseif($isAdmin && !empty($userBranchId)) {
        $branchQuery = mysqli_query($conn, "SELECT branch_name FROM branches WHERE id='$userBranchId'");
        if($branchQuery && mysqli_num_rows($branchQuery) > 0) {
            $branchName = mysqli_fetch_assoc($branchQuery)['branch_name'];
            fputcsv($output, ['Branch: ' . $branchName]);
        }
    }
    
    fputcsv($output, []); // Empty row
    
    // Overview Statistics
    fputcsv($output, ['OVERVIEW STATISTICS']);
    fputcsv($output, ['Metric', 'Value']);
    fputcsv($output, ['Total Sales', '₱' . number_format($totalSales, 2)]);
    fputcsv($output, ['Total Orders', number_format($totalOrders)]);
    fputcsv($output, ['Average Order Value', '₱' . number_format($averageOrderValue, 2)]);
    fputcsv($output, []); // Empty row
    
    // Payment Methods
    fputcsv($output, ['PAYMENT METHOD BREAKDOWN']);
    fputcsv($output, ['Payment Method', 'Order Count', 'Total Sales']);
    if($paymentMethodResult && mysqli_num_rows($paymentMethodResult) > 0) {
        mysqli_data_seek($paymentMethodResult, 0); // Reset pointer
        while($payment = mysqli_fetch_assoc($paymentMethodResult)) {
            fputcsv($output, [
                $payment['payment_mode'],
                $payment['order_count'],
                '₱' . number_format($payment['total_sales'], 2)
            ]);
        }
    }
    fputcsv($output, []); // Empty row
    
    // Daily Sales
    fputcsv($output, ['DAILY SALES TREND']);
    fputcsv($output, ['Date', 'Orders', 'Total Sales']);
    if($dailySalesResult && mysqli_num_rows($dailySalesResult) > 0) {
        mysqli_data_seek($dailySalesResult, 0); // Reset pointer
        while($day = mysqli_fetch_assoc($dailySalesResult)) {
            fputcsv($output, [
                date('M d, Y', strtotime($day['order_date'])),
                $day['order_count'],
                '₱' . number_format($day['total_sales'], 2)
            ]);
        }
    }
    fputcsv($output, []); // Empty row
    
    // Top Services
    fputcsv($output, ['TOP SERVICES']);
    fputcsv($output, ['Service Name', 'Times Ordered', 'Total Quantity', 'Total Revenue']);
    if($topServicesResult && mysqli_num_rows($topServicesResult) > 0) {
        mysqli_data_seek($topServicesResult, 0); // Reset pointer
        while($service = mysqli_fetch_assoc($topServicesResult)) {
            fputcsv($output, [
                $service['name'],
                $service['times_ordered'],
                $service['total_quantity'],
                '₱' . number_format($service['total_revenue'], 2)
            ]);
        }
    }
    fputcsv($output, []); // Empty row
    
    // Top Customers
    fputcsv($output, ['TOP CUSTOMERS']);
    fputcsv($output, ['Customer Name', 'Phone', 'Orders', 'Total Spent']);
    if($topCustomersResult && mysqli_num_rows($topCustomersResult) > 0) {
        mysqli_data_seek($topCustomersResult, 0); // Reset pointer
        while($customer = mysqli_fetch_assoc($topCustomersResult)) {
            fputcsv($output, [
                $customer['name'],
                $customer['phone'],
                $customer['order_count'],
                '₱' . number_format($customer['total_spent'], 2)
            ]);
        }
    }
    fputcsv($output, []); // Empty row
    
    // Branch Performance (Super Admin Only)
    if($isSuperAdmin && $branchPerformance && mysqli_num_rows($branchPerformance) > 0) {
        fputcsv($output, ['BRANCH PERFORMANCE']);
        fputcsv($output, ['Branch Name', 'Total Orders', 'Total Sales', 'Avg Order Value']);
        mysqli_data_seek($branchPerformance, 0); // Reset pointer
        while($branch = mysqli_fetch_assoc($branchPerformance)) {
            $avgValue = $branch['order_count'] > 0 ? $branch['total_sales'] / $branch['order_count'] : 0;
            fputcsv($output, [
                $branch['branch_name'],
                $branch['order_count'],
                '₱' . number_format($branch['total_sales'], 2),
                '₱' . number_format($avgValue, 2)
            ]);
        }
    }
    
    fclose($output);
    exit;
}

// ============================================
// PDF EXPORT (Print-friendly HTML)
// ============================================
if($exportType == 'pdf') {
    // Redirect to print view
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('type=pdf', 'print=1', $queryString);
    header('Location: analytics-print.php?' . $queryString);
    exit;
}

redirect('analytics.php', 'Export failed.');
?>