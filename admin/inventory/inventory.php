<?php include('../includes/header.php'); ?>

<?php
$branchId = $_SESSION['loggedInUser']['branch_id'];
$getBranch = mysqli_query($conn, "SELECT branch_name FROM branches WHERE id='$branchId' LIMIT 1");
$branch = mysqli_fetch_assoc($getBranch);
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
    .btn-add {
        background: #2d3250;
        border: none;
        color: #fff;
        padding: 0.55rem 1.25rem;
        border-radius: 9px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-add:hover { background: #424870; color: #fff; }

    .filters-panel {
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .filters-panel .form-control {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.85rem;
        background: #fff;
    }
    .filters-panel .form-control:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }
    .btn-filter {
        background: #2d3250;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        transition: background 0.2s;
        cursor: pointer;
    }
    .btn-filter:hover { background: #424870; color: #fff; }

    .inventory-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        overflow: hidden;
    }

    /* Modal styles */
    .modal-content {
        border-radius: 14px;
        border: 1px solid #e6e9f0;
    }
    .modal-header {
        border-bottom: 1px solid #f0f2f8;
        padding: 1.1rem 1.5rem;
    }
    .modal-title {
        font-weight: 700;
        font-size: 1rem;
        color: #2d3250;
    }
    .modal-body { padding: 1.25rem 1.5rem; }
    .modal-footer {
        border-top: 1px solid #f0f2f8;
        padding: 0.9rem 1.5rem;
    }
    .modal .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5072;
        margin-bottom: 0.35rem;
    }
    .modal .form-control,
    .modal .form-select {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #2d3250;
    }
    .modal .form-control:focus,
    .modal .form-select:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }
    .readonly-field {
        background: #f4f6fb !important;
        color: #6b7189 !important;
        cursor: not-allowed;
    }
    .btn-modal-cancel {
        background: #fff;
        color: #6b7189;
        border: 1px solid #dde1ec;
        border-radius: 9px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        transition: background 0.15s;
        cursor: pointer;
    }
    .btn-modal-cancel:hover { background: #f0f2f8; color: #2d3250; }
    .btn-modal-save {
        background: #2d3250;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s;
        cursor: pointer;
    }
    .btn-modal-save:hover { background: #424870; }
    .btn-modal-save.green { background: #1a9e5f; }
    .btn-modal-save.green:hover { background: #148a50; }
</style>

<div class="container-fluid px-4 mt-4">

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-boxes me-2" style="opacity:0.75;"></i>Inventory</h2>
            <p><?= htmlspecialchars($branch['branch_name'] ?? 'Branch') ?> — Laundry Consumables</p>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="fas fa-plus"></i> Add Item
        </button>
    </div>

    <!-- SEARCH -->
    <div class="filters-panel">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold mb-1">Search</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search item..."
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TABLE (loaded via JS) -->
    <div class="inventory-table-wrap">
        <div id="inventoryTable"></div>
    </div>

</div>

<!-- ADD ITEM MODAL -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addItemForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2" style="color:#4361ee;opacity:0.85;"></i>Add New Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control" placeholder="e.g. Detergent Powder" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch</label>
                        <input type="hidden" name="branch_id" value="<?= $branchId ?>">
                        <input type="text" class="form-control readonly-field" value="<?= htmlspecialchars($branch['branch_name'] ?? '') ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:#f4f6fb;border-color:#dde1ec;color:#6b7189;font-size:0.85rem;">₱</span>
                            <input type="number" name="price" class="form-control" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-check"></i> Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADJUST STOCK MODAL -->
<div class="modal fade" id="adjustStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="adjustStockForm">
                <input type="hidden" name="item_id" id="adjust_item_id">
                <input type="hidden" name="branch_id" id="adjust_branch_id">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sliders-h me-2" style="color:#1a9e5f;opacity:0.85;"></i>Adjust Stock
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" id="adjust_item_name" class="form-control readonly-field" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch</label>
                        <input type="text" id="adjust_branch_name" class="form-control readonly-field" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Stock</label>
                        <input type="number" id="current_stock" class="form-control readonly-field" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="ADD">Add Stock</option>
                            <option value="DEDUCT">Deduct Stock</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="add_quantity" class="form-control" min="1" placeholder="Enter quantity" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-save green">
                        <i class="fas fa-check"></i> Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT ITEM MODAL -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editItemForm">
                <input type="hidden" name="item_id" id="edit_item_id">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-pen me-2" style="color:#4361ee;opacity:0.85;"></i>Edit Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" id="edit_item_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch</label>
                        <input type="text" id="edit_branch_name" class="form-control readonly-field" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:#f4f6fb;border-color:#dde1ec;color:#6b7189;font-size:0.85rem;">₱</span>
                            <input type="number" name="price" id="edit_price" step="0.01" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-check"></i> Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
<script src="<?= $baseUrl ?>assets/js/inventory.js"></script>
