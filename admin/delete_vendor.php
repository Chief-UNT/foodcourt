<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

if(isset($_GET['id'])){

    $vendor_id = $_GET['id'];

    // Delete vendor foods first
    mysqli_query(
        $conn,
        "DELETE FROM menu_items
        WHERE vendor_id='$vendor_id'"
    );

    // Delete vendor orders
    mysqli_query(
        $conn,
        "DELETE FROM orders
        WHERE vendor_id='$vendor_id'"
    );

    // Delete vendor account
    mysqli_query(
        $conn,
        "DELETE FROM vendors
        WHERE id='$vendor_id'"
    );
}

header("Location: vendors.php");
exit();

?>