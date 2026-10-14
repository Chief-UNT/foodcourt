<!DOCTYPE html>
<html><?php

include '../db.php';

if(isset($_POST['add_food'])){

    $food_name = $_POST['food_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
   $image = $_FILES['image']['name'];

$tmp_name = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp_name,
"../images/".$image);

    $query = "INSERT INTO menu_items(food_name, price, category, description, image)
              VALUES('$food_name', '$price', '$category', '$description', '$image')";

    mysqli_query($conn, $query);

    header("Location: foods.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Food</title>

    <style>

        body{
            font-family: Arial;
            background: #f5f5f5;
            padding: 30px;
        }

        h2{
            color: darkred;
        }

        form{
            background: white;
            width: 400px;
            padding: 20px;
            border-radius: 10px;
        }

        input{
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        button{
            background: darkred;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>

</head>
<body>

<h2>Add New Food</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Food Name</label>
    <input type="text" name="food_name" required>

    <label>Price</label>
    <input type="number" name="price" required>
    <label>Category</label>
<label>Description</label>

<textarea
name="description"
placeholder="Enter food description"
rows="4"
required></textarea>
<input type="text"
name="category"
placeholder="Enter category"
required>

    <label>Food Image</label>
<input type="file" name="image" required>

    <button type="submit" name="add_food">
        Add Food
    </button>

</form>

</body>
</html>
<head>
    <title>Add Food</title>

    <style>

        body{
            font-family: Arial;
            background: #f5f5f5;
            padding: 30px;
        }

        h2{
            color: darkred;
        }

        form{
            background: white;
            width: 400px;
            padding: 20px;
            border-radius: 10px;
        }

        input{
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        button{
            background: darkred;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>

</head>
<body>

<h2>Add New Food</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Food Name</label>
    <input type="text" name="food_name" required>

    <label>Price</label>
    <input type="number" name="price" required>
    <label>Category</label>
<label>Description</label>

<textarea
name="description"
placeholder="Enter food description"
rows="4"
required></textarea>
<input type="text"
name="category"
placeholder="Enter category"
required>

    <label>Food Image</label>
<input type="file" name="image" required>

    <button type="submit" name="add_food">
        Add Food
    </button>

</form>

</body>
</html>