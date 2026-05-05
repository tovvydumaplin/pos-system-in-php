<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit User
                <a href="<?= $baseUrl ?>users/users.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="<?= $baseUrl ?>code.php" method="POST">

                <?php 
                    if(isset($_GET['id']))
                    {
                        if($_GET['id'] != ''){
                            $userId = $_GET['id'];
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

                    $userData = getById('users', $userId);
                    if($userData)
                    {
                        if($userData['status'] == 200)
                        {
                            ?>
                            <input type="hidden" name="userId" value="<?= $userData['data']['id']; ?>">
                        
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Name *</label>
                                    <input type="text" name="name" required value="<?= $userData['data']['name']; ?>" class="form-control" />
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="">User Type *</label>
                                    <select name="user_type" required class="form-select">
                                        <option value="">-- Select User Type --</option>
                                        <option value="admin" <?= $userData['data']['user_type'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="staff" <?= $userData['data']['user_type'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="">Email *</label>
                                    <input type="email" name="email" required value="<?= $userData['data']['email']; ?>" class="form-control" />
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="">Password</label>
                                    <input type="password" name="password" class="form-control" />
                                    <small class="text-muted">Leave blank to keep current password</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="">Phone Number *</label>
                                    <input type="number" name="phone" required value="<?= $userData['data']['phone']; ?>" class="form-control" />
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="">Ban Status</label>
                                    <br/>
                                    <input type="checkbox" name="is_ban" <?= $userData['data']['is_ban'] == true ? 'checked':''; ?> style="width:30px;height:30px;" />
                                    <small class="text-muted d-block">Check to ban user</small>
                                </div>
                                
                                <div class="col-md-12 mb-3 text-end">
                                    <button type="submit" name="updateUser" class="btn btn-primary">Update User</button>
                                </div>

                            </div>
                                    
                            <?php
                        }
                        else
                        {
                            echo '<h5>'.$userData['message'].'</h5>';
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

<?php include('../includes/footer.php'); ?>
