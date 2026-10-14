<?php
session_start();

if (!isset($_SESSION['vendor_id'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

$vendor_id = $_SESSION['vendor_id'];

$foods_query = mysqli_query(
    $conn,
    "SELECT * FROM menu_items WHERE vendor_id='$vendor_id'"
);
$foods = mysqli_num_rows($foods_query);

$orders_query = mysqli_query(
    $conn,
    "SELECT * FROM orders WHERE vendor_id='$vendor_id'"
);
$orders = mysqli_num_rows($orders_query);

$revenue_query = mysqli_query(
    $conn,
    "SELECT SUM(total) AS revenue
     FROM orders
     WHERE vendor_id='$vendor_id'
     AND status='Delivered'"
);

$revenue_row = mysqli_fetch_assoc($revenue_query);
$revenue = $revenue_row['revenue'] ?? 0;
$vendor_name = $_SESSION['vendor_name'] ?? 'Vendor';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vendor Dashboard | Moor Food Court</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #252525;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #8b0000;
            color: white;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .brand {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .brand span {
            color: #ffcccc;
        }

        .brand-subtitle {
            font-size: 12px;
            color: #f5dada;
            margin-bottom: 45px;
        }

        .nav-label {
            font-size: 11px;
            color: #f0baba;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
        }

        .nav-link {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 14px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: background 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.16);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 35px;
        }

        .logout {
            display: block;
            text-align: center;
            padding: 13px;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 10px;
            color: white;
            text-decoration: none;
            transition: 0.2s;
        }

        .logout:hover {
            background: white;
            color: #8b0000;
        }

        /* MAIN CONTENT */
        .main {
            flex: 1;
            min-width: 0;
            padding: 35px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 35px;
        }

        .welcome h1 {
            font-size: 28px;
            margin-bottom: 9px;
        }

        .welcome p {
            color: #777;
            font-size: 14px;
            line-height: 1.6;
        }

        .vendor-badge {
            background: white;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
        }

        .section-title {
            font-size: 19px;
            margin-bottom: 20px;
        }

        /* STATISTICS */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
            margin-bottom: 35px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            border: 1px solid #eeeeee;
            box-shadow: 0 5px 20px rgba(0,0,0,0.035);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.07);
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .card-label {
            color: #777;
            font-size: 14px;
            font-weight: 600;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
        }

        .food-icon {
            background: #fff0e5;
        }

        .order-icon {
            background: #e8f1ff;
        }

        .revenue-icon {
            background: #e6f8ed;
        }

        .card h2 {
            font-size: 30px;
            color: #8b0000;
            margin-bottom: 10px;
            overflow-wrap: anywhere;
        }

        .card-description {
            font-size: 12px;
            color: #999;
            line-height: 1.5;
        }

        /* QUICK ACTIONS */
        .actions-panel {
            background: white;
            border: 1px solid #eeeeee;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.035);
        }

        .actions-panel p {
            color: #777;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 22px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 14px 22px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-primary {
            background: #8b0000;
            color: white;
        }

        .btn-primary:hover {
            background: #690000;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #fff0f0;
            color: #8b0000;
            border: 1px solid #f2dada;
        }

        .btn-secondary:hover {
            background: #ffe0e0;
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 35px;
        }

        /* TABLET */
        @media (max-width: 1000px) {
            .sidebar {
                width: 215px;
            }

            .main {
                padding: 25px;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* MOBILE */
        @media (max-width: 650px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 20px;
            }

            .brand-subtitle {
                margin-bottom: 22px;
            }

            .nav-label {
                margin-bottom: 10px;
            }

            .nav-link {
                display: inline-block;
                margin: 0 5px 5px 0;
                padding: 10px 12px;
                font-size: 13px;
            }

            .sidebar-bottom {
                margin-top: 15px;
                padding-top: 5px;
            }

            .logout {
                display: inline-block;
                padding: 10px 18px;
            }

            .main {
                padding: 22px 16px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 28px;
            }

            .welcome h1 {
                font-size: 24px;
            }

            .stats {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .card {
                padding: 22px;
            }

            .actions-panel {
                padding: 22px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <div class="brand">
            MOOR <span>FOOD</span>
        </div>

        <div class="brand-subtitle">
            Vendor Management Portal
        </div>

        <div class="nav-label">Main Menu</div>

        <a href="dashboard.php" class="nav-link active">
            🏠 Dashboard
        </a>

        <a href="foods.php" class="nav-link">
            🍔 Manage Foods
        </a>

        <a href="orders.php" class="nav-link">
            📦 View Orders
        </a>

        <div class="sidebar-bottom">
            <a href="logout.php" class="logout">
                ↪ Logout
            </a>
        </div>

    </aside>

    <main class="main">

        <header class="topbar">

            <div class="welcome">
                <h1>
                    Welcome, <?php
                    echo htmlspecialchars($vendor_name, ENT_QUOTES, 'UTF-8');
                    ?>!
                </h1>

                <p>
                    Here's an overview of your food business.
                </p>
            </div>

            <div class="vendor-badge">
                🏪 Vendor Account
            </div>

        </header>

        <h2 class="section-title">Business Overview</h2>

        <section class="stats">

            <div class="card">
                <div class="card-top">
                    <span class="card-label">Total Foods</span>
                    <div class="card-icon food-icon">🍔</div>
                </div>

                <h2><?php echo $foods; ?></h2>

                <div class="card-description">
                    Food items in your menu
                </div>
            </div>

            <div class="card">
                <div class="card-top">
                    <span class="card-label">Total Orders</span>
                    <div class="card-icon order-icon">📦</div>
                </div>

                <h2><?php echo $orders; ?></h2>

                <div class="card-description">
                    Orders assigned to your business
                </div>
            </div>

            <div class="card">
                <div class="card-top">
                    <span class="card-label">Total Revenue</span>
                    <div class="card-icon revenue-icon">💰</div>
                </div>

                <h2>
                    ₦<?php echo number_format((float)$revenue, 2); ?>
                </h2>

                <div class="card-description">
                    Revenue from delivered orders
                </div>
            </div>

        </section>

        <section class="actions-panel">

            <h2 class="section-title">Quick Actions</h2>

            <p>
                Manage your menu items and keep track of customer orders
                directly from your dashboard.
            </p>

            
            <div class="actions">

                <a href="foods.php" class="btn btn-primary">
                    + Manage Foods
                </a>

                <a href="orders.php" class="btn btn-secondary">
                    📋 View Orders
                </a>

            </div>

        </section>

        <div class="footer">
            &copy; <?php echo date('Y'); ?> Moor Food Court.
            All rights reserved.
        </div>

    </main>

</div>

</body>
</html>