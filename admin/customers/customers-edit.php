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
        font-size: 0.82rem; font-weight: 600; color: #4a5072; margin-bottom: 0.35rem;
    }
    .form-control {
        border-color: #dde1ec; border-radius: 8px; font-size: 0.875rem; color: #2d3250;
    }
    .form-control:focus {
        border-color: #2d3250; box-shadow: 0 0 0 0.2rem rgba(45,50,80,0.1);
    }

    .visibility-toggle {
        display: flex; align-items: center; gap: 0.75rem;
        background: #f8f9fc; border: 1px solid #e6e9f0;
        border-radius: 10px; padding: 0.85rem 1.1rem;
    }
    .visibility-toggle input[type="checkbox"] {
        width: 20px; height: 20px; accent-color: #2d3250; cursor: pointer; flex-shrink: 0;
    }
    .visibility-toggle .toggle-label { font-size: 0.85rem; font-weight: 600; color: #4a5072; margin: 0; }
    .visibility-toggle .toggle-hint  { font-size: 0.78rem; color: #9ba3b8; margin: 0; }

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
            <h2><i class="fas fa-user-edit me-2" style="opacity:0.75;"></i>Edit Customer</h2>
            <p>Update customer details</p>
        </div>
        <a href="customers.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Customers
        </a>
    </div>

    <form action="../code.php" method="POST">

        <?php
        $paramValue = checkParamId('id');
        if (!is_numeric($paramValue)) {
            echo '<div class="alert alert-danger">' . $paramValue . '</div>';
            include('../includes/footer.php');
            exit;
        }

        $customer = getById('customers', $paramValue);
        if ($customer['status'] != 200) {
            echo '<div class="alert alert-danger">' . $customer['message'] . '</div>';
            include('../includes/footer.php');
            exit;
        }
        $c = $customer['data'];
        ?>

        <input type="hidden" name="customerId" value="<?= $c['id'] ?>">

        <div class="form-panel">
            <div class="panel-title">Customer Details</div>
            <div class="row g-3">

                <div class="col-md-12">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required class="form-control" value="<?= htmlspecialchars($c['name']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($c['email']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="number" name="phone" class="form-control" value="<?= htmlspecialchars($c['phone']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label d-block">Visibility</label>
                    <div class="visibility-toggle">
                        <input type="checkbox" name="status" id="statusToggle" <?= $c['status'] == 1 ? 'checked' : '' ?>>
                        <div>
                            <p class="toggle-label">Hide this customer</p>
                            <p class="toggle-hint">When checked, this customer won't appear in selections</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <a href="customers.php" class="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" name="updateCustomer" class="btn-save">
                <i class="fas fa-check"></i> Update Customer
            </button>
        </div>

    </form>

</div>

<?php include('../includes/footer.php'); ?>
