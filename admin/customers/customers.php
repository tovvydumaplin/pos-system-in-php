<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Customers
                <a href="customers-create.php" class="btn btn-primary float-end">Add Customer</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertMessage(); ?>

            <?php
            // Filter customers by branch unless super_admin
            $isSuperAdmin = isset($_SESSION['loggedInUser']['user_type']) && $_SESSION['loggedInUser']['user_type'] == 'super_admin';
            $userBranchId = $_SESSION['loggedInUser']['branch_id'] ?? null;
            
            if ($isSuperAdmin) {
                $customers = getAll('customers');
            } else {
                $customers = mysqli_query($conn, "
                    SELECT * FROM customers 
                    WHERE branch_id = '$userBranchId'
                    ORDER BY id DESC
                ");
            }
            
            if(!$customers){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if(mysqli_num_rows($customers) > 0)
            {
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $item) : 
                            // Get branch name if assigned
                            $branchName = '-';
                            if(isset($item['branch_id']) && !empty($item['branch_id'])){
                                $branchData = getById('branches', $item['branch_id']);
                                if($branchData && $branchData['status'] == 200){
                                    $branchName = $branchData['data']['branch_name'];
                                }
                            }
                        ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['email'] ?></td>
                            <td><?= $item['phone'] ?></td>
                            <td><?= $branchName ?></td>
                            <td>
                                <?php
                                    if($item['status'] == 1){
                                        echo '<span class="badge bg-danger">Hidden</span>';
                                    }else{
                                        echo '<span class="badge bg-primary">Visible</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="customers-edit.php?id=<?= $item['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a 
                                    href="customers-delete.php?id=<?= $item['id']; ?>" 
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this data?')"
                                >
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else
            {
                ?>
                    <h4 class="mb-0">No Record Found</h4>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
