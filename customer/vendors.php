<?php

session_start();

include '../db.php';

$result = mysqli_query($conn,"SELECT * FROM vendors");

?>

<!DOCTYPE html>
<html>
<head>

<title>Vendors</title>

<style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:20px;
}

h1{
    text-align:center;
    color:#8b0000;
}

.vendor-container{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
}

.vendor-card{
    width:300px;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    text-align:center;
}

.vendor-card h2{
    color:#8b0000;
}

.btn{
    display:inline-block;
    background:#8b0000;
    color:white;
    padding:10px 20px;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>
<body>

<h1>Choose A Vendor</h1>

<div class="vendor-container">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="vendor-card">

<h2><?php echo $row['vendor_name']; ?></h2>

<p>
📍 <?php echo $row['location']; ?>
</p>

<a href="vendor_menu.php?id=<?php echo $row['id']; ?>" class="btn">
View Menu
</a>

</div>

<?php } ?>

</div>

</body>
</html>