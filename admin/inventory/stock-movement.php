<?php include('../includes/header.php'); ?>

<?php
$branchId = $_SESSION['loggedInUser']['branch_id'];
$userType = $_SESSION['loggedInUser']['user_type'];
$isSuperAdmin = ($userType == 'super_admin');
?>

<div class="container-fluid px-4">

    <div class="card mt-4 shadow-sm">

        <!-- HEADER -->
        <div class="card-header">
            <div class="row align-items-center">

                <div class="col-md-4">
                    <h4 class="mb-0">Stock Movement</h4>
                </div>

                <div class="col-md-8">
                    <form method="GET">
                        <div class="row g-1">

                            <!-- SEARCH ITEM -->
                            <div class="col-md-4">
                                <input type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Search..."
                                    value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            </div>

                            <!-- MOVEMENT TYPE -->
                            <div class="col-md-3">
                                <select name="movement_type" class="form-select">
                                    <option value="">All Movement</option>

                                    <option value="IN"
                                        <?= (isset($_GET['movement_type']) && $_GET['movement_type']=='IN') ? 'selected':''; ?>>
                                        Stock In
                                    </option>

                                    <option value="OUT"
                                        <?= (isset($_GET['movement_type']) && $_GET['movement_type']=='OUT') ? 'selected':''; ?>>
                                        Stock Out
                                    </option>

                                    <option value="ADJUSTMENT"
                                        <?= (isset($_GET['movement_type']) && $_GET['movement_type']=='ADJUSTMENT') ? 'selected':''; ?>>
                                        Adjustment
                                    </option>
                                </select>
                            </div>

                            <?php if($isSuperAdmin): ?>
                            <div class="col-md-2">
                                <select name="branch_id" class="form-select">
                                    <option value="">All Branches</option>

                                    <?php
                                    $branches = mysqli_query(
                                        $conn,
                                        "SELECT * FROM branches ORDER BY branch_name ASC"
                                    );

                                    while($branch = mysqli_fetch_assoc($branches)):
                                    ?>

                                    <option
                                        value="<?= $branch['id']; ?>"
                                        <?= (isset($_GET['branch_id']) && $_GET['branch_id']==$branch['id']) ? 'selected':''; ?>
                                    >
                                        <?= $branch['branch_name']; ?>
                                    </option>

                                    <?php endwhile; ?>

                                </select>
                            </div>
                            <?php endif; ?>

                            <!-- BUTTON -->
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    Filter
                                </button>

                                <a href="stock-movement.php" class="btn btn-danger">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <?php alertMessage(); ?>

            <?php

            $query = "
                SELECT 
                    sm.*,
                    lc.item_name,
                    u.name as created_by_name,
                    b.branch_name

                FROM stock_movement sm

                LEFT JOIN laundry_consumables lc
                    ON lc.id = sm.consumable_id

                LEFT JOIN users u
                    ON u.id = sm.created_by

                LEFT JOIN branches b
                    ON b.id = sm.branch_id

                WHERE 1=1
            ";

            // branch restriction
            if(!$isSuperAdmin){
                $query .= " AND sm.branch_id='$branchId'";
            }

            // SEARCH FILTER
            if(isset($_GET['search']) && $_GET['search'] != '')
            {
                $search = validate($_GET['search']);

                $query .= "
                    AND (
                        lc.item_name LIKE '%$search%'
                        OR sm.reference_no LIKE '%$search%'
                    )
                ";
            }

            // MOVEMENT FILTER
            if(isset($_GET['movement_type']) && $_GET['movement_type'] != '')
            {
                $movementType = validate($_GET['movement_type']);

                $query .= " AND sm.movement_type='$movementType'";
            }
            // BRANCH FILTER
            if(
                $isSuperAdmin &&
                isset($_GET['branch_id']) &&
                $_GET['branch_id'] != ''
            )
            {
                $filterBranch = validate($_GET['branch_id']);

                $query .= " AND sm.branch_id='$filterBranch'";
            }
            $query .= " ORDER BY sm.id DESC";

            $result = mysqli_query($conn,$query);

            if($result && mysqli_num_rows($result) > 0)
            {
            ?>

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-items-center">

                    <thead>
                        <tr>

                            <th>#</th>

                            <th>Item</th>

                            <?php if($isSuperAdmin): ?>
                                <th>Branch</th>
                            <?php endif; ?>

                            <th>Movement</th>
                            <th>Qty</th>
                            <th>Reference</th>
                            <th>Remarks</th>
                            <th>Created By</th>
                            <th>Date</th>

                        </tr>
                    </thead>

                    <tbody>

                    <?php $no=1; ?>

                    <?php foreach($result as $row): ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td>
                                <?= $row['item_name']; ?>
                            </td>

                            <?php if($isSuperAdmin): ?>
                            <td>
                                <span class="badge bg-primary">
                                    <?= $row['branch_name'] ?? '-'; ?>
                                </span>
                            </td>
                            <?php endif; ?>

                            <td>

                                <?php if($row['movement_type']=='IN'): ?>

                                    <span class="badge bg-success">
                                        STOCK IN
                                    </span>

                                <?php elseif($row['movement_type']=='OUT'): ?>

                                    <span class="badge bg-danger">
                                        STOCK OUT
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-warning text-dark">
                                        ADJUSTMENT
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td><?= $row['quantity']; ?></td>

                            <td><?= $row['reference_no']; ?></td>

                            <td><?= $row['remarks']; ?></td>

                            <td><?= $row['created_by_name']; ?></td>

                            <td>
                                <?= date('d M Y h:i A', strtotime($row['created_at'])); ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <?php
            }
            else
            {
                echo "<h5>No Stock Movement Found</h5>";
            }
            ?>

        </div>

    </div>

</div>

<?php include('../includes/footer.php'); ?>