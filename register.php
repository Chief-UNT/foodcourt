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
    font-family:Arial;
    background:#f5f5f5;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.form-box{
    background:white;
    padding:30px;
    width:400px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
}

button{
    width:100%;
    padding:12px;
    background:#8b0000;
    color:white;
    border:none;
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
<!-- name="password" -->
placeholder="Password"
required>

<button
type="submit"
name="register">

Register

</button>

</form>

</div>

</body>
</html>