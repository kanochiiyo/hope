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

<div class="container-fluid d-flex" id="detailOrders">
    <?php include(__DIR__ . "/../templates/sidebar.php"); ?>

    <div class="main-content col-10" id="mainContent">
        <!-- Tabel Konfirmasi Proyek -->
        <div class="card m-5 p-3 border-0 shadow rounded-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <div class="table-responsive detailOrderTable">
                            <div class="header">
                                <h3 class="fw-bold">Detail Orders #666</h3>
                            </div>

                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="row-label" style="width: 250px">Project Name</td>
                                        <td>:</td>
                                        <td>Pintu Gerbang Warna Pink</td>
                                    </tr>
                                    <tr>
                                        <td class="row-label" style="width: 250px">Quantity</td>
                                        <td>:</td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td class="row-label" style="width: 250px">Order Date</td>
                                        <td>:</td>
                                        <td>02/04/2025</td>
                                    </tr>
                                    <tr>
                                        <td class="row-label" style="width: 250px">Est. Completion Date</td>
                                        <td>:</td>
                                        <td>02/04/2025</td>
                                    </tr>
                                    <tr>
                                        <td class="row-label" style="width: 250px">Price</td>
                                        <td>:</td>
                                        <td>Rp 100000</td>
                                    </tr>
                                    <tr>
                                        <td class="row-label" style="width: 250px">Shipping Address</td>
                                        <td>:</td>
                                        <td>Tokyo</td>
                                    </tr>
                                    <tr>
                                        <td class="row-label" style="width: 250px">Description</td>
                                        <td>:</td>
                                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque quasi
                                            officiis
                                            facilis blanditiis, rem dolor, nobis quidem ullam ad nesciunt optio esse
                                            amet quia
                                            minima soluta accusantium, sit vel officia?</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mb-3 row d-flex justify-content-between" style="margin: 0px 1px;">
                                <a href="index.php" class="back-btn col-2 text-decoration-none text-center">Back</a>
                            </div>

                        </div>
                    </div>
                    <div class="col-3" style="background: #FAFBFF; border: 1px solid #5038ED; border-radius: 16px;">
                        <h1>Status Projek</h1>
                        <div class="position-relative w-full h-full" style="height: 100%;">
                            <div class="position-absolute"
                                style="width:1px; background: black;min-height: 300px;left: 100px;"></div>
                            <div class="position-absolute d-flex align-items-center">
                                <time style="width:95px">03-27</time>
                                <div class="bg-success position-absolute"
                                    style="width: 12px;height: 12px; border-radius: 50%; border: 1px solid black; left: 94px;">
                                </div>
                                <span style="margin-left: 50px;">Project Approved</span>
                            </div>
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="position-absolute d-flex align-items-center" style="top: <?= $i * 50; ?>px">
                                    <time style="width:95px">03-27</time>
                                    <div class="bg-success position-absolute"
                                        style="width: 12px;height: 12px; border-radius: 50%; border: 1px solid black; left: 94px;">
                                    </div>
                                    <span style="margin-left: 50px;">Project Approved</span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>