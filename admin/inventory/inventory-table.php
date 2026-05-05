<?php
require '../../config/function.php';
$query = "SELECT * FROM laundry_consumables WHERE 1=1";

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

<?php foreach($result as $item): 

    if ($item['quantity'] <= 0) {
        $status = "<span class='badge bg-danger'>OUT</span>";
    } elseif ($item['quantity'] <= 10) {
        $status = "<span class='badge bg-warning text-dark'>LOW</span>";
    } else {
        $status = "<span class='badge bg-success'>OK</span>";
    }

?>

<tr>
    <td><?= $item['id']; ?></td>
    <td><?= $item['item_name']; ?></td>
    <td><?= $item['quantity']; ?></td>
    <td><?= $item['price']; ?></td>
    <td><?= $status ?></td>
    <td><?= $item['created_at']; ?></td>
    <td>
        <a href="inventory-edit.php?id=<?= $item['id']; ?>" class="btn btn-outline-success btn-sm">Adjust Stock</a>
        <a href="inventory-edit.php?id=<?= $item['id']; ?>" class="btn btn-outline-primary btn-sm">Edit</a>
        <a href="inventory-delete.php?id=<?= $item['id']; ?>" class="btn btn-outline-danger btn-sm">Delete</a>
    </td>
</tr>

<?php endforeach; ?>

    </tbody>
</table>

<?php } else {
    echo "<h5>No Inventory Found</h5>";
}
?>