<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";
require_once __DIR__ . "/../functions/pagination_helper.php";

$connection = getConnection();

if (!isLogged()) {
  header("Location:/hope/login.php");
}

// ============================== PAGINATION LOGIC ==============================
$limit_req_orders = 4;
$page_req_orders = isset($_GET['page_req_orders']) ? (int) $_GET['page_req_orders'] : 1;
$page_req_orders = max(1, $page_req_orders);
$offset_req_orders = ($page_req_orders - 1) * $limit_req_orders;
$total_req_orders_query = "SELECT COUNT(*) AS total FROM orders WHERE owner_approve IS NULL;";
$total_req_orders_result = $connection->query($total_req_orders_query);
$total_req_orders_rows = $total_req_orders_result->fetch_assoc()['total'];
$total_req_orders_pages = calculateTotalPages($total_req_orders_rows, $limit_req_orders);

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
WHERE o.owner_approve is NULL
ORDER BY o.updated_at DESC, o.id DESC 
LIMIT $limit_req_orders OFFSET $offset_req_orders;
";
$orders = $connection->query($q);

include __DIR__ . "/../templates/header.php";
include __DIR__ . "/../templates/modal.php";
?>

<div class="container-fluid d-flex" id="reqOrders">
  <?php include __DIR__ . "/../templates/sidebar.php"; ?>
  <div class="main-content col-10" id="mainContent">
    <div class="header mx-5 mt-5 p-3 d-flex justify-content-between align-items-center">
      <div class="header-title">
        <h3>Hi, Admin!</h3>
        <h1 class="fw-bold">Welcome to Website!</h1>
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
              <?php if ($orders && $orders->num_rows > 0): ?>
                <?php while ($row = $orders->fetch_assoc()): ?>
                  <tr>
                    <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['cust_name']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['qty']) ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['order_date'])) ?></td>
                    <td class="text-center">
                      <?= $row['price'] ? 'Rp ' . number_format($row['price'], 0, ',', '.') : '-' ?>
                    </td>
                    <td class="text-center align-middle">
                      <button type="button" class="action-btn update-btn" data-bs-toggle="modal" data-id="<?= $row["id"] ?>"
                        data-bs-target="#ownerModal">
                        Update
                      </button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td class="text-center" colspan="8">Tidak ada data order.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <hr>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
          <p class="text-muted">Showing data <?= min($offset_req_orders + 1, $total_req_orders_rows) ?> to
            <?= min($offset_req_orders + $limit_req_orders, $total_req_orders_rows) ?> of
            <?= $total_req_orders_rows ?> entries
          </p>
          <?php echo generatePaginationHtml($page_req_orders, $total_req_orders_pages, 'page_req_orders', [], 'Pagination for requested orders');
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
  const buttons = document.querySelectorAll(".update-btn");
  const approvalIdInput = document.querySelector("#owner-id-input");

  for (const button of buttons) {
    button.addEventListener("click", () => {
      approvalIdInput.value = button.dataset.id;
    })
  }
</script>