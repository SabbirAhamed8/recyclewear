<?php
session_start();
include 'db_connect.php';

$cat_filter = "";
if (isset($_GET['category']) && $_GET['category'] != 'All') {
    $cat = $conn->real_escape_string($_GET['category']);
    $cat_filter = " AND category = '$cat'";
}

$sql_prods = "SELECT * FROM Recycled_Product WHERE status='Available' $cat_filter ORDER BY product_id DESC";
$result = $conn->query($sql_prods);

$sql_cats = "SELECT DISTINCT category FROM Recycled_Product WHERE status='Available'";
$cats = $conn->query($sql_cats);
?>