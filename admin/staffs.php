<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Staffs
                <a href="staffs-create.php" class="btn btn-primary float-end">Add Staff</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
            $staffs = getAll('users');
            if(!$staffs){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if(mysqli_num_rows($staffs) > 0)
            {
                
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($staffs as $staffItem) : ?>
                        <tr>
                            <td><?= $staffItem['id'] ?></td>
                            <td><?= $staffItem['name'] ?></td>
                            <td><?= $staffItem['email'] ?></td>
                            <td>
                                <?php
                                    if($staffItem['is_ban'] == 1){
                                        echo '<span class="badge bg-danger">Banned</span>';
                                    }else{
                                        echo '<span class="badge bg-primary">Active</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="staffs-edit.php?id=<?= $staffItem['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="staffs-delete.php?id=<?= $staffItem['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
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

<?php include('includes/footer.php'); ?>
