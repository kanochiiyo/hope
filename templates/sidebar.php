<div class="sidebar col-2" id="sidebar">
    <div class="logo-section">
        <img src="https://placehold.co/30x30" alt="hope">
        <span class="fw-bold m-3" style="color: #4318ff">HOPE</span>
    </div>

    <div class="sidebar-list">
        <?php if (isAdmin()) { ?>
            <div class="nav-item p-2 m-2 fw-bold">
                <span>Dashboard</span>
            </div>

            <div class="nav-item p-2 m-2 fw-bold">
                <span>Requested Orders</span>
            </div>
        <?php } ?>
        
        <div class="nav-item p-2 m-2 fw-bold active">
            <span>Orders</span>
        </div>
        
        <div class="nav-item p-2 m-2 fw-bold">
            <span>Create Orders</span>
        </div>

        <div class="nav-item p-2 m-2 fw-bold">
            <span><a href="/hope/logout.php" class="text-decoration-none text-reset">Log Out</a></span>
        </div>
    </div>
</div>