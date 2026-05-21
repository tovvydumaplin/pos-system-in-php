<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
    <h1 class="mb-1 mt-4"><?= $_SESSION['loggedInUser']['branch_name']; ?> Stocks</h1>
    <div class="card mt-4 shadow-sm">
        
        <!-- HEADER -->
        <div class="card-header">
            <div class="row align-items-center">

                <div class="col-md-4">
                    <h4 class="mb-0">Laundry Consumables</h4>
                </div>

                <div class="col-md-8">
                    <form method="GET">
                        <div class="row g-1 align-items-center">

                            <!-- SEARCH -->
                            <div class="col-md-4">
                                <input type="text" 
                                    name="search"
                                    class="form-control"
                                    placeholder="Search item..."
                                    value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                            </div>

                            <!-- BUTTONS -->
                            <div class="col-md-8 text-end">

                                <!-- ADD ITEM BUTTON -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                    + Add Item
                                </button>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <div id="inventoryTable"></div>

            </div>
        </div>
    </div>

    <!-- MODAL  -->
<!-- MODAL -->
<div class="modal fade" id="addItemModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

        <form id="addItemForm">

        <div class="modal-header">
            <h5 class="modal-title">Add New Item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="mb-3">
                <label>Item Name</label>
                <input 
                    type="text" 
                    name="item_name" 
                    class="form-control" 
                    required
                >
            </div>

            <!-- Branch Required -->
            <?php
            $branchId = $_SESSION['loggedInUser']['branch_id'];

            $getBranch = mysqli_query(
                $conn,
                "SELECT branch_name FROM branches WHERE id='$branchId' LIMIT 1"
            );

            $branch = mysqli_fetch_assoc($getBranch);
            ?>

            <div class="mb-3">
                <label>Branch</label>

                <input 
                    type="hidden"
                    name="branch_id"
                    value="<?= $branchId; ?>"
                >

                <input 
                    type="text"
                    class="form-control"
                    value="<?= $branch['branch_name']; ?>"
                    readonly
                >
            </div>

            <div class="mb-3">
                <label>Quantity</label>
                <input 
                    type="number" 
                    name="quantity" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input 
                    type="number" 
                    name="price" 
                    class="form-control" 
                    step="0.01"
                    required
                >
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

            <button type="submit" class="btn btn-primary">
                Save Item
            </button>
        </div>

        </form>

    </div>
  </div>
</div>
<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="adjustStockForm">

                <input type="hidden" name="item_id" id="adjust_item_id">
                <input type="hidden" name="branch_id" id="adjust_branch_id">

                <div class="modal-header">
                    <h5 class="modal-title">Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Item Name</label>
                        <input 
                            type="text"
                            id="adjust_item_name"
                            class="form-control"
                            readonly
                        >
                    </div>

                    <div class="mb-3">
                        <label>Branch</label>
                        <input
                            type="text"
                            id="adjust_branch_name"
                            class="form-control"
                            readonly
                        >
                    </div>

                    <div class="mb-3">
                        <label>Current Stock</label>
                        <input
                            type="number"
                            id="current_stock"
                            class="form-control"
                            readonly
                        >
                    </div>

                    <div class="mb-3">
                        <label>Adjustment Type</label>

                        <select name="adjustment_type" class="form-control" required>
                            <option value="ADD">Add Stock</option>
                            <option value="DEDUCT">Deduct Stock</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Quantity</label>
                        <input
                            type="number"
                            name="add_quantity"
                            class="form-control"
                            min="1"
                            required
                        >
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

                    <button type="submit" class="btn btn-success">
                        Update Stock
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="editItemForm">

                <input type="hidden" name="item_id" id="edit_item_id">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Item
                    </h5>

                    <button 
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Item Name</label>

                        <input
                            type="text"
                            name="item_name"
                            id="edit_item_name"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Branch</label>

                        <input
                            type="text"
                            id="edit_branch_name"
                            class="form-control"
                            readonly
                        >
                    </div>

                    <div class="mb-3">
                        <label>Price</label>

                        <input
                            type="number"
                            name="price"
                            id="edit_price"
                            step="0.01"
                            class="form-control"
                            required
                        >
                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Update Item
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>
<script src="<?= $baseUrl ?>assets/js/inventory.js"></script>