<?php
// Start session
session_start();
include('UserNav.php');

// Include database configuration
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

$order_status = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = trim($_POST['order_id']);

    // Check if order ID is provided
    if (!empty($order_id)) {
        // Fetch order details from the database based on the order ID
        $query = "SELECT order_status FROM orders WHERE order_id = ?";
        $stmt = $con->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param('i', $order_id); // Use 'i' for integer
            $stmt->execute();
            $result = $stmt->get_result();
            $order = $result->fetch_assoc();

            // Check if the order exists
            if ($order) {
                $order_status = $order['order_status']; // Correct column name
            } else {
                $error = "Order not found. Please check the Order ID.";
            }

            // Close the statement
            $stmt->close();
        } else {
            $error = "Failed to prepare the SQL statement: " . $con->error;
        }
    } else {
        $error = "Please enter your Order ID.";
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .track-order-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .track-order-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-group input {
            border-radius: 8px;
        }
        .btn {
            background-color: #ff5722;
            color: white;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #e64a19;
        }
        .order-status {
            text-align: center;
            margin-top: 20px;
            font-size: 1.1rem;
        }
        .order-status .status {
            font-weight: bold;
            color: #ff5722;
        }
        .error {
            color: red;
            font-size: 0.9rem;
            text-align: center;
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

<div class="track-order-container">
    <h3>Track Your Order</h3>

    <!-- Error message -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Order tracking form -->
    <form method="POST" action="TrackOrder.php">
        <div class="form-group">
            <label for="order_id">Enter Order ID</label>
            <input type="text" name="order_id" id="order_id" class="form-control" placeholder="Order ID" required>
        </div>
        <button type="submit" class="btn btn-block">Track Order</button>
    </form>

    <!-- Display order status -->
    <?php if ($order_status): ?>
        <div class="order-status">
            <p>Your order status is: <span class="status"><?php echo htmlspecialchars($order_status); ?></span></p>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
