<?php
session_start();
include '../db.php';

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query(
    $conn,
    "SELECT * FROM vendors
    WHERE email='$email'
    AND password='$password'"
    );

    if(mysqli_num_rows($query) > 0){

        $vendor = mysqli_fetch_assoc($query);

        $_SESSION['vendor_id'] = $vendor['id'];
        $_SESSION['vendor_name'] = $vendor['vendor_name'];

        header("Location: dashboard.php");
        exit();

    }else{

        $message = "Invalid Email or Password";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Vendor Login</title>

<style>

body{
    margin:0;
    padding:0;
    font-family:Arial, sans-serif;
    background:#f5f5f5;

    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.login-box{
    width:400px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    color:#E63946;
    margin-bottom:25px;
}

.message{
    text-align:center;
    color:red;
    margin-bottom:15px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:8px;
    box-sizing:border-box;
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

</style>

</head>
<body>

<div class="login-box">

    <h2>Vendor Login</h2>

    <?php if($message != ""){ ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST">

        <input
        type="email"
        name="email"
        placeholder="Enter Email"
        required>

        <input
        type="password"
        name="password"
        placeholder="Enter Password"
        required>

        <button type="submit" name="login">
            Login
        </button>

    </form>

</div>

</body>
</html>