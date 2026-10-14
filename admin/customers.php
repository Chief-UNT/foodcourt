<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$customers = mysqli_query(
$conn,
"SELECT * FROM users ORDER BY id DESC"
);

?><!DOCTYPE html><html>
<head>
<title>Manage Customers</title><style>

body{
    font-family:Arial,sans-serif;
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

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
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

tr:hover{
    background:#fafafa;
}

.btn{
    background:red;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

.btn:hover{
    background:darkred;
}

.orders{
    color:#E63946;
    font-weight:bold;
}

.amount{
    color:green;
    font-weight:bold;
}

</style></head>
<body><a href="dashboard.php" class="back-btn">
← Back To Dashboard
</a><h1>Manage Customers</h1><table><tr>
    <th>ID</th>
    <th>Username</th>
    <th>Total Orders</th>
    <th>Total Spent</th>
    <th>Action</th>
</tr><?php while($row = mysqli_fetch_assoc($customers)){ ?><?php

$order_count = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM orders
        WHERE customer_name='".$row['username']."'"
    )
);

$spent_query = mysqli_query(
    $conn,
    "SELECT SUM(total) AS spent
    FROM orders
    WHERE customer_name='".$row['username']."'
    AND status='Delivered'"
);

$spent_row = mysqli_fetch_assoc($spent_query);

$total_spent = $spent_row['spent'];

if($total_spent==""){
    $total_spent = 0;
}

?><tr><td><?php echo $row['id']; ?></td><td><?php echo $row['username']; ?></td><td class="orders">
<?php echo $order_count; ?>
</td><td class="amount">
₦<?php echo number_format($total_spent); ?>
</td><td><a href="delete_customer.php?id=<?php echo $row['id']; ?>"
class="btn"
onclick="return confirm('Delete this customer?')">

Delete

</a></td></tr><?php } ?></table></body>
</html>