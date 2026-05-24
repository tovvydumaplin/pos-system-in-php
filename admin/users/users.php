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
        text-decoration: none;
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
    .filters-panel .form-select {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.85rem;
        background: #fff;
    }
    .filters-panel .form-select:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }

    .users-table-wrap {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .users-table {
        margin: 0;
        font-size: 0.875rem;
    }
    .users-table thead tr {
        background: #f4f6fb;
        border-bottom: 2px solid #e6e9f0;
    }
    .users-table thead th {
        color: #6b7189;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.8rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .users-table tbody tr {
        border-bottom: 1px solid #f0f2f8;
        transition: background 0.1s;
    }
    .users-table tbody tr:last-child { border-bottom: none; }
    .users-table tbody tr:hover { background: #f8f9fc; }
    .users-table td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border: none;
        color: #2d3250;
    }

    .user-name  { font-weight: 700; color: #2d3250; }
    .user-email { font-size: 0.8rem; color: #9ba3b8; }
    .user-phone { color: #6b7189; font-size: 0.82rem; }

    .type-super  { background: #fdecea; color: #d63031; border-radius: 6px; font-size: 0.72rem; padding: 0.22rem 0.6rem; font-weight: 600; white-space: nowrap; }
    .type-admin  { background: #eef2ff; color: #4361ee; border-radius: 6px; font-size: 0.72rem; padding: 0.22rem 0.6rem; font-weight: 600; }
    .type-staff  { background: #f4f6fb; color: #6b7189; border-radius: 6px; font-size: 0.72rem; padding: 0.22rem 0.6rem; font-weight: 600; }

    .branch-pill { background: #eef2ff; color: #4361ee; border-radius: 6px; font-size: 0.75rem; padding: 0.22rem 0.6rem; font-weight: 600; }

    .status-active { background: #e6f9f0; color: #1a9e5f; border-radius: 20px; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; }
    .status-banned { background: #fdecea; color: #d63031; border-radius: 20px; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; }

    .btn-edit-user {
        background: #eef2ff; color: #4361ee; border: none;
        border-radius: 7px; font-size: 0.78rem; font-weight: 600;
        padding: 0.35rem 0.8rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.3rem;
        transition: background 0.15s;
    }
    .btn-edit-user:hover { background: #dce4ff; color: #4361ee; }
    .btn-delete-user {
        background: #fdecea; color: #d63031; border: none;
        border-radius: 7px; font-size: 0.78rem; font-weight: 600;
        padding: 0.35rem 0.8rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.3rem;
        transition: background 0.15s;
    }
    .btn-delete-user:hover { background: #fbc8c8; color: #d63031; }

    .empty-state {
        text-align: center; padding: 3.5rem 2rem; color: #9ba3b8;
    }
    .empty-state i { font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 0.75rem; }
    .empty-state h6 { font-weight: 700; color: #6b7189; margin-bottom: 0.25rem; }
    .empty-state p  { font-size: 0.82rem; margin: 0; }
</style>

<div class="container-fluid px-4 mt-4">

    <?php alertMessage(); ?>

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-users me-2" style="opacity:0.75;"></i>Users</h2>
            <p>Manage admin accounts and staff members</p>
        </div>
        <a href="<?= $baseUrl ?>users/CreateUser.php" class="btn-add">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>

    <!-- FILTER -->
    <div class="filters-panel">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label text-muted small fw-semibold mb-1">Filter by Role</label>
                <select id="userTypeFilter" class="form-select">
                    <option value="all">All Users</option>
                    <option value="super_admin">Super Admins</option>
                    <option value="admin">Admins</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <?php
    $isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';
    $userBranchId = $_SESSION['loggedInUser']['branch_id'] ?? null;

    if ($isSuperAdmin) {
        $users = getAll('users');
    } else {
        $users = mysqli_query($conn, "SELECT * FROM users WHERE branch_id = '$userBranchId' ORDER BY id DESC");
    }

    if (!$users) {
        echo '<div class="alert alert-danger">Something went wrong loading users.</div>';
        include('../includes/footer.php');
        exit;
    }
    ?>

    <div class="users-table-wrap">
        <?php if (mysqli_num_rows($users) > 0): ?>
        <div class="table-responsive">
            <table class="table users-table" id="usersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $userItem):
                        $branchName = null;
                        if (!empty($userItem['branch_id'])) {
                            $branchData = getById('branches', $userItem['branch_id']);
                            if ($branchData && $branchData['status'] == 200) {
                                $branchName = $branchData['data']['branch_name'];
                            }
                        }
                    ?>
                    <tr data-usertype="<?= $userItem['user_type'] ?>">
                        <td style="color:#9ba3b8;"><?= $userItem['id'] ?></td>
                        <td>
                            <span class="user-name"><?= htmlspecialchars($userItem['name']) ?></span>
                            <span class="user-email d-block"><?= htmlspecialchars($userItem['email']) ?></span>
                        </td>
                        <td>
                            <?php if ($userItem['user_type'] == 'super_admin'): ?>
                                <span class="type-super">Super Admin</span>
                            <?php elseif ($userItem['user_type'] == 'admin'): ?>
                                <span class="type-admin">Admin</span>
                            <?php else: ?>
                                <span class="type-staff">Staff</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="user-phone"><?= htmlspecialchars($userItem['phone']) ?></span></td>
                        <td>
                            <?php if ($branchName): ?>
                                <span class="branch-pill"><?= htmlspecialchars($branchName) ?></span>
                            <?php else: ?>
                                <span style="color:#c5cade;font-size:0.8rem;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($userItem['is_ban'] == 1): ?>
                                <span class="status-banned"><i class="fas fa-ban me-1" style="font-size:0.65rem;"></i>Banned</span>
                            <?php else: ?>
                                <span class="status-active"><i class="fas fa-check me-1" style="font-size:0.65rem;"></i>Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= $baseUrl ?>users/EditUser.php?id=<?= $userItem['id'] ?>" class="btn-edit-user">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <a href="<?= $baseUrl ?>users/DeleteUser.php?id=<?= $userItem['id'] ?>"
                                   class="btn-delete-user"
                                   onclick="return confirm('Delete this user?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h6>No Users Found</h6>
            <p>Add your first user to get started.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.getElementById('userTypeFilter').addEventListener('change', function () {
    const filter = this.value;
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = (filter === 'all' || row.getAttribute('data-usertype') === filter) ? '' : 'none';
    });
});
</script>

<?php include('../includes/footer.php'); ?>
