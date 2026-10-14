<?php

session_start();

include '../db.php';

$customer = $_SESSION['customer'];

$result = mysqli_query(
$conn,
"SELECT * FROM cart
WHERE customer_name='$customer'"
);

?>

<!DOCTYPE html>
<html>
<head>
<title>My Cart</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

th{
    background:#E63946;
    color:white;
    padding:15px;
}

td{
    padding:15px;
    border-bottom:1px solid #eee;
    text-align:center;
}

.checkout{
    background:#E63946;
    color:white;
    padding:15px 25px;
    text-decoration:none;
    border-radius:10px;
}

</style>

</head>
<body>

<h1>My Cart 🛒</h1>

<table>

<tr>
<th>Food</th>
<th>Price</th>
<th>Quantity</th>
<th>Total</th>
<th>Action</th>
</tr>

<?php

$grand_total = 0;

while($row = mysqli_fetch_assoc($result)){

$grand_total += $row['total'];

?>

<tr>

<td><?php echo $row['food_name']; ?></td>

<td>₦<?php echo $row['price']; ?></td>

<td>

<a href="decrease_cart.php?id=<?php echo $row['id']; ?>">
<button>-</button>
</a>

<?php echo $row['quantity']; ?>

<a href="increase_cart.php?id=<?php echo $row['id']; ?>">
<button>+</button>
</a>

</td>

<td>₦<?php echo $row['total']; ?></td>
<td>

<a href="remove_cart.php?id=<?php echo $row['id']; ?>">

<button
style="
background:red;
color:white;
border:none;
padding:8px 12px;
border-radius:5px;
cursor:pointer;
">

Remove

</button>

</a>

</td>
</tr>

<?php } ?>

</table>
<a href="clear_cart.php">

<button
style="
background:red;
color:white;
border:none;
padding:12px;
border-radius:8px;
cursor:pointer;
">

Clear Cart

</button>

</a>

<br><br>
<h2>

Grand Total:
₦<?php echo $grand_total; ?>

</h2>

<a class="checkout" href="checkout.php">

Checkout

</a>

</body>
</html>