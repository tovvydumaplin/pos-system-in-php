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

    .branches-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .branches-table {
        margin: 0;
        font-size: 0.875rem;
    }
    .branches-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .branches-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.8rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .branches-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.1s;
    }
    .branches-table tbody tr:last-child { border-bottom: none; }
    .branches-table tbody tr:hover { background: #f8f9fc; }
    .branches-table td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border: none;
        color: #2d3250;
    }

    .branch-name { font-weight: 700; color: #2d3250; }
    .branch-address { color: #6b7189; font-size: 0.82rem; }
    .branch-contact { color: #6b7189; font-size: 0.82rem; }
    .created-by { color: #9ba3b8; font-size: 0.8rem; }

    .status-active   { background: #e6f9f0; color: #1a9e5f; border-radius: 20px; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; }
    .status-inactive { background: #fdecea; color: #d63031; border-radius: 20px; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; }

    .btn-edit-branch {
        background: #eef2ff;
        color: #4361ee;
        border: none;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: background 0.15s;
        cursor: pointer;
    }
    .btn-edit-branch:hover { background: #dce4ff; }

    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: #9ba3b8;
    }
    .empty-state i { font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }
    .empty-state h6 { font-weight: 700; color: #6b7189; margin-bottom: 0.25rem; }
    .empty-state p { font-size: 0.82rem; margin: 0; }

    /* Modals */
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
    .modal-body {
        padding: 1.25rem 1.5rem;
    }
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
</style>

<div class="container-fluid px-4 mt-4">

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-code-branch me-2" style="opacity:0.75;"></i>Branches</h2>
            <p>Manage your laundromat branch locations</p>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addBranchModal">
            <i class="fas fa-plus"></i> Add Branch
        </button>
    </div>

    <!-- SEARCH -->
    <div class="filters-panel">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold mb-1">Search</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by name or address..."
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>
                <div class="col-md-auto">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="branches.php" class="btn-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <?php
    $perPage     = 10;
    $currentPage = max(1, (int)($_GET['page'] ?? 1));
    $search      = isset($_GET['search']) ? $_GET['search'] : '';

    $where = $search != '' ? "WHERE branch_name LIKE '%$search%' OR address LIKE '%$search%'" : "WHERE 1=1";

    $countRes    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM branches $where"));
    $totalRows   = (int)($countRes['cnt'] ?? 0);
    $totalPages  = max(1, ceil($totalRows / $perPage));
    $currentPage = min($currentPage, $totalPages);
    $offset      = ($currentPage - 1) * $perPage;

    $branches = mysqli_query($conn, "SELECT * FROM branches $where ORDER BY id DESC LIMIT $perPage OFFSET $offset");
    ?>

    <div class="branches-table-wrap">
        <?php if (mysqli_num_rows($branches) > 0): ?>
        <div class="table-responsive">
            <table class="table branches-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Branch Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($branches as $branch): ?>
                    <tr>
                        <td style="color:#9ba3b8;"><?= $i++ ?></td>
                        <td><span class="branch-name"><?= htmlspecialchars($branch['branch_name']) ?></span></td>
                        <td><span class="branch-address"><?= htmlspecialchars($branch['address']) ?></span></td>
                        <td><span class="branch-contact"><?= htmlspecialchars($branch['contact_no']) ?></span></td>
                        <td>
                            <?php if ($branch['status'] == 'ACTIVE'): ?>
                                <span class="status-active">Active</span>
                            <?php else: ?>
                                <span class="status-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="created-by"><?= htmlspecialchars($branch['created_by'] ?? '-') ?></span></td>
                        <td>
                            <button class="btn-edit-branch editBranchBtn"
                                    data-id="<?= $branch['id'] ?>"
                                    data-name="<?= htmlspecialchars($branch['branch_name']) ?>"
                                    data-address="<?= htmlspecialchars($branch['address']) ?>"
                                    data-contact="<?= htmlspecialchars($branch['contact_no']) ?>"
                                    data-status="<?= $branch['status'] ?>">
                                <i class="fas fa-pen"></i> Edit
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        $pParams = $_GET; unset($pParams['page']);
        $pBase   = '?' . (http_build_query($pParams) ? http_build_query($pParams) . '&' : '') . 'page=';
        $pFrom   = $totalRows > 0 ? $offset + 1 : 0;
        $pTo     = min($offset + $perPage, $totalRows);
        $pStart  = max(1, min($currentPage - 2, $totalPages - 4));
        $pEnd    = min($totalPages, $pStart + 4);
        ?>
        <div class="pagination-wrap">
            <span class="pagination-info">Showing <?= $pFrom ?>–<?= $pTo ?> of <?= $totalRows ?></span>
            <div class="pagination-btns">
                <a href="<?= $pBase . ($currentPage - 1) ?>" class="page-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>">‹ Prev</a>
                <?php for ($pi = $pStart; $pi <= $pEnd; $pi++): ?>
                    <a href="<?= $pBase . $pi ?>" class="page-btn <?= $pi == $currentPage ? 'active' : '' ?>"><?= $pi ?></a>
                <?php endfor; ?>
                <a href="<?= $pBase . ($currentPage + 1) ?>" class="page-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">Next ›</a>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-code-branch"></i>
            <h6>No Branches Found</h6>
            <p>Add your first branch to get started.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- ADD BRANCH MODAL -->
<div class="modal fade" id="addBranchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addBranchForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2" style="color:#4361ee;opacity:0.85;"></i>Add Branch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" class="form-control" placeholder="e.g. Main Branch" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Full address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_no" class="form-control" placeholder="e.g. 09123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="ACTIVE" selected>Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-check"></i> Save Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT BRANCH MODAL -->
<div class="modal fade" id="editBranchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editBranchForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-pen me-2" style="color:#4361ee;opacity:0.85;"></i>Edit Branch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="branchId" id="editBranchId">
                    <div class="mb-3">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" id="editBranchName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" id="editAddress" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_no" id="editContactNumber" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="editStatus" class="form-select">
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-check"></i> Update Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<script src="../assets/js/branches.js"></script>
