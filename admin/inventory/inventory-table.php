<?php
require '../../config/function.php';
$branchId = $_SESSION['loggedInUser']['branch_id'];
$query = "
SELECT 
    laundry_consumables.*,
    branches.branch_name
FROM laundry_consumables
LEFT JOIN branches 
    ON branches.id = laundry_consumables.branch_id
WHERE laundry_consumables.branch_id='$branchId'
";

if(isset($_GET['search']) && $_GET['search'] != ''){
    $search = validate($_GET['search']);
    $query .= " AND item_name LIKE '%$search%'";
}
$query .= " ORDER BY id ASC";

$result = mysqli_query($conn, $query);

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
                        href="inventory-edit.php?id=<?= $item['id']; ?>" 
                        class="dropdown-item"
                    >
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit
                    </a>
                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <a 
                        href="inventory-delete.php?id=<?= $item['id']; ?>" 
                        class="dropdown-item text-danger"
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

<?php } else {
    echo "<h5>No Inventory Found</h5>";
}
?>