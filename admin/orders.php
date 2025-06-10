<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";

$connection = getConnection();

if (!isLogged()) {
    header("Location:/hope/login.php");
}

include __DIR__ . "/../templates/header.php";
?>

<div class="container-fluid d-flex" id="adminOrders">
    <?php include __DIR__ . "/../templates/sidebar.php"; ?>

    <div class="main-content col-10" id="mainContent">

        <div class="header mx-5 mt-5 p-3 d-flex justify-content-between align-items-center">
            <div class="header-title">
                <h3>Hi, Admin!</h3>
                <h1 class="fw-bold">Welcome to Website!</h1>
            </div>
            <div class="download-btn">
                <!-- buat download report -->
                <input type="submit" class="submit-btn" value="Download Report">
            </div>
        </div>
        <div class="card mx-5 mb-5 mt-3 p-3 border-0 shadow rounded-4">
            <div class="card-body">
                <div class="table-responsive orderTable">
                    <div class="header d-flex justify-content-between">
                        <h3 class="fw-bold">All Orders</h3>
                        <!-- <input type="text">
          search -->

                    </div>
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <!-- ganti pake looping nanti -->
                                <td class="text-muted text-center">Order ID</td>
                                <td class="text-muted text-center">Customer Name</td>
                                <td class="text-muted text-center">Project Name</td>
                                <td class="text-muted text-center">Order Date</td>
                                <td class="text-muted text-center">Quantity</td>
                                <td class="text-muted text-center">Price</td>
                                <td class="text-muted text-center">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">93232</td>
                                <td class="text-center">Gojo Satoru</td>
                                <td class="text-center">Kanopi Teduh</td>
                                <td class="text-center">12/12/2024</td>
                                <td class="text-center">4</td>
                                <td class="text-center">Rp 120000</td>
                                <td class="text-center">
                                    <p class="status-ongoing">On Going</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">93232</td>
                                <td class="text-center">Gojo Satoru</td>
                                <td class="text-center">Kanopi Teduh</td>
                                <td class="text-center">12/12/2024</td>
                                <td class="text-center">4</td>
                                <td class="text-center">Rp 120000</td>
                                <td class="text-center">
                                    <p class="status-pending">Pending</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">93232</td>
                                <td class="text-center">Gojo Satoru</td>
                                <td class="text-center">Kanopi Teduh</td>
                                <td class="text-center">12/12/2024</td>
                                <td class="text-center">4</td>
                                <td class="text-center">Rp 120000</td>
                                <td class="text-center">
                                    <p class="status-completed">Completed</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">93232</td>
                                <td class="text-center">Gojo Satoru</td>
                                <td class="text-center">Kanopi Teduh</td>
                                <td class="text-center">12/12/2024</td>
                                <td class="text-center">4</td>
                                <td class="text-center">Rp 120000</td>
                                <td class="text-center">
                                    <p class="status-finishing">Finishing</p>
                                </td>
                            </tr>
                    </table>
                    <hr>
                </div>
                <div class="d-flex justify-content-between pagination align-items-center">
                    <p class="text-muted orderTable">Showing data 1 to 2 of 256K entries</p>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item mx-1"><a class="page-link" href="#">&lt;</a></li>
                            <li class="page-item mx-1 active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item mx-1"><a class="page-link" href="#">2</a></li>
                            <li class="page-item mx-1"><a class="page-link" href="#">3</a></li>
                            <li class="page-item mx-1"><a class="page-link" href="#">4</a></li>
                            <li class="page-item mx-1"><a class="page-link" href="#">&gt;</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>