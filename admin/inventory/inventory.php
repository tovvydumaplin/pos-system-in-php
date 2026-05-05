<?php include('../includes/header.php'); ?>

<div class="container-fluid px-4">
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
            <input type="text" name="item_name" class="form-control" required>
            </div>

            <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
            </div>

            <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" required>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Item</button>
        </div>

        </form>

    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
<script src="<?= $baseUrl ?>assets/js/inventory.js"></script>