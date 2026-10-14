<?php
session_start();

if (!isset($_SESSION['vendor_id'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

$vendor_id = $_SESSION['vendor_id'];

$result = mysqli_query(
    $conn,
    "SELECT * FROM menu_items WHERE vendor_id='$vendor_id'"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Foods | Moor Food Court</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #292929;
        }

        .header {
            background: #8b0000;
            color: white;
            padding: 25px 35px;
        }

        .header-content {
            max-width: 1250px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .brand span {
            color: #ffcccc;
        }

        .header p {
            color: #f8dddd;
            font-size: 14px;
            line-height: 1.6;
        }

        .back-btn {
            display: inline-block;
            color: white;
            border: 1px solid rgba(255,255,255,0.6);
            padding: 12px 18px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: 0.2s;
        }

        .back-btn:hover {
            background: white;
            color: #8b0000;
        }

        .container {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 25px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .page-heading h1 {
            color: #292929;
            font-size: 27px;
            margin-bottom: 8px;
        }

        .page-heading p {
            color: #777;
            font-size: 14px;
            line-height: 1.6;
        }

        .add-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            background: #8b0000;
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }

        .add-btn:hover {
            background: #690000;
            transform: translateY(-2px);
        }

        .summary {
            background: white;
            border: 1px solid #eee;
            border-radius: 14px;
            padding: 22px 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.035);
        }

        .summary-label {
            font-size: 13px;
            color: #777;
            margin-bottom: 8px;
        }

        .summary-number {
            font-size: 28px;
            color: #8b0000;
            font-weight: bold;
        }

        .table-panel {
            background: white;
            border: 1px solid #eee;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04);
        }

        .table-heading {
            padding: 22px 25px;
            border-bottom: 1px solid #eee;
        }

        .table-heading h2 {
            font-size: 18px;
            margin-bottom: 6px;
        }

        .table-heading p {
            color: #888;
            font-size: 13px;
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        thead th {
            background: #fff3f3;
            color: #790000;
            padding: 17px 15px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
            white-space: nowrap;
        }

        tbody td {
            padding: 16px 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fffafa;
        }

        .food-image {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
            display: block;
            margin: auto;
        }

        .food-name {
            font-weight: bold;
            color: #292929;
        }

        .food-category {
            display: inline-block;
            background: #f3f3f3;
            color: #555;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .food-price {
            font-weight: bold;
            color: #8b0000;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .action-btn {
            display: inline-block;
            text-decoration: none;
            color: white;
            padding: 9px 13px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: bold;
            transition: 0.2s;
        }

        .edit {
            background: #167647;
        }

        .edit:hover {
            background: #105a35;
        }

        .delete {
            background: #c62828;
        }

        .delete:hover {
            background: #981b1b;
        }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
        }

        .empty-icon {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            font-size: 19px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #777;
            font-size: 14px;
            margin-bottom: 22px;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            padding: 30px 15px;
        }

        @media (max-width: 650px) {
            .header {
                padding: 22px 18px;
            }

            .container {
                margin: 25px auto;
                padding: 0 14px;
            }

            .page-heading {
                align-items: stretch;
            }

            .page-heading h1 {
                font-size: 24px;
            }

            .add-btn {
                width: 100%;
            }

            .summary {
                padding: 20px;
            }

            .table-heading {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<header class="header">
    <div class="header-content">

        <div>
            <div class="brand">
                MOOR <span>FOOD</span>
            </div>

            <p>Vendor Management Portal</p>
        </div>

        <a href="dashboard.php" class="back-btn">
            ← Back to Dashboard
        </a>

    </div>
</header>

<main class="container">

    <section class="page-heading">

        <div>
            <h1>My Foods</h1>
            <p>View and manage the food items in your menu.</p>
        </div>

        <a href="add_food.php" class="add-btn">
            + Add New Food
        </a>

    </section>

    <?php if ($result): ?>

        <?php $total_foods = mysqli_num_rows($result); ?>

        <section class="summary">
            <div class="summary-label">Total Food Items</div>

            <div class="summary-number">
                <?php echo $total_foods; ?>
            </div>
        </section>

        <section class="table-panel">

            <div class="table-heading">
                <h2>Food Menu</h2>
                <p>Your food items and their current prices.</p>
            </div>

            <?php if ($total_foods > 0): ?>

                <div class="table-scroll">

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Food Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>
                                <td>
                                    <?php echo (int)$row['id']; ?>
                                </td>

                                <td>
                                    <img
                                        class="food-image"
                                        src="../images/<?php
                                        echo rawurlencode(basename($row['image']));
                                        ?>"
                                        alt="<?php
                                        echo htmlspecialchars(
                                            $row['food_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>"
                                    >
                                </td>

                                <td>
                                    <span class="food-name">
                                        <?php
                                        echo htmlspecialchars(
                                            $row['food_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="food-category">
                                        <?php
                                        echo htmlspecialchars(
                                            $row['category'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="food-price">
                                        ₦<?php
                                        echo number_format(
                                            (float)$row['price'],
                                            2
                                        );
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a
                                            class="action-btn edit"
                                            href="edit_food.php?id=<?php
                                            echo (int)$row['id'];
                                            ?>"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            class="action-btn delete"
                                            href="delete_food.php?id=<?php
                                            echo (int)$row['id'];
                                            ?>"
                                            onclick="return confirm('Are you sure you want to delete this food?');"
                                        >
                                            Delete
                                        </a>

                                    </div>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                        </tbody>
                    </table>

                </div>

            <?php else: ?>

                <div class="empty-state">
                    <div class="empty-icon">🍽️</div>

                    <h3>No Food Items Yet</h3>

                    <p>
                        You have not added any food items.
                        Start by adding your first menu item.
                    </p>

                    <a href="add_food.php" class="add-btn">
                        + Add Your First Food
                    </a>
                </div>

            <?php endif; ?>

        </section>

    <?php else: ?>

        <section class="table-panel">
            <div class="empty-state">
                <h3>Unable to Load Food Items</h3>
                <p>Please check your database connection and try again.</p>
            </div>
        </section>

    <?php endif; ?>

</main>

<footer class="footer">
    &copy; <?php echo date('Y'); ?> Moor Food Court. All rights reserved.
</footer>

</body>
</html>