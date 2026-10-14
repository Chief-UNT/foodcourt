<?php

session_start();

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

include '../db.php';

$id = $_SESSION['customer_id'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $username = $_POST['username'];
    $email = $_POST['email'];

    mysqli_query($conn,
    "UPDATE users
    SET username='$username',
    email='$email'
    WHERE id='$id'");

    $_SESSION['customer'] = $username;

    header("Location: profile.php");
}

?><!DOCTYPE html><html>
<head>
<title>Edit Profile</title><style>
body{
    font-family:Arial;
    background:#f5f5f5;
    padding:40px;
}

.box{
    max-width:500px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
}

button{
    background:#8b0000;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
}
</style></head>
<body><div class="box"><h2>Edit Profile</h2><form method="POST"><input
type="text"
name="username"
value="<?php echo $user['username']; ?>"
required>

<input
type="email"
name="email"
value="<?php echo $user['email']; ?>"
required>

<button
type="submit"
name="update">
Update Profile
</button>

</form></div></body>
</html>