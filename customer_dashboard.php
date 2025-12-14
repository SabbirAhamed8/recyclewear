<?php require 'customer_dashboard_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
        
        <div style="background: gold; color: black; padding: 5px 15px; border-radius: 20px; font-weight: bold; margin-left: 20px;">
            🏆 Points: <?php echo $points ? $points : 0; ?>
        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h3>Make New Request</h3>
        <?php if($msg) echo "<div class='alert'>$msg</div>"; ?>
        
        <form method="post" enctype="multipart/form-data">
            <label>Item Type:</label>
            <input type="text" name="item_type" required>
            
            <label>Condition:</label>
            <select name="condition">
                <option value="Good">Good</option>
                <option value="Damaged">Damaged (Recycle)</option>
                <option value="New">Like New</option>
            </select>
            
            <label>I want to:</label>
            <select name="request_type">
                <option value="Donate">Donate</option>
                <option value="Sell">Sell</option>
            </select>
            
            <label>Upload Photo (Optional):</label>
            <input type="file" name="item_image" accept="image/*">

            <label>Pickup Date:</label>
            <input type="date" name="pickup_date" required>
            
            <button type="submit" name="submit">Submit Request</button>
        </form>
    </div>

    <div class="container">
        <h3>Request History (Donations/Sales)</h3>
        <table>
            <thead>
                <tr>
                    <th>Image</th><th>Item</th><th>Type</th><th>Status</th><th>Date</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM Donation_Sell_Request WHERE customer_id='$cid' ORDER BY request_id DESC");
                if ($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        echo "<tr><td>";
                        if (!empty($row['image']) && file_exists($row['image'])) {
                            echo "<img src='" . $row['image'] . "' style='width:50px; height:50px; object-fit:cover; border-radius:4px;'>";
                        } else { echo "<span style='color:#ccc; font-size:12px;'>No Image</span>"; }
                        echo "</td>
                              <td>{$row['item_type']}</td>
                              <td>{$row['request_type']}</td>
                              <td>{$row['status']}</td>
                              <td>{$row['pickup_date']}</td>
                              <td>";
                        if($row['status'] == 'Pending') {
                            echo "<form method='post' onsubmit='return confirm(\"Are you sure?\");'>
                                    <input type='hidden' name='req_id' value='{$row['request_id']}'>
                                    <button type='submit' name='delete' class='del-btn'>Delete</button>
                                  </form>";
                        } else { echo "Locked"; }
                        echo "</td></tr>";
                    }
                } else { echo "<tr><td colspan='6'>No requests found.</td></tr>"; }
                ?>
            </tbody>
        </table>
    </div>

    <div class="container">
        <h3>My Orders (Purchases)</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_orders->num_rows > 0) {
                    while($order = $result_orders->fetch_assoc()) {
                        echo "<tr>
                                <td>";
                                if (!empty($order['image']) && file_exists($order['image'])) {
                                    echo "<img src='{$order['image']}' style='width:50px; height:50px; object-fit:cover; border-radius:4px;'>";
                                } else { echo "No Image"; }
                        echo "  </td>
                                <td>{$order['product_name']}</td>
                                <td>\${$order['price']}</td>
                                <td>{$order['order_date']}</td>
                                <td>
                                    <span style='font-weight:bold; padding:4px 8px; border-radius:4px; 
                                        background-color:" . ($order['status']=='Shipped' ? '#c8e6c9' : '#fff9c4') . "; 
                                        color:" . ($order['status']=='Shipped' ? '#2e7d32' : '#f57f17') . ";'>
                                        {$order['status']}
                                    </span>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>You haven't purchased anything yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>