<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

/* Dashboard statistics */
$today_orders = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM orders WHERE DATE(created_at)=CURDATE()"
));

$total_foods = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM menu_items"
));

$paid_orders = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM orders WHERE payment_status='Paid'"
));

$pending_payments = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM orders WHERE payment_status='Pending'"
));

$total_orders = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM orders"
));

$pending_orders = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM orders WHERE status='Pending'"
));

$delivered_orders = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM orders WHERE status='Delivered'"
));

$total_customers = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM users"
));

$total_vendors = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM vendors"
));

/* Revenue calculations */
$revenue_query = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(total), 0) AS revenue
     FROM orders WHERE status='Delivered'"
);

$total_revenue = mysqli_fetch_assoc($revenue_query)['revenue'];

$paid_revenue_query = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(total), 0) AS paid_revenue
     FROM orders WHERE payment_status='Paid'"
);

$paid_revenue = mysqli_fetch_assoc($paid_revenue_query)['paid_revenue'];

$today_query = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(total), 0) AS today_revenue
     FROM orders
     WHERE status='Delivered'
     AND DATE(created_at)=CURDATE()"
);

$today_revenue = mysqli_fetch_assoc($today_query)['today_revenue'];

/* Orders */
$result = mysqli_query(
    $conn,
    "SELECT * FROM orders ORDER BY id DESC"
);

$recent_orders = mysqli_query(
    $conn,
    "SELECT * FROM orders ORDER BY id DESC LIMIT 5"
);

/* Top-selling foods */
$top_foods = mysqli_query(
    $conn,
    "SELECT food_name,
            SUM(quantity) AS total_sold,
            SUM(total) AS revenue
     FROM orders
     WHERE status='Delivered'
     GROUP BY food_name
     ORDER BY total_sold DESC
     LIMIT 5"
);

/* Sales report */
$sales_query = mysqli_query(
    $conn,
    "SELECT food_name,
            SUM(quantity) AS total_sold,
            SUM(total) AS revenue
     FROM orders
     WHERE status='Delivered'
     GROUP BY food_name
     ORDER BY total_sold DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Moor Food Court | Admin Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #292d32;
            line-height: 1.5;
        }

        a {
            text-decoration: none;
        }

        button {
            font-family: inherit;
        }

        /* Top navigation */
        .topbar {
            background: #ffffff;
            padding: 18px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-section img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .logo-section h2 {
            margin: 0;
            color: #8b0000;
            font-size: 22px;
        }

        .admin-name {
            font-weight: bold;
            color: #555;
        }

        /* Main content */
        .dashboard {
            width: 92%;
            max-width: 1450px;
            margin: 32px auto;
        }

        /* Welcome banner */
        .dashboard-banner {
            background: linear-gradient(135deg, #8b0000, #c73535);
            color: white;
            padding: 32px;
            border-radius: 18px;
            margin-bottom: 30px;
            box-shadow: 0 8px 22px rgba(139, 0, 0, 0.13);
        }

        .dashboard-banner h1 {
            margin: 0 0 8px;
            font-size: 27px;
        }

        .dashboard-banner p {
            margin: 0;
            color: #ffe9e9;
        }

        /* Section headings */
        .section-heading {
            margin: 35px 0 18px;
        }

        .section-heading h2 {
            font-size: 22px;
            margin: 0 0 5px;
            color: #292d32;
        }

        .section-heading p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        /* Quick action buttons */
        .menu-buttons {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 35px;
        }

        .action-button {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 58px;
            padding: 14px;
            border-radius: 11px;
            background: #ffffff;
            border: 1px solid #e7e7e7;
            color: #8b0000;
            font-weight: bold;
            text-align: center;
            transition: 0.2s;
        }

        .action-button:hover {
            background: #8b0000;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Statistics cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
        }

        .card {
            min-width: 0;
            background: #ffffff;
            padding: 24px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.045);
            border: 1px solid #ededed;
            border-left: 5px solid #8b0000;
            transition: 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.07);
        }

        .card.paid-card {
            border-left-color: #239b56;
        }

        .card.pending-card {
            border-left-color: #e69a16;
        }

        .card.revenue-card {
            border-left-color: #356ac3;
        }

        .icon {
            font-size: 29px;
            margin-bottom: 10px;
        }

        .card h3 {
            margin: 0 0 6px;
            color: #8b0000;
            font-size: 27px;
            overflow-wrap: anywhere;
        }

        .card p {
            margin: 0;
            color: #70757d;
            font-size: 14px;
            font-weight: bold;
        }

        /* General content panels */
        .panel {
            background: #ffffff;
            padding: 25px;
            margin-top: 30px;
            border-radius: 16px;
            border: 1px solid #ededed;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.045);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .panel-header h2 {
            margin: 0;
            font-size: 21px;
            color: #292d32;
        }

        .report-link {
            color: #8b0000;
            font-weight: bold;
            font-size: 14px;
        }

        /* Tables */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th {
            padding: 16px;
            background: #8b0000;
            color: #ffffff;
            text-align: left;
            font-size: 13px;
            white-space: nowrap;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #eeeeee;
            font-size: 14px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        tbody tr:hover {
            background: #fff3f3;
        }

        .food-image {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            object-fit: cover;
            display: block;
        }

        .status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .pending {
            background: #fff0d5;
            color: #965900;
        }

        .preparing {
            background: #e2efff;
            color: #225da8;
        }

        .delivery {
            background: #f0e4ff;
            color: #7040a0;
        }

        .delivered {
            background: #dff6e8;
            color: #16723b;
        }

        .delete-button {
            display: inline-block;
            padding: 8px 12px;
            background: #fff0f0;
            color: #b00020;
            border: 1px solid #f2cccc;
            border-radius: 7px;
            font-size: 12px;
            font-weight: bold;
        }

        .delete-button:hover {
            background: #b00020;
            color: #ffffff;
        }

        .empty-message {
            padding: 25px;
            text-align: center;
            color: #777;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 45px;
            padding: 25px 15px;
            background: #ffffff;
            color: #777;
            border-top: 1px solid #eeeeee;
            font-size: 13px;
        }

        .footer strong {
            color: #8b0000;
        }

        /* Responsive layouts */
        @media (max-width: 1000px) {
            .stats {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .menu-buttons {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .dashboard {
                width: 94%;
                margin: 20px auto;
            }

            .topbar {
                padding: 15px 4%;
            }

            .logo-section img {
                width: 43px;
                height: 43px;
            }

            .logo-section h2 {
                font-size: 17px;
            }

            .dashboard-banner {
                padding: 24px 20px;
            }

            .dashboard-banner h1 {
                font-size: 22px;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .card {
                padding: 17px;
            }

            .card h3 {
                font-size: 23px;
            }

            .menu-buttons {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .panel {
                padding: 16px;
            }
        }

        @media (max-width: 380px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .menu-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="logo-section">
        <img src="../images/logo.png" alt="Moor Food Court Logo">
        <h2>Moor Food Court Admin</h2>
    </div>

    <div class="admin-name">
        Welcome, Admin 👋
        &nbsp; | &nbsp;
        <a href="logout.php" style="color:#8b0000;">Logout</a>
    </div>
</header>

<main class="dashboard">

    <section class="dashboard-banner">
        <h1>🍔 Moor Food Court Management System</h1>
        <p>Manage your food court, monitor orders and track sales performance.</p>
    </section>

    <section>
        <div class="section-heading">
            <h2>Quick Actions</h2>
            <p>Access the main management sections from here.</p>
        </div>

        <div class="menu-buttons">
            <a class="action-button" href="foods.php">🍽️ Manage Foods</a>
            <a class="action-button" href="add_food.php">➕ Add Food</a>
            <a class="action-button" href="payment_history.php">💳 Payment History</a>
            <a class="action-button" href="orders.php">📦 View Orders</a>
            <a class="action-button" href="vendors.php">🏪 Manage Vendors</a>
            <a class="action-button" href="customers.php">👥 Manage Customers</a>
            <a class="action-button" href="report.php">📊 Sales Report</a>
        </div>
    </section>

    <section>
        <div class="section-heading">
            <h2>Dashboard Overview</h2>
            <p>A summary of your food court's current performance.</p>
        </div>

        <div class="stats">

            <div class="card">
                <div class="icon">🍽️</div>
                <h3><?php echo $total_foods; ?></h3>
                <p>Total Foods</p>
            </div>

            <div class="card">
                <div class="icon">📦</div>
                <h3><?php echo $total_orders; ?></h3>
                <p>Total Orders</p>
            </div>

            <div class="card paid-card">
                <div class="icon">💳</div>
                <h3><?php echo $paid_orders; ?></h3>
                <p>Paid Orders</p>
            </div>

            <div class="card pending-card">
                <div class="icon">⌛</div>
                <h3><?php echo $pending_payments; ?></h3>
                <p>Pending Payments</p>
            </div>

            <div class="card revenue-card">
                <div class="icon">💰</div>
                <h3>₦<?php echo number_format((float)$paid_revenue); ?></h3>
                <p>Payment Revenue</p>
            </div>

            <div class="card">
                <div class="icon">👥</div>
                <h3><?php echo $total_customers; ?></h3>
                <p>Total Customers</p>
            </div>

            <div class="card pending-card">
                <div class="icon">⏳</div>
                <h3><?php echo $pending_orders; ?></h3>
                <p>Pending Orders</p>
            </div>

            <div class="card paid-card">
                <div class="icon">🚚</div>
                <h3><?php echo $delivered_orders; ?></h3>
                <p>Delivered Orders</p>
            </div>

            <div class="card">
                <div class="icon">🏪</div>
                <h3><?php echo $total_vendors; ?></h3>
                <p>Total Vendors</p>
            </div>

            <div class="card revenue-card">
                <div class="icon">💵</div>
                <h3>₦<?php echo number_format((float)$total_revenue); ?></h3>
                <p>Delivered Order Revenue</p>
            </div>

            <div class="card">
                <div class="icon">📆</div>
                <h3><?php echo $today_orders; ?></h3>
                <p>Today's Orders</p>
            </div>

            <a href="report.php" style="color:inherit;">
                <div class="card">
                    <div class="icon">📈</div>
                    <h3>₦<?php echo number_format((float)$today_revenue); ?></h3>
                    <p>Today's Delivered Revenue</p>
                </div>
            </a>

        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>📋 Recent Orders</h2>
            <a class="report-link" href="orders.php">View All Orders →</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Food Item</th>
                        <th>Customer</th>
                        <th>Order Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($recent_orders && mysqli_num_rows($recent_orders) > 0) { ?>

                    <?php while ($recent = mysqli_fetch_assoc($recent_orders)) { ?>

                        <?php
                        $status_class = 'pending';

                        if ($recent['status'] === 'Preparing') {
                            $status_class = 'preparing';
                        } elseif ($recent['status'] === 'On The Way') {
                            $status_class = 'delivery';
                        } elseif ($recent['status'] === 'Delivered') {
                            $status_class = 'delivered';
                        }
                        ?>

                        <tr>
                            <td>#<?php echo (int)$recent['id']; ?></td>
                            <td><?php echo htmlspecialchars($recent['food_name']); ?></td>
                            <td><?php echo htmlspecialchars($recent['customer_name']); ?></td>
                            <td>
                                <span class="status <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($recent['status']); ?>
                                </span>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>
                    <tr>
                        <td colspan="4" class="empty-message">No orders have been recorded yet.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>🔥 Top Selling Foods</h2>
            <a class="report-link" href="report.php">Full Sales Report →</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Quantity Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($top_foods && mysqli_num_rows($top_foods) > 0) { ?>

                    <?php while ($food = mysqli_fetch_assoc($top_foods)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($food['food_name']); ?></td>
                            <td><?php echo (int)$food['total_sold']; ?></td>
                            <td>₦<?php echo number_format((float)$food['revenue']); ?></td>
                        </tr>
                    <?php } ?>

                <?php } else { ?>
                    <tr>
                        <td colspan="3" class="empty-message">No delivered food sales yet.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>🧾 All Orders</h2>
            <a class="report-link" href="orders.php">Manage Orders →</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Food Image</th>
                        <th>Food Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <?php
                        $food_name = mysqli_real_escape_string(
                            $conn,
                            $row['food_name']
                        );

                        $image_query = mysqli_query(
                            $conn,
                            "SELECT image FROM menu_items
                             WHERE food_name='$food_name'
                             LIMIT 1"
                        );

                        $image_row = $image_query
                            ? mysqli_fetch_assoc($image_query)
                            : null;

                        $image_name = $image_row['image'] ?? '';
                        ?>

                        <tr>
                            <td>#<?php echo (int)$row['id']; ?></td>

                            <td>
                                <?php if ($image_name !== '') { ?>
                                    <img
                                        class="food-image"
                                        src="../images/<?php echo rawurlencode(basename($image_name)); ?>"
                                        alt="<?php echo htmlspecialchars($row['food_name']); ?>"
                                    >
                                <?php } else { ?>
                                    <span>🍽️</span>
                                <?php } ?>
                            </td>

                            <td><?php echo htmlspecialchars($row['food_name']); ?></td>

                            <td>₦<?php echo number_format((float)$row['price']); ?></td>

                            <td><?php echo (int)$row['quantity']; ?></td>

                            <td>₦<?php echo number_format((float)$row['total']); ?></td>

                            <td>
                                <a
                                    class="delete-button"
                                    href="delete_order.php?id=<?php echo (int)$row['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this order?');"
                                >Delete</a>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="empty-message">No orders available.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>📊 Sales Report</h2>
            <a class="report-link" href="report.php">Open Full Report →</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Quantity Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($sales_query && mysqli_num_rows($sales_query) > 0) { ?>

                    <?php while ($sale = mysqli_fetch_assoc($sales_query)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sale['food_name']); ?></td>
                            <td><?php echo (int)$sale['total_sold']; ?></td>
                            <td>₦<?php echo number_format((float)$sale['revenue']); ?></td>
                        </tr>
                    <?php } ?>

                <?php } else { ?>
                    <tr>
                        <td colspan="3" class="empty-message">No delivered sales available yet.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

</main>

<footer class="footer">
    <strong>Moor Food Court</strong> &nbsp; | &nbsp; Fast, Fresh and Affordable
    <br>
    Admin Dashboard
</footer>

</body>
</html>