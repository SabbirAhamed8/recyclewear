<?php require 'register_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - RecycleWear</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <header><h1>Customer Registration</h1></header>
    <div class="container reg-box">
        <?php if($message) echo "<div class='alert'>$message</div>"; ?>
        
        <form method="post">
            <label>Full Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label>Password (Min 8 chars, 1 Number, 1 Special Char):</label>
            <input type="password" name="password" required>
            
            <label>Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            
            <button type="submit">Register</button>
        </form>
        <p style="text-align:center;">Already have an account? <a href="customer_login.php">Login</a></p>
    </div>
</body>
</html>