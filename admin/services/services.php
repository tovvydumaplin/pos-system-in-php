<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Services
                <a href="services-create.php" class="btn btn-primary float-end">Add Service</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <?php
            // Filter services by branch unless super_admin
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
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if (mysqli_num_rows($services) > 0):
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Branch</th>
                            <th>Required Items</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="../../<?= $item['image']; ?>"
                                         style="width:48px;height:48px;object-fit:cover;border-radius:6px;"
                                         alt="<?= $item['name']; ?>" />
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-semibold"><?= $item['name'] ?></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['branch_name'] ?? '<span class="text-muted">—</span>' ?></td>
                            <td>
                                <?php if (!empty($item['items_list'])): ?>
                                    <?php foreach (explode('||', $item['items_list']) as $itm): ?>
                                        <span class="badge bg-info text-dark me-1 mb-1"><?= htmlspecialchars($itm) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted small">No items defined</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['status'] == 1): ?>
                                    <span class="badge bg-danger">Hidden</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Visible</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="services-edit.php?id=<?= $item['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="services-delete.php?id=<?= $item['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this service?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h4>No Record Found</h4>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
