<?php require 'admin_dashboard_logic.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <header><h1>Admin Dashboard</h1><nav><a href="logout.php">Logout</a></nav></header>
    <div class="container">
        
        <div class="dashboard-section" style="background: #e3f2fd; text-align: center;">
            <h2 style="margin:0; color:#0d47a1;">Total Revenue</h2>
            <h1 style="margin:10px 0; font-size: 3rem; color:#2E7D32;">$<?php echo number_format($revenue, 2); ?></h1>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
            <div class="dashboard-section">
                <h3>Inventory</h3>
                <ul><?php while($row=$result_stats->fetch_assoc()) echo "<li><b>{$row['category']}:</b> {$row['count']} Items</li>"; ?></ul>
            </div>
            <div class="dashboard-section">
                <h3>Audit Logs</h3>
                <ul style="font-size:0.9em; color:#555;">
                <?php while($log=$result_logs->fetch_assoc()) echo "<li style='border-bottom:1px solid #eee; padding:5px;'><b>{$log['action_type']}</b>: {$log['description']}</li>"; ?>
                </ul>
            </div>
        </div>

        <div class="dashboard-section">
            <h3>Add Driver</h3>
            <form method="post" style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr auto; gap:10px;">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="contact" placeholder="Phone" required>
                <input type="password" name="password" placeholder="Pass" required>
                <button type="submit" name="add_driver">Add</button>
            </form>
        </div>

        <div class="dashboard-section" style="background:#e8f5e9;">
            <h3>Add Product</h3>
            <form method="post" enctype="multipart/form-data" style="display:grid; grid-template-columns:1fr 1fr auto; gap:10px;">
                <input type="text" name="p_name" placeholder="Name" required>
                <input type="number" name="p_price" placeholder="Price" step="0.01" required>
                <input type="text" name="p_cat" placeholder="Category" required>
                <input type="file" name="p_image" accept="image/*" required>
                <input type="text" name="p_desc" placeholder="Desc" style="grid-column:span 2;" required>
                <button type="submit" name="add_product" style="grid-column:span 2;">Upload</button>
            </form>
        </div>

        <div class="dashboard-section">
            <h3>Pickups & Assignments</h3>
            <?php
            $res = $conn->query("SELECT r.*, d.name as driver_name FROM Donation_Sell_Request r LEFT JOIN Driver d ON r.assigned_driver_id=d.driver_id ORDER BY request_id DESC LIMIT 5");
            
            echo "<table><tr><th>ID</th><th>Status</th><th>Driver</th><th>Action</th></tr>";
            
            while($row=$res->fetch_assoc()){
                // Dropdown of free drivers
                $drivers=""; 
                $dres=$conn->query("SELECT * FROM Driver WHERE availability=1");
                while($d=$dres->fetch_assoc()) $drivers.="<option value='{$d['driver_id']}'>ID:{$d['driver_id']} - {$d['name']}</option>";
                
                echo "<tr>
                        <td>{$row['request_id']}</td>
                        <td>{$row['status']}</td>
                        <td>".($row['driver_name'] ?? 'None')."</td>
                        <td>";
                
                // --- NEW LOGIC START ---
                if($row['status'] == 'Pending') {
                    // 1. PENDING: Show Assign Form
                    echo "<form method='post' style='display:flex;gap:5px;'>
                            <input type='hidden' name='request_id' value='{$row['request_id']}'>
                            <select name='driver_id' required><option value=''>Select Driver</option>$drivers</select>
                            <button name='assign_pickup_driver'>Assign</button>
                          </form>";

                } elseif ($row['status'] == 'Scheduled') {
                    // 2. SCHEDULED: Show Remove/Change Option
                    echo "<form method='post' onsubmit='return confirm(\"Change driver? This will reset request to Pending.\");'>
                            <input type='hidden' name='request_id' value='{$row['request_id']}'>
                            <input type='hidden' name='assigned_driver_id' value='{$row['assigned_driver_id']}'>
                            <button name='remove_pickup_driver' style='background:#f44336; color:white; border:none; padding:5px 10px; border-radius:3px; cursor:pointer;'>Remove / Change</button>
                          </form>";

                } else {
                    // 3. PICKED UP / COMPLETED: Show Nothing or 'Done'
                    echo "<span style='color:green; font-weight:bold;'>Completed</span>";
                }
                // --- NEW LOGIC END ---

                echo "</td></tr>";
            }
            echo "</table>";
            ?>
        </div>

        <div class="dashboard-section">
            <h3>Deliveries</h3>
            <table><thead><tr><th>Order</th><th>Status</th><th>Assign</th></tr></thead><tbody>
            <?php
            $orders = $conn->query("SELECT * FROM Orders WHERE status='Processing'");
            while($o=$orders->fetch_assoc()){
                $drivers=""; $dres=$conn->query("SELECT * FROM Driver WHERE availability=1");
                while($d=$dres->fetch_assoc()) $drivers.="<option value='{$d['driver_id']}'>ID:{$d['driver_id']} - {$d['name']}</option>";
                echo "<tr><td>{$o['order_id']}</td><td>{$o['status']}</td><td><form method='post' style='display:flex;gap:5px;'><input type='hidden' name='order_id' value='{$o['order_id']}'><select name='driver_id' required><option value=''>Select</option>$drivers</select><button name='assign_delivery_driver'>Assign</button></form></td></tr>";
            }
            ?>
            </tbody></table>
        </div>

        <div class="dashboard-section">
            <h3>Driver Availability Monitor</h3>
            <div style="display: flex; gap: 20px;">
                
                <div style="flex: 1; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; background: #f0fff4;">
                    <h4 style="color: green; margin-top: 0;">✅ Available Drivers</h4>
                    <ul style="list-style: none; padding: 0;">
                    <?php 
                    if($drivers_free->num_rows > 0) {
                        while($d = $drivers_free->fetch_assoc()) {
                            echo "<li style='padding:5px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center;'>
                                    <span><b>ID: {$d['driver_id']} - {$d['name']}</b> ({$d['contact']})</span>
                                    <form method='post' onsubmit='return confirm(\"Delete this driver?\");' style='margin:0;'>
                                        <input type='hidden' name='driver_id' value='{$d['driver_id']}'>
                                        <button type='submit' name='delete_driver' style='background:#ff4444; color:white; border:none; padding:3px 8px; cursor:pointer; font-weight:bold; border-radius:3px;'>X</button>
                                    </form>
                                  </li>";
                        }
                    } else { echo "<li>No drivers available.</li>"; }
                    ?>
                    </ul>
                </div>

                <div style="flex: 1; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; background: #fff5f5;">
                    <h4 style="color: red; margin-top: 0;">⛔ Busy Drivers</h4>
                    <ul style="list-style: none; padding: 0;">
                    <?php 
                    if($drivers_busy->num_rows > 0) {
                        while($d = $drivers_busy->fetch_assoc()) {
                            echo "<li style='padding:5px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center;'>
                                    <span><b>ID: {$d['driver_id']} - {$d['name']}</b> ({$d['contact']})</span>
                                    <form method='post' onsubmit='return confirm(\"Delete this driver?\");' style='margin:0;'>
                                        <input type='hidden' name='driver_id' value='{$d['driver_id']}'>
                                        <button type='submit' name='delete_driver' style='background:#ff4444; color:white; border:none; padding:3px 8px; cursor:pointer; font-weight:bold; border-radius:3px;'>X</button>
                                    </form>
                                  </li>";
                        }
                    } else { echo "<li>All drivers are free.</li>"; }
                    ?>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</body>
</html>