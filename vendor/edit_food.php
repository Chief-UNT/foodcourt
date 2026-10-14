<?php

session_start();

if (!isset($_SESSION['vendor_id'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

$vendor_id = (int) $_SESSION['vendor_id'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid food ID.");
}

/* Get food belonging to this vendor */
$query = mysqli_query(
    $conn,
    "SELECT * FROM menu_items
     WHERE id = $id
     AND vendor_id = $vendor_id
     LIMIT 1"
);

if (!$query || mysqli_num_rows($query) == 0) {
    die("Food not found or you are not authorised to edit it.");
}

$food = mysqli_fetch_assoc($query);

$error = "";
$success = "";

/* Update food when form is submitted */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $food_name = trim($_POST['food_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? '';

    if ($food_name === '' || $category === '' ||
        $price === '' || !is_numeric($price) || (float)$price < 0) {

        $error = "Please enter a valid food name, category and price.";

    } else {

        $food_name_safe = mysqli_real_escape_string(
            $conn,
            $food_name
        );

        $category_safe = mysqli_real_escape_string(
            $conn,
            $category
        );

        $price_value = (float)$price;

        $update = mysqli_query(
            $conn,
            "UPDATE menu_items
             SET food_name = '$food_name_safe',
                 category = '$category_safe',
                 price = '$price_value'
             WHERE id = $id
             AND vendor_id = $vendor_id"
        );

        if ($update) {

            header("Location: foods.php");
            exit();

        } else {

            $error = "Unable to update food: " . mysqli_error($conn);

        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Food - Moor Food Court</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }

        h2 {
            color: #E63946;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button, .back {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            background: #E63946;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .error {
            color: #b00020;
            margin-top: 12px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Edit Food</h2>

    <?php if ($error !== ''): ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Food Name</label>
        <input
            type="text"
            name="food_name"
            value="<?php echo htmlspecialchars($food['food_name']); ?>"
            required
        >

        <label>Category</label>
        <input
            type="text"
            name="category"
            value="<?php echo htmlspecialchars($food['category']); ?>"
            required
        >

        <label>Price (₦)</label>
        <input
            type="number"
            name="price"
            min="0"
            step="0.01"
            value="<?php echo htmlspecialchars((string)$food['price']); ?>"
            required
        >

        <button type="submit">Save Changes</button>

        <a href="foods.php" class="back">Cancel</a>

    </form>

</div>

</body>
</html>