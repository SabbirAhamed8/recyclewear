<?php
session_start();
include 'db_connect.php';

$error = "";
$redirect_msg = "";

// 1. Check if there is a redirect message (from Shop)
if(isset($_SESSION['redirect_to'])) {
    $redirect_msg = "Please login to complete your purchase.";
}

// 2. Handle Login Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check Database for Email
    $sql = "SELECT * FROM Customer WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify Password
        if (password_verify($password, $row['password'])) {
            // SUCCESS: Set Session Variables
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['name'] = $row['name'];
            
            // Redirect Logic
            if (isset($_SESSION['redirect_to'])) {
                $url = $_SESSION['redirect_to'];
                unset($_SESSION['redirect_to']); 
                header("Location: " . $url);
            } else {
                header("Location: customer_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>