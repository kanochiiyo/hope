<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";

$connection = getConnection();

if (!isLogged()) {
  header("Location:/hope/login.php");
}

include __DIR__ . "/../templates/header.php";
// include __DIR__ . "/../templates/modal.php";
?>

<div class="container-fluid d-flex" id="adminPage">
  <?php include __DIR__ . "/../templates/sidebar.php"; ?>
  <div class="main-content col-10" id="mainContent">
    <div class="dashboard-content w-100 p-5">
      <div class="row mb-4">
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow rounded-4 p-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-muted mb-0">Total Order</h6>
                <div class="icon-box-sm rounded d-flex align-items-center justify-content-center"
                  style="background-color: #e6e6fa;"> <i data-lucide="package" style="color: #4318ff;"></i> </div>
              </div>
              <h3 class="fw-bold mb-1">10293</h3>
              <p class="mb-0">
                <span class="text-success me-1 d-inline-flex align-items-center">
                  <i data-lucide="trending-up" class="icon-small"></i> 1.3%
                </span>
                <span class="text-muted small">Up from past week</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow rounded-4 p-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-muted mb-0">Total Sales</h6>
                <div class="icon-box-sm rounded d-flex align-items-center justify-content-center"
                  style="background-color: #e6fae6;"> <i data-lucide="line-chart" style="color: #28a745;"></i> </div>
              </div>
              <h3 class="fw-bold mb-1">$89,000</h3>
              <p class="mb-0">
                <span class="text-danger me-1 d-inline-flex align-items-center">
                  <i data-lucide="trending-down" class="icon-small"></i> 4.3%
                </span>
                <span class="text-muted small">Down from yesterday</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow rounded-4 p-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-muted mb-0">Total Pending</h6>
                <div class="icon-box-sm rounded d-flex align-items-center justify-content-center"
                  style="background-color: #fae6e6;"> <i data-lucide="clock" style="color: #fd7e14;"></i> </div>
              </div>
              <h3 class="fw-bold mb-1">2040</h3>
              <p class="mb-0">
                <span class="text-success me-1 d-inline-flex align-items-center">
                  <i data-lucide="trending-up" class="icon-small"></i> 1.8%
                </span>
                <span class="text-muted small">Up from yesterday</span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow rounded-4 p-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Total Spent</h5>
            <div class="icon-box-sm rounded d-flex align-items-center justify-content-center"
              style="background-color: #e6e6fa;"> <i data-lucide="bar-chart-2" style="color: #4318ff;"></i> </div>
          </div>
          <div class="chart-container" style="position: relative; height: 200px;">
            <div class="chart-mockup"
              style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
              <div
                style="position: absolute; bottom: 0; left: 0; right: 0; height: 100%; display: flex; align-items: flex-end; justify-content: space-around; padding: 0 15px;">
                <div style="width: 35px; background-color: #e0e0e0; height: 40%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 55%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 30%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 60%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #4318ff; height: 80%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 50%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 70%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 45%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 35%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 65%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 50%; border-radius: 8px;"></div>
                <div style="width: 35px; background-color: #e0e0e0; height: 75%; border-radius: 8px;"></div>
              </div>
              <div style="position: absolute; left: 0; right: 0; top: 25%; border-top: 3px dashed #4318FF;"></div>
              <div
                style="position: absolute; right: 10px; top: 25%; transform: translateY(-50%); font-size: 0.9em; color: #6c757d; background-color: white; padding: 0 5px;">
                $179</div>
            </div>
          </div>
          <div class="d-flex justify-content-between text-muted small mt-2 px-2">
            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>