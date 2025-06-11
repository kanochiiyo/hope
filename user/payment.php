<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";
require_once __DIR__ . "/../functions/orderFunctions.php";

$connection = getConnection();

if (!isLogged()) {
    header("Location:/hope/login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $result = updateUserApproval($_POST);
  $id = $_POST['id'];
}

if (isset($_POST["payment"])) {
  $result = createPayment($_POST);
}

include __DIR__ . "/../templates/header.php";
?>

<div class="container-fluid d-flex" id="payment">
    <?php include(__DIR__ . "/../templates/sidebar.php"); ?>

    <?php if (isset($_POST["payment"])): ?>
    <div class="col-10 order-form-container d-flex justify-content-center align-items-center" style="min-height: 100vh">
      <!-- Pop-up Start -->
      <div class="popup p-5">
        <a class="d-flex align-items-center justify-content-center" href="index.php">X</a>
        <?php if ($result): ?>
          <div class="order-sucess m-5">
            <img class="mb-4" src="../assets/check_circle.png" alt="Order Completed">
            <h4 class="row-label">Payment Successfully</h4>
          </div>
        <?php else: ?>
          <div class="order-failed m-5">
            <!-- ga nemu yg gambar X -->
            <img class="mb-4" src="../assets/check_circle.png" alt="Order Failed">
            <h4 class="row-label">Payment Failed</h4>
          </div>
        <?php endif; ?>
      </div>
      <!-- Pop-up End -->
    </div>
  <?php else: ?>

    <div class="main-content col-10" id="mainContent">
        <div class="card m-5 p-3 border-0 shadow rounded-4">
            <div class="card-body">
                <div class="header">
                    <h3 class="fw-bold">Complete Registration Payment</h3>
                    <h5 class="fw-bold my-3">Personal Detail</h5>
                </div>
                <form method="post">
                    <input type="hidden" name="id" value="<?=$id?>">
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="address" class="form-label label">Address Line</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="city" class="form-label label">City</label>
                            <input type="text" class="form-control" id="city" name="city">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="state" class="form-label label">State</label>
                            <input type="text" class="form-control" id="state" name="state">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="postal" class="form-label label">Postal Code</label>
                            <input type="text" class="form-control" id="postal" name="postal">
                        </div>
                    </div>
                    <h5 class="fw-bold my-3">Payment Method</h5>
                    <input type="radio" class="btn-check" id="visa" autocomplete="off" name="payment_method" checked>
                    <label class="btn" for="visa"><img src="../assets/visa.png" alt="Visa"></label>
                    <input type="radio" class="btn-check" id="mastercard" autocomplete="off" name="payment_method">
                    <label class="btn" for="mastercard"><img src="../assets/mastercard.png" alt="Mastercard"></label>
                    <h5 class="fw-bold my-3">Card Details</h5>
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="holderName" class="form-label label">Cardholder's Name</label>
                            <input type="text" class="form-control" id="holderName" name="card_name">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="cardNumber" class="form-label label">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" name="card_number">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="mb-1 col-6">
                            <label for="expirity" class="form-label label">Expirity</label>
                            <input type="text" class="form-control" id="expirity" name="expirity">
                        </div>
                        <div class="mb-1 col-6">
                            <label for="cvc" class="form-label label">CVC</label>
                            <input type="text" class="form-control" id="cvc" name="cvc">
                        </div>
                    </div>
                    <div class="mb-3 row d-flex justify-content-between" style="margin: 0px 1px;">
                        <a href="index.php" class="back-btn col-2 text-decoration-none text-center">Back</a>
                        <input type="submit" class="submit-btn col-2" name="payment" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
      <?php endif; ?>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>