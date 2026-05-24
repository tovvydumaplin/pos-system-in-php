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
    .btn-back:hover {
        background: #f0f2f8;
        color: #2d3250;
    }

    .form-panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.5rem;
    }
    .form-panel .panel-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9ba3b8;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f0f2f8;
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5072;
        margin-bottom: 0.35rem;
    }
    .form-control,
    .form-select {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #2d3250;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }

    .visibility-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 10px;
        padding: 0.85rem 1.1rem;
    }
    .visibility-toggle input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #2d3250;
        cursor: pointer;
        flex-shrink: 0;
    }
    .visibility-toggle .toggle-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5072;
        margin: 0;
    }
    .visibility-toggle .toggle-hint {
        font-size: 0.78rem;
        color: #9ba3b8;
        margin: 0;
    }

    .items-table {
        margin: 0;
        font-size: 0.875rem;
    }
    .items-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .items-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.75rem 1rem;
        border: none;
    }
    .items-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
    }
    .items-table tbody tr:last-child {
        border-bottom: none;
    }
    .items-table td {
        padding: 0.65rem 1rem;
        vertical-align: middle;
        border: none;
    }
    .items-table .form-control,
    .items-table .form-select {
        font-size: 0.82rem;
    }
    .items-table-wrap {
        border: 1px solid #e6e9f0;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 0.75rem;
    }

    .btn-add-item {
        background: #e6f9f0;
        color: #1a9e5f;
        border: none;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.45rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: background 0.15s;
        cursor: pointer;
    }
    .btn-add-item:hover {
        background: #c8f5e0;
    }

    .btn-remove-row {
        background: #fdecea;
        color: #d63031;
        border: none;
        border-radius: 7px;
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-remove-row:hover {
        background: #fbc8c8;
    }

    .remaining-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
    }
    .remaining-badge.ok      { background: #e6f9f0; color: #1a9e5f; }
    .remaining-badge.warn    { background: #fdecea; color: #d63031; }
    .remaining-badge.neutral { background: #f0f2f8; color: #9ba3b8; }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 0.5rem;
    }
    .btn-save {
        background: #2d3250;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s;
        cursor: pointer;
    }
    .btn-save:hover {
        background: #424870;
    }
    .btn-cancel {
        background: #fff;
        color: #6b7189;
        border: 1px solid #dde1ec;
        border-radius: 9px;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 0.6rem 1.25rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-cancel:hover {
        background: #f0f2f8;
        color: #2d3250;
    }

    .empty-items-row td {
        text-align: center;
        color: #b0b5c8;
        font-size: 0.82rem;
        font-style: italic;
        padding: 1.5rem;
    }
</style>

<div class="container-fluid px-4 mt-4">

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-plus-circle me-2" style="opacity:0.75;"></i>Add Service</h2>
            <p>Fill in the details to create a new service</p>
        </div>
        <a href="services.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Services
        </a>
    </div>

    <?php alertMessage(); ?>

    <form action="<?= $baseUrl ?>code.php" method="POST" enctype="multipart/form-data">

        <!-- BASIC INFO -->
        <div class="form-panel">
            <div class="panel-title">Basic Information</div>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Service Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. Regular Wash">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional short description of this service"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Base Price <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#f4f6fb;border-color:#dde1ec;color:#6b7189;font-size:0.85rem;">₱</span>
                        <input type="text" name="price" required class="form-control" placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Service Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block">Visibility</label>
                    <div class="visibility-toggle">
                        <input type="checkbox" name="status" id="statusToggle">
                        <div>
                            <p class="toggle-label">Hide this service</p>
                            <p class="toggle-hint">When checked, this service won't appear to customers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- REQUIRED ITEMS -->
        <div class="form-panel">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <div class="panel-title mb-0" style="border:none;padding:0;">Required Consumable Items</div>
                <button type="button" class="btn-add-item" id="addItemRow">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
            <p style="font-size:0.8rem;color:#9ba3b8;margin:0.5rem 0 0;">Define which inventory items are consumed when this service is performed.</p>

            <div class="items-table-wrap">
                <table class="table items-table">
                    <thead>
                        <tr>
                            <th>Consumable Item</th>
                            <th style="width:160px;">Qty Required</th>
                            <th style="width:150px;">Stock After</th>
                            <th style="width:50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr id="emptyRow" class="empty-items-row">
                            <td colspan="4">No items added yet. Click "Add Item" to begin.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="form-actions">
            <a href="services.php" class="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" name="saveService" class="btn-save">
                <i class="fas fa-check"></i> Save Service
            </button>
        </div>

    </form>
</div>

<?php
$isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';
$userBranchId = $_SESSION['loggedInUser']['branch_id'] ?? null;

if ($isSuperAdmin) {
    $consumables = mysqli_query($conn, "
        SELECT lc.id, lc.item_name, lc.quantity, b.branch_name
        FROM laundry_consumables lc
        LEFT JOIN branches b ON b.id = lc.branch_id
        ORDER BY lc.item_name ASC
    ");
} else {
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
        badge.className = 'remaining-badge neutral';
        badge.textContent = '—';
    } else if (remaining < 0) {
        badge.className = 'remaining-badge warn';
        badge.textContent = remaining + ' (insufficient)';
    } else {
        badge.className = 'remaining-badge ok';
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
            <span class="remaining-badge neutral">—</span>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn-remove-row remove-row">
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
            empty.className = 'empty-items-row';
            empty.innerHTML = '<td colspan="4">No items added yet. Click "Add Item" to begin.</td>';
            document.getElementById('itemsBody').appendChild(empty);
        }
    }
});
</script>

<?php include('../includes/footer.php'); ?>
