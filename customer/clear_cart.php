<?php

session_start();

include '../db.php';

$customer = $_SESSION['customer'];

mysqli_query(
$conn,
"DELETE FROM cart
WHERE customer_name='$customer'"
);

header("Location: cart.php");
exit();

?>