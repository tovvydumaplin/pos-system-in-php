<?php include('../includes/header.php'); ?>

<?php
$branchId = $_SESSION['loggedInUser']['branch_id'];
$userType = $_SESSION['loggedInUser']['user_type'];
$isSuperAdmin = ($userType == 'super_admin');
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

    .filters-panel {
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .filters-panel .form-control,
    .filters-panel .form-select {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.85rem;
        background: #fff;
    }
    .filters-panel .form-control:focus,
    .filters-panel .form-select:focus {
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
    .btn-reset {
        background: #fff;
        color: #6b7189;
        border: 1px solid #dde1ec;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: background 0.15s;
    }
    .btn-reset:hover { background: #f0f2f8; color: #2d3250; }

    .movement-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .movement-table {
        margin: 0;
        font-size: 0.875rem;
    }
    .movement-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .movement-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.8rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .movement-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.1s;
    }
    .movement-table tbody tr:last-child { border-bottom: none; }
    .movement-table tbody tr:hover { background: #f8f9fc; }
    .movement-table td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border: none;
        color: #2d3250;
    }

    .item-name  { font-weight: 600; color: #2d3250; }
    .ref-no     { font-family: monospace; font-size: 0.82rem; color: #6b7189; }
    .remarks-text { font-size: 0.82rem; color: #6b7189; }
    .date-text  { font-size: 0.82rem; color: #6b7189; }
    .created-by { font-size: 0.82rem; color: #9ba3b8; }

    .branch-pill {
        background: #eef2ff;
        color: #4361ee;
        border-radius: 6px;
        font-size: 0.75rem;
        padding: 0.22rem 0.6rem;
        font-weight: 600;
    }

    .badge-in         { background: #e6f9f0; color: #1a9e5f; border-radius: 6px; font-size: 0.75rem; padding: 0.25rem 0.65rem; font-weight: 600; }
    .badge-out        { background: #fdecea; color: #d63031; border-radius: 6px; font-size: 0.75rem; padding: 0.25rem 0.65rem; font-weight: 600; }
    .badge-adjustment { background: #fff8ec; color: #e67e22; border-radius: 6px; font-size: 0.75rem; padding: 0.25rem 0.65rem; font-weight: 600; }

    .qty-in  { font-weight: 700; color: #1a9e5f; }
    .qty-out { font-weight: 700; color: #d63031; }
    .qty-adj { font-weight: 700; color: #e67e22; }

    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: #9ba3b8;
    }
    .empty-state i { font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }
    .empty-state h6 { font-weight: 700; color: #6b7189; margin-bottom: 0.25rem; }
    .empty-state p  { font-size: 0.82rem; margin: 0; }
</style>

<div class="container-fluid px-4 mt-4">

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-exchange-alt me-2" style="opacity:0.75;"></i>Stock Movement</h2>
            <p>Track inventory changes across all items<?= $isSuperAdmin ? ' and branches' : '' ?></p>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="filters-panel">
        <form method="GET">
            <div class="row g-2 align-items-end">

                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">Search</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Item name or reference..."
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold mb-1">Movement</label>
                    <select name="movement_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="IN"         <?= (isset($_GET['movement_type']) && $_GET['movement_type'] == 'IN') ? 'selected' : '' ?>>Stock In</option>
                        <option value="OUT"        <?= (isset($_GET['movement_type']) && $_GET['movement_type'] == 'OUT') ? 'selected' : '' ?>>Stock Out</option>
                        <option value="ADJUSTMENT" <?= (isset($_GET['movement_type']) && $_GET['movement_type'] == 'ADJUSTMENT') ? 'selected' : '' ?>>Adjustment</option>
                    </select>
                </div>

                <?php if ($isSuperAdmin): ?>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold mb-1">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        <?php
                        $branches = mysqli_query($conn, "SELECT * FROM branches ORDER BY branch_name ASC");
                        while ($b = mysqli_fetch_assoc($branches)):
                        ?>
                            <option value="<?= $b['id'] ?>"
                                <?= (isset($_GET['branch_id']) && $_GET['branch_id'] == $b['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['branch_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md-auto">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="stock-movement.php" class="btn-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- TABLE -->
    <?php
    $query = "
        SELECT sm.*, lc.item_name, u.name AS created_by_name, b.branch_name
        FROM stock_movement sm
        LEFT JOIN laundry_consumables lc ON lc.id = sm.consumable_id
        LEFT JOIN users u ON u.id = sm.created_by
        LEFT JOIN branches b ON b.id = sm.branch_id
        WHERE 1=1
    ";

    if (!$isSuperAdmin) {
        $query .= " AND sm.branch_id='$branchId'";
    }

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $search = validate($_GET['search']);
        $query .= " AND (lc.item_name LIKE '%$search%' OR sm.reference_no LIKE '%$search%')";
    }

    if (isset($_GET['movement_type']) && $_GET['movement_type'] != '') {
        $movementType = validate($_GET['movement_type']);
        $query .= " AND sm.movement_type='$movementType'";
    }

    if ($isSuperAdmin && isset($_GET['branch_id']) && $_GET['branch_id'] != '') {
        $filterBranch = validate($_GET['branch_id']);
        $query .= " AND sm.branch_id='$filterBranch'";
    }

    $query .= " ORDER BY sm.id DESC";
    $result = mysqli_query($conn, $query);
    ?>

    <div class="movement-table-wrap">
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table movement-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <?php if ($isSuperAdmin): ?><th>Branch</th><?php endif; ?>
                        <th>Movement</th>
                        <th>Qty</th>
                        <th>Reference</th>
                        <th>Remarks</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($result as $row):
                        $mvType = $row['movement_type'];
                    ?>
                    <tr>
                        <td style="color:#9ba3b8;"><?= $no++ ?></td>
                        <td><span class="item-name"><?= htmlspecialchars($row['item_name']) ?></span></td>
                        <?php if ($isSuperAdmin): ?>
                        <td><span class="branch-pill"><?= htmlspecialchars($row['branch_name'] ?? '-') ?></span></td>
                        <?php endif; ?>
                        <td>
                            <?php if ($mvType == 'IN'): ?>
                                <span class="badge-in"><i class="fas fa-arrow-down me-1"></i>Stock In</span>
                            <?php elseif ($mvType == 'OUT'): ?>
                                <span class="badge-out"><i class="fas fa-arrow-up me-1"></i>Stock Out</span>
                            <?php else: ?>
                                <span class="badge-adjustment"><i class="fas fa-sliders-h me-1"></i>Adjustment</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="<?= $mvType == 'IN' ? 'qty-in' : ($mvType == 'OUT' ? 'qty-out' : 'qty-adj') ?>">
                                <?= $mvType == 'IN' ? '+' : ($mvType == 'OUT' ? '-' : '±') ?><?= $row['quantity'] ?>
                            </span>
                        </td>
                        <td><span class="ref-no"><?= htmlspecialchars($row['reference_no']) ?></span></td>
                        <td><span class="remarks-text"><?= htmlspecialchars($row['remarks']) ?></span></td>
                        <td><span class="created-by"><?= htmlspecialchars($row['created_by_name']) ?></span></td>
                        <td><span class="date-text"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-exchange-alt"></i>
            <h6>No Stock Movement Found</h6>
            <p>Try adjusting your filters or check back later.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php include('../includes/footer.php'); ?>
