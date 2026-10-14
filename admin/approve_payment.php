<?php

include '../db.php';

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE payments
SET payment_status='Approved'
WHERE id='$id'
");

header("Location: payments.php");
exit();

?>