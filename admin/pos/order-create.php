<?php include('../includes/header.php'); ?>

<?php
$branchId = $_SESSION['loggedInUser']['branch_id'];
$isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';

// Build services query with branch filtering (unless super_admin)
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

<div class="container-fluid px-4 pt-4">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="dashboard-kicker mb-0">Point of Sale</p>
            <h4 class="mb-0 fw-bold" style="color:#111827;">New Order</h4>
        </div>
        <a href="orders.php" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

    <?php alertMessage(); ?>

    <div class="row g-4 pb-4">

        <!-- ===================== LEFT: Add Items ===================== -->
        <div class="col-xl-5 col-lg-5">

            <!-- Add Service -->
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex align-items-center gap-2" style="background:#fff;">
                    <span style="width:32px;height:32px;border-radius:8px;background:#eff6ff;color:#2563eb;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-concierge-bell" style="font-size:.8rem;"></i>
                    </span>
                    <span class="fw-semibold" style="font-size:.95rem;">Add Service</span>
                </div>
                <div class="card-body">
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

                        <!-- Service Info Panel -->
                        <div id="serviceInfo" class="d-none mb-3">
                            <div class="p-3 rounded-3" style="background:#f0f7ff;border:1px solid #bfdbfe;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-semibold" id="infoName" style="color:#1e3a8a;"></span>
                                    <span class="fw-bold" id="infoPrice" style="color:#2563eb;"></span>
                                </div>
                                <div id="infoItems" class="small"></div>
                            </div>
                        </div>

                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="service_qty" id="serviceQty"
                                       value="1" min="1" class="form-control" />
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="addService" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add Service
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Item Manually -->
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center gap-2" style="background:#fff;">
                    <span style="width:32px;height:32px;border-radius:8px;background:#f0fdf4;color:#059669;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-box" style="font-size:.8rem;"></i>
                    </span>
                    <span class="fw-semibold" style="font-size:.95rem;">Add Item Manually</span>
                </div>
                <div class="card-body">
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
                                <input type="number" name="item_qty" value="1" min="1" class="form-control" />
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="addConsumable" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- ===================== RIGHT: Order Summary ===================== -->
        <div class="col-xl-7 col-lg-7">
            <div class="card shadow-sm" style="min-height:420px;">
                <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:32px;height:32px;border-radius:8px;background:#fff7ed;color:#ea580c;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-receipt" style="font-size:.8rem;"></i>
                        </span>
                        <span class="fw-semibold" style="font-size:.95rem;">Order Summary</span>
                    </div>
                    <?php if (!empty($_SESSION['orderItems'])): ?>
                        <span class="badge bg-primary rounded-pill"><?= count($_SESSION['orderItems']) ?> item(s)</span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($_SESSION['orderItems'])): ?>

                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:.875rem;">
                            <thead>
                                <tr style="background:#f9fafb;">
                                    <th class="px-3 py-2 fw-semibold" style="color:#6b7280;font-size:.75rem;text-transform:uppercase;border-bottom:1px solid #e5e7eb;">#</th>
                                    <th class="px-3 py-2 fw-semibold" style="color:#6b7280;font-size:.75rem;text-transform:uppercase;border-bottom:1px solid #e5e7eb;">Type</th>
                                    <th class="px-3 py-2 fw-semibold" style="color:#6b7280;font-size:.75rem;text-transform:uppercase;border-bottom:1px solid #e5e7eb;">Name</th>
                                    <th class="px-3 py-2 fw-semibold text-center" style="color:#6b7280;font-size:.75rem;text-transform:uppercase;border-bottom:1px solid #e5e7eb;">Qty</th>
                                    <th class="px-3 py-2 fw-semibold text-end" style="color:#6b7280;font-size:.75rem;text-transform:uppercase;border-bottom:1px solid #e5e7eb;">Price</th>
                                    <th class="px-3 py-2 fw-semibold text-end" style="color:#6b7280;font-size:.75rem;text-transform:uppercase;border-bottom:1px solid #e5e7eb;">Total</th>
                                    <th class="px-3 py-2" style="border-bottom:1px solid #e5e7eb;"></th>
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
                                    
                                    // If it's a service, calculate consumables cost
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
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <td class="px-3 py-2" style="color:#9ca3af;"><?= $i++ ?></td>
                                    <td class="px-3 py-2">
                                        <?php if ($item['type'] == 'service'): ?>
                                            <span class="badge" style="background:#eff6ff;color:#2563eb;font-weight:500;">Service</span>
                                        <?php else: ?>
                                            <span class="badge" style="background:#f0fdf4;color:#059669;font-weight:500;">Item</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-2 fw-medium">
                                        <?= htmlspecialchars($item['name']) ?>
                                        <?php if ($item['type'] == 'service'): ?>
                                            <?php
                                            $serviceConsumablesQuery = mysqli_query($conn, "
                                                SELECT si.quantity_required, lc.price, lc.item_name
                                                FROM service_items si
                                                JOIN laundry_consumables lc ON lc.id = si.consumable_id
                                                WHERE si.service_id = '{$item['id']}'
                                            ");
                                            
                                            if ($serviceConsumablesQuery && mysqli_num_rows($serviceConsumablesQuery) > 0):
                                            ?>
                                                <div class="mt-1">
                                                    <?php while ($sc = mysqli_fetch_assoc($serviceConsumablesQuery)): 
                                                        $totalQtyNeeded = $sc['quantity_required'] * $item['quantity'];
                                                        $itemCost = $sc['price'] * $totalQtyNeeded;
                                                    ?>
                                                        <small class="d-block text-muted" style="font-size:0.75rem;">
                                                            <i class="fas fa-arrow-right" style="font-size:0.6rem;opacity:0.5;"></i>
                                                            <?= htmlspecialchars($sc['item_name']) ?> 
                                                            × <?= $totalQtyNeeded ?> 
                                                            @ &#8369;<?= number_format($sc['price'], 2) ?> 
                                                            = &#8369;<?= number_format($itemCost, 2) ?>
                                                        </small>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-2 text-center" style="color:#374151;"><?= $item['quantity'] ?></td>
                                    <td class="px-3 py-2 text-end" style="color:#6b7280;">&#8369;<?= number_format($item['price'], 2) ?></td>
                                    <td class="px-3 py-2 text-end fw-semibold" style="color:#111827;">&#8369;<?= number_format($lineTotal + $consumablesCost, 2) ?></td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="order-item-delete.php?index=<?= $key ?>"
                                           class="btn btn-sm btn-danger" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals Summary -->
                    <div class="px-3 py-3" style="border-top:2px solid #e5e7eb;background:#fafbfc;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small" style="color:#6b7280;">Services Total</span>
                            <span class="small fw-semibold" style="color:#374151;">&#8369;<?= number_format($servicesTotal, 2) ?></span>
                        </div>
                        <?php if ($consumablesFromServicesTotal > 0): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small" style="color:#6b7280;">Service Consumables</span>
                            <span class="small fw-semibold" style="color:#374151;">&#8369;<?= number_format($consumablesFromServicesTotal, 2) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small" style="color:#6b7280;">Manual Items Total</span>
                            <span class="small fw-semibold" style="color:#374151;">&#8369;<?= number_format($itemsTotal, 2) ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-2" style="border-top:1px solid #e5e7eb;">
                            <span class="fw-semibold" style="color:#374151;">Grand Total</span>
                            <span class="fw-bold" style="font-size:1.25rem;color:#2563eb;">&#8369;<?= number_format($grandTotal, 2) ?></span>
                        </div>
                    </div>

                    <!-- Checkout Footer -->
                    <div class="card-footer" style="background:#fff;border-top:1px solid #e5e7eb;">
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
                                                // Filter customers by branch unless super_admin
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
                                                        echo '<option value="'.$cust['phone'].'">'
                                                            .$cust['phone'].' &mdash; '.htmlspecialchars($cust['name'])
                                                            .'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="button"
                                                class="btn btn-outline-secondary flex-shrink-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addCustomerModal"
                                                title="Add new customer">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary w-100 proceedToPlace">
                                        <i class="fas fa-check me-1"></i> Place
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-5 text-center">
                        <div class="mb-3" style="width:60px;height:60px;border-radius:50%;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-shopping-cart" style="font-size:1.25rem;color:#d1d5db;"></i>
                        </div>
                        <p class="mb-1 fw-semibold" style="color:#374151;">No items added yet</p>
                        <small style="color:#9ca3af;">Pick a service or item from the left to get started.</small>
                    </div>

                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- ADD CUSTOMER MODAL -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCustomerForm">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title fw-semibold">
                        <i class="fas fa-user-plus me-2" style="color:#2563eb;"></i>Add New Customer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="c_name" name="name" class="form-control" placeholder="e.g. Juan dela Cruz" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="number" id="c_phone" name="phone" class="form-control" placeholder="e.g. 09123456789" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-muted" style="font-weight:400;">(optional)</span></label>
                        <input type="email" id="c_email" name="email" class="form-control" placeholder="e.g. juan@email.com">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary saveCustomer">
                        <i class="fas fa-save me-1"></i> Save Customer
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
        itemsDiv.innerHTML = '<span style="color:#6b7280;">No consumables required</span>';
    } else {
        const qty = parseInt(document.getElementById('serviceQty').value) || 1;
        itemsDiv.innerHTML = '<div style="color:#6b7280;margin-bottom:6px;">Requires:</div>' +
            svc.items.map(function (itm) {
                const needed = itm.qty * qty;
                const ok = itm.stock >= needed;
                return '<span class="badge me-1 mb-1" style="background:' + (ok ? '#dcfce7' : '#fee2e2') + ';color:' + (ok ? '#166534' : '#991b1b') + ';font-weight:500;">' +
                    itm.name + ' &times;' + needed + ' <span style="opacity:.7;">(stock: ' + itm.stock + ')</span></span>';
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
