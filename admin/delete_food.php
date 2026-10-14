<?php

include '../db.php';

$id = $_GET['id'];

$query = "DELETE FROM menu_items WHERE id='$id'";

mysqli_query($conn, $query);

header("Location: foods.php");

?>