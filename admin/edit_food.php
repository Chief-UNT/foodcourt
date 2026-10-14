<?php

include '../db.php';

$id = $_GET['id'];

$query = mysqli_query(
$conn,
"SELECT * FROM menu_items WHERE id='$id'"
);

$row = mysqli_fetch_assoc($query);

if(isset($_POST['update_food'])){

    $food_name = $_POST['food_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $image = $row['image'];

    if(!empty($_FILES['image']['name'])){

        $image = $_FILES['image']['name'];

        move_uploaded_file(
        $_FILES['image']['tmp_name'],
        "../images/".$image
        );
    }

    mysqli_query(
    $conn,
    "UPDATE menu_items SET

    food_name='$food_name',
    price='$price',
    category='$category',
    description='$description',
    image='$image'

    WHERE id='$id'"
    );

    header("Location: foods.php");
    exit();
}

?><!DOCTYPE html><html>
<head><title>Edit Food</title><style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:30px;
}

.form-box{
    max-width:600px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    color:#E63946;
    text-align:center;
}

input,
textarea{
    width:100%;
    padding:12px;
    margin-top:8px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:8px;
}

img{
    width:150px;
    height:150px;
    object-fit:cover;
    border-radius:10px;
    display:block;
    margin:auto;
}

button{
    width:100%;
    padding:12px;
    background:#E63946;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#d62839;
}

</style></head><body><div class="form-box"><h2>Edit Food</h2><img src="../images/<?php echo $row['image']; ?>"><form method="POST" enctype="multipart/form-data"><label>Food Name</label>

<input
type="text"
name="food_name"
value="<?php echo $row['food_name']; ?>"
required>

<label>Price</label>

<input
type="number"
name="price"
value="<?php echo $row['price']; ?>"
required>

<label>Category</label>

<input
type="text"
name="category"
value="<?php echo $row['category']; ?>"
required>

<label>Description</label>

<textarea
name="description"
required><?php echo $row['description']; ?></textarea><label>Change Image (Optional)</label>

<input
type="file"
name="image">

<button
type="submit"
name="update_food">

Update Food

</button></form></div></body>
</html>