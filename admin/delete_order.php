<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$id = $_GET['id'];

mysqli_query(
$conn,
"DELETE FROM orders WHERE id='$id'"
);

header("Location: orders.php");

exit();

?>