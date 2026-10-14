<?php

session_start();
if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}
include '../db.php';

if(isset($_POST['submit'])){

    $food_name = $_POST['food_name'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $customer = $_SESSION['customer'];

    mysqli_query(
        $conn,
        "INSERT INTO reviews
        (food_name,customer_name,rating,review)
        VALUES
        ('$food_name','$customer','$rating','$review')"
    );

    echo "Review Submitted!";
}
?>
<style>body{
    font-family:Arial,sans-serif;
    background:#f5f5f5;
    margin:0;

    display:flex;
    justify-content:center;
    align-items:center;

    min-height:100vh;
}

.box{
    width:500px;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.12);
}

h2{
    text-align:center;
    color:#E63946;
    margin-bottom:20px;
}

.msg{
    color:green;
    text-align:center;
    font-weight:bold;
    margin-bottom:15px;
}

input,
select,
textarea{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
    box-sizing:border-box;
}

textarea{
    height:120px;
}

button{
    width:100%;
    padding:14px;
    background:#E63946;
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-size:16px;
    font-weight:bold;
}

button:hover{
    background:#d62839;
}</style>
<form method="POST">

<input
type="text"
name="food_name"
placeholder="Food Name"
required>

<select name="rating">

<option value="5">⭐⭐⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="2">⭐⭐</option>
<option value="1">⭐</option>

</select>

<br><br>

<textarea
name="review"
placeholder="Write your review"
required>
</textarea>

<br><br>

<button name="submit">
Submit Review
</button>

</form>