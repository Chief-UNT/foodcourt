<?php
include '../db.php';

$query = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Menu</title>

    <style>
        body{
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        .food-box{
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        img{
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        h1{
            color: darkred;
        }
    </style>
</head>
<body>

<h1>Food Menu</h1>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="food-box">

    <img src="<?php echo $row['image']; ?>">

    <h2><?php echo $row['food_name']; ?></h2>

    <p>Price: ₦<?php echo $row['price']; ?></p>

    <a href="order.php?id=<?php echo $row['id']; ?>">
        <button>Order Now</button>
    </a>

</div>

<?php } ?>

</body>
</html>