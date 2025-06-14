<?php


// mengambil fungsi koneksi dari connection
require_once (__DIR__ . "/connection.php");
// require_once (__DIR__ . "/functions.php");4

function createOrder($orderData){
    $connection = getConnection();

    $name = $orderData["custName"];
    $phone = $orderData["phoneNumber"];
    $project = $orderData["projectName"];
    $quantity = $orderData["quantity"];
    $shipping = $orderData["shippingAddress"];
    $description = $orderData["description"];
    $orderDate = date('Y-m-d'); // tanggal saat ini
    $custId = $_SESSION['id'];

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

  if ($result) {
        return true;
    } else {
        return false;
    }
}

function updateUserApproval($approvalData){
    $connection = getConnection();

    $id = $approvalData["id"];
    $approvalInput = isset($approvalData["userApproval"]) ? $approvalData["userApproval"] : null;

    // Konversi nilai approval
    if ($approvalInput === "Approve") {
        $approval = 1;
        $status_id = 1;
    } elseif ($approvalInput === "Reject") {
        $approval = 0;
        $status_id = 6;
    } else {
        // Jika nilai tidak valid, bisa throw error atau return false
        return false;
    }

    $today = date('Y-m-d');
        // Update status approval pada tabel orders
    $query1 = "UPDATE orders SET cust_approve = $approval WHERE id = $id";
    $result1 = $connection->query($query1);

    // Cek apakah sudah ada progress dengan status dan tanggal yang sama
    $checkQuery = "
        SELECT 1 FROM orders_progress 
        WHERE orders_id = $id 
        AND status_id = $status_id 
        AND date = '$today'
        LIMIT 1
    ";
    $checkResult = $connection->query($checkQuery);

    // Jika belum ada, maka insert
    if ($checkResult && $checkResult->num_rows === 0) {
        $query2 = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($id, '$today', $status_id)";
        $connection->query($query2);
    }

    return $result1; // true jika update berhasil, false jika gagal
}

function updateOwnerApproval($approvalData){
    $connection = getConnection();

    $id = $approvalData["id"];
    $approvalInput = isset($approvalData["ownerApproval"]) ? $approvalData["ownerApproval"] : null;
    $price = $approvalData["price"];
    $est_date = $approvalData["estimation"];

    // Konversi nilai approval
    if ($approvalInput === "Approve") {
        $approval = 1;
    } elseif ($approvalInput === "Reject") {
        $approval = 0;
    } else {
        // Jika nilai tidak valid, bisa throw error atau return false
        return false;
    }

        // Update status approval pada tabel orders
    $query = "UPDATE orders SET owner_approve = $approval, price = $price, estimation_date = '$est_date' WHERE id = $id";
    $result = $connection->query($query);

    return $result; // true jika update berhasil, false jika gagal
}

function updateStatusStaff($statusData){
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

    // Jika belum ada, maka insert
    if ($checkResult && $checkResult->num_rows === 0) {
        $query2 = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($id, '$today', $statusId)";
        $connection->query($query2);
    }
}

function createPayment($paymentData){
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