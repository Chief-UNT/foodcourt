<?php

include '../db.php';


/*
Check receipt ID
*/
if (!isset($_GET['id']) || empty($_GET['id'])) {

    die("Invalid Receipt");

}


$id = (int)$_GET['id'];


/*
Get the order used for the receipt
*/
$query = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     WHERE id='$id'
     LIMIT 1"
);


if (!$query || mysqli_num_rows($query) == 0) {

    die("Receipt Not Found");

}


$first_order = mysqli_fetch_assoc($query);


/*
Get the Paystack reference
*/
$payment_reference = $first_order['payment_reference'];


/*
Get all items belonging to this payment
*/
$reference_safe = mysqli_real_escape_string(
    $conn,
    $payment_reference
);


$orders = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     WHERE payment_reference='$reference_safe'
     ORDER BY id ASC"
);


if (!$orders || mysqli_num_rows($orders) == 0) {

    die("Order Items Not Found");

}


/*
Calculate complete order total
*/
$grand_total = 0;

$order_items = [];


while ($item = mysqli_fetch_assoc($orders)) {

    $order_items[] = $item;

    $grand_total += (float)$item['total'];

}


/*
Use information from the first order
*/
$customer = $first_order['customer_name'];
$phone = $first_order['phone'];
$address = $first_order['address'];
$payment_method = $first_order['payment_method'];
$payment_status = $first_order['payment_status'];
$status = $first_order['status'];
$created_at = $first_order['created_at'];

?>

<!DOCTYPE html>
<html>

<head>

<title>Order Receipt - Moor Food Court</title>


<style>

@media print{

    .btn{
        display:none;
    }

}


*{
    box-sizing:border-box;
}


body{

    font-family:Arial, sans-serif;

    background:#f5f5f5;

    padding:40px;

}


.receipt{

    max-width:700px;

    margin:auto;

    background:white;

    padding:30px;

    border-radius:15px;

    box-shadow:0 0 15px rgba(0,0,0,0.15);

}


.receipt-header{

    text-align:center;

    margin-bottom:20px;

}


.receipt-header h1{

    color:#8b0000;

    margin-bottom:5px;

}


.receipt-header p{

    color:#666;

    font-size:14px;

}


.receipt-info{

    background:#f8f8f8;

    padding:15px;

    border-radius:10px;

    margin-bottom:20px;

}


.receipt-info p{

    margin:8px 0;

}


table{

    width:100%;

    border-collapse:collapse;

    margin-top:15px;

}


th{

    background:#8b0000;

    color:white;

    padding:12px;

    text-align:left;

}


td{

    padding:12px;

    border-bottom:1px solid #ddd;

}


.total-row{

    font-size:22px;

    font-weight:bold;

    color:#8b0000;

}


.total-row td{

    border-bottom:none;

    padding-top:20px;

}


.customer-info{

    background:#f8f8f8;

    padding:15px;

    border-radius:10px;

    margin-top:20px;

}


.customer-info p{

    margin:8px 0;

}


.status{

    color:orange;

    font-weight:bold;

}


.paid{

    color:green;

    font-weight:bold;

}


.pending{

    color:red;

    font-weight:bold;

}


.btn{

    display:block;

    text-align:center;

    background:#8b0000;

    color:white;

    text-decoration:none;

    padding:12px;

    border-radius:5px;

    margin-top:15px;

}


.btn:hover{

    background:#650000;

}


.thank-you{

    text-align:center;

    margin-top:25px;

    color:#666;

}

</style>

</head>


<body>


<div class="receipt">


<div class="receipt-header">

    <h1>MOOR FOOD COURT RECEIPT</h1>

    <p>Thank you for ordering with us</p>

</div>


<div class="receipt-info">

    <p>
        <strong>Order ID:</strong>
        <?php echo $first_order['id']; ?>
    </p>

    <p>
        <strong>Payment Reference:</strong>
        <?php echo htmlspecialchars($payment_reference); ?>
    </p>

    <p>
        <strong>Date:</strong>
        <?php echo htmlspecialchars($created_at); ?>
    </p>

</div>


<hr>


<h3>Order Items</h3>


<table>

<thead>

<tr>

    <th>Food</th>

    <th>Price</th>

    <th>Qty</th>

    <th>Total</th>

</tr>

</thead>


<tbody>

<?php foreach ($order_items as $item): ?>

<tr>

    <td>
        <?php echo htmlspecialchars($item['food_name']); ?>
    </td>

    <td>
        ₦<?php echo number_format($item['price'], 2); ?>
    </td>

    <td>
        <?php echo $item['quantity']; ?>
    </td>

    <td>
        ₦<?php echo number_format($item['total'], 2); ?>
    </td>

</tr>

<?php endforeach; ?>


<tr class="total-row">

    <td colspan="3">
        Grand Total
    </td>

    <td>
        ₦<?php echo number_format($grand_total, 2); ?>
    </td>

</tr>

</tbody>

</table>


<hr>


<div class="customer-info">


<p>

<strong>Customer:</strong>

<?php echo htmlspecialchars($customer); ?>

</p>


<p>

<strong>Phone:</strong>

<?php

echo !empty($phone)

    ? htmlspecialchars($phone)

    : 'Not Provided';

?>

</p>


<p>

<strong>Address:</strong>

<?php

echo !empty($address)

    ? htmlspecialchars($address)

    : 'Not Provided';

?>

</p>


<p>

<strong>Payment Method:</strong>

<?php echo htmlspecialchars($payment_method); ?>

</p>


<p>

<strong>Payment Status:</strong>

<?php

if ($payment_status == 'Paid') {

    echo "<span class='paid'>Paid</span>";

} else {

    echo "<span class='pending'>Pending</span>";

}

?>

</p>


<p>

<strong>Order Status:</strong>

<span class="status">

<?php echo htmlspecialchars($status); ?>

</span>

</p>


</div>


<p class="thank-you">

Thank you for ordering from Moor Food Court.

</p>


<a
    href="#"
    onclick="window.print()"
    class="btn"
>
    Print Receipt
</a>


<a
    href="download_receipt.php?id=<?php echo $first_order['id']; ?>"
    class="btn"
>
    Download PDF Receipt
</a>


<a
    href="../index.php"
    class="btn"
>
    Back To Menu
</a>


</div>


</body>

</html>