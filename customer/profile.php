<?php

session_start();

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$id = $_SESSION['customer_id'];

$query = "SELECT * FROM users WHERE id='$id'";
$result = mysqli_query($conn,$query);

$user = mysqli_fetch_assoc($result);

?><!DOCTYPE html><html>
<head>
<title>My Profile</title><style>

body{
    font-family:Arial;
    background:#f5f5f5;
    padding:40px;
}

.profile-box{
    max-width:500px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
}

h2{
    color:#8b0000;
    text-align:center;
    margin-bottom:20px;
}

.info{
    margin-bottom:15px;
    font-size:18px;
}

.label{
    font-weight:bold;
    color:#8b0000;
}

.btn{
    display:inline-block;
    background:#8b0000;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:8px;
    margin-top:20px;
}

.btn:hover{
    background:red;
}

</style></head>
<body><div class="profile-box"><h2>My Profile</h2><div class="info">
<span class="label">Username:</span>
<?php echo $user['username']; ?>
</div><div class="info">
<span class="label">Email:</span>
<?php echo $user['email']; ?>
</div><div class="info">
<span class="label">Role:</span>
<?php echo $user['role']; ?>
</div><a href="../index.php" class="btn">
Back Home
<a href="edit_profile.php" class="btn">
    Edit Profile
</a>
</a></div></body>
</html>