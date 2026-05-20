<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Service
                <a href="services.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <?php
                $paramValue = checkParamId('id');
                if (!is_numeric($paramValue)) {
                    echo '<h5>Id is not an integer</h5>';
                    return false;
                }

                $service = getById('services', $paramValue);
                if (!$service || $service['status'] != 200) {
                    echo '<h5>' . ($service['message'] ?? 'Something Went Wrong') . '</h5>';
                    return false;
                }

                $serviceData = $service['data'];

                // All consumables with stock
                $allConsumables = mysqli_query($conn, "
                    SELECT lc.id, lc.item_name, lc.quantity, b.branch_name
                    FROM laundry_consumables lc
                    LEFT JOIN branches b ON b.id = lc.branch_id
                    ORDER BY lc.item_name ASC
                ");
                $consumablesList = [];
                if ($allConsumables && mysqli_num_rows($allConsumables) > 0) {
                    foreach ($allConsumables as $c) $consumablesList[] = $c;
                }

                // Existing service items
                $existingItems = mysqli_query($conn, "
                    SELECT si.consumable_id, si.quantity_required
                    FROM service_items si
                    WHERE si.service_id = '{$serviceData['id']}'
                    ORDER BY si.id ASC
                ");
                $existingList = [];
                if ($existingItems && mysqli_num_rows($existingItems) > 0) {
                    foreach ($existingItems as $row) $existingList[] = $row;
                }

                // Build a stock map for JS
                $stockMap = [];
                foreach ($consumablesList as $c) $stockMap[$c['id']] = (int)$c['quantity'];
            ?>

            <form action="<?= $baseUrl ?>code.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="service_id" value="<?= $serviceData['id']; ?>" />

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Service Name *</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($serviceData['name']); ?>" class="form-control" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($serviceData['description']); ?></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Price *</label>
                        <input type="text" name="price" required value="<?= $serviceData['price']; ?>" class="form-control" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Quantity *</label>
                        <input type="text" name="quantity" required value="<?= $serviceData['quantity']; ?>" class="form-control" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" />
                        <?php if (!empty($serviceData['image'])): ?>
                            <img src="../../<?= $serviceData['image']; ?>" class="mt-2"
                                 style="width:60px;height:60px;object-fit:cover;border-radius:4px;" alt="Current" />
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Visibility Status</label>
                        <br/>
                        <input type="checkbox" name="status" <?= $serviceData['status'] == 1 ? 'checked' : ''; ?> style="width:30px;height:30px" />
                        <small class="text-muted d-block mt-1">Check to hide service</small>
                    </div>
                </div>

                <hr />

                <!-- REQUIRED ITEMS -->
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="mb-0">Required Consumable Items</h5>
                        <button type="button" class="btn btn-sm btn-success" id="addItemRow">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <small class="text-muted">Define which inventory items are consumed when this service is performed.</small>

                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Consumable Item</th>
                                <th style="width:160px;">Qty Required</th>
                                <th style="width:140px;">Stock After</th>
                                <th style="width:60px;"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <?php if (!empty($existingList)): ?>
                                <?php foreach ($existingList as $i => $item): ?>
                                <tr>
                                    <td>
                                        <select name="items[<?= $i ?>][consumable_id]" class="form-select" required>
                                            <?php foreach ($consumablesList as $c):
                                                $label = htmlspecialchars($c['item_name']);
                                                if (!empty($c['branch_name'])) $label .= ' (' . htmlspecialchars($c['branch_name']) . ')';
                                                $label .= ' — Stock: ' . (int)$c['quantity'];
                                                $selected = ($c['id'] == $item['consumable_id']) ? 'selected' : '';
                                            ?>
                                                <option value="<?= $c['id'] ?>" data-stock="<?= (int)$c['quantity'] ?>" <?= $selected ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?= $i ?>][quantity]" class="form-control"
                                               value="<?= $item['quantity_required']; ?>" min="1" step="1" required />
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="remaining-badge badge bg-secondary">—</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="emptyRow">
                                    <td colspan="4" class="text-center text-muted py-3">No items added yet. Click "Add Item" to begin.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end">
                    <button type="submit" name="updateService" class="btn btn-primary">Update Service</button>
                    <a href="services.php" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
$consumableOptions = '<option value="" data-stock="0">-- Select Item --</option>';
foreach ($consumablesList as $c) {
    $label = htmlspecialchars($c['item_name']);
    if (!empty($c['branch_name'])) $label .= ' (' . htmlspecialchars($c['branch_name']) . ')';
    $label .= ' — Stock: ' . (int)$c['quantity'];
    $consumableOptions .= '<option value="' . $c['id'] . '" data-stock="' . (int)$c['quantity'] . '">' . $label . '</option>';
}
$startIndex = count($existingList);
?>

<script>
let rowIndex = <?= $startIndex ?>;
const consumableOptions = `<?= $consumableOptions ?>`;

function updateRemaining(row) {
    const select   = row.querySelector('select');
    const qtyInput = row.querySelector('input[type="number"]');
    const badge    = row.querySelector('.remaining-badge');
    if (!badge) return;

    const opt       = select.options[select.selectedIndex];
    const stock     = parseInt(opt?.dataset?.stock ?? 0);
    const qty       = parseFloat(qtyInput.value) || 0;
    const remaining = stock - qty;

    if (!opt?.value) {
        badge.className = 'remaining-badge badge bg-secondary';
        badge.textContent = '—';
    } else if (remaining < 0) {
        badge.className = 'remaining-badge badge bg-danger';
        badge.textContent = remaining + ' (insufficient)';
    } else {
        badge.className = 'remaining-badge badge bg-success';
        badge.textContent = remaining + ' left';
    }
}

// Initialize existing rows
document.querySelectorAll('#itemsBody tr').forEach(function (tr) {
    const select = tr.querySelector('select');
    const input  = tr.querySelector('input[type="number"]');
    if (!select || !input) return;
    updateRemaining(tr);
    select.addEventListener('change', () => updateRemaining(tr));
    input.addEventListener('input',  () => updateRemaining(tr));
});

document.getElementById('addItemRow').addEventListener('click', function () {
    document.getElementById('emptyRow')?.remove();

    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select name="items[${rowIndex}][consumable_id]" class="form-select" required>
                ${consumableOptions}
            </select>
        </td>
        <td>
            <input type="number" name="items[${rowIndex}][quantity]" class="form-control"
                   value="1" min="1" step="1" required />
        </td>
        <td class="text-center align-middle">
            <span class="remaining-badge badge bg-secondary">—</span>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-danger remove-row">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);

    tr.querySelector('select').addEventListener('change', () => updateRemaining(tr));
    tr.querySelector('input[type="number"]').addEventListener('input', () => updateRemaining(tr));
    updateRemaining(tr);

    rowIndex++;
});

document.getElementById('itemsBody').addEventListener('click', function (e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('tr').remove();
        if (!document.getElementById('itemsBody').children.length) {
            const empty = document.createElement('tr');
            empty.id = 'emptyRow';
            empty.innerHTML = '<td colspan="4" class="text-center text-muted py-3">No items added yet. Click "Add Item" to begin.</td>';
            document.getElementById('itemsBody').appendChild(empty);
        }
    }
});
</script>

<?php include('../includes/footer.php'); ?>
