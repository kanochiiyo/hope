<?php
session_start();

require_once(__DIR__ . "/functions/authentication.php");

$connection = getConnection();

if (!isLogged()) {
  header("Location:login.php");
}

include(__DIR__ . "/templates/header.php");
?>

<div class="container-fluid d-flex" id="userPage">
  <?php include(__DIR__ . "/templates/sidebar.php"); ?>

  <div class="main-content col-10">
    <div class="card m-5 border-0" style="width: 61rem;">
      <div class="card-body">
        <div class="table-responsive">
          <h3>All Orders</h3>
          <input type="text" class="form-control mb-3" placeholder="Search">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Project Name</th>
                <th>Quantity</th>
                <th>Order Date</th>
                <th>Est. Date</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>93232</td>
                <td>Pintu Gerbang</td>
                <td>1</td>
                <td>12/12/2024</td>
                <td>1/3/2025</td>
                <td>25000000</td>
                <td><button class="btn btn-primary">Confirm</button></td>
              </tr>
              <tr>
                <td>93232</td>
                <td>Pintu Gerbang</td>
                <td>1</td>
                <td>12/12/2024</td>
                <td>1/3/2025</td>
                <td>25000000</td>
                <td><button class="btn btn-primary">Confirm</button></td>
              </tr>
            </tbody>
          </table>
          <div>
            <p>Showing data 1 to 2 of 256K entries</p>
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>