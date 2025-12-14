<?php require 'shop_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="shop.css">
</head>
<body>
    <header>
        <h1><a href="index.php" style="text-decoration:none; color:white;">♻️ RecycleWear</a></h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <?php if(isset($_SESSION['customer_id'])): ?>
                <a href="customer_dashboard.php" style="background:white; color:#2E7D32; padding:5px 10px; border-radius:4px;">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="customer_login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <h2 style="color: #2E7D32;">Marketplace</h2>
        <div class="filter-bar">
            <form method="get" style="display:flex; align-items:center; gap:10px; width:100%;">
                <label>Filter:</label>
                <select name="category" style="width:auto; margin:0;">
                    <option value="All">All</option>
                    <?php while($c = $cats->fetch_assoc()) echo "<option value='{$c['category']}'>{$c['category']}</option>"; ?>
                </select>
                <button type="submit" style="width:auto; margin:0;">Go</button>
            </form>
        </div>

        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";
                    
                    if (!empty($row['image']) && file_exists($row['image'])) {
                        echo "<img src='" . $row['image'] . "' class='prod-img'>";
                    } else {
                        echo "<div class='prod-placeholder'>🛍️</div>";
                    }

                    echo "<h4>" . htmlspecialchars($row['product_name']) . "</h4>
                          <p class='price'>$" . htmlspecialchars($row['price']) . "</p>
                          <a href='checkout.php?id=" . $row['product_id'] . "'><button>Buy Now</button></a>
                          </div>";
                }
            } else { echo "<p>No products.</p>"; }
            ?>
        </div>
    </div>
</body>
</html>