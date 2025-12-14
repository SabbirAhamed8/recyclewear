<?php
include 'db_connect.php';
$message = "";

// Initialize variables to keep data in form if validation fails
$name = "";
$email = "";
$address = "";
$phone = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capture the input immediately
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password']; 

    // --- STRONG PASSWORD VALIDATION ---
    if (strlen($password) < 8) {
        $message = "Error: Password must be at least 8 characters long.";
    } 
    elseif (!preg_match("#[0-9]+#", $password)) {
        $message = "Error: Password must include at least one number.";
    }
    elseif (!preg_match("#[A-Z]+#", $password)) {
        $message = "Error: Password must include at least one uppercase letter.";
    }
    elseif (!preg_match("#[\W]+#", $password)) {
        $message = "Error: Password must include at least one special character (e.g., !@#$).";
    }
    else {
        // Validation Passed
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check for duplicate email first to avoid SQL error crashing the page
        $check = $conn->query("SELECT * FROM Customer WHERE email='$email'");
        if ($check->num_rows > 0) {
            $message = "Error: This email is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO Customer (name, email, password, address, phone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $address, $phone);
            
            if ($stmt->execute()) {
                $message = "Registration successful! <a href='customer_login.php'>Login here</a>";
                // Clear variables on success so form resets
                $name = ""; $email = ""; $address = ""; $phone = "";
            } else {
                $message = "Error: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>