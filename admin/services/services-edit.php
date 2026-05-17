<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Service
                <a href="services.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <form action="<?= $baseUrl ?>code.php" method="POST" enctype="multipart/form-data">
                

                <?php
                    $paramValue = checkParamId('id');
                    if(!is_numeric($paramValue)){
                        echo '<h5>Id is not an integer</h5>';
                        return false;
                    }
                    
                    $service = getById('services',$paramValue);
                    if($service)
                    {
                        if($service['status'] == 200)
                        {
                        ?>

                        <input type="hidden" name="service_id" value="<?= $service['data']['id']; ?>" />
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">Service Name *</label>
                                <input type="text" name="name" required value="<?= $service['data']['name']; ?>" class="form-control" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= $service['data']['description']; ?></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Price *</label>
                                <input type="text" name="price" required value="<?= $service['data']['price']; ?>" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Quantity *</label>
                                <input type="text" name="quantity" required value="<?= $service['data']['quantity']; ?>" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Image</label>
                                <input type="file" name="image" class="form-control" />
                                <?php if(!empty($service['data']['image'])): ?>
                                    <img src="../../<?= $service['data']['image']; ?>" class="mt-2" style="width:60px;height:60px;object-fit:cover;border-radius:4px;" alt="Current" />
                                <?php endif; ?>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="">Visibility Status</label>
                                <br/>
                                <input type="checkbox" name="status" <?= $service['data']['status'] == true ? 'checked':''; ?> style="width:30px;height:30px" />
                                <small class="text-muted d-block mt-1">Check to hide service</small>
                            </div>

                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="updateService" class="btn btn-primary">Update Service</button>
                                <a href="services.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                        <?php
                        }
                        else
                        {
                            echo '<h5>'.$service['message'].'</h5>';
                        }
                    }
                    else
                    {
                        echo '<h5>Something Went Wrong</h5>';
                        return false;
                    }
                ?>

            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
