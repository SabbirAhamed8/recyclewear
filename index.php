<?php require 'index_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>RecycleWear</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <div class="logo"><h1>♻️ RecycleWear</h1></div>
        <nav style="display: flex; align-items: center;">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <?php if($dashboardLink != ""): ?>
                <a href="<?php echo $dashboardLink; ?>" style="background:white; color:#2E7D32; padding:5px 10px; border-radius:4px;"><?php echo $dashboardLabel; ?></a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <div class="dropdown">
                    <button class="dropbtn">Login ▾</button>
                    <div class="dropdown-content">
                        <a href="customer_login.php">Customer</a>
                        <a href="staff_login.php">Driver</a>
                        <a href="admin_login.php">Admin</a>
                    </div>
                </div>
                <a href="register.php" style="background:white; color:#2E7D32; padding:5px 10px; border-radius:4px; margin-left:10px;">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="hero">
        <h2 style="font-size: 3rem; color: #2E7D32;">Give Your Clothes a Second Life</h2>
        <div style="margin-top: 30px;">
            <a href="register.php"><button style="width: 200px; margin-right: 10px;">Start Donating</button></a>
            <a href="shop.php"><button style="width: 200px; background-color: #333;">Shop Now</button></a>
        </div>
    </div>

    <div class="container" style="border:none; box-shadow:none;">
        <h3 style="text-align:center;">How It Works</h3>
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div class="step-card"><h2>1</h2><h3>Donate</h3><p>Schedule pickup.</p></div>
            <div class="step-card"><h2>2</h2><h3>Recycle</h3><p>We upcycle.</p></div>
            <div class="step-card"><h2>3</h2><h3>Resell</h3><p>Buy eco-friendly.</p></div>
        </div>
    </div>

    <div class="container">
        <h3>Latest Finds</h3>
        <div class="product-grid">
            <?php
            while($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                if (!empty($row['image']) && file_exists($row['image'])) {
                    echo "<img src='" . $row['image'] . "' style='width:100%; height:150px; object-fit:cover;'>";
                } else {
                    echo "<div style='height:150px; background:#f4f4f4; display:flex; align-items:center; justify-content:center; font-size:2rem;'>👕</div>";
                }
                echo "<h4>" . htmlspecialchars($row['product_name']) . "</h4>
                      <p class='price'>$" . htmlspecialchars($row['price']) . "</p>
                      <a href='checkout.php?id={$row['product_id']}'><button>Buy</button></a>
                      </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>