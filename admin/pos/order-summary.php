<?php 
include('../includes/header.php'); 
if(!isset($_SESSION['orderItems'])){
    echo '<script> window.location.href = "order-create.php"; </script>';
}
?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Order Summary
                        <a href="order-create.php" class="btn btn-danger float-end">Back to create order</a>
                    </h4>
                </div>
                <div class="card-body">

                    <?php alertMessage() ?>

                    <div id="myBillingArea">

                        <?php
                        if(isset($_SESSION['cphone']))
                        {
                            $phone = validate($_SESSION['cphone']);
                            $invoiceNo = validate($_SESSION['invoice_no']);
                            
                            $customerQuery = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
                            if($customerQuery){
                                if(mysqli_num_rows($customerQuery) > 0){

                                    $cRowData = mysqli_fetch_assoc($customerQuery);
                                    ?>
                                    <table style="width: 100%; margin-bottom: 20px;">
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;" colspan="2">
                                                    <h4 style="font-size: 23px; line-height: 30px; margin:2px; padding: 0;">TipidSulit Laundromat</h4>
                                                    <p style="font-size: 16px; line-height: 24px; margin:2px; padding: 0;">70 G. Marcelo, Maysan, Valenzuela City, Metro Manila, 1440</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Customer Details</h5>
                                                    <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Name: <?= $cRowData['name'] ?> </p>
                                                    <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Phone No.: <?= $cRowData['phone'] ?> </p>
                                                    <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Email Id: <?= $cRowData['email'] ?> </p>
                                                </td>
                                                <td align="end">
                                                    <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;">Invoice Details</h5>
                                                    <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice No: <?= $invoiceNo; ?> </p>
                                                    <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice Date: <?= date('d M Y'); ?> </p>
                                                    <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Address: 70 G. Marcelo, Maysan, Valenzuela City, Metro Manila, 1440 </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php
                                }else{
                                    echo "<h5>No Customer Found</h5>";
                                    return;
                                }
                            }

                        }
                        ?>

                            <?php
                            if(isset($_SESSION['orderItems']))
                            {
                                $sessionItems = $_SESSION['orderItems'];
                            ?>
                            <div class="table-responsive mb-3">
                                <table style="width:100%;" cellpadding="5">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            $totalAmount = 0;

                                            foreach($sessionItems as $key => $row):

                                            $total = $row['price'] * $row['quantity'];
                                            $totalAmount += $total;
                                        ?>
                                        <tr>
                                            <td><?= $i++; ?></td>

                                            <td>
                                                <?php if($row['type'] == 'service'): ?>
                                                    Service
                                                <?php else: ?>
                                                    Item
                                                <?php endif; ?>
                                            </td>

                                            <td><?= $row['name']; ?></td>
                                            <td><?= number_format($row['price'],0); ?></td>
                                            <td><?= $row['quantity']; ?></td>
                                            <td><?= number_format($total,0); ?></td>
                                        </tr>
                                        <?php endforeach; ?>

                                        <tr>
                                            <td colspan="5" align="end"><strong>Grand Total:</strong></td>
                                            <td><strong><?= number_format($totalAmount,0); ?></strong></td>
                                        </tr>

                                        <tr>
                                            <td colspan="6">Payment Mode: <?= $_SESSION['payment_mode']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            }
                            ?>
                        
                    </div>

                    <?php if(isset($_SESSION['orderItems'])) : ?>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-primary px-4 mx-1" id="saveOrder">Save</button>
                        <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()">Print</button>
                        <button class="btn btn-warning px-4 mx-1" onclick="downloadPDF('<?= $_SESSION['invoice_no']; ?>')">
                            Download PDF
                        </button>
                    </div>
                    <div class="modal fade" id="orderSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-body">
                                
                                <div class="mb-3 p-4">
                                    <h5 id="orderPlaceSuccessMessage"></h5>
                                </div>
                                <div class="text-center">
                                    <a href="orders.php" class="btn btn-secondary">Close</a>
                                    <button type="button" onclick="printMyBillingArea()" class="btn btn-danger">Print</button>
                                    <button type="button" onclick="downloadPDF('<?= $_SESSION['invoice_no']; ?>')" class="btn btn-warning">Download PDF</button>
                                </div>

                            <div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

