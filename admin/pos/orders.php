<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">

        <!-- HEADER -->
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="mb-0">Orders</h4>
                </div>

                <div class="col-md-8">
                    <form action="" method="GET">
                        <div class="row g-1">

                            <!-- DATE -->
                            <div class="col-md-3">
                                <input type="date" 
                                    name="date"
                                    class="form-control" 
                                    value="<?= isset($_GET['date']) ? $_GET['date']:''; ?>" 
                                />
                            </div>

                            <!-- PAYMENT -->
                            <div class="col-md-3">
                                <select name="payment_status" class="form-select">
                                    <option value="">Select Payment Status</option>

                                    <option value="Cash Payment"
                                        <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'Cash Payment') ? 'selected':''; ?>>
                                        Cash Payment
                                    </option>

                                    <option value="Online Payment"
                                        <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == 'Online Payment') ? 'selected':''; ?>>
                                        Online Payment
                                    </option>
                                </select>
                            </div>

                            <!-- TRACKING SEARCH -->
                            <div class="col-md-3">
                                <input type="text" 
                                    name="tracking_no"
                                    class="form-control" 
                                    placeholder="Search Tracking No..."
                                    value="<?= isset($_GET['tracking_no']) ? $_GET['tracking_no'] : ''; ?>" 
                                />
                            </div>

                            <!-- BUTTONS -->
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="orders.php" class="btn btn-danger">Reset</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <?php

            // 🔥 CLEAN DYNAMIC QUERY
            $query = "SELECT o.*, c.* FROM orders o 
                      JOIN customers c ON c.id = o.customer_id 
                      WHERE 1=1";

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
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Payment Mode</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($orders as $orderItem): ?>
                    <tr>
                        <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                        <td><?= $orderItem['name']; ?></td>
                        <td><?= $orderItem['phone']; ?></td>
                        <td><?= date('d M, Y', strtotime($orderItem['order_date'])); ?></td>
                        <td><?= $orderItem['order_status']; ?></td>
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