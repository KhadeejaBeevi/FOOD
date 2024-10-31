<?php
// Start session
session_start();

// Include database configuration
include('config.php');

// Check if the user is logged in as admin/staff
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$error = '';
$success = '';

// Handle update delivery status request
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['delivery_status'];

    $query = "UPDATE orders SET delivery_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $new_status, $order_id);

    if ($stmt->execute()) {
        $success = "Delivery status updated successfully!";
    } else {
        $error = "Error updating the delivery status. Please try again.";
    }
}

// Fetch all orders
$query = "SELECT o.order_id, o.quantity, o.total_price, o.delivery_status, o.order_date, f.food_name, u.username
          FROM orders o
          JOIN food_items f ON o.food_id = f.food_id
          JOIN users u ON o.user_id = u.user_id
          ORDER BY o.order_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Delivery Status - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .update-delivery-container {
            max-width: 900px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .update-delivery-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .btn {
            background-color: #ff5722;
            color: white;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #e64a19;
        }
        .error {
            color: red;
            font-size: 0.9rem;
            text-align: center;
        }
        .success {
            color: green;
            font-size: 0.9rem;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="update-delivery-container">
    <h3>Update Delivery Status</h3>

    <!-- Error message -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Success message -->
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Food Item</th>
                <th>Quantity</th>
                <th>Total Price (₹)</th>
                <th>Current Status</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['food_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo number_format($row['total_price'], 2); ?></td>
                    <td><?php echo $row['delivery_status']; ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="delivery_status" class="form-control">
                                <option value="Pending" <?php echo $row['delivery_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="On the Way" <?php echo $row['delivery_status'] == 'On the Way' ? 'selected' : ''; ?>>On the Way</option>
                                <option value="Delivered" <?php echo $row['delivery_status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="Cancelled" <?php echo $row['delivery_status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-primary mt-2">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
