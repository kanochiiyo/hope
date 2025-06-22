<div class="sidebar col-2" id="sidebar">
    <div class="logo-section">
        <img src="../assets/logo.png" alt="hope">
        <span class="fw-bold m-1" style="color: #4318ff">HOPE</span>
    </div>

    <div class="sidebar-list">
        <?php if (isAdmin()) { ?>
            <div class="nav-item p-2 m-2 fw-bold <?= isActive('/hope/admin/index.php') ?>"
                onclick="location.href='/hope/admin/index.php'">
                Dashboard
            </div>
            <div class="nav-item p-2 m-2 fw-bold <?= isActive('/hope/admin/orders.php') ?>"
                onclick="location.href='/hope/admin/orders.php'">
                Orders
            </div>

            <div class="nav-item p-2 m-2 fw-bold <?= isActive('/hope/admin/req_orders.php') ?>"
                onclick="location.href='/hope/admin/req_orders.php'">
                Requested Order
            </div>
        <?php } ?>

        <?php if (isStaff()) { ?>
            <div class="nav-item p-2 m-2 fw-bold <?= isActive('index.php') ?>"
                onclick="location.href='/hope/staff/index.php'">
                Orders
            </div>
        <?php } elseif (!isAdmin()) { ?>
            <div class="nav-item p-2 m-2 fw-bold <?= isActive('index.php') ?>"
                onclick="location.href='/hope/user/index.php'">
                Orders
            </div>
            <div class="nav-item p-2 m-2 fw-bold <?= isActive('create_orders.php') ?>"
                onclick="location.href='/hope/user/create_orders.php'">
                Create Orders
            </div>
        <?php } ?>



        <!-- SEMENTARA DI SINI DULU LOGOUTNYA -->
        <div class="nav-item p-2 m-2 fw-bold <?= isActive('logout.php') ?>" onclick="location.href='/hope/logout.php'">
            Log Out
        </div>
    </div>
</div>