<?php include('../includes/header.php'); ?>

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
    .page-hero p  { color: #9ba3b8; font-size: 0.85rem; margin: 0.2rem 0 0; }
    .btn-back {
        background: #fff; color: #6b7189; border: 1px solid #dde1ec;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.5rem 1.1rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-back:hover { background: #f0f2f8; color: #2d3250; }
    .btn-print {
        background: #eef2ff; color: #4361ee; border: none;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.5rem 1.1rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-print:hover { background: #dce4ff; color: #4361ee; }

    /* Info panels */
    .info-panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .info-panel-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f0f2f8;
        background: #fafbfc;
        font-weight: 700;
        font-size: 0.82rem;
        color: #2d3250;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .info-panel-header i { color: #9ba3b8; }
    .info-panel-body { padding: 1.1rem 1.25rem; }

    .info-row {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 0.65rem;
        font-size: 0.875rem;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-label { color: #9ba3b8; font-size: 0.8rem; min-width: 110px; flex-shrink: 0; }
    .info-value { font-weight: 600; color: #2d3250; }

    .status-badge {
        display: inline-flex; align-items: center;
        border-radius: 20px; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.75rem;
    }
    .status-booked   { background: #e8f4fd; color: #2980b9; }
    .status-released { background: #e6f9f0; color: #1a9e5f; }
    .status-cancelled{ background: #fdecea; color: #d63031; }
    .status-default  { background: #f0f2f8; color: #6b7189; }

    .payment-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.82rem; color: #6b7189;
    }

    /* Action buttons */
    .btn-release {
        background: #e6f9f0; color: #1a9e5f; border: none;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.55rem 1.25rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-release:hover { background: #c8f5e0; color: #1a9e5f; }
    .btn-cancel-order {
        background: #fdecea; color: #d63031; border: none;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.55rem 1.25rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-cancel-order:hover { background: #fbc8c8; color: #d63031; }

    /* Order items table */
    .items-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .items-table-wrap .section-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f0f2f8;
        background: #fafbfc;
        font-weight: 700;
        font-size: 0.82rem;
        color: #2d3250;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .items-table-wrap .section-header i { color: #9ba3b8; }

    .order-items-table { margin: 0; font-size: 0.875rem; }
    .order-items-table thead tr { background: #f4f6fb; border-bottom: 2px solid #e6e9f0; }
    .order-items-table thead th {
        color: #6b7189; font-weight: 600; font-size: 0.75rem;
        text-transform: uppercase; letter-spacing: 0.04em;
        padding: 0.75rem 1rem; border: none; white-space: nowrap;
    }
    .order-items-table tbody tr { border-bottom: 1px solid #f0f2f8; }
    .order-items-table tbody tr:last-child { border-bottom: none; }
    .order-items-table td { padding: 0.75rem 1rem; vertical-align: middle; border: none; }

    .service-row td { background: #fff; }
    .consumable-row td { background: #fafbfc; }

    .service-img {
        width: 40px; height: 40px; object-fit: cover;
        border-radius: 8px; border: 1px solid #e6e9f0;
        flex-shrink: 0;
    }
    .service-name { font-weight: 700; color: #2d3250; font-size: 0.875rem; }
    .consumable-line {
        display: flex; align-items: center; gap: 0.4rem;
        color: #9ba3b8; font-size: 0.8rem; padding-left: 1rem;
    }
    .consumable-line i { font-size: 0.6rem; opacity: 0.6; }

    .type-service { background: #eef2ff; color: #4361ee; border-radius: 6px; font-size: 0.7rem; padding: 0.18rem 0.5rem; font-weight: 600; }
    .type-item    { background: #e6f9f0; color: #1a9e5f; border-radius: 6px; font-size: 0.7rem; padding: 0.18rem 0.5rem; font-weight: 600; }

    .price-main { font-weight: 700; color: #2d3250; }
    .price-sub  { color: #b0b5c8; font-size: 0.8rem; }

    .total-row td { background: #f8f9fc !important; border-top: 2px solid #e6e9f0 !important; }
    .total-label  { font-weight: 700; color: #2d3250; font-size: 0.9rem; text-align: right; }
    .total-amount { font-weight: 800; color: #4361ee; font-size: 1.15rem; text-align: right; }

    .empty-state {
        text-align: center; padding: 4rem 2rem; color: #9ba3b8;
    }
    .empty-state i  { font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }
    .empty-state h6 { font-weight: 700; color: #6b7189; margin-bottom: 0.5rem; }
    .empty-state .btn-go-back {
        background: #2d3250; color: #fff; border: none;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.55rem 1.5rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.2s; margin-top: 0.75rem;
    }
    .empty-state .btn-go-back:hover { background: #424870; color: #fff; }
</style>

<div class="container-fluid px-4 mt-4">

    <?php alertMessage(); ?>

    <?php if (!isset($_GET['track']) || $_GET['track'] == ''): ?>

        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h6>No Tracking Number Provided</h6>
            <a href="orders.php" class="btn-go-back"><i class="fas fa-arrow-left"></i> Back to Orders</a>
        </div>

    <?php else:

        $trackingNo       = validate($_GET['track']);
        $viewUserType     = $_SESSION['loggedInUser']['user_type'];
        $viewIsSuperAdmin = ($viewUserType === 'super_admin');
        $branchId         = $_SESSION['loggedInUser']['branch_id'];
        $branchClause     = $viewIsSuperAdmin ? '' : "AND o.branch_id='" . (int)$branchId . "'";

        $orders = mysqli_query($conn, "
            SELECT o.*, c.*
            FROM orders o
            JOIN customers c ON c.id = o.customer_id
            WHERE o.tracking_no='$trackingNo' $branchClause
            ORDER BY o.id DESC
        ");

        if (!$orders || mysqli_num_rows($orders) == 0):
    ?>

        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <h6>Order Not Found</h6>
            <a href="orders.php" class="btn-go-back"><i class="fas fa-arrow-left"></i> Back to Orders</a>
        </div>

    <?php else:
        $orderData = mysqli_fetch_assoc($orders);
        $orderId   = $orderData['id'];
        $status    = strtolower($orderData['order_status']);

        if ($status == 'booked')        $sBadge = 'status-booked';
        elseif ($status == 'released')  $sBadge = 'status-released';
        elseif ($status == 'cancelled') $sBadge = 'status-cancelled';
        else                            $sBadge = 'status-default';

        $paymentIcon = ($orderData['payment_mode'] == 'Online Payment') ? 'fa-wifi' : 'fa-money-bill-wave';
    ?>

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-receipt me-2" style="opacity:0.75;"></i>Order Details</h2>
            <p>Tracking No: <strong style="color:#2d3250;font-family:monospace;"><?= htmlspecialchars($trackingNo) ?></strong></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="orders-view-print.php?track=<?= htmlspecialchars($trackingNo) ?>" class="btn-print" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
            <a href="orders.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- ACTION BUTTONS (booked only) -->
    <?php if ($status == 'booked'): ?>
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <a href="orders-code.php?release=<?= htmlspecialchars($orderData['tracking_no']) ?>"
           class="btn-release"
           onclick="return confirm('Mark this order as released?')">
            <i class="fas fa-check-circle"></i> Mark as Released
        </a>
        <a href="orders-code.php?cancel=<?= htmlspecialchars($orderData['tracking_no']) ?>"
           class="btn-cancel-order"
           onclick="return confirm('Are you sure you want to cancel this order?')">
            <i class="fas fa-times-circle"></i> Cancel Order
        </a>
    </div>
    <?php endif; ?>

    <!-- ORDER + CUSTOMER INFO -->
    <div class="row g-3 mb-3">

        <div class="col-md-6">
            <div class="info-panel">
                <div class="info-panel-header">
                    <i class="fas fa-receipt"></i> Order Info
                </div>
                <div class="info-panel-body">
                    <div class="info-row">
                        <span class="info-label">Tracking No.</span>
                        <span class="info-value" style="font-family:monospace;"><?= htmlspecialchars($orderData['tracking_no']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Order Date</span>
                        <span class="info-value"><?= date('F d, Y', strtotime($orderData['order_date'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="status-badge <?= $sBadge ?>"><?= ucfirst($orderData['order_status']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment</span>
                        <span class="payment-badge">
                            <i class="fas <?= $paymentIcon ?>"></i>
                            <?= htmlspecialchars($orderData['payment_mode']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-panel">
                <div class="info-panel-header">
                    <i class="fas fa-user"></i> Customer Info
                </div>
                <div class="info-panel-body">
                    <div class="info-row">
                        <span class="info-label">Full Name</span>
                        <span class="info-value"><?= htmlspecialchars($orderData['name']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= htmlspecialchars($orderData['email']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?= htmlspecialchars($orderData['phone']) ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ORDER ITEMS -->
    <?php
    $orderItemsRes = mysqli_query($conn, "
        SELECT
            oi.quantity AS orderItemQuantity,
            oi.price    AS orderItemPrice,
            o.total_amount,
            s.name      AS service_name,
            s.image     AS service_image,
            lc.item_name AS consumable_name,
            oi.service_id,
            oi.consumable_id
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        LEFT JOIN services s ON s.id = oi.service_id
        LEFT JOIN laundry_consumables lc ON lc.id = oi.consumable_id
        WHERE o.tracking_no='$trackingNo'
        ORDER BY oi.service_id, oi.id
    ");
    ?>

    <div class="items-table-wrap">
        <div class="section-header">
            <i class="fas fa-list"></i> Order Items
        </div>
        <?php if ($orderItemsRes && mysqli_num_rows($orderItemsRes) > 0): ?>
        <div class="table-responsive">
            <table class="table order-items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="width:80px;">Type</th>
                        <th style="width:120px;" class="text-center">Price</th>
                        <th style="width:90px;" class="text-center">Qty</th>
                        <th style="width:130px;" class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItemsRes as $row):
                        $isService            = !empty($row['service_id'])    && empty($row['consumable_id']);
                        $isServiceConsumable  = !empty($row['service_id'])    && !empty($row['consumable_id']);
                        $isStandalone         = empty($row['service_id'])     && !empty($row['consumable_id']);
                        $lineTotal            = $row['orderItemPrice'] * $row['orderItemQuantity'];
                    ?>
                    <tr class="<?= $isServiceConsumable ? 'consumable-row' : 'service-row' ?>">
                        <td>
                            <?php if ($isService || $isStandalone): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <?php
                                    $img = !empty($row['service_image'])
                                        ? '../../' . $row['service_image']
                                        : '../../assets/images/no-img.jpg';
                                    ?>
                                    <img src="<?= $img ?>" class="service-img" alt="">
                                    <span class="service-name"><?= htmlspecialchars($row['service_name'] ?? $row['consumable_name']) ?></span>
                                </div>
                            <?php else: ?>
                                <div class="consumable-line">
                                    <i class="fas fa-arrow-right"></i>
                                    <?= htmlspecialchars($row['consumable_name']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isService): ?>
                                <span class="type-service">Service</span>
                            <?php elseif ($isStandalone): ?>
                                <span class="type-item">Item</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (!$isServiceConsumable): ?>
                                <span class="price-main">₱<?= number_format($row['orderItemPrice'], 2) ?></span>
                            <?php else: ?>
                                <span class="price-sub">₱<?= number_format($row['orderItemPrice'], 2) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (!$isServiceConsumable): ?>
                                <span style="font-weight:600;"><?= $row['orderItemQuantity'] ?></span>
                            <?php else: ?>
                                <span class="price-sub"><?= $row['orderItemQuantity'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if (!$isServiceConsumable): ?>
                                <span class="price-main">₱<?= number_format($lineTotal, 2) ?></span>
                            <?php else: ?>
                                <span class="price-sub">₱<?= number_format($lineTotal, 2) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <tr class="total-row">
                        <td colspan="4" class="total-label">Grand Total</td>
                        <td class="total-amount">₱<?= number_format($row['total_amount'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="empty-state" style="padding:2.5rem;">
                <p style="color:#9ba3b8;font-size:0.85rem;margin:0;">No items found for this order.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php endif; endif; ?>

</div>

<?php include('../includes/footer.php'); ?>
