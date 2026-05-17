<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Customer
                <a href="customers.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="../code.php" method="POST">

                <?php
                    $paramValue = checkParamId('id');
                    if(!is_numeric($paramValue)){
                        echo '<h5>'.$paramValue.'</h5>';
                        return false;
                    }

                    $customer = getById('customers', $paramValue);
                    if($customer['status'] == 200)
                    {
                        ?>

                        <input type="hidden" name="customerId" value="<?= $customer['data']['id']; ?>" />

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">Name *</label>
                                <input type="text" name="name" required value="<?= $customer['data']['name']; ?>" class="form-control" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="">Email Id</label>
                                <input type="email" name="email" value="<?= $customer['data']['email']; ?>" class="form-control" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="">Phone </label>
                                <input type="number" name="phone" value="<?= $customer['data']['phone']; ?>" class="form-control" />
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="">Visibility Status</label>
                                <br/>
                                <input type="checkbox" name="status" <?= $customer['data']['status'] == true ? 'checked':''; ?> style="width:30px;height:30px" />
                                <small class="text-muted d-block mt-1">Check to hide customer</small>
                            </div>

                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="updateCustomer" class="btn btn-primary">Update Customer</button>
                                <a href="customers.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                        <?php
                    }
                    else
                    {
                        echo '<h5>'.$customer['message'].'</h5>';
                        return false;
                    }
                ?>
                

            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
