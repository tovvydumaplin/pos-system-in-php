<?php include('../includes/header.php'); ?>

<?php
$branchId = $_SESSION['loggedInUser']['branch_id'];

// Services with their required items as JSON for JS
$servicesQuery = mysqli_query($conn, "
    SELECT s.id, s.name, s.price,
        GROUP_CONCAT(
            CONCAT(si.quantity_required, '||', lc.item_name, '||', lc.quantity)
            ORDER BY lc.item_name ASC SEPARATOR '@@'
        ) AS items_raw
    FROM services s
    LEFT JOIN service_items si ON si.service_id = s.id
    LEFT JOIN laundry_consumables lc ON lc.id = si.consumable_id
    WHERE s.status = 0
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

// Consumables for this branch
$consumables = mysqli_query($conn, "
    SELECT * FROM laundry_consumables
    WHERE branch_id = '$branchId'
    ORDER BY item_name ASC
");
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Order
                <a href="orders.php" class="btn btn-outline-primary btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="orders-code.php" method="POST">
                <div class="row g-3">

                    <!-- SERVICE -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light py-2">
                                <i class="fas fa-concierge-bell me-1"></i> Add Service
                            </div>
                            <div class="card-body">
                                <div class="row g-2 align-items-end">
                                    <div class="col">
                                        <label class="form-label small mb-1">Service</label>
                                        <select name="service_id" id="serviceSelect" class="form-select">
                                            <option value="">-- Select Service --</option>
                                            <?php foreach ($servicesData as $id => $svc): ?>
                                                <option value="<?= $id ?>">
                                                    <?= htmlspecialchars($svc['name']) ?> — ₱<?= number_format($svc['price'], 2) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label class="form-label small mb-1">Qty</label>
                                        <input type="number" name="service_qty" id="serviceQty"
                                               value="1" min="1" class="form-control" style="width:70px;" />
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" name="addService" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>

                                <!-- Service info panel -->
                                <div id="serviceInfo" class="mt-3 d-none">
                                    <div class="p-2 rounded bg-light border small">
                                        <div class="fw-semibold mb-1" id="infoName"></div>
                                        <div class="text-success mb-2" id="infoPrice"></div>
                                        <div id="infoItems"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CONSUMABLE -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light py-2">
                                <i class="fas fa-box me-1"></i> Add Item Manually
                            </div>
                            <div class="card-body">
                                <div class="row g-2 align-items-end">
                                    <div class="col">
                                        <label class="form-label small mb-1">Item</label>
                                        <select name="consumable_id" class="form-select mySelect2">
                                            <option value="">-- Select Item --</option>
                                            <?php if ($consumables && mysqli_num_rows($consumables) > 0):
                                                foreach ($consumables as $item):
                                                    $disabled = $item['quantity'] <= 0 ? 'disabled' : '';
                                            ?>
                                                <option value="<?= $item['id'] ?>" <?= $disabled ?>>
                                                    <?= htmlspecialchars($item['item_name']) ?>
                                                    (<?= $item['quantity'] ?> left · ₱<?= number_format($item['price'], 2) ?>)
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label class="form-label small mb-1">Qty</label>
                                        <input type="number" name="item_qty" value="1" min="1"
                                               class="form-control" style="width:70px;" />
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" name="addConsumable" class="btn btn-success">
                                            <i class="fas fa-plus me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- ORDER ITEMS TABLE -->
    <?php if (!empty($_SESSION['orderItems'])): ?>
    <div class="card mt-3 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span><i class="fas fa-receipt me-1"></i> Order Items</span>
            <span class="badge bg-primary"><?= count($_SESSION['orderItems']) ?> item(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
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
                        $grandTotal = 0;
                        foreach ($_SESSION['orderItems'] as $key => $item):
                            $lineTotal = $item['price'] * $item['quantity'];
                            $grandTotal += $lineTotal;
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <?php if ($item['type'] == 'service'): ?>
                                    <span class="badge bg-primary">Service</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Item</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end">₱<?= number_format($item['price'], 2) ?></td>
                            <td class="text-end fw-semibold">₱<?= number_format($lineTotal, 2) ?></td>
                            <td class="text-center">
                                <a href="order-item-delete.php?index=<?= $key ?>"
                                   class="btn btn-danger btn-sm">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="5" class="text-end fw-bold">Grand Total</td>
                            <td class="text-end fw-bold text-success">₱<?= number_format($grandTotal, 2) ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- PAYMENT + CUSTOMER -->
        <div class="card-footer">
            <form id="proceedForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Payment Mode</label>
                        <select id="payment_mode" class="form-select">
                            <option value="">-- Select Payment --</option>
                            <option value="Cash Payment">Cash Payment</option>
                            <option value="Online Payment">Online Payment</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Customer</label>
                        <select id="cphone" class="form-select mySelect2">
                            <option value="">-- Select Customer --</option>
                            <?php
                            $customers = getAll('customers');
                            if ($customers && mysqli_num_rows($customers) > 0) {
                                foreach ($customers as $cust) {
                                    echo '<option value="'.$cust['phone'].'">'
                                        .$cust['phone'].' - '.htmlspecialchars($cust['name'])
                                        .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-toggle="modal"
                                data-bs-target="#addCustomerModal">
                            + New Customer
                        </button>
                    </div>
                    <div class="col-md-auto ms-auto">
                        <button type="button" class="btn btn-warning proceedToPlace">
                            <i class="fas fa-check me-1"></i> Proceed to Place Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="card mt-3 shadow-sm">
        <div class="card-body text-center text-muted py-4">
            <i class="fas fa-shopping-cart fa-2x mb-2 d-block opacity-25"></i>
            No items added yet.
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- ADD CUSTOMER MODAL -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="addCustomerForm">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" id="c_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="number" id="c_phone" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email (optional)</label>
                    <input type="email" id="c_email" name="email" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveCustomer">Save Customer</button>
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
    document.getElementById('infoPrice').textContent = '₱' + parseFloat(svc.price).toLocaleString('en-PH', {minimumFractionDigits: 2});

    const itemsDiv = document.getElementById('infoItems');
    if (svc.items.length === 0) {
        itemsDiv.innerHTML = '<span class="text-muted">No consumables required</span>';
    } else {
        const qty = parseInt(document.getElementById('serviceQty').value) || 1;
        itemsDiv.innerHTML = '<div class="text-muted mb-1">Requires:</div>' +
            svc.items.map(function(itm) {
                const needed = itm.qty * qty;
                const ok = itm.stock >= needed;
                const cls = ok ? 'bg-success' : 'bg-danger';
                return '<span class="badge ' + cls + ' me-1">' +
                    itm.name + ' ×' + needed + ' <small>(stock: ' + itm.stock + ')</small></span>';
            }).join('');
    }

    panel.classList.remove('d-none');
});

// Recalculate needed qty when service qty changes
document.getElementById('serviceQty').addEventListener('input', function() {
    const sel = document.getElementById('serviceSelect');
    if (sel.value) sel.dispatchEvent(new Event('change'));
});
</script>

<?php include('../includes/footer.php'); ?>
