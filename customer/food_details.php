<?php

include '../db.php';

$id = $_GET['id'];

$query = "SELECT * FROM menu_items WHERE id='$id'";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);
$vendor = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT vendor_name
FROM vendors
WHERE id='".$row['vendor_id']."'"
)
);
$review = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT AVG(rating) AS rating
FROM reviews
WHERE food_name='".$row['food_name']."'"
)
);
?><!DOCTYPE html><html>
<head>
<title><?php echo $row['food_name']; ?></title><style>

body{
    font-family:Arial;
    background:#f5f5f5;
    margin:0;
}

.container{
    max-width:900px;
    margin:40px auto;
    background:white;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

.container img{
    width:100%;
    height:400px;
    object-fit:cover;
}

.content{
    padding:30px;
}

.category{
    display:inline-block;
    background:#E63946;
    color:white;
    padding:8px 15px;
    border-radius:20px;
    margin-bottom:15px;
}

h1{
    margin:10px 0;
    color:#333;
}

.price{
    font-size:30px;
    color:#E63946;
    font-weight:bold;
    margin:20px 0;
}

.desc{
    color:#666;
    line-height:1.8;
    font-size:16px;
}

.order-btn{
    display:inline-block;
    background:#E63946;
    color:white;
    text-decoration:none;
    padding:15px 30px;
    border-radius:10px;
    margin-top:20px;
    font-weight:bold;
}

.order-btn:hover{
    background:#d62839;
}

</style></head>
<body><div class="container"><img src="../images/<?php echo $row['image']; ?>"><div class="content"><div class="category">
<?php echo $row['category']; ?>
</div><h1>
<?php echo $row['food_name']; ?>
<p style="color:#E63946;font-weight:bold;">
🏪 Vendor:
<?php echo $vendor['vendor_name']; ?>
</p>

<p>
⭐
<?php echo round($review['rating'],1); ?>
</p>
</h1><div class="price">
₦<?php echo $row['price']; ?>
</div><p class="desc">
<?php echo $row['description']; ?>
</p><a class="order-btn"
href="order.php?id=<?php echo $row['id']; ?>">
Order Now
</a>
<a class="order-btn"
style="background:#333;margin-left:10px;"
href="add_to_cart.php?id=<?php echo $row['id']; ?>">

Add To Cart 🛒

</a>
</div></div></body>
</html>