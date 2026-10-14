<?php

session_start();

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$id = (int)$_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM menu_items WHERE id='$id'"
);

if(!$query || mysqli_num_rows($query) == 0){
    die("Food item not found.");
}

$row = mysqli_fetch_assoc($query);

$customer = mysqli_real_escape_string(
    $conn,
    $_SESSION['customer']
);

$food_name = mysqli_real_escape_string(
    $conn,
    $row['food_name']
);

$price = (float)$row['price'];

/*
Get vendor ID from the food item
*/
$vendor_id = isset($row['vendor_id'])
    ? (int)$row['vendor_id']
    : 0;

if($vendor_id <= 0){

    die(
        "Unable to add food to cart: "
        . "Vendor information is missing."
    );

}

$quantity = 1;

$total = $price;


/*
Add food to cart
*/
$insert = mysqli_query(
    $conn,

    "INSERT INTO cart
    (
        customer_name,
        vendor_id,
        food_name,
        price,
        quantity,
        total
    )

    VALUES
    (
        '$customer',
        '$vendor_id',
        '$food_name',
        '$price',
        '$quantity',
        '$total'
    )"
);


if(!$insert){

    die(
        "Unable to add item to cart: "
        . mysqli_error($conn)
    );

}


$_SESSION['success'] =
    $row['food_name']
    . " added to cart successfully!";


header("Location: ../index.php");

exit();

?>