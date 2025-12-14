<?php require 'checkout_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - RecycleWear</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <header><h1>Confirm Purchase</h1></header>
    <div class="container checkout-box">
        <h2>You are about to buy:</h2>
        <div class="product-preview">
            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
            <p style="color: #666;"><?php echo htmlspecialchars($product['description']); ?></p>
            <h1 class="price-tag">$<?php echo htmlspecialchars($product['price']); ?></h1>
            
            <form method="post">
                <button type="submit" name="confirm_order">Confirm & Pay</button>
            </form>
            <br>
            <a href="shop.php" class="cancel-link">Cancel</a>
        </div>
    </div>
</body>
</html>