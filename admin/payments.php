<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$query = mysqli_query($conn,"
SELECT * FROM payments
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Payments</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
padding:20px;
}

h2{
color:#E63946;
}

table{
width:100%;
background:white;
border-collapse:collapse;
box-shadow:0 5px 15px rgba(0,0,0,.1);
}

th{
background:#E63946;
color:white;
padding:12px;
}

td{
padding:10px;
text-align:center;
border-bottom:1px solid #ddd;
}

img{
width:120px;
height:120px;
object-fit:cover;
border-radius:10px;
}

.approve{
background:green;
color:white;
padding:8px 12px;
text-decoration:none;
border-radius:5px;
}

.reject{
background:red;
color:white;
padding:8px 12px;
text-decoration:none;
border-radius:5px;
}

</style>

</head>

<body>

<h2>Payment Management</h2>

<table>

<tr>
<th>ID</th>
<th>Order ID</th>
<th>Customer</th>
<th>Amount</th>
<th>Proof</th>
<th>Status</th>
<th>Approve</th>
<th>Reject</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['order_id']; ?></td>

<td><?php echo $row['customer_name']; ?></td>

<td>₦<?php echo number_format($row['amount']); ?></td>

<td>
<img src="../payment_proofs/<?php echo $row['payment_proof']; ?>">
</td>

<td><?php echo $row['payment_status']; ?></td>

<td>
<a class="approve"
href="approve_payment.php?id=<?php echo $row['id']; ?>">
Approve
</a>
</td>

<td>
<a class="reject"
href="reject_payment.php?id=<?php echo $row['id']; ?>">
Reject
</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>