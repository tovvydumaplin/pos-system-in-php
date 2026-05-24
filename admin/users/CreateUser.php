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
    .btn-back {
        background: #fff; color: #6b7189; border: 1px solid #dde1ec;
        border-radius: 9px; font-size: 0.85rem; font-weight: 600;
        padding: 0.55rem 1.25rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-back:hover { background: #f0f2f8; color: #2d3250; }

    .form-panel {
        background: #fff;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.5rem;
    }
    .form-panel .panel-title {
        font-size: 0.78rem;
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
    .form-control, .form-select {
        border-color: #dde1ec;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #2d3250;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2d3250;
        box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }
    .field-hint { font-size: 0.78rem; color: #9ba3b8; margin-top: 0.3rem; }

    .ban-toggle {
        display: flex; align-items: center; gap: 0.75rem;
        background: #f8f9fc; border: 1px solid #e6e9f0;
        border-radius: 10px; padding: 0.85rem 1.1rem;
    }
    .ban-toggle input[type="checkbox"] {
        width: 20px; height: 20px; accent-color: #d63031; cursor: pointer; flex-shrink: 0;
    }
    .ban-toggle .toggle-label { font-size: 0.85rem; font-weight: 600; color: #4a5072; margin: 0; }
    .ban-toggle .toggle-hint  { font-size: 0.78rem; color: #9ba3b8; margin: 0; }

    .form-actions {
        display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding-top: 0.5rem;
    }
    .btn-save {
        background: #2d3250; color: #fff; border: none;
        border-radius: 9px; font-size: 0.88rem; font-weight: 600;
        padding: 0.6rem 1.5rem;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.2s; cursor: pointer;
    }
    .btn-save:hover { background: #424870; }
    .btn-cancel {
        background: #fff; color: #6b7189; border: 1px solid #dde1ec;
        border-radius: 9px; font-size: 0.88rem; font-weight: 600;
        padding: 0.6rem 1.25rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: background 0.15s;
    }
    .btn-cancel:hover { background: #f0f2f8; color: #2d3250; }
</style>

<div class="container-fluid px-4 mt-4">

    <?php alertMessage(); ?>

    <!-- HERO -->
    <div class="page-hero">
        <div>
            <h2><i class="fas fa-user-plus me-2" style="opacity:0.75;"></i>Add User</h2>
            <p>Create a new admin or staff account</p>
        </div>
        <a href="<?= $baseUrl ?>users/users.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <form action="<?= $baseUrl ?>code.php" method="POST">

        <div class="form-panel">
            <div class="panel-title">Account Details</div>
            <div class="row g-3">

                <div class="col-md-12">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. Juan dela Cruz">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="user_type" required class="form-select">
                        <option value="">— Select Role —</option>
                        <?php if (isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin'): ?>
                        <option value="super_admin">Super Admin</option>
                        <?php endif; ?>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" required class="form-control" placeholder="e.g. juan@email.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" required class="form-control" placeholder="Enter password">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="number" name="phone" required class="form-control" placeholder="e.g. 09123456789">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">— No Branch —</option>
                        <?php
                        $branches = getAll('branches');
                        if ($branches && mysqli_num_rows($branches) > 0) {
                            foreach ($branches as $branch) {
                                echo '<option value="' . $branch['id'] . '">' . htmlspecialchars($branch['branch_name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <p class="field-hint">Assign this user to a specific branch</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label d-block">Ban Status</label>
                    <div class="ban-toggle">
                        <input type="checkbox" name="is_ban" id="banToggle">
                        <div>
                            <p class="toggle-label">Ban this user</p>
                            <p class="toggle-hint">Banned users cannot log in to the system</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <a href="<?= $baseUrl ?>users/users.php" class="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" name="saveUser" class="btn-save">
                <i class="fas fa-check"></i> Save User
            </button>
        </div>

    </form>
</div>

<?php include('../includes/footer.php'); ?>
