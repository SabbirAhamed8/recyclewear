<?php
session_start();
include 'db_connect.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Driver WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['staff_id'] = $row['driver_id'];
            $_SESSION['role'] = 'Driver';
            $_SESSION['name'] = $row['name'];
            header("Location: driver_dashboard.php");
            exit();
        } else { $error = "Incorrect Password"; }
    } else { $error = "Driver email not found."; }
}
?>