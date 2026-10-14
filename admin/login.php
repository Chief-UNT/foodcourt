<?php

session_start();
include '../db.php';

if(isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users 
              WHERE username='$username' 
              AND password='$password' 
              AND role='admin'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {

        $_SESSION['admin'] = $username;

        header("Location: dashboard.php");
        exit();

    } else {

        echo "<script>alert('Wrong Username or Password');</script>";

    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body{
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f6f5f8, #fcf6f6);
        }

        .login-box{
            background: white;
            width: 350px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-box h2{
            margin-bottom: 25px;
            color: darkred;
            font-size: 28px;
        }

        .login-box input{
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        .login-box input:focus{
            outline: none;
            border-color: darkred;
        }

        .login-box button{
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            background: darkred;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-box button:hover{
            background: red;
        }

        .admin-icon{
            font-size: 50px;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>

<div class="login-box">

    <div class="admin-icon">🍔</div>

    <h2>Admin Login</h2>

    <form method="POST">

        <input type="text" name="username" placeholder="Enter Username" required>

        <input type="password" name="password" placeholder="Enter Password" required>

        <button type="submit" name="login">
            Login
        </button>

    </form>

</div>

</body>
</html>