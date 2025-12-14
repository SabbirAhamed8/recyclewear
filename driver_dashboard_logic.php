<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['staff_id']) || $_SESSION['role'] != 'Driver') {
    header("Location: staff_login.php");
    exit();
}
$driver_id = $_SESSION['staff_id'];
$message = "";

// 1. COMPLETE PICKUP
if (isset($_POST['complete_pickup'])) {
    $req_id = $_POST['request_id'];
    $conn->query("UPDATE Donation_Sell_Request SET status='Picked Up' WHERE request_id='$req_id'");
    $conn->query("UPDATE Driver SET availability=1 WHERE driver_id='$driver_id'");
    $message = "Pickup Completed! You are now Available.";
}

// 2. COMPLETE DELIVERY
if (isset($_POST['complete_delivery'])) {
    $del_id = $_POST['delivery_id'];
    $order_id = $_POST['order_id'];
    $conn->query("UPDATE Delivery SET status='Delivered' WHERE delivery_id='$del_id'");
    $conn->query("UPDATE Orders SET status='Delivered' WHERE order_id='$order_id'");
    $conn->query("UPDATE Driver SET availability=1 WHERE driver_id='$driver_id'");
    $message = "Delivery Completed! You are now Available.";
}
?>