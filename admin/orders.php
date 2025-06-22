<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";
require_once __DIR__ . "/../functions/orderFunctions.php";
require_once __DIR__ . "/../functions/pagination_helper.php";

$connection = getConnection();

if (!isLogged()) {
  header("Location:/hope/login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $result = updateOwnerApproval($_POST);
}

// ============================== PAGINATION LOGIC ==============================
$limit_admin_orders = 4; // Tampilkan 4 baris per halaman
$page_admin_orders = isset($_GET['page_admin_orders']) ? (int) $_GET['page_admin_orders'] : 1;
$page_admin_orders = max(1, $page_admin_orders);
$offset_admin_orders = ($page_admin_orders - 1) * $limit_admin_orders;
$total_admin_orders_query = "SELECT COUNT(*) AS total FROM orders WHERE owner_approve IS NOT NULL;";
$total_admin_orders_result = $connection->query($total_admin_orders_query);
$total_admin_orders_rows = $total_admin_orders_result->fetch_assoc()['total'];
$total_admin_orders_pages = calculateTotalPages($total_admin_orders_rows, $limit_admin_orders);

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
WHERE o.owner_approve IS NOT NULL
ORDER BY o.updated_at DESC, o.id DESC 
LIMIT $limit_admin_orders OFFSET $offset_admin_orders;
";
$orders = $connection->query($q);

include __DIR__ . "/../templates/header.php";
?>

<div class="container-fluid d-flex" id="adminOrders">
  <?php include __DIR__ . "/../templates/sidebar.php"; ?>
  <div class="main-content col-10" id="mainContent">
    <div class="header mx-5 mt-5 p-3 d-flex justify-content-between align-items-center">
      <div class="header-title">
        <h3>Hi, Admin!</h3>
        <h1 class="fw-bold">Welcome to Website!</h1>
      </div>
      <div class="export-btn d-flex justify-content-between align-items-center">
        <div class="download-btn">
          <form action="/hope/functions/download_reports.php" method="GET">
            <input type="hidden" name="format" value="xlsx">
            <input type="submit" class="submit-btn" value="Download Excel Report">
          </form>
        </div>
        <div class="download-btn ps-3">
          <form action="/hope/functions/download_reports.php" method="GET">
            <input type="hidden" name="format" value="csv">
            <input type="submit" class="submit-btn" value="Download CSV Report">
          </form>
        </div>
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
                <td class="text-muted text-center">Status</td>
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
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['order_date'])) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['qty']) ?></td>
                    <td class="text-center">
                      <?= $row['price'] ? 'Rp ' . number_format($row['price'], 0, ',', '.') : '-' ?>
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
                  <td class="text-center" colspan="8">Tidak ada data order.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <hr>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
          <p class="text-muted">Showing data <?= min($offset_admin_orders + 1, $total_admin_orders_rows) ?> to
            <?= min($offset_admin_orders + $limit_admin_orders, $total_admin_orders_rows) ?> of
            <?= $total_admin_orders_rows ?> entries
          </p>
          <?php echo generatePaginationHtml($page_admin_orders, $total_admin_orders_pages, 'page_admin_orders', [], 'Pagination for admin orders');
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>