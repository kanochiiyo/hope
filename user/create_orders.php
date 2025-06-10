<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";
require_once __DIR__ . "/../functions/orderFunctions.php";

$connection = getConnection();
$result = false;

if (isset($_POST["createOrder"])) {
  $result = createOrder($_POST);
}

if (!isLogged()) {
  header("Location:/hope/login.php");
}

include __DIR__ . "/../templates/header.php";
?>

<div class="container-fluid d-flex" id="createOrder">
  <?php include(__DIR__ . "/../templates/sidebar.php"); ?>

  <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div class="col-10 order-form-container d-flex justify-content-center align-items-center">
      <!-- Pop-up Start -->
      <div class="popup p-5">
        <a href="index.php">X</a>
        <?php if ($result): ?>
          <div class="order-sucess m-5">
            <img class="mb-4" src="../assets/check_circle.png" alt="Order Completed">
            <h4 class="row-label">Order Created Successfully</h4>
          </div>
        <?php else: ?>
          <div class="order-failed m-5">
            <!-- ga nemu yg gambar X -->
            <img class="mb-4" src="../assets/check_circle.png" alt="Order Failed">
            <h4 class="row-label">Order Creation Failed</h4>
          </div>
        <?php endif; ?>
      </div>
      <!-- Pop-up End -->
    </div>
  <?php else: ?>
    <div class="order-form col-10" id="orderForm">
      <div class="card m-5 p-3 border-0 shadow rounded-4">
        <div class="card-body">
          <div class="header d-flex justify-content-between">
            <h3 class="fw-bold">Create Orders</h3>
          </div>
          <div class="order-form">
            <form method="post" class="border-0">
              <div class="mb-3 row">
                <label for="inputName" class="col-sm-3 col-form-label label">Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="inputName" name="custName" placeholder="Input Name">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="inputPhone" class="col-sm-3 col-form-label label">Phone Number</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control" id="inputPhone" name="phoneNumber"
                    placeholder="Input Phone Number">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="inputProjectName" class="col-sm-3 col-form-label label">Project Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="inputProjectName" name="projectName"
                    placeholder="Input Project Name">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="inputQuantity" class="col-sm-3 col-form-label label">Quantity</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control" id="inputQuantity" name="quantity"
                    placeholder="Input Quantity">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="inputShippingAddres" class="col-sm-3 col-form-label label">Shipping
                  Address</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="inputShippingAddres" name="shippingAddress"
                    placeholder="Input Shpping Address">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="inputDesc" class="col-sm-3 col-form-label label">Desciption</label>
                <div class="col-sm-9">
                  <textarea class="form-control" placeholder="Description" id="inputDesc" name="description"
                    style="height: 100px"></textarea>
                </div>
              </div>

              <div class="mb-3 row d-flex justify-content-between" style="margin: 0px 1px;">
                <a href="index.php" class="back-btn col-2 text-decoration-none text-center">Back</a>
                <input type="submit" class="submit-btn col-2" value="Submit" name="createOrder">
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php
include __DIR__ . "/../templates/footer.php";
?>