<?php
session_start();
include('UserNav.php');

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables for messages
$order_success = '';
$order_error = '';

// Ensure the cart is initialized
if (!isset($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = []; // Initialize as an empty array
}

// Debugging: Print current cart contents
echo "<pre>";
print_r($_SESSION['cart_id']);
echo "</pre>";

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the cart exists and is an array
    if (is_array($_SESSION['cart_id']) && count($_SESSION['cart_id']) > 0) {
        $cart = $_SESSION['cart_id'];
        $payment_method = $_POST['payment_method'];
        $delivery_address = $_POST['delivery_address'];
        $contact = $_POST['contact'];

        // Loop through each item in the cart and place the order
        foreach ($cart as $item) {
            // Calculate total price for each item based on quantity
            $total_price = $item['price'] * $item['quantity'];

            // Insert order details into the database
            $query = "INSERT INTO orders (item_id, quantity, total_price, delivery_address, payment_method, contact_number) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            if ($stmt) {
                $stmt->bind_param("iissss", $item['id'], $item['quantity'], $total_price, $delivery_address, $payment_method, $contact);
                if ($stmt->execute()) {
                    $order_success = "Order placed successfully!";
                } else {
                    $order_error = "Failed to execute query: " . $stmt->error;
                }
            } else {
                $order_error = "Failed to prepare the statement: " . $con->error;
            }
        }

        // Clear cart session after order
        unset($_SESSION['cart_id']);
    } else {
        $order_error = "Your cart is empty. Please add items before placing an order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - QUICKBITE</title>
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
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #ff5722;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e64a19;
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
    <h3 class="order-title">Place Order</h3>
    
    <?php if ($order_success) echo "<p class='alert alert-success'>$order_success</p>"; ?>
    <?php if ($order_error) echo "<p class='alert alert-danger'>$order_error</p>"; ?>

    <?php if (count($_SESSION['cart_id']) > 0): ?>
        <h4>Your Cart:</h4>
        <ul class="list-group">
            <?php foreach ($_SESSION['cart_id'] as $item): ?>
                <li class="list-group-item">
                    Item ID: <?= htmlspecialchars($item['id']) ?> | Quantity: <?= htmlspecialchars($item['quantity']) ?> | Price: $<?= htmlspecialchars($item['price']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <form method="POST" class="container mt-4">
            <div class="form-group">
                <label for="delivery_address">Delivery Address:</label>
                <input type="text" name="delivery_address" id="delivery_address" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number:</label>
                <input type="text" name="contact" id="contact" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="Cash">Cash on Delivery</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="UPI">UPI</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty. Please add items before placing an order.</p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
