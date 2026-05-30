<?php
require '../../config/function.php';
$branchId = $_SESSION['loggedInUser']['branch_id'];

$perPage     = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));

$where = "WHERE laundry_consumables.branch_id='$branchId' AND laundry_consumables.status=1";
if (isset($_GET['search']) && $_GET['search'] != '') {
    $search = validate($_GET['search']);
    $where .= " AND item_name LIKE '%$search%'";
}

$baseFrom    = "FROM laundry_consumables LEFT JOIN branches ON branches.id = laundry_consumables.branch_id $where";
$countRes    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt $baseFrom"));
$totalRows   = (int)($countRes['cnt'] ?? 0);
$totalPages  = max(1, ceil($totalRows / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset      = ($currentPage - 1) * $perPage;

$result = mysqli_query($conn, "SELECT laundry_consumables.*, branches.branch_name $baseFrom ORDER BY laundry_consumables.id ASC LIMIT $perPage OFFSET $offset");

if($result && mysqli_num_rows($result) > 0){
?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

<?php 
    $no = 1;
    foreach($result as $item): 

    if ($item['quantity'] <= 0) {
        $status = "<span class='badge bg-danger'>OUT</span>";
    } elseif ($item['quantity'] <= 10) {
        $status = "<span class='badge bg-warning text-dark'>LOW</span>";
    } else {
        $status = "<span class='badge bg-success'>OK</span>";
    }

?>

<tr>
    <td><?= $no++; ?></td>
    <td><?= $item['item_name']; ?></td>
    <td><?= $item['quantity']; ?></td>
    <td><?= $item['price']; ?></td>
    <td><?= $status ?></td>
    <td><?= $item['created_at']; ?></td>
    <td class="text-center">
        <div class="dropdown">

            <button 
                class="btn btn-sm btn-light border" 
                type="button" 
                data-bs-toggle="dropdown"
            >
                ⋯
            </button>

            <ul class="dropdown-menu dropdown-menu-end">

                <li>
                    <a 
                        href="#"
                        class="dropdown-item adjustStockBtn"
                        data-id="<?= $item['id']; ?>"
                        data-name="<?= $item['item_name']; ?>"
                        data-qty="<?= $item['quantity']; ?>"
                        data-branch="<?= $item['branch_id']; ?>"
                        data-branch-name="<?= $item['branch_name']; ?>"
                    >
                        Adjust Stock
                    </a>
                </li>

                <li>
                    <a
                        href="#"
                        class="dropdown-item editItemBtn"

                        data-id="<?= $item['id']; ?>"
                        data-name="<?= $item['item_name']; ?>"
                        data-price="<?= $item['price']; ?>"
                        data-branch="<?= $item['branch_name']; ?>"
                    >
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit
                    </a>
                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <a
                        href="#"
                        class="dropdown-item text-danger deleteItemBtn"

                        data-id="<?= $item['id']; ?>"
                    >
                        <i class="bi bi-trash me-2"></i>
                        Delete
                    </a>
                </li>

            </ul>

        </div>
    </td>
</tr>

<?php endforeach; ?>

    </tbody>
</table>

<?php if ($totalPages > 1):
    $pFrom  = $totalRows > 0 ? $offset + 1 : 0;
    $pTo    = min($offset + $perPage, $totalRows);
    $pStart = max(1, min($currentPage - 2, $totalPages - 4));
    $pEnd   = min($totalPages, $pStart + 4);
    $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
?>
<div class="pagination-wrap">
    <span class="pagination-info">Showing <?= $pFrom ?>–<?= $pTo ?> of <?= $totalRows ?></span>
    <div class="pagination-btns">
        <span onclick="loadInventoryTable(<?= $currentPage - 1 ?>, '<?= $search ?>')"
              class="page-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>">‹ Prev</span>
        <?php for ($pi = $pStart; $pi <= $pEnd; $pi++): ?>
            <span onclick="loadInventoryTable(<?= $pi ?>, '<?= $search ?>')"
                  class="page-btn <?= $pi == $currentPage ? 'active' : '' ?>"><?= $pi ?></span>
        <?php endfor; ?>
        <span onclick="loadInventoryTable(<?= $currentPage + 1 ?>, '<?= $search ?>')"
              class="page-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">Next ›</span>
    </div>
</div>
<?php endif; ?>

<?php } else {
    echo "<div style='padding:2.5rem;text-align:center;color:#9ba3b8;'><i class='fas fa-box-open' style='font-size:2rem;opacity:0.3;display:block;margin-bottom:0.5rem;'></i>No Inventory Found</div>";
}
?>