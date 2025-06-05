<?php
session_start();

require_once __DIR__ . "/functions/authentication.php";
require_once __DIR__ . "/functions/functions.php";

$connection = getConnection();

if (!isLogged()) {
    header("Location:login.php");
}

include __DIR__ . "/templates/header.php";
?>

<div class="container-fluid d-flex" id="createOrder">
    <?php include(__DIR__ . "/templates/sidebar.php"); ?>

    <div class="order-form col-10" id="orderForm">
        <div class="card m-5 p-3 border-0 shadow rounded-4" style="width: 61rem;">
            <div class="card-body">
                <div class="header d-flex justify-content-between">
                    <h3 class="fw-bold">Create Orders</h3>
                </div>
                <div class="order-form">
                    <form action="" method="post" class="border-0">
                        <div class="mb-3 row">
                            <label for="inputName" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputName" name="custName"
                                    placeholder="Input Name">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputPhone" class="col-sm-3 col-form-label">Phone Number</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="inputPhone" name="phoneNumber"
                                    placeholder="Input Phone Number">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputProjectName" class="col-sm-3 col-form-label">Project Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputProjectName" name="projectName"
                                    placeholder="Input Project Name">
                            </div>
                        </div>
                        <!-- slect projct type -->
                        <div class="mb-3 row">
                            <label for="inputQuantity" class="col-sm-3 col-form-label">Quantity</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="inputQuantity" name="quantity"
                                    placeholder="Input Quantity">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputShippingAddres" class="col-sm-3 col-form-label">Shipping Address</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputShippingAddres" name="shippingAddress"
                                    placeholder="Input Shpping Address">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputDesc" class="col-sm-3 col-form-label">Desciption</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" placeholder="Description" id="inputDesc"
                                    style="height: 100px"></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row d-flex justify-content-between" style="margin: 0px 1px;">
                            <input type="submit" class="back-btn col-2" value="Back">
                            <input type="submit" class="submit-btn col-2" value="Submit">
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>