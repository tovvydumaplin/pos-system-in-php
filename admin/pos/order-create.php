<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Order
                <a href="#" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>

        <div class="card-body">

            <?php alertMessage(); ?>

            <!-- ADD SERVICE FORM -->
            <form action="orders-code.php" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <!-- SERVICE -->
                            <div class="col-md-6 mb-3">
                                <label>Select Service</label>
                                <select name="service_id" class="form-select mySelect2">
                                    <option value="">-- Select Service --</option>
                                    <?php
                                        $services = getAll('services');
                                        if($services && mysqli_num_rows($services) > 0){
                                            foreach($services as $service){
                                    ?>
                                        <option value="<?= $service['id']; ?>">
                                            <?= $service['name']; ?>
                                        </option>
                                    <?php } } ?>
                                </select>
                            </div>

                            <!-- QTY -->
                            <div class="col-md-2 mb-3">
                                <label>Quantity</label>
                                <input type="number" name="service_qty" value="1" class="form-control" />
                            </div>

                            <!-- BUTTON -->
                            <div class="col-md-3 mb-3">
                                <label>&nbsp;</label>
                                <button type="submit" name="addService" class="btn btn-primary w-100">
                                    Add Service
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <!-- CONSUMABLE -->
                            <div class="col-md-6 mb-3">
                                <label>Select Item</label>
                                <select name="consumable_id" class="form-select mySelect2">
                                    <option value="">-- Select Item --</option>

                                    <?php
                                        $items = getAll('laundry_consumables');
                                        if($items && mysqli_num_rows($items) > 0){
                                            foreach($items as $item){
                                                $disabled = $item['quantity'] <= 0 ? 'disabled' : '';
                                    ?>
                                        <option value="<?= $item['id']; ?>" <?= $disabled; ?>>
                                            <?= $item['item_name']; ?> 
                                            (<?= $item['quantity']; ?>)
                                        </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>

                            <!-- ITEM QTY -->
                            <div class="col-md-2 mb-3">
                                <label>Qty</label>
                                <input type="number" name="item_qty" value="1" class="form-control" />
                            </div>

                            <!-- ADD ITEM BUTTON -->
                            <div class="col-md-3 mb-3">
                                <label>&nbsp;</label>
                                <button type="submit" name="addConsumable" class="btn btn-success w-100">
                                    Add Item
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- TABLE -->
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="mb-0">Order Items</h4>
        </div>

        <div class="card-body">

            <?php if(!empty($_SESSION['orderItems'])): ?>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                $i = 1;
                                $grandTotal = 0;

                                foreach($_SESSION['orderItems'] as $key => $item): 

                                $total = $item['price'] * $item['quantity'];
                                $grandTotal += $total;
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>

                                    <td>
                                        <?php if($item['type'] == 'service'): ?>
                                            <span class="badge bg-primary">Service</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Item</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= $item['name']; ?></td>
                                    <td><?= $item['quantity']; ?></td>
                                    <td><?= number_format($item['price'], 2); ?></td>
                                    <td><?= number_format($total, 2); ?></td>

                                    <td>
                                        <a href="order-item-delete.php?index=<?= $key; ?>" 
                                           class="btn btn-danger btn-sm">
                                           Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- GRAND TOTAL -->
                <div class="text-end">
                    <h4>Total: ₱<?= number_format($grandTotal, 2); ?></h4>
                </div>

                <!-- PAYMENT + CUSTOMER -->
                <div class="mt-3">
                    <hr>
                    <form id="proceedForm">
                        <div class="row">

                            <div class="col-md-3">
                                <label>Payment Mode</label>
                                <select id="payment_mode" class="form-select">
                                    <option value="">-- Select Payment --</option>
                                    <option value="Cash Payment">Cash Payment</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Select Customer</label>
                                <select id="cphone" class="form-select mySelect2">
                                    <option value="">-- Select Customer --</option>

                                    <?php
                                        $customers = getAll('customers');
                                        if($customers && mysqli_num_rows($customers) > 0){
                                            foreach($customers as $cust){
                                    ?>
                                        <option value="<?= $cust['phone']; ?>">
                                            <?= $cust['phone']; ?> - <?= $cust['name']; ?>
                                        </option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" 
                                        class="btn btn-outline-success w-100"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#addCustomerModal">
                                    + New Customer
                                </button>
                            </div>
                            <div class="col-md-3">
                                <br/>
                                <button type="button" class="btn btn-warning w-100 proceedToPlace">
                                    Proceed to place order
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            <?php else: ?>

                <h5>No Items addead</h5>

            <?php endif; ?>

        </div>
    </div>

</div>

<!-- ADD CUSTOMER MODAL -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

        <form action="orders-code.php" method="POST">

            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" id="c_name" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="number" id="c_phone" name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email (optional)</label>
                    <input type="email" id="c_email" name="email" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" name="saveCustomer" class="btn btn-primary saveCustomer">
                    Save Customer
                </button>
            </div>

        </form>

    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
