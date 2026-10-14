<?php

$conn = mysqli_connect("localhost", "root", "", "foodcourt");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>