<?php
session_start();
include 'db.php';
$cart_count = 0;

if(isset($_SESSION['customer'])){

    $customer = $_SESSION['customer'];

    $cart_query = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM cart
         WHERE customer_name='$customer'"
    );

    $cart_row = mysqli_fetch_assoc($cart_query);

    $cart_count = $cart_row['total'];
}
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT * FROM menu_items WHERE 1";

if (!empty($search)) {
    $query .= " AND food_name LIKE '%$search%'";
}

if (!empty($category)) {
    $query .= " AND category='$category'";
}

$vendors = mysqli_query(
$conn,
"SELECT * FROM vendors"
);
?>

<!DOCTYPE html>
<html>
<head>

<title>Food Court</title>


<style>
   .success-msg{
    background:#28a745;
    color:white;
    width:90%;
    margin:15px auto;
    padding:15px;
    text-align:center;
    border-radius:10px;
    font-weight:bold;

    animation:fadeOut 3s forwards;
}

@keyframes fadeOut{

    0%{
        opacity:1;
    }

    80%{
        opacity:1;
    }

    100%{
        opacity:0;
        display:none;
    }

}
  .cart-icon{
    position:relative;
    text-decoration:none;
    font-size:24px;
    margin:0 10px;
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
.badge{
    position:absolute;
    top:-8px;
    right:-10px;

    background:#E63946;
    color:white;

    min-width:20px;
    height:20px;

    border-radius:50%;

    display:flex;
    justify-content:center;
    align-items:center;

    font-size:11px;
    font-weight:bold;

    padding:2px;
}
    .welcome-banner{
    background:#E63946;
    color:white;
    text-align:center;
    padding:15px;
    font-size:18px;
    font-weight:bold;
}
    
    .logo-area{
    display:flex;
    align-items:center;
    gap:15px;
}

.logo-area img{
    width:60px;
    height:60px;
    border-radius:50%;
    background:white;
    padding:5px;
}

.logo-area h1{
    color:white;
    margin:0;
}
    .track-btn{
    display:inline-block;
    background:#8b0000;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:8px;
    margin:15px;
    font-weight:bold;
}

.track-btn:hover{
    background:red;
}
    .categories{
    text-align:center;
    margin:20px 0;
}

.categories button{
    background:#8b0000;
    color:white;
    border:none;
    padding:10px 15px;
    margin:5px;
    border-radius:20px;
    cursor:pointer;
}

.categories button:hover{
    background:red;
}
    .desc{
    color:#555;
    font-size:14px;
    padding:0 15px;
    margin-bottom:15px;
    line-height:1.5;
    height:60px;
    overflow:hidden;
}
    .desc{
    color:#555;
    font-size:14px;
    padding:0 15px;
    margin-bottom:15px;
}
    .price{
    font-size:22px;
    font-weight:bold;
    color:#8b0000;
    margin:15px 0;
}
    .view-btn{
    background:#333;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:10px;
    cursor:pointer;
    width:80%;
}

.view-btn:hover{
    background:black;
}
   .food-box{
    background:white;
    width:320px;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    transition:0.4s;
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
    padding:15px;
    font-size:22px;
}

.price{
    color:#E63946;
    font-size:24px;
    font-weight:bold;
}
.food-box button{
    background:#E63946;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
    margin-bottom:15px;
}

.food-box button:hover{
    background:#d62839;
}
@keyframes fadeIn{

    from{
        opacity:0;
        transform:translateY(20px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }

}
  .footer{
    background:#222;
    color:white;
    text-align:center;
    padding:40px 20px;
    margin-top:50px;
}

.footer p{
    margin:5px;
}
    .search-box{
    text-align:center;
    margin-bottom:30px;
}

.search-box{
    text-align:center;
    margin:40px auto;
    max-width:700px;
}

.search-box input{
    width:70%;
    padding:16px;
    border:none;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    font-size:16px;
}

.search-box button{
    padding:16px 25px;
    border:none;
    border-radius:12px;
    background:#E63946;
    color:white;
    cursor:pointer;
    font-weight:bold;
}
   .hero{
    text-align:center;
    padding:80px 20px;
    background:linear-gradient(to right,#fff5f5,#ffffff);
}

.hero h2{
    font-size:50px;
    color:#E63946;
    margin-bottom:15px;
}

.hero p{
    font-size:20px;
    color:#666;
}

h1{
    text-align: center;
    color: darkred;
    margin-bottom: 30px;
}

body {
    font-family: Arial, sans-serif;
    background-color: #fff7ed;
    background-image:
        linear-gradient(
            rgba(255, 247, 237, 0.55),
            rgba(255, 247, 237, 0.55)
        ),
        url("images/background.jpeg");
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
}

/* Stack vendor cards vertically */
.food-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 25px;
    padding: 20px;
    width: 100%;
    box-sizing: border-box;
}

.food-container .food-box {
    width: 100%;
    max-width: 600px;
    box-sizing: border-box;
    background: rgba(255, 255, 255, 0.96);
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.10);
}
.food-box{
    background:white;
    width:280px;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    text-align:center;
    padding-bottom:20px;
    transition:0.3s;
}
.food-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 25px;
    padding: 20px;
    width: 100%;
    box-sizing: border-box;
}

.food-container .food-box {
    width: 100%;
    max-width: 600px;
    box-sizing: border-box;
}
.food-box:hover{
    transform:translateY(-10px) scale(1.03);
}
.food-box img{
    width:100%;
    height:200px;
    object-fit:cover;
   transition:0.4s; 
}
.food-box:hover img{
    transform:scale(1.05);
}

.food-box h3{
    margin-top: 15px;
    color: #333;
}

.food-box p{
    color:#8b0000;
    font-size:20px;
    font-weight:bold;
    margin-bottom:15px;
}

.food-box button{
    background:#E63946;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    transition:0.3s;
}

.food-box button:hover{
    background:red;
}

.food-box button:hover{
    background: red;
}
.header{
    background:white;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    position:sticky;
    top:0;
    z-index:1000;
}

.logo-area{
    display:flex;
    align-items:center;
    gap:15px;
}

.logo-area img{
    width:60px;
    height:60px;
}

.logo-area h1{
    color:#E63946;
    font-size:28px;
}

.nav-links a{
    text-decoration:none;
    color:#333;
    margin-left:15px;
    font-weight:bold;
}

.nav-links a:hover{
    color:#E63946;
}

.header h1{
    margin:0;
    color:#E63946;
}
.header a{
    background:white;
    color:#8b0000;
    padding:10px 15px;
    text-decoration:none;
    border-radius:5px;
    font-weight:bold;
}
.food-box h4{
    color:#777;
    margin-bottom:10px;
    font-size:16px;
}
.categories button{
    background:white;
    color:#333;
    border:1px solid #ddd;
    padding:12px 20px;
    margin:5px;
    border-radius:30px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

.categories button:hover{
    background:#E63946;
    color:white;
}
.slider{
    width:100%;
    height:350px;
    overflow:hidden;
    position:relative;
}

.slide{
    display:none;
    width:100%;
    height:350px;
}

.slide img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.active{
    display:block;
}

</style>

</head>
   <body>
<?php

if(isset($_SESSION['success'])){

?>

<div class="success-msg">

✅ <?php echo $_SESSION['success']; ?>

</div>

<?php

unset($_SESSION['success']);

}

?>
<div class="slider">

    <div class="slide active">
        <img src="images/banner1.jpg">
    </div>

    <div class="slide">
        <img src="images/banner2.jpg">
    </div>

    <div class="slide">
        <img src="images/banner3.jpg">
    </div>

</div>

<div class="hero">

    <h2>Delicious Meals Delivered To Your Doorstep</h2>

    <p>
        Fast • Fresh • Affordable
    </p>

</div>
<div class="welcome-banner">

    🍔 Order Delicious Meals Anytime - Anywhere
    - Anyday.

</div>
<div class="search-box">

    <form method="GET">

<input
type="text"
name="search"
placeholder="Search Food..."
value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">
Search
</button>

</form>
    <div class="categories">

<a href="index.php">
<button>All</button>
</a>

<a href="index.php?category=Rice">
<button>Rice</button>
</a>

<a href="index.php?category=Drinks">
<button>Drinks</button>
</a>

<a href="index.php?category=Fast Food">
<button>Fast Food</button>
</a>

<a href="index.php?category=Snacks">
<button>Snacks</button>
</a>

</div>

</div>

</div>


</div>
<body>
    <div class="header">

    <div class="logo-area">
        <img src="images/logo.png" alt="Moor Food Court Logo">
        <h1>Moor Food Court</h1>
    </div>

<?php if(isset($_SESSION['customer'])){ ?>

    <span style="color:#333;font-weight:bold;margin-right:15px;">
        Welcome, <?php echo $_SESSION['customer']; ?>
    </span>
<a href="customer/cart.php" class="cart-icon">

🛒

<span class="badge">
<?php echo $cart_count; ?>
</span>

</a>
    <a href="customer/profile.php">
        Profile
    </a>

    <a href="customer/my_orders.php">
        My Orders
    </a>
   

    <a href="customer/logout.php">
        Logout
    </a>

<?php } else { ?>

        <a href="customer/login.php">
            Login
        </a>

        <a href="register.php">
            Register
        </a>

    <?php } ?>

    </div>
<a href="customer/track_order.php" class="track-btn">
    Track My Order
</a>
<h1>🏪 Available foods</h1><div class="food-container"><?php while($vendor = mysqli_fetch_assoc($vendors)) { ?><div class="food-box"><img src="images/vendor.jpg">

<h3>
    <?php echo $vendor['vendor_name']; ?>
</h3>

<p class="desc">
    Browse meals from this vendor
</p>

<a href="customer/vendor_menu.php?id=<?php echo $vendor['id']; ?>">

    <button class="view-btn">
        View Foods
    </button>

</a>

</div><?php } ?></div>

 
</a>
</div>

<?php ?>

</div>
<div class="footer">

    <h3>Moor Food Court</h3>

    <p>
        Fast • Fresh • Affordable
    </p>

    <p>
        © 2026 Moor Food Court
    </p>

    <p>
        Designed by Dauda Taiwo Taofeek NDCOM/24/668
    </p>

</div>
<script>

let slides = document.querySelectorAll(".slide");

let current = 0;

setInterval(function(){

    slides[current].classList.remove("active");

    current++;

    if(current >= slides.length){
        current = 0;
    }

    slides[current].classList.add("active");

},3000);

</script>

</body>
</html>