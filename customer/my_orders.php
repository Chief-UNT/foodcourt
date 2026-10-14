<?php

session_start();

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$username = $_SESSION['customer'];

$query = "SELECT * FROM orders
          WHERE customer_name='$username'
          ORDER BY id DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>

<style>
.pending{
    background:orange;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}

.preparing{
    background:blue;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}

.delivery{
    background:purple;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}

.delivered{
    background:green;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}
body{
    font-family:Arial;
    background:#f5f5f5;
    padding:20px;
}

h1{
    color:#8b0000;
}

table{
    width:100%;
    background:white;
    border-collapse:collapse;
}

th{
    background:#8b0000;
    color:white;
    padding:12px;
}

td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

</style>

</head>
<body>

<h1>My Orders</h1>

<table>

<tr>
    <th>ID</th>
    <th>Food</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Date</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['food_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₦<?php echo $row['total']; ?></td>

<td><?php echo $row['created_at']; ?></td>

<td>

<?php

if($row['status']=="Pending"){

    echo "<span class='pending'>Pending</span>";

}elseif($row['status']=="Preparing"){

    echo "<span class='preparing'>Preparing</span>";

}elseif($row['status']=="Out For Delivery"){

    echo "<span class='delivery'>Out For Delivery</span>";

}else{

    echo "<span class='delivered'>Delivered</span>";

}

?>
<a href="receipt.php?id=<?php echo $row['id']; ?>">
    Download Receipt
</a>
</td>

<?php } ?>

</table>

</body>
</html>