<?php
session_start();
include 'db_connect.php';

// 1. Security Check
if (!isset($_SESSION['customer_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$cid = $_SESSION['customer_id'];
$msg = "";

// --- FIX: FETCH POINTS (This was missing) ---
$sql_points = "SELECT points FROM Customer WHERE customer_id='$cid'";
$result_points = $conn->query($sql_points);
$points = 0; // Default value
if ($result_points->num_rows > 0) {
    $row_p = $result_points->fetch_assoc();
    $points = $row_p['points'] ? $row_p['points'] : 0;
}
// --------------------------------------------

// 2. DELETE REQUEST
if (isset($_POST['delete'])) {
    $rid = $_POST['req_id'];
    $chk = $conn->query("SELECT status FROM Donation_Sell_Request WHERE request_id='$rid' AND customer_id='$cid'");
    if ($chk->num_rows > 0) {
        $row = $chk->fetch_assoc();
        if ($row['status'] == 'Pending') {
            $conn->query("DELETE FROM Donation_Sell_Request WHERE request_id='$rid'");
            $msg = "Request deleted successfully.";
        } else {
            $msg = "Cannot delete: Request is already processed.";
        }
    }
}

// 3. SUBMIT REQUEST (With Image Upload)
if (isset($_POST['submit'])) {
    $type = $_POST['item_type']; 
    $cond = $_POST['condition']; 
    $req = $_POST['request_type']; 
    $date = $_POST['pickup_date'];
    $image_path = ""; 

    // Handle Image Upload
    if (!empty($_FILES['item_image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = time() . "_" . basename($_FILES["item_image"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO Donation_Sell_Request (customer_id, item_type, condition_status, request_type, pickup_date, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $cid, $type, $cond, $req, $date, $image_path);
    
    if ($stmt->execute()) {
        $msg = "Request Submitted Successfully!";
    } else {
        $msg = "Error submitting request.";
    }
}

// --- NEW FEATURE: FETCH ORDER HISTORY ---
// We join Orders with Recycled_Product to get the product name and image
$sql_orders = "SELECT o.order_id, o.order_date, o.status, p.product_name, p.price, p.image 
               FROM Orders o 
               JOIN Recycled_Product p ON o.product_id = p.product_id 
               WHERE o.customer_id='$cid' 
               ORDER BY o.order_date DESC";
$result_orders = $conn->query($sql_orders);
?>