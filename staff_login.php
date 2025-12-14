<?php require 'staff_login_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Driver Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="staff_login.css">
</head>
<body>
    <header><h1>Driver Login</h1></header>
    <div class="container login-box">
        <?php if($error) echo "<div class='alert' style='background:#ffcccc; color:red;'>$error</div>"; ?>
        <form method="post">
            <label>Email:</label>
            <input type="email" name="email" required placeholder="driver@recyclewear.com">
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>