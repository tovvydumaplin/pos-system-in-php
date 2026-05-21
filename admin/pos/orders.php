<?php include('../includes/header.php'); ?>
<?php 
$branchId = $_SESSION['loggedInUser']['branch_id']; 
$userType = $_SESSION['loggedInUser']['user_type'];
$isSuperAdmin = ($userType == 'super_admin');
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            
            <!-- TITLE ROW -->
            <div class="row align-items-center mb-3">
                <div class="col">
                    <h4 class="mb-0">List of Orders</h4>
                    <?php if ($isSuperAdmin): ?>
                        <small class="text-muted">All Branches</small>
                        <span class="badge bg-danger ms-2">Super Admin View</span>
                    <?php else: ?>
                        <small class="text-muted">
                            <i class="fas fa-building me-1"></i>
                            <?= htmlspecialchars($_SESSION['loggedInUser']['branch_name'] ?? 'Unassigned Branch'); ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FILTERS ROW -->
            <form action="" method="GET">
                <div class="row g-2">
                    
                    <?php if($isSuperAdmin): ?>
                    <div class="col-md-3">
                        <select name="branch_filter" class="form-select">
                            <option value="">All Branches</option>
                            <?php
                            $branches = getAll('branches');
                            if($branches && mysqli_num_rows($branches) > 0){
                                foreach($branches as $branch){
                                    $selected = (isset($_GET['branch_filter']) && $_GET['branch_filter'] == $branch['id']) ? 'selected' : '';
                                    echo '<option value="'.$branch['id'].'" '.$selected.'>'.$branch['branch_name'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="col-md">
                        <input type="date" 
                            name="date"
                            class="form-control" 
                            value="<?= isset($_GET['date']) ? $_GET['date']:''; ?>" 
                            placeholder="Filter by Date"
                        />
                    </div>

                    <div class="col-md">
                        <select name="payment_status" class="form-select">
                            <option value="">All Payment Status</option>
                            <option value="Cash Payment" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'Cash Payment') ? 'selected':''; ?>>
                                Cash Payment
                            </option>
                            <option value="Online Payment" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'Online Payment') ? 'selected':''; ?>>
                                Online Payment
                            </option>
                        </select>
                    </div>

                    <div class="col-md">
                        <input type="text" 
                            name="tracking_no"
                            class="form-control" 
                            placeholder="Search Tracking No..."
                            value="<?= isset($_GET['tracking_no']) ? $_GET['tracking_no'] : ''; ?>" 
                        />
                    </div>

                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="orders.php" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>

                </div>
            </form>

        </div>

        <!-- BODY -->
        <div class="card-body">

            <?php

            $query = "SELECT o.*, c.* 
                    FROM orders o
                    JOIN customers c ON c.id = o.customer_id
                    WHERE 1=1";

            // Super Admin: Show all orders or filter by selected branch
            if($isSuperAdmin){
                if(isset($_GET['branch_filter']) && $_GET['branch_filter'] != ''){
                    $selectedBranch = validate($_GET['branch_filter']);
                    $query .= " AND o.branch_id='$selectedBranch'";
                }
                // If no branch filter, show ALL orders (no branch restriction)
            } else {
                // Regular admin/staff: Filter by their assigned branch
                $query .= " AND o.branch_id='$branchId'";
            }

            if(isset($_GET['date']) && $_GET['date'] != ''){
                $date = validate($_GET['date']);
                $query .= " AND o.order_date='$date'";
            }

            if(isset($_GET['payment_status']) && $_GET['payment_status'] != ''){
                $payment = validate($_GET['payment_status']);
                $query .= " AND o.payment_mode='$payment'";
            }

            if(isset($_GET['tracking_no']) && $_GET['tracking_no'] != ''){
                $track = validate($_GET['tracking_no']);
                $query .= " AND o.tracking_no LIKE '%$track%'";
            }

            $query .= " ORDER BY o.id DESC";

            $orders = mysqli_query($conn, $query);

            if($orders && mysqli_num_rows($orders) > 0)
            {
            ?>

            <table class="table table-striped table-bordered align-items-center justify-content-center">
                <thead>
                    <tr>
                        <th>Tracking No.</th>
                        <th>Name</th>
                        <th>Phone No.</th>
                        <?php if($isSuperAdmin): ?>
                        <th>Branch</th>
                        <?php endif; ?>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Payment Mode</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($orders as $orderItem): 
                        // Get branch name for super admin view
                        $branchName = '-';
                        if($isSuperAdmin && isset($orderItem['branch_id']) && !empty($orderItem['branch_id'])){
                            $branchData = getById('branches', $orderItem['branch_id']);
                            if($branchData && $branchData['status'] == 200){
                                $branchName = $branchData['data']['branch_name'];
                            }
                        }
                    ?>
                    <tr>
                        <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                        <td><?= $orderItem['name']; ?></td>
                        <td><?= $orderItem['phone']; ?></td>
                        <?php if($isSuperAdmin): ?>
                        <td>
                            <span class="badge bg-primary"><?= $branchName; ?></span>
                        </td>
                        <?php endif; ?>
                        <td><?= date('d M, Y', strtotime($orderItem['order_date'])); ?></td>
                        <?php
                        $status = strtolower($orderItem['order_status']);

                        if($status == 'cancelled'){
                            $statusClass = 'bg-danger-subtle text-danger';
                        }
                        elseif($status == 'booked'){
                            $statusClass = 'bg-info-subtle text-info';
                        }
                        elseif($status == 'released'){
                            $statusClass = 'bg-success-subtle text-success';
                        }
                        else{
                            $statusClass = 'bg-secondary-subtle text-secondary';
                        }
                        ?>

                        <td>
                            <span class="badge <?= $statusClass ?> px-3 py-2">
                                <?= ucfirst($orderItem['order_status']); ?>
                            </span>
                        </td>
                        <td><?= $orderItem['payment_mode']; ?></td>
                        <td>
                            <a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>" 
                               class="btn btn-info btn-sm">View</a>

                            <a href="orders-view-print.php?track=<?= $orderItem['tracking_no']; ?>" 
                               class="btn btn-primary btn-sm">Print</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            }
            else
            {
                echo "<h5>No Record Available</h5>";
            }
            ?>

        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>