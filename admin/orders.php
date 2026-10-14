<?php

include '../db.php';

$pending_orders = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM orders WHERE status='Pending'")
);

$preparing_orders = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM orders WHERE status='Preparing'")
);

$delivery_orders = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM orders WHERE status='Out For Delivery'")
);

$delivered_orders = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM orders WHERE status='Delivered'")
);

$result = mysqli_query(
$conn,
"SELECT * FROM orders ORDER BY id DESC"
);

?><!DOCTYPE html><html>
<head><title>Manage Orders</title><style>

body{
font-family:Arial;
background:#f5f5f5;
padding:20px;
}

h1{
color:#E63946;
margin-bottom:20px;
}

.back-btn{
display:inline-block;
background:#E63946;
color:white;
text-decoration:none;
padding:10px 15px;
border-radius:8px;
margin-bottom:20px;
}

.stats{
display:flex;
gap:15px;
flex-wrap:wrap;
margin-bottom:25px;
}

.card{
background:white;
width:200px;
padding:20px;
border-radius:10px;
text-align:center;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.card h2{
color:#E63946;
margin:0;
}

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:15px;
overflow:hidden;
box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

table th{
background:#E63946;
color:white;
padding:15px;
}

table td{
padding:12px;
text-align:center;
border-bottom:1px solid #eee;
}

table tr:hover{
background:#fafafa;
}

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

.paid{
background:green;
color:white;
padding:5px 10px;
border-radius:5px;
}

.unpaid{
background:red;
color:white;
padding:5px 10px;
border-radius:5px;
}

.btn{
background:#E63946;
color:white;
text-decoration:none;
padding:8px 12px;
border-radius:6px;
display:inline-block;
margin:2px;
}

.btn:hover{
background:#d62839;
}

</style></head><body><a href="dashboard.php" class="back-btn">
← Back To Dashboard
</a><h1>Manage Orders</h1><div class="stats"><div class="card">
<h2><?php echo $pending_orders; ?></h2>
<p>Pending</p>
</div><div class="card">
<h2><?php echo $preparing_orders; ?></h2>
<p>Preparing</p>
</div><div class="card">
<h2><?php echo $delivery_orders; ?></h2>
<p>Out For Delivery</p>
</div><div class="card">
<h2><?php echo $delivered_orders; ?></h2>
<p>Delivered</p>
</div></div><table><tr><th>ID</th>
<th>Food</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
<th>Customer</th>
<th>Phone</th>
<th>Address</th>
<th>Order Status</th>
<th>Payment Method</th>
<th>Payment Status</th>
<th>Action</th></tr><?php while($row = mysqli_fetch_assoc($result)){ ?><tr><td><?php echo $row['id']; ?></td><td><?php echo $row['food_name']; ?></td><td>₦<?php echo number_format($row['price']); ?></td><td><?php echo $row['quantity']; ?></td><td>₦<?php echo number_format($row['total']); ?></td><td><?php echo $row['customer_name']; ?></td><td><?php echo $row['phone']; ?></td><td><?php echo $row['address']; ?></td><td><?php

if($row['status']=="Pending"){

echo "<span class='pending'>Pending</span>";

}elseif($row['status']=="Preparing"){

echo "<span class='preparing'>Preparing</span>";

}elseif($row['status']=="Out For Delivery"){

echo "<span class='delivery'>Out For Delivery</span>";

}else{

echo "<span class='delivered'>Delivered</span>";

}

?></td><td><?php echo $row['payment_method']; ?></td><td><?php

if($row['payment_status']=="Paid"){

echo "<span class='paid'>Paid</span>";

}else{

echo "<span class='unpaid'>Pending</span>";

}

?></td><td><?php

if($row['status']=="Pending"){

?><a class="btn"
href="update_status.php?id=<?php echo $row['id']; ?>">
Start Preparing
</a>

<?php

}elseif($row['status']=="Preparing"){

?><a class="btn"
href="update_status.php?id=<?php echo $row['id']; ?>">
Send For Delivery
</a>

<?php

}elseif($row['status']=="Out For Delivery"){

?><a class="btn"
href="update_status.php?id=<?php echo $row['id']; ?>">
Mark Delivered
</a>

<?php

}else{

echo "✅ Completed";

}

?><br><br>

<a class="btn"
href="delete_order.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this order?')">
Delete
</a>

</td></tr><?php } ?></table></body>
</html>