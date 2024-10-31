<?php
session_start();
include('AdminNav.php'); // Include admin navigation (if applicable)

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables for messages
$status_update_success = '';
$status_update_error = '';

// Handle the status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    // Update the order status in the database
    $query = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $con->prepare($query);
    if ($stmt) {
        $stmt->bind_param("si", $new_status, $order_id);
        if ($stmt->execute()) {
            $status_update_success = "Order status updated successfully!";
        } else {
            $status_update_error = "Failed to update order status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $status_update_error = "Failed to prepare the statement: " . $con->error;
    }
}

// Fetch all orders for display
$order_query = "SELECT * FROM orders";
$result = mysqli_query($con, $order_query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Change - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .order-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .order-title {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        footer {
            background-color: #ff5722;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="order-container">
    <h3 class="order-title">Change Order Status</h3>
    
    <?php if ($status_update_success): ?>
        <p class='alert alert-success'><?= $status_update_success ?></p>
    <?php endif; ?>
    
    <?php if ($status_update_error): ?>
        <p class='alert alert-danger'><?= $status_update_error ?></p>
    <?php endif; ?>

    <h4>All Orders:</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Item ID</th>
                <th>Total Price</th>
                <th>Delivery Address</th>
                <th>Payment Method</th>
                <th>Contact Number</th>
                <th>Order Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['item_id']) ?></td>
                    <td>₹<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                    <td><?= htmlspecialchars($order['payment_method']) ?></td>
                    <td><?= htmlspecialchars($order['contact_number']) ?></td>
                    <td><?= htmlspecialchars($order['order_status']) ?></td>
                    <td>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                            <select name="order_status" class="form-control mr-2" required>
                                <option value="Pending" <?= $order['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Completed" <?= $order['order_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="Cancelled" <?= $order['order_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
