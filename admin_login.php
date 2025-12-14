<?php require 'admin_login_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - RecycleWear</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <header><h1>Admin Panel Login</h1></header>
    <div class="container login-box">
        <?php if($error) echo "<div class='alert' style='background: #ffcccc; color: red;'>$error</div>"; ?>
        <form method="post">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>