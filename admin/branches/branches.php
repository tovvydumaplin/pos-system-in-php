<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">

        <!-- HEADER -->
        <div class="card-header">
            <div class="row align-items-center">

                <div class="col-md-4">
                    <h4 class="mb-0">Branches Dashboard</h4>
                </div>

                <div class="col-md-8">
                    <form method="GET">
                        <div class="row g-1 align-items-center">

                            <!-- SEARCH -->
                            <div class="col-md-4">
                                <input type="text" 
                                    name="search"
                                    class="form-control"
                                    placeholder="Search branch..."
                                    value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                            </div>

                            <!-- BUTTONS -->
                            <div class="col-md-8 text-end">

                                <!-- ADD BRANCH BUTTON -->
                                <button 
                                    type="button" 
                                    class="btn btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addBranchModal"
                                >
                                    + Add Branch
                                </button>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Branch Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        
                        if($search != ''){
                            $query = "SELECT * FROM branches WHERE branch_name LIKE '%$search%' OR address LIKE '%$search%' ORDER BY id DESC";
                        }else{
                            $query = "SELECT * FROM branches ORDER BY id DESC";
                        }
                        
                        $branches = mysqli_query($conn, $query);
                        
                        if(mysqli_num_rows($branches) > 0){
                            $i = 1;
                            foreach($branches as $branch){
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $branch['branch_name']; ?></td>
                                    <td><?= $branch['address']; ?></td>
                                    <td><?= $branch['contact_no']; ?></td>

                                    <td>
                                        <?php if($branch['status'] == 'ACTIVE'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <small class="text-muted">
                                            <?= isset($branch['created_by']) ? $branch['created_by'] : '-'; ?>
                                        </small>
                                    </td>

                                    <td>
                                        <button 
                                            class="btn btn-sm btn-primary editBranchBtn"
                                            data-id="<?= $branch['id']; ?>"
                                            data-name="<?= $branch['branch_name']; ?>"
                                            data-address="<?= $branch['address']; ?>"
                                            data-contact="<?= $branch['contact_no']; ?>"
                                            data-status="<?= $branch['status']; ?>"
                                        >
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        }else{
                            ?>
                            <tr>
                                <td colspan="7" class="text-center">No branches found</td>
                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

<!-- ADD BRANCH MODAL -->
<div class="modal fade" id="addBranchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="addBranchForm">

                <div class="modal-header">
                    <h5 class="modal-title">Add Branch</h5>

                    <button 
                        type="button" 
                        class="btn-close" 
                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Branch Name <span class="text-danger">*</span></label>

                        <input 
                            type="text"
                            name="branch_name"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Address</label>

                        <textarea 
                            name="address"
                            class="form-control"
                            rows="3"
                        ></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Contact Number</label>

                        <input 
                            type="text"
                            name="contact_no"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-3">
                        <label>Status</label>

                        <select 
                            name="status"
                            class="form-control"
                        >
                            <option value="ACTIVE" selected>Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">

                    <button 
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button 
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save Branch
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<!-- EDIT BRANCH MODAL -->
<div class="modal fade" id="editBranchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="editBranchForm">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Branch</h5>

                    <button 
                        type="button" 
                        class="btn-close" 
                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="branchId" id="editBranchId">

                    <div class="mb-3">
                        <label>Branch Name <span class="text-danger">*</span></label>

                        <input 
                            type="text"
                            name="branch_name"
                            id="editBranchName"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Address</label>

                        <textarea 
                            name="address"
                            id="editAddress"
                            class="form-control"
                            rows="3"
                        ></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Contact Number</label>

                        <input 
                            type="text"
                            name="contact_no"
                            id="editContactNumber"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-3">
                        <label>Status</label>

                        <select 
                            name="status"
                            id="editStatus"
                            class="form-control"
                        >
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">

                    <button 
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button 
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Branch
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<script src="../assets/js/branches.js"></script>
