<?php

// mengambil fungsi koneksi dari connection
require_once(__DIR__ . "/connection.php");

function createOrder($orderData)
{
    error_log("DEBUG: createOrder function called.");
    $connection = getConnection();

    $name = $orderData["custName"];
    $phone = $orderData["phoneNumber"];
    $project = $orderData["projectName"];
    $quantity = $orderData["quantity"];
    $shipping = $orderData["shippingAddress"];
    $description = $orderData["description"];
    $orderDate = date('Y-m-d');
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

    if (!$result) {
        error_log("SQL Error on createOrder INSERT: " . $connection->error);
        return false;
    }

    $order_id = $connection->insert_id;
    $initialStatusId = 2;
    $today = date('Y-m-d');

    $query_progress_initial = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($order_id, '$today', $initialStatusId)";
    $connection->query($query_progress_initial);

    if (!$connection->errno) {
        $connection->query("UPDATE orders SET updated_at = NOW() WHERE id = $order_id");
    }

    return $result;
}

function updateUserApproval($approvalData)
{
    error_log("DEBUG: updateUserApproval function called.");
    $connection = getConnection();

    $id = $approvalData["id"];
    $approvalInput = $approvalData["userApproval"] ?? null;

    if ($approvalInput === "Approve") {
        $approval = 1;
        $status_id_progress = 1;
    } elseif ($approvalInput === "Reject") {
        $approval = 0;
        $status_id_progress = 6;
    } else {
        error_log("ERROR: Invalid user approval input: " . htmlspecialchars($approvalInput));
        return false;
    }

    $today = date('Y-m-d');

    $query1 = "UPDATE orders SET cust_approve = $approval WHERE id = $id";
    $result1 = $connection->query($query1);
    if (!$result1) {
        error_log("SQL Error on updateUserApproval (cust_approve) for ID $id: " . $connection->error);
        return false;
    }

    $checkQuery = "
        SELECT 1 FROM orders_progress 
        WHERE orders_id = $id 
        AND status_id = $status_id_progress 
        AND date = '$today'
        LIMIT 1
    ";
    $checkResult = $connection->query($checkQuery);

    if ($checkResult && $checkResult->num_rows === 0) {
        $query2 = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($id, '$today', $status_id_progress)";
        $connection->query($query2);
        if (!$connection->errno) {
            $connection->query("UPDATE orders SET updated_at = NOW() WHERE id = $id");
        }
    }

    return $result1;
}

function updateOwnerApproval($approvalData)
{
    error_log("DEBUG: updateOwnerApproval function called.");
    $connection = getConnection();

    $id = $approvalData["id"];
    $approvalInput = $approvalData["ownerApproval"] ?? null;
    $price = $approvalData["price"];
    $est_date = $approvalData["estimation"];

    if ($approvalInput === "Approve") {
        $approval = 1;
        $status_id_progress = 1;
    } elseif ($approvalInput === "Reject") {
        $approval = 0;
        $status_id_progress = 6;
    } else {
        error_log("ERROR: Invalid owner approval input: " . htmlspecialchars($approvalInput));
        return false;
    }

    $query = "UPDATE orders SET owner_approve = $approval, price = $price, estimation_date = '$est_date' WHERE id = $id";
    $result = $connection->query($query);
    if (!$result) {
        error_log("SQL Error on updateOwnerApproval (orders) for ID $id: " . $connection->error);
        return false;
    }

    if (isset($status_id_progress)) {
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
            $connection->query($query_progress);
            if (!$connection->errno) {
                $connection->query("UPDATE orders SET updated_at = NOW() WHERE id = $id");
            }
        }
    }

    return $result;
}

function updateStatusStaff($statusData)
{
    error_log("DEBUG: updateStatusStaff function called.");
    $connection = getConnection();

    $id = $statusData["id"];
    $statusInput = strtolower($statusData['status'] ?? 'pending');
    $today = date('Y-m-d');

    $statusMap = [
        'pending' => 2,
        'ongoing' => 3,
        'finishing' => 4,
        'completed' => 5,
    ];
    $statusId = $statusMap[$statusInput] ?? 2;

    $checkQuery = "
        SELECT 1 FROM orders_progress 
        WHERE orders_id = $id 
        AND status_id = $statusId 
        AND date = '$today'
        LIMIT 1
    ";
    $checkResult = $connection->query($checkQuery);

    if ($checkResult && $checkResult->num_rows === 0) {
        $query2 = "INSERT INTO orders_progress (orders_id, date, status_id) VALUES ($id, '$today', $statusId)";
        $connection->query($query2);
        if (!$connection->errno) {
            $connection->query("UPDATE orders SET updated_at = NOW() WHERE id = $id");
        }
    }

    return true;
}

function createPayment($paymentData)
{
    error_log("DEBUG: createPayment function called.");
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

    $query = "
        INSERT INTO payment (
            address_line, city, state, postal_code, card_name, card_number, expirity, cvc, payment_method, orders_id
        ) VALUES (
            '$address', '$city', '$state', '$postal', '$card_name', '$card_number', '$expirity', '$cvc', '$payment_method', $order_id
        )
    ";

    $result = $connection->query($query);

    if (!$result) {
        error_log("SQL Error on createPayment INSERT: " . $connection->error);
        return false;
    }

    return true;
}
