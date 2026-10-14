<?php

include '../db.php';

$result = "";

if(isset($_POST['search'])){

    $phone = mysqli_real_escape_string(
        $conn,
        $_POST['phone']
    );

    $query = "
        SELECT *
        FROM orders
        WHERE phone='$phone'
        ORDER BY id DESC
    ";

    $result = mysqli_query($conn, $query);
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Track Order</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:30px;
}

.container{
    max-width:900px;
    margin:auto;
}

h1{
    text-align:center;
    color:#8b0000;
}

form{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:15px;
    box-sizing:border-box;
}

button{
    background:#8b0000;
    color:white;
    border:none;
    padding:12px 20px;
    cursor:pointer;
    border-radius:5px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th{
    background:#8b0000;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

.status{
    font-weight:bold;
}

.pending{
    color:orange;
}

.preparing{
    color:#e67e22;
}

.on-the-way{
    color:#2980b9;
}

.delivered{
    color:green;
}

</style>

</head>

<body>

<div class="container">

<h1>Track Your Order</h1>

<form method="POST">

<input
    type="text"
    name="phone"
    placeholder="Enter Phone Number"
    required
>

<button
    type="submit"
    name="search"
>
    Track Order
</button>

</form>


<?php

if($result){

    if(mysqli_num_rows($result) > 0){

?>

<table>

<tr>

<th>ID</th>
<th>Food</th>
<th>Total</th>
<th>Status</th>
<th>Date</th>

</tr>

<?php

while($row = mysqli_fetch_assoc($result)){

    $status = $row['status'];

    if($status == "Pending"){

        $status_class = "pending";

    }elseif($status == "Preparing"){

        $status_class = "preparing";

    }elseif($status == "On The Way"){

        $status_class = "on-the-way";

    }elseif($status == "Delivered"){

        $status_class = "delivered";

    }else{

        $status_class = "status";

    }

?>

<tr>

<td>
    <?php echo $row['id']; ?>
</td>

<td>
    <?php echo htmlspecialchars($row['food_name']); ?>
</td>

<td>
    ₦<?php echo number_format($row['total'], 2); ?>
</td>

<td>

<span class="<?php echo $status_class; ?>">

<?php echo htmlspecialchars($status); ?>

</span>

</td>

<td>
    <?php echo htmlspecialchars($row['created_at']); ?>
</td>

</tr>

<?php

}

?>

</table>

<?php

    }else{

        echo "<p>No order found for this phone number.</p>";

    }

}

?>

</div>

</body>

</html>