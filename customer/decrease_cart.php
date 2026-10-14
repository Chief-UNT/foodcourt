<?php

include '../db.php';

$id = $_GET['id'];

$item = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM cart WHERE id='$id'")
);

$new_quantity = $item['quantity'] - 1;

if($new_quantity < 1){
    $new_quantity = 1;
}

$new_total = $item['price'] * $new_quantity;

mysqli_query(
$conn,
"UPDATE cart
SET quantity='$new_quantity',
total='$new_total'
WHERE id='$id'"
);

header("Location: cart.php");
exit();

?>