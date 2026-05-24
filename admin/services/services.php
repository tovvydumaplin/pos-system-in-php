<?php include('../includes/header.php'); ?>

<style>
    .services-hero {
        background: #f8f9fc;
        border: 1px solid #e6e9f0;
        border-radius: 14px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .services-hero h2 {
        color: #2d3250;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
    }
    .services-hero p {
        color: #9ba3b8;
        margin: 0.2rem 0 0;
        font-size: 0.85rem;
    }
    .services-hero .btn-add {
        background: #2d3250;
        border: none;
        color: #fff;
        padding: 0.55rem 1.25rem;
        border-radius: 9px;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
    }
    .services-hero .btn-add:hover {
        background: #424870;
        transform: translateY(-1px);
        color: #fff;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .service-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.2s, transform 0.2s;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .service-card:hover {
        box-shadow: 0 8px 28px rgba(0,0,0,0.13);
        transform: translateY(-3px);
    }

    .service-card-img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        background: #f1f3f6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .service-card-img img {
        width: 100%;
        height: 170px;
        object-fit: cover;
    }
    .service-card-img .no-img {
        width: 100%;
        height: 170px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e8eaf0, #d1d5e0);
        color: #9ba3b8;
        font-size: 2.8rem;
    }

    .service-card-body {
        padding: 1.1rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .service-name {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1a1a2e;
        margin: 0;
        line-height: 1.3;
    }

    .service-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .service-price {
        font-size: 1.15rem;
        font-weight: 700;
        color: #e94560;
    }

    .service-branch {
        font-size: 0.78rem;
        color: #7a7f9a;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .service-items-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
        min-height: 1.5rem;
    }
    .service-items-wrap .badge-item {
        background: #eef2ff;
        color: #4361ee;
        border-radius: 6px;
        font-size: 0.72rem;
        padding: 0.22rem 0.55rem;
        font-weight: 600;
    }
    .service-items-wrap .no-items {
        font-size: 0.78rem;
        color: #b0b5c8;
        font-style: italic;
    }

    .service-status {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
    }
    .service-status.visible {
        background: #e6f9f0;
        color: #1a9e5f;
    }
    .service-status.hidden {
        background: #fdecea;
        color: #d63031;
    }

    .service-card-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid #f0f1f5;
        display: flex;
        gap: 0.6rem;
    }
    .service-card-footer .btn-edit {
        flex: 1;
        background: #f4f6ff;
        color: #4361ee;
        border: 1px solid #d6dcff;
        border-radius: 8px;
        padding: 0.45rem 0;
        font-size: 0.82rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
    }
    .service-card-footer .btn-edit:hover {
        background: #e2e8ff;
        color: #4361ee;
    }
    .service-card-footer .btn-delete {
        flex: 1;
        background: #fff5f5;
        color: #e53e3e;
        border: 1px solid #ffc5c5;
        border-radius: 8px;
        padding: 0.45rem 0;
        font-size: 0.82rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
    }
    .service-card-footer .btn-delete:hover {
        background: #ffe0e0;
        color: #e53e3e;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ba3b8;
    }
    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        opacity: 0.4;
    }
    .empty-state h5 {
        font-weight: 600;
        color: #6b7189;
        margin-bottom: 0.4rem;
    }
    .empty-state p {
        font-size: 0.88rem;
    }

    .service-id-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0,0,0,0.5);
        color: #fff;
        font-size: 0.7rem;
        padding: 0.18rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
    }
    .service-card-img-wrap {
        position: relative;
    }
    .service-status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

<div class="container-fluid px-4 mt-4">

    <?php alertMessage(); ?>

    <div class="services-hero">
        <div>
            <h2><i class="fas fa-concierge-bell me-2" style="opacity:0.85;"></i>Services</h2>
            <p>Manage your laundry service catalog</p>
        </div>
        <a href="services-create.php" class="btn-add">
            <i class="fas fa-plus"></i> Add Service
        </a>
    </div>

    <?php
    $isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';
    $userBranchId = $_SESSION['loggedInUser']['branch_id'] ?? null;

    if ($isSuperAdmin) {
        $services = mysqli_query($conn, "
            SELECT s.*,
                b.branch_name,
                GROUP_CONCAT(
                    CONCAT(lc.item_name, ' x', si.quantity_required)
                    ORDER BY lc.item_name ASC
                    SEPARATOR '||'
                ) AS items_list
            FROM services s
            LEFT JOIN branches b ON b.id = s.branch_id
            LEFT JOIN service_items si ON si.service_id = s.id
            LEFT JOIN laundry_consumables lc ON lc.id = si.consumable_id
            GROUP BY s.id
            ORDER BY s.id DESC
        ");
    } else {
        $services = mysqli_query($conn, "
            SELECT s.*,
                b.branch_name,
                GROUP_CONCAT(
                    CONCAT(lc.item_name, ' x', si.quantity_required)
                    ORDER BY lc.item_name ASC
                    SEPARATOR '||'
                ) AS items_list
            FROM services s
            LEFT JOIN branches b ON b.id = s.branch_id
            LEFT JOIN service_items si ON si.service_id = s.id
            LEFT JOIN laundry_consumables lc ON lc.id = si.consumable_id
            WHERE s.branch_id = '$userBranchId'
            GROUP BY s.id
            ORDER BY s.id DESC
        ");
    }

    if (!$services) {
        echo '<div class="alert alert-danger">Something went wrong loading services.</div>';
        include('../includes/footer.php');
        exit;
    }
    ?>

    <?php if (mysqli_num_rows($services) > 0): ?>
    <div class="services-grid">
        <?php foreach ($services as $item): ?>
        <div class="service-card">
            <div class="service-card-img-wrap">
                <?php if (!empty($item['image'])): ?>
                    <img class="service-card-img" src="../../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <?php else: ?>
                    <div class="service-card-img no-img">
                        <i class="fas fa-tshirt"></i>
                    </div>
                <?php endif; ?>
                <span class="service-id-badge">#<?= $item['id'] ?></span>
                <span class="service-status-badge">
                    <?php if ($item['status'] == 1): ?>
                        <span class="service-status hidden"><i class="fas fa-eye-slash"></i> Hidden</span>
                    <?php else: ?>
                        <span class="service-status visible"><i class="fas fa-eye"></i> Visible</span>
                    <?php endif; ?>
                </span>
            </div>

            <div class="service-card-body">
                <p class="service-name"><?= htmlspecialchars($item['name']) ?></p>

                <div class="service-meta">
                    <span class="service-price">₱<?= number_format($item['price'], 2) ?></span>
                    <?php if (!empty($item['branch_name'])): ?>
                        <span class="service-branch">
                            <i class="fas fa-code-branch"></i> <?= htmlspecialchars($item['branch_name']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="service-items-wrap">
                    <?php if (!empty($item['items_list'])): ?>
                        <?php foreach (explode('||', $item['items_list']) as $itm): ?>
                            <span class="badge-item"><?= htmlspecialchars($itm) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="no-items">No consumables defined</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="service-card-footer">
                <a href="services-edit.php?id=<?= $item['id'] ?>" class="btn-edit">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <a href="services-delete.php?id=<?= $item['id'] ?>"
                   class="btn-delete"
                   onclick="return confirm('Delete this service?')">
                    <i class="fas fa-trash-alt"></i> Delete
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-concierge-bell d-block"></i>
        <h5>No Services Yet</h5>
        <p>Get started by adding your first service.</p>
        <a href="services-create.php" class="btn btn-primary mt-2">
            <i class="fas fa-plus me-1"></i> Add Service
        </a>
    </div>
    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>
