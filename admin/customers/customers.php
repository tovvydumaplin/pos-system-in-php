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
    .btn-add {
        background: #2d3250; border: none; color: #fff;
        padding: 0.55rem 1.25rem; border-radius: 9px;
        font-weight: 600; font-size: 0.85rem;
        display: inline-flex; align-items: center; gap: 0.45rem;
        text-decoration: none; transition: background 0.2s; white-space: nowrap;
    }
    .btn-add:hover { background: #424870; color: #fff; }

    .customers-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .customers-table {
        margin: 0;
        font-size: 0.875rem;
    }
    .customers-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .customers-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.8rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .customers-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.1s;
    }
    .customers-table tbody tr:last-child { border-bottom: none; }
    .customers-table tbody tr:hover { background: #f8f9fc; }
    .customers-table td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border: none;
        color: #2d3250;
    }

    .customer-name  { font-weight: 700; color: #2d3250; }
    .customer-email { font-size: 0.8rem; color: #9ba3b8; }
    .customer-phone { color: #6b7189; font-size: 0.82rem; }
    .branch-pill    { background: #eef2ff; color: #4361ee; border-radius: 6px; font-size: 0.75rem; padding: 0.22rem 0.6rem; font-weight: 600; }

    .status-visible { background: #e6f9f0; color: #1a9e5f; border-radius: 20px; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; }
    .status-hidden  { background: #fdecea; color: #d63031; border-radius: 20px; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; }

    .btn-edit {
        background: #eef2ff; color: #4361ee; border: none;
        border-radius: 7px; font-size: 0.78rem; font-weight: 600;
        padding: 0.35rem 0.8rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.3rem;
        transition: background 0.15s;
    }
    .btn-edit:hover { background: #dce4ff; color: #4361ee; }
    .btn-delete {
        background: #fdecea; color: #d63031; border: none;
        border-radius: 7px; font-size: 0.78rem; font-weight: 600;
        padding: 0.35rem 0.8rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.3rem;
        transition: background 0.15s;
    }
    .btn-delete:hover { background: #fbc8c8; color: #d63031; }

    .empty-state {
        text-align: center; padding: 3.5rem 2rem; color: #9ba3b8;
    }
    .empty-state i  { font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }
    .empty-state h6 { font-weight: 700; color: #6b7189; margin-bottom: 0.25rem; }
    .empty-state p  { font-size: 0.82rem; margin: 0; }
</style>

<div class="container-fluid px-4 mt-4">

    <?php alertMessage(); ?>

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-user-friends me-2" style="opacity:0.75;"></i>Customers</h2>
            <p>Manage your registered customer records</p>
        </div>
        <a href="customers-create.php" class="btn-add">
            <i class="fas fa-plus"></i> Add Customer
        </a>
    </div>

    <!-- TABLE -->
    <?php
    $isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';
    $userBranchId = $_SESSION['loggedInUser']['branch_id'] ?? null;

    $perPage     = 10;
    $currentPage = max(1, (int)($_GET['page'] ?? 1));
    $where       = $isSuperAdmin ? "WHERE 1=1" : "WHERE branch_id = '$userBranchId'";

    $countRes    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM customers $where"));
    $totalRows   = (int)($countRes['cnt'] ?? 0);
    $totalPages  = max(1, ceil($totalRows / $perPage));
    $currentPage = min($currentPage, $totalPages);
    $offset      = ($currentPage - 1) * $perPage;

    $customers = mysqli_query($conn, "SELECT * FROM customers $where ORDER BY id DESC LIMIT $perPage OFFSET $offset");

    if (!$customers) {
        echo '<div class="alert alert-danger">Something went wrong loading customers.</div>';
        include('../includes/footer.php');
        exit;
    }
    ?>

    <div class="customers-table-wrap">
        <?php if (mysqli_num_rows($customers) > 0): ?>
        <div class="table-responsive">
            <table class="table customers-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $item):
                        $branchName = null;
                        if (!empty($item['branch_id'])) {
                            $branchData = getById('branches', $item['branch_id']);
                            if ($branchData && $branchData['status'] == 200) {
                                $branchName = $branchData['data']['branch_name'];
                            }
                        }
                    ?>
                    <tr>
                        <td style="color:#9ba3b8;"><?= $item['id'] ?></td>
                        <td>
                            <span class="customer-name"><?= htmlspecialchars($item['name']) ?></span>
                            <?php if (!empty($item['email'])): ?>
                                <span class="customer-email d-block"><?= htmlspecialchars($item['email']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><span class="customer-phone"><?= htmlspecialchars($item['phone']) ?></span></td>
                        <td>
                            <?php if ($branchName): ?>
                                <span class="branch-pill"><?= htmlspecialchars($branchName) ?></span>
                            <?php else: ?>
                                <span style="color:#c5cade;font-size:0.8rem;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($item['status'] == 1): ?>
                                <span class="status-hidden"><i class="fas fa-eye-slash me-1" style="font-size:0.65rem;"></i>Hidden</span>
                            <?php else: ?>
                                <span class="status-visible"><i class="fas fa-eye me-1" style="font-size:0.65rem;"></i>Visible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="customers-edit.php?id=<?= $item['id'] ?>" class="btn-edit">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <a href="customers-delete.php?id=<?= $item['id'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Delete this customer?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </div>
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
            <i class="fas fa-user-friends"></i>
            <h6>No Customers Found</h6>
            <p>Add your first customer to get started.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php include('../includes/footer.php'); ?>
