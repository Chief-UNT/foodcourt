<?php

session_start();

if(!isset($_SESSION['vendor_id'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$vendor_id = (int)$_SESSION['vendor_id'];

$id = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

$status = $_GET['status'] ?? '';

/*
Allowed order statuses
*/
$allowed_statuses = [
    'Preparing',
    'On The Way',
    'Delivered'
];

/*
Check order ID
*/
if($id <= 0){
    die("Invalid order ID.");
}

/*
Check requested status
*/
if(!in_array($status, $allowed_statuses, true)){
    die("Invalid order status.");
}

/*
Update only an order belonging
to the logged-in vendor
*/
$status_safe = mysqli_real_escape_string(
    $conn,
    $status
);

$update = mysqli_query(
    $conn,

    "UPDATE orders
     SET status='$status_safe'
     WHERE id='$id'
     AND vendor_id='$vendor_id'"
);

if(!$update){
    die(
        "Unable to update order status: "
        . mysqli_error($conn)
    );
}

/*
Return to vendor orders
*/
header("Location: orders.php");
exit();

?>