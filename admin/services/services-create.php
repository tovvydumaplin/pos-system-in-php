<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Service
                <a href="services.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="<?= $baseUrl ?>code.php" method="POST" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Service Name *</label>
                        <input type="text" name="name" required class="form-control" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Base Price *</label>
                        <input type="text" name="price" required class="form-control" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Visibility Status</label>
                        <br/>
                        <input type="checkbox" name="status" style="width:30px;height:30px" />
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
                            <tr id="emptyRow">
                                <td colspan="4" class="text-center text-muted py-3">No items added yet. Click "Add Item" to begin.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end">
                    <button type="submit" name="saveService" class="btn btn-primary">Save Service</button>
                    <a href="services.php" class="btn btn-outline-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
// Filter consumables by branch unless super_admin
$isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';
$userBranchId = $_SESSION['loggedInUser']['branch_id'] ?? null;

if ($isSuperAdmin) {
    // Super admin sees all consumables
    $consumables = mysqli_query($conn, "
        SELECT lc.id, lc.item_name, lc.quantity, b.branch_name
        FROM laundry_consumables lc
        LEFT JOIN branches b ON b.id = lc.branch_id
        ORDER BY lc.item_name ASC
    ");
} else {
    // Regular admin/staff only sees their branch consumables
    $consumables = mysqli_query($conn, "
        SELECT lc.id, lc.item_name, lc.quantity, b.branch_name
        FROM laundry_consumables lc
        LEFT JOIN branches b ON b.id = lc.branch_id
        WHERE lc.branch_id = '$userBranchId'
        ORDER BY lc.item_name ASC
    ");
}
$consumableOptions = '<option value="" data-stock="0">-- Select Item --</option>';
if ($consumables && mysqli_num_rows($consumables) > 0) {
    foreach ($consumables as $c) {
        $label = htmlspecialchars($c['item_name']);
        if (!empty($c['branch_name'])) $label .= ' (' . htmlspecialchars($c['branch_name']) . ')';
        $label .= ' — Stock: ' . (int)$c['quantity'];
        $consumableOptions .= '<option value="' . $c['id'] . '" data-stock="' . (int)$c['quantity'] . '">' . $label . '</option>';
    }
}
?>

<script>
let rowIndex = 0;
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
