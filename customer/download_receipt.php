<?php

include '../db.php';
require('../fpdf/fpdf.php');


/*
Check receipt ID
*/
if (!isset($_GET['id']) || empty($_GET['id'])) {

    die("Invalid Receipt");

}


$id = (int)$_GET['id'];


/*
Get the selected order
*/
$query = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     WHERE id='$id'
     LIMIT 1"
);


if (!$query || mysqli_num_rows($query) == 0) {

    die("Receipt Not Found");

}


$first_order = mysqli_fetch_assoc($query);


/*
Get payment reference
*/
$payment_reference = $first_order['payment_reference'];

$reference_safe = mysqli_real_escape_string(
    $conn,
    $payment_reference
);


/*
Get all items from the same payment
*/
$orders = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     WHERE payment_reference='$reference_safe'
     ORDER BY id ASC"
);


if (!$orders || mysqli_num_rows($orders) == 0) {

    die("Order Items Not Found");

}


/*
Calculate grand total
*/
$grand_total = 0;

$order_items = [];


while ($item = mysqli_fetch_assoc($orders)) {

    $order_items[] = $item;

    $grand_total += (float)$item['total'];

}


/*
Create PDF
*/
$pdf = new FPDF();

$pdf->AddPage();


/*
Title
*/
$pdf->SetFont('Arial', 'B', 18);

$pdf->Cell(
    190,
    10,
    'MOOR FOOD COURT',
    0,
    1,
    'C'
);


$pdf->SetFont('Arial', '', 11);

$pdf->Cell(
    190,
    7,
    'Order Receipt',
    0,
    1,
    'C'
);


$pdf->Ln(8);


/*
Order information
*/
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(
    100,
    8,
    'Order ID: '.$first_order['id'],
    0,
    1
);

$pdf->Cell(
    100,
    8,
    'Customer: '.$first_order['customer_name'],
    0,
    1
);

$pdf->Cell(
    100,
    8,
    'Phone: '.$first_order['phone'],
    0,
    1
);

$pdf->Cell(
    100,
    8,
    'Address: '.$first_order['address'],
    0,
    1
);

$pdf->Cell(
    100,
    8,
    'Date: '.$first_order['created_at'],
    0,
    1
);

$pdf->Cell(
    100,
    8,
    'Payment: '.$first_order['payment_method'],
    0,
    1
);

$pdf->Cell(
    100,
    8,
    'Payment Reference: '.$payment_reference,
    0,
    1
);

$pdf->Cell(
    100,
    8,
    'Payment Status: '.$first_order['payment_status'],
    0,
    1
);


$pdf->Ln(8);


/*
Order items heading
*/
$pdf->SetFont('Arial', 'B', 13);

$pdf->Cell(
    190,
    8,
    'ORDER ITEMS',
    0,
    1
);


$pdf->Ln(2);


/*
Table headings
*/
$pdf->SetFont('Arial', 'B', 11);

$pdf->Cell(75, 8, 'Food', 1);

$pdf->Cell(35, 8, 'Price', 1);

$pdf->Cell(25, 8, 'Qty', 1);

$pdf->Cell(55, 8, 'Total', 1);

$pdf->Ln();


/*
Table items
*/
$pdf->SetFont('Arial', '', 11);


foreach ($order_items as $item) {

    $pdf->Cell(
        75,
        8,
        substr($item['food_name'], 0, 35),
        1
    );

    $pdf->Cell(
        35,
        8,
        'N'.number_format($item['price'], 2),
        1
    );

    $pdf->Cell(
        25,
        8,
        $item['quantity'],
        1
    );

    $pdf->Cell(
        55,
        8,
        'N'.number_format($item['total'], 2),
        1
    );

    $pdf->Ln();

}


/*
Grand total
*/
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(
    135,
    10,
    'GRAND TOTAL',
    1
);

$pdf->Cell(
    55,
    10,
    'N'.number_format($grand_total, 2),
    1
);

$pdf->Ln(15);


/*
Order status
*/
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(
    100,
    8,
    'Order Status: '.$first_order['status'],
    0,
    1
);


$pdf->Ln(10);


/*
Thank you message
*/
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(
    190,
    8,
    'Thank you for ordering from Moor Food Court',
    0,
    1,
    'C'
);


/*
Output PDF
*/
$pdf->Output();

?>