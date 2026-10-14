<?php

session_start();

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

/*
Store customer name for payment page
*/

$_SESSION['checkout_customer'] = $_SESSION['customer'];

header("Location: payment.php");
exit();

?>