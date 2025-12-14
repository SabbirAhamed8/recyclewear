<?php require 'driver_dashboard_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Driver Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="driver_dashboard.css">
</head>
<body>
    <header>
        <h1>Driver Dashboard</h1>
        <div style="color:white;">Welcome, <?php echo $_SESSION['name']; ?> | <a href="logout.php" style="color:white;">Logout</a></div>
    </header>

    <div class="container">
        <?php if($message) echo "<div class='alert'>$message</div>"; ?>

        <h3>Task 1: Pickups (Donations)</h3>
        <table>
            <thead><tr><th>ID</th><th>Address</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php
                $sql = "SELECT r.*, c.address, c.phone FROM Donation_Sell_Request r JOIN Customer c ON r.customer_id=c.customer_id WHERE r.assigned_driver_id='$driver_id' AND r.status='Scheduled'";
                $res = $conn->query($sql);
                if($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        echo "<tr>
                            <td>#{$row['request_id']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                    <button type='submit' name='complete_pickup' class='action-btn'>Mark Picked Up</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else { echo "<tr><td colspan='5'>No active pickups.</td></tr>"; }
                ?>
            </tbody>
        </table>

        <h3>Task 2: Deliveries (Orders)</h3>
        <table>
            <thead><tr><th>Delivery ID</th><th>Address</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php
                $sql_del = "SELECT d.*, c.address, c.phone FROM Delivery d JOIN Orders o ON d.order_id = o.order_id JOIN Customer c ON o.customer_id = c.customer_id WHERE d.driver_id='$driver_id' AND d.status != 'Delivered'";
                $res_del = $conn->query($sql_del);
                if($res_del->num_rows > 0) {
                    while($row = $res_del->fetch_assoc()) {
                        echo "<tr>
                            <td>#{$row['delivery_id']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='delivery_id' value='{$row['delivery_id']}'>
                                    <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                    <button type='submit' name='complete_delivery' class='action-btn'>Mark Delivered</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else { echo "<tr><td colspan='4'>No active deliveries.</td></tr>"; }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>