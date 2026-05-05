<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Users Management
                <a href="<?= $baseUrl ?>users/CreateUser.php" class="btn btn-primary float-end">Add User</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <!-- Filter by User Type -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="userTypeFilter" class="form-select">
                        <option value="all">All Users</option>
                        <option value="admin">Admins Only</option>
                        <option value="staff">Staff Only</option>
                    </select>
                </div>
            </div>

            <?php
            $users = getAll('users');
            if(!$users){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if(mysqli_num_rows($users) > 0)
            {
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $userItem) : ?>
                        <tr data-usertype="<?= $userItem['user_type'] ?>">
                            <td><?= $userItem['id'] ?></td>
                            <td><?= $userItem['name'] ?></td>
                            <td>
                                <?php
                                    if($userItem['user_type'] == 'admin'){
                                        echo '<span class="badge bg-info">Admin</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">Staff</span>';
                                    }
                                ?>
                            </td>
                            <td><?= $userItem['email'] ?></td>
                            <td><?= $userItem['phone'] ?></td>
                            <td>
                                <?php
                                    if($userItem['is_ban'] == 1){
                                        echo '<span class="badge bg-danger">Banned</span>';
                                    }else{
                                        echo '<span class="badge bg-success">Active</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="<?= $baseUrl ?>users/EditUser.php?id=<?= $userItem['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a 
                                    href="<?= $baseUrl ?>users/DeleteUser.php?id=<?= $userItem['id']; ?>" 
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
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
                <h4 class="mb-0">No Users Found</h4>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script>
// Filter users by type
document.getElementById('userTypeFilter').addEventListener('change', function() {
    const filter = this.value;
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const userType = row.getAttribute('data-usertype');
        if (filter === 'all' || userType === filter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php include('../includes/footer.php'); ?>
