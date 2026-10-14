<?php

include '../db.php';

if(isset($_POST['submit_payment'])){

    $order_id = $_POST['order_id'];
    $customer_name = $_POST['customer_name'];
    $amount = $_POST['amount'];

    $proof = $_FILES['payment_proof']['name'];
    $tmp = $_FILES['payment_proof']['tmp_name'];

    move_uploaded_file(
        $tmp,
        "../payment_proofs/".$proof
    );

    mysqli_query($conn,"
    INSERT INTO payments
    (
        order_id,
        customer_name,
        amount,
        payment_proof
    )
    VALUES
    (
        '$order_id',
        '$customer_name',
        '$amount',
        '$proof'
    )
    ");

    echo "<script>
    alert('Payment Proof Uploaded Successfully');
    window.location='my_orders.php';
    </script>";
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Upload Payment</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
}

.box{
width:500px;
margin:50px auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.1);
}

input{
width:100%;
padding:12px;
margin-top:10px;
margin-bottom:15px;
}

button{
background:#E63946;
color:white;
border:none;
padding:12px;
width:100%;
border-radius:10px;
cursor:pointer;
}

</style>

</head>

<body>

<div class="box">

<h2>Upload Payment Proof</h2>

<form method="POST" enctype="multipart/form-data">

<input
type="number"
name="order_id"
placeholder="Order ID"
required>

<input
type="text"
name="customer_name"
placeholder="Customer Name"
required>

<input
type="number"
name="amount"
placeholder="Amount Paid"
required>

<input
type="file"
name="payment_proof"
required>

<button
type="submit"
name="submit_payment">

Upload Payment

</button>

</form>

</div>

</body>
</html>