<?php
session_start();
include 'db_connect.php';

// --- SECURITY CHECK ---
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// 1. ASSIGN PICKUP
if (isset($_POST['assign_pickup_driver'])) {
    $req_id = $_POST['request_id'];
    $driver_id = $_POST['driver_id'];
    $conn->query("UPDATE Donation_Sell_Request SET assigned_driver_id='$driver_id', status='Scheduled' WHERE request_id='$req_id'");
    $conn->query("UPDATE Driver SET availability=0 WHERE driver_id='$driver_id'");
    echo "<script>alert('Driver assigned to Pickup!');</script>";
}

// 2. REMOVE/CHANGE PICKUP DRIVER
if (isset($_POST['remove_pickup_driver'])) {
    $req_id = $_POST['request_id'];
    $driver_id = $_POST['assigned_driver_id']; 

    // Reset Request to Pending
    $conn->query("UPDATE Donation_Sell_Request SET assigned_driver_id=NULL, status='Pending' WHERE request_id='$req_id'");
    
    // Make Driver Available Again
    $conn->query("UPDATE Driver SET availability=1 WHERE driver_id='$driver_id'");

    echo "<script>alert('Driver removed. Request is Pending again.');</script>";
}

// 3. ASSIGN DELIVERY
if (isset($_POST['assign_delivery_driver'])) {
    $order_id = $_POST['order_id'];
    $driver_id = $_POST['driver_id'];
    $date = date('Y-m-d');
    $conn->query("INSERT INTO Delivery (order_id, driver_id, status, date) VALUES ('$order_id', '$driver_id', 'Out for Delivery', '$date')");
    $conn->query("UPDATE Orders SET status='Shipped' WHERE order_id='$order_id'");
    $conn->query("UPDATE Driver SET availability=0 WHERE driver_id='$driver_id'");
    echo "<script>alert('Driver assigned to Delivery!');</script>";
}

// 4. DELETE DRIVER
if (isset($_POST['delete_driver'])) {
    $del_id = $_POST['driver_id'];
    $check_busy = $conn->query("SELECT * FROM Driver WHERE driver_id='$del_id' AND availability=0");
    if ($check_busy->num_rows > 0) {
        echo "<script>alert('Cannot delete: This driver is busy.');</script>";
    } else {
        $conn->query("DELETE FROM Driver WHERE driver_id='$del_id'");
        echo "<script>alert('Driver Removed Successfully');</script>";
    }
}

// 5. ADD DRIVER
if (isset($_POST['add_driver'])) {
    $name = $_POST['name']; $email = $_POST['email']; $contact = $_POST['contact'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $check = $conn->query("SELECT email FROM Driver WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Error: Email already exists!');</script>";
    } else {
        $conn->query("INSERT INTO Driver (name, email, password, contact, availability) VALUES ('$name', '$email', '$pass', '$contact', 1)");
        echo "<script>alert('Driver Added Successfully');</script>";
    }
}

// 6. ADD PRODUCT
if (isset($_POST['add_product'])) {
    $p_name = $_POST['p_name']; $p_price = $_POST['p_price']; $p_cat = $_POST['p_cat']; $p_desc = $_POST['p_desc'];
    $target = "uploads/" . time() . "_" . basename($_FILES["p_image"]["name"]);
    if (!is_dir("uploads/")) mkdir("uploads/", 0777, true);
    if (move_uploaded_file($_FILES["p_image"]["tmp_name"], $target)) {
        $conn->query("INSERT INTO Recycled_Product (product_name, description, category, price, status, image) VALUES ('$p_name', '$p_desc', '$p_cat', '$p_price', 'Available', '$target')");
    }
}

// --- ANALYTICS ---
$start = date('Y-01-01'); $end = date('Y-12-31');
$conn->query("CALL GetTotalRevenue('$start', '$end', @total)");
$res_rev = $conn->query("SELECT @total as revenue");
$revenue = $res_rev->fetch_assoc()['revenue'] ?? 0.00;

$result_report = $conn->query("SELECT * FROM Full_Sales_Report ORDER BY order_date DESC LIMIT 5");
$result_logs = $conn->query("SELECT * FROM System_Logs ORDER BY log_date DESC LIMIT 5");
$result_stats = $conn->query("SELECT category, COUNT(*) as count FROM Recycled_Product WHERE status = 'Available' GROUP BY category");

// --- DRIVER LISTS ---
$drivers_free = $conn->query("SELECT * FROM Driver WHERE availability=1");
$drivers_busy = $conn->query("SELECT * FROM Driver WHERE availability=0");
?>