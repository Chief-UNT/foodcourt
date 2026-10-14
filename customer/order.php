<?php

session_start();

if(!isset($_SESSION['customer'])){

    header("Location: login.php");
    exit();
}

include '../db.php';

$id = $_GET['id'];

$query = "SELECT * FROM menu_items WHERE id='$id'";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){

    $food_name = $row['food_name'];
    $price = $row['price'];

    $customer_name = $_SESSION['customer'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $quantity = $_POST['quantity'];

    $total = $price * $quantity;

    $status = "Pending";

    $insert = "INSERT INTO orders
    (food_name, price, quantity, total,
    customer_name, phone, address, status)

    VALUES

    ('$food_name','$price','$quantity','$total',
    '$customer_name','$phone','$address','$status')";

    mysqli_query($conn, $insert);

    $order_id = mysqli_insert_id($conn);

    header("Location: receipt.php?id=".$order_id);
    exit();

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Food</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            background:#f5f5f5;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
        }

        .order-box{
            width:400px;
            background:white;
            border-radius:15px;
            overflow:hidden;
            box-shadow:0 5px 15px rgba(0,0,0,0.2);
        }

        .order-box img{
            width:100%;
            height:250px;
            object-fit:cover;
        }

        .content{
            padding:20px;
        }

        h1{
            margin-bottom:10px;
            color:#8b0000;
        }

        .price{
            font-size:20px;
            margin-bottom:20px;
            font-weight:bold;
        }

        label{
            display:block;
            margin-top:10px;
            margin-bottom:5px;
            font-weight:bold;
        }

        input, textarea{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:8px;
        }

        textarea{
            height:80px;
        }

        button{
            width:100%;
            padding:14px;
            background:#8b0000;
            color:white;
            border:none;
            border-radius:8px;
            margin-top:20px;
            font-size:16px;
            cursor:pointer;
        }

        button:hover{
            background:red;
        }

    </style>

</head>
<body>

<div class="order-box">

   <img src="../images/<?php echo $row['image']; ?>">

    <div class="content">

        <h1><?php echo $row['food_name']; ?></h1>

        <p class="price">
            ₦<?php echo $row['price']; ?>
        </p>

        <form method="POST">

            <label>Customer Name</label>
            <input
type="text"
name="customer_name"
value="<?php echo $_SESSION['customer']; ?>"
readonly>

            <label>Phone Number</label>
            <input type="text" name="phone" required>

            <label>Address</label>
            <textarea name="address" required></textarea>

            <label>Quantity</label>
            <input type="number" name="quantity" required>

            <button type="submit" name="submit">
                Place Order
            </button>

        </form>

    </div>

</div>

</body>
</html>