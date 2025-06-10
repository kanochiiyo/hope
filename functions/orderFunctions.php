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
            qty, shipping_address, owner_approve, cust_approve, cust_id
        ) VALUES (
            '$project', '$phone', '$description', NULL, '$orderDate',
            NULL, NULL,
            $quantity, '$shipping', 0, 0, $custId
        )
    ";

    $result = $connection->query($query);

  if ($result) {
        return true;
    } else {
        return false;
    }
    


}

