<?php

include '../db.php';

$total_transactions = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM orders
        WHERE payment_status='Paid'"
    )
);

$revenue_query = mysqli_query(
    $conn,
    "SELECT SUM(total) AS revenue
    FROM orders
    WHERE payment_status='Paid'"
);

$revenue_row = mysqli_fetch_assoc(
    $revenue_query
);

$total_revenue = $revenue_row['revenue'];

if($total_revenue==""){
    $total_revenue = 0;
}

$result = mysqli_query(
$conn,
"SELECT * FROM orders
WHERE payment_status='Paid'
ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>

<title>Payment History</title>

<style>
.summary{
display:flex;
gap:20px;
margin-bottom:25px;
}

.card{
background:white;
padding:20px;
width:250px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
text-align:center;
}

.card h2{
color:#E63946;
margin:0;
}

body{
font-family:Arial;
background:#f5f5f5;
padding:20px;
}

h1{
color:#E63946;
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

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th{
background:#E63946;
color:white;
padding:15px;
}

td{
padding:12px;
border-bottom:1px solid #eee;
text-align:center;
}

.paid{
background:green;
color:white;
padding:5px 10px;
border-radius:5px;
}

</style>

</head>

<body>

<a href="dashboard.php" class="back-btn">
← Back To Dashboard
</a>

<h1>Payment History</h1>

<div class="summary">

<div class="card">
<h2><?php echo $total_transactions; ?></h2>
<p>Total Paid Transactions</p>
</div>

<div class="card">
<h2>₦<?php echo number_format($total_revenue); ?></h2>
<p>Total Payment Revenue</p>
</div>

</div>

<table>

<tr>
<th>ID</th>
<th>Customer</th>
<th>Food</th>
<th>Amount</th>
<th>Payment Method</th>
<th>Payment Status</th>
<th>Date</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['customer_name']; ?></td>

<td><?php echo $row['food_name']; ?></td>

<td>₦<?php echo number_format($row['total']); ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td>
<span class="paid">
<?php echo $row['payment_status']; ?>
</span>
</td>

<td><?php echo $row['created_at']; ?></td>

</tr>

<?php } ?>

</table>

</body>
</html>