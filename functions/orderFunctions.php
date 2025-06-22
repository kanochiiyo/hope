<?php

// mengambil fungsi koneksi dari connection
require_once(__DIR__ . "/connection.php");

function createOrder($orderData)
{
    $connection = getConnection();

    $name = $orderData["custName"];
    $phone = $orderData["phoneNumber"];
    $project = $orderData["projectName"];
    $quantity = $orderData["quantity"];
    $shipping = $orderData["shippingAddress"];
    $description = $orderData["description"];
    $orderDate = date('Y-m-d'); // tanggal saat ini
    $custId = $_SESSION['id'];

    // Kolom 'current_status_id' dihapus dari sini karena tidak ada lagi di tabel 'orders'
    $query = "
        INSERT INTO orders (
            name, phone_number, deskripsi, price, order_date, estimation_date, end_date,
            qty, shipping_address, owner_approve, cust_approve, cust_id, cust_name
        ) VALUES (
            '$project', '$phone', '$description', NULL, '$orderDate',
            NULL, NULL,
            $quantity, '$shipping', NULL, NULL, $custId, '$name'
        )
    ";

    $result = $connection->query($query);

    // Setelah membuat order, tambahkan entri pertama ke orders_progress
    // Biasanya status awal adalah Pending (ID 2), sesuaikan jika berbeda
    if ($result) {
        $order_id = $connection->insert_id; // Ambil ID order yang baru saja di-insert
        $initialStatusId = 2; // ID untuk status 'Pending'
        $today = date('Y-m-d');
        $query_progress_initial = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($order_id, '$today', $initialStatusId)";
        $connection->query($query_progress_initial);
    }

    return $result;
}

function updateUserApproval($approvalData)
{
    $connection = getConnection();

    $id = $approvalData["id"];
    $approvalInput = isset($approvalData["userApproval"]) ? $approvalData["userApproval"] : null;

    // Konversi nilai approval
    if ($approvalInput === "Approve") {
        $approval = 1;
        $status_id_progress = 1; // ID Status: Approved
    } elseif ($approvalInput === "Reject") {
        $approval = 0;
        $status_id_progress = 6; // ID Status: Rejected
    } else {
        return false;
    }

    $today = date('Y-m-d');

    // Update cust_approve pada tabel orders
    // Ini akan memicu updated_at di tabel orders jika nilai $approval berubah
    $query1 = "UPDATE orders SET cust_approve = $approval WHERE id = $id";
    $result1 = $connection->query($query1);

    // Cek apakah sudah ada progress dengan status dan tanggal yang sama
    $checkQuery = "
        SELECT 1 FROM orders_progress 
        WHERE orders_id = $id 
        AND status_id = $status_id_progress 
        AND date = '$today'
        LIMIT 1
    ";
    $checkResult = $connection->query($checkQuery);

    // Jika belum ada, maka insert ke orders_progress
    if ($checkResult && $checkResult->num_rows === 0) {
        $query2 = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($id, '$today', $status_id_progress)";
        $connection->query($query2); // Trigger database akan memicu updated_at di tabel orders
    }

    return $result1;
}

function updateOwnerApproval($approvalData)
{
    $connection = getConnection();

    $id = $approvalData["id"];
    $approvalInput = isset($approvalData["ownerApproval"]) ? $approvalData["ownerApproval"] : null;
    $price = $approvalData["price"];
    $est_date = $approvalData["estimation"];

    $status_id_progress = null;
    if ($approvalInput === "Approve") {
        $approval = 1;
        // PENTING: Ketika Owner Approve, statusnya menjadi 'Approved' (ID 1)
        $status_id_progress = 1;
    } elseif ($approvalInput === "Reject") {
        $approval = 0;
        $status_id_progress = 6; // Status Rejected
    } else {
        return false;
    }

    // Update owner_approve, price, estimation_date pada tabel orders
    $query = "UPDATE orders SET owner_approve = $approval, price = $price, estimation_date = '$est_date' WHERE id = $id";
    $result = $connection->query($query);

    // Jika owner approve/reject, masukkan juga ke orders_progress
    // Ini penting agar status 'Approved' atau 'Rejected' tercatat di history trace
    // dan memicu updated_at di tabel orders melalui trigger database.
    if ($status_id_progress !== null) {
        $today = date('Y-m-d');
        $checkQuery = "
            SELECT 1 FROM orders_progress 
            WHERE orders_id = $id 
            AND status_id = $status_id_progress 
            AND date = '$today'
            LIMIT 1
        ";
        $checkResult = $connection->query($checkQuery);

        if ($checkResult && $checkResult->num_rows === 0) {
            $query_progress = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($id, '$today', $status_id_progress)";
            $connection->query($query_progress); // Trigger database akan memicu updated_at di tabel orders
        }
    }

    return $result;
}

function updateStatusStaff($statusData)
{
    $connection = getConnection();

    $id = $statusData["id"];
    $statusInput = $statusData['status'] ?? 'pending'; // default ke pending jika kosong
    $today = date('Y-m-d');


    switch (strtolower($statusInput)) {
        case 'pending':
            $statusId = 2;
            break;
        case 'ongoing':
            $statusId = 3;
            break;
        case 'finishing':
            $statusId = 4;
            break;
        case 'completed':
            $statusId = 5;
            break;
        default:
            $statusId = 2; // fallback default ke 'pending'
    }

    // Cek apakah sudah ada progress dengan status dan tanggal yang sama
    $checkQuery = "
        SELECT 1 FROM orders_progress 
        WHERE orders_id = $id 
        AND status_id = $statusId 
        AND date = '$today'
        LIMIT 1
    ";
    $checkResult = $connection->query($checkQuery);

    // Jika belum ada, maka insert ke orders_progress
    if ($checkResult && $checkResult->num_rows === 0) {
        $query2 = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($id, '$today', $statusId)";
        $connection->query($query2); // Trigger database akan memicu updated_at di tabel orders
    }

    return true;
}

function createPayment($paymentData)
{
    $connection = getConnection();

    $address = $paymentData["address"];
    $city = $paymentData["city"];
    $state = $paymentData["state"];
    $postal = $paymentData["postal"];
    $payment_method = $paymentData["payment_method"];
    $card_name = $paymentData["card_name"];
    $card_number = $paymentData["card_number"];
    $expirity = $paymentData["expirity"];
    $cvc = $paymentData["cvc"];
    $order_id = $paymentData["id"];

    // Query insert
    $query = "INSERT INTO payment 
            (address_line, city, state, postal_code, card_name, card_number, expirity, cvc, payment_method, orders_id)
        VALUES 
            ('$address', '$city', '$state', '$postal', '$card_name', '$card_number', '$expirity', '$cvc', '$payment_method', $order_id)
    ";

    $result = $connection->query($query);

    if ($result) {
        return true;
    } else {
        return false;
    }
}