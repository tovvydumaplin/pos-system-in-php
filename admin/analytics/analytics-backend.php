<?php
/**
 * Analytics Backend Logic
 * Handles data fetching based on user role and filters
 */

// Get user info
$userType = $_SESSION['loggedInUser']['user_type'];
$userBranchId = $_SESSION['loggedInUser']['branch_id'];
$isSuperAdmin = ($userType == 'super_admin');
$isAdmin = ($userType == 'admin');

// Date filters
$startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? validate($_GET['start_date']) : date('Y-m-01'); // First day of current month
$endDate = isset($_GET['end_date']) && !empty($_GET['end_date']) ? validate($_GET['end_date']) : date('Y-m-d'); // Today

// Branch filter (super admin only)
$selectedBranch = '';
if($isSuperAdmin && isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
    $selectedBranch = validate($_GET['branch_id']);
}

// Build WHERE clause based on user role
function buildWhereClause($includeDate = true) {
    global $isSuperAdmin, $isAdmin, $userBranchId, $selectedBranch, $startDate, $endDate;
    
    $where = "1=1";
    
    // Branch filtering based on role
    if($isSuperAdmin) {
        // Super admin can filter by branch or see all
        if(!empty($selectedBranch)) {
            $where .= " AND o.branch_id = '$selectedBranch'";
        }
    } elseif($isAdmin) {
        // Regular admin only sees their branch
        if(!empty($userBranchId)) {
            $where .= " AND o.branch_id = '$userBranchId'";
        }
    }
    
    // Date filtering
    if($includeDate) {
        $where .= " AND o.order_date BETWEEN '$startDate' AND '$endDate'";
    }
    
    return $where;
}

// ============================================
// ANALYTICS QUERIES
// ============================================

// Total Sales
$totalSalesQuery = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders o WHERE " . buildWhereClause();
$totalSalesResult = mysqli_query($conn, $totalSalesQuery);
$totalSales = mysqli_fetch_assoc($totalSalesResult)['total'];

// Total Orders
$totalOrdersQuery = "SELECT COUNT(*) as total FROM orders o WHERE " . buildWhereClause();
$totalOrdersResult = mysqli_query($conn, $totalOrdersQuery);
$totalOrders = mysqli_fetch_assoc($totalOrdersResult)['total'];

// Average Order Value
$averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

// Sales by Payment Method
$paymentMethodQuery = "
    SELECT 
        payment_mode,
        COUNT(*) as order_count,
        COALESCE(SUM(total_amount), 0) as total_sales
    FROM orders o
    WHERE " . buildWhereClause() . "
    GROUP BY payment_mode
";
$paymentMethodResult = mysqli_query($conn, $paymentMethodQuery);

// Daily Sales (for chart)
$dailySalesQuery = "
    SELECT 
        order_date,
        COUNT(*) as order_count,
        COALESCE(SUM(total_amount), 0) as total_sales
    FROM orders o
    WHERE " . buildWhereClause() . "
    GROUP BY order_date
    ORDER BY order_date ASC
";
$dailySalesResult = mysqli_query($conn, $dailySalesQuery);

// Top Services
$topServicesQuery = "
    SELECT 
        s.name,
        COUNT(oi.id) as times_ordered,
        COALESCE(SUM(oi.quantity), 0) as total_quantity,
        COALESCE(SUM(oi.price * oi.quantity), 0) as total_revenue
    FROM order_items oi
    JOIN services s ON s.id = oi.service_id
    JOIN orders o ON o.id = oi.order_id
    WHERE " . buildWhereClause() . "
    GROUP BY oi.service_id
    ORDER BY total_revenue DESC
    LIMIT 10
";
$topServicesResult = mysqli_query($conn, $topServicesQuery);

// Top Customers
$topCustomersQuery = "
    SELECT 
        c.name,
        c.phone,
        COUNT(o.id) as order_count,
        COALESCE(SUM(o.total_amount), 0) as total_spent
    FROM orders o
    JOIN customers c ON c.id = o.customer_id
    WHERE " . buildWhereClause() . "
    GROUP BY o.customer_id
    ORDER BY total_spent DESC
    LIMIT 10
";
$topCustomersResult = mysqli_query($conn, $topCustomersQuery);

// Branch Performance (Super Admin Only)
$branchPerformance = null;
if($isSuperAdmin) {
    $branchPerformanceQuery = "
        SELECT 
            b.branch_name,
            b.id as branch_id,
            COUNT(o.id) as order_count,
            COALESCE(SUM(o.total_amount), 0) as total_sales
        FROM branches b
        LEFT JOIN orders o ON o.branch_id = b.id AND o.order_date BETWEEN '$startDate' AND '$endDate'
        GROUP BY b.id
        ORDER BY total_sales DESC
    ";
    $branchPerformance = mysqli_query($conn, $branchPerformanceQuery);
}

// Order Status Distribution
$orderStatusQuery = "
    SELECT 
        order_status,
        COUNT(*) as count
    FROM orders o
    WHERE " . buildWhereClause() . "
    GROUP BY order_status
";
$orderStatusResult = mysqli_query($conn, $orderStatusQuery);

?>