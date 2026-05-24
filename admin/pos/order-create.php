<?php include('../includes/header.php'); ?>

<?php
$branchId = $_SESSION['loggedInUser']['branch_id'];
$isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';

$serviceWhereClause = $isSuperAdmin ? "s.status = 0" : "s.status = 0 AND s.branch_id = '$branchId'";

$servicesQuery = mysqli_query($conn, "
    SELECT s.id, s.name, s.price,
        GROUP_CONCAT(
            CONCAT(si.quantity_required, '||', lc.item_name, '||', lc.quantity)
            ORDER BY lc.item_name ASC SEPARATOR '@@'
        ) AS items_raw
    FROM services s
    LEFT JOIN service_items si ON si.service_id = s.id
    LEFT JOIN laundry_consumables lc ON lc.id = si.consumable_id
    WHERE $serviceWhereClause
    GROUP BY s.id
    ORDER BY s.name ASC
");

$servicesData = [];
if ($servicesQuery && mysqli_num_rows($servicesQuery) > 0) {
    foreach ($servicesQuery as $svc) {
        $items = [];
        if (!empty($svc['items_raw'])) {
            foreach (explode('@@', $svc['items_raw']) as $part) {
                [$qty, $name, $stock] = explode('||', $part);
                $items[] = ['qty' => (int)$qty, 'name' => $name, 'stock' => (int)$stock];
            }
        }
        $servicesData[$svc['id']] = [
            'name'  => $svc['name'],
            'price' => $svc['price'],
            'items' => $items,
        ];
    }
}

$consumables = mysqli_query($conn, "
    SELECT * FROM laundry_consumables
    WHERE branch_id = '$branchId'
    ORDER BY item_name ASC
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
    .page-hero h2 {
        color: #2d3250;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
    }
    .page-hero p {
        color: #9ba3b8;
        font-size: 0.85rem;
        margin: 0.2rem 0 0;
    }
    .btn-back {
        background: #fff;
        color: #6b7189;
        border: 1px solid #dde1ec;
        border-radius: 9px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-back:hover { background: #f0f2f8; color: #2d3250; }

    .form-panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .form-panel-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f0f2f8;
        background: #fafbfc;
    }
    .panel-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.8rem;
    }
    .panel-icon.blue  { background: #eef2ff; color: #4361ee; }
    .panel-icon.green { background: #e6f9f0; color: #1a9e5f; }
    .panel-icon.amber { background: #fff8ec; color: #e67e22; }
    .form-panel-header span.title {
        font-weight: 700;
        font-size: 0.9rem;
        color: #2d3250;
    }
    .form-panel-body { padding: 1.25rem; }

    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5072;
        margin-bottom: 0.35rem;
    }
    .form-control, .form-select {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #2d3250;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }

    .service-info-box {
        background: #f4f6ff;
        border: 1px solid #d6dcff;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-bottom: 1rem;
    }
    .service-info-box .info-name {
        font-weight: 700;
        color: #2d3250;
        font-size: 0.9rem;
    }
    .service-info-box .info-price {
        font-weight: 700;
        color: #4361ee;
        font-size: 0.9rem;
    }

    .btn-add-primary {
        background: #2d3250;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: background 0.2s;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-add-primary:hover { background: #424870; color: #fff; }

    /* Order Summary Table */
    .summary-table {
        margin: 0;
        font-size: 0.855rem;
    }
    .summary-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .summary-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.73rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.75rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .summary-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.1s;
    }
    .summary-table tbody tr:last-child { border-bottom: none; }
    .summary-table tbody tr:hover { background: #f8f9fc; }
    .summary-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border: none;
        color: #2d3250;
    }

    .type-badge-service { background: #eef2ff; color: #4361ee; border-radius: 6px; font-size: 0.72rem; padding: 0.2rem 0.55rem; font-weight: 600; }
    .type-badge-item    { background: #e6f9f0; color: #1a9e5f; border-radius: 6px; font-size: 0.72rem; padding: 0.2rem 0.55rem; font-weight: 600; }

    .consumable-line {
        font-size: 0.75rem;
        color: #9ba3b8;
        display: block;
        margin-top: 0.2rem;
    }
    .consumable-line i { font-size: 0.6rem; opacity: 0.5; }

    .btn-remove-item {
        background: #fdecea;
        color: #d63031;
        border: none;
        border-radius: 7px;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-remove-item:hover { background: #fbc8c8; color: #d63031; }

    .totals-box {
        background: #f8f9fc;
        border-top: 2px solid #e6e9f0;
        padding: 1rem 1.25rem;
    }
    .totals-box .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.84rem;
        color: #6b7189;
    }
    .totals-box .total-row span:last-child { font-weight: 600; color: #2d3250; }
    .totals-box .grand-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.65rem;
        border-top: 1px solid #e6e9f0;
        margin-top: 0.25rem;
    }
    .totals-box .grand-row .grand-label { font-weight: 700; color: #2d3250; font-size: 0.9rem; }
    .totals-box .grand-row .grand-amount { font-weight: 800; font-size: 1.25rem; color: #4361ee; }

    .checkout-footer {
        padding: 1.1rem 1.25rem;
        border-top: 1px solid #e6e9f0;
        background: #fff;
    }

    .btn-place {
        background: #2d3250;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 0.6rem 1.4rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: background 0.2s;
        cursor: pointer;
    }
    .btn-place:hover { background: #424870; }

    .empty-cart {
        text-align: center;
        padding: 3.5rem 2rem;
        color: #9ba3b8;
    }
    .empty-cart .cart-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f4f6fb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.3rem;
        color: #c5cade;
    }
    .empty-cart h6 { font-weight: 700; color: #4a5072; margin-bottom: 0.3rem; }
    .empty-cart p  { font-size: 0.82rem; margin: 0; }

    .items-count-badge {
        background: #eef2ff;
        color: #4361ee;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
    }

    .btn-add-customer {
        background: #fff;
        color: #6b7189;
        border: 1px solid #dde1ec;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        transition: background 0.15s;
        flex-shrink: 0;
    }
    .btn-add-customer:hover { background: #f0f2f8; color: #2d3250; }
</style>

<div class="container-fluid px-4 mt-4">

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-cash-register me-2" style="opacity:0.75;"></i>New Order</h2>
            <p>Point of Sale — add services and items to build an order</p>
        </div>
        <a href="orders.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <?php alertMessage(); ?>

    <div class="row g-4 pb-5">

        <!-- ===================== LEFT ===================== -->
        <div class="col-xl-5 col-lg-5">

            <!-- Add Service -->
            <div class="form-panel">
                <div class="form-panel-header">
                    <span class="panel-icon blue"><i class="fas fa-concierge-bell"></i></span>
                    <span class="title">Add Service</span>
                </div>
                <div class="form-panel-body">
                    <form action="orders-code.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Service</label>
                            <select name="service_id" id="serviceSelect" class="form-select">
                                <option value="">— Select a service —</option>
                                <?php foreach ($servicesData as $id => $svc): ?>
                                    <option value="<?= $id ?>">
                                        <?= htmlspecialchars($svc['name']) ?> &mdash; &#8369;<?= number_format($svc['price'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="serviceInfo" class="service-info-box d-none">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="info-name" id="infoName"></span>
                                <span class="info-price" id="infoPrice"></span>
                            </div>
                            <div id="infoItems" class="small"></div>
                        </div>

                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="service_qty" id="serviceQty"
                                       value="1" min="1" class="form-control">
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="addService" class="btn-add-primary">
                                    <i class="fas fa-plus"></i> Add Service
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Item Manually -->
            <div class="form-panel">
                <div class="form-panel-header">
                    <span class="panel-icon green"><i class="fas fa-box"></i></span>
                    <span class="title">Add Item Manually</span>
                </div>
                <div class="form-panel-body">
                    <form action="orders-code.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Item</label>
                            <select name="consumable_id" class="form-select mySelect2">
                                <option value="">— Select an item —</option>
                                <?php if ($consumables && mysqli_num_rows($consumables) > 0):
                                    foreach ($consumables as $item):
                                        $disabled = $item['quantity'] <= 0 ? 'disabled' : '';
                                ?>
                                    <option value="<?= $item['id'] ?>" <?= $disabled ?>>
                                        <?= htmlspecialchars($item['item_name']) ?>
                                        (<?= $item['quantity'] ?> left &middot; &#8369;<?= number_format($item['price'], 2) ?>)
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="item_qty" value="1" min="1" class="form-control">
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="addConsumable" class="btn-add-primary">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- ===================== RIGHT ===================== -->
        <div class="col-xl-7 col-lg-7">
            <div class="form-panel" style="min-height:420px;">

                <div class="form-panel-header">
                    <span class="panel-icon amber"><i class="fas fa-receipt"></i></span>
                    <span class="title">Order Summary</span>
                    <?php if (!empty($_SESSION['orderItems'])): ?>
                        <span class="items-count-badge ms-auto"><?= count($_SESSION['orderItems']) ?> item(s)</span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($_SESSION['orderItems'])): ?>

                    <div class="table-responsive">
                        <table class="table summary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $servicesTotal = 0;
                                $itemsTotal = 0;
                                $consumablesFromServicesTotal = 0;
                                $grandTotal = 0;

                                foreach ($_SESSION['orderItems'] as $key => $item):
                                    $lineTotal = $item['price'] * $item['quantity'];
                                    $consumablesCost = 0;

                                    if ($item['type'] == 'service') {
                                        $serviceConsumables = mysqli_query($conn, "
                                            SELECT si.quantity_required, lc.price, lc.item_name
                                            FROM service_items si
                                            JOIN laundry_consumables lc ON lc.id = si.consumable_id
                                            WHERE si.service_id = '{$item['id']}'
                                        ");
                                        if ($serviceConsumables && mysqli_num_rows($serviceConsumables) > 0) {
                                            while ($sc = mysqli_fetch_assoc($serviceConsumables)) {
                                                $consumablesCost += ($sc['price'] * $sc['quantity_required'] * $item['quantity']);
                                            }
                                        }
                                        $consumablesFromServicesTotal += $consumablesCost;
                                        $servicesTotal += $lineTotal;
                                    } else {
                                        $itemsTotal += $lineTotal;
                                    }

                                    $grandTotal += $lineTotal + $consumablesCost;
                                ?>
                                <tr>
                                    <td style="color:#9ba3b8;"><?= $i++ ?></td>
                                    <td>
                                        <?php if ($item['type'] == 'service'): ?>
                                            <span class="type-badge-service">Service</span>
                                        <?php else: ?>
                                            <span class="type-badge-item">Item</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span style="font-weight:600;"><?= htmlspecialchars($item['name']) ?></span>
                                        <?php if ($item['type'] == 'service'): ?>
                                            <?php
                                            $scq = mysqli_query($conn, "
                                                SELECT si.quantity_required, lc.price, lc.item_name
                                                FROM service_items si
                                                JOIN laundry_consumables lc ON lc.id = si.consumable_id
                                                WHERE si.service_id = '{$item['id']}'
                                            ");
                                            if ($scq && mysqli_num_rows($scq) > 0):
                                                while ($sc = mysqli_fetch_assoc($scq)):
                                                    $totalQtyNeeded = $sc['quantity_required'] * $item['quantity'];
                                                    $itemCost = $sc['price'] * $totalQtyNeeded;
                                            ?>
                                                <span class="consumable-line">
                                                    <i class="fas fa-arrow-right"></i>
                                                    <?= htmlspecialchars($sc['item_name']) ?>
                                                    &times;<?= $totalQtyNeeded ?>
                                                    @ &#8369;<?= number_format($sc['price'], 2) ?>
                                                    = &#8369;<?= number_format($itemCost, 2) ?>
                                                </span>
                                            <?php endwhile; endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-end" style="color:#6b7189;">&#8369;<?= number_format($item['price'], 2) ?></td>
                                    <td class="text-end" style="font-weight:700;">&#8369;<?= number_format($lineTotal + $consumablesCost, 2) ?></td>
                                    <td class="text-center">
                                        <a href="order-item-delete.php?index=<?= $key ?>" class="btn-remove-item" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="totals-box">
                        <div class="total-row">
                            <span>Services Total</span>
                            <span>&#8369;<?= number_format($servicesTotal, 2) ?></span>
                        </div>
                        <?php if ($consumablesFromServicesTotal > 0): ?>
                        <div class="total-row">
                            <span>Service Consumables</span>
                            <span>&#8369;<?= number_format($consumablesFromServicesTotal, 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="total-row">
                            <span>Manual Items Total</span>
                            <span>&#8369;<?= number_format($itemsTotal, 2) ?></span>
                        </div>
                        <div class="grand-row">
                            <span class="grand-label">Grand Total</span>
                            <span class="grand-amount">&#8369;<?= number_format($grandTotal, 2) ?></span>
                        </div>
                    </div>

                    <!-- Checkout -->
                    <div class="checkout-footer">
                        <form id="proceedForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Payment Mode</label>
                                    <select id="payment_mode" class="form-select">
                                        <option value="">— Select payment —</option>
                                        <option value="Cash Payment">Cash Payment</option>
                                        <option value="Online Payment">Online Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Customer</label>
                                    <div class="d-flex gap-2">
                                        <div class="flex-fill">
                                            <select id="cphone" class="form-select mySelect2">
                                                <option value="">— Select customer —</option>
                                                <?php
                                                if ($isSuperAdmin) {
                                                    $customers = getAll('customers');
                                                } else {
                                                    $customers = mysqli_query($conn, "
                                                        SELECT * FROM customers
                                                        WHERE branch_id = '$branchId'
                                                        ORDER BY name ASC
                                                    ");
                                                }
                                                if ($customers && mysqli_num_rows($customers) > 0) {
                                                    foreach ($customers as $cust) {
                                                        echo '<option value="' . $cust['phone'] . '">'
                                                            . $cust['phone'] . ' &mdash; ' . htmlspecialchars($cust['name'])
                                                            . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="button"
                                                class="btn-add-customer"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addCustomerModal"
                                                title="Add new customer">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn-place proceedToPlace">
                                        <i class="fas fa-check"></i> Place
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                <?php else: ?>

                    <div class="empty-cart">
                        <div class="cart-icon"><i class="fas fa-shopping-cart"></i></div>
                        <h6>No items added yet</h6>
                        <p>Pick a service or item from the left to get started.</p>
                    </div>

                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- ADD CUSTOMER MODAL -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:14px;border:1px solid #e6e9f0;">
            <form id="addCustomerForm">
                <div class="modal-header" style="border-bottom:1px solid #f0f2f8;padding:1.1rem 1.5rem;">
                    <h5 class="modal-title fw-bold" style="color:#2d3250;font-size:1rem;">
                        <i class="fas fa-user-plus me-2" style="color:#4361ee;opacity:0.85;"></i>Add New Customer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.25rem 1.5rem;">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="c_name" name="name" class="form-control" placeholder="e.g. Juan dela Cruz" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="number" id="c_phone" name="phone" class="form-control" placeholder="e.g. 09123456789" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="email" id="c_email" name="email" class="form-control" placeholder="e.g. juan@email.com">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f2f8;padding:0.9rem 1.5rem;">
                    <button type="button" class="btn-back" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-add-primary saveCustomer">
                        <i class="fas fa-check"></i> Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const servicesData = <?= json_encode($servicesData) ?>;

document.getElementById('serviceSelect').addEventListener('change', function () {
    const id = this.value;
    const panel = document.getElementById('serviceInfo');

    if (!id || !servicesData[id]) {
        panel.classList.add('d-none');
        return;
    }

    const svc = servicesData[id];
    document.getElementById('infoName').textContent = svc.name;
    document.getElementById('infoPrice').textContent = '₱' + parseFloat(svc.price).toLocaleString('en-PH', { minimumFractionDigits: 2 });

    const itemsDiv = document.getElementById('infoItems');
    if (svc.items.length === 0) {
        itemsDiv.innerHTML = '<span style="color:#9ba3b8;">No consumables required</span>';
    } else {
        const qty = parseInt(document.getElementById('serviceQty').value) || 1;
        itemsDiv.innerHTML = '<div style="color:#9ba3b8;margin-bottom:6px;font-size:0.78rem;">Requires:</div>' +
            svc.items.map(function (itm) {
                const needed = itm.qty * qty;
                const ok = itm.stock >= needed;
                return '<span class="badge me-1 mb-1" style="background:' + (ok ? '#e6f9f0' : '#fdecea') + ';color:' + (ok ? '#1a9e5f' : '#d63031') + ';font-weight:600;font-size:0.72rem;">' +
                    itm.name + ' &times;' + needed + ' <span style="opacity:.65;">(stock: ' + itm.stock + ')</span></span>';
            }).join('');
    }

    panel.classList.remove('d-none');
});

document.getElementById('serviceQty').addEventListener('input', function () {
    const sel = document.getElementById('serviceSelect');
    if (sel.value) sel.dispatchEvent(new Event('change'));
});
</script>

<?php include('../includes/footer.php'); ?>
