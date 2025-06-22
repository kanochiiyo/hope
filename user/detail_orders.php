<?php
session_start();

require_once __DIR__ . "/../functions/authentication.php";
require_once __DIR__ . "/../functions/functions.php";

$connection = getConnection();

if (!isLogged()) {
    header("Location:/hope/login.php");
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $orderId = (int) $_GET['id']; // Amankan dari SQL Injection

    // Query untuk mengambil detail order dan user-nya
    $orderQuery = "
        SELECT o.*, u.username AS customer_name
        FROM orders o
        LEFT JOIN users u ON o.cust_id = u.id
        WHERE o.id = $orderId
    ";
    $orderResult = $connection->query($orderQuery);

    // Query untuk mengambil riwayat status order
    $progressQuery = "
        SELECT op.date, s.name AS status_name, s.id status_id
        FROM orders_progress op
        JOIN status s ON op.status_id = s.id
        WHERE op.orders_id = $orderId
        ORDER BY op.date ASC
    ";
    $progressResult = $connection->query($progressQuery);

    // Validasi apakah order ditemukan
    if ($orderResult && $orderResult->num_rows > 0) {
        $order = $orderResult->fetch_assoc();
        // Gunakan $order dan $progressResult sesuai kebutuhan
    } else {
        echo "<script>alert('Order tidak ditemukan'); window.location.href='index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID order tidak valid'); window.location.href='index.php';</script>";
    exit;
}

include __DIR__ . "/../templates/header.php";
?>

<div class="container-fluid d-flex" id="detailOrders">
    <?php include(__DIR__ . "/../templates/sidebar.php"); ?>

    <div class="main-content col-10" id="mainContent">
        <!-- Tabel Konfirmasi Proyek -->
        <div class="card m-5 p-3 border-0 shadow rounded-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        <div class="table-responsive detailOrderTable">
                            <div class="header">
                                <h3 class="fw-bold">Detail Orders #<?=$order['id']?></h3>
                            </div>
                            <table class="table table-borderless">
                            <tbody>
                                 <tr>
                                <td class="row-label" style="width: 250px">Customer Name</td>
                                <td><?= htmlspecialchars($order['cust_name']) ?></td>
                                </tr>
                                <tr>
                                <td class="row-label" style="width: 250px">Project Name</td>
                                <td><?= htmlspecialchars($order['name']) ?></td>
                                </tr>
                                <tr>
                                <td class="row-label" style="width: 250px">Quantity</td>
                                <td><?= htmlspecialchars($order['qty']) ?></td>
                                </tr>
                                <tr>
                                <td class="row-label" style="width: 250px">Order Date</td>
                                <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
                                </tr>
                                <tr>
                                <td class="row-label" style="width: 250px">Est. Completion Date</td>
                                <td>
                                    <?= $order['estimation_date'] ? date('d/m/Y', strtotime($order['estimation_date'])) : '-' ?>
                                </td>
                                </tr>
                                <tr>
                                <td class="row-label" style="width: 250px">Price</td>
                                <td>
                                    <?= $order['price'] ? 'Rp ' . number_format($order['price'], 0, ',', '.') : '-' ?>
                                </td>
                                </tr>
                                <tr>
                                <td class="row-label" style="width: 250px">Shipping Address</td>
                                <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                                </tr>
                                <tr>
                                <td class="row-label" style="width: 250px">Description</td>
                                <td><?= nl2br(htmlspecialchars($order['deskripsi'])) ?></td>
                                </tr>
                            </tbody>
                            </table>
                            <div class="mb-3 row d-flex justify-content-between" style="margin: 0px 1px;">
                                <a href="index.php" class="back-btn col-2 text-decoration-none text-center">Back</a>
                            </div>

                        </div>
                    </div>
                    <div class="col-3" style="background: #FAFBFF; border: 1px solid #5038ED; border-radius: 16px;">
                        <p class="row-label mt-4">Status Projek</p>
                        <div class="position-relative w-full h-full" style="height: 100%;">
                            <div class="position-absolute"
                                style="width:1px; background: black;min-height: 300px;left: 100px;"></div>
            
                                <!-- <div class="position-absolute d-flex align-items-center" style="top: <?= $i * 50; ?>px">
                                    <time style="width:95px">03-27</time>
                                    <div class="bg-success position-absolute"
                                        style="width: 12px;height: 12px; border-radius: 50%; border: 1px solid black; left: 94px;">
                                    </div>
                                    <span style="margin-left: 50px;">Project Approved</span>
                                </div> -->

                            <?php
                          $statusColors = [
                            1 => '#00EDB6', // approved
                            2 => '#DF0404', // pending
                            3 => '#FD7E14', // on going
                            4 => '#1466FD', // finishing
                            5 => '#00AC4F', // completed
                        ];

                        $i = 0;
                        while ($progress = $progressResult->fetch_assoc()):
                            $date = date('m-d', strtotime($progress['date']));
                            $statusName = htmlspecialchars($progress['status_name']);
                            $statusId = (int) $progress['status_id']; // pastikan `status_id` ikut di-query dari DB
                            $color = $statusColors[$statusId] ?? '#ccc'; // fallback abu-abu jika tidak ditemukan
                        ?>
                        <div class="position-absolute d-flex align-items-center" style="top: <?= $i * 65; ?>px">
                            <time style="width:95px"><?= $date ?></time>
                            <div class="position-absolute"
                                style="width: 12px; height: 12px; border-radius: 50%; border: 1px solid black; left: 94px; background-color: <?= $color ?>;">
                            </div>
                            <span style="margin-left: 50px;">Project <?= $statusName ?></span>
                        </div>
                        <?php
                        $i++;
                        endwhile;
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>