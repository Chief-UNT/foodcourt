<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$vendors = mysqli_query(
$conn,
"SELECT * FROM vendors ORDER BY id DESC"
);

?><!DOCTYPE html><html><head><title>Manage Vendors</title><style>

body{
    font-family:Arial,sans-serif;
    background:#f5f5f5;
    padding:20px;
}

h1{
    color:#E63946;
    margin-bottom:20px;
}

.back-btn{
    display:inline-block;
    background:#E63946;
    color:white;
    text-decoration:none;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th{
    background:#E63946;
    color:white;
    padding:15px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}

tr:hover{
    background:#fafafa;
}

.btn{
    background:red;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

.btn:hover{
    background:darkred;
}

.food-count{
    font-weight:bold;
    color:#E63946;
}

</style></head><body><a href="dashboard.php" class="back-btn">
← Back To Dashboard
</a><h1>Manage Vendors</h1><table><tr>
    <th>ID</th>
    <th>Vendor Name</th>
    <th>Username</th>
    <th>Total Foods</th>
    <th>Action</th>
</tr><?php while($row = mysqli_fetch_assoc($vendors)){ ?><?php

$food_count = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM menu_items
         WHERE vendor_id='".$row['id']."'"
    )
);

?><tr><td>
<?php echo $row['id']; ?>
</td><td>
<?php echo $row['vendor_name']; ?>
</td><td>
N/A
</td><td class="food-count">
<?php echo $food_count; ?>
</td><td>
<a href="delete_vendor.php?id=<?php echo $row['id']; ?>" class="btn">
Delete
</a>
</td></tr><?php } ?></table></body></html>