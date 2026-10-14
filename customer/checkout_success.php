<?php

session_start();
include '../db.php';

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

$customer = $_SESSION['customer'];

$get_order = mysqli_query(
$conn,
"SELECT * FROM orders
WHERE customer_name='$customer'
ORDER BY id DESC
LIMIT 1"
);

$order = mysqli_fetch_assoc($get_order);

$order_id = $order['id'];

?>

<!DOCTYPE html>
<html>
<head>

<title>Payment Details</title>

<style>

body{
display:flex;
justify-content:center;
align-items:center;
height:100vh;
font-family:Arial;
background:#f5f5f5;
}

.box{
background:white;
padding:40px;
border-radius:20px;
text-align:center;
box-shadow:0 10px 30px rgba(0,0,0,.1);
width:500px;
}

h1{
color:green;
}

.account{
background:#f8f8f8;
padding:15px;
margin:20px 0;
border-radius:10px;
line-height:2;
}

.btn{
display:inline-block;
background:#E63946;
color:white;
padding:12px 20px;
text-decoration:none;
border-radius:10px;
margin-top:10px;
border:none;
cursor:pointer;
font-size:16px;
}

.btn:hover{
background:#c92d3a;
}

</style>

</head>

<body>

<div class="box">

<h1>✅ Order Placed Successfully</h1>

<p>
Thank you for ordering from Moor Food Court.
</p>

<h3>Payment Information</h3>

<div class="account">

<strong>Bank Name:</strong> Opay<br>

<strong>Account Name:</strong> Moor Food Court<br>

<strong>Account Number:</strong> 1234567890

</div>

<a href="receipt.php?id=<?php echo $order_id; ?>">

<button class="btn">

View Receipt

</button>

</a>

</div>

</body>
</html>