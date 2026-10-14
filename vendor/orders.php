<?php

session_start();

if(!isset($_SESSION['vendor_id'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$vendor_id = $_SESSION['vendor_id'];

$result = mysqli_query(
$conn,
"SELECT * FROM orders
WHERE vendor_id='$vendor_id'
ORDER BY id DESC"
);

?><!DOCTYPE html><html>
<head>
<title>Vendor Orders</title><style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:20px;
}

h1{
    color:#E63946;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th{
    background:#E63946;
    color:white;
    padding:12px;
}

td{
    padding:10px;
    border:1px solid #ddd;
}

.status{
    font-weight:bold;
}

</style></head>
<body><h1>Customer Orders</h1><table><tr>
<th>ID</th>
<th>Customer</th>
<th>Food</th>
<th>Quantity</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr><?php while($row=mysqli_fetch_assoc($result)){ ?><tr><td><?php echo $row['id']; ?></td><td><?php echo $row['customer_name']; ?></td><td><?php echo $row['food_name']; ?></td><td><?php echo $row['quantity']; ?></td><td>₦<?php echo $row['total']; ?></td>
<td class="status">
<?php echo $row['status']; ?>
</td>

<td>

<a href="update_status.php?id=<?php echo $row['id']; ?>&status=Preparing">
Preparing
</a>

|

<a href="update_status.php?id=<?php echo $row['id']; ?>&status=On The Way">
On The Way
</a>

|

<a href="update_status.php?id=<?php echo $row['id']; ?>&status=Delivered">
Delivered
</a>

</td><?php echo $row['status']; ?>
</td>
<td>
    <a href="update_order.php?id=<?php echo $row['id']; ?>">Update</a>
</td></tr><?php } ?></table></body>
</html>