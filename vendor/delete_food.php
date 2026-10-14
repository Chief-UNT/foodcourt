<?php

session_start();

if (!isset($_SESSION['vendor_id'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

$vendor_id = (int) $_SESSION['vendor_id'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid food ID.");
}

/* Find food belonging to this vendor */
$check = mysqli_query(
    $conn,
    "SELECT id, image
     FROM menu_items
     WHERE id = $id
     AND vendor_id = $vendor_id
     LIMIT 1"
);

if (!$check) {
    die("Database error: " . mysqli_error($conn));
}

if (mysqli_num_rows($check) === 0) {
    die("Food not found or you are not authorised to delete it.");
}

$food = mysqli_fetch_assoc($check);

/* Delete the food */
$delete = mysqli_query(
    $conn,
    "DELETE FROM menu_items
     WHERE id = $id
     AND vendor_id = $vendor_id"
);

if (!$delete) {
    die("Unable to delete food: " . mysqli_error($conn));
}

/* Delete the associated image if it exists */
if (!empty($food['image'])) {
    $image_path = __DIR__ . '/../images/' . basename($food['image']);

    if (is_file($image_path)) {
        unlink($image_path);
    }
}

/* Return to My Foods */
header("Location: foods.php");
exit();

?>