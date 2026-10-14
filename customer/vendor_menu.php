<?php

session_start();
include '../db.php';

$vendor_id = $_GET['id'];

$vendor = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM vendors WHERE id='$vendor_id'"
)
);

$foods = mysqli_query(
$conn,
"SELECT * FROM menu_items
WHERE vendor_id='$vendor_id'"
);

?><!DOCTYPE html><html><head><title><?php echo $vendor['vendor_name']; ?></title><style>


.header{
    background:#E63946;
    color:white;
    text-align:center;
    padding:30px;
}

.header h1{
    margin:0;
}
body {
    font-family: Arial, sans-serif;
    background-color: #fff7ed;
    background-image:
        linear-gradient(
            rgba(255, 247, 237, 0.55),
            rgba(255, 247, 237, 0.55)
        ),
        url("../images/background.jpeg");
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
}
.back-btn{
    display:inline-block;
    margin:20px;
    padding:12px 20px;
    background:#333;
    color:white;
    text-decoration:none;
    border-radius:8px;
}
/* Centre and resize the vendor logo */
.food-box img {
    display: block;
    width: 100%;
    max-width: 220px;
    height: 150px;
    object-fit: contain;
    margin: 0 auto 15px;
    border-radius: 10px;
}
/* Display food cards one below another */
.food-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    padding: 20px;
    width: 100%;
    box-sizing: border-box;
}

/* Keep each food card centred */
.food-container .food-box {
    width: 100%;
    max-width: 600px;
    box-sizing: border-box;
}

.food-box{
    background:white;
    width:300px;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    text-align:center;
    transition:0.3s;
}

.food-box:hover{
    transform:translateY(-8px);
}

.food-box img{
    width:100%;
    height:220px;
    object-fit:cover;
}

.food-box h3{
    margin:15px 0 5px;
}

.category{
    color:#777;
    font-size:14px;
}

.price{
    color:#E63946;
    font-size:24px;
    font-weight:bold;
    margin:15px 0;
}

.desc{
    padding:0 15px;
    color:#555;
    min-height:60px;
}

.btn{
    display:inline-block;
    background:#E63946;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:8px;
    margin:10px 0 20px;
    font-weight:bold;
}

.btn:hover{
    background:#d62839;
}

</style></head><body><div class="header"><h1>
<?php echo $vendor['vendor_name']; ?>
</h1><p>Browse all meals from this vendor</p></div><a href="../index.php" class="back-btn">
← Back To Vendors
</a><div class="food-container"><?php while($row = mysqli_fetch_assoc($foods)) { ?><div class="food-box"><img src="../images/<?php echo $row['image']; ?>"><h3>
<?php echo $row['food_name']; ?>
</h3><div class="category">
<?php echo $row['category']; ?>
</div><div class="price">
₦<?php echo $row['price']; ?>
</div><p class="desc">
<?php echo $row['description']; ?>
</p><a href="food_details.php?id=<?php echo $row['id']; ?>" class="btn">
View Details
</a><br><a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn">
Add To Cart 🛒
</a></div><?php } ?></div></body>
</html>