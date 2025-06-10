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
    <?php include(__DIR__ . "/../templates/sidebar.php"); ?>

    <div class="main-content col-10" id="mainContent">
        <div class="card m-5 p-3 border-0 shadow rounded-4">
            <div class="card-body">
                <div class="header">
                    <h3 class="fw-bold">Complete Registration Payment</h3>
                    <h5 class="fw-bold my-3">Personal Detail</h5>
                </div>

                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="address" class="form-label label">Address Line</label>
                            <input type="text" class="form-control" id="address">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="city" class="form-label label">City</label>
                            <input type="text" class="form-control" id="city">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="state" class="form-label label">State</label>
                            <input type="text" class="form-control" id="state">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="postal" class="form-label label">Postal Code</label>
                            <input type="text" class="form-control" id="postal">
                        </div>
                    </div>
                    <h5 class="fw-bold my-3">Payment Method</h5>
                    <input type="radio" class="btn-check" name="options-base" id="visa" autocomplete="off" checked>
                    <label class="btn" for="visa"><img src="../assets/visa.png" alt="Visa"></label>
                    <input type="radio" class="btn-check" name="options-base" id="mastercard" autocomplete="off">
                    <label class="btn" for="mastercard"><img src="../assets/mastercard.png" alt="Mastercard"></label>
                    <h5 class="fw-bold my-3">Card Details</h5>
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="holderName" class="form-label label">Cardholder's Name</label>
                            <input type="text" class="form-control" id="holderName">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="cardNumber" class="form-label label">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="expirity" class="form-label label">Expirity</label>
                            <input type="text" class="form-control" id="expirity">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="cvc" class="form-label label">CVC</label>
                            <input type="text" class="form-control" id="cvc">
                        </div>
                    </div>
                    <div class="mb-3 row d-flex justify-content-between" style="margin: 0px 1px;">
                        <a href="index.php" class="back-btn col-2 text-decoration-none text-center">Back</a>
                        <input type="submit" class="submit-btn col-2" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>