<?php
session_start();
include 'db_connect.php';

// 1. Check ID
if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = $_GET['id'];

// 2. Redirect to Login if needed
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['redirect_to'] = "checkout.php?id=" . $product_id;
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// 3. Fetch Product Details
$sql = "SELECT * FROM Recycled_Product WHERE product_id = '$product_id' AND status = 'Available'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("<div class='container'><h3>Product not found or sold.</h3><a href='shop.php'>Go Back</a></div>");
}

$product = $result->fetch_assoc();

// 4. Handle Purchase
if (isset($_POST['confirm_order'])) {
    // Create Order
    $sql1 = "INSERT INTO Orders (customer_id, product_id, quantity, status) VALUES ('$customer_id', '$product_id', 1, 'Processing')";
    // Mark as Sold
    $sql2 = "UPDATE Recycled_Product SET status='Sold' WHERE product_id='$product_id'";
    
    if ($conn->query($sql1) && $conn->query($sql2)) {
        echo "<script>alert('Order Placed Successfully!'); window.location.href='customer_dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>