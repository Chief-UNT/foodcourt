<?php

include 'db.php';

$message = "";

if(isset($_POST['register'])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($check) > 0){

        $message = "Email already exists";

    }else{

        $query = "INSERT INTO users
        (username,email,password,role)

        VALUES

        ('$username','$email','$password','customer')";

        mysqli_query($conn,$query);

        $message = "Registration Successful";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:linear-gradient(135deg,#E63946,#ff7b7b);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.register-box{
    width:450px;
    background:white;
    padding:40px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
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

.message{
    color:green;
    margin-bottom:10px;
}

</style>

</head>
<body>

<div class="form-box">

<h2>Customer Registration</h2>
<div class="logo">

    <img src="images/logo.png">

    <h2>Moor Food Court</h2>

    <p>Create Your Account</p>

</div>

<p class="message">
<?php echo $message; ?>
</p>

<form method="POST">

<input
type="text"
name="username"
placeholder="Username"
required>

<input
type="email"
name="email"
placeholder="Email"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<button
type="submit"
name="register">

Register
<p style="text-align:center;margin-top:15px;">

Already have an account?

<a href="customer/login.php">
Login
</a>

</p>

</button>

</form>

</div>

</body>
</html>