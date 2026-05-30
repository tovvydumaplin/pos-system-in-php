<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Print Order
                <a href="orders.php" class="btn btn-success btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <div id="myBillingArea">
                <?php
                    if(isset($_GET['track']))
                    {
                        $trackingNo = validate($_GET['track']);
                        if($trackingNo == ''){
                            ?>
                            <div class="text-center py-5">
                                <h5>Please provide Tracking Number</h5>
                                <div>
                                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
                                </div>
                            </div>
                            <?php
                        }

                        $branchId = $_SESSION['loggedInUser']['branch_id'];

                        $orderQuery = "
                        SELECT 
                            o.*, 
                            c.*,
                            b.branch_name,
                            b.address as branch_address
                        FROM orders o
                        JOIN customers c 
                            ON c.id=o.customer_id
                        LEFT JOIN branches b
                            ON b.id=o.branch_id
                        WHERE o.tracking_no='$trackingNo'
                        AND o.branch_id='$branchId'
                        LIMIT 1
                        ";
                        $orderQueryRes = mysqli_query($conn, $orderQuery);
                        
                        if(!$orderQueryRes){
                            echo "<h5>Something Went Wrong</h5>";
                            return false;
                        }

                        if(mysqli_num_rows($orderQueryRes) > 0)
                        {
                            $orderDataRow = mysqli_fetch_assoc($orderQueryRes);
                            // print_r($orderDataRow);
                            ?>
                            <table style="width: 100%; margin-bottom: 20px;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;" colspan="2">
                                            <h4 style="font-size: 23px; line-height: 30px; margin:2px; padding: 0;">TipidSulit Laundromat</h4>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">
                                             <?= $orderDataRow['branch_address']; ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Customer Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Name: <?= $orderDataRow['name'] ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Phone No.: <?= $orderDataRow['phone'] ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Email Id: <?= $orderDataRow['email'] ?> </p>
                                        </td>
                                        <td align="end">
                                            <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Invoice Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice No: <?= $orderDataRow['invoice_no']; ?> </p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice Date: <?= date('d M Y'); ?> </p>
                                            <?php if (!empty($orderDataRow['branch_address'])): ?>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Address: <?= htmlspecialchars($orderDataRow['branch_address']) ?></p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                        }
                        else
                        {
                            echo "<h5>No data found</h5>";
                            return false;
                        }

                        $orderItemQuery = "
                        SELECT
                            oi.quantity as orderItemQuantity,
                            oi.price as orderItemPrice,

                            o.total_amount,
                            o.payment_mode,

                            s.name as service_name,
                            lc.item_name as consumable_name,

                            oi.service_id,
                            oi.consumable_id

                        FROM orders o

                        JOIN order_items oi
                            ON oi.order_id = o.id

                        LEFT JOIN services s
                            ON s.id = oi.service_id

                        LEFT JOIN laundry_consumables lc
                            ON lc.id = oi.consumable_id

                        WHERE o.tracking_no='$trackingNo'
                        ORDER BY oi.service_id, oi.id
                        ";

                        $orderItemQueryRes = mysqli_query($conn, $orderItemQuery);
                        if($orderItemQueryRes)
                        {
                            if(mysqli_num_rows($orderItemQueryRes) > 0)
                            {
                                ?>
                                <div class="table-responsive mb-3">
                                    <table style="width:100%;" cellpadding="5">
                                        <thead>
                                            <tr>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="5%">ID</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;">Service Name</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="15%">Total Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $i = 1;
                                                    foreach($orderItemQueryRes as $key => $row) :
                                                        // Determine if this is a service, consumable from service, or standalone consumable
                                                        $isService = !empty($row['service_id']) && empty($row['consumable_id']);
                                                        $isServiceConsumable = !empty($row['service_id']) && !empty($row['consumable_id']);
                                                        $isStandaloneConsumable = empty($row['service_id']) && !empty($row['consumable_id']);
                                                        
                                                        $bgColor = $isServiceConsumable ? '#f8f9fa' : '#ffffff';
                                                ?>
                                                <tr style="background-color: <?= $bgColor ?>;">
                                                    <td style="border-bottom: 1px solid #ccc;">
                                                        <?php if (!$isServiceConsumable) echo $i++; ?>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ccc;">
                                                        <?php if ($isServiceConsumable): ?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;→ <small><?= $row['consumable_name']; ?></small>
                                                        <?php else: ?>
                                                            <?= $row['service_name'] ?? $row['consumable_name']; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ccc;">
                                                        <?php if ($isServiceConsumable): ?>
                                                            <small><?= number_format($row['orderItemPrice'],0) ?></small>
                                                        <?php else: ?>
                                                            <?= number_format($row['orderItemPrice'],0) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ccc;">
                                                        <?php if ($isServiceConsumable): ?>
                                                            <small><?= $row['orderItemQuantity'] ?></small>
                                                        <?php else: ?>
                                                            <?= $row['orderItemQuantity'] ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="border-bottom: 1px solid #ccc;">
                                                        <?php if ($isServiceConsumable): ?>
                                                            <small><?= number_format($row['orderItemPrice'] * $row['orderItemQuantity'], 0) ?></small>
                                                        <?php else: ?>
                                                            <?= number_format($row['orderItemPrice'] * $row['orderItemQuantity'], 0) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                
                                                <tr>
                                                    <td colspan="4" align="end" style="font-weight: bold;">Grand Total: </td>
                                                    <td colspan="1" style="font-weight: bold;"><?= number_format($row['total_amount'], 0); ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">Payment Mode: <?=$row['payment_mode'];?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php
                            }
                            else
                            {
                                echo "<h5>No data found</h5>";
                                return false;
                            }
                        }
                        else
                        {
                            echo "<h5>Something Went Wrong!</h5>";
                            return false;
                        }
                    }
                    else
                    {
                        ?>
                        <div class="text-center py-5">
                            <h5>No Tracking Number Parameter Found</h5>
                            <div>
                                <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>

            <div class="mt-4 text-end">
                <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()">
                    Print
                </button>

                <button class="btn btn-primary px-4 mx-1"
                        onclick="downloadPDF('<?= $orderDataRow['invoice_no']; ?>')">
                    Download PDF
                </button>

            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
