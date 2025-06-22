<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";
require_once __DIR__ . "/../functions/orderFunctions.php";
require_once __DIR__ . "/../functions/pagination_helper.php";

$connection = getConnection();
$user_id = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $result = updateUserApproval($_POST);
}

// ============================== PAGINATION LOGIC ==============================
$pagination_unconfirmed = getPaginationParams('page_unconfirmed', 4);
$page_unconfirmed = $pagination_unconfirmed['currentPage'];
$limit_unconfirmed = $pagination_unconfirmed['limit'];
$offset_unconfirmed = $pagination_unconfirmed['offset'];
$total_unconfirmed_query = "SELECT COUNT(*) AS total FROM orders WHERE cust_approve IS NULL AND cust_id = $user_id;";
$total_unconfirmed_result = $connection->query($total_unconfirmed_query);
$total_unconfirmed_rows = $total_unconfirmed_result->fetch_assoc()['total'];
$total_unconfirmed_pages = calculateTotalPages($total_unconfirmed_rows, $limit_unconfirmed);

$pagination_all_orders = getPaginationParams('page_all_orders', 4);
$page_all_orders = $pagination_all_orders['currentPage'];
$limit_all_orders = $pagination_all_orders['limit'];
$offset_all_orders = $pagination_all_orders['offset'];
$total_all_orders_query = "SELECT COUNT(*) AS total FROM orders WHERE cust_approve IS NOT NULL AND cust_id = $user_id;";
$total_all_orders_result = $connection->query($total_all_orders_query);
$total_all_orders_rows = $total_all_orders_result->fetch_assoc()['total'];
$total_all_orders_pages = calculateTotalPages($total_all_orders_rows, $limit_all_orders);

$query = "SELECT * FROM orders WHERE cust_approve is NULL AND cust_id = $user_id ORDER BY order_date DESC, id DESC LIMIT $limit_unconfirmed OFFSET $offset_unconfirmed;";
$uncomfirmed_orders = $connection->query($query);

$q = "
SELECT o.*, latest_status.name AS status_name
FROM orders o
LEFT JOIN (
    SELECT op.orders_id, s.name
    FROM orders_progress op
    JOIN status s ON op.status_id = s.id
    WHERE (op.orders_id, op.date, op.status_id) IN (
        SELECT op2.orders_id, op2.date, MAX(op2.status_id)
        FROM orders_progress op2
        WHERE (op2.date) = (
            SELECT MAX(op3.date)
            FROM orders_progress op3
            WHERE op3.orders_id = op2.orders_id
        )
        GROUP BY op2.orders_id, op2.date
    )
) AS latest_status ON o.id = latest_status.orders_id
WHERE o.cust_approve is not NULL
AND o.cust_id = $user_id
ORDER BY o.updated_at DESC, o.id DESC 
LIMIT $limit_all_orders OFFSET $offset_all_orders;
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
            <h3 class="fw-bold">Unconfirmed Orders</h3>
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
                    <?php
                    $is_est_date_empty = empty($row['estimation_date']);
                    $is_price_empty = empty($row['price']);

                    if ($is_est_date_empty && $is_price_empty):
                      ?>
                      <td class="text-center" colspan="3">Waiting for Approval</td>
                    <?php else: ?>
                      <td class="text-center">
                        <?= $is_est_date_empty ? '-' : date('d/m/Y', strtotime($row['estimation_date'])) ?>
                      </td>
                      <td class="text-center">
                        <?= $is_price_empty ? '-' : 'Rp ' . number_format($row['price'], 0, ',', '.') ?>
                      </td>
                      <td class="text-center">
                        <button type="button" class="action-btn confirm-button" data-id="<?= $row["id"] ?>"
                          data-bs-toggle="modal" data-bs-target="#userModal">
                          Confirm
                        </button>
                      </td>
                    <?php endif; ?>

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
        <div class="d-flex justify-content-between align-items-center mt-3">
          <p class="text-muted">Showing data <?= min($offset_unconfirmed + 1, $total_unconfirmed_rows) ?> to
            <?= min($offset_unconfirmed + $limit_unconfirmed, $total_unconfirmed_rows) ?> of
            <?= $total_unconfirmed_rows ?> entries
          </p>
          <?php
          $otherParamsForUnconfirmed = ['page_all_orders' => $page_all_orders];
          echo generatePaginationHtml($page_unconfirmed, $total_unconfirmed_pages, 'page_unconfirmed', $otherParamsForUnconfirmed, 'Pagination for unconfirmed orders');
          ?>
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
                      <p class="status-<?= strtolower(str_replace(' ', '', $row['status_name'] ?? 'pending')) ?>">
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
        <div class="d-flex justify-content-between align-items-center mt-3">
          <p class="text-muted">Showing data <?= min($offset_all_orders + 1, $total_all_orders_rows) ?> to
            <?= min($offset_all_orders + $limit_all_orders, $total_all_orders_rows) ?> of <?= $total_all_orders_rows ?>
            entries
          </p>
          <?php
          $otherParamsForAllOrders = ['page_unconfirmed' => $page_unconfirmed];
          echo generatePaginationHtml($page_all_orders, $total_all_orders_pages, 'page_all_orders', $otherParamsForAllOrders, 'Pagination for all orders');
          ?>
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

  for (const button of buttons) {
    button.addEventListener("click", () => {
      approvalIdInput.value = button.dataset.id;
    })
  }

  userApprovalForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const form = e.target;
    const isApprove = (e.target.elements["userApproval"].value == "Approve") ? true : false;
    if (!isApprove) {
      form.action = "/hope/user/index.php";
    }

    form.submit();
  })
</script>