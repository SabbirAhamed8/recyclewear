<?php
include 'db_connect.php';
session_start();

$dashboardLink = "";
$dashboardLabel = "";

if(isset($_SESSION['customer_id'])) {
    $dashboardLink = "customer_dashboard.php";
    $dashboardLabel = "Customer Dashboard";
} elseif(isset($_SESSION['admin_id'])) {
    $dashboardLink = "admin_dashboard.php";
    $dashboardLabel = "Admin Dashboard";
} elseif(isset($_SESSION['staff_id'])) {
    $dashboardLink = "driver_dashboard.php";
    $dashboardLabel = "Driver Dashboard";
}

$sql = "SELECT * FROM Recycled_Product WHERE status='Available' LIMIT 6";
$result = $conn->query($sql);
?>