<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add User
                <a href="<?= $baseUrl ?>users/users.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            
            <form action="<?= $baseUrl ?>code.php" method="POST">
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">Name *</label>
                        <input type="text" name="name" required class="form-control" />
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="">User Type *</label>
                        <select name="user_type" required class="form-select">
                            <option value="">-- Select User Type --</option>
                            <?php if(isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin'): ?>
                            <option value="super_admin">Super Admin</option>
                            <?php endif; ?>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="">Email *</label>
                        <input type="email" name="email" required class="form-control" />
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="">Password *</label>
                        <input type="password" name="password" required class="form-control" />
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="">Phone Number *</label>
                        <input type="number" name="phone" required class="form-control" />
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="">Branch (Optional)</label>
                        <select name="branch_id" class="form-select">
                            <option value="">-- Select Branch --</option>
                            <?php
                            $branches = getAll('branches');
                            if($branches && mysqli_num_rows($branches) > 0){
                                foreach($branches as $branch){
                                    echo '<option value="'.$branch['id'].'">'.$branch['branch_name'].'</option>';
                                }
                            }
                            ?>
                        </select>
                        <small class="text-muted">Assign user to a specific branch</small>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="">Ban Status</label>
                        <br/>
                        <input type="checkbox" name="is_ban" style="width:30px;height:30px;" />
                        <small class="text-muted d-block">Check to ban user</small>
                    </div>
                    
                    <div class="col-md-12 mb-3 text-end">
                        <button type="submit" name="saveUser" class="btn btn-primary">Save User</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
