<?php
    $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>

                <a class="nav-link <?= $page == 'index.php' ? 'active':''; ?>" href="<?= $baseUrl ?>index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard 
                </a>

                <a class="nav-link <?= $page == 'order-create.php' ? 'active':''; ?>"
                    href="<?= $baseUrl ?>pos/order-create.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                    Create Order
                </a>
                <a class="nav-link <?= $page == 'orders.php' ? 'active':''; ?>" href="<?= $baseUrl ?>pos/orders.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                    Orders
                </a>
                
                
                <div class="sb-sidenav-menu-heading">Interface</div>

                <a class="nav-link <?= ($page == 'categories-create.php') || ($page == 'categories.php') ? 'collapse active':'collapsed'; ?>"
                    href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Categories
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?= ($page == 'categories-create.php') || ($page == 'categories.php') ? 'show':''; ?>"  
                    id="collapseCategory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'categories-create.php' ? 'active':''; ?>" href="<?= $baseUrl ?>categories/categories-create.php">Create Category</a>
                        <a class="nav-link <?= $page == 'categories.php' ? 'active':''; ?>" href="<?= $baseUrl ?>categories/categories.php">View Categories</a>
                    </nav>
                </div>

                <a class="nav-link <?= ($page == 'services-create.php') || ($page == 'services.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseService" aria-expanded="false" aria-controls="collapseService">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Services
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?= ($page == 'services-create.php') || ($page == 'services.php') ? 'show':''; ?>" id="collapseService" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'services-create.php' ? 'active':''; ?>" href="<?= $baseUrl ?>services/services-create.php">Create Service</a>
                        <a class="nav-link <?= $page == 'services.php' ? 'active':''; ?>" href="<?= $baseUrl ?>services/services.php">View Services</a>
                    </nav>
                </div>
                

<a class="nav-link <?= ($page == 'inventory.php') || ($page == 'stock-movement.php') ? 'collapse active':'collapsed'; ?>" 
    href="#" 
    data-bs-toggle="collapse" 
    data-bs-target="#collapseInventory" 
    aria-expanded="false" 
    aria-controls="collapseInventory">

    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
    Inventory
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>

<div class="collapse <?= ($page == 'inventory.php') || ($page == 'stock-movement.php') ? 'show':''; ?>" 
    id="collapseInventory" 
    data-bs-parent="#sidenavAccordion">

    <nav class="sb-sidenav-menu-nested nav">

        <a class="nav-link <?= $page == 'inventory.php' ? 'active':''; ?>" 
            href="<?= $baseUrl ?>inventory/inventory.php">
            Laundry Stocks
        </a>

        <a class="nav-link <?= $page == 'stock-movement.php' ? 'active':''; ?>" 
            href="<?= $baseUrl ?>inventory/stock-movement.php">
            Stock Movement
        </a>

    </nav>
</div>
                <div class="sb-sidenav-menu-heading">Manage Users</div>
                
                <!-- Unified Users Management -->
                <a class="nav-link <?= ($page == 'users.php') || ($page == 'CreateUser.php') || ($page == 'EditUser.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseUsers" 
                    aria-expanded="false" aria-controls="collapseUsers">

                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Users (Admin & Staff)
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?= ($page == 'users.php') || ($page == 'CreateUser.php') || ($page == 'EditUser.php') ? 'show':''; ?>" id="collapseUsers" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'CreateUser.php' ? 'active':''; ?>" href="<?= $baseUrl ?>users/CreateUser.php">Add User</a>
                        <a class="nav-link <?= $page == 'users.php' ? 'active':''; ?>" href="<?= $baseUrl ?>users/users.php">View Users</a>
                    </nav>
                </div>

                <a class="nav-link <?= ($page == 'customers-create.php') || ($page == 'customers.php') || ($page == 'customers-edit.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseCustomer" 
                    aria-expanded="false" aria-controls="collapseCustomer">

                    <div class="sb-nav-link-icon"><i class="fas fa-user-friends"></i></div>
                    Customers
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?= ($page == 'customers-create.php') || ($page == 'customers.php') || ($page == 'customers-edit.php') ? 'show':''; ?>" id="collapseCustomer" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'customers-create.php' ? 'active':''; ?>" href="<?= $baseUrl ?>customers/customers-create.php">Add Customer</a>
                        <a class="nav-link <?= $page == 'customers.php' ? 'active':''; ?>" href="<?= $baseUrl ?>customers/customers.php">View Customers</a>
                    </nav>
                </div>

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= $_SESSION['loggedInUser']['name']; ?>
            <?php if(isset($_SESSION['loggedInUser']['user_type'])): ?>
                <span class="badge <?= $_SESSION['loggedInUser']['user_type'] == 'admin' ? 'bg-info' : 'bg-secondary' ?>">
                    <?= ucfirst($_SESSION['loggedInUser']['user_type']); ?>
                </span>
            <?php endif; ?>
        </div>
    </nav>
</div>
