<?php

session_start();

if(!isset($_SESSION['vendor_id'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

if(isset($_POST['submit'])){

    $food_name = $_POST['food_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $vendor_id = $_SESSION['vendor_id'];

    $image = $_FILES['image']['name'];

    move_uploaded_file(
    $_FILES['image']['tmp_name'],
    "../images/".$image
    );

    mysqli_query(
    $conn,
    "INSERT INTO menu_items
    (vendor_id,food_name,price,image,category,description)

    VALUES

    ('$vendor_id',
    '$food_name',
    '$price',
    '$image',
    '$category',
    '$description')"
    );

    header("Location: foods.php");
    exit();
}

?><!DOCTYPE html><html>
<head>
<title>Add Food</title>
</head>
<body><h1>Add Food</h1><form method="POST" enctype="multipart/form-data"><input type="text"
name="food_name"
placeholder="Food Name"
required>

<br><br>

<input type="number"
name="price"
placeholder="Price"
required>

<br><br>

<input type="text"
name="category"
placeholder="Category"
required>

<br><br>

<textarea
name="description"
placeholder="Description"
required></textarea><br><br>

<input type="file"
name="image"
required>

<br><br>

<button name="submit">
Add Food
</button></form></body>
</html>