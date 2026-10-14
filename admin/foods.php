<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$search = $_GET['search'] ?? '';
$total_foods = mysqli_num_rows(
mysqli_query(
$conn,
"SELECT * FROM menu_items"
)
);
$query = mysqli_query(
$conn,
"SELECT menu_items.*, vendors.vendor_name
FROM menu_items
LEFT JOIN vendors
ON menu_items.vendor_id = vendors.id
WHERE menu_items.food_name LIKE '%$search%'
OR vendors.vendor_name LIKE '%$search%'
ORDER BY menu_items.id DESC"
);

?><!DOCTYPE html><html><head>
<title>Manage Foods</title><style>

body{
    font-family:Arial,sans-serif;
    background:#f5f5f5;
    padding:20px;
}

h1{
    color:#E63946;
}

.back-btn{
    display:inline-block;
    background:#E63946;
    color:white;
    text-decoration:none;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:15px;
}

.search-box{
    margin-bottom:20px;
}

.search-box input{
    padding:10px;
    width:300px;
    border:1px solid #ccc;
    border-radius:8px;
}

.search-box button{
    padding:10px 15px;
    background:#E63946;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
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
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#fafafa;
}

img{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:10px;
}

.edit-btn{
    background:#3498db;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

.delete-btn{
    background:red;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

.vendor{
    color:#E63946;
    font-weight:bold;
}

</style></head><body><a href="dashboard.php" class="back-btn">
← Back To Dashboard
</a>
<h1>Manage Food Items</h1>

<div style="
background:white;
padding:15px;
margin-bottom:20px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
font-size:20px;
font-weight:bold;
color:#E63946;
">

🍽 Total Foods:
<?php echo $total_foods; ?>

</div>
<div class="search-box"><form method="GET"><input
type="text"
name="search"
placeholder="Search food or vendor..."
value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">
Search
</button></form></div><table><tr>
    <th>ID</th>
    <th>Image</th>
    <th>Food Name</th>
    <th>Vendor</th>
    <th>Category</th>
    <th>Price</th>
    <th>Edit</th>
    <th>Delete</th>
</tr><?php while($row=mysqli_fetch_assoc($query)){ ?><tr><td><?php echo $row['id']; ?></td><td>
<img src="../images/<?php echo $row['image']; ?>">
</td><td><?php echo $row['food_name']; ?></td><td class="vendor">
<?php echo $row['vendor_name']; ?>
</td><td>
<?php echo $row['category']; ?>
</td><td>
₦<?php echo number_format($row['price']); ?>
</td><td>
<a href="edit_food.php?id=<?php echo $row['id']; ?>" class="edit-btn">
Edit
</a>
</td><td>
<a href="delete_food.php?id=<?php echo $row['id']; ?>"
class="delete-btn"
onclick="return confirm('Delete this food item?')">
Delete
</a>
</td></tr><?php } ?></table></body>
</html>