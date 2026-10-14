<?php

session_start();
include '../db.php';

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query(
$conn,
"UPDATE orders
SET status='$status'
WHERE id='$id'"
);

header("Location: orders.php");
exit();

?>