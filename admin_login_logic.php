<?php
session_start();
include 'db_connect.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Admin WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['admin_name'] = $row['name'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid Admin Credentials";
    }
}
// --- ADVANCED SQL 1: AGGREGATE & GROUP BY (Inventory Stats) ---
// Count how many products we have in each category
$sql_stats = "SELECT category, COUNT(*) as count, AVG(price) as avg_price 
              FROM Recycled_Product 
              WHERE status = 'Available' 
              GROUP BY category";
$result_stats = $conn->query($sql_stats);

// --- ADVANCED SQL 2: SUB QUERY (Find Active Buyers) ---
// Select customers who have placed at least one order (ID is inside the Orders table)
$sql_vip = "SELECT * FROM Customer 
            WHERE customer_id IN (SELECT DISTINCT customer_id FROM Orders)";
$result_vip = $conn->query($sql_vip);

// --- ADVANCED SQL 3: JOIN (Detailed Sales Report) ---
// Join 3 tables: Orders, Customer, and Recycled_Product to get full details
$sql_report = "SELECT o.order_id, o.order_date, c.name AS customer_name, p.product_name, p.price 
               FROM Orders o 
               INNER JOIN Customer c ON o.customer_id = c.customer_id
               INNER JOIN Recycled_Product p ON o.product_id = p.product_id
               ORDER BY o.order_date DESC LIMIT 5";
$result_report = $conn->query($sql_report);
?>