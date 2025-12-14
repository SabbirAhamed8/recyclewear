<?php require 'customer_login_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - RecycleWear</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header><h1>Login</h1></header>
    <div class="container login-wrap">
        <?php if($redirect_msg) echo "<div style='color:green;'>$redirect_msg</div>"; ?>
        <?php if($error) echo "<div style='color:red;'>$error</div>"; ?>
        
        <form method="post">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>New here? <a href="register.php">Register</a></p>
    </div>
</body>
</html>