<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Staff
                <a href="staffs.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method="POST">

                <?php 
                    if(isset($_GET['id']))
                    {
                        if($_GET['id'] != ''){

                            $staffId = $_GET['id'];
                        }else{
                            echo '<h5>No Id Found</h5>';
                            return false;
                        }
                    }
                    else
                    {
                        echo '<h5>No Id given in url</h5>';
                        return false;
                    }
                

                    $staffData = getById('users', $staffId);
                    if($staffData)
                    {
                        if($staffData['status'] == 200)
                        {
                            ?>
                            <input type="hidden" name="staffId" value="<?= $staffData['data']['id']; ?>">
                        
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Name *</label>
                                    <input type="text" name="name" required value="<?= $staffData['data']['name']; ?>" class="form-control" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Email *</label>
                                    <input type="email" name="email" required value="<?= $staffData['data']['email']; ?>" class="form-control" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Password *</label>
                                    <input type="password" name="password" class="form-control" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Phone Number *</label>
                                    <input type="number" name="phone" required value="<?= $staffData['data']['phone']; ?>" class="form-control" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="">Status</label>
                                    <br/>
                                    <input type="checkbox" name="is_ban" <?= $staffData['data']['is_ban'] == true ? 'checked':''; ?> style="width:30px;height:30px;" />
                                </div>
                                <div class="col-md-12 mb-3 text-end">
                                    <button type="submit" name="updateStaff" class="btn btn-primary">Update</button>
                                </div>

                            </div>
                                    
                            <?php
                        }
                        else
                        {
                            echo '<h5>'.$staffData['message'].'</h5>';
                        }
                    }
                    else
                    {
                        echo 'Something Went Wrong';
                        return false;
                    }

                ?>
                
          
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>