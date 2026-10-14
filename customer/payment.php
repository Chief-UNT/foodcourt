<?php

session_start();
include '../db.php';

if (!isset($_SESSION['checkout_customer'])) {
    header("Location: login.php");
    exit();
}

$customer = $_SESSION['checkout_customer'];

$customer_safe = mysqli_real_escape_string($conn, $customer);

/*
Get customer's cart
*/
$cart = mysqli_query(
    $conn,
    "SELECT * FROM cart
     WHERE customer_name='$customer_safe'"
);

if (!$cart || mysqli_num_rows($cart) == 0) {
    die("Your cart is empty.");
}

/*
Calculate total
*/
$grand_total = 0;

while ($item = mysqli_fetch_assoc($cart)) {
    $grand_total += (float)$item['total'];
}

/*
Paystack uses kobo
*/
$amount_kobo = (int) round($grand_total * 100);

?>

<!DOCTYPE html>
<html>

<head>

<title>Payment - Moor Food Court</title>

<script src="https://js.paystack.co/v2/inline.js"></script>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f5f5f5;

    display:flex;
    justify-content:center;
    align-items:center;

    min-height:100vh;
}

.box{
    background:white;
    width:420px;
    max-width:92%;

    padding:30px;

    border-radius:15px;

    box-shadow:0 5px 20px rgba(0,0,0,.1);
}

h2{
    text-align:center;
    color:#E63946;
    margin-top:0;
}

.description{
    text-align:center;
    color:#666;
    font-size:14px;
    margin-bottom:25px;
}

.amount{
    background:#fff3f3;
    padding:18px;

    border-radius:10px;

    text-align:center;

    margin-bottom:25px;
}

.amount small{
    display:block;
    color:#666;
    margin-bottom:7px;
}

.amount strong{
    color:#E63946;
    font-size:28px;
}

label{
    display:block;
    font-weight:bold;
    margin-bottom:7px;
    color:#333;
}

input{
    width:100%;

    padding:12px;

    border:1px solid #ccc;
    border-radius:8px;

    font-size:15px;

    margin-bottom:17px;
}

button{
    width:100%;

    padding:14px;

    border:none;
    border-radius:8px;

    background:#E63946;
    color:white;

    font-size:16px;
    font-weight:bold;

    cursor:pointer;
}

button:hover{
    background:#c92f3b;
}

.note{
    margin-top:18px;

    padding:12px;

    background:#f8f8f8;

    border-radius:8px;

    font-size:13px;
    color:#666;

    line-height:1.5;
}

</style>

</head>

<body>

<div class="box">

<h2>Payment</h2>

<p class="description">
Complete your order securely with Paystack.
</p>


<div class="amount">

<small>Total Amount</small>

<strong>
₦<?php echo number_format($grand_total, 2); ?>
</strong>

</div>


<label>Email Address</label>

<input
    type="email"
    id="email"
    placeholder="Enter your email"
    required
>


<label>Phone Number</label>

<input
    type="text"
    id="phone"
    placeholder="08012345678"
    required
>


<label>Delivery Address</label>

<input
    type="text"
    id="address"
    placeholder="Enter your delivery address"
    required
>


<button type="button" onclick="payWithPaystack()">

Pay Now

</button>


<div class="note">

You will be taken to Paystack's secure payment
window to complete your payment.

</div>

</div>


<script>

function payWithPaystack(){

    const email =
        document.getElementById("email").value.trim();

    const phone =
        document.getElementById("phone").value.trim();

    const address =
        document.getElementById("address").value.trim();


    if(email === ""){

        alert("Please enter your email address.");

        return;
    }


    if(phone === ""){

        alert("Please enter your phone number.");

        return;
    }


    if(address === ""){

        alert("Please enter your delivery address.");

        return;
    }


    const popup = new PaystackPop();


    popup.newTransaction({

        /*
        Your Paystack TEST PUBLIC KEY
        */

        key: "pk_test_68eb1099287831a22909f91d121db9197b2dcba8",

        email: email,

        phone: phone,

        amount: <?php echo $amount_kobo; ?>,

        currency: "NGN",

        channels: [
            "card",
            "bank",
            "ussd",
            "bank_transfer"
        ],


        onSuccess: function(transaction){

            /*
            Send payment information
            to process_payment.php
            */

            window.location.href =
                "process_payment.php"
                + "?reference="
                + encodeURIComponent(transaction.reference)
                + "&email="
                + encodeURIComponent(email)
                + "&phone="
                + encodeURIComponent(phone)
                + "&address="
                + encodeURIComponent(address);

        },


        onCancel: function(){

            alert("Payment was cancelled.");

        },


        onError: function(error){

            alert(
                "Payment error: "
                + error.message
            );

        }

    });

}

</script>

</body>

</html>