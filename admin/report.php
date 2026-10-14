<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$total_orders = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM orders")
);

$pending_orders = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM orders WHERE status='Pending'")
);

$delivered_orders = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM orders WHERE status='Delivered'")
);

$revenue_query = mysqli_query(
    $conn,
    "SELECT SUM(total) AS revenue
     FROM orders
     WHERE status='Delivered'"
);

$revenue_row = mysqli_fetch_assoc($revenue_query);

$total_revenue = $revenue_row['revenue'];

if($total_revenue==""){
    $total_revenue = 0;
}

$today_query = mysqli_query(
$conn,
"SELECT SUM(total) AS today_revenue
FROM orders
WHERE status='Delivered'
AND DATE(created_at)=CURDATE()"
);

$today_row = mysqli_fetch_assoc($today_query);

$today_revenue = $today_row['today_revenue'];

if($today_revenue==""){
$today_revenue = 0;
}

$week_query = mysqli_query(
$conn,
"SELECT SUM(total) AS week_revenue
FROM orders
WHERE status='Delivered'
AND YEARWEEK(created_at,1)=YEARWEEK(CURDATE(),1)"
);

$week_row = mysqli_fetch_assoc($week_query);

$week_revenue = $week_row['week_revenue'];

if($week_revenue==""){
$week_revenue = 0;
}

$sales_query = mysqli_query($conn,"
SELECT food_name,
SUM(quantity) AS total_sold,
SUM(total) AS revenue
FROM orders
WHERE status='Delivered'
GROUP BY food_name
ORDER BY total_sold DESC");

$orders_query = mysqli_query($conn,"
SELECT *
FROM orders
WHERE status='Delivered'
ORDER BY id DESC");

?><!DOCTYPE html><html>
<head><title>Sales Report</title><style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:30px;
}

h1{
    text-align:center;
    color:#8b0000;
}

h2{
    color:#8b0000;
}

.card h2{
    color:white;
}

.cards{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    justify-content:center;
}

.card{
    background:#8b0000;
    color:white;
    width:220px;
    padding:25px;
    border-radius:12px;
    text-align:center;
}

.card h2{
    font-size:35px;
    color:#fff;
    margin-bottom:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

table th{
    background:#8b0000;
    color:white;
    padding:12px;
}

table td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

.btn{
    display:block;
    width:200px;
    margin:30px auto;
    text-align:center;
    background:#8b0000;
    color:white;
    text-decoration:none;
    padding:12px;
    border-radius:8px;
}

.paid{
    background:green;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}

.pending{
    background:red;
    color:white;
    padding:5px 10px;
    border-radius:5px;
}

@media print{
    .btn{
        display:none;
    }
}

</style></head><body><h1>Sales Report</h1><div class="cards"><div class="card">
<h2><?php echo $total_orders; ?></h2>
<p>Total Orders</p>
</div><div class="card">
<h2><?php echo $pending_orders; ?></h2>
<p>Pending Orders</p>
</div><div class="card">
<h2><?php echo $delivered_orders; ?></h2>
<p>Delivered Orders</p>
</div><div class="card">
<h2>₦<?php echo number_format($total_revenue); ?></h2>
<p>Total Revenue</p>
</div><div class="card">
<h2>₦<?php echo number_format($today_revenue); ?></h2>
<p>Today's Revenue</p>
</div><div class="card">
<h2>₦<?php echo number_format($week_revenue); ?></h2>
<p>Weekly Revenue</p>
</div></div><br><h2>Revenue By Food Item</h2><table><tr>
    <th>Food Item</th>
    <th>Quantity Sold</th>
    <th>Revenue</th>
</tr><?php while($sale = mysqli_fetch_assoc($sales_query)){ ?><tr><td><?php echo $sale['food_name']; ?></td><td><?php echo $sale['total_sold']; ?></td><td>₦<?php echo number_format($sale['revenue']); ?></td></tr><?php } ?></table><br><br>

<h2>Delivered Orders</h2><table><tr>
    <th>ID</th>
    <th>Food</th>
    <th>Customer</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Payment Method</th>
    <th>Payment Status</th>
</tr><?php while($order = mysqli_fetch_assoc($orders_query)){ ?><tr><td><?php echo $order['id']; ?></td><td><?php echo $order['food_name']; ?></td><td><?php echo $order['customer_name']; ?></td><td><?php echo $order['quantity']; ?></td><td>₦<?php echo number_format($order['total']); ?></td><td><?php echo $order['payment_method']; ?></td><td><?php

if($order['payment_status']=="Paid"){

echo "<span class='paid'>Paid</span>";

}else{

echo "<span class='pending'>Pending</span>";

}

?></td></tr><?php } ?></table><a href="#" onclick="window.print()" class="btn">
Print Report
</a></body>
</html>