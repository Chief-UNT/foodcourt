<?php

session_start();
include '../db.php';


/*
Check customer session
*/
if (!isset($_SESSION['checkout_customer'])) {

    header("Location: login.php");
    exit();

}

$customer = $_SESSION['checkout_customer'];


/*
Get payment information from Paystack
*/
$reference = $_GET['reference'] ?? '';
$email     = $_GET['email'] ?? '';
$phone     = $_GET['phone'] ?? '';
$address   = $_GET['address'] ?? '';


/*
Check required information
*/
if (
    empty($reference) ||
    empty($email) ||
    empty($phone) ||
    empty($address)
) {

    die("Payment information is incomplete.");

}


/*
Protect values
*/
$customer_safe = mysqli_real_escape_string(
    $conn,
    $customer
);

$reference_safe = mysqli_real_escape_string(
    $conn,
    $reference
);

$phone_safe = mysqli_real_escape_string(
    $conn,
    $phone
);

$address_safe = mysqli_real_escape_string(
    $conn,
    $address
);


/*
Get customer's cart
*/
$cart = mysqli_query(
    $conn,
    "SELECT *
     FROM cart
     WHERE customer_name='$customer_safe'"
);


if (!$cart || mysqli_num_rows($cart) == 0) {

    die("Your cart is empty.");

}


/*
Store latest order ID
*/
$latest_order_id = 0;


/*
Create an order record
for every food item
*/
while ($item = mysqli_fetch_assoc($cart)) {

    /*
    Get cart information
    */
    $food_name = mysqli_real_escape_string(
        $conn,
        $item['food_name']
    );

    $price = (float)$item['price'];

    $quantity = (int)$item['quantity'];

    $total = (float)$item['total'];


    /*
    Get vendor ID from cart
    */
    $vendor_id = isset($item['vendor_id'])
        ? (int)$item['vendor_id']
        : 0;


    /*
    Make sure vendor ID exists
    */
    if ($vendor_id <= 0) {

        die(
            "Unable to create order: "
            . "Vendor information is missing for "
            . htmlspecialchars($food_name)
        );

    }


    /*
    Insert order
    */
    $insert_order = mysqli_query(
        $conn,

        "INSERT INTO orders
        (
            food_name,
            price,
            quantity,
            total,
            customer_name,
            phone,
            address,
            status,
            payment_method,
            payment_reference,
            payment_status,
            vendor_id
        )

        VALUES
        (
            '$food_name',
            '$price',
            '$quantity',
            '$total',
            '$customer_safe',
            '$phone_safe',
            '$address_safe',
            'Pending',
            'Paystack Test',
            '$reference_safe',
            'Paid',
            '$vendor_id'
        )"
    );


    /*
    Check if order was created
    */
    if (!$insert_order) {

        die(
            "Unable to create order: "
            . mysqli_error($conn)
        );

    }


    /*
    Get this order ID
    */
    $latest_order_id = mysqli_insert_id($conn);

}


/*
Clear cart only after
successful order creation
*/
if ($latest_order_id > 0) {

    mysqli_query(
        $conn,

        "DELETE FROM cart
         WHERE customer_name='$customer_safe'"
    );

}


/*
Redirect to receipt
*/
header(
    "Location: receipt.php?id="
    . $latest_order_id
);

exit();

?>