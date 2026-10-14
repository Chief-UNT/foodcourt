<?php

session_start();
include '../db.php';

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users
              WHERE email='$email'
              AND password='$password'
              AND role='customer'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        $_SESSION['customer'] = $user['username'];
        $_SESSION['customer_id'] = $user['id'];

        header("Location: ../index.php");
        exit();

    }else{

        $message = "Invalid Login Details";

    }
}

?><!DOCTYPE html><html>
<head>
<title>Moor Food Court - Customer Login</title><style>

body{
    margin:0;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f5f5;
    font-family:Arial, sans-serif;
}

.login-box{
    width:420px;
    background:white;
    padding:40px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.12);
}

.logo{
    text-align:center;
    margin-bottom:20px;
}

.logo img{
    width:80px;
}

.logo h2{
    color:#E63946;
    margin-top:10px;
}
.message{
    color:red;
    text-align:center;
    margin-bottom:15px;
    font-weight:bold;
}
input{
    width:100%;
    padding:15px;
    margin:10px 0;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:16px;
}

button{
    width:100%;
    padding:15px;
    background:#E63946;
    color:white;
    border:none;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    background:#d62839;
}
.register-link{
    margin-top:20px;
}

.register-link a{
    color:#8b0000;
    text-decoration:none;
    font-weight:bold;
}

.register-link a:hover{
    color:blue;
}

</style></head>
<body>
    <div class="login-box"><div class="logo">

    <img src="../images/logo.png">

    <h2>Moor Food Court</h2>

    <p>Welcome Back</p>

</div><div class="logo">🍔</div><h2>Customer Login</h2><p class="message">
<?php echo $message; ?>
</p><form method="POST"><input
type="email"
name="email"
placeholder="Enter Email"
required>

<input
type="password"
name="password"
placeholder="Enter Password"
required>

<button
type="submit"
name="login">
Login
</button>
</form>

<p style="text-align:center;margin-top:15px;">

    Don't have an account?

    <a href="../register.php">
        Register
    </a>

</p>

</div>
</html>