<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";
require_once __DIR__ . "/../functions/orderFunctions.php";

$connection = getConnection();
$user_id = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $result = updateUserApproval($_POST);
}

$query = "SELECT * FROM orders WHERE cust_approve is NULL AND cust_id = $user_id;";
$uncomfirmed_orders = $connection->query($query);

$q = "
SELECT o.*, latest_status.name AS status_name
FROM orders o
LEFT JOIN (
    SELECT op.orders_id, st.name
    FROM orders_progress op
    JOIN status st ON op.status_id = st.id
    WHERE op.date = (
        SELECT MAX(op2.date)
        FROM orders_progress op2
        WHERE op2.orders_id = op.orders_id
    )
) AS latest_status ON o.id = latest_status.orders_id
WHERE o.cust_approve is not NULL
AND o.cust_id = $user_id;
";
$orders = $connection->query($q);

if (!isLogged()) {
  header("Location:/hope/login.php");
}



include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../templates/modal.php";
?>

<div class="container-fluid d-flex" id="userPage">
  <?php include __DIR__ . "/../templates/sidebar.php"; ?>

  <div class="main-content col-10" id="mainContent">
    <div class="card m-5 p-3 border-0 shadow rounded-4">
      <div class="card-body">
        <div class="table-responsive orderTable">
          <div class="header d-flex justify-content-between">
            <h3 class="fw-bold">All Orders</h3>
          </div>
          <table class="table table-borderless">
            <thead>
              <tr>
                <td class="text-muted text-center">Order ID</td>
                <td class="text-muted text-center">Project Name</td>
                <td class="text-muted text-center">Quantity</td>
                <td class="text-muted text-center">Order Date</td>
                <td class="text-muted text-center">Est. Date</td>
                <td class="text-muted text-center">Price</td>
                <td class="text-muted text-center">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($uncomfirmed_orders && $uncomfirmed_orders->num_rows > 0): ?>
                <?php while ($row = $uncomfirmed_orders->fetch_assoc()): ?>
                  <tr>
                    <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['qty']) ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['order_date'])) ?></td>
                    <td class="text-center">
                      <?= $row['estimation_date'] ? date('d/m/Y', strtotime($row['estimation_date'])) : '-' ?>
                    </td>
                    <td class="text-center">
                      <?= $row['price'] ? 'Rp ' . number_format($row['price'], 0, ',', '.') : '-' ?>
                    </td>
                    <td class="text-center">
                      <button type="button" class="action-btn confirm-button" data-id="<?= $row["id"] ?>" data-bs-toggle="modal" data-bs-target="#userModal">
                        Confirm
                      </button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td class="text-center" colspan="7">Tidak ada data order.</td>
                </tr>
              <?php endif; ?>
            </tbody>
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

    <div class="card m-5 p-3 border-0 shadow rounded-4">
      <div class="card-body">
        <div class="table-responsive orderTable">
          <div class="header d-flex justify-content-between">
            <h3 class="fw-bold">All Orders</h3>
          </div>
          <table class="table table-borderless">
            <thead>
              <tr>
                <td class="text-muted text-center">Order ID</td>
                <td class="text-muted text-center">Project Name</td>
                <td class="text-muted text-center">Quantity</td>
                <td class="text-muted text-center">Order Date</td>
                <td class="text-muted text-center">Est. Date</td>
                <td class="text-muted text-center">Status</td>
                <td class="text-muted text-center">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders && $orders->num_rows > 0): ?>
                <?php while ($row = $orders->fetch_assoc()): ?>
                  <tr>
                    <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['qty']) ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['order_date'])) ?></td>
                    <td class="text-center">
                      <?= $row['estimation_date'] ? date('d/m/Y', strtotime($row['estimation_date'])) : '-' ?>
                    </td>
                    <td class="text-center align-middle">
                      <p class="status-<?= strtolower(str_replace(' ', '-', $row['status_name'] ?? 'pending')) ?>">
                        <?= htmlspecialchars($row['status_name'] ?? 'Pending') ?>
                      </p>
                    </td>
                    <td class="text-center align-middle">
                      <a class="action-btn text-decoration-none" href="detail_orders.php?id=<?= $row['id'] ?>">
                       <span class="text-reset">Detail</span> 
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td class="text-center" colspan="7">Tidak ada data order.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <hr>
        </div>
        <div class="d-flex justify-content-between pagination align-items-center">
          <p class="text-muted orderTable">Showing data 1 to 2 of 256K entries</p>
          <nav aria-label="Page navigation">
            <ul class="pagination">
              <li class="page-item mx-1"><a class="page-link" href="#">&lt;</a></li>
              <li class="page-item mx-1"><a class="page-link" href="#">1</a></li>
              <li class="page-item mx-1 active"><a class="page-link" href="#">2</a></li>
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

<script>
  const buttons = document.querySelectorAll(".confirm-button");
  const approvalIdInput = document.querySelector("#approval-id-input");
  const userApprovalForm = document.querySelector("#userApprovalForm");

  for(const button of buttons) {
    button.addEventListener("click", () => {
      approvalIdInput.value = button.dataset.id;
    })
  }

  userApprovalForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const form = e.target;
    const isApprove = (e.target.elements["userApproval"].value == "Approve") ? true : false;
    if(!isApprove) {
      form.action = "/hope/user/index.php";
    }
    
    form.submit();
  })
</script>