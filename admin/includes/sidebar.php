<?php
    $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
    $isAdmin = isset($_SESSION['loggedInUser']['user_type']) && ($_SESSION['loggedInUser']['user_type'] == 'admin' || $_SESSION['loggedInUser']['user_type'] == 'super_admin');
?>
<style>
    /* Section headings */
    .sb-sidenav-dark .sb-sidenav-menu-heading {
        color: #94a3b8;
        font-size: 0.65rem;
        letter-spacing: .08em;
    }

    /* Icon colors per link */
    .sb-sidenav-dark .nav-link .sb-nav-link-icon { transition: color .15s; }

    .nav-icon-cyan   { color: #38bdf8 !important; }
    .nav-icon-amber  { color: #fbbf24 !important; }
    .nav-icon-green  { color: #4ade80 !important; }
    .nav-icon-orange { color: #fb923c !important; }
    .nav-icon-blue   { color: #60a5fa !important; }
    .nav-icon-violet { color: #a78bfa !important; }
    .nav-icon-emerald{ color: #34d399 !important; }
    .nav-icon-pink   { color: #f472b6 !important; }
    .nav-icon-indigo { color: #818cf8 !important; }
    .nav-icon-slate  { color: #94a3b8 !important; }

    /* Active link gets a left accent */
    .sb-sidenav-dark .nav-link.active {
        border-left: 3px solid #38bdf8;
        padding-left: calc(1rem - 3px);
    }
</style>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>

                <a class="nav-link <?= $page == 'index.php' ? 'active':''; ?>" href="<?= $baseUrl ?>index.php">
                    <div class="sb-nav-link-icon nav-icon-cyan"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link <?= $page == 'order-create.php' ? 'active':''; ?>"
                    href="<?= $baseUrl ?>pos/order-create.php">
                    <div class="sb-nav-link-icon nav-icon-amber"><i class="fas fa-bell"></i></div>
                    Create Order
                </a>
                <a class="nav-link <?= $page == 'orders.php' ? 'active':''; ?>" href="<?= $baseUrl ?>pos/orders.php">
                    <div class="sb-nav-link-icon nav-icon-green"><i class="fas fa-list"></i></div>
                    Orders
                </a>
                <?php if($isAdmin): ?>
                <div class="sb-sidenav-menu-heading">Interface</div>

<a class="nav-link <?= ($page == 'services-create.php') || ($page == 'services.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseService" aria-expanded="false" aria-controls="collapseService">
                    <div class="sb-nav-link-icon nav-icon-orange"><i class="fas fa-columns"></i></div>
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

                    <div class="sb-nav-link-icon nav-icon-blue"><i class="fas fa-box"></i></div>
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

                <div class="sb-sidenav-menu-heading">Admin</div>

                <a class="nav-link <?= $page == 'branches.php' ? 'active':''; ?>" 
                    href="<?= $baseUrl ?>branches/branches.php">
                    <div class="sb-nav-link-icon nav-icon-violet"><i class="fas fa-building"></i></div>
                    Manage Branches
                </a>

                <a class="nav-link <?= $page == 'backup-restore.php' ? 'active':''; ?>" 
                    href="<?= $baseUrl ?>backup-restore/backup-restore.php">
                    <div class="sb-nav-link-icon nav-icon-slate"><i class="fas fa-database"></i></div>
                    Backup & Restore
                </a>

                <a class="nav-link <?= $page == 'analytics.php' ? 'active':''; ?>" 
                    href="<?= $baseUrl ?>analytics/analytics.php">
                    <div class="sb-nav-link-icon nav-icon-emerald"><i class="fas fa-chart-bar"></i></div>
                    Analytics & Reports
                </a>

                <div class="sb-sidenav-menu-heading">Manage Users</div>
                
                <!-- Unified Users Management -->
                <a class="nav-link <?= ($page == 'users.php') || ($page == 'CreateUser.php') || ($page == 'EditUser.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseUsers" 
                    aria-expanded="false" aria-controls="collapseUsers">

                    <div class="sb-nav-link-icon nav-icon-pink"><i class="fas fa-users"></i></div>
                    Users
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

                    <div class="sb-nav-link-icon nav-icon-indigo"><i class="fas fa-user-friends"></i></div>
                    Customers
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?= ($page == 'customers-create.php') || ($page == 'customers.php') || ($page == 'customers-edit.php') ? 'show':''; ?>" id="collapseCustomer" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'customers-create.php' ? 'active':''; ?>" href="<?= $baseUrl ?>customers/customers-create.php">Add Customer</a>
                        <a class="nav-link <?= $page == 'customers.php' ? 'active':''; ?>" href="<?= $baseUrl ?>customers/customers.php">View Customers</a>
                    </nav>
                </div>

                <?php endif; ?>
            </div>

        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= $_SESSION['loggedInUser']['name']; ?>
            <?php if(isset($_SESSION['loggedInUser']['user_type'])): ?>
                <?php
                    $userType = $_SESSION['loggedInUser']['user_type'];
                    $badgeClass = 'bg-secondary';
                    $displayName = ucfirst(str_replace('_', ' ', $userType));
                    
                    if($userType == 'super_admin') {
                        $badgeClass = 'bg-danger';
                    } elseif($userType == 'admin') {
                        $badgeClass = 'bg-info';
                    }
                ?>
                <span class="badge <?= $badgeClass ?>">
                    <?= $displayName; ?>
                </span>
            <?php endif; ?>
        </div>
    </nav>
</div>
